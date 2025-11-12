<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\EstimateRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
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
    public function store(Request $request)
    {
        try{
            $validatedData = $request->validate([
               'number' => 'required|string|max:255',
               'date' => 'required|date',
               'customer_id' => 'required|exists:users,id',
               'asset_ids' => 'required|array',
               'asset_ids.*' => 'required',
               'note' => 'required|string|max:255',
            ]);

            $response = $this->estimateRepository->create($validatedData);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return response()->json(['status' => false, 'errors' => $e->errors()], 422);
            } else {
                Log::error('Estimate Creation Error: ' . $e->getMessage());
                return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
            }
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
