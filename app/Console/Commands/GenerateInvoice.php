<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Interfaces\UserRepositoryInterface;
use App\Interfaces\InvoiceRepositoryInterface;

class GenerateInvoice extends Command
{

    protected $signature = 'generate:invoice';
    protected $description = 'Generate Invoice';

    public function __construct(
        private UserRepositoryInterface $userRepository,
        private InvoiceRepositoryInterface $invoiceRepository
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $customers = $this->userRepository->find(['user_type' => 'customer']);
        foreach($customers as $customer){
            
        }
    }
}
