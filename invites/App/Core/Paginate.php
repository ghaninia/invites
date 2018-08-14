<?php
namespace App\Core;
trait Paginate
{
    public $pre_page , $start , $end , $count ;

    private function page()
    {
        if ( request()->input("page") > 0)
        {
           $page = request()->input("page") ;
        }else{
            $page = 1 ;
        }
        return $page ;
    }

    private function start()
    {
        return ( $this->page() - 1 ) * $this->pre_page ;
    }

    public function paginate( $pre_page ){
        $this->pre_page = $pre_page ;
        $this->count = DB::table(self::$table)->method("count")->get()[0]["count(*)"];
        $this->limit( $this->start() , $this->pre_page )  ;
        return $this ;
    }

    public function links()
    {
        $nextAll = @ceil( $this->count / $this->pre_page ) ;
        $string = "" ;
        $string .= '<ul class="pagination">' ;
        //prev
        if( $this->page() == 1 ){
            $string .= "<li class='active'><a><i class='zmdi zmdi-chevron-right zmdi-hc-fw'></i></a></li>" ;
        }elseif ( $this->page() <= $nextAll ){
            $string .= "<li><a href='".$this->parse(["page" => $this->page()-1 ])."'><i class='zmdi zmdi-chevron-right zmdi-hc-fw'></i></a></li>" ;
        }elseif( $this->page() > $nextAll){
            $string .= "<li><a href='".$this->parse(["page" => $nextAll ])."'><i class='zmdi zmdi-chevron-right zmdi-hc-fw'></i></a></li>" ;
        }
        //last
        if( $this->page() == $nextAll || $this->page() >= $nextAll){
            $string .= "<li class='active'><a><i class='zmdi zmdi-chevron-left zmdi-hc-fw'></i></a></li>" ;
        }else{
            $string .= "<li><a href='".$this->parse(["page" => $this->page()+1 ])."'><i class='zmdi zmdi-chevron-left zmdi-hc-fw'></i></a></li>" ;
        }
        $string .= '</ul>' ;

        return $string ;
    }

    private function parse(array  $parameters)
    {
        $queryString = parse_url( $_SERVER['REQUEST_URI'] , PHP_URL_QUERY )."&".http_build_query($parameters);
        $arr = [] ;
        if(!empty($queryString)){
            $queries = explode("&" , $queryString );

            if(count($queries) > 0){
                foreach ($queries as $query ){
                    $baseman = explode("=" , $query ) ;
                    if(is_array($baseman) && (count($baseman) == 2) ){
                        $arr[$baseman[0]] = $baseman[1] ;
                    }
                }
            }
        }
        return "?".http_build_query($arr) ;
    }

}