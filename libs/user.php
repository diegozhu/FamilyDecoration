<?php
	include_once "conn.php";
	$action = $_GET["action"];
	$prefix = "familydecoration-";
	$res = "";
	switch($action){
		case "register": 				$res = register();  break;
		case "login":					$res = login(); break;
		case "logout":					$res = logout(); break;
		case "edit":					$res = edit(); break;
		case "modify":					$res = modify(); break;
		case "view":					$res = getList(); break;
		case "reset":					$res = resetAccount(); break;
		default: 		throw new Exception("unknown action:".$action);
	}
	echo $res;

	/**
	 * [register]
	 * @param array $user [consists of name, password]
	 */
	function register (){
			$name = $_POST["name"];
			$password = $_POST["password"];
			$level = $_POST["level"];
			global $mysql, $prefix;
			$user = $mysql->DBGetOneRow("`user`", "*", "`name` = '$name'");
			if($user){
				throw new Exception('用户已经存在！');
			}
			$password = md5($prefix.$password);
			$mysql->DBInsert("`user`", "`name`, `password`, `level`","'".$name."', '".$password."', ".$level);
			return json_encode(array('status'=>'successful', 'errMsg' => ''));
	}

	/**
	 * [login description]
	 * @return [type]       [description]
	 */
	function login (){
		$name = $_REQUEST["name"];
		$password = $_REQUEST["password"];
		global $mysql, $prefix;
		$password = md5($prefix.$password);
		$user = $mysql->DBGetOneRow("`user`", "*", "`name` = '$name'");
		if ($user["name"] == $name && $user["password"] == $password) {
			$sessionId = session_id();
			$_SESSION["name"] = $user["name"];
			$_SESSION["password"] = $user["password"];
			$_SESSION["level"] = $user["level"];
			if($name == "admin"){
				$_SESSION["admin"] = true;
				//update
				$condition = "`id` = '2' ";
				$setValue = " `updateTime` = CURRENT_TIMESTAMP , `paramValue` = '$sessionId' ";
				$mysql->DBUpdateSomeCols("`system`", $condition, $setValue);
			}
			
			return json_encode(array('status'=>'successful', 'errMsg'=>'','token'=>$sessionId));
		}
		throw new Exception('用户或密码不正确！');
	}

	/**
	 * [logout description]
	 * @return [type] [description]
	 */
	function logout() {
		session_unset();
		session_destroy();
		return json_encode(array('status'=>'successful', 'errMsg'=>''));
	}

	/**
	 * [edit password by current account user]
	 * @return [type] [description]
	 */
	function edit (){
		$name = $_POST["name"];
		$oldpassword= $_POST['oldpassword'];
		$newpassword= $_POST['newpassword'];
		$level = $_POST["level"];

		global $mysql, $prefix;

		$oldpassword = md5($prefix.$oldpassword);
		$newpassword = md5($prefix.$newpassword);

		$user = $mysql->DBGetOneRow("`user`", "*", "`name` = '$name'");

		if ($user["name"] == $name) {
			if ($user["password"] == $oldpassword) {
				$mysql->DBUpdateOneCol("`user`", "`name` = '".$user["name"]."'", "`password`", $newpassword);
				return json_encode(array('status'=>'successful', 'errMsg'=>''));
			} else {
				throw new Exception('原密码不正确！');
			}
		} else {
			throw new Exception('用户不存在！');
		}
	}

	/**
	 * [edit password by administrator]
	 * @param array $user [consists of name, password]
	 */
	function modify (){
		$name = $_POST["name"];
		$password = $_POST["password"];
		$level = $_POST["level"];
		global $mysql, $prefix;
		$password = md5($prefix.$password);
		$mysql->DBUpdateSomeCols("`user`", "`name` = '$name'", "`password` = '$password', `level` = '$level'");
		return json_encode(array('status'=>'successful', 'errMsg' => ''));
	}

	/**
	 * [reset account's password]
	 * @return [type] [description]
	 */
	function resetAccount (){
		$name = $_POST["name"];

		global $mysql, $prefix;

		$newpassword = md5($prefix."666666");

		$user = $mysql->DBGetOneRow("`user`", "*", "`name` = '$name'");

		if ($user["name"] == $name) {
			$mysql->DBUpdateOneCol("`user`", "`name` = '".$user["name"]."'", "`password`", $newpassword);
			return json_encode(array('status'=>'successful', 'errMsg'=>''));
		} else {
			return json_encode(array('status'=>'failing', 'errMsg'=>'用户不存在！'));
		}
	
	}

	/**
	 * get all user in a list.
	 * @return json user json string
	 */
	function getList (){
		global $mysql, $prefix;
		$res = $mysql->DBGetAllRows("`user`", "*");
		return json_encode($res);
	}
?>