<?php

namespace App\Http\Controllers\Backend\Admin\Faq;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Faq\FaqRequest;
use App\Http\Traits\AuditRelationTraits;
use App\Models\Faq;
use App\Services\Admin\Faq\FaqService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;


class FaqController extends Controller implements HasMiddleware
{
    protected FaqService $FaqService;
    use AuditRelationTraits;

    protected $serviceName = FaqService::class;

    protected function redirectIndex(): RedirectResponse
    {
        return redirect()->route('index route');
    }

    protected function redirectTrashed(): RedirectResponse
    {
        return redirect()->route('trash route');
    }

    public function __construct(FaqService $FaqService)
    {
        $this->FaqService = $FaqService;
    }
    
    public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:faq-list', only: ['index']),
            new Middleware('permission:faq-details', only: ['show']),
            new Middleware('permission:faq-create', only: ['create', 'store']),
            new Middleware('permission:faq-edit', only: ['edit', 'update']),
            new Middleware('permission:faq-delete', only: ['destroy']),
            new Middleware('permission:faq-trash', only: ['trash']),
            new Middleware('permission:faq-restore', only: ['restore']),
            new Middleware('permission:faq-permanent-delete', only: ['permanentDelete']),
            //add more permissions if needed
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
          if($request->ajax()) {
              $query = $this->FaqService->getFaqs();
              return DataTables::eloquent($query)

               
                 ->editColumn('status', fn($faq) => "<span class='badge badge-soft {$faq->status_color}'>{$faq->status_label}</span>")


                  ->editColumn('created_by', function ($faq) {
                      // return $faq->creater_name;
                      return $this->creater_name($faq);
                  })
                  ->editColumn('created_at', function ($faq) {
                      return $faq->created_at_formatted;
                  })
                  ->editColumn('action', function ($faq) {
                      $menuItems = $this->menuItems($faq);
                      return view('components.action-buttons', compact('menuItems'))->render();
                  })
                  ->rawColumns(['key','status','created_by', 'created_at', 'action'])
                  ->make(true);
          }
        return view('backend.admin.faq-management.faq.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'javascript:void(0)',
                'data-id' => encrypt($model->id),
                'className' => 'view',
                'label' => 'Details',
                'permissions' => ['permission-list', 'permission-delete', 'permission-status']
            ],
            [
                'routeName' => 'fm.faq.edit',
                'params' => [encrypt($model->id)],
                'label' => 'Edit',
                'permissions' => ['permission-edit']
            ],
            [
                'routeName' => 'fm.faq.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status ? 'Deactivate' : 'Activate',
                'status' => true,
                'permissions' => ['permission-status']
            ],

            [
                'routeName' => 'fm.faq.destroy',
                'params' => [encrypt($model->id)],
                'label' => 'Delete',
                'delete' => true,
                'permissions' => ['permission-delete']
            ],
            

        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        
        return view('backend.admin.faq-management.faq.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FaqRequest $request)
    {
         try {
             $validated = $request->validated();
             $this->FaqService->createFaq($validated);
            return redirect()->route('fm.faq.index')->with('success', 'Service created successfully');
        } catch (\Throwable $e) {
           session()->flash('error', $e->getMessage());
            return redirect()->back();
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        $data = $this->FaqService->getFaq($id);
        $data['creater_name'] = $this->creater_name($data);
        $data['updater_name'] = $this->updater_name($data);
        return response()->json($data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
       $data['faq'] = $this->FaqService->getFaq($id);
        return view('backend.admin.faq-management.faq.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FaqRequest $request, string $id)
    {
         try {
            $validated = $request->validated();
            $this->FaqService->updateFaq($this->FaqService->getFaq($id), $validated, $id);
            session()->flash('success', "Service updated successfully");
        } catch (\Throwable $e) {
            session()->flash('Service update failed');
            throw $e;
        }
        return $this->redirectIndex();
    }

    public function status(Request $request, string $id)
    {
       $faq = Faq::findOrFail(decrypt($id));
        $faq->update(['status' => !$faq->status, 'updated_by' => admin()->id]);
        session()->flash('success', 'Faq status updated successfully!');
        return redirect()->route('fm.faq.index');
    }

    /**
     * Remove the specified resource from storage.
     */
  public function destroy(string $id)
{
   $faq = Faq::findOrFail(decrypt($id));
        $faq->update(['deleted_by' => admin()->id]);
        $faq->delete();
        session()->flash('success', 'Faq deleted successfully!');
        return redirect()->route('fm.faq.index');
}

    // public function trash(Request $request)
    // {
    //     if ($request->ajax()) {
    //         $query = $this->FaqService->getFaqs()->onlyTrashed();
    //         return DataTables::eloquent($query)
    //             ->editColumn('deleted_by', function ($admin) {
    //                 return $this->deleter_name($admin);
    //             })
    //             ->editColumn('deleted_at', function ($admin) {
    //                 return $admin->deleted_at_formatted;
    //             })
    //             ->editColumn('action', function ($permission) {
    //                 $menuItems = $this->trashedMenuItems($permission);
    //                 return view('components.action-buttons', compact('menuItems'))->render();
    //             })
    //             ->rawColumns(['deleted_by', 'deleted_at', 'action'])
    //             ->make(true);
    //     }
    //     return view('view blade file url...');
    // }

    // protected function trashedMenuItems($model): array
    // {
    //     return [
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Restore',
    //             'permissions' => ['permission-restore']
    //         ],
    //         [
    //             'routeName' => '',
    //             'params' => [encrypt($model->id)],
    //             'label' => 'Permanent Delete',
    //             'p-delete' => true,
    //             'permissions' => ['permission-permanent-delete']
    //         ]

    //     ];
    // }

    //  public function restore(string $id): RedirectResponse
    // {
    //     try {
    //         $this->FaqService->restore($id);
    //         session()->flash('success', "Service restored successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service restore failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }

    // public function permanentDelete(string $id): RedirectResponse
    // {
    //     try {
    //         $this->FaqService->permanentDelete($id);
    //         session()->flash('success', "Service permanently deleted successfully");
    //     } catch (\Throwable $e) {
    //         session()->flash('Service permanent delete failed');
    //         throw $e;
    //     }
    //     return $this->redirectTrashed();
    // }
}
