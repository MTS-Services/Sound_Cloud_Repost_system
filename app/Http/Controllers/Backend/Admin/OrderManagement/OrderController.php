<?php

namespace App\Http\Controllers\Backend\Admin\OrderManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\OrderManagement\OrderRequest;
use App\Models\Order;
use App\Services\Admin\OrderManagement\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request)
    {

        if ($request->ajax()) {
            $query = $this->orderService->getOrders();
            return DataTables::eloquent($query)
                ->editColumn('user_urn', function ($order) {
                    return $order->user->name;
                })
                ->editColumn('credit', function ($order) {
                    return $order->credit;
                })
                ->editColumn('amount', function ($order) {
                    return $order->amount;
                })

                ->editColumn('status', fn($order) => "<span class='badge badge-soft {$order->status_color}'>{$order->status_label}</span>")

                ->editColumn('created_at', function ($order) {
                    return $order->created_at_formatted;
                })
                ->editColumn('action', function ($order) {
                    $menuItems = $this->menuItems($order);
                    return view('components.action-buttons', compact('menuItems'))->render();
                })
                ->rawColumns(['user_urn', 'credit', 'amount', 'created_by', 'action', 'created_at', 'status', 'start_date', 'end_date'])
                ->make(true);
        }
        return view('backend.admin.order-management.orders.index');
    }

    protected function menuItems($model): array
    {
        return [
            [
                'routeName' => 'om.order.detail',
                'params' => [encrypt($model->id)],
                'label' => ' Details',
                'permissions' => ['order-detail']
            ],
            // [
            //     'routeName' => 'javascript:void(0)',
            //     'data-id' => encrypt($model->id),
            //     'className' => 'view',
            //     'label' => 'Details',
            //     'permissions' => ['order-list', 'order-delete', 'order-status']
            // ],
            [
                'routeName' => 'om.order.status',
                'params' => [encrypt($model->id)],
                'label' => $model->status_btn_label,
                'className' => $model->status_btn_color,
                'permissions' => ['order-status']
            ],




        ];
    }

    public function detail($id)
    {
        $data['orders'] = Order::where('id', decrypt($id))->first();
        return view('backend.admin.order-management.orders.detail',$data);
    }

   public function show(Request $request, string $id)
{
    $data = $this->orderService->getOrder($id);
    $data['user_urn'] = $data->user?->name;

    // remove the following line
    // $data['updater_name'] = $this->updater_name($data);
    return response()->json($data);
}
    

   // app/Http/Controllers/Backend/Admin/OrderManagement/OrderController.php

public function status(string $id): RedirectResponse
{
    $data = $this->orderService->getOrder($id);
    $this->orderService->toggleStatus($data);
    session()->flash('success', 'Order status updated successfully!');
    return redirect()->route('om.order.index');
}
}
