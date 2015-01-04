<?php
	/**
	 * @desc Common operations including functions and operations.
	 * @auth Diego & Alex
	 */

	function ErrorHandler($errno, $errstr,$errorFile,$errorLine){
		//print_r();
		$result = array("status" => "failing","errMsg" =>"[$errno]:$errstr","file"=>$errorFile,"line"=>$errorLine);
		echo json_encode($result);
		die();
	}
	function ExceptionHandler($e){
		$result = array("status" => "failing","errMsg" =>$e->getMessage(),"file"=>$e->getFile(),"line"=>$e->getLine());
		echo json_encode($result);
		die();
	}
	function str2GBK($str){
		$res = '';
		#$res = is_null($str) ? "" : iconv("UTF-8","GB2312//IGNORE",$str);  //平方米等utf8单位会丢失
		$res = is_null($str) ? "" : mb_convert_encoding($str,"gbk","utf-8");
		return $res;
	};
	set_error_handler("ErrorHandler");
	set_exception_handler("ExceptionHandler");
?>