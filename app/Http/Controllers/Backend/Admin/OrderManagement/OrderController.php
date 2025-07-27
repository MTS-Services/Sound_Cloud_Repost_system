<?php

namespace App\Http\Controllers\Backend\Admin\OrderManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManagement\OrderRequest;
use App\Services\Admin\OrderManagement\OrderService;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index()
    {
        //
    }
    public function store(OrderRequest $request)
    {
        try{
            $validated = $request->validated();
            $orders =$this->orderService->createOrder($validated);
            return redirect()->route('f.payment.method', encrypt($orders->id));
        }catch(\Exception $e){
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
