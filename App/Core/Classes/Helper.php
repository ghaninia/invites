<?php

function config($key){
    /******************/
    /*** direct url ***/
    $protocol = (strpos($_SERVER['SERVER_SIGNATURE'], '443') !== false ? 'https://' : 'http://') ;
    $url = $protocol . $_SERVER['HTTP_HOST']  ;
    $script_name = str_replace(str_replace("/" , DIRECTORY_SEPARATOR , $_SERVER["DOCUMENT_ROOT"] ) , "" , getcwd()) ;
    $script_name = strtolower( str_replace( DIRECTORY_SEPARATOR , "/"  , substr($script_name , 1 , strlen($script_name)) ) ) ;
    $asset = strtolower($url ."/". $script_name) ;
    /****** $APP *****/
    /*****************/

    $app = [
        "URL"     =>  $asset ,
        "SCRIPT_NAME" => $script_name
    ] ;

    if(is_string(trim($key))){
        return $app[strtoupper(trim($key))];
    }

    return ;
}
/*********************/
/***** import all ****/
/*********************/
define("ASSET" , config("URL") ) ; //نشانی خود را وارد نمایید
define("ROOT" , getcwd() ) ;
define("SCRIPT_NAME" , config("SCRIPT_NAME") ) ;
define("VIEW" , ROOT."/App/Resources/" );
/*** VIEW CONTROLLER ***/
/*** ASSET FILE PUBLIC ***/
/*** DIE AND DUMP ***/
function view( $path ,  $information = null ){
    $path = VIEW.str_replace("." , DIRECTORY_SEPARATOR , $path ) . ".blade.php" ;
    if(file_exists($path)){
        if(is_array($information) && count($information) > 0 )
            foreach ($information as $key => $value )
                $$key = $value ;
        require_once $path ;
    }
    return "view not found please test again";
}
function asset($path){
    return ASSET."/Public/".$path ;
}
function dd($data){
    return die(json_encode($data)) ;
}
/*** RESPONSE ***/
/****************/
/****************/
function response(){
    $response = new \App\Core\Response() ;
    return $response ;
}
/*** Route ***/
/*************/
/*************/
function route( $name , $params = [] ){

    $protocol = strpos($_SERVER['SERVER_SIGNATURE'], '443') !== false ? 'https://' : 'http://';
    $route = $url = $protocol . $_SERVER['HTTP_HOST']  ;
    $routes = \App\Core\Route::$routes ;
    
    if ( isset($routes[$name]) ) {
        $url = $url . $routes[$name];
        $url = parse_url($url, PHP_URL_PATH);
        if (!empty($params)) {
            if (preg_match('/\(+:+[a-z]{3}+\)/', $url)) {
                $i = 0;
                while (true) {
                    if (preg_match('/\(+:+[a-z]{3}+\)/', $url)) {
                        $url = preg_replace('/\(+:+[a-z]{3}+\)/', (isset($params[$i]) ? $params[$i] : ""), $url);
                        $i++;
                    } else {
                        break;
                    }
                }
                return $route.$url;
            } else {
                $url .= "?" . http_build_query($params);
            }
        }
        return $route.$url;
    }
}

function abort($code , $message = null ){
    if ($code == 404) {
        view("errors/404" , ['message' => $message ]) ;
    }
    die() ;
}
/*** VIEW HEADER AND FOOTER ***/
/******************************/
/******************************/
function get_header($information = []){
    return view("layouts/header" , $information);
}
function get_footer($information = []){
    return view("layouts/footer" , $information);
}
/*** AUTHUNTICATE ***/
/********************/
/********************/
function auth(){
    $auth = new \App\Core\Auth() ;
    return $auth ;
}
/*** Redirect ***/
/****************/
/****************/
function redirect()
{
    $redirect= new \App\Core\Redirect() ;
    return $redirect ;
}

/*** Cookie ***/
/**************/
/**************/
function cookie(){
    $cookie = new \App\Core\Cookie() ;
    return $cookie ;
}
/*** Request ***/
/***************/
/***************/
function request()
{
    $request = new \App\Core\Request ;
    return $request ;
}

/*** Jdate Time format ***/
/*************************/
function JdateTimeFormat($memeber)
{
    if (isset($memeber['create_time']))
    {
        $timestamp = strtotime($memeber['create_time']) ;
        return '<strong>'.jdate("d " , $timestamp ).'</strong>'.jdate("F Y" , $timestamp );
    }
}

/*** OrderBy Icon ***/
/********************/
function OrderByIcon($orderBy , $withIcon = false ){
    $query_builder = parse_url($_SERVER['REQUEST_URI'] , PHP_URL_QUERY );
    $query_builder = explode("&", $query_builder );
    $queries = [] ;
    foreach ($query_builder as $query)
    {
        $query = explode("=" , $query ) ;
        if (count($query) === 2)
            $queries[$query[0]] = $query[1] ;
    }

    $order = "desc"  ;
    if( request()->has(['orderBy' , 'order']) )
    {
        if ( strtolower(request()->input("orderBy")) === strtolower($orderBy) )
            if ( strtolower(request()->input("order")) === "asc")
            {
                $order = "desc" ;
            }
            else
            {
                $order = "asc" ;
            }
    }

    $queries["orderBy"] = $orderBy ;
    $queries["order"] = $order ;
    $query_builder = "?".http_build_query($queries) ;
    if ($withIcon)
        return sprintf('<a class="quicksort" title="%s" href="%s"><i class="zmdi zmdi-sort-amount-%s"></i></a>' , $queries['order'] , $query_builder , $queries['order'] ) ;
    return $query_builder ;
}

/*** MobileNumberStar ***/
/************************/
function MobileNumberStar($mobile)
{
    if(strlen($mobile) == 11)
    {
        for ($i = 4 ; $i < 7 ; $i++ )
            $mobile[$i] = '*' ;
        return $mobile ;
    }


    return $mobile ;
}