<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Backend\CustomAuthController;
use App\Http\Controllers\Backend\HomeController;
use App\Http\Controllers\Backend\AgentController;
use App\Http\Controllers\Backend\PartyController;
use App\Http\Controllers\Backend\ExcelController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Backend\ShipmentController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Backend\TransferBalanceController;

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
//     return view('Backend.Layouts.app');
// });

Route::namespace('Admin')->prefix('admin')->group(function () {
    Route::get('/',[CustomAuthController::class,'index'])->name('login');
    Route::post('custom-login', [CustomAuthController::class,'customLogin'])->name('login.custom');
    Route::get('logout',[CustomAuthController::class,'signOut'])->name('logout');
    Route::get('home',[HomeController::class,'index'])->name('admin.home');

    // Agent Data
    Route::get('agent',[AgentController::class,'index'])->name('agent');
    Route::any('addagent',[AgentController::class,'AddAgent'])->name('add.agent');
    Route::any('editagent',[AgentController::class,'EditAgent'])->name('edit.agent');
    Route::any('deleteagent',[AgentController::class,'DeleteAgent'])->name('delete.agent');

    // User

    Route::get('user',[UserController::class,'index'])->name('user');
    Route::any('adduser',[UserController::class,'AddUser'])->name('add.user');
    Route::any('edituser',[UserController::class,'EditUser'])->name('edit.user');
    Route::any('deleteuser',[UserController::class,'DeleteUser'])->name('delete.user');

    // Party
    Route::get('party',[PartyController::class,'index'])->name('party');
    Route::any('addparty',[PartyController::class,'AddParty'])->name('add.party');
    Route::any('editparty',[PartyController::class,'EditParty'])->name('edit.party');
    Route::any('deleteparty',[PartyController::class,'DeleteParty'])->name('delete.party');

    // Excel generate
    Route::any('excel',[ExcelController::class,'index'])->name('excel');
    Route::any('upload',[ExcelController::class,'UploadExcel'])->name('upload');
    Route::any('diamondlist',[ExcelController::class,'DiamondList'])->name('diamond.list');

    Route::any('download/{filename}',[ExcelController::class,'getfile'])->name('download.file');

    Route::any('edit/{id}',[ExcelController::class,'Edit'])->name('edit');
    Route::any('update/diamond',[ExcelController::class,'UpdateDiamond'])->name('update.diamond');
    Route::any('delete/{id}',[ExcelController::class,'deleteDiamond'])->name('delete');


    // Report
    Route::any('report',[ReportController::class,'index'])->name('report');
    Route::any('export/csv',[ReportController::class,'exportCSVFile'])->name('export.csv');
    Route::any('generate/pdf',[ReportController::class,'generatePDF'])->name('generate.pdf');

    Route::any('agent/report',[ReportController::class,'AgentWise'])->name('agent.report');
    Route::any('agent/export/csv',[ReportController::class,'AgentexportCSVFile'])->name('agent.export.csv');
    Route::any('agent/generate/pdf',[ReportController::class,'AgentgeneratePDF'])->name('agent.generate.pdf');

    Route::any('sold/report',[ReportController::class,'SoldWise'])->name('sold.report');
    Route::any('sold/export/csv',[ReportController::class,'SoldexportCSVFile'])->name('sold.export.csv');
    Route::any('sold/generate/pdf',[ReportController::class,'SoldgeneratePDF'])->name('sold.generate.pdf');
    Route::any('pending/delivery',[ReportController::class,'PendingDelivery'])->name('pending.delivery');
    Route::any('stock/reject',[ReportController::class,'StockReject'])->name('stock.reject');
    Route::any('ledger',[ReportController::class,'ledger'])->name('ledger');





    Route::any('notification',[NotificationController::class,'Index'])->name('notification');


    //shipment mode
    Route::get('shipment',[ShipmentController::class,'index'])->name('shipment');
    Route::any('addshipment',[ShipmentController::class,'AddShipment'])->name('add.shipment');
    Route::any('editshipment',[ShipmentController::class,'EditShipment'])->name('edit.shipment');
    Route::any('deleteshipment',[ShipmentController::class,'DeleteShipment'])->name('delete.shipment');

    Route::any('transfer',[TransferBalanceController::class,'index'])->name('transfer.menu');
    Route::any('transfer/store',[TransferBalanceController::class,'store'])->name('transfer.store');
    Route::any('paymennt/list',[TransferBalanceController::class,'payment'])->name('payment.list');
    Route::any('collection/order',[TransferBalanceController::class,'Collection'])->name('collection.order');



    // Route::get('transfer/delete/{id}',[TransferBalanceController::class,'Delete'])->name('transfer.menu.delete');



});




