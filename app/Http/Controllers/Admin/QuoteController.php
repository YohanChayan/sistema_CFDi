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
        $product = $request->get('product');     // Nombre del producto
        $budget = $request->get('budget');       // Presupuesto
        $filter = $request->get('filter');       // Todos, Más Comprados, Novedades
        $location = $request->get('location');   // Estado seleccionado

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

        if(count($products) > 0) {
            $subjects = [];   // Guarda los productos en un arreglo para su proceso en el árbol de decisión

            // Proceso de organización de los datos
            foreach($products as $product) {
                foreach($productsCount as $key => $productCount) {
                    if($product->name == $key) {
                        $monthlySales = 0;

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
                            'stockDate' => $product->created_at->format('Y-m-d'),
                        ]);

                        break;
                    }
                }
            }

            /**************************************************/
            /*                   Ubicación                    */
            /**************************************************/

            // Posibles decisiones para los estados
            $stateDecisions = [
                strval($location) => new TerminalNode(),   // Estado seleccionado
                'Other State' => new TerminalNode(),       // Otros estados
            ];

            // Crear el nodo de decisión para el estado y asignarlo a los nodos terminales
            $stateDecider = new DecisionNode(function ($subject) use ($location) {
                if($location == 'Todos')
                    return (!is_null($subject['state']) ? strval($location) : 'Other State');
                else
                    return ($subject['state'] == $location ? strval($location) : 'Other State');
            }, $stateDecisions);

            // Posibles decisiones para el filtro (todos, más comprados, novedades) y asignarlo a los nodos terminales
            $filterDecisions = [
                strval($filter) => $stateDecider,          // Filtro seleccionado
                'Other Filter' => new TerminalNode(),      // Otros filtros
            ];

            /**************************************************/
            /*                     Filtro                     */
            /**************************************************/

            $filterDecider = '';   // Nodo de decisión para el tipo de filtro seleccionado por el usuario

            if($filter == 'Mas_Comprados') {

                $sumTotalSales = 0;   // Total de ventas de todos los productos
                $contSales = 0;       // Contador para descartar ventas según el presupuesto
                foreach($subjects as $subject) {
                    if($subject['price'] <= $budget) {
                        $sumTotalSales += $subject['totalSales'];
                        $contSales++;
                    }
                }
                $averageTotalSales = ceil($sumTotalSales/$contSales);   // Promedio de ventas, se redondea el entero hacia arriba

                // Se realiza la decisión de acuerdo al premio obtenido de sumar todas la ventas totales
                $filterDecider = new DecisionNode(function ($subject) use ($averageTotalSales, $filter) {
                    return ($subject['totalSales'] >= $averageTotalSales) ? strval($filter) : 'Other Filter';
                }, $filterDecisions);
                
            } else if($filter == 'Novedades') {
                
                $sumTotalSales = 0;    // Total de ventas de todos los productos
                $salesDates = [];      // Contador para descartar ventas según el presupuesto
                foreach($subjects as $subject) {
                    if($subject['price'] <= $budget) {
                        $salesDates[] = $subject['stockDate'];
                    }
                }

                // Se toma en cuenta la primera fecha (están ordenadas de forma descendente)
                $lastDate = explode('-', strval(trim($salesDates[0])));
                $lastYear = intval($lastDate[0]);
                $lastMonth = intval($lastDate[1]);
                
                $filterDecider = new DecisionNode(function ($subject) use($lastYear, $lastMonth, $filter) {
                    $tempDate = explode('-', strval(trim($subject['stockDate'])));
                    $tempYear = intval($tempDate[0]);
                    $tempMonth = intval($tempDate[1]);

                    if($tempYear == $lastYear && $tempMonth == $lastMonth)
                        return strval($filter);
                    else
                        return 'Other Filter';
                }, $filterDecisions);

            } else {   // Todos

                $filterDecider = new DecisionNode(function ($subject) use($filter) {
                    return (!is_null($subject)) ? strval($filter) : 'Other Filter';
                }, $filterDecisions);
                
            }

            /**************************************************/
            /*                  Presupuesto                   */
            /**************************************************/

            // Posibles decisiones para el presupuesto (budget) y asignarlo a los nodos terminales
            $budgetDecisions = [
                strval($budget) => $filterDecider,          // Presupuesto
                'Its expensive' => new TerminalNode(),      // Otros filtros
            ];

            // Crear el nodo de decisión para el presupuesto y asignarlo a los nodos terminales
            $budgetDecider = new DecisionNode(function ($subject) use ($budget) {
                return ($subject['price'] <= $budget ? strval($budget) : 'Its expensive');
            }, $budgetDecisions);
            
            // Creamos el nodo raíz
            $rootNode = new RootNode($subjects);

            // Add the first (last created): POR AHORA budgetDecider
            $rootNode->addSubNode($budgetDecider);

            // Clasificar el árbol de decisión
            $rootNode->classify();

            // Obtiene el resultado de la inferencia
            $result = $stateDecisions[strval($location)]->getSubjects();

            // Obtener los id's del resultado obtenido
            $ids = [];
            foreach($result as $r) {
                array_push($ids, $r['id']);
            }

            $products = InvoiceDetail::with('sat_product')->whereIn('id', $ids)->get();
        }

        $zip_codes = ZipCode::all();

        return view('app.admin.quotes.infer')->with('products', $products)->with('zip_codes', $zip_codes);
    }
}