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
    Route::post('permission-family-matrix-relationship', 'FamilyMatrixController@giveConsent');
//    Route::post('family-matrix-relationship-note', 'FamilyMatrixController@addFamilyMatrixNote');
//    // Update a note
//    Route::put('edit-note-family-matrix', 'FamilyMatrixController@updateFamilyMatrixNote');
//    // Delete a note
//    Route::delete('delete-note-family-matrix', 'FamilyMatrixController@deleteFamilyMatrixNote');
//    Route::get('all-note-family-matrix-relationship','FamilyMatrixNoteController@viewFamilyMatrixNotes');

});
