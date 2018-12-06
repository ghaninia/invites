<?php
namespace App\Core ;
class Route{

    public static $halts = false;
    public static $routes = array();
    public static $methods = array();
    public static $name = null ;
    public static $callbacks = array();
    public static $folderController = "\\App\\Controllers\\" ;
    public static $patterns = array(
        ':any' => '[^/]+',
        ':num' => '[0-9]+',
        ':all' => '.*'
    );
    public static $currentRouteName ;

    public static $error_callback;
    /**
     * Defines a route w/ callback and method
     */
    public static function __callstatic($method, $params ) {
        $uri = "/".config("SCRIPT_NAME").'/'.$params[0];
        $callback = $params[1];
        array_push(self::$methods, strtoupper($method));
        array_push(self::$callbacks, $callback);
        if( isset($params[2]) )
            self::$name = $params[2] ;
        if(!! self::$name )
            self::$routes[self::$name] = $uri ;
    }
    /**
     * Defines callback if route is not found
     */
    public static function error($callback) {
        self::$error_callback = $callback;
    }
    public static function haltOnMatch($flag = true) {
        self::$halts = $flag;
    }
    /**
     * Runs the callback for the given request
     */
    public static function dispatch(){
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $method = $_SERVER['REQUEST_METHOD'];
        $searches = array_keys(static::$patterns);
        $replaces = array_values(static::$patterns);
        $found_route = false;
        self::$routes = preg_replace('/\/+/', '/', self::$routes);
        $routes = array_values(self::$routes) ;
        // Check if route is defined without regex
        if (in_array($uri,  $routes)) {

            $route_pos = array_keys( $routes , $uri);

            foreach ($route_pos as $route) {
                // Using an ANY option to match both GET and POST requests
                if (self::$methods[$route] == $method || self::$methods[$route] == 'ANY') {
                    $found_route = true;

                    //set route name
                    self::$currentRouteName = @array_keys(array_slice(self::$routes , $route , 1 ))[0] ;

                    // If route is not an object
                    if (!is_object(self::$callbacks[$route])) {

                        // Grab all parts based on a / separator
                        $parts = explode('/',self::$callbacks[$route]);
                        // Collect the last index of the array
                        $last = end($parts);
                        // Grab the controller name and method call
                        $segments = explode('@',$last);
                        // Instanitate controller
                        $file = self::$folderController.$segments[0] ;
                        $controller = new $file();
                        // Call method
                        $controller->{$segments[1]}();
                        if (self::$halts) return ;
                    } else {

                        // Call closure
                        call_user_func(self::$callbacks[$route]);
                        if (self::$halts) return;
                    }
                }
            }
        }else{
            // Check if defined with regex
            $pos = 0;
            foreach ( $routes as $route) {
                $routeFound = $route ;
                if (strpos($route, ':') !== false) {
                    $route = str_replace($searches, $replaces, $route);
                }
                if (preg_match('#^' . $route . '$#', $uri, $matched)) {
                    if (self::$methods[$pos] == $method || self::$methods[$pos] == 'ANY') {
                        $found_route = true;
                        // Remove $matched[0] as [1] is the first parameter.
                        array_shift($matched);
                        //set route name
                        self::$currentRouteName = self::searchWithValue(self::$routes , $routeFound) ;

                        if (!is_object(self::$callbacks[$pos])) {

                            // Grab all parts based on a / separator
                            $parts = explode('/',self::$callbacks[$pos]);
                            // Collect the last index of the array
                            $last = end($parts);
                            // Grab the controller name and method call
                            $segments = explode('@',$last);
                            // Instanitate controller
                            $file = self::$folderController.$segments[0] ;
                            $controller = new $file();
                            // Fix multi parameters
                            if (!method_exists($controller, $segments[1])) {
                                echo "controller and action not found";
                            } else {
                                call_user_func_array(array($controller, $segments[1]), $matched);
                            }
                            if (self::$halts) return;
                        } else {
                            call_user_func_array(self::$callbacks[$pos], $matched);
                            if (self::$halts) return;
                        }

                    }
                }
                $pos++;
            }
        }
        // Run the error callback if the route was not found
        if ($found_route == false) {
            if (!self::$error_callback) {
                self::$error_callback = function() {
                    header($_SERVER['SERVER_PROTOCOL']." 404 Not Found");
                    echo '404';
                };
            } else {
                if (is_string(self::$error_callback)) {
                    self::get($_SERVER['REQUEST_URI'], self::$error_callback);
                    self::$error_callback = null;
                    self::dispatch();
                    return ;
                }
            }
            call_user_func(self::$error_callback);
        }
    }
    /**
     * current RouteName
     */
    public static function currentRouteName()
    {
        return self::$currentRouteName ;
    }

    public static function currentRouteMethod()
    {
        $methods = self::$callbacks ;
        $names = @array_keys(self::$routes) ;
        $index = @array_search(self::$currentRouteName , $names );
        $segment = @explode("@" , $methods[$index] ) ;
        return @$segment[1] ;
    }

    private static function searchWithValue($arr , $value)
    {
        if (is_array($arr) && is_string($value))
        {
            foreach ($arr as $k => $v )
            {
                if ($v == $value)
                    return $k ;
            }
        }
        return ;
    }
}