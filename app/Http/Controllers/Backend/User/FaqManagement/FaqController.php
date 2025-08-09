<?php

namespace App\Http\Controllers\Backend\User\FaqManagement;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{

    protected $attributes = [];
    public function __construct($attributes = [])
{
    $this->attributes = $attributes ?? [];
}
     public function index()
    {
        
        return view('backend.user.faq-management.faq');
    }
}
