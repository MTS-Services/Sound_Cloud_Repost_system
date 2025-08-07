<?php

namespace App\Http\Controllers\Backend\Admin\PackageManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageManagement\FeatureRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Feature;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use App\Services\Admin\PackageManagement\FeatureSevice;
use GuzzleHttp\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('pm.feature.index');
    }

    protected FeatureSevice $featureService;
    protected FeatureCategorySevice $featureCategoryService;

    public function __construct(FeatureSevice $featureService, FeatureCategorySevice $featureCategoryService)
    {
        $this->featureService = $featureService;
        $this->featureCategoryService = $featureCategoryService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:feature-list', only: ['index']),
            new Middleware('permission:feature-create', only: ['create', 'store']),
            new Middleware('permission:feature-edit', only: ['edit', 'update']),
            new Middleware('permission:feature-permanent-delete', only: ['permanentDelete']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = $this->featureService->getFeatures();
            return DataTables::eloquent($query)
                ->editColumn('key', function ($feature) {
                    return $feature->features_name;
                })
                ->editColumn('type', function ($feature) {
                    return $feature->type_name;
                })
                ->editColumn('feature_category_id', function ($feature) {
                    return $feature->featureCategory->name;
                })
                ->editColumn('created_by', function ($feature) {
                    return $this->creater_name($feature);
                })
                ->editColumn('created_at', function ($feature) {
                    return $feature->created_at_formatted;
                })
                ->editColumn('action', function ($feature) {
                    $menuItems = $this->menuItems($feature);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['action', 'type', 'key', 'feature_category_id', 'created_by', 'created_at'])
                ->make(true);
        }
        return view('backend.admin.package_management.features.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'pm.feature.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['feature-edit']
            ],

            [
                'routeName' => 'pm.feature.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['feature-delete']
            ]

        ];
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['feature_categories'] = $this->featureCategoryService->getFeatureCategories()->select(['id', 'name'])->get();
        return view('backend.admin.package_management.features.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'feature_category_id' => 'required|exists:feature_categories,id',
                'name' => 'required|unique:features,name',
                // 'key' => 'required|unique:features,key|in:' . Feature::getKeys(),
                'key' => 'required|unique:features,key|in:' . implode(',', array_keys(Feature::getKeys())),
                'type' => 'required|in:' . implode(',', array_keys(Feature::getTypes())),
            ]
        );
        try {

            // $validated = $request->validated();
            $this->featureService->createFeature($validated);
            session()->flash('success', 'Feature created successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature create failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data['feature'] = $this->featureService->getFeature($id);
        $data['feature_categories'] = $this->featureCategoryService->getFeatureCategories()->select(['id'])->get();
        return view('backend.admin.package_management.features.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'feature_category_id' => 'required|exists:feature_categories,id',
                'name' => 'required|unique:features,name,' . decrypt($id),
                'key' => 'required|unique:features,key,' . decrypt($id) . ',id|in:' . implode(',', array_keys(Feature::getKeys())),
                'type' => 'required|in:' . implode(',', array_keys(Feature::getTypes())),
            ]
        );
        try {
            $featrue = $this->featureService->getFeature($id);
            $this->featureService->updateFeature($validated, $featrue);
            session()->flash('success', 'Feature  updated successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature  update failed!');
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
            $this->featureService->deleteFeature($id);
            session()->flash('success', 'Feature  deleted successfully!');
        } catch (\Throwable $e) {
            session()->flash('error', 'Feature  delete failed!');
            throw $e;
        }
        return $this->redirectIndex();
    }
}
