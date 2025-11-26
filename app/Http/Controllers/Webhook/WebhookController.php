<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\EstimateRepositoryInterface;
use App\Traits\Eset;
use App\Interfaces\InvoiceRepositoryInterface;

class WebhookController extends Controller
{
    use Eset;
    protected $estimateRepository;
    protected $invoiceRepository;

    public function __construct(
        EstimateRepositoryInterface $estimateRepository,
        InvoiceRepositoryInterface $invoiceRepository
    ){
        $this->estimateRepository = $estimateRepository;
        $this->invoiceRepository = $invoiceRepository;
    }

    public function approveEstimate(Request $request){
        try{
            Log::info('Syncro Webhook Request: ' . json_encode($request->all()));
            $estimate = $this->estimateRepository->findOne(['syncro_estimate_id' => $request->estimate_id]);
            if(!$estimate){
                Log::error('Estimate not found: ' . $request->estimate_id);
                return;
            }

            // Create ESET Customer
            if(!$estimate?->customer?->eset_company_id){
                $esetCustomerResponse = $this->esetPost('/Company/Create/customer', [
                    "name" => $estimate?->customer?->first_name . ' ' . $estimate?->customer?->last_name,
                    "customIdentifier" => "Syncro-" . $estimate?->customer?->syncro_customer_id,
                    "email" => $estimate?->customer?->email
                ]);
    
                Log::info('Create ESET Customer Response: ' . json_encode($esetCustomerResponse));
                if(!$esetCustomerResponse || !isset($esetCustomerResponse['companyId'])){
                    Log::error('Failed to create ESET Customer: ' . $request->estimate_id);
                    return;
                }

                $estimate->customer->update(['eset_company_id' => $esetCustomerResponse['companyId']]);
            }

            // Order License
            $orderLicenseResponse = $this->esetPost('/License/Order', [
                "quantity" => 1,
                "productCode" => 2015,
                "customerId" => $estimate->customer->eset_company_id,
                "licenseType" => 1
            ]);

            Log::info('Order License Response: ' . json_encode($orderLicenseResponse));
            if(!$orderLicenseResponse){
                Log::error('Failed to order license: ' . $request->estimate_id);
                return;
            }

            $invoiceResponse = $this->invoiceRepository->create([
                'syncro_estimate_id' => $request->estimate_id,
                'eset_license_key' => $orderLicenseResponse['publicLicenseKey']
            ]);

            if(!$invoiceResponse['status']){
                Log::error('Failed to create invoice: ' . $request->estimate_id);
                return;
            }

            Log::info('Approve Estimate Webhook completed successfully');
        } catch (\Exception $e) {
            Log::error('Approve Estimate Webhook Error: ' . $e->getMessage());
        }
    }
}
