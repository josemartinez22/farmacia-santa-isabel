<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ReportsController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

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

Route::redirect('/', '/login');


Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])
                ->name('users')
                ->middleware('permission:view-user');
    Route::get('/users/create', [UserController::class, 'create'])
                ->name('user.create')
                ->middleware('permission:create-user');
    Route::post('/users/create', [UserController::class, 'store'])
                ->middleware('permission:create-user');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
                ->name('user.edit')
                ->middleware('permission:update-user');
    Route::put('/users/{user}', [UserController::class, 'update'])
                ->name('user.update')
                ->middleware('permission:update-user');
    Route::get('/users/download/backup', [UserController::class, 'download_backup'])
                ->name('download.backup')
                ->middleware('role:Administrador');
});

Route::middleware('auth')->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])
                ->name('categories')
                ->middleware('permission:view-category');
    Route::get('/categories/create', [CategoryController::class, 'create'])
                ->name('category.create')
                ->middleware('permission:create-category');
    Route::post('/categories/create', [CategoryController::class, 'store'])
                ->middleware('permission:create-category');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
                ->name('category.edit')
                ->middleware('permission:update-category');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
                ->name('category.update')
                ->middleware('permission:update-category');
});

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])
                ->name('products')
                ->middleware('permission:view-product');
    Route::get('/products/create', [ProductController::class, 'create'])
                ->name('product.create')->middleware('permission:create-product');
    Route::post('/products/create', [ProductController::class, 'store'])
                ->middleware('permission:create-product');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
                ->name('product.edit')
                ->middleware('permission:update-product');
    Route::put('/products/{product}', [ProductController::class, 'update'])
                ->name('product.update')
                ->middleware('permission:update-product');
});

Route::middleware('auth')->group(function () {
    Route::get('/suppliers', [SupplierController::class, 'index'])
                ->name('suppliers')
                ->middleware('permission:view-supplier');
    Route::get('/suppliers/create', [SupplierController::class, 'create'])
                ->name('supplier.create')
                ->middleware('permission:create-supplier');
    Route::post('/suppliers/create', [SupplierController::class, 'store'])
                ->middleware('permission:create-supplier');
    Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])
                ->name('supplier.edit')
                ->middleware('permission:update-supplier');
    Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])
                ->name('supplier.update')
                ->middleware('permission:update-supplier');
});

Route::middleware('auth')->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'index'])
                ->name('purchases')
                ->middleware('permission:view-purchase');
    Route::get('/purchases/create', [PurchaseController::class, 'create'])
                ->name('purchase.create')
                ->middleware('permission:create-purchase');
    Route::post('/purchases/create', [PurchaseController::class, 'store'])
                ->middleware('permission:create-purchase');
});

Route::middleware(['auth', 'permission:view-cart'])->group(function () {
    Route::get('/cart', [CartController::class, 'index'])->name('cart');
    Route::post('/cart/{productid}/add', [CartController::class, 'store'])->name('cart.store');
    Route::post('/cart/{cart}', [CartController::class, 'attach'])->name('cart.update');
    Route::post('/cart/checkout/{cart}/', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/cart/{cartid}/{productid}/', [CartController::class, 'detach'])->name('cart.destroy');
});

Route::middleware(['auth', 'permission:view-sale'])->group(function () {
    Route::get('/sales', [SaleController::class, 'index'])->name('sales');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sale.show');
    Route::get('/invoice/{saleID}', [ReportsController::class, 'invoicePDF']);
});

Route::middleware(['auth', 'permission:view-reports'])->group(function () {
    Route::get('/reports', [ReportsController::class, 'index'])->name('reports');
    Route::get('/reports/pdf/{user}/{type}/{f1}/{f2}', [ReportsController::class, 'reportPDF']);
    Route::get('/reports/pdf/{user}/{type}', [ReportsController::class, 'reportPDF']);
    Route::get('/reports/excel/{user}/{type}/{f1}/{f2}', [ReportsController::class, 'reportExcel']);
    Route::get('/reports/excel/{user}/{type}', [ReportsController::class, 'reportExcel']);
});

Route::middleware('auth')->group(function () {
    Route::inertia('/manuals', 'Manuals/Index');
});

Route::get('/img/{path}', [ImagesController::class, 'show'])
    ->where('path', '.*')
    ->name('image');

require __DIR__.'/auth.php';
