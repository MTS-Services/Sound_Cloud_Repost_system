<?php

namespace App\Http\Controllers\Backend\Admin\PackageManagement;
;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageManagement\FeatureCategoryRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class FeatureCategoryController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('pm.feature-category.index');
    }

    protected FeatureCategorySevice $FeatureCategorySevice;

    public function __construct(FeatureCategorySevice $FeatureCategorySevice)
    {
        $this->FeatureCategorySevice = $FeatureCategorySevice;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:feature_category-list', only: ['index']),
            new Middleware('permission:feature_category-create', only: ['create', 'store']),
            new Middleware('permission:feature_category-edit', only: ['edit', 'update']),
            new Middleware('permission:feature_category-permanent-delete', only: ['permanentDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->FeatureCategorySevice->getFeatureCategories();
            return DataTables::eloquent($query)
                ->editColumn('created_by', function ($credit) {
                    return $this->creater_name($credit);
                })
                ->editColumn('created_at', function ($credit) {
                    return $credit->created_at_formatted;
                })
                ->editColumn('action', function ($credit) {
                    $menuItems = $this->menuItems($credit);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.admin.package_management.feature_category.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'pm.feature-category.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['feature_category-edit']
            ],

            [
                'routeName' => 'pm.feature-category.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['feature_category-delete']
            ]

        ];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.package_management.feature_category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FeatureCategoryRequest $request)
    {
        try {
            $validated = $request->validated();
            $this->FeatureCategorySevice->createFeatureCategory($validated);
            session()->flash('success', 'Feature Category created successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature Category create failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['feature_category'] = $this->FeatureCategorySevice->getFeatureCategory($id);
        return view('backend.admin.package_management.feature_category.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureCategoryRequest $request, string $id)
    {
        try {
            $validated = $request->validated();
            $featrue_category = $this->FeatureCategorySevice->getFeatureCategory($id);
            $this->FeatureCategorySevice->updateFeatureCategory($validated, $featrue_category);
            session()->flash('success', 'Feature Category updated successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature Category update failed!');
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
            $this->FeatureCategorySevice->deleteFeatureCategory($id);
            session()->flash('success', 'Feature Category deleted successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature Category delete failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }
}
