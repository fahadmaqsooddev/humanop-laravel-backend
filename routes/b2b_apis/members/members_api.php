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
    Route::post('create-invite-link-for-member', 'MemberController@createInviteLinkForMember')->middleware('check_package:add_members');
//    Route::post('edit-member', 'MemberController@EditMember');
//    Route::post('add-member', 'MemberController@addMember')->middleware('check_package:add_members');
    Route::get('all-members','MemberController@AllMembers');
    Route::delete('delete-member','MemberController@DeleteMember');
    Route::post('member-to-candidate','MemberController@ConvertMember');
    Route::get('all-member-invites', 'MemberController@allMemberInvites');
    Route::get('future-consideration-member','MemberController@ArchivesingleMember');
    Route::get('check-future-consideration-member-share-data','MemberController@checkFutureConsiderationShareData');
    Route::post('future-consideration-member-share-data','MemberController@futureConsiderationShareData');
    Route::get('all-future-consideration-members','MemberController@AllArchiveMembers');
    Route::delete('delete-member-invite','MemberController@DeleteInvite');
    Route::get('request-access-data','MemberController@requestAccessData');


});
