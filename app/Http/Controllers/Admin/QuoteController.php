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
        $products = $products->unique('name');   // Descartar productos repetidos

        return view('app.admin.quotes.infer')->with('products', $products);
    }
}
