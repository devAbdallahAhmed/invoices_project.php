<?php

use App\Http\Controllers\ArchiveInvoice;
use App\Http\Controllers\CustomersReport;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceAttachmentsController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceDetailsController;
use App\Http\Controllers\InvoiceReport;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SectionController;
use App\Http\Controllers\UserController;
use App\Models\Invoice_details;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('auth.login');
});
require __DIR__.'/auth.php';
//Auth::routes(['register' => false]);


Route::get('/dashboard',[DashboardController::class,'index'])
    ->middleware(['auth'])->name('dashboard');

Route::resource('invoices',InvoiceController::class)->middleware('auth');
Route::resource('sections',SectionController::class)->middleware('auth');
Route::resource('products',ProductController::class)->middleware('auth');
Route::resource('InvoiceAttachments',InvoiceAttachmentsController::class)->middleware('auth');
Route::resource('Archive_Invoice',ArchiveInvoice::class);


Route::group(['middleware' => ['auth']], function () {
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
});

Route::get('section/{id}',[InvoiceController::class,'getProducts']);
Route::get('InvoicesDetails/{id}',[InvoiceDetailsController::class,'edit']);
Route::get('Status_show/{id}',[InvoiceController::class,'show'])->name('Status_show');
Route::post('Status_update/{id}',[InvoiceController::class,'Status_update'])->name('Status_update');
Route::get('download/{invoice_number}/{file_name}', [ InvoiceDetailsController::class,'downloadFile']);
Route::get('view_file/{invoice_number}/{file_name}',[InvoiceDetailsController::class,'open_file']);
Route::post('delete_file',[InvoiceDetailsController::class,'destroy']);
Route::get('Invoice_Paid',[InvoiceController::class,'Invoice_Paid']);
Route::get('Invoice_Unpaid',[InvoiceController::class,'Invoice_Unpaid']);
Route::get('Invoice_Partial',[InvoiceController::class,'Invoice_Partial']);
Route::get('Print_invoice/{id}',[InvoiceController::class,'Print_invoice']);
Route::get('export_invoices',[InvoiceController::class,'export']);
Route::get('MarkAsRead_all',[InvoiceController::class,'MarkAsRead_all'])->name('MarkAsRead_all');

Route::get('invoices_report',[InvoiceReport::class,'index']);
Route::post('Search_invoices', [InvoiceReport::class,'Search_invoices']);

Route::get('customers_report', [CustomersReport::class,'index'])->name("customers_report");
Route::post('Search_customers', [CustomersReport::class,'Search_customers'])->name('Search_customers');
Route::get('edit_invoice/{id}',[InvoiceController::class,'edit']);


Route::get('/{page}', [\App\Http\Controllers\AdminController::class,'index']);
