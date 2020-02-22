<?php
 
 namespace App\Http\Controllers\Api  ;

 require_once(dirname(__FILE__) . "/../../../Util/Meting.php") ;
 use App\Http\Controllers\Controller;
 use App\Util\MetingMusic ;
 use Illuminate\Http\Request;
 use Illuminate\Support\Facades\DB;

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
		return response(json_encode($result)) -> header("Content-Type", "application/json");
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
			$bool = DB::insert("insert into users(user, pwd, name, gender, addr, birth) values($username, $password, $name, $gender, $addr, $birth)");
			//var_dump($result);
			$result = Array("code" => 200, "msg" => "注册成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 上传文件
	public function upload_file1()
	{
		
	}
 }