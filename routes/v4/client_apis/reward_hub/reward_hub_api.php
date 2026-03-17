<?php
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['checkUser','assessment.completed']], function () {
    Route::get('rewards-impact', 'ImpactProjectController@index');
    Route::post('rewards-impact-contribute','ImpactProjectController@contribute');
    Route::get('impact-project-logs','ImpactProjectController@impactLogs');
    Route::get('rewards-logs','ImpactProjectController@rewardLogs');
});
