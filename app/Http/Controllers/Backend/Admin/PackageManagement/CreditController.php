<?php

namespace App\Http\Controllers\Backend\Admin\PackageManagement;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageManagement\CreditRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Credit;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\Admin\PackageManagement\CreditService;

class CreditController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;
    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('pm.credit.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('pm.credit.trash');
    }

    protected CreditService $creditService;

    public function __construct(CreditService $creditService)
    {
        $this->creditService = $creditService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:credit-list', only: ['index']),
            new Middleware('permission:credit-details', only: ['show']),
            new Middleware('permission:credit-create', only: ['create', 'store']),
            new Middleware('permission:credit-edit', only: ['edit', 'update']),
            new Middleware('permission:credit-delete', only: ['destroy']),
            new Middleware('permission:credit-trash', only: ['trash']),
            new Middleware('permission:credit-restore', only: ['restore']),
            new Middleware('permission:credit-permanent-delete', only: ['permanentDelete']),
            new Middleware('permission:credit-status', only: ['status']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->creditService->getCredits();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($credit) => "<span class='badge badge-soft {$credit->status_color}'>{$credit->status_label}</span>")
                ->editColumn('price', fn($credit) => "$" . $credit->price)
                ->editColumn('created_by', fn($credit) => $this->creater_name($credit))
                ->editColumn('created_at', fn($credit) => $credit->created_at_formatted)
                ->editColumn('action', fn($credit) => view('components.action-buttons', ['menuItems' => $this->menuItems($credit)])->render())
                ->rawColumns(['action', 'status', 'created_at', 'created_by'])
                ->make(true);
        }
        return view('backend.admin.package_management.credit.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'pm.credit.detail',
                'params' => encrypt($model->id),
                'label' => 'View Details',
                'permissions' => ['user-detail']
            ],
            [
                'routeName' => 'pm.credit.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['credit-edit']
            ],
            [
                'routeName' => 'pm.credit.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'permissions' => ['credit-status']
            ],
            [
                'routeName' => 'pm.credit.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['credit-delete']
            ]

        ];
    }

    public function detail($id)
    {
        $data['credits'] = Credit::where('id', decrypt($id))->first();
        return view('backend.admin.package_management.credit.detail', $data);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('backend.admin.package_management.credit.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreditRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->creditService->createCredit($validated);
            session()->flash('success', "Credit created successfully");
        } catch (\Throwable $e) {
            session()->flash('Credit creation failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->creditService->getCredit($id);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['credit'] = $this->creditService->getCredit($id);
        return view('backend.admin.package_management.credit.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreditRequest $request, string $id)
    {
        try {
            $credit = $this->creditService->getCredit($id);
            $validated = $request->validated();
            $this->creditService->updateCredit($credit, $validated);
            session()->flash('success', "Credit updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Credit update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $credit = $this->creditService->getCredit($id);

            $this->creditService->delete($credit);
            session()->flash('success', "Credit deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Credit delete failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->creditService->getCredits()->onlyTrashed();
            return DataTables::eloquent($query)
                ->editColumn('status', fn($credit) => "<span class='badge badge-soft {$credit->status_color}'>{$credit->status_label}</span>")
                ->editColumn('deleted_by', fn($credit) => $this->deleter_name($credit))
                ->editColumn('deleted_at', fn($credit) => $credit->deleted_at_formatted)
                ->editColumn('action', function ($credit) {
                    $menuItems = $this->trashedMenuItems($credit);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'status', 'deleted_at', 'deleted_by'])
                ->make(true);
        }
        return view('backend.admin.package_management.credit.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'pm.credit.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['credit-restore']
            ],
            [
                'routeName' => 'pm.credit.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['credit-permanent-delete']
            ]

        ];
    }

    public function restore(string $id): RedirectResponse
    {
        try {
            $this->creditService->restore($id);
            session()->flash('success', "Credit restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Credit restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function permanentDelete(string $id): RedirectResponse
    {
        try {
            $this->creditService->permanentDelete($id);
            session()->flash('success', "Credit permanently deleted successfully");
        } catch (\Throwable $e) {
            session()->flash('Credit permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }

    public function status(string $id): RedirectResponse
    {
        $data = $this->creditService->getCredit($id);
        $this->creditService->toggleStatus($data);
        session()->flash('success', 'Credit status updated successfully!');
        return $this->redirectIndex();
    }
}
