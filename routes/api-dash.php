<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BlogController;



Route::apiResource('blog', BlogController::class);






