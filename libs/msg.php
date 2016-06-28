<?php
	include_once "conn.php";
	include_once "msgLogDB.php";
	$action = $_REQUEST["action"];
	$action = strtolower($action);
	$res;
	switch($action){
		//查询发送的短信
		case "getsendmsgs":
			$sender = isset($_REQUEST['sender']) ? $_REQUEST['sender'] : null; //根据用户名查
			$reciever = isset($_REQUEST['reciever']) ? $_REQUEST['reciever'] : null; //根据用户名查
			$recieverPhone = isset($_REQUEST['recieverPhone']) ? $_REQUEST['recieverPhone'] : null;// 根据手机号查
			$status = isset($_REQUEST['status']) ? $_REQUEST['status'] : null;
			$beginTime = isset($_REQUEST['beginTime']) ? $_REQUEST['beginTime'] : date('Y-m-d H:i:s',time() - 3600*24*7);
			$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] :  date('Y-m-d H:i:s');
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
			$size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 30;
			$res = getsendmsgs($sender,$reciever,$recieverPhone,$status,$beginTime,$endTime,$page,$size);
			break;
		//查询收到的短信
		case "getrecvmsgs":
			$sender = isset($_REQUEST['sender']) ? $_REQUEST['sender'] : null;    //根据用户名查
			$senderPhone = isset($_REQUEST['senderPhone']) ? $_REQUEST['senderPhone'] : null; // 根据手机号查
			$beginTime = isset($_REQUEST['beginTime']) ? $_REQUEST['beginTime'] : date('Y-m-d H:i:s',time() - 3600*24*7);
			$endTime = isset($_REQUEST['endTime']) ? $_REQUEST['endTime'] :  date('Y-m-d H:i:s');
			$page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
			$size = isset($_REQUEST['size']) ? $_REQUEST['size'] : 30;
			$res = getrecvmsgs($sender,$senderPhone,$endTime,$page,$size);
			break;
		//检查短信有没有敏感词
		case "checkmsg":
			$res = checkMsg($_REQUEST['content']);
			break;
		//发送短信
		case "sendmsg":
			$sender = $_REQUEST['sender'];  //发送人姓名
			$reciever = $_REQUEST['reciever'];//接收人姓名
			$recieverPhone = $_REQUEST['recieverPhone'];//接收人手机号
			$content = $_REQUEST['content']; //content
			$time = isset($_REQUEST['time']) ? $_REQUEST['time'] : null;  // 定时短信，时间格式20090101101010
			$res = sendMsg($sender,$reciever,$recieverPhone,$content,$time,'sendSMS');
			break;
		//查询余额
		case "getbalance":
			$res = getBalance();
			break;
		//从运营商收取短信  --- 这个接口添加到定时任务里
		case "getmsgfromvendor":
			$res = syncWithMsgVendorServer();
			break;
		default: 		
			throw new Exception("unknown action:".$action);
	}
	echo (json_encode($res));
?>