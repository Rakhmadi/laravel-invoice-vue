<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthToken;
use App\Http\Controllers\product_;
use App\Http\Controllers\customers_;
use App\Http\Controllers\InvoiceHandel;
header("Access-Control-Allow-Origin: *");
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register',[AuthToken::class,'register'] );
Route::post('/login',[AuthToken::class,'login']);
Route::get('/logout',[AuthToken::class,'logout']);
Route::post('/cektoken',[AuthToken::class,'AuthCheck']);
Route::group(['middleware' => ['auth.token']], function () {
    // Product CRUD
    Route::group(['prefix' => 'product'], function () {
        Route::get('/',[product_::class,'showProduct']);
        Route::post('/save',[product_::class,'saveProduct']);
        Route::get('/get/{id}',[product_::class,'getID']);
        Route::put('/update/{id}',[product_::class,'updateProduct']);
        Route::delete('/delete/{id}',[product_::class,'deleteProduct']);
    });
     // Costumer CRUD
    Route::group(['prefix' => 'customer'], function () {
         Route::get('/',[customers_::class,'getCustomers']);
         Route::post('/save',[customers_::class,'createCustomers']);
         Route::get('/get/{id}',[customers_::class,'getId']);
         Route::put('/update/{id}',[customers_::class,'updateCustomers']);
         Route::delete('/delete/{id}', [customers_::class,'deleteCustomers']);
    });
    // invoice crud
    Route::group(['prefix' => 'invoice'], function () {
        Route::post('/make',[InvoiceHandel::class,'CreateInv']);
        Route::post('/make_details/{id}',[InvoiceHandel::class,'Invoicedetailsave']);
        Route::get('/getInv/{id}',[InvoiceHandel::class,'getInvloce']);
        Route::get('/getInvDetails/{id}',[InvoiceHandel::class,'getinvDetails']);
        Route::post('/datadash', [InvoiceHandel::class,'DashboardData']);
        Route::delete('/deletedetai/{id}/{idinv}/{mnqty}/{prdk_id}', [InvoiceHandel::class,'deletedetai']);
        Route::get('/getinvoiceslist',[InvoiceHandel::class,'getinvcuslist']);
        Route::delete('/deleteinv/{id}',[InvoiceHandel::class,'deleteinv']);



    });
});

