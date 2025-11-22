<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\EstimateRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Http\Requests\EstimateRequest;
use Illuminate\Support\Facades\Log;
use App\Traits\Syncro;
class EstimateController extends Controller
{
    protected $view = 'admin.estimates.';
    protected $estimateRepository;
    protected $userRepository;
    use Syncro;

    public function __construct(
        EstimateRepositoryInterface $estimateRepository,
        UserRepositoryInterface $userRepository
    ){
        $this->estimateRepository = $estimateRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if(request()->ajax()){
            return $this->estimateRepository->getDataTable();
        }

        $customers = $this->userRepository->find(['user_type' => 'customer']);
        $products = $this->syncroGet('products');
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
