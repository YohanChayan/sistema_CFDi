<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\SatProduct;
use App\Models\State;
use App\Models\ZipCode;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

use Devtronic\TreeClassifier\DecisionNode;
use Devtronic\TreeClassifier\RootNode;
use Devtronic\TreeClassifier\TerminalNode;

class QuoteController extends Controller
{
    public function index() {
        $states = State::all();
        return view('app.admin.quotes.index')->with('states', $states);
    }

    public function infer(Request $request) {
        $product = $request->get('product');
        $budget = $request->get('budget');
        $filter = $request->get('filter');
        $location = $request->get('location');

        // Buscar los nombres de los productos del sat que incluyan el nombre dado por el usuario
        $satProducts = SatProduct::where('name', 'LIKE', '%' . $product . '%')->get();
        $satProductsIds = [];
        foreach($satProducts as $satProduct) {
            array_push($satProductsIds, $satProduct->id);
        }

        // Buscar en las facturas los nombres de los productos que incluyan el nombre dado por el usuario
        $invoiceProducts = InvoiceDetail::with('invoice', 'invoice.provider', 'sat_product')->where('name', 'LIKE', '%' . $product . '%')->get();

        // Obtener los productos del sat incluidos en las facturas
        $satInvoiceProducts = InvoiceDetail::with('invoice', 'invoice.provider', 'sat_product')->whereIn('sat_product_id', $satProductsIds)->get();

        $products = $invoiceProducts->union($satInvoiceProducts);   // Unir ambos resultados
        $products = $products->sortBy([['created_at', 'desc'], ['name', 'asc']]);   // Ordenar los productos por fecha y nombre
        $productsCount = $products->groupBy('name');   // Obtener cuantas veces se repite cada producto
        $products = $products->unique('name');   // Descartar productos repetidos
        //dd($productsCount, $products);

        $subjects = [];   // Guarda los productos en un arreglo para su proceso en el árbol de decisión

        // Proceso de organización de los datos
        foreach($products as $product) {
            foreach($productsCount as $key => $productCount) {
                if($product->name == $key) {
                    $monthlySales = 0;
                    $state = '';

                    // Obtener las ventas mensuales actuales del producto
                    foreach($productCount as $productC) {
                        if(date('m', strtotime($productC->created_at)) == date('m', strtotime(now()))) {
                            $monthlySales++;
                        }
                    }

                    // Obtener la información del lugar a partir del código postal
                    $zip_code = ZipCode::where('zip_code', $product->invoice->zip_code)->first();

                    array_push($subjects, [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'totalSales' => count($productCount),
                        'monthlySales' => $monthlySales,
                        'state' => $zip_code->state,
                        'fecha_en_stock' => $product->created_at->format('Y-m-d'),
                    ]);

                    break;
                }
            }
        }

        // dd($subjects);

        // state indetifier (name)
        $_targetState = $location;

        // We want all subjects who IS IN THAT STATE
        $stateDecisions = [
            strval($_targetState) => new TerminalNode(),  //selected state name
            'other state' => new TerminalNode(), // This is our last node
        ];

        // Create the decider for state
        $stateDecider = new DecisionNode(function ($subject) use ($_targetState) {
            return ($subject['state'] == $_targetState ? strval($_targetState) : 'other state');
        }, $stateDecisions);

        // Great, next we need the filter-decisions.
        $_targetFilter = $filter; //Todos , Mas_Comprados , Novedades

        $filterDecisions = [
            strval($_targetFilter) => $stateDecider, // redirect all subjects WHICH FILTER MATCH THE SELECTED
            'other filter' => new TerminalNode(),
        ];

        $filterDecider = '';

        if($_targetFilter == 'Mas_Comprados'){

            $_sumTotalSales = 0;
            foreach($subjects as $s)
                $_sumTotalSales += $s['totalSales'];
            $_averageTotalSales = ceil($_sumTotalSales/count($subjects)); //round up integer  -ex, 0.6 = 1

            $filterDecider = new DecisionNode(function ($subject) use ($_averageTotalSales, $_targetFilter) {
                return ($subject['totalSales'] > $_averageTotalSales) ? strval($_targetFilter) : 'other filter'; //Apply filter
            }, $filterDecisions);



        }else if($_targetFilter == 'Novedades'){

            $_sumFechas_en_stocks = 0;
            foreach($subjects as $s)
                $_sumFechas_en_stocks += strtotime($s['fecha_en_stock']); //suma las fechas en timestamp

            $_averageFechas = $_sumTotalSales/count($subjects); //promedio de fechas

            $filterDecider = new DecisionNode(function ($subject) use($_averageFechas, $_targetFilter){
                return (strtotime($subject['fecha_en_stock']) > strtotime($_averageFechas) ) ? strval($_targetFilter) : 'other filter' ; //Apply filter
            }, $filterDecisions);

        }else{
            $filterDecider = new DecisionNode(function ($subject) use($_targetFilter) {
                return (!is_null($subject)) ? strval($_targetFilter) : 'other filter'; //Todos
            }, $filterDecisions);
        }
        // And now we need to create a RootNode
        $rootNode = new RootNode($subjects);

        // Add the first (last created): POR AHORA filterDecider
        $rootNode->addSubNode($filterDecider);

        // And classify
        $rootNode->classify();

        dd($filterDecisions[$_targetFilter]);

        return view('app.admin.quotes.infer')->with('products', $products);
    }
}
