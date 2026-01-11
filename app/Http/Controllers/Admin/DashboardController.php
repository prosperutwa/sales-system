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
    public function dashboard()
    {
        $totalUsers     = BiovetTechUser::count();
        $totalProducts  = BiovetTechProduct::count();
        $totalCustomers = BiovetTechCustomer::count();
        $totalInvoices  = BiovetTechInvoice::count();
        $totalPayments  = BiovetTechPayment::sum('amount_paid');

        $totalProfit = BiovetTechInvoiceItem::join('biovet_tech_invoices', 'biovet_tech_invoice_items.invoice_id', '=', 'biovet_tech_invoices.auto_id')
        ->join('biovet_tech_products', 'biovet_tech_invoice_items.product_id', '=', 'biovet_tech_products.auto_id')
        ->where('biovet_tech_invoices.status', 'paid') 
        ->selectRaw('SUM((biovet_tech_invoice_items.unit_price - biovet_tech_products.buying_price) * biovet_tech_invoice_items.quantity) as profit')
        ->value('profit');

        $topProducts = BiovetTechInvoiceItem::join('biovet_tech_invoices', 'biovet_tech_invoice_items.invoice_id', '=', 'biovet_tech_invoices.auto_id')
        ->join('biovet_tech_products', 'biovet_tech_invoice_items.product_id', '=', 'biovet_tech_products.auto_id')
        ->where('biovet_tech_invoices.status', 'paid')
        ->select(
            'biovet_tech_products.name',
            DB::raw('SUM(biovet_tech_invoice_items.quantity) as total_sold')
        )
        ->groupBy('biovet_tech_products.name')
        ->orderByDesc('total_sold')
        ->limit(5)
        ->get();

        $monthlySales = BiovetTechInvoice::selectRaw("MONTH(invoice_date) as month, SUM(total_amount) as total")
        ->groupBy('month')
        ->orderBy('month')
        ->where('status','paid')
        ->pluck('total', 'month');

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
