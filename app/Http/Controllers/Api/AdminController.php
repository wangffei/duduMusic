<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/Meting.php") ;
 use App\Http\Controllers\Controller;
 use App\Util\MetingMusic ;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;
 use Illuminate\Support\Facades\Cookie;
 use Illuminate\Support\Facades\Storage;

 class AdminController extends Controller{
	public function data()
	{
		$music = DB::select("select * from all_music");
		$result = Array("code" => 0, "msg" => "成功", "count" => 1, "data" => $music);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}

	// 登录
	public function login1(Request $request)
	{
		$username = $request -> input("username");
		$password = $request -> input("password");
		$admin = DB::select("select user, role, name from users
							where user = '$username' and pwd = '$password' limit 1");
		if(count($admin) > 0)
		{
			$result = Array("code" => 200, "msg" => "登录成功！", "count" => 1, "data" => $admin);
		}
		else
		{
			$result = Array("code" => 500, "msg" => "账号或者密码错误！", "count" => 1, "data" => $admin);
		}
		$cookie = Cookie::make("username" , $username , $minutes = 30, $path = null, $domain = null, $secure = false, $httpOnly = false) ;
		return response(json_encode($result)) -> header("Content-Type", "application/json") -> withCookie($cookie) ;
	}

	// 注册
	public function register1(Request $request)
	{
		$username = $request -> input("username");
		$password = $request -> input("password");
		$name = $request -> input("name");
		$gender = $request -> input("gender");
		$addr = $request -> input("addr");
		$birth = $request -> input("birth");

		$result = null ;
		if (is_null($username))
		{
			$result = Array("code" => 500, "msg" => "用户名不能为空!!!", "count" => 1, "data" => "");
		}
		if (is_null($password))
		{
			$result = Array("code" => 500, "msg" => "密码不能为空!!!", "count" => 1, "data" => "");
		}
		if (is_null($name))
		{
			$result = Array("code" => 500, "msg" => "昵称不能为空!!!", "count" => 1, "data" => "");
		}

		if($result != null){
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
		else
		{
			$bool = DB::insert("insert into users(user, pwd, name, gender, addr, birth) values('$username', '$password', '$name', '$gender', '$addr', $birth)");
			//var_dump($bool);
			$result = Array("code" => 200, "msg" => "注册成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 上传文件
	public function upload_file1(Request $request)
	{
		// 1、获取上传的文件

		$file=$request->file('file');
		// 2、获取上传文件的文件名（带后缀，如abc.png）

		$filename=$file->getClientOriginalName();
		// 3、获取上传文件的后缀（如abc.png，获取到的为png）

		$fileextension=$file->getClientOriginalExtension();
		// 4、获取上传文件的大小

		// $filesize=$file->getClientSize();
		// 5、获取缓存在tmp目录下的文件名（带后缀，如php8933.tmp）

		// $filaname=$file->getFilename();
		// 6、获取上传的文件缓存在tmp文件夹下的绝对路径

		$realpath=$file->getRealPath();
		// 7、将缓存在tmp目录下的文件移到某个位置，返回的是这个文件移动过后的路径
		$newfilename = time().".".$fileextension ;
		$path=$file->move("./tmp/" , $newfilename);
		// move() 方法有两个参数，第一个参数是文件移到哪个文件夹下的路径，第二个参数是将上传的文件重新命名的文件名

		// 8、检测上传的文件是否合法，返回值为true或false

		if(!$file->isValid()){
			$result = Array("code" => 0, "msg" => "上传成功" , "data" => Array("file" => "./tmp/".$newfilename));
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else{
			$result = Array("code" => 500, "msg" => "上传失败" , "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 提交表单
	public function commit1(Request $request)
	{
		// 获取需要提交的信息
		$song = $request -> input("song");
		$singer = $request -> input("singer");
		$album = $request -> input("album");
		$img_file = $request -> input("img_file");
		$music_file = $request -> input("music_file");
		$img = null;
		$url = null;

		if(is_null($img_file) || is_null($music_file)){
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}

		// 获得移动后文件的地址并且移动文件
		if (strrchr($img_file, '/'))
		{
			$img = "./imgfile".strrchr($img_file, '/');
			$this -> move($img_file, $img);
		}
		if (strrchr($music_file, '/'))
		{
			$url = "./musicfile".strrchr($music_file, '/');
			$this -> move($music_file, $url);
		}

		// 将所有数据写入数据库
		if ($img!=null && $url!=null)
		{
			$bool = DB::insert("insert into all_music(song, singer, album, url, img, local) values('$song', '$singer', '$album', '$url', '$img', 0)");
			//var_dump($bool);
			$result = Array("code" => 200, "msg" => "提交成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
		else
		{
			$result = Array("code" => 500, "msg" => "提交失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	public function song_list1(Request $request){
		// 需要提交的信息
		$name = $request -> input("name");
		$img_file = $request -> input("img_file");
		$url = null;

		if(is_null($img_file) || is_null($name)){
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}

		// 获得移动后文件的地址并且移动文件
		if (strrchr($img_file, '/'))
		{
			$url = "./song_list".strrchr($img_file, '/');
			$this -> move($img_file, $url);
		}

		// 将所有数据写入数据库
		if ($url != null)
		{
			$bool = DB::insert("insert into albums(name, img, flag, mid) values('$name', '$url', 1, 0)");
			//var_dump($bool);
			$result = Array("code" => 200, "msg" => "提交成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
		else
		{
			$result = Array("code" => 500, "msg" => "提交失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	private function move($source , $dist){
		copy($source , $dist) ;
		//unlink($source);
	}
 }