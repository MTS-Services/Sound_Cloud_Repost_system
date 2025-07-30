<?php

namespace App\Http\Controllers\Backend\Admin\OrderManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreditTransactionRequest;
use App\Models\Payment;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use App\Services\Admin\OrderManagement\PaymentService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CreditTransactionController extends Controller
{
    protected CreditTransactionService  $creditTransactionService;
    protected PaymentService $paymentService;

    public function __construct(CreditTransactionService $creditTransactionService, PaymentService $paymentService)
    {
        $this->creditTransactionService = $creditTransactionService;
        $this->paymentService = $paymentService;
    }
    public function index()
    {
        // 
    }
    public function store(CreditTransactionRequest $request)
    {
        try {
            $validated = $request->validated();
            //
            session()->flash('success', "Credit transaction created successfully");
            return redirect()->back();
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
    }
    public function purchase(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->creditTransactionService->getPurchase();
            return DataTables::eloquent($query)
                ->editColumn('name', function ($purchase) {
                    return $purchase->receiver->name;
                })
                ->editColumn('created_at', function ($purchase) {
                    return $purchase->created_at_formatted;
                })
                ->editColumn('action', function ($purchase) {
                    $menuItems = $this->menuItems($purchase);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'name', 'created_at'])
                ->make(true);
        }
        return view('backend.admin.order-management.transactions.purchases');
    }
    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['purchase-list', 'purchase-delete', 'purchase-status']
            ],
            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['purchase-edit']
            ],

            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['purchase-delete']
            ]

        ];
    }


    public function payments(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->paymentService->getPayments();
            return DataTables::eloquent($query)
                ->editColumn('created_at', function ($payment) {
                    return $payment->created_at_formatted;
                })
                ->editColumn('action', function ($payment) {
                    $menuItems = $this->paymentMenuItems($payment);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'created_at'])
                ->make(true);
        }
        return view('backend.admin.order-management.payments.payment');
    }

    protected function paymentMenuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['purchase-list', 'purchase-delete', 'purchase-status']
            ],
            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['purchase-edit']
            ],

            [
                'routeName' => '',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['purchase-delete']
            ]

        ];
    }
}
