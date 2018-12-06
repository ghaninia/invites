<?php
namespace App\Controllers ;
use App\Core\Request ;
use App\Core\DB ;
class DownloadsController
{
    public function index($short)
    {
        $request = new Request ;
        $result = DB::table("tbl_links")->where(["short" => $short])->first() ;
        if (!! $result ) {
            DB::table("tbl_downloads")->insert([
                "link_id" => $result['id'] ,
                "user_agent" => $request->agent() ,
                "user_ip" => $request->ip()
            ]);
            header("location:{$result['link']}") ;
        }else{
            abort(404) ;
        }
    }
}
