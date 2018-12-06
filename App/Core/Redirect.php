<?php
namespace App\Core;
class Redirect
{
    private $string  ;
    private static $halts = false ;

    public function route($name , $params = [] , $time = null)
    {
        $url = route($name , $params) ;
        if(!! $url ){
            $this->string = sprintf("Location:%s" , $url ) ;
            if(! is_null($time))
                $this->string = sprintf("Refresh:%s;Location:%s" , $time , $url  ) ;
        }
        return header($this->string) ;
    }

    public function with($information)
    {
        Session::put($information) ;
    }
}