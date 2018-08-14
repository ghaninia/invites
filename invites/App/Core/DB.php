<?php
namespace App\Core ;
class DB
{
    use Paginate ;
    private $host = "localhost" ;
    private $database = "pquorair_porsa_db" ;
    private $username = "pquorair_SuPorsa" ;
    private $password = "!zG+}M;#Wdff" ;
    private $conn , $where , $method , $select , $join , $lJoin , $rJoin , $whereIn ,$first , $last , $orderBy , $limit;
    protected static $table ;

    public function __construct()
    {
        try{
            $query = sprintf( "mysql:host=%s;dbname=%s" , $this->host , $this->database ) ;
            $this->conn = new \PDO( $query , $this->username , $this->password ) ;
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->conn->exec("SET NAMES utf8") ;
        }catch (\PDOException $error)
        {
            dd($error->getMessage()) ;
        }
    }

    public static function Table($name)
    {
        self::$table = $name ;
        return new self() ;
    }

    public function where(array $array){
        if(func_num_args() == 1){
            if(is_array($array) && count($array) > 0 ){
                $queryString = " WHERE " ;
                $flag = 1 ;
                foreach ($array as $key => $value ) {
                    if(is_array($value)){
                        if(count($value) == 3){
                            if(in_array($value[1] , ["<" , "=" , ">" , ">=" , "<=" , "<>" ,"like" , "LIKE"]))
                                foreach ($value as $item){
                                    if($value[2] == $item){
                                        if(is_string($item)){
                                            $queryString .= " ". "'$item'" ;
                                            continue ;
                                        }
                                    }
                                    $queryString .= " ". $item ;
                                }
                            else
                                break ;
                            if ($flag < count($array)){
                                $queryString .= " AND " ;
                            }
                        }
                    }
                    else{

                        if (is_string($value))
                        {
                            if (strtoupper(trim($value)) == "NOTEMPTY")
                                $queryString .= sprintf("%s != '' " , $key) ;
                            elseif (strtoupper(trim($value)) == "NOTNULL")
                                    $queryString .= sprintf("%s IS NOT NULL " , $key) ;
                            else
                                $queryString .= sprintf("%s = '%s' " , $key , $value) ;
                        }elseif (is_numeric($value))
                        {
                            $queryString .= sprintf("%s = %s " , $key , $value) ;
                        }elseif(is_null($value))
                        {
                            $queryString .= sprintf("%s IS NULL ", $key) ;
                        }else
                        {
                            $queryString .= sprintf("%s IS NOT NULL " , $key) ;
                        }

                        if ($flag < count($array)){
                            $queryString .= " AND " ;
                        }
                    }
                    $flag++ ;
                }
                $this->where = $queryString  ;
            }
        }else{
            $this->where = null  ;
        }
        return $this;
    }

    public function method($method)
    {
        $this->method = $method ;
        return $this ;
    }

    public function orderBy($orderBy , $order = "ASC")
    {
        if ( in_array( trim(strtoupper($order)) , ['ASC' , 'DESC'] ) )
            $this->orderBy = sprintf("ORDER BY %s %s" , $orderBy , trim(strtoupper($order)) ) ;
        return $this ;
    }

    public function select(array $lists)
    {
        $this->select = implode("," , $lists ) ;
        return $this ;
    }

    public function join( $table , $fieldTableOne , $operator , $fieldTableTwo )
    {
        $this->join[] =  sprintf("JOIN %s ON %s %s %s" , $table , $fieldTableOne , $operator , $fieldTableTwo )  ;
        return $this ;
    }

    public function rJoin( $table , $fieldTableOne , $operator , $fieldTableTwo )
    {
        $this->rJoin[] = sprintf("RIGHT JOIN %s ON %s %s %s" , $table , $fieldTableOne , $operator , $fieldTableTwo )  ;
        return $this ;
    }

    public function lJoin( $table , $fieldTableOne , $operator , $fieldTableTwo )
    {
        $this->lJoin[] = sprintf("LEFT JOIN %s ON %s %s %s" , $table , $fieldTableOne , $operator , $fieldTableTwo )  ;
        return $this ;
    }

    public function WhereIn($column , Array $characters )
    {
        $this->whereIn = sprintf("WHERE {$column} IN (%s) "). implode("," , $characters) ;
        return $this ;
    }

    public function first()
    {
        $this->orderBy("id" , "DESC") ;
        $this->limit(1) ;
        $result = $this->get() ;
        if (! empty($result))
            return $result[0] ;
        return ;
    }

    public function last(){
        $this->orderBy("id" , "ASCS") ;
        $this->limit(1) ;
        $result = $this->get() ;
        if (! empty($result))
            return $result[0] ;
        return ;
    }

    public function get()
    {
        $Query = trim($this->query()) ;
        try
        {
            $result = $this->conn->query($Query) ;
            $result = $result->fetchAll(\PDO::FETCH_ASSOC) ;
            return $result ;
        }catch (\PDOException $error)
        {
            die($error->getMessage()) ;
        }
    }

    public function query()
    {
        $Query = "" ;

        if(!! $this->select && !! $this->method )
            $Query = sprintf("SELECT %s(%s) " , $this->method , $this->select ) ;
        elseif (!! $this->select )
            $Query = sprintf("SELECT %s " , $this->select ) ;
        elseif (!! $this->method)
            $Query = sprintf("SELECT %s(*) " , $this->method ) ;
        else
            $Query = "SELECT * " ;

        if (empty(self::$table))
            dd("please Enter table name to database {$this->database}") ;
        $Query .= sprintf( "FROM %s ",self::$table ) ;

        if (!! $this->join )
            $Query .= implode(" " , $this->join) ;
        if (!! $this->lJoin )
            $Query .= implode(" " , $this->lJoin  ) ;
        if (!! $this->rJoin )
            $Query .= implode(" " , $this->rJoin ) ;

        if (!! $this->where )
            $Query .= " {$this->where} " ;

        if (!! $this->whereIn)
            $Query .= "{$this->whereIn} " ;

        if (!! $this->orderBy )
            $Query .= " {$this->orderBy} " ;

        if (!! $this->limit )
            $Query .= " {$this->limit} " ;

        return trim(preg_replace("/[ ]{2,50}/" , " " , $Query )) ; // delete space
    }

    public function limit($start , $end = null )
    {
        if(is_integer($start)){
            $this->limit = " LIMIT ".$start.( is_null($end) ? null : ",".intval($end) );
        }
        return $this ;
    }

    public function insert(){
        if(func_num_args() > 0){
            $args = func_get_args() ;
            $fullResult = [] ;
            try{
                foreach ($args as $arg){
                    $queryString  = "INSERT INTO ".self::$table ;
                    $queryString .= "(".implode(",",array_keys($arg)).")" ;
                    $queryString .= " VALUES (" ;
                    $queryString .=
                        implode("," , array_map(function ($item){
                            return "?" ;
                        } , array_keys($arg))) ;
                    $queryString .= ")" ;
                    $insertData = $this->conn->prepare($queryString) ;
                    $i = 1 ;
                    foreach ($arg as $key => $value){
                        $value = str_replace('"' , '' , $value) ;
                        $insertData->bindValue( $i , $value ) ;
                        $i++  ;
                    }
                    $fullResult[] = $insertData->execute()  ;
                }
                return $fullResult ;
            }catch(\PDOException $e){
                die( "Ooops : ".$e->getMessage() );
            }
        }
        return false ;
    }
}
