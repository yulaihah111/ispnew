<?php

use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ReminderController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Admin\InvoiceController;


Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/', function (Request $request) {
        $user = $request->user();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('customer.dashboard');
    })->name('home');

    Route::prefix('admin')
        ->name('admin.')
        ->middleware('role:admin')
        ->group(function () {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

            Route::resource('packages', \App\Http\Controllers\Admin\PackageController::class)
                ->except(['show', 'create', 'edit']);

            Route::resource('customers', CustomerController::class)
                ->only(['index', 'store', 'destroy']);
            Route::patch('/customers/{customer}/update-contact', [CustomerController::class, 'updateContact'])->name('customers.update-contact');
            Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/invoices', [InvoiceController::class, 'store'])->name('invoices.store');
            Route::patch('/invoices/{invoice}/confirm', [InvoiceController::class, 'confirm'])->name('invoices.confirm');

            // Reminder WhatsApp
            Route::get('/reminders', [ReminderController::class, 'index'])->name('reminders.index');
        });

    Route::prefix('customer')
        ->name('customer.')
        ->middleware('role:customer')
        ->group(function () {
            Route::get('/dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
            
            // Invoices and transactions for Customers
            Route::get('/invoices', [\App\Http\Controllers\Customer\InvoiceController::class, 'index'])->name('invoices.index');
            Route::post('/invoices/{invoice}/pay', [\App\Http\Controllers\Customer\InvoiceController::class, 'pay'])->name('invoices.pay');
        });
});