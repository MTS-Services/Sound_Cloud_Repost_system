<?php

namespace App\Http\Controllers\Backend\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\FaqCategoryRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Faq;
use App\Models\FaqCategory;
use App\Services\Admin\Faq\FaqCategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;


class FaqCategotyController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;
    
    protected FaqCategoryService $faqCategoryService;

    public function __construct(FaqCategoryService $faqCategoryService)
    {
        $this->faqCategoryService = $faqCategoryService;
    }
    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('fm.faq-category.index');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('fm.faq-category.trash');
    }
      public function getFaqCategories()
    {
        return FaqCategory::query();
    }


    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:faqCategory-list', only: ['index']),
            new Middleware('permission:faqCategory-details', only: ['show']),
            new Middleware('permission:faqCategory-create', only: ['create', 'store']),
            new Middleware('permission:faqCategory-edit', only: ['edit', 'update']),
            new Middleware('permission:faqCategory-delete', only: ['destroy']),
            new Middleware('permission:faqCategory-trash', only: ['trash']),
            new Middleware('permission:faqCategory-restore', only: ['restore']),
            new Middleware('permission:faqCategory-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->faqCategoryService->getFaqCategories();
            return DataTables::eloquent($query)

            ->editColumn('name', function ($faqCategory) {
                return $faqCategory->name;
            })

            ->editColumn('slug', function ($faqCategory) {
                return $faqCategory->slug;
            })
            ->editColumn('status', fn($faqCategory) => "<span class='badge badge-soft {$faqCategory->status_color}'>{$faqCategory->status_label}</span>")
                ->editColumn('created_by', function ($faqCategory) {
                    return $this->creater_name($faqCategory);
                })
                ->editColumn('created_at', function ($faqCategory) {
                    return $faqCategory->created_at_formatted;
                })
                ->editColumn('action', function ($service) {
                    $menuItems = $this->menuItems($service);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns([ 'name', 'slug','status','created_by', 'created_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.faq-management.faq-category.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-details']
            ],
            [
                'routeName' => 'fm.faq-category.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'fm.faq-category.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status ? 'Deactivate' : 'Activate',
                'status' => true,
                'permissions' => ['permission-status']
            ],

            [
                'routeName' => 'fm.faq-category.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ]

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        //
        return view('backend.admin.faq-management.faq-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqCategoryRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->faqCategoryService->createFaqCategory($validated);
             return redirect()->route('fm.faq-category.index')->with('success', 'Service created successfully');
           
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature Category create failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->faqCategoryService->getFaqCategory($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): View
    {
        $data['faq_category'] = $this->faqCategoryService->getFaqCategory($id);
        return view('backend.admin.faq-management.faq-category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(FaqCategoryRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $faq_category = $this->faqCategoryService->getFaqCategory($id);
            $this->faqCategoryService->updateFaqCategory($validated, $faq_category);
            session()->flash('success', 'Faq Category updated successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Faq Category update failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }



     public function status(Request $request, string $id)
    {
        $faqCategory = FaqCategory::findOrFail(decrypt($id));
        $faqCategory->update(['status' => !$faqCategory->status, 'updated_by' => admin()->id]);
        session()->flash('success', 'Faq status updated successfully!');
        return redirect()->route('fm.faq-category.index');
    }
    /**
     * Remove the specified resource from storage.
     */ public function destroy(string $id)
    {
        $faq =FaqCategory::findOrFail(decrypt($id));
        $faq->update(['deleted_by' => admin()->id]);
        $faq->delete();
        session()->flash('success', 'Faq category deleted successfully!');
        return redirect()->route('fm.faq-category.index');
    }

    public function trash(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->faqCategoryService->getFaqCategories()->onlyTrashed();
            return DataTables::eloquent($query)

              ->editColumn('name', function ($faqCategory) {
                return $faqCategory->name;
            })

            ->editColumn('slug', function ($faqCategory) {
                return $faqCategory->slug;
            })
            ->editColumn('status', fn($faqCategory) => "<span class='badge badge-soft {$faqCategory->status_color}'>{$faqCategory->status_label}</span>")
               
                ->editColumn('deleted_by', function ($admin) {
                    return $this->deleter_name($admin);
                })
                ->editColumn('deleted_at', function ($admin) {
                    return $admin->deleted_at_formatted;
                })
                ->editColumn('action', function ($permission) {
                    $menuItems = $this->trashedMenuItems($permission);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['name', 'slug','status','deleted_by', 'deleted_at', 'action'])
                ->make(true);
        }
        return view('backend.admin.faq-management.faq-category.trash');
    }

    protected function trashedMenuItems($model): array
    {
        return [
            [
                'routeName' => 'fm.faq-category.restore',
                'params' => [encrypt($model->id)],
                'label' => 'Restore',
                'permissions' => ['permission-restore']
            ],
            [
                'routeName' => 'fm.faq-category.permanent-delete',
                'params' => [encrypt($model->id)],
                'label' => 'Permanent Delete',
                'p-delete' => true,
                'permissions' => ['permission-permanent-delete']
            ]

        ];
    }

     public function restore(string $id): RedirectResponse
    {
        try {
            $faqCategory = FaqCategory::onlyTrashed()->findOrFail(decrypt($id));

            $this->faqCategoryService->restore($faqCategory, $id);
            session()->flash('success', "Faq category restored successfully");
        } catch (\Throwable $e) {
            session()->flash('Faq restore failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }


    public function permanentDelete(string $encryptedId): RedirectResponse
    {
        try {
            $id = decrypt($encryptedId);
            $faqCategory = FaqCategory::onlyTrashed()->findOrFail($id);

            $this->faqCategoryService->permanentDelete($faqCategory, $id);
            $faqCategory->forceDelete();

            session()->flash('success', 'Faq permanently deleted successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Faq permanent delete failed');
            throw $e;
        }
        return $this->redirectTrashed();
    }
}
