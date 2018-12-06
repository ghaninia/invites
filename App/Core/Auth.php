<?php
namespace App\Core;
class Auth
{
    private $guard = "tbl_broker" ;
    private $keyAuthCookie , $keyAuthSession ;

    public function __construct()
    {
        $this->keyAuthCookie = $this->keyAuthSession = sha1($this->guard."_authunticate") ;
    }

    public function guard($table)
    {
        $this->guard($table) ;
        return $this ;
    }

    public function check()
    {
        if (cookie()->has($this->keyAuthCookie) && !Session::set($this->keyAuthSession))
        {
            $cookie = cookie()->get($this->keyAuthCookie) ;
            $cookie = @str_replace( sha1($this->keyAuthCookie) , "" , $cookie ) ;
            $cookie = @base64_decode($cookie);
            $cookie = @json_decode($cookie , true ) ;
            if (isset($cookie['id']) && isset($cookie['password']))
            {
                return $this->login($cookie) ;
            }
        }
        return Session::set($this->keyAuthSession) ;
    }

    public function user()
    {
        if(self::check()){
            $userId = intval(Session::get($this->keyAuthSession)) ;
            $user = DB::table($this->guard)->where(["id" => $userId])->first()  ;
            return $user ;
        }
        return null ;
    }

    public function id()
    {
        if(self::check())
            return intval(Session::get($this->keyAuthSession)) ;
        return null ;
    }

    public function loginByUsingId($id)
    {
        $user = DB::table($this->guard)->where(["id" , $id])->first()  ;
        if(! is_null($user) ){
            Session::put([$this->keyAuthSession => $id ]) ;
            return true ;
        }
        return false ;
    }

    public function login( $items )
    {
        if(is_array( $items ))
        {
            if( isset($items["password"]) && count($items) > 1 )
            {
                $user = DB::table($this->guard)->where($items)->first() ;
                if(!is_null($user)){
                    /////////////////////////
                    //set cookie authunticate
                    /////////////////////////
                    cookie()->put([
                        $this->keyAuthCookie =>
                            base64_encode(
                                json_encode(
                                    [
                                        "id" => $user['id'] ,
                                        "password" => $user['password']
                                    ]
                                )
                            ).sha1($this->keyAuthCookie)
                    ]);
                    Session::put([ $this->keyAuthSession => $user["id"] ]) ;
                    return true ;
                }
            }
        }
        return false ;
    }

    public function logout()
    {
        cookie()->flash() ;
        Session::flush() ;
    }

}
