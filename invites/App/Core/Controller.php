<?php
namespace App\Core;
class Controller
{
    use Middleware ;
    /**
     * @param $request all $_Request
     * @param $rules ["request-name" => "rule" ]
     * support :::
     * in:x,y,z , max:x , min:y
     * required , mobile , url , persian , ip , required , email , ..
     */
    public function validate($request  , $rules )
    {
        $errors = [] ;
        if(count($request) > 0 && is_array($rules)){
            foreach ($rules as $name => $rule ){
                if(isset($request[$name])){
                    if(is_array($rule) && count($rule) > 0 ){
                        foreach ($rule as $rul ){
                            $rul = trim(strtolower($rul)) ;
                            if( $rul == "required" ){
                                if(isset($request[$name]) == false){
                                    $errors[$name]["required"] = "این فیلد الزامی می باشد ." ;
                                    break ;
                                }
                            }
                            elseif( $rul === "email" ){
                                if(filter_var( $request[$name] , FILTER_VALIDATE_EMAIL )  == false)
                                    $errors[$name]["email"] = "فرمت پست الکترونیکی درست نمی باشد." ;
                                continue ;
                            }
                            elseif( $rul === "mobile" ){
                                if( preg_match("/^09[0-9]{9}/" ,  $request[$name] )  == false)
                                    $errors[$name]["mobile"] = "فرمت موبایل صحیح نمی باشد ." ;
                                continue ;
                            }
                            elseif( $rul === "url" ){
                                if(
                                    preg_match( "/^(http(s?):\/\/)?(www\.)+[a-zA-Z0-9\.\-\_]+(\.[a-zA-Z]{2,3})+(\/[a-zA-Z0-9\_\-\s\.\/\?\%\#\&\=]*)?$/" ,
                                        $request[$name] ) == false
                                )
                                    $errors[$name]["url"] = "فرمت وب سایت صحیح نمی باشد ." ;
                                continue ;
                            }
                            elseif( $rul === "ip" ){
                                if(filter_var($request[$name]  , FILTER_VALIDATE_IP ) == false )
                                    $errors[$name]["ip"] = "" ;
                                continue ;
                            }
                            elseif( $rul === "persian" ){
                                if(preg_match('/[^x{600}-\x{6FF}$]/u', $request[$name]  ) == false )
                                    $errors[$name]["persian"] = "" ;
                                continue ;
                            }
                            elseif( strstr($rul , "min:") ){
                                $rul = str_replace("min:" , "" , $rul ) ;
                                if( filter_var($rul , FILTER_VALIDATE_INT ) )
                                    if ( $request[$name] > $rul ){
                                        $errors[$name]["min"] = sprintf("فیلد مورد نظر نباید کوچکتر از %s باشد."  , $rul ) ;
                                        continue ;
                                    }
                            }
                            elseif (strstr($rul , "max:")){
                                $rul = str_replace("max:" , "" , $rul ) ;
                                if( filter_var($rul , FILTER_VALIDATE_INT ) )
                                    if ( $request[$name] < $rul ){
                                        $errors[$name]["max"] = sprintf("فیلد مورد نظر نباید بزرگتر از %s باشد."  , $rul ) ;
                                        continue ;
                                    }
                            }
                            elseif (strstr($rul , "in:")){
                                $rul = str_replace("in:" , "" , $rul ) ;
                                $rul = explode("," , $rul) ;
                                if( is_array($rul) ){
                                    if(in_array($request[$name] , $rul) == false ){
                                        $errors[$name]["in"] = "فیلد پر شده مقدار مجاز نمی باشد ." ;
                                        continue ;
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $errors ;
    }
}