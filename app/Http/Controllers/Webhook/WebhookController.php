<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Interfaces\EstimateRepositoryInterface;
use App\Traits\Eset;
use App\Interfaces\InvoiceRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\SyncroProductRepositoryInterface;
class WebhookController extends Controller
{
    use Eset;
    protected $estimateRepository;
    protected $invoiceRepository;
    protected $userRepository;
    protected $syncroProductRepository;

    public function __construct(
        EstimateRepositoryInterface $estimateRepository,
        InvoiceRepositoryInterface $invoiceRepository,
        UserRepositoryInterface $userRepository,
        SyncroProductRepositoryInterface $syncroProductRepository
    ){
        $this->estimateRepository = $estimateRepository;
        $this->invoiceRepository = $invoiceRepository;
        $this->userRepository = $userRepository;
        $this->syncroProductRepository = $syncroProductRepository;
    }

    public function approveEstimate(Request $request){
        try{
            Log::info('Approve Estimate Webhook Request: ' . json_encode($request->all()));
            $estimate = $this->estimateRepository->findOne(['syncro_estimate_id' => $request->estimate_id]);
            if(!$estimate){
                Log::error('Estimate not found: ' . $request->estimate_id);
                return response()->json(['status' => false, 'message' => 'Estimate not found'], 404);
            }

            if($estimate->status == 'Approved'){
				Log::error('Estimate already approved: ' . $request->estimate_id);
                return response()->json(['status' => false, 'message' => 'Estimate already approved'], 400);
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
                    return response()->json(['status' => false, 'message' => 'Failed to create ESET Customer'], 500);
                }

                $estimate->customer->update(['eset_company_id' => $esetCustomerResponse['companyId']]);
            }

            // Order License
            $orderLicenseResponse = $this->esetPost('/License/Order', [
                "quantity" => 1,
                "productCode" => $estimate?->syncroProduct?->esetProduct?->eset_product_code,
                "customerId" => $estimate->customer->eset_company_id,
                "licenseType" => 1
            ]);

            Log::info('Order License Response: ' . json_encode($orderLicenseResponse));
            if(!$orderLicenseResponse){
                Log::error('Failed to order license: ' . $request->estimate_id);
                return response()->json(['status' => false, 'message' => 'Failed to order license'], 500);
            }

            $invoiceResponse = $this->invoiceRepository->create([
                'syncro_estimate_id' => $request->estimate_id,
                'eset_license_key' => $orderLicenseResponse['publicLicenseKey']
            ]);

            if(!$invoiceResponse['status']){
                Log::error('Failed to create invoice: ' . $request->estimate_id);
                return response()->json(['status' => false, 'message' => 'Failed to create invoice'], 500);
            }

            Log::info($request->estimate_id . ' - Approve Estimate Webhook completed successfully');
            return response()->json(['status' => true, 'message' => 'Estimate approved successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Approve Estimate Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to approve estimate'], 500);
        }
    }

    public function addAsset(Request $request){
        try{
            Log::info('Add Asset Webhook Request: ' . json_encode($request->all()));
            if(!$request->asset || !isset($request->asset['customer_id'])){
                Log::error('Invalid asset data: ' . json_encode($request->all()));
                return response()->json(['status' => false, 'message' => 'Invalid asset data'], 400);
            }

            $customer = $this->userRepository->findOne(['syncro_customer_id' => $request->asset['customer_id']]);
            if(!$customer){
                Log::error('Customer not found: ' . $request->asset['customer_id']);
                return response()->json(['status' => false, 'message' => 'Customer not found'], 404);
            }

            $syncroProduct = $this->syncroProductRepository->findOne(['billing_type' => $customer->billing_type]);
            if(!$syncroProduct){
                Log::error('Syncro product not found: ' . $customer->billing_type);
                return response()->json(['status' => false, 'message' => 'Syncro product not found'], 404);
            }

            $estimateResponse = $this->estimateRepository->create([
                'syncro_product_id' => $syncroProduct->id,
                'quantity' => 1,
                'customer_id' => $customer->id,
                'note' => 'Asset added via webhook',
            ]);

            if(!$estimateResponse['status']){
                Log::error('Failed to create estimate: ' . json_encode($estimateResponse));
                return response()->json(['status' => false, 'message' => 'Failed to create estimate'], 500);
            }

            Log::info('Asset added via webhook successfully');
            return response()->json(['status' => true, 'message' => 'Asset added successfully'], 200);
        } catch (\Exception $e) {
            Log::error('Add Asset Webhook Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Failed to add asset'], 500);
        }
    }
}
