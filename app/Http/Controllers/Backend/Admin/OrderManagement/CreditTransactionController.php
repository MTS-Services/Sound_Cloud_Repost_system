<?php

namespace App\Http\Controllers\Backend\Admin\OrderManagement;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreditTransactionRequest;
use App\Models\Credit;
use App\Models\CreditTransaction;
use App\Models\Payment;
use App\Models\User;
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
    public function index(Request $request)
    {


        if ($request->ajax()) {
            $query = $this->creditTransactionService->getTransactions();
            return DataTables::eloquent($query)
                ->editColumn('name', function ($credit) {
                    return $credit->receiver->name;
                })
                ->editColumn('credit', function ($credit) {
                    return $credit->credit;
                })
                ->editColumn('amount', function ($credit) {
                    return '$' . number_format($credit->amount, 2);
                })
                ->editColumn('credits', function ($credit) {
                    return number_format($credit->credits, 2);
                })
                ->editColumn('status', fn($credit) => "<span class='badge badge-soft {$credit->status_color}'>{$credit->status_label}</span>")
                ->editColumn('calculation_type', fn($credit) => "<span class='badge badge-soft {$credit->calculation_type_color}'>{$credit->calculation_type_name}</span>")
                ->editColumn('action', function ($credit) {
                    $menuItems = $this->creditmeniItems($credit);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['name', 'credit', 'amount', 'credits', 'status', 'calculation_type', 'action'])
                ->make(true);
        }
        return view('backend.admin.order-management.transactions.index');
    }

    public function creditmeniItems($model)
    {
        return [

            [
                'routeName' => 'om.credit-transaction.detail',
                'params' => encrypt($model->id),
                'label' => ' Details',
                'permissions' => ['creditTransaction-detail']
            ],
            // [
            //     'routeName' => 'javascript:void(0)',
            //     'data-id' => encrypt($model->id),
            //     'className' => 'view',
            //     'label' => 'Details',
            //     'permissions' => ['creditTransaction-list',]
            // ],

        ];
    }
    public function detail($id)
    {
        $data['transactions'] = Payment::where('id', decrypt($id))->first();
        return view('backend.admin.order-management.transactions.detail', $data);
    }

    public function show(string $id)
    {
        $data = $this->creditTransactionService->getTransaction($id);
        $data['user_urn'] = $data->user?->name;
        return response()->json($data);
    }

    public function status(Request $request, string $id)
    {
        $data = $this->creditTransactionService->getTransaction($id);
        $this->creditTransactionService->toggleStatus($data);
        session()->flash('success', 'Transaction status updated successfully!');
        return redirect()->route('om.transaction.index');
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
                'routeName' => 'om.credit-transaction.payment-detail',
                'params' => encrypt($model->id),
                'label' => ' Details',
                'permissions' => ['payment-detail']
            ],
            // [
            //     'routeName' => 'javascript:void(0)',
            //     'data-id' => encrypt($model->id),
            //     'className' => 'view',
            //     'label' => 'Details',
            //     'permissions' => ['purchase-list', 'purchase-delete', 'purchase-status']
            // ],
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
    public function paymentDetails($id)
    {
        $data['payments'] = Payment::where('id', decrypt($id))->first();
        return view('backend.admin.order-management.payments.detail', $data);
    }
}
