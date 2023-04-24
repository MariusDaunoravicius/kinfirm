<?php

declare(strict_types=1);

use App\Http\Controllers\V1\Products\IndexController;
use App\Http\Controllers\V1\Products\ShowController;

Route::get(
    uri: '/{sku}',
    action: ShowController::class,
)->name('show');

Route::get(
    uri: '/',
    action: IndexController::class,
)->name('index');
