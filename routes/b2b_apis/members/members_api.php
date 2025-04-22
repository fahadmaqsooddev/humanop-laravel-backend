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
    Route::post('create-invite-link-for-member', 'MemberController@createInviteLinkForMember');
    Route::post('edit-member', 'MemberController@EditMember');
    Route::post('add-member', 'MemberController@addMember');
    Route::get('all-members','MemberController@AllMembers');
    Route::delete('delete-member','MemberController@DeleteMember');
    Route::post('member-to-candidate','MemberController@ConvertMember');
    Route::get('all-member-invites', 'MemberController@allMemberInvites');
    Route::get('future-consideration-member','MemberController@ArchivesingleMember');
    Route::get('all-future-consideration-members','MemberController@AllArchiveMembers');
    Route::delete('delete-member-invite','MemberController@DeleteInvite');


});
