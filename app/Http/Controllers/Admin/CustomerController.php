<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Interfaces\UserRepositoryInterface;
use App\Traits\Syncro;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CustomerController extends Controller
{
    use Syncro;
    
    protected $view = 'admin.customers.';
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        if(request()->ajax()){
            return $this->userRepository->getDataTable(['user_type' => 'customer']);
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
        try{
            $validatedData = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email'
            ]);

            $data = [...$validatedData, 'user_type' => 'customer'];
            $response = $this->userRepository->create($data);
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
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id
            ]);

            $response = $this->userRepository->update($id, $validatedData);
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
            $response = $this->userRepository->delete($id);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    public function details(string $id)
    {
        $customer = $this->userRepository->findOne(['id' => $id]);
        switch(request()->query('tab')) {
            case 'syncroDetails':
                $customerDetails = [];
                $customerSyncroDetails = $this->syncroGet('customers/' . $customer->syncro_customer_id);
                if ($customerSyncroDetails) {
                    $customerDetails = $customerSyncroDetails['customer'];
                }

                break;
            case 'customerAssets':
                $customerAssets = [];
                $customerSyncroAssets = $this->syncroGet('customer_assets', ['customer_id' => $customer->syncro_customer_id]);
                if($customerSyncroAssets){
                    $customerAssets = $customerSyncroAssets['assets'];
                }

                break;
        }
        
        return view($this->view . 'view', get_defined_vars());
    }
}
