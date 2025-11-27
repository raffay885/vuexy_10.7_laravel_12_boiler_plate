<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\SyncroProductRepositoryInterface;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\SyncroProductRequest;

class SyncroProductController extends Controller
{
    protected $syncroProductRepository;
    protected $view = 'admin.syncroProducts.';

    public function __construct(SyncroProductRepositoryInterface $syncroProductRepository)
    {
        $this->syncroProductRepository = $syncroProductRepository;
    }

    public function index()
    {
        if(request()->ajax()){
            return $this->syncroProductRepository->getDataTable();
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
    public function store(SyncroProductRequest $request)
    {
        try{
            $response = $this->syncroProductRepository->create($request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Syncro Product Creation Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
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
    public function update(SyncroProductRequest $request, string $id)
    {
        try{
            $response = $this->syncroProductRepository->update($id, $request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Syncro Product Update Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $response = $this->syncroProductRepository->delete($id);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Syncro Product Delete Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    public function getProducts(string $billingType)
    {
        $products = $this->syncroProductRepository->find(['billing_type' => $billingType]);
        return response()->json($products);
    }
}
