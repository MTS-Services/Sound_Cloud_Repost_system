<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProfileRequest; 
use App\Services\Admin\Profile\AdminProfileService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class AdminProfileController extends Controller implements HasMiddleware
{
    protected $adminProfileService;
public static function middleware(): array
    {
        return [
            'auth:admin', // Applies 'auth:admin' to all methods

            // Permission middlewares using the Middleware class
            new Middleware('permission:admin-profile-list', only: ['index']),
            new Middleware('permission:admin-profile-create', only: ['create', 'store']),
          
 
            //add more permissions if needed
        ];
    }
    public function __construct(AdminProfileService $adminProfileService)
    {
        $this->adminProfileService = $adminProfileService;
    }

    public function index()
    {
        $data['admin'] = $this->adminProfileService->getAdminProfile(encrypt(admin()->id));
        return view('backend.admin.profile.index', $data);
    }

    public function edit()
    {
        $data['admin'] = $this->adminProfileService->getAdminProfile(encrypt(admin()->id));
        return view('backend.admin.profile.edit', $data);
    }

    public function update(AdminProfileRequest $request)
    {
        $data= $this->adminProfileService->getAdminProfile(encrypt(admin()->id));

        $validatedData = $request->validated(); 

        $this->adminProfileService->updateProfile($data, $validatedData ,$request->file('image'));
        return redirect()->route('admin.profile.index')->with('success', 'Profile updated successfully!');
    }
}

