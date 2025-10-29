<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\Crypt;

Route::get('/encrypt', function () {
    $data = ['plan_id' => 1];
    return Crypt::encryptString(json_encode($data));
});

Route::get('/decrypt', function (Request $request) {
    $encrypted = $request->query('data');
    return json_decode(Crypt::decryptString($encrypted), true);
});


Route::get('/', function () {
    return view('welcome');
});
