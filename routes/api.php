<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SouncCloud\Auth\SoundCloudController;


Route::post('/soundcloud/playlists', [SoundCloudController::class, 'storeApiPlaylists'])->name('soundcloud.api.playlists.store');
