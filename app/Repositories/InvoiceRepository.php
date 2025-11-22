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

			if($estimate->is_approved){
				return ['status' => false, 'message' => 'Estimate already approved', 'statusCode' => 400];
			}
			
			// $syncroResponse = $this->syncroPost('invoices', [
			// 	"customer_id" => $estimate->customer->syncro_customer_id,
			// 	"number" => $estimate->number,
			// 	"date" => $estimate->date,
			// 	"note" => $estimate->note,
			// 	"total" => $estimate->invoice_total,
			// 	"line_items" => [
			// 		[
			// 			"product_id" => $estimate->syncro_product_id,
			// 			"quantity" => $estimate->quantity
			// 		]
			// 	]
			// ]);

			$syncroResponse['invoice'] = [
				"id" => 'INV-' . rand(10000000, 99999999),
				"date" => now()->format('Y-m-d'),
				"due_date" => now()->format('Y-m-d'),
				"subtotal" => $estimate->invoice_total,
				"total" => $estimate->invoice_total,
				"tax" => 0,
				"note" => $estimate->note,
				"number" => 'INV-' . rand(10000000, 99999999),
			];

			Log::info('Syncro Response: ' . json_encode($syncroResponse));
			if($syncroResponse && isset($syncroResponse['invoice'])){
				Invoice::create([
					'estimate_id' => $estimate->id,
					'customer_id' => $estimate->customer->id,
					'number' => $syncroResponse['invoice']['number'],
					'date' => $syncroResponse['invoice']['date'],
					'due_date' => $syncroResponse['invoice']['due_date'],
					'subtotal' => $syncroResponse['invoice']['subtotal'],
					'total' => $syncroResponse['invoice']['total'],
					'tax' => $syncroResponse['invoice']['tax'],
					'note' => $syncroResponse['invoice']['note'],
					'syncro_invoice_id' => $syncroResponse['invoice']['id']
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