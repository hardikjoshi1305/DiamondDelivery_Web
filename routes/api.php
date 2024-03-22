<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AgentLoginController;
use App\Http\Controllers\Api\AgentDetailsController;
use App\Http\Controllers\Api\AcceptController;
use App\Http\Controllers\Api\RejectController;
use App\Http\Controllers\Api\PDFController;
use App\Http\Controllers\Api\AgeentDetailsController;
use App\Http\Controllers\Api\ShapeController;
use App\Http\Controllers\Api\ColorController;
use App\Http\Controllers\Api\SoldController;
use App\Http\Controllers\Api\PendingController;
use App\Http\Controllers\Api\TransferListController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("login",[AdminController::class,'login']);
Route::post("agentlogin",[AgentLoginController::class,'Agentlogin']);

Route::group(['middleware' => 'auth.api'], function () {

Route::get("agentdetails",[AgentDetailsController::class,'AgentDetails']);

Route::post("orderaccept",[AcceptController::class,'OrderAccept']);
Route::any("transfermoney",[AcceptController::class,'Remark']);
Route::any("pendingpayment",[AcceptController::class,'PendingPayment']);
Route::any("addpayment",[AcceptController::class,'AddPayment']);
Route::any("fetch_transaction",[AcceptController::class,'FetchTransaction']);


Route::post("orderreject",[RejectController::class,'OrderReject']);
Route::post("my_stock",[RejectController::class,'OrderRejectDetail']);

Route::any("diamondlist/pdf",[PDFController::class,'PDF']);
Route::any("agentwise/pdf",[PDFController::class,'AgentWisePDF']);
Route::any("soldwise/pdf",[PDFController::class,'SoldWisePDF']);
Route::any("allagent",[PDFController::class,'AllAgent']);
Route::any("clientwise",[AgeentDetailsController::class,'AgentDetails']);
Route::any("datewise",[AgeentDetailsController::class,'DateWise']);
Route::any("addtocart",[AgeentDetailsController::class,'AddToCart']);
Route::get("gettocart",[AgeentDetailsController::class,'GetCartList']);
Route::post("cartdelete",[AgeentDetailsController::class,'DeleteCart']);
Route::post("multiorderaccept",[AgeentDetailsController::class,'MultiOrderAccept']);
Route::get("sharecarlist",[AgeentDetailsController::class,'ExcelGetCartList']);


Route::any("shape",[ShapeController::class,'Shape']);
Route::any("clarity",[ShapeController::class,'clarity']);

Route::any("color",[ColorController::class,'Color']);
Route::any("sold",[SoldController::class,'Sold']);
Route::any("pending",[PendingController::class,'pending']);
Route::any("Transferlist",[TransferListController::class,'TransferList']);
Route::any("SendMoney",[TransferListController::class,'SendMoney']);
Route::any("AgentList",[TransferListController::class,'AgentList']);
Route::any("ClientList",[TransferListController::class,'ClientList']);
Route::any("WalletBalance",[TransferListController::class,'walletbalance']);
Route::any("LedgerList",[TransferListController::class,'AdvancePaymentList']);




});

