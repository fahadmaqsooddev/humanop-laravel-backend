<?php 
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;


Route::get('/deploy', function () {
    Artisan::call('deploy:run');

    return response()->json([
        'output' => Artisan::output()
    ]);
});