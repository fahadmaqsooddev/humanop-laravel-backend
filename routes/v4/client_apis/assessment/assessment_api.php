<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::group(['middleware' => ['checkUser','assessment.completed']], function () {

    // ✅ Assessment-related routes where middleware applies
    Route::get('all-assessments', 'AssessmentController@allAssessments');
    Route::get('assessment-answers', 'AssessmentController@assessmentAnswers');
    Route::get('grid', 'AssessmentController@grid');
    Route::get('user-report', 'AssessmentController@userReport');
    Route::get('intro-assessment', 'AssessmentController@introAssessment');
    Route::post('assessment-checkout', 'AssessmentController@assessmentCheckout');
    Route::post('create-styles-assessment', 'AssessmentController@createStylesAssessment');
    Route::post('assessment-watch-video-track', 'AssessmentController@assessmentWatchVideoTrack');
    Route::get('get-assessment-video-track', 'AssessmentController@getAssessmentVideoTrack');
    Route::get('user-assessment-details', 'AssessmentController@userAssessmentDetails');
    Route::get('trend-direction', 'HotSpotController@getTrendDirection');

    // ❌ Routes to bypass assessment.completed middleware
    Route::get('assessment-status', 'AssessmentController@assessmentStatus')->withoutMiddleware('assessment.completed');
    Route::get('questions', 'AssessmentController@questions')->withoutMiddleware('assessment.completed');
    Route::post('submit-assessment', 'AssessmentController@submitAnswers')->withoutMiddleware('assessment.completed');
});