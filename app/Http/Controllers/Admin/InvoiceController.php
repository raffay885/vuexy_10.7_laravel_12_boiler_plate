<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\InvoiceRepositoryInterface;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    protected $view = 'admin.invoices.';
    protected $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository){
        $this->invoiceRepository = $invoiceRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            return $this->invoiceRepository->getDataTable();
        }

        return view($this->view . 'index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
