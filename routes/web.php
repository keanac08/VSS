<?php

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

// Route::get('/', function () {
//     return view('dashboard');
// });

// Route::group(['middleware' => ['auth:oracle_user, ipc_portal_user']], function () {
Route::middleware(['auth:oracle_user,ipc_portal_user'])->group(function () { //--> Authenticated Users

    Route::view('/', 'dashboard');;
    Route::view('/dashboard', 'dashboard')->name('dashboard');
    Route::view('/wb-entry', 'warranty_booklet.entry')->name('wb.entry');
    Route::view('/wb-allocate', 'warranty_booklet.allocate')->name('wb.allocate');
    Route::view('/invoice-print', 'invoice.print')->name('invoice.print');

    //warranty booklet
    Route::post('/wb-store', 'WbController@store');
    Route::get('/wb-inventory', 'WbController@inventory')->name('wb.inventory');
    Route::get('/wb-fetch', 'WbController@fetchRequiredWbs')->name('wb.fetch');
    Route::patch('/wb-update', 'WbController@updateAllocatedWb')->name('wb.update');

    //reports
    Route::post('/pdf/receiving-copy', 'Reports\Pdf\ReceivingController@index')->name('pdf.receiving');
    Route::post('/pdf/quality', 'Reports\Pdf\QualityController@index')->name('pdf.quality');
    Route::post('/pdf/invoice', 'Reports\Pdf\InvoiceController@index')->name('pdf.invoice');
    Route::post('/pdf/delivery', 'Reports\Pdf\DeliveryController@index')->name('pdf.delivery');

    //invoice
    Route::get('/invoice-fetch-allocation', 'InvoiceController@fetchForAllocation');
    Route::get('/invoice-fetch-print', 'InvoiceController@fetchForPrint');

    //dashboard
    Route::get('/dashboard-data', 'DashboardController@fetchDashboardData');

    Route::view('/dashboard-today', 'dashboard.today')->name('dashboard.today');
    Route::get('/fetch-dashboard-today', 'DashboardController@fetchInvoiceToday');

    Route::view('/dashboard-pending', 'dashboard.pending')->name('dashboard.pending');
    Route::get('/fetch-dashboard-pending', 'DashboardController@fetchPendingInvoice');

    Route::view('/dashboard-tagged', 'dashboard.tagged')->name('dashboard.tagged');
    Route::get('/fetch-dashboard-tagged', 'DashboardController@fetchTagged');

    Route::view('/dashboard-wb', 'dashboard.wb')->name('dashboard.wb');
    Route::get('/fetch-dashboard-wb', 'DashboardController@fetchWb');

    Route::get('/fetch-dashboard-monthly', 'DashboardController@fetchDashboardMonthlySummary');


    //customer
    Route::get('/customer-list-allocation', 'CustomerController@fetchForWbAllocation');
    Route::get('/customer-list-print', 'CustomerController@fetchForInvoicePrint');

});

//login - logout
Route::get('login/{user_id}/{source_id}', 'Auth\LoginController@authenticate');
Route::get('logout', 'Auth\LogoutController@logout')->name('logout');





