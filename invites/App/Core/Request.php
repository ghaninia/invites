<?php
namespace App\Core ;
class Request
{
    private $request ;
    public function __construct()
    {
        $this->request = $_REQUEST ;
    }
    public function all()
    {
        return $this->request ;
    }
    public function input($name , $default = null ){
        if(isset($this->request[$name]))
        {
            return $this->validate( $this->request[$name] );
        }
        else{
            if ( ! is_null($default)){
                return $default ;
            }
        }
        return ;
    }
    public function only(array $names)
    {
        $results = [] ;
        if(! empty($this->request) && is_array($names)){
            foreach ($this->request as $name => $value ){
                if(in_array($name , $names)){
                    $results[$name] = $value ;
                }
            }
            return $results ;
        }
        return ;
    }
    public function except(array $names)
    {
        $results = $this->request ;
        if(! empty($this->request) && is_array($names)){
            foreach ($this->request as $name => $value ){
                if(in_array($name , $names)){
                    unset($results[$name]) ;
                }
            }
            return $results ;
        }
        return null ;
    }
    public function method($type)
    {
        if(isset($_SERVER["REQUEST_METHOD"])){
            return strtolower($_SERVER["REQUEST_METHOD"]) == strtolower($type);
        }
    }
    public function ajax()
    {
        if( !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
            return true ;
        return false ;
    }
    public function has(array $list)
    {
        foreach ($list as $character)
        {
            if ( !isset($this->request[$character]) )
                return false ;
        }
        return true ;
    }
    private function validate($input){
        $input = htmlentities($input) ;
        $input = htmlspecialchars($input) ;
        $input = str_replace("'" , "" , $input ) ;
        $input = str_replace('"' , "" , $input ) ;
        return $input ;
    }
    public function ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        else
          $ip=$_SERVER['REMOTE_ADDR'];
        return $ip;
    }
    public function agent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ;
    }
    public function user($guard = null )
    {
        if (is_null($guard))
            return auth()->user() ;
        return auth()->guard($guard)->user() ;
    }
}
