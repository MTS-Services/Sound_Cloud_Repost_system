<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreditTransactionRequest;
use App\Services\Admin\CreditManagement\CreditTransactionService;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class CreditTransactionController extends Controller
{
    protected CreditTransactionService $creditTransactionService;

    public function __construct(CreditTransactionService $creditTransactionService)
    {
        $this->creditTransactionService = $creditTransactionService;
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
            $query = $this->creditTransactionService->getPurchase()->with('receiver')->purchase();
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
        return view('backend.admin.credit-management.purchases');
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
}
