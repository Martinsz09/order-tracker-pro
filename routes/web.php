<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DispatchController;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('auth')->group(function () {
    Route::get("/", function () {
        return Redirect("/dashboard");
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get("/dashboard", [DispatchController::class, "index"]);
    Route::get("/dashboard/orders/create", [DispatchController::class, "create"]);
    Route::post("/dashboard/orders/create", [DispatchController::class, "store"]);
    });

require __DIR__.'/auth.php';
