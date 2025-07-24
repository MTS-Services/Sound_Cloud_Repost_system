<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreditTransactionRequest;

class CreditTransactionController extends Controller
{
    public function index()
    {
        // 
    }
    public function store(CreditTransactionRequest $request)
    {
        try{
            $validated = $request->validated();
            //
            session()->flash('success', "Credit transaction created successfully");
            return redirect()->back();
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
