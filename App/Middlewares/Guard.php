<?php
namespace App\Middlewares;
class Guard
{
    public function handle($request)
    {
        if ($request->user())
            return true ;
        else
            abort("404" , "ابتدا شما باید وارد حساب کاربری خود شوید.") ;
    }
}