<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InvoiceDetail;
use App\Models\SatProduct;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class QuoteController extends Controller
{
    public function index() {
        return view('app.admin.quotes.index');
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

                    // Obtener las ventas mensuales actuales del producto
                    foreach($productCount as $productC) {
                        if(date('m', strtotime($productC->created_at)) == date('m', strtotime(now()))) {
                            $monthlySales++;
                        }
                    }

                    array_push($subjects, [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'totalSales' => count($productCount),
                        'monthlySales' => $monthlySales,
                    ]);

                    break;
                }
            }
        }
        
        dd($subjects);

        return view('app.admin.quotes.infer')->with('products', $products);
    }
}
