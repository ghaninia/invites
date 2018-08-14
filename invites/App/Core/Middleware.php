<?php
namespace App\Core;
trait Middleware
{
    public function middleware(array $lists)
    {
        $request = new Request() ;
        if (is_array($lists))
        {
            $currentRouteMethod = Route::currentRouteMethod() ;
            if(is_string($currentRouteMethod) && isset($lists[$currentRouteMethod]))
            {
                foreach ( $lists[$currentRouteMethod] as $middleware )
                {
                    $vars = [] ;
                    //if define varible
                    if (strstr($middleware , ":")){
                        $segment = explode(":" , $middleware) ;
                        if ( isset($segment[1]) )
                        {
                            $vars = explode( "," , $segment[1]) ;
                            $middle = $segment[0] ;
                            $vars = array_map(function ($var){return trim($var);} , $vars) ;
                        }
                    }else{
                        $middle = $middleware ;
                    }

                    array_unshift($vars , $request );
                    $class = $this->kernel($middle) ;

                    if ($class)
                    {
                        if (is_array($class))
                        {
                            $classes = $class;
                            foreach ($classes as $class)
                            {
                                if (method_exists($class , "handle"))
                                {
                                    $res = @call_user_func_array([$class , "handle"] , $vars ) ;
                                    if ($res)
                                        continue ; // if middleware true
                                    else
                                        return $res ; // if middleware false
                                }else{
                                    abort(404, sprintf("متد handle در کلاس %s یافت نشد لطفا بررسی نمایید." , basename($class) ) ) ;
                                }
                            }
                        }else{
                            if (method_exists($class , "handle"))
                            {
                                $res = @call_user_func_array([$class , "handle"] , $vars ) ;
                                if ($res)
                                    continue ; // if middleware true
                                else
                                    return $res ; // if middleware false
                            }else{
                                abort(404, sprintf("متد handle در کلاس %s یافت نشد لطفا بررسی نمایید." , basename($class) ) ) ;
                            }
                        }
                    }else{
                        abort(404, "کلاس میان افزار یافت نشد لطفا کد نرم افزاری را چک نمایید.") ;
                    }
                }
            }
        }
    }

    private function kernel($name)
    {
        $middleware =
        [
            "guard" => '\App\Middlewares\Guard' ,
            "guest" => '\App\Middlewares\Guest'
        ];

        if (isset($middleware[$name]))
            return $middleware[$name] ;
        return false ;
    }

}