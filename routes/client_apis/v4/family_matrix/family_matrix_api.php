<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can dashboard API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['checkUser']], function () {

    Route::prefix('v4/family-matrix/')->group(function () {

        Route::get('analyze', 'FamilyMatrixController@familyMatrixAnalyze');

    });

    Route::get('all-family-matrix-relationship', 'FamilyMatrixController@allFamilyMatrixRelationship');
    Route::post('assign-family-matrix-relationship', 'FamilyMatrixController@assignFamilyMatrixRelationship');
    Route::get('all-assign-family-matrix-relationship', 'FamilyMatrixController@allAssignFamilyMatrixRelationship');
    Route::delete('delete-assign-family-matrix-relationship', 'FamilyMatrixController@deleteAssignFamilyMatrixRelationship');
    Route::put('permission-family-matrix-relationship', 'FamilyMatrixController@giveConsent');

    //Family Note Routes

    Route::post('family-matrix-relationship-note', 'FamilyMatrixController@addFamilyMatrixNotes');
    Route::get('show-note-family-matrix', 'FamilyMatrixController@showFamilyMatrixNote');
    Route::put('update-note-family-matrix', 'FamilyMatrixController@updateFamilyMatrixNotes');
    Route::delete('delete-note-family-matrix', 'FamilyMatrixController@deleteFamilyMatrixNotes');
    Route::get('all-note-family-matrix-relationship','FamilyMatrixController@viewFamilyMatrixNotes');

});
