<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\EstimateRepositoryInterface;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Interfaces\CustomerAssetRepositoryInterface;
use App\Models\Invoice;
use App\Models\Estimate;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    protected $view = 'admin.dashboard';
    protected $userRepository;
    protected $estimateRepository;
    protected $invoiceRepository;
    protected $customerAssetRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        EstimateRepositoryInterface $estimateRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        CustomerAssetRepositoryInterface $customerAssetRepository
    ){
        $this->userRepository = $userRepository;
        $this->estimateRepository = $estimateRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->customerAssetRepository = $customerAssetRepository;
    }

    public function dashboard()
    {
        // Revenue Tracking
        $monthlyRevenue = Invoice::whereMonth('syncro_invoice_date', now()->month)->whereYear('syncro_invoice_date', now()->year)->sum('syncro_invoice_total');
        $lastMonthRevenue = Invoice::whereMonth('syncro_invoice_date', now()->subMonth()->month)->whereYear('syncro_invoice_date', now()->subMonth()->year) ->sum('syncro_invoice_total');
        $revenueGrowth = $lastMonthRevenue > 0 ? round((($monthlyRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1) : 0;

        // Invoices Overview
        $totalInvoices = $this->invoiceRepository->count();
        $newInvoicesThisMonth = Invoice::whereMonth('syncro_invoice_date', now()->month)->whereYear('syncro_invoice_date', now()->year)->count();

        // Estimates Overview
        $totalEstimates = $this->estimateRepository->count();
        $newEstimatesThisMonth = Estimate::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count();

        // Customer Overview
        $totalCustomers = $this->userRepository->count(['user_type' => 'customer']);
        $newCustomersThisMonth = User::where('user_type', 'customer')
        ->whereMonth('created_at', now()->month)
        ->whereYear('created_at', now()->year)
        ->count();

        // Monthly Revenue Data for Chart (Last 12 months)
        $monthlyRevenueData = [];
        $monthLabels = [];
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = Invoice::whereMonth('syncro_invoice_date', $date->month)->whereYear('syncro_invoice_date', $date->year)->sum('syncro_invoice_total');
            $monthlyRevenueData[] = round($revenue, 2);
            $monthLabels[] = $date->format('M Y');
        }

        // Top Customers by Revenue
        $topCustomers = Invoice::with('customer')->select('customer_id', DB::raw('SUM(syncro_invoice_total) as total_revenue'))
        ->groupBy('customer_id')->orderBy('total_revenue', 'desc')->limit(5)
        ->get();

        // Estimates Status
        $estimateStatusData = [
            'Fresh' => $this->estimateRepository->count(['status' => 'Fresh']),
            'Approved' => $this->estimateRepository->count(['status' => 'Approved']),
            'Declined' => $this->estimateRepository->count(['status' => 'Declined']),
            'Draft' => $this->estimateRepository->count(['status' => 'Draft'])
        ];

        // Recent Customers
        $recentCustomers = $this->userRepository->find(['user_type' => 'customer'], 5);    
        return view($this->view, get_defined_vars());
    }
}
