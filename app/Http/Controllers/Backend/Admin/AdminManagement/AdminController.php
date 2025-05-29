<?php

namespace App\Http\Controllers\Backend\Admin\AdminManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Exception;


class AdminController extends Controller
{
    /**
     * Display the admin management page
     */
    public function index(): View
    {
        // Configuration for the DataTable component
        $tableConfig = [
            'id' => 'admin-table',
            'title' => 'User Management',
            'description' => 'Manage your administrators with advanced filtering and export options',
            'api_url' => route('am.admin.fetch'),
            'actions' => [
                'create' => route('am.admin.save'),
                'update' => 'am.admin.update', // Route name pattern for update
                'delete' => 'am.admin.delete', // Route name pattern for delete
                'bulk' => route('am.admin.bulk-action')
            ],
            'columns' => [
                [
                    'key' => 'id',
                    'label' => 'SL',
                    'sortable' => true,
                    'searchable' => false,
                    'type' => 'number'
                ],
                [
                    'key' => 'name',
                    'label' => 'Name',
                    'sortable' => true,
                    'searchable' => true,
                    'type' => 'text'
                ],
                [
                    'key' => 'email',
                    'label' => 'Email',
                    'sortable' => true,
                    'searchable' => true,
                    'type' => 'email'
                ],
                [
                    'key' => 'created_at',
                    'label' => 'Created At',
                    'sortable' => true,
                    'searchable' => false,
                    'type' => 'date'
                ],
                [
                    'key' => 'actions',
                    'label' => 'Actions',
                    'sortable' => false,
                    'searchable' => false,
                    'type' => 'actions'
                ]
            ],
            'form_fields' => [
                [
                    'name' => 'name',
                    'label' => 'Name',
                    'type' => 'text',
                    'required' => true,
                    'validation' => 'required|string|max:255'
                ],
                [
                    'name' => 'email',
                    'label' => 'Email',
                    'type' => 'email',
                    'required' => true,
                    'validation' => 'required|email|unique:users,email'
                ],
                [
                    'name' => 'password',
                    'label' => 'Password',
                    'type' => 'password',
                    'required' => true,
                    'validation' => 'required|min:8'
                ],
                [
                    'name' => 'role',
                    'label' => 'Role',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Administrator' => 'Administrator',
                        'Manager' => 'Manager',
                        'User' => 'User',
                        'Guest' => 'Guest'
                    ],
                    'validation' => 'required|in:Administrator,Manager,User,Guest'
                ],
                [
                    'name' => 'status',
                    'label' => 'Status',
                    'type' => 'select',
                    'required' => true,
                    'options' => [
                        'Active' => 'Active',
                        'Inactive' => 'Inactive',
                        'Pending' => 'Pending'
                    ],
                    'validation' => 'required|in:Active,Inactive,Pending'
                ]
            ]
        ];

        return view('backend.admin.admin-management.admin.index', compact('tableConfig'));
    }

    /**
     * Fetch data for DataTable via AJAX
     */
    public function fetch(Request $request): JsonResponse
    {
        try {
            $query = Admin::query();

            // Handle search
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                        ->orWhere('email', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Handle sorting
            $sortColumn = $request->get('sort_column', 'id');
            $sortDirection = $request->get('sort_direction', 'asc');

            if (in_array($sortColumn, ['id', 'name', 'email', 'created_at'])) {
                $query->orderBy($sortColumn, $sortDirection);
            }

            // Handle pagination
            $perPage = $request->get('per_page', 10);
            $page = $request->get('page', 1);

            $total = $query->count();
            $data = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            // Transform data for frontend
            $transformedData = $data->map(function ($user, $index) use ($page, $perPage) {
                return [
                    'id' => $user->id,
                    'sl' => ($page - 1) * $perPage + $index + 1,
                    'name' => $user->name,
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('Y-m-d'),
                    'actions' => $this->generateActionButtons($user->id)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $transformedData,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'last_page' => ceil($total / $perPage),
                    'from' => ($page - 1) * $perPage + 1,
                    'to' => min($page * $perPage, $total)
                ]
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching data: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Save new admin user
     */
    public function save(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'role' => 'required|in:Administrator,Manager,User,Guest',
                'status' => 'required|in:Active,Inactive,Pending'
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => bcrypt($validated['password']),
                'role' => $validated['role'],
                'status' => $validated['status']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Admin user created successfully',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update admin user
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'role' => 'required|in:Administrator,Manager,User,Guest',
                'status' => 'required|in:Active,Inactive,Pending'
            ]);

            // Only update password if provided
            if ($request->filled('password')) {
                $validated['password'] = bcrypt($request->password);
            }

            $user->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Admin user updated successfully',
                'data' => $user
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete admin user
     */
    public function delete($id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'success' => true,
                'message' => 'Admin user deleted successfully'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting user: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Handle bulk actions
     */
    public function bulkAction(Request $request): JsonResponse
    {
        try {
            $action = $request->get('action');
            $ids = $request->get('ids', []);

            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No items selected'
                ], 400);
            }

            switch ($action) {
                case 'delete':
                    User::whereIn('id', $ids)->delete();
                    $message = 'Selected users deleted successfully';
                    break;

                case 'activate':
                    User::whereIn('id', $ids)->update(['status' => 'Active']);
                    $message = 'Selected users activated successfully';
                    break;

                case 'deactivate':
                    User::whereIn('id', $ids)->update(['status' => 'Inactive']);
                    $message = 'Selected users deactivated successfully';
                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid action'
                    ], 400);
            }

            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error performing bulk action: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate action buttons HTML
     */
    private function generateActionButtons($id): string
    {
        return '
            <div class="flex items-center space-x-2">
                <button onclick="editRecord(' . $id . ')" 
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                    <i data-lucide="edit" class="w-4 h-4"></i>
                </button>
                <button onclick="deleteRecord(' . $id . ')" 
                        class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                </button>
            </div>
        ';
    }
}
