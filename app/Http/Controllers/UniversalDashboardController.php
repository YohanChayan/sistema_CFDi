<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Admin;
use App\Models\Invoice;
use App\Models\Provider;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class UniversalDashboardController extends Controller
{
    
    public function index(){

        $user_type = auth()->user()->type;

        if($user_type == 'A'){ //admin dashboard
            $invoices = Invoice::with('owner', 'provider')->get();

        // counts
        $providers_count = Provider::count();
        $invoices_count = count($invoices);
        $invoices_today = Invoice::whereDate('created_at', Carbon::today() )->count();
        $users_count = User::count();

        // Bar Chart
            /* sql query
                SELECT COUNT(*) AS facturas, p.nombre FROM `invoices` as i JOIN providers as p ON i.provider_id = p.id
                GROUP BY p.nombre
                ORDER BY facturas desc
                limit 5;
            */
        $providers_vs_invoices = DB::table('invoices as i')
            ->join('providers as p', 'p.id', '=', 'i.provider_id')
            ->select(DB::raw('COUNT(*) AS facturas'),'p.nombre as nombre_proveedor')
            ->groupBy('p.nombre')
            ->orderBy('facturas', 'desc')
            ->get()
            ->toArray();

        // Line Chart
            /* sql query
                SELECT COUNT(*) AS facturas, MONTH(created_at) as mes FROM invoices where YEAR(created_at) = YEAR(CURDATE());
            */
        $invoices_vs_months = Invoice::select(DB::raw('COUNT(*) AS facturas'),DB::raw('MONTH(created_at) AS mes') )
            ->whereRaw('YEAR(created_at) = YEAR(CURDATE())')
            ->groupBy('mes')
            ->get()
            ->toArray();

        $arr = [];
        for($i = 0; $i < count($invoices_vs_months); $i++){ //agg los existentes (index = mes) y (content = facturas)
            $itemArray = $invoices_vs_months[$i];
            $arr[$itemArray['mes'] - 1 ] = $itemArray['facturas'];
        }
        for($i = 0; $i < 12; $i++){ // agg los inexistentes
            if( !array_key_exists($i, $arr ) )
                $arr[$i] = 0;
        }
        ksort($arr); //ordena los index tal que enero=0, feb=1, etc.

        return view('app.admin.admin-index')
        ->with('invoices', $invoices)
        ->with('providers_count', $providers_count)
        ->with('invoices_count', $invoices_count)
        ->with('invoices_today', $invoices_today)
        ->with('users_count', $users_count)
        ->with('providers_vs_invoices', $providers_vs_invoices)
        ->with('invoices_vs_months', $invoices_vs_months)
        ->with('invoices_vs_months', $arr)
        ;

        }else if($user_type == 'P'){ //provider dashboard
            return view('home');
        }


    }
}
