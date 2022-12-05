<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\SpendingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseDetailController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleDetailController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['middleware' => 'level:1'], function () {
        Route::get('/categories/data', [CategoryController::class, 'data'])->name('kategori.data');
        Route::resource('categories', CategoryController::class);

        Route::get('/products/data', [ProductController::class, 'data'])->name('produk.data');
        Route::post('/products/delete-selected', [ProductController::class, 'deleteSelected'])->name('products.deleteSelected');
        Route::post('/products/cetak-barcode', [ProductController::class, 'cetakBarcode'])->name('products.cetakBarcode');
        Route::resource('products', ProductController::class);

        Route::get('/suppliers/data', [SupplierController::class, 'data'])->name('supplier.data');
        Route::resource('suppliers', SupplierController::class);
        
        Route::get('/spendings/data', [SpendingController::class, 'data'])->name('spending.data');
        Route::resource('spendings', SpendingController::class);

        Route::get('/purchases/data', [PurchaseController::class, 'data'])->name('purchase.data');
        Route::get('purchases/{id}/create', [PurchaseController::class, 'create'])->name('purchases.create');
        Route::resource('purchases', PurchaseController::class)->except('create');

        Route::get('purchase-details/{id}/data', [PurchaseDetailController::class, 'data'])->name('purchase_details.data');
        Route::get('/purchase-details/loadform/{diskon}/{total}', [PurchaseDetailController::class, 'loadForm'])->name('purchase_details.load_form');
        Route::get('purchase-details', [PurchaseDetailController::class, 'index'])->name('purchase_details.index');
        Route::post('purchase-details', [PurchaseDetailController::class, 'store'])->name('purchase_details.store');
        Route::put('purchase-details/{id}', [PurchaseDetailController::class, 'update'])->name('purchase_details.update');
        Route::delete('purchase-details/{id}', [PurchaseDetailController::class, 'destroy'])->name('purchase_details.destroy');

        Route::get('/sales/data', [SaleController::class, 'data'])->name('sales.data');
        Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
        Route::get('/sales/{id}', [SaleController::class, 'show'])->name('sales.show');
        Route::delete('/sales/{id}', [SaleController::class, 'destroy'])->name('sales.destroy');

        Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
        Route::get('/laporan/data/{awal}/{akhir}', [LaporanController::class, 'data'])->name('laporan.data');
        Route::get('/laporan/pdf/{awal}/{akhir}', [LaporanController::class, 'exportPDF'])->name('laporan.export_pdf');

        Route::get('/users/data', [UserController::class, 'data'])->name('users.data');
        Route::resource('users', UserController::class);

        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::get('/settings/first', [SettingController::class, 'show'])->name('settings.show');
        Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
    });

    Route::group(['middleware' => 'level:1,2'], function () {
        Route::get('/transactions/create', [SaleController::class, 'create'])->name('transactions.create');
        Route::post('/transactions/simpan', [SaleController::class, 'store'])->name('transactions.simpan');
        Route::get('/transactions/finish', [SaleController::class, 'finish'])->name('transactions.finish');
        Route::get('/transactions/nota-kecil', [SaleController::class, 'notaKecil'])->name('transactions.nota_kecil');
        Route::get('/transactions/nota-besar', [SaleController::class, 'notaBesar'])->name('transactions.nota_besar');

        Route::get('/transactions/{id}/data', [SaleDetailController::class, 'data'])->name('transactions.data');
        Route::get('/transactions/loadform/{discount}/{total}/{received}', [SaleDetailController::class, 'loadForm'])->name('transactions.load_form');
        Route::resource('/transactions', SaleDetailController::class)
            ->except('create', 'show', 'edit');

        Route::get('/profile', [UserController::class, 'profile'])->name('users.profile');
        Route::post('/profile', [UserController::class, 'updateProfile'])->name('users.update_profile');
    });

});