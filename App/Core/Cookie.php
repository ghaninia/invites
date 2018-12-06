<?php
namespace App\Core;

class Cookie
{
    private $expire_at , $cookie ;
    public function __construct()
    {
        $this->cookie = $_COOKIE ;
        $this->expire_at = time() + 86400  ; // one day
    }
    public function put(array $parameters)
    {
        if(is_array($parameters) && count($parameters) > 0 ){
            foreach ($parameters as $parameter => $value ){
                setcookie($parameter , $value , $this->expire_at ) ;
            }
            return true ;
        }
        return false ;
    }

    public function get($parameter , $default = null )
    {
        if (isset($this->cookie[$parameter]))
            return $this->cookie[$parameter] ;
        if ( !is_null($default) )
            return $default ;
        return false ;
    }

    public function has()
    {
        $args = func_get_args() ;
        if (!empty($args))
        {
            foreach ($args as $arg)
                if (array_key_exists( $arg , $this->cookie ))
                    return false ;
            return true ;
        }
        return false ;
    }

    public function flash()
    {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }

}