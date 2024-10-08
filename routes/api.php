<?php

use App\Http\Controllers\Api\BestSellers;
use Illuminate\Support\Facades\Route;

Route::get('/1/nyt/best-sellers', BestSellers::class);
