<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\CustomerAssetRepositoryInterface;
use App\Http\Interfaces\UserRepositoryInterface;
use App\Traits\Syncro;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerAssetController extends Controller
{
    use Syncro;
    protected $view = 'admin.customerAssets.';
    protected $customerAssetRepository;
    protected $userRepository;

    public function __construct(
        CustomerAssetRepositoryInterface $customerAssetRepository,
        UserRepositoryInterface $userRepository
    )
    {
        $this->customerAssetRepository = $customerAssetRepository;
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if(request()->ajax()){
            return $this->customerAssetRepository->getDataTable(request()->all());
        }

        $customers = $this->userRepository->find(['user_type' => 'customer']);
        $assetTypes = $this->syncroGet('asset_types');
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
        try{
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'asset_type_id' => 'required|string|max:255',
                'asset_serial' => 'required|string|max:255|unique:customer_assets,asset_serial',
            ]);

            $response = $this->customerAssetRepository->create($validatedData);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return response()->json(['status' => false, 'errors' => $e->errors()], 422);
            } else {
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
        try{
            $validatedData = $request->validate([
                'customer_id' => 'required|exists:users,id',
                'name' => 'required|string|max:255',
                'asset_type_id' => 'required|string|max:255',
                'asset_serial' => 'required|string|max:255|unique:customer_assets,asset_serial,' . $id,
            ]);

            $response = $this->customerAssetRepository->update($id, $validatedData);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception | ValidationException $e) {
            if ($e instanceof ValidationException) {
                return response()->json(['status' => false, 'errors' => $e->errors()], 422);
            } else {
                return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $response = $this->customerAssetRepository->delete($id);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    public function details(string $assetId)
    {
        $customerAsset = $this->syncroGet('customer_assets/' . $assetId);
        if($customerAsset){
            return $customerAsset['asset'];
        }

        return [];
    }
}
