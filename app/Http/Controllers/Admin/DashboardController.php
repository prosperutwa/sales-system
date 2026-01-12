<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Systems\BiovetTechUser;
use App\Models\Systems\BiovetTechProduct;
use App\Models\Systems\BiovetTechCustomer;
use App\Models\Systems\BiovetTechInvoice;
use App\Models\Systems\BiovetTechInvoiceItem;
use App\Models\Systems\BiovetTechPayment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
   public function dashboard(Request $request)
{
    $filter = $request->filter ?? 'all';
    $from   = $request->from;
    $to     = $request->to;

    /*
    |--------------------------------------------------------------------------
    | Date Filter Helper
    |--------------------------------------------------------------------------
    */
    $applyDateFilter = function ($query, $column) use ($filter, $from, $to) {

        if ($from && $to) {
            return $query->whereBetween($column, [$from, $to]);
        }

        if ($filter === 'today') {
            return $query->whereDate($column, today());
        }

        if ($filter === 'week') {
            return $query->whereBetween($column, [
                now()->startOfWeek(),
                now()->endOfWeek()
            ]);
        }

        if ($filter === 'month') {
            return $query->whereMonth($column, now()->month)
                         ->whereYear($column, now()->year);
        }

        if ($filter === 'year') {
            return $query->whereYear($column, now()->year);
        }

        return $query; // all
    };

    /*
    |--------------------------------------------------------------------------
    | USERS / PRODUCTS / CUSTOMERS (created_at)
    |--------------------------------------------------------------------------
    */
    $totalUsers = $applyDateFilter(
        BiovetTechUser::query(),
        'created_at'
    )->count();

    $totalProducts = $applyDateFilter(
        BiovetTechProduct::query(),
        'created_at'
    )->count();

    $totalCustomers = $applyDateFilter(
        BiovetTechCustomer::query(),
        'created_at'
    )->count();

    /*
    |--------------------------------------------------------------------------
    | INVOICES (invoice_date)
    |--------------------------------------------------------------------------
    */
    $invoiceQuery = $applyDateFilter(
        BiovetTechInvoice::where('status', 'paid'),
        'invoice_date'
    );

    $invoiceIds    = $invoiceQuery->pluck('auto_id');
    $totalInvoices = $invoiceIds->count();

    /*
    |--------------------------------------------------------------------------
    | PAYMENTS
    |--------------------------------------------------------------------------
    */
    $totalPayments = BiovetTechPayment::whereIn('invoice_id', $invoiceIds)
                        ->sum('amount_paid');

    /*
    |--------------------------------------------------------------------------
    | PROFIT
    |--------------------------------------------------------------------------
    */
    $totalProfit = BiovetTechInvoiceItem::join(
            'biovet_tech_products',
            'biovet_tech_invoice_items.product_id',
            '=',
            'biovet_tech_products.auto_id'
        )
        ->whereIn('biovet_tech_invoice_items.invoice_id', $invoiceIds)
        ->selectRaw(
            'SUM(
                (biovet_tech_invoice_items.unit_price - biovet_tech_products.buying_price)
                * biovet_tech_invoice_items.quantity
            ) as profit'
        )
        ->value('profit');

    /*
    |--------------------------------------------------------------------------
    | TOP PRODUCTS
    |--------------------------------------------------------------------------
    */
    $topProducts = BiovetTechInvoiceItem::join(
            'biovet_tech_products',
            'biovet_tech_invoice_items.product_id',
            '=',
            'biovet_tech_products.auto_id'
        )
        ->whereIn('biovet_tech_invoice_items.invoice_id', $invoiceIds)
        ->select(
            'biovet_tech_products.name',
            DB::raw('SUM(biovet_tech_invoice_items.quantity) as total_sold')
        )
        ->groupBy('biovet_tech_products.name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();

    /*
    |--------------------------------------------------------------------------
    | MONTHLY SALES (Chart)
    |--------------------------------------------------------------------------
    */
    $monthlySales = BiovetTechInvoice::whereIn('auto_id', $invoiceIds)
        ->selectRaw('MONTH(invoice_date) as month, SUM(total_amount) as total')
        ->groupBy('month')
        ->pluck('total', 'month');

    /*
    |--------------------------------------------------------------------------
    | RETURN VIEW
    |--------------------------------------------------------------------------
    */
    return view('templates.admin.dashboard', compact(
        'totalUsers',
        'totalProducts',
        'totalCustomers',
        'totalInvoices',
        'totalPayments',
        'totalProfit',
        'topProducts',
        'monthlySales'
    ));
}


}
