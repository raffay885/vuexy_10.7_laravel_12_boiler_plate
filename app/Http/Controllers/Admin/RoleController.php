<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\RoleRepositoryInterface;
use App\Http\Requests\RoleRequest;
use Illuminate\Support\Facades\Log;

class RoleController extends Controller
{
    protected $view = 'admin.roles.';
    protected $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function index()
    {
        if(request()->ajax()){
            return $this->roleRepository->getDataTable();
        }
        return view($this->view . 'index', get_defined_vars());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = $this->roleRepository->find();
        $permissions = $this->roleRepository->getPermissions()->groupBy('parent_name');
        return view($this->view . 'create', get_defined_vars());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleRequest $request)
    {
        try{
            $response = $this->roleRepository->create($request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Role Creation Error: ' . $e->getMessage());
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
        $role = $this->roleRepository->findOne(['id' => $id]);
        $permissions = $this->roleRepository->getPermissions()->groupBy('parent_name');
        return view($this->view . 'edit', get_defined_vars());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoleRequest $request, string $id)
    {
        try{
            $response = $this->roleRepository->update($id, $request->validated());
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Role Update Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $response = $this->roleRepository->delete($id);
            return response()->json(['status' => $response['status'], 'message' => $response['message']], $response['statusCode']);
        } catch (\Exception $e) {
            Log::error('Role Delete Error: ' . $e->getMessage());
            return response()->json(['status' => false, 'message' => 'Uh-oh! Something went wrong.'], 500);
        }
    }
}
