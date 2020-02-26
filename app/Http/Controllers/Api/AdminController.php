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
		$user = $request -> input("user");
		$pwd = $request -> input("pwd");
		$name = $request -> input("name");
		$gender = $request -> input("gender");
		$addr = $request -> input("addr");
		$birth = $request -> input("birth");

		$result = null ;
		if (is_null($user))
		{
			$result = Array("code" => 500, "msg" => "用户名不能为空!!!", "count" => 1, "data" => "");
		}
		if (is_null($pwd))
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
			$bool = DB::insert("insert into users(user, pwd, name, gender, addr, birth) values('$user', '$pwd', '$name', '$gender', '$addr', '$birth')");
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

	// 提交新建歌单信息
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
			$bool = DB::insert("insert into albums(name, img, flag, mid, count) values('$name', '$url', 1, 0, 0)");
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

	// 提交网易云歌单
	public function online_list1(Request $request) {
		// 需要提交的信息
		$name = $request -> input("name");
		$img_file = $request -> input("img_file");
		$img_url = $request -> input("img_url");
		$mid = $request -> input("mid");
		$url = null;

		if(is_null($name) || is_null($mid)){
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}

		if(is_null($img_file) && is_null($img_url)) {
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else if(is_null($img_url) && $img_file != null) {
			// 如果是本地图片
			// 获得移动后文件的地址并且移动文件
			if (strrchr($img_file, '/'))
			{
				$url = "./song_list".strrchr($img_file, '/');
				$this -> move($img_file, $url);
			}

			// 将所有数据写入数据库
			if ($url != null)
			{
				$bool = DB::insert("insert into albums(name, img, flag, mid) values('$name', '$url', 0, $mid)");
				//var_dump($bool);
				$result = Array("code" => 200, "msg" => "提交成功！", "count" => 1, "data" => "");
				return response(json_encode($result)) -> header("Content-Type", "application/json");
			}
			else
			{
				$result = Array("code" => 500, "msg" => "提交失败！", "count" => 1, "data" => "");
				return response(json_encode($result)) -> header("Content-Type", "application/json");
			}
		}else if(is_null($img_file) && $img_url != null) {
			// 如果是网络图片
			$bool = DB::insert("insert into albums(name, img, flag, mid) values('$name', '$img_url', 0, $mid)");
			$result = Array("code" => 200, "msg" => "提交成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "提交的参数有误！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	private function move($source , $dist){
		copy($source , $dist) ;
		//unlink($source);
	}

	public function list_music($id){
		// 1.查询出非本歌单中的歌曲
		$list_no = DB::select("select * from all_music where id not in (select music_id from album_music_list where album_id=$id)") ;
		// 2.查询出本歌单中的歌曲
		$list_yes = DB::select("select * from all_music where id in (select music_id from album_music_list where album_id=$id)") ;
		$result = Array("code" => 200, "msg" => "成功",  "data" => Array("list_yes" => $list_yes , "list_no" => $list_no));
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}

	// 向歌单中增加歌曲
	public function add1(Request $request) {
		// 需要提交的信息
		$album_id = $request -> input('album_id');
		$music_id = $request -> input('music_id');
		//dump($album_id) ;
		//dump($music_id) ;

		if($album_id!=null && $music_id!=null) {
			foreach ($music_id as $value) {
			 	# code...
			 	$bool = DB::insert("insert into album_music_list(album_id, music_id) values($album_id, $value)");
			}
			$result = Array("code" => 200, "msg" => "增加成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "增加失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 从歌单中删除歌曲
	public function delete1(Request $request) {
		// 需要删除的信息
		$album_id = $request -> input('album_id');
		$music_id = $request -> input('music_id');

		if($album_id!=null && $music_id!=null) {
			foreach ($music_id as $value) {
				$bool = DB::delete("delete from album_music_list where album_id=$album_id and music_id=$value");
			}
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 修改歌单中count的值
	public function update_count1(Request $request) {
		$count = $request -> input('count');
		$id = $request -> input('id');

		if($count != null && $id != null) {
			$bool = DB::update('update albums set count=? where id=?', [$count, $id]);
			$result = Array("code" => 200, "msg" => "修改成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "修改失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 删除歌单
	public function delete_list1(Request $request) {
		$id = $request -> input('id');

		if($id != null) {
			$bool = DB::delete("delete from albums where id=?", [$id]);
			$num = DB::delete("delete from album_music_list where album_id=?", [$id]);
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 批量删除歌单
	public function delete_lists1(Request $request) {
		$ids = $request -> input('ids');

		if($ids != null) {
			foreach ($ids as $value) {
				$bool = DB::delete("delete from albums where id=?", [$value]);
				$num = DB::delete("delete from album_music_list where album_id=?", [$value]);
			}
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 修改歌单信息
	public function update_list1(Request $request) {
		$id = $request -> input('id');          // 需要修改的歌单ID
		$field = $request -> input('field');    // 需要修改的列
		$element = $request -> input('element');// 需要更新的值

		if (is_null($id) || is_null($field) || is_null($element)) {
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		} else if($field == 'name') {
			$bool = DB::update("update albums set name=? where id=?", [$element, $id]);
			$result = Array("code" => 200, "msg" => "更新成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		} else if($field == 'img') {
			$bool = DB::update("update albums set img=? where id=?", [$element, $id]);
			$result = Array("code" => 200, "msg" => "更新成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		} else {
			$result = Array("code" => 500, "msg" => "修改失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 删除歌曲
	public function delete_music1(Request $request) {
		$id = $request -> input('id');

		if($id != null) {
			$bool = DB::delete("delete from all_music where id=?", [$id]);
			$num = DB::delete("delete from album_music_list where music_id=?", [$id]);
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 批量删除歌曲
	public function delete_musics1(Request $request) {
		$ids = $request -> input('ids');

		if($ids != null) {
			foreach ($ids as $value) {
				$bool = DB::delete("delete from all_music where id=?", [$value]);
				$num = DB::delete("delete from album_music_list where music_id=?", [$value]);
			}
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 修改歌曲信息
	public function update_music1(Request $request) {
		$id = $request -> input('id');
		$song = $request -> input('song');
		$album = $request -> input('album');
		$singer = $request -> input('singer');

		if (is_null($song) || is_null($album) || is_null($singer) || is_null($id)) {
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		} else {
			$bool1 = DB::update("update all_music set song=? where id=?", [$song, $id]);
			$bool2 = DB::update("update all_music set album=? where id=?", [$album, $id]);
			$bool3 = DB::update("update all_music set singer=? where id=?", [$singer, $id]);
			$result = Array("code" => 200, "msg" => "更新成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 获取用户列表
	public function all_users1() {
		$user = DB::select("select * from users");
		$result = Array("code" => 0, "msg" => "成功", "count" => 1, "data" => $user);
		return response(json_encode($result)) -> header("Content-Type", "application/json");
	}

	// 删除用户
	public function delete_user1(Request $request) {
		$id = $request -> input('id');
		$username = $request -> input('username');

		if($id != null && $username != null) {
			$bool = DB::delete("delete from users where id=?", [$id]);
			$num = DB::delete("delete from user_music where username=?", [$username]);
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 批量删除用户
	public function delete_users1(Request $request) {
		$ids = $request -> input('ids');
		$usernames = $request -> input('usernames');

		if($ids != null && $usernames != null) {
			foreach ($ids as $value) {
				$bool = DB::delete("delete from users where id=?", [$value]);
			}
			foreach ($usernames as $value) {
				$num = DB::delete("delete from user_music where username=?", [$value]);
			}
			$result = Array("code" => 200, "msg" => "删除成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}else {
			$result = Array("code" => 500, "msg" => "删除失败！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}

	// 修改用户信息
	public function update_user1(Request $request) {
		$id = $request -> input('id');
		$user = $request -> input('user');
		$pwd = $request -> input('pwd');
		$name = $request -> input('name');
		$gender = $request -> input('gender');
		$addr = $request -> input('addr');
		$birth = $request -> input('birth');

		if (is_null($user) || is_null($pwd) || is_null($name) || is_null($id)) {
			$result = Array("code" => 500, "msg" => "参数不完整", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		} else {
			$bool1 = DB::update("update users set user=? where id=?", [$user, $id]);
			$bool2 = DB::update("update users set pwd=? where id=?", [$pwd, $id]);
			$bool3 = DB::update("update users set name=? where id=?", [$name, $id]);
			$bool4 = DB::update("update users set gender=? where id=?", [$gender, $id]);
			$bool5 = DB::update("update users set addr=? where id=?", [$addr, $id]);
			$bool6 = DB::update("update users set birth=? where id=?", [$birth, $id]);
			$result = Array("code" => 200, "msg" => "更新成功！", "count" => 1, "data" => "");
			return response(json_encode($result)) -> header("Content-Type", "application/json");
		}
	}
}