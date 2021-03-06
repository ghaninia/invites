Invites is a mini sample mvc freamwork , Open source PHP router , Query Builder , Response , Redirect , ... 

<h1>USAGE</h1> 

1 - install Composer <br>
2 - Open the cmd and type in it and then press enter ...<br>
<code langauge="php">
composer install
</code><br>
3 - Pase the core file in your project.<br>
4 - Set namespace In Composer Default namespace is App\Core and ReWrite in Core Files<br>

<h1>How to use ROUTE , CONTROLLER ?</h1>
Make a file .htaccess on your project home page

<h5>Apache</h5>
<pre lang="htaccess">
RewriteEngine On
RewriteBase /
# Allow any files or directories that exist to be displayed directly
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php?$1 [QSA,L]
</pre>
<h5>Nginx</h5>
<pre lang="htaccess">
rewrite ^/(.*)/$ /$1 redirect;
if (!-e $request_filename){
	rewrite ^(.*)$ /index.php break;
}
</pre>


<pre lang="php">
App\Core\Route ;

Route::get("url" , "Callback OR Controller" , "routename") ;
Route::post("url" , "Callback OR Controller" , "routename") ;
Route::any("url" , "Callback OR Controller" , "routename") ;
Route::get("post/(:any)" , "Callback OR Controller" , "routename") ; // you can use wallcard 
Route::get("post/(:num)" , function($id){
   echo $id ; 
}, "routename") ; // ...

Route::disptach() ;
</pre>

<h5>Demo</h5>

<pre lang="php">
//web.php 
use App\Core\Route ; 
Route::get("/" , "MainController@index" , "mainPage") ;
Route::get("/post/(:num)" , "MainController@post" , "post") ;


//Controller folder 
//folder App\Controller
use App\Controller\MainController ;
use App\Core\Controller;
class MainController extends Controller {
      public function index(){
          return view('main') ; // view folder App\Resource basename withOut ".php"
      }

      public function post($id)
      {
          echo $id ;
      }

      // you can use validateor !
      public function index(){
          $request = new Request() ;
          $this-validate($request , [
              'filedName' => [ 'required' , 'mobile' , 'url' , 'persian' , 'ip' , 'email' ] ,
          ]);
      }
      
      // you can youe middleware 
      public function __construct(){
          $this->middleware(
              [
                  "methodName" => ["guard"] ,
                  "methodName" => ["guest"] ,
              ]
          );
      }
      
}
</pre>
<pre lang='php'>
Route::currentRouteName() ; // The name of the route shows me the current page
Route::currentRouteMethod() ; // The method of the route shows me the current page
abort(404 , 'description') ; // Redirects to page 404 and displays the message
</pre>

<h1>USE Request and Response</h1>
<h5>1 - use Request,Response Class</h5>
<pre lang='php'>
Request::input('fieldName' , 'default') ; // return Field Value IF exists otherwise return default
Request::only(['filedNames']) ; 
Request::except(['except filedNames']) ;
Request::method() ; //What method is used?
Request::ajax() ; // Is ajax request?
Request::ip() ; // Applicant ip
Request::agent() ; // Applicant user agent
Request::user() ; // If the user login, then show me the information
--- 
Response::json(['key' => $value , ... ]) ;
Response::download("file link") ;

</pre>

