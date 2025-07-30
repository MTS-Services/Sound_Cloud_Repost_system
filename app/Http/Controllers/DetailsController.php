<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DetailsController extends Controller
{
    /**
     * Show the details page.
     */
    public function Details()
    {
        // Logic to retrieve details can be added here
        return view('details'); // Assuming you have a view named 'details.show'
    }
}
