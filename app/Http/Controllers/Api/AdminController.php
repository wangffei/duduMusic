<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/Meting.php") ;
 use App\Http\Controllers\Controller;
 use App\Util\MetingMusic ;
 use Illuminate\Support\Facades\DB;

 class AdminController extends Controller{
	public function data()
	{
		$music = DB::select("select * from all_music");
		$result = Array("code" => 200, "msg" => "成功", "count" => 1000, "data" => $music);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}
 }