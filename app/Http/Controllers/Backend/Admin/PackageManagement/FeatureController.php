<?php

namespace App\Http\Controllers\Backend\Admin\PackageManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PackageManagement\FeatureRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Feature;
use App\Services\Admin\PackageManagement\FeatureCategorySevice;
use App\Services\Admin\PackageManagement\FeatureSevice;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;

class FeatureController extends Controller implements HasMiddleware
{
    use AuditRelationTraits;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('pm.feature.index');
    }

    protected FeatureSevice $featureService;

    public function __construct(FeatureSevice $featureService)
    {
        $this->featureService = $featureService;
    }

    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:feature-list', only: ['index']),
            new Middleware('permission:feature-create', only: ['create', 'store']),
            new Middleware('permission:feature-edit', only: ['edit', 'update']),
            new Middleware('permission:feature-delete', only: ['destroy']),
            new Middleware('permission:feature-status', only: ['status']),
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

             ->editColumn('status', fn($feature) => "<span class='badge badge-soft {$feature->status_color}'>{$feature->status_label}</span>")

                ->editColumn('key', function ($feature) {
                    return $feature->features_name;
                })
                ->editColumn('type', function ($feature) {
                    return $feature->type_name;
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
                ->rawColumns(['status', 'action', 'type', 'key', 'created_by', 'created_at'])
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
                'routeName' => 'pm.feature.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status ? 'Deactivate' : 'Activate',
                'status' => true,
                'permissions' => ['permission-status']
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
        return view('backend.admin.package_management.features.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'name' => 'required|unique:features,name',
                'type' => 'required|in:' . implode(',', array_keys(Feature::getTypes())),
                'note' => 'nullable|string',
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
        return view('backend.admin.package_management.features.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate(
            [
                'name' => 'required|unique:features,name,' . decrypt($id),
                'type' => 'required|in:' . implode(',', array_keys(Feature::getTypes())),
                'note' => 'nullable|string',
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

    public function status(Request $request, string $id)
    {
        $featrue = Feature::findOrFail(decrypt($id));
        $featrue->update(['status' => !$featrue->status, 'updated_by' => admin()->id]);
        session()->flash('success', 'Feature  status updated successfully!');
        return redirect()->route('pm.feature.index');
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
