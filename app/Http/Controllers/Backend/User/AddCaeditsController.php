<?php

namespace App\Http\Controllers\Backend\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManagement\OrderRequest;
use App\Models\Credit;
use App\Services\Admin\OrderManagement\OrderService;
use App\Services\Admin\PackageManagement\CreditService;
use Illuminate\Http\Request;

class AddCaeditsController extends Controller
{
    protected CreditService $creditService;

    protected OrderService $orderService;

    public function __construct(CreditService $creditService, OrderService $orderService)
    {
        $this->creditService = $creditService;

        $this->orderService = $orderService;

    }
    public function addCredits()
    {
        $data['credits'] = $this->creditService->getCredits()->active()->get();
        return view('backend.user.add-credits', $data);
    }

    public function buyCredits(OrderRequest $request)
    {
        try {
            $validated = $request->validated();
            $validated['source_id'] = decrypt($validated['credit_id']);
            $validated['source_type'] = Credit::class;
            $order = $this->orderService->createOrder($validated);
            return redirect()->route('f.payment.method', encrypt($order->id));
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
}
