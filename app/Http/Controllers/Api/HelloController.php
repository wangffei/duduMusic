<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/Meting.php") ;
 use App\Http\Controllers\Controller;
 use App\Util\MetingMusic ;
 use Illuminate\Support\Facades\DB;

 class HelloController extends Controller{

   public function index($id){
      // 1.查数据库是否有当前歌曲
      $m = DB::select("select * from all_music where id=$id") ;
      if(count($m) > 0){
         $list_music = DB::select("select song as songName , singer as singer , album as album , url as play_url , img as img , local as local , id , lrc as lrc from all_music where id=$id") ;
         $result = Array("code" => 200 , "msg" => "成功" , "data" => ($list_music[0])) ;
         return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
      }
      // 1.判断用户是否登陆
      $username = request() -> cookie("username") ;
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
         // 如果用于已经登陆
         if($username){
            // 1.从音乐表中查出当前歌曲
            $music = DB::select("select id from all_music where mid=$id") ;
            if(count($music) > 0){
               $user = DB::select("select id from user_music where music_id = {$music[0] -> id}") ;
               if(count($user)==0){
                  DB::insert("insert into user_music(music_id , username) values({$music[0] -> id} , '$username')") ;
               }
            }else{
               $lrc = addslashes($result["data"]["lyric"]) ;
               $sql = "insert into all_music(song , singer , album , img , local , mid , lrc) values('{$result["data"]["songName"]}' , '{$result["data"]["singer"]}' , '{$result["data"]["album"]}' , '{$result["data"]["img"]}' , 1 , {$result["data"]["id"]} , '{$lrc}')" ;
               $flag = DB::insert($sql) ;
               if($flag){
                  $iid = DB::select("SELECT LAST_INSERT_ID() as id") ;
                  DB::insert("insert into user_music(music_id , username) values('{$iid[0] -> id}' , '$username')") ;
               }
            }
         }
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
      // 如果用于已经登陆
      if($username){
         // 1.从音乐表中查出当前歌曲
         $music = DB::select("select id from all_music where mid=$id") ;
         if(count($music) > 0){
            $user = DB::select("select id from user_music where music_id = {$music[0] -> id}") ;
            if(count($user)==0){
               DB::insert("insert into user_music(music_id , username) values({$music[0] -> id} , '$username')") ;
            }
         }else{
            $lrc = addslashes($result["data"]["lyric"]) ;
            $sql = "insert into all_music(song , singer , album , img , local , mid , lrc) values('{$result["data"]["songName"]}' , '{$result["data"]["singer"]}' , '{$result["data"]["album"]}' , '{$result["data"]["img"]}' , 1 , {$result["data"]["id"]} , '{$lrc}')" ;
            $flag = DB::insert($sql) ;
            if($flag){
               $iid = DB::select("SELECT LAST_INSERT_ID() as id") ;
               DB::insert("insert into user_music(music_id , username) values('{$iid[0] -> id}' , '$username')") ;
            }
         }
      }
      return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function playUrl($id){
      $m = DB::select("select * from all_music where id=$id") ;
      if(count($m) > 0){
         $list_music = DB::select("select  url  from all_music where id=$id") ;
         $result = Array("code" => 200 , "msg" => "成功" , "data" => ($list_music[0])) ;
         return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
      }
      $api = new MetingMusic("netease") ;
      $song = $api -> format(true) -> url($id) ;
      $song = json_decode($song , true)["url"] ;
      $result = Array("code" => 200 , "msg" => "成功" , "data" => Array("url" => $song)) ;
      return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function albums(){
      $list = DB::select("select id , name , img , mid , flag , count from albums") ;
   	$result = Array("code" => 200 , "msg" => "成功" , "data" => ($list)) ;
   	return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
   }

   public function album($id){
      $list = DB::select("select flag , mid from albums where id=$id limit 1") ;
      if($list[0] -> flag == 1){
         $list_music = DB::select("select * from all_music where id in (select music_id from album_music_list where album_id=$id)") ;
         $data = Array() ;
         for($i = 0 ; $i < count($list_music) ; $i++){
            $temp = Array("id" => $list_music[$i] -> id , "name" => $list_music[$i] -> song , "artist" => Array(0 => $list_music[$i] -> singer) , "album" => $list_music[$i] -> album) ;
            array_push($data , $temp) ;
         }
         $result = Array("code" => 200 , "msg" => "成功" , "data" => $data) ;
         return response(json_encode($result)) -> header("Content-Type" , "application/json") ;
      }
      $mid = $list[0] -> mid ;
   	$filename = "./music/album".$mid.".json" ;
   	if(file_exists($filename)){
   		$file = fopen($filename , "r") or die("file exception") ;
   		$data = fread($file , filesize($filename)) ;
   		fclose($file) ;
   		$result = Array("code" => 200 , "msg" => "成功" , "data" => json_decode($data , true)) ;
   		return response($result) -> header("Content-Type" , "application/json") ;
   	}
   	$api = new MetingMusic("netease") ;
   	$data = $api -> format(true) -> playlist($mid) ;
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

   public function getuserinfo($username)
   {
   	$student = DB::select("select user,role,name from users where user='$username' limit 1");
   	$result = Array("code" => 200, "msg" => "成功", "data" => $student[0]);
   	return response(json_encode($result)) -> header("Content-Type", "application/json");
   }

   public function list(){
      $username = request() -> cookie("username") ;
      $list = DB::select("select all_music.song as songName , all_music.singer as singer , all_music.album as album , all_music.url as play_url , all_music.img as img , all_music.local as local , all_music.mid as id , all_music.lrc as lrc from all_music , user_music where all_music.id = user_music.music_id and username='$username'") ;
      $result = Array("code" => 200, "msg" => "成功", "data" => $list);
      return response(json_encode($result)) -> header("Content-Type", "application/json");
   }
 }
