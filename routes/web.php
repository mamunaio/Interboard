<?php

declare(strict_types=1);

use App\Livewire\Auth\Login;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\ListCustomers;
use App\Livewire\Dashboard\AdminDashboard;
use App\Livewire\Payments\CollectPayment;
use App\Livewire\Routers\RouterManager;
use Illuminate\Support\Facades\Route;

// Redirect root domain to the admin dashboard (will trigger auth check)
Route::redirect('/', '/dashboard');

// Auth Middleware Group: Restricts access strictly to authenticated operators
Route::middleware('auth')->group(function () {
    
    // Core Application Admin Dashboard
    Route::get('/dashboard', AdminDashboard::class)->name('dashboard');

    // Customer Management
    Route::get('/customers', ListCustomers::class)->name('customers.index');
    Route::get('/customers/create', CreateCustomer::class)->name('customers.create');
    Route::get('/customers/{customer}', fn() => 'Customer Details View (Stub)')->name('customers.show');
    Route::get('/customers/{customer}/edit', fn() => 'Customer Edit Form (Stub)')->name('customers.edit');

    // MikroTik Router Management
    Route::get('/routers', RouterManager::class)->name('routers.index');

    // Payments Collection & Ledger List
    Route::get('/payments', fn() => 'Payments Transaction Ledger (Stub)')->name('payments.index');
    Route::get('/payments/collect/{invoice}', CollectPayment::class)->name('payments.collect');
});

// Public Guest Route: Admin Authentication Login Portal
Route::get('/login', Login::class)->name('login');
