<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SystemLogController extends Controller
{
    protected $view = 'admin.systemLogs.';

    public function index()
    {
        if(request()->ajax()){
            $systemLogs = SystemLog::query();
            if(request()->has('source')){
                $systemLogs->where('source', request()->source);
            }
            if(request()->has('method') && request()->method != ''){
                $systemLogs->where('method', request()->method);
            }
            if(request()->has('status') && request()->status != ''){
                $systemLogs->where('status', request()->status);
            }
            $systemLogs = $systemLogs->orderBy('id', 'desc')->get();
            return DataTables::of($systemLogs)->addIndexColumn()->make(true);
        }

        return view($this->view . 'index', get_defined_vars());
    }

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
