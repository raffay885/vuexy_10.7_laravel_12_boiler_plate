<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\EsetProductRequest;
use Illuminate\Http\Request;
use App\Interfaces\EsetProductRepositoryInterface;
use App\Interfaces\SyncroProductRepositoryInterface;
use Illuminate\Support\Facades\Log;

class EsetProductController extends Controller
{
    protected $esetProductRepository;
    protected $syncroProductRepository;
    protected $view = 'admin.esetProducts.';

    public function __construct(
        EsetProductRepositoryInterface $esetProductRepository,
        SyncroProductRepositoryInterface $syncroProductRepository
    )
    {
        $this->esetProductRepository = $esetProductRepository;
        $this->syncroProductRepository = $syncroProductRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            return $this->esetProductRepository->getDataTable();
        }

        $syncroProducts = $this->syncroProductRepository->find();
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
    public function store(EsetProductRequest $request)
    {
        try{
            $response = $this->esetProductRepository->create($request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Eset Product Creation Error: ' . $e->getMessage());
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
    public function update(EsetProductRequest $request, string $id)
    {
        try{
            $response = $this->esetProductRepository->update($id, $request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Eset Product Update Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $response = $this->esetProductRepository->delete($id);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Eset Product Delete Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }
}
