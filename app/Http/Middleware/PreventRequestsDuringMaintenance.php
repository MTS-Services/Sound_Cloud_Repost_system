<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while in maintenance mode.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    /**
     * The IP addresses that should be allowed while in maintenance mode.
     *
     * @var array<int, string>
     */
    protected $allow = [
        '192.168.10.15',
        '221.120.101.241'
    ];
}
