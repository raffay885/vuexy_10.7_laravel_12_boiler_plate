<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\EstimateRepositoryInterface;
use App\Http\Requests\EstimateRequest;
use Illuminate\Support\Facades\Log;
class EstimateController extends Controller
{
    protected $estimateRepository;

    public function __construct(EstimateRepositoryInterface $estimateRepository){
        $this->estimateRepository = $estimateRepository;
    }

    public function index()
    {
        //
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
    public function store(EstimateRequest $request)
    {
        try{
            $response = $this->estimateRepository->create($request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Estimate Creation Error: ' . $e->getMessage());
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
    public function update(EstimateRequest $request, string $id)
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
