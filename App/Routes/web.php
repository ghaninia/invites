<?php
use App\Core\Route ;
//tools and widget
Route::get("/captcha" , "CaptchaController@index" , "captchaIndex" );

//Download Link And Counter
Route::get("/dl/(:any)", "DownloadsController@index" , "downloadsIndex") ;

//code invites
Route::get("(:any)" , "InviteController@code" , "invitesCode" );
Route::post("register" , "InviteController@register" , "register") ;
Route::get("(:any)/login" , "InviteController@login" , "LoginInvitesCode" );
Route::post("login" , "InviteController@loginRequest" , "LoginRequestInvitesCode" );
Route::get("/dashboard" , "InviteController@dashboard" , "DashboardInvitesCode" );
Route::get("/dashboard/logout" , "InviteController@logout" , "DashboardLogoutInvitesCode" );


Route::error(function() {
    return abort(404);
});

Route::dispatch() ;
