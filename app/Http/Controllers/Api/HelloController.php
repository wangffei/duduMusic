<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/Meting.php") ;
 use App\Http\Controllers\Controller;
 use App\Util\MetingMusic ;
 use Illuminate\Support\Facades\DB;

 class HelloController extends Controller{

 	public $AlbumList = Array(0 => Array("id" => "19723756" , "img" => "http://p2.music.126.net/DrRIg6CrgDfVLEph9SNh7w==/18696095720518497.jpg?param=200y200" , "name" => "云音乐飙升榜") , 1 => Array("id" => "3779629" , "img" => "http://p2.music.126.net/N2HO5xfYEqyQ8q6oxCw8IQ==/18713687906568048.jpg?param=200y200" , "name" => "云音乐新歌榜") , 2 => Array("id" => "2884035" , "img" => "http://p2.music.126.net/sBzD11nforcuh1jdLSgX7g==/18740076185638788.jpg?param=200y200" , "name" => "网易原创歌曲榜") , 3 => Array("id" => "3778678" , "img" => "http://p2.music.126.net/GhhuF6Ep5Tq9IEvLsyCN7w==/18708190348409091.jpg?param=200y200" , "name" => "云音乐热歌榜") , 4 => Array("id" => "991319590" , "img" => "http://p2.music.126.net/y8zyTt4HwXbJqB31aQKz1A==/109951164353220919.jpg?param=200y200" , "name" => "云音乐说唱榜") , 5 => Array("id" => "71384707" , "img" => "http://p2.music.126.net/BzSxoj6O1LQPlFceDn-LKw==/18681802069355169.jpg?param=200y200" , "name" => "云音乐古典音乐榜") , 6 => Array("id" => "1978921795" , "img" => "http://p2.music.126.net/5tgOCD4jiPKBGt7znJl-2g==/18822539557941307.jpg?param=200y200" , "name" => "云音乐电音榜") , 7 => Array("id" => "2250011882" , "img" => "http://p2.music.126.net/oUxnXXvM33OUHxxukYnUjQ==/109951164174523461.jpg?param=200y200" , "name" => "抖音排行榜") , 8 => Array("id" => "2617766278" , "img" => "http://p2.music.126.net/XbjRDARP1xv5a-40ZDOy6A==/109951163785427934.jpg?param=200y200" , "name" => "新声榜") , 9 => Array("id" => "71385702" , "img" => "http://p2.music.126.net/vttjtRjL75Q4DEnjRsO8-A==/18752170813539664.jpg?param=200y200" , "name" => "云音乐ACG音乐榜") , 10 => Array("id" => "745956260" , "img" => "http://p2.music.126.net/vs-cMh49e6qPEorHuhU07A==/18737877162497499.jpg?param=200y200" , "name" => "云音乐韩语榜") , 11 => Array("id" => "10520166" , "img" => "http://p2.music.126.net/8-GBrukQ3BHhs4CmK6qE4w==/109951163424197392.jpg?param=200y200" , "name" => "云音乐国电榜") , 12 => Array("id" => "2023401535" , "img" => "http://p2.music.126.net/0_6_Efe9m0D0NtghOxinUg==/109951163089272193.jpg?param=200y200" , "name" => "英国Q杂志中文版周榜") , 13 => Array("id" => "2006508653" , "img" => "http://p2.music.126.net/CUqQp33MZf_m0BwH4u0V6A==/109951163078922993.jpg?param=200y200" , "name" => "电竞音乐榜") , 14 => Array("id" => "180106" , "img" => "http://p2.music.126.net/VQOMRRix9_omZbg4t-pVpw==/18930291695438269.jpg?param=200y200" , "name" => "UK排行榜周榜") , 15 => Array("id" => "60198" , "img" => "http://p2.music.126.net/EBRqPmY8k8qyVHyF8AyjdQ==/18641120139148117.jpg?param=200y200" , "name" => "美国Billboard周榜") , 16 => Array("id" => "3812895" , "img" => "http://p2.music.126.net/A61n94BjWAb-ql4xpwpYcg==/18613632348448741.jpg?param=200y200" , "name" => "Beatport全球电子舞曲榜") , 17 => Array("id" => "21845217" , "img" => "http://p2.music.126.net/H4Y7jxd_zwygcAmPMfwJnQ==/19174383276805159.jpg?param=200y200" , "name" => "KTV唛榜") , 18 => Array("id" => "11641012" , "img" => "http://p2.music.126.net/WTpbsVfxeB6qDs_3_rnQtg==/109951163601178881.jpg?param=200y200" , "name" => "iTunes榜") , 19 => Array("id" => "60131" , "img" => "http://p2.music.126.net/Rgqbqsf4b3gNOzZKxOMxuw==/19029247741938160.jpg?param=200y200" , "name" => "日本Oricon周榜") , 20 => Array("id" => "120001" , "img" => "http://p2.music.126.net/54vZEZ-fCudWZm6GH7I55w==/19187577416338508.jpg?param=200y200" , "name" => "Hit FM Top榜") , 21 => Array("id" => "112463" , "img" => "http://p2.music.126.net/wqi4TF4ILiTUUL5T7zhwsQ==/18646617697286899.jpg?param=200y200" , "name" => "台湾Hito排行榜") , 22 => Array("id" => "2809513713" , "img" => "http://p2.music.126.net/c0iThrYPpnFVgFvU6JCVXQ==/109951164091703579.jpg?param=200y200" , "name" => "云音乐欧美热歌榜") , 23 => Array("id" => "2809577409" , "img" => "http://p2.music.126.net/Zb8AL5xdl9-_7WIyAhRLbw==/109951164091690485.jpg?param=200y200" , "name" => "云音乐欧美新歌榜") , 24 => Array("id" => "27135204" , "img" => "http://p2.music.126.net/6O0ZEnO-I_RADBylVypprg==/109951162873641556.jpg?param=200y200" , "name" => "法国 NRJ Vos Hits 周榜") , 25 => Array("id" => "3001835560" , "img" => "http://p2.music.126.net/SkGlKQ6acixthb77VlD9eQ==/109951164432300406.jpg?param=200y200" , "name" => "云音乐ACG动画榜") , 26 => Array("id" => "3001795926" , "img" => "http://p2.music.126.net/hivOOHMwEmnn9s_6rgZwEQ==/109951164432303700.jpg?param=200y200" , "name" => "云音乐ACG游戏榜") , 27 => Array("id" => "3001890046" , "img" => "http://p2.music.126.net/Ag7RyRCYiINcd9EtRXf6xA==/109951164432303690.jpg?param=200y200" , "name" => "云音乐ACG VOCALOID榜") , 28 => Array("id" => "3112516681" , "img" => "http://p2.music.126.net/E5xPfNqD1rp6dB1VPOlLUQ==/109951164540938467.jpg?param=200y200" , "name" => "中国新乡村音乐排行榜")) ;

   public function index($id){
   	$filename = "./music/music".$id.".json" ;
   	if(file_exists($filename)){
   		$file = fopen($filename , "r") or die("file exception") ;
   		$fileData = fread($file , filesize($filename)) ;
   		fclose($file) ;
   		$result = json_decode($fileData , true) ;
   		// 1.请求音乐播放地址
   		$api = new MetingMusic("netease") ;
		$song = $api -> format(true) -> url($result["data"]["id"]) ;
		$song = json_decode($song , true)["url"] ;
		$result["data"]["play_url"] = $song ;
   		return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   	}
	$api = new MetingMusic("netease") ;
	$data = $api->format(true)->song($id);
	$data = json_decode($data , true) ;

	// 1.请求音乐播放地址
	$song = $api -> format(true) -> url($data[0]["url_id"]) ;
	$song = json_decode($song , true)["url"] ;
	// 2.请求歌曲图片
	$pic = $api -> format(true) -> pic($data[0]["pic_id"]) ;
	$pic = json_decode($pic , true)["url"] ;
	// 3.请求歌词数据
	$lrc = $api -> format(true) -> lyric($data[0]["lyric_id"]) ;
	$lrc = json_decode($lrc , true)["lyric"] ;
	$result = Array("code" => 200 , "msg" => "成功" , "data" => Array("img" => $pic , "play_url" => $song , "lyric" => $lrc , "songName" => $data[0]["name"] , "album" => $data[0]["album"] , "singer" => $data[0]["artist"][0] , "id" => $data[0]["url_id"])) ;
   	$file = fopen($filename , "w") ;
   	fwrite($file, json_encode($result)) ;
   	fclose($file) ;

    return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function albums(){
   	$result = Array("code" => 200 , "msg" => "成功" , "data" => ($this -> AlbumList)) ;
   	return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function album($id){
   	$filename = "./music/album".$id.".json" ;
   	if(file_exists($filename)){
   		$file = fopen($filename , "r") or die("file exception") ;
   		$data = fread($file , filesize($filename)) ;
   		fclose($file) ;
   		$result = Array("code" => 200 , "msg" => "成功" , "data" => json_decode($data , true)) ;
   		return response($result) -> header("Content-Type" , "application/json") ;
   	}
   	$api = new MetingMusic("netease") ;
   	$data = $api -> format(true) -> playlist($id) ;
   	$file = fopen($filename , "w") ;
   	fwrite($file, $data) ;
   	fclose($file) ;
   	$result = Array("code" => 200 , "msg" => "成功" , "data" => (json_decode($data , true))) ;
   	return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }  

   function hot(){
   	$result = Array("code" => 200 , "msg" => "成功" , "data" => Array()) ;
   	$files = glob("./music/*") ;
   	for($i = 0 ; $i < count($files) ; $i++){
   		$arr = Array() ;
   		preg_match("/.*?music\d+.*?/" , $files[$i] , $arr) ;
   		if(count($arr) == 1){
   			$temp = str_replace("./music/music" , "" , $files[$i]) ;
   			$temp = str_replace(".json" , "" , $temp) ;
   			$f = fopen($files[$i] , "r") ;
   			$data = fread($f , filesize($files[$i])) ;
   			fclose($f) ;
   			$arr = json_decode($data , true) ;
   			$item = Array("songName" => $arr["data"]["songName"] , "album" => $arr["data"]["album"] , "singer" => $arr["data"]["singer"] , "id" => $temp) ;
   			array_push($result["data"] , $item) ;
   		}
   	}
   	return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function search($key , $page){
   	$api = new MetingMusic("netease") ;
	$data = $api->format(true)->search($key , Array(
		"limit" => 30 ,
		"page"  => $page
	));
	$result = Array("code" => 200 , "msg" => "成功" , "data" => json_decode($data , true)) ;
	return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function test1()
   {
	$student = DB::select("select * from hot");
	$result = Array("code" => 200, "msg" => "成功", "data" => $student);
	return response(json_encode($result)) -> header("Content-Type", "application/json");
   }
 }
