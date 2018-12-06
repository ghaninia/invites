<?php
namespace App\Controllers ;
use App\Core\Controller;
use App\Core\Cookie;
use App\Core\DB;
use App\Core\Request;
use App\Core\Response;
use App\Core\Route;
use App\Core\Session;

class InviteController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                "dashboard" => ["guard"] ,
                "logout" => ["guard"] ,
                "loginRequest" => [ "guest"] ,
                "login" => ['guest']
            ]
        );
    }

    public function index()
    {
        return view("index") ;
    }

    public function code($code)
    {
        $user = DB::Table("tbl_broker")
            ->where(["code" => $code])
            ->select(['tbl_broker.*' ])
            ->first() ;
        $p_title = (is_null($user) ? "دعوتنامه یافت نشد." : sprintf("دعوت نامه %s" , $user['name'].$user['family']) ) ;
        return view("index" , ["user" => $user , "p_title" => $p_title ]) ;
    }

    public function register()
    {
        $request = new Request() ;

        if ($request->has(["_code" , "mobile" , "captcha"]))
        {
            $errors = []  ;

            if ( Session::pull("captcha") != $request->input("captcha"))
            {
                $errors[] =  "کد امنیتی درست وارد نشده است ." ;
            }

            if (!preg_match('/^(((\+|00)98)|0)?9[0123456789]\d{8}$/' , $request->input("mobile")))
            {
                $errors[] =  "فرمت شماره همراه صحیح نمیباشد ." ;
            }else{
                $validate = DB::Table("tbl_users")->where(["mobile" => $request->input("mobile")])->first() ;
                if (!empty($validate)){
                    $errors[] =  "این شماره همراه قبلا ثبت نام نموده است ." ;
                }
            }
            $BrokerTable = DB::Table("tbl_broker")->where(["code" => $request->input("_code")])->first() ;
            if(is_null($BrokerTable))
            {
               $errors[] = "کد دعوتنامه غیر معتبر است ." ;
            }

            //if has Error
            if (!empty($errors))
            {
                return Response::json([
                    'status' => false ,
                    'errors'  => $errors
                ]);
            }else
            {
                DB::Table("tbl_users")->insert([
                    "mobile" => $request->input("mobile") ,
                    "promotion_code" => $request->input("_code") ,
                    "create_time" => ( new \DateTime() )->format("Y-m-d H:i:s") ,
                    "broker_id" => $BrokerTable['id']
                ]);

                return Response::json([
                    'status' => true ,
                    'message'  => sprintf("اکانتی با شماره همراه %s ساخته شد لطفا برنامه دانلود نمایید ." , $request->input("mobile") ) ,
                    'links' => [
                        'bazar' => [
                          'icon' => asset('images/android.svg') ,
                          'text' => 'دریافت مستقیم' ,
                          'link' => "http://yon.ir/directDl"
                        ] ,
                        'android' => [
                          'icon' => asset('images/bazar.svg') ,
                          'text' => 'دریافت از' ,
                          'link' => "http://yon.ir/cafeBazar"
                        ]
                    ]
                ]);
            }
        }
    }

    public function login($code)
    {
        $user = DB::table("tbl_broker")->where(["code" => $code])->first() ;
        if(is_null($user)){
            abort(404 , "کاربر مورد نظر یافت نشد." ) ;
        }
        else{
            $p_title = sprintf("ورود به حساب کاربری %s" , $user['name'].$user['family']) ;
        }
        return view("login" , ['user' => $user , 'p_title' => $p_title] );
    }

    public function loginRequest()
    {
        $request = new Request() ;
        if($request->has(["_code" , "password" , "captcha"]))
        {
            $errors = []  ;
            if ( Session::pull("captcha") != $request->input("captcha"))
            {
                $errors[] =  "کد امنیتی درست وارد نشده است ." ;
            }
            $BrokerTable = DB::Table("tbl_broker")->where(["code" => $request->input("_code")])->first() ;
            if(is_null($BrokerTable))
            {
                $errors[] = "کد دعوتنامه غیر معتبر است ." ;
            }

            $auth = auth()->login([
                'code' => $request->input("_code") ,
                'password' => $request->input("password")
            ]);
            if (!$auth){
                $errors[] = "گذرواژه اشتباه میباشد ." ;
            }
            //if has Error
             if (!empty($errors))
            {
                return Response::json([
                    'status' => false ,
                    'errors'  => $errors
                ]);
            }else
            {
                return Response::json([
                    'status' => true ,
                    'message'  => "شما با موفقیت وارد حساب کاربری خود شده اید." ,
                    'redirect' => route('DashboardInvitesCode')
                ]);
            }
        }
    }

    public function dashboard()
    {

        $wheres = [] ;
        $user = auth()->user() ;
        $indexRequest = strtolower(request()->input("index")) ;
        $order = "desc" ;
        $orderBy = "id" ;

        if ( request()->has(['orderBy' , 'order']) )
        {
            $inpOrderBy = request()->input("orderBy") ;
            $inpOrder = request()->input("order") ;
            if ( in_array( strtolower($inpOrderBy) , ['create_time' , "id" , "name" , "family"] ) )
            {
                $orderBy = $inpOrderBy ;
            }
            if ( in_array( strtolower($inpOrder) , ['asc' , 'desc']) )
            {
                $order = strtoupper($inpOrder) ;
            }
        }

        if ( $indexRequest && in_array($indexRequest , ['registration' , 'installed' , 'total'] ) )
        {

            if (request()->has(["date-from" , "date-to"]))
            {
                $dateFrom = @intval(request()->input("date-from")) ;
                $dateTo = @intval(request()->input("date-to")) ;
                
                $newDateTimeDateForm = (new \DateTime())->setTimestamp($dateFrom) ;
                $newDateTimeDateTo = (new \DateTime())->setTimestamp($dateTo) ;
            
                $DateTimeFrom =  $newDateTimeDateForm->format("Y-m-d H:i:s")  ;
                $DateTimeTo =    $newDateTimeDateTo->format("Y-m-d H:i:s")  ;

                if ($dateFrom < $dateTo)
                {
                    $wheres[] = [ "create_time" , ">" , $DateTimeFrom ] ;
                    $wheres[] = [ "create_time" , "<" , $DateTimeTo ] ;
                }

            }

            if ( request()->has(['q']) && !! trim(request()->input('q')) )
            {
                $wheres[] = [ "mobile" , "LIKE" , "%".request()->input("q")."%" ] ;
            }

            if ($indexRequest == 'registration')
            {
                $wheres["broker_id"] = $user['id'] ;
                $wheres["password"] = '' ;

            }elseif ($indexRequest == 'installed')
            {
                $wheres["broker_id"] = $user['id'] ;
                $wheres["password"] = 'NOTEMPTY' ;
            }else
            {
                $wheres["broker_id"] = $user['id'] ;
  
            }

            $items = DB::Table("tbl_users")
                ->select(['username' , 'mobile' , 'image' , 'email' , "create_time"])
                ->orderBy( $orderBy , $order )
                ->where($wheres)->paginate(10) ;

            $items_links = $items->links() ;
            $items = $items->get() ;

            return view("dashboard" , [
                'user' => $user ,
                'p_title' => "داشبورد" ,
                "members" => $items ,
                "member_paginate" => $items_links ,
                'indexRequest' => $indexRequest
            ]);

        }else{

            /* registration **/
            //* کسانی که ثبت نام کردند. *//
            $registration = DB::Table("tbl_users")
                ->select(['mobile' , "create_time" ])
                ->where(["broker_id" => $user['id'] , "password" => '' ])
                ->orderBy( $orderBy , $order )->get() ;


            /* installed */
            //* کسانی که نصب کردند و وارد شدند *//
            $installed = DB::Table("tbl_users")
                ->select(['username' , 'mobile' , 'image' , 'email' , "create_time"])
                ->where(["broker_id" => $user['id'] , "password" => 'NOTEMPTY' ])
                ->orderBy( $orderBy , $order )->get() ;

            /* total */
            //* تمام دریافت کنندگان *//
            $total = count($registration) + count($installed) ;



            return view("dashboard" , [
                'user' => $user ,
                'p_title' => "داشبورد" ,
                'registration' => $registration ,
                'installed' => $installed ,
                'total' => $total ,
                'indexRequest' => $indexRequest
            ]);


        }
    }

    public function logout()
    {
        $user = auth()->user() ;
        if (!!$user)
        {
            auth()->logout() ;
        }
        return redirect()->route("LoginInvitesCode" , [$user['code']]) ;
    }

}
