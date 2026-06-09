<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard');
    });

    Route::prefix('admin')->group(function () {
        Route::get('/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'index'])->name('admin.residents.index');
        Route::get('/residents/non-active', [\App\Http\Controllers\Admin\ResidentController::class, 'nonActive'])->name('admin.residents.non-active');
        Route::post('/residents/register-out', [\App\Http\Controllers\Admin\ResidentController::class, 'registerOut'])->name('admin.residents.register-out');
        Route::post('/residents', [\App\Http\Controllers\Admin\ResidentController::class, 'store'])->name('admin.residents.store');
        Route::get('/residents/{id}', [\App\Http\Controllers\Admin\ResidentController::class, 'show'])->name('admin.residents.show');
        Route::put('/residents/{id}', [\App\Http\Controllers\Admin\ResidentController::class, 'update'])->name('admin.residents.update');
        Route::delete('/residents/{id}', [\App\Http\Controllers\Admin\ResidentController::class, 'destroy'])->name('admin.residents.destroy');
        
        Route::get('/vehicles', [\App\Http\Controllers\Admin\VehicleController::class, 'index'])->name('admin.vehicles.index');
        Route::post('/vehicles', [\App\Http\Controllers\Admin\VehicleController::class, 'store'])->name('admin.vehicles.store');
        Route::get('/vehicles/{id}', [\App\Http\Controllers\Admin\VehicleController::class, 'show'])->name('admin.vehicles.show');
        Route::put('/vehicles/{id}', [\App\Http\Controllers\Admin\VehicleController::class, 'update'])->name('admin.vehicles.update');
        Route::delete('/vehicles/{id}', [\App\Http\Controllers\Admin\VehicleController::class, 'destroy'])->name('admin.vehicles.destroy');

        Route::get('/id-cards', [\App\Http\Controllers\Admin\IdCardController::class, 'index'])->name('admin.id-cards.index');

        Route::post('/family-members', [\App\Http\Controllers\Admin\FamilyMemberController::class, 'store'])->name('admin.family-members.store');
        Route::get('/family-members/{id}', [\App\Http\Controllers\Admin\FamilyMemberController::class, 'show'])->name('admin.family-members.show');
        Route::put('/family-members/{id}', [\App\Http\Controllers\Admin\FamilyMemberController::class, 'update'])->name('admin.family-members.update');
        Route::delete('/family-members/{id}', [\App\Http\Controllers\Admin\FamilyMemberController::class, 'destroy'])->name('admin.family-members.destroy');

        Route::get('/finance', [\App\Http\Controllers\Admin\FinanceController::class, 'index'])->name('admin.finance.index');

        Route::get('/blocks', [\App\Http\Controllers\Admin\BlockController::class, 'index'])->name('admin.blocks.index');
        Route::post('/blocks', [\App\Http\Controllers\Admin\BlockController::class, 'store'])->name('admin.blocks.store');
        Route::get('/blocks/{id}', [\App\Http\Controllers\Admin\BlockController::class, 'show'])->name('admin.blocks.show');
        Route::put('/blocks/{id}', [\App\Http\Controllers\Admin\BlockController::class, 'update'])->name('admin.blocks.update');
        Route::delete('/blocks/{id}', [\App\Http\Controllers\Admin\BlockController::class, 'destroy'])->name('admin.blocks.destroy');
        Route::get('/blocks/{id}/units', [\App\Http\Controllers\Admin\BlockController::class, 'getUnits'])->name('admin.blocks.units');

        Route::get('/savings-programs', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'index'])->name('admin.savings.programs');
        Route::get('/savings-programs/{id}/details', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'details'])->name('admin.savings.programs.details');
        Route::post('/savings-programs', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'store'])->name('admin.savings.programs.store');
        Route::get('/savings-programs/{id}', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'show'])->name('admin.savings.programs.show');
        Route::put('/savings-programs/{id}', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'update'])->name('admin.savings.programs.update');
        Route::delete('/savings-programs/{id}', [\App\Http\Controllers\Admin\SavingsProgramController::class, 'destroy'])->name('admin.savings.programs.destroy');

        Route::get('/savings-deposits', [\App\Http\Controllers\Admin\SavingsTransactionController::class, 'index'])->name('admin.savings.deposits');
        Route::post('/savings-deposits', [\App\Http\Controllers\Admin\SavingsTransactionController::class, 'store'])->name('admin.savings.deposits.store');
        Route::delete('/savings-deposits/{id}', [\App\Http\Controllers\Admin\SavingsTransactionController::class, 'destroy'])->name('admin.savings.deposits.destroy');

        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
        Route::put('/settings/profile', [\App\Http\Controllers\Admin\SettingsController::class, 'updateProfile'])->name('admin.settings.profile.update');
        Route::post('/settings/staff', [\App\Http\Controllers\Admin\SettingsController::class, 'storeStaff'])->name('admin.settings.staff.store');
        Route::delete('/settings/staff/{id}', [\App\Http\Controllers\Admin\SettingsController::class, 'destroyStaff'])->name('admin.settings.staff.destroy');
        Route::put('/settings/finance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateFinance'])->name('admin.settings.finance.update');

        Route::get('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'index'])->name('admin.announcements.index');
        Route::post('/announcements', [\App\Http\Controllers\Admin\AnnouncementController::class, 'store'])->name('admin.announcements.store');
        Route::get('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'show'])->name('admin.announcements.show');
        Route::put('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'update'])->name('admin.announcements.update');
        Route::delete('/announcements/{id}', [\App\Http\Controllers\Admin\AnnouncementController::class, 'destroy'])->name('admin.announcements.destroy');

        Route::get('/under-construction', function () {
            return view('admin.under_construction');
        })->name('admin.under_construction');
    });

    Route::get('/warga', [\App\Http\Controllers\Warga\DashboardController::class, 'index'])->name('warga.dashboard');
    Route::get('/bank', function () {
        return view('bank.dashboard');
    });
    Route::get('/security', function () {
        return view('security.dashboard');
    });
});
