<?php
namespace App\Core;
class Session{
    public $session;
    public function __construct()
    {
        $this->session = $_SESSION ;
    }
    //get function multi session and one :)
    public static function get($parameters , $default = null ){
        $self = new static;
        if(!! $parameters ){
            if(is_string($parameters)){
                if (array_key_exists($parameters , $self->session )){
                    $data = @unserialize($self->session[$parameters]);
                    if ($data !== false) {
                        return unserialize($self->session[$parameters]) ;
                    }
                    return $self->session[$parameters] ;
                }
                return is_null($default) ? false : $default ;
            }elseif (is_array($parameters)){
                $result = [] ;
                foreach ($parameters as $parameter){
                    if(array_key_exists($parameter , $self->session )){
                        $result[$parameter] = $self->session[$parameter] ;
                    }
                }
                return empty($result) ? false : $result ;
            }
        }
        return false ;
    }
    //put to session function
    public static function put(array $parameters)
    {
        if(is_array($parameters) && count($parameters) > 0 ){
            foreach ($parameters as $parameter => $value ){
                $_SESSION[$parameter] = $value ;
            }
            return true ;
        }
        return false ;
    }
    //pull get session and remove
    public static function pull($parameters , $default = null)
    {
        $self = new static;
        if(is_string($parameters)){
            if(array_key_exists($parameters , $self->session )){
                $result = $self->session[$parameters] ;
                unset($_SESSION[$parameters]) ;
//                session_destroy() ;
                return $result ;
            }
            return is_null($default) ? false : $default ;
        }
        elseif (is_array($parameters)){
            if(count($parameters) > 0){
                $array = [] ;
                foreach ($parameters as $parameter){
                    if(array_key_exists($parameter , $self->session )){
                        $result = $self->session[$parameter] ;
                        unset($_SESSION[$parameter]) ;
//                        session_destroy() ;
                        $array[$parameter] = $result ;
                    }
                }
                return $array ;
            }
            return false ;
        }
        return false ;
    }
    //delete all session
    public static function flush(){
        return session_destroy() ;
    }
    //push a new value to session
    public static function push( $name , $pusher )
    {
        $self = new static ;
        if(array_key_exists( $name , $self->session )){
            if (!is_string($name))
                return false ;
            if( is_string($self->get($name)) ){
                $data = array($self->get($name)) ;
            }elseif(is_array($self->get($name)) || ($self->get($name) == null) ){
                $data = $self->get($name) ;
            }
            if (is_string($pusher)){
                array_push($data , $pusher) ;
            }elseif( is_array($pusher) ){
                foreach ($pusher as $push){
                    array_push($data , $push) ;
                }
            }
            $_SESSION[$name] = serialize($data) ;
            return true;
        }
        return false ;
    }
    public static function set($parameters){
        $self = new static() ;
        if (is_string($parameters)){
            return array_key_exists($parameters , $self->session ) ;
        }elseif (is_array($parameters)){
            $Result = [] ;
            foreach ( $parameters as $param ){
                array_push($Result , array_key_exists($param , $self->session ) );
            }
            return !in_array( FALSE , $Result ) ;
        }
        return false ;
    }
    public static function comparison( $name , $value ){
        if(is_string($name) && is_string($value))
            return self::get( $name ) == $value ;
        return false ;
    }
}