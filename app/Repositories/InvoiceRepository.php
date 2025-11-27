<?php

namespace App\Repositories;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Invoice;
use App\Traits\Eset;
use App\Traits\Syncro;
use App\Interfaces\EstimateRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InvoiceRepository implements InvoiceRepositoryInterface{

	use Eset;
	use Syncro;
	protected $estimateRepository;

	public function __construct(EstimateRepositoryInterface $estimateRepository){
		$this->estimateRepository = $estimateRepository;
	}

	public function getDataTable(array $filters = []){
		return DataTables::of($this->find([ ...$filters ]))->addIndexColumn()->make(true);
	}

	public function find(array $filters = []){
		return Invoice::with(['customer', 'estimate'])->where([ ...$filters ])->orderBy('id', 'desc')->get();
	}

	public function count(array $filters = []){
		return Invoice::where([ ...$filters ])->count();
	}

	public function create(array $data){
		try{
			$estimate = $this->estimateRepository->findOne(['syncro_estimate_id' => $data['syncro_estimate_id']]);
			if(!$estimate){
				return ['status' => false, 'message' => 'Estimate not found', 'statusCode' => 404];
			}
			
			if($estimate->is_annual){
				// Convert Estimate to Syncro Invoice
				$syncroResponse = $this->syncroPost('estimates/' . $estimate->syncro_estimate_id . '/convert_to_invoice');
			}else{
				// Create Syncro Invoice
				$syncroResponse = $this->syncroPost('invoices', [
					"customer_id" => $estimate->customer->syncro_customer_id,
					"number" => $estimate->syncro_estimate_number,
					"date" => now()->format('Y-m-d'),
					"note" => $estimate->note,
					"total" => $estimate->invoice_total,
					"line_items" => [
						[
							"product_id" => $estimate->syncroProduct->syncro_product_id,
							"quantity" => $estimate->quantity
						]
					]
				]);
			}

			// Dummy
			// $syncroResponse['invoice'] = [
			// 	"id" => 'INV-' . rand(10000000, 99999999),
			// 	"date" => now()->format('Y-m-d'),
			// 	"due_date" => now()->format('Y-m-d'),
			// 	"subtotal" => $estimate->invoice_total,
			// 	"total" => $estimate->invoice_total,
			// 	"tax" => 0,
			// 	"note" => $estimate->note,
			// 	"number" => 'INV-' . rand(10000000, 99999999),
			// ];

			Log::info('Create Invoice Syncro Response: ' . json_encode($syncroResponse));
			if($syncroResponse && isset($syncroResponse['invoice'])){
				Invoice::create([
					'customer_id' => $estimate->customer->id,
					'estimate_id' => $estimate->id,
					'syncro_invoice_id' => $syncroResponse['invoice']['id'],
					'syncro_invoice_number' => $syncroResponse['invoice']['number'],
					'syncro_invoice_date' => $syncroResponse['invoice']['date'],
					'syncro_invoice_due_date' => $syncroResponse['invoice']['due_date'],
					'syncro_invoice_subtotal' => $syncroResponse['invoice']['subtotal'],
					'syncro_invoice_total' => $syncroResponse['invoice']['total'],
					'syncro_invoice_tax' => $syncroResponse['invoice']['tax'],
					'syncro_invoice_note' => $syncroResponse['invoice']['note'],
					'eset_license_key' => $data['eset_license_key'] ?? null,
				]);

				$estimate->update([
					'status' => 'Approved',
					'approved_at' => now()->format('Y-m-d')
				]);

				return ['status' => true, 'message' => 'Invoice created successfully', 'statusCode' => 200];
			}

			Log::error('Invoice Creation Error: ' . json_encode($syncroResponse));
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		} catch (\Exception $e) {
			Log::error('Invoice Creation Error: ' . $e->getMessage());
			return ['status' => false, 'message' => 'Uh-oh! Something went wrong.', 'statusCode' => 500];
		}
	}

}