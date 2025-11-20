<?php

namespace App\Repositories;

use App\Interfaces\InvoiceRepositoryInterface;
use App\Models\Invoice;
use App\Traits\Eset;
use App\Traits\Syncro;
use App\Interfaces\EstimateRepositoryInterface;
use Illuminate\Support\Facades\Log;

class InvoiceRepository implements InvoiceRepositoryInterface{

	use Eset;
	use Syncro;
	protected $estimateRepository;

	public function __construct(EstimateRepositoryInterface $estimateRepository){
		$this->estimateRepository = $estimateRepository;
	}

	public function find(array $filters = []){
		$invoices = Invoice::query();
		if(isset($filters['customer_id'])){
			$invoices->where('customer_id', $filters['customer_id']);
		}

		return $invoices->orderBy('id', 'desc')->get();
	}

	public function create(array $data){
		try{
			$estimate = $this->estimateRepository->findOne(['syncro_estimate_id' => $data['syncro_estimate_id']]);
			$syncroResponse = $this->syncroPost('invoices', [
				"customer_id" => $estimate->customer->syncro_customer_id,
				"number" => $estimate->number,
				"date" => $estimate->date,
				"note" => $estimate->note,
				"hardwarecost" => $estimate->estimate_subtotal,
				"line_items" => [
					[
						"product_id" => $estimate->syncro_product_id,
						"quantity" => $estimate->quantity
					]
				]
			]);

			Log::info('Syncro Response: ' . json_encode($syncroResponse));
			if($syncroResponse && isset($syncroResponse['invoice'])){
				$invoice = Invoice::create([
					'estimate_id' => $estimate->id,
					'customer_id' => $estimate->customer->id,
					'number' => $syncroResponse['invoice']['number'],
					'date' => $syncroResponse['invoice']['date'],
					'due_date' => $syncroResponse['invoice']['due_date'],
					'subtotal' => $syncroResponse['invoice']['subtotal'],
					'total' => $syncroResponse['invoice']['total'],
					'tax' => $syncroResponse['invoice']['tax'],
					'note' => $syncroResponse['invoice']['note'],
					'syncro_invoice_id' => $syncroResponse['invoice']['id'],
				]);

				Log::info('Invoice Created: ' . json_encode($invoice));
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