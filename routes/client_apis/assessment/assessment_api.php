<?php

use Illuminate\Support\Facades\Route;
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

Route::get('all-assessments','AssessmentController@allAssessments');
Route::get('assessment-answers', 'AssessmentController@assessmentAnswers');
Route::get('grid', 'AssessmentController@grid');
Route::get('assessment-status', 'AssessmentController@assessmentStatus');
Route::get('questions', 'AssessmentController@questions');
Route::post('submit-assessment', 'AssessmentController@submitAnswers');
