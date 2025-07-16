<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AdminProfileRequest; 
use App\Services\Admin\Profile\AdminProfileService;

class AdminProfileController extends Controller
{
    protected $adminProfileService;

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

