<?php
namespace App\Middlewares;
class Guest
{
    public function handle($request)
    {

        if (is_null($request->user()))
            return true ;
        else
            return redirect()->route("DashboardInvitesCode") ;
    }
}