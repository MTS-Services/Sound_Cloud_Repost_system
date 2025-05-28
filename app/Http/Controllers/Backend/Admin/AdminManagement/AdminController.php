<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;


class AdminController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:admin');
    }

    public function index()
    {
        return view('backend.admin.admin-management.admin.index');
    }

    public function fetch(Request $request)
    {
        try {
            $query = Admin::query();

            // Apply filters if provided
            // if ($request->has('status') && $request->status !== '') {
            //     $query->where('status', $request->status);
            // }

            // if ($request->has('role') && $request->role !== '') {
            //     $query->where('role', $request->role);
            // }

            // Apply search if provided
            if ($request->has('search') && $request->search !== '') {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('role', 'LIKE', "%{$search}%");
                });
            }

            // Get data with pagination info
            $data = $query->select([
                'id',
                'name',
                'email',
                'created_at',
                'updated_at'
            ])
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a new admin record
     */
    public function save(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'role' => 'required|in:Administrator,Manager,User,Guest',
                'status' => 'required|in:Active,Inactive,Pending',
                'password' => 'nullable|string|min:8|confirmed'
            ]);

            // Set default password if not provided
            if (empty($validated['password'])) {
                $validated['password'] = 'password123'; // You might want to generate a random password
            }

            $validated['password'] = Hash::make($validated['password']);

            $user = Admin::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin created successfully',
                'data' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to create admin',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing admin record
     */
    public function update(Request $request, $id)
    {
        try {
            $user = Admin::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('users', 'email')->ignore($user->id)
                ],
                'role' => 'required|in:Administrator,Manager,User,Guest',
                'status' => 'required|in:Active,Inactive,Pending',
                'password' => 'nullable|string|min:8|confirmed'
            ]);

            // Only update password if provided
            if (!empty($validated['password'])) {
                $validated['password'] = Hash::make($validated['password']);
            } else {
                unset($validated['password']);
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin updated successfully',
                'data' => $user->fresh()
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Admin not found'
            ], 404);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to update admin',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete an admin record
     */
    public function delete($id)
    {
        try {
            $user = Admin::findOrFail($id);

            // Prevent deleting current user
            if ($user->id === auth()->id()) {
                return response()->json([
                    'error' => 'Cannot delete your own account'
                ], 403);
            }

            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin deleted successfully'
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Admin not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to delete admin',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:delete,activate,deactivate',
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer|exists:users,id'
            ]);

            $ids = $validated['ids'];
            $action = $validated['action'];

            // Prevent bulk operations on current user
            if (in_array(auth()->id(), $ids)) {
                return response()->json([
                    'error' => 'Cannot perform bulk operations on your own account'
                ], 403);
            }

            switch ($action) {
                case 'delete':
                    Admin::whereIn('id', $ids)->delete();
                    $message = 'Selected admins deleted successfully';
                    break;

                case 'activate':
                    Admin::whereIn('id', $ids)->update(['status' => 'Active']);
                    $message = 'Selected admins activated successfully';
                    break;

                case 'deactivate':
                    Admin::whereIn('id', $ids)->update(['status' => 'Inactive']);
                    $message = 'Selected admins deactivated successfully';
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Bulk operation failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export data
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            $query = Admin::query();

            // Apply same filters as fetch method
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            if ($request->has('role') && $request->role !== '') {
                $query->where('role', $request->role);
            }

            $data = $query->select([
                'id',
                'name',
                'email',
                'role',
                'status',
                'created_at'
            ])->get();

            switch ($format) {
                case 'csv':
                    return $this->exportToCsv($data);
                case 'json':
                    return $this->exportToJson($data);
                case 'excel':
                    return $this->exportToExcel($data);
                default:
                    return response()->json(['error' => 'Invalid format'], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Export failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export to CSV
     */
    private function exportToCsv($data)
    {
        $filename = 'admins_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($data) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Role', 'Status', 'Created At']);

            // Add data rows
            foreach ($data as $row) {
                fputcsv($file, [
                    $row->id,
                    $row->name,
                    $row->email,
                    $row->role,
                    $row->status,
                    $row->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export to JSON
     */
    private function exportToJson($data)
    {
        $filename = 'admins_' . date('Y-m-d_H-i-s') . '.json';

        return response()->json($data)
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    /**
     * Get statistics for dashboard
     */
    public function statistics()
    {
        try {
            $stats = [
                'total' => Admin::count(),
                'active' => Admin::where('status', 'Active')->count(),
                'inactive' => Admin::where('status', 'Inactive')->count(),
                'pending' => Admin::where('status', 'Pending')->count(),
                'by_role' => Admin::select('role')
                    ->selectRaw('count(*) as count')
                    ->groupBy('role')
                    ->pluck('count', 'role'),
                'recent' => Admin::where('created_at', '>=', now()->subDays(7))->count()
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to get statistics',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.admin-management.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     //
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
