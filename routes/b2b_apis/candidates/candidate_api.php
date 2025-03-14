<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can member API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['checkUser']], function () {

    Route::post('create-invite-link-for-candidate', 'CandidateController@createInviteLinkForCandidate');
    Route::get('all-candidate-invites', 'CandidateController@allCandidates');
    Route::get('all-candidates','CandidateController@getAllCandidates');
    
    Route::post('candidate-to-member','CandidateController@ConvertCandidate');
    Route::get('delete-candidate','CandidateController@DeletesingleCandidate');
    Route::get('future-consideration-candidate','CandidateController@ArchivesingleCandidate');
    Route::get('all-future-consideration-candidates','CandidateController@AllArchiveCandidates');
    Route::get('all-deleted-candidates','CandidateController@AllDeletedCandidates');

});
