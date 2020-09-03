<?php

use Illuminate\Support\Facades\Route;

Route::get('/payments', 'PaymentsController@create');
Route::post('/payments', 'PaymentsController@store');
Route::get('/thankyou', 'PaymentsController@thankyou');
