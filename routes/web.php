<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return redirect('/login');
});

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Portal
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    Route::get('/', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::post('users/{user}/toggle', [UserController::class, 'toggleStatus'])->name('users.toggle');
    Route::post('users/{user}/force-logout', [UserController::class, 'forceLogout'])->name('users.force_logout');
    Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset_password');
    
    // Admin Portal Expansion Routes
    Route::resource('roles', App\Http\Controllers\Admin\AdminRoleController::class);
    Route::post('roles/{role}/clone', [App\Http\Controllers\Admin\AdminRoleController::class, 'clone'])->name('roles.clone');
    Route::get('permissions', [App\Http\Controllers\Admin\AdminPermissionController::class, 'index'])->name('permissions.index');
    Route::post('permissions', [App\Http\Controllers\Admin\AdminPermissionController::class, 'store'])->name('permissions.store');
    
    Route::get('portals', [App\Http\Controllers\Admin\AdminPortalController::class, 'index'])->name('portals.index');
    Route::post('portals', [App\Http\Controllers\Admin\AdminPortalController::class, 'store'])->name('portals.store');
    
    Route::get('security', [App\Http\Controllers\Admin\AdminSecurityController::class, 'index'])->name('security.index');
    Route::post('security', [App\Http\Controllers\Admin\AdminSecurityController::class, 'store'])->name('security.store');
    
    Route::get('logs', [App\Http\Controllers\Admin\AdminLogController::class, 'index'])->name('logs.index');
    
    Route::get('files', [App\Http\Controllers\Admin\AdminFileController::class, 'index'])->name('files.index');
    Route::delete('files/{file}', [App\Http\Controllers\Admin\AdminFileController::class, 'destroy'])->name('files.destroy');
    
    Route::get('settings', [App\Http\Controllers\Admin\AdminSettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\AdminSettingController::class, 'store'])->name('settings.store');
    
    Route::get('backups', [App\Http\Controllers\Admin\AdminBackupController::class, 'index'])->name('backups.index');
    Route::post('backups/create', [App\Http\Controllers\Admin\AdminBackupController::class, 'create'])->name('backups.create');
    Route::get('backups/{file}/download', [App\Http\Controllers\Admin\AdminBackupController::class, 'download'])->name('backups.download');
    Route::post('backups/{file}/restore', [App\Http\Controllers\Admin\AdminBackupController::class, 'restore'])->name('backups.restore');
});

// Operations Portal
Route::prefix('ops')->middleware(['auth', 'role:operations'])->name('ops.')->group(function () {
    Route::get('/', [App\Http\Controllers\Ops\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('clients', App\Http\Controllers\Ops\ClientController::class);
    
    // Passport Module
    Route::get('passport', [App\Http\Controllers\Ops\PassportController::class, 'index'])->name('passport.index');
    Route::get('passport/create/individual', [App\Http\Controllers\Ops\PassportController::class, 'createIndividual'])->name('passport.create.individual');
    Route::get('passport/create/organization', [App\Http\Controllers\Ops\PassportController::class, 'createOrganization'])->name('passport.create.organization');
    Route::post('passport', [App\Http\Controllers\Ops\PassportController::class, 'store'])->name('passport.store');
    Route::get('passport/{document}/edit', [App\Http\Controllers\Ops\PassportController::class, 'edit'])->name('passport.edit');
    Route::put('passport/{document}', [App\Http\Controllers\Ops\PassportController::class, 'update'])->name('passport.update');
    Route::delete('passport/{document}', [App\Http\Controllers\Ops\PassportController::class, 'destroy'])->name('passport.destroy');

    // Invoice Module
    Route::get('invoice', [App\Http\Controllers\Ops\InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('invoice/create/individual', [App\Http\Controllers\Ops\InvoiceController::class, 'createIndividual'])->name('invoice.create.individual');
    Route::get('invoice/create/organization', [App\Http\Controllers\Ops\InvoiceController::class, 'createOrganization'])->name('invoice.create.organization');
    Route::post('invoice', [App\Http\Controllers\Ops\InvoiceController::class, 'store'])->name('invoice.store');
    Route::get('invoice/{invoice}/edit', [App\Http\Controllers\Ops\InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::put('invoice/{invoice}', [App\Http\Controllers\Ops\InvoiceController::class, 'update'])->name('invoice.update');
    Route::delete('invoice/{invoice}', [App\Http\Controllers\Ops\InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::get('invoice/{invoice}/pdf', [App\Http\Controllers\Ops\InvoiceController::class, 'generatePdf'])->name('invoice.pdf');
    // Tickets Module
    Route::get('tickets', [App\Http\Controllers\Ops\TicketController::class, 'index'])->name('tickets.index');
    Route::get('tickets/create', [App\Http\Controllers\Ops\TicketController::class, 'create'])->name('tickets.create');
    Route::post('tickets', [App\Http\Controllers\Ops\TicketController::class, 'store'])->name('tickets.store');
    Route::delete('tickets/{ticket}', [App\Http\Controllers\Ops\TicketController::class, 'destroy'])->name('tickets.destroy');

    Route::resource('organizations', App\Http\Controllers\Ops\OrganizationController::class);
});

    // Sales Portal
    Route::middleware(['auth', 'role:sales|admin'])->prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [App\Http\Controllers\Sales\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('ebm', App\Http\Controllers\Sales\EbmController::class);
        Route::get('tax', [App\Http\Controllers\Sales\TaxController::class, 'index'])->name('tax.index');
        Route::get('tax/create', [App\Http\Controllers\Sales\TaxController::class, 'create'])->name('tax.create');
        Route::post('tax', [App\Http\Controllers\Sales\TaxController::class, 'store'])->name('tax.store');
        Route::put('tax/{tax}', [App\Http\Controllers\Sales\TaxController::class, 'update'])->name('tax.update');
        Route::resource('payments', App\Http\Controllers\Sales\ClientPaymentController::class);
        Route::resource('rra', App\Http\Controllers\Sales\RraFileController::class);
        Route::resource('invoices', App\Http\Controllers\Sales\InvoiceTrackingController::class)->only(['index', 'update']);
    });

// MD Portal
Route::prefix('md')->middleware(['auth', 'role:md'])->name('md.')->group(function () {
    Route::get('/', [App\Http\Controllers\Md\DashboardController::class, 'index'])->name('dashboard');
    
    // Governance Expansion
    Route::get('activity', [App\Http\Controllers\Md\ActivityController::class, 'index'])->name('activity.index');
    Route::get('employees', [App\Http\Controllers\Md\EmployeeController::class, 'index'])->name('employees.index');
    
    // Corporate Document Vault
    Route::resource('vault', App\Http\Controllers\Md\CorporateDocumentController::class);
    Route::resource('folders', App\Http\Controllers\Md\CorporateFolderController::class);
    Route::post('folders/{folder}/import', [App\Http\Controllers\Md\CorporateFolderController::class, 'import'])->name('folders.import');
    Route::post('folders/{folder}/upload', [App\Http\Controllers\Md\CorporateFolderController::class, 'upload'])->name('folders.upload');
});
