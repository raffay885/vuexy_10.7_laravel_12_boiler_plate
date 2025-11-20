<?php

namespace App\Interfaces;

interface InvoiceRepositoryInterface{

	public function find(array $filters = []);

	public function create(array $data);

}