<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\EstimateRepositoryInterface;
use App\Traits\Syncro;

class GenerateEstimate extends Command
{

    use Syncro;
    protected $signature = 'generate:estimate';
    protected $description = 'Generate Estimate';

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EstimateRepositoryInterface $estimateRepository,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try{
            $this->info('Generating Invoices...');
            $customers = $this->userRepository->find(['user_type' => 'customer']);

            foreach($customers as $customer){
                $annualEstimate = $this->estimateRepository->findOne(['customer_id' => $customer->id, 'status' => 'Approved', 'is_annual' => 1]);
                if(!$annualEstimate){
                    $this->info('No annual estimate found for customer having Syncro ID: ' . $customer->syncro_customer_id);
                    continue;
                }
    
                $nextAnniversary = \Carbon\Carbon::parse($annualEstimate->approved_at)->addYear()->subMonth();
                if($customer->billing_type == 'monthly'){
                    $nextAnniversary = \Carbon\Carbon::parse($annualEstimate->approved_at)->endOfMonth();
                }

                if ($nextAnniversary >= now()) {
                    $this->info('Next anniversary is in the future for customer having Syncro ID: ' . $customer->syncro_customer_id);
                    continue;
                }

                $customerSyncroAssets = $this->syncroGet('customer_assets', ['customer_id' => $customer->syncro_customer_id]);
                // $customerSyncroAssets['assets'] = [[1],[2],[3],[4],[5],[6],[7],[8],[9],[10],[11],[12]];

                if(!$customerSyncroAssets || !isset($customerSyncroAssets['assets'])){
                    $this->info('No customer syncro assets found for customer having Syncro ID: ' . $customer->syncro_customer_id);
                    continue;
                }

                $estimateResponse = $this->estimateRepository->create([
                    'syncro_product_id' => $annualEstimate->syncro_product_id,
                    'quantity' => count($customerSyncroAssets['assets']),
                    'date' => now()->format('Y-m-d'),
                    'customer_id' => $customer->id,
                    'note' => 'Annual Estimate',
                    'is_annual' => 1
                ]);

                if(!$estimateResponse['status']){
                    $this->info('Failed to create estimate for customer having Syncro ID: ' . $customer->syncro_customer_id);
                    continue;
                }

                $this->info('Estimate created successfully for customer having Syncro ID: ' . $customer->syncro_customer_id);
            }
            
            $this->info('Estimates generated for all customers successfully...');
        }catch(\Exception $e){
            $this->error('Error: ' . $e->getMessage());
        }
    }
}
