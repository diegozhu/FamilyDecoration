<?php
	
	function getBusinessDetails($businessId){
		global $mysql;
		$arr = $mysql->DBGetSomeRows("`business_detail`", " * "," where businessId = '$businessId' and `isDeleted` = 'false' " ,"ORDER BY  `id` DESC");
		$count = 0;
		$res = array();
		foreach($arr as $key => $val) {
		    $res[$count]["id"] = $val["id"];
		    $res[$count]["content"] = $val["content"];
		    $count ++;
        }
		return $res;
	}

	function addBusinessDetail($post){
		$obj = array(
			"id"=>date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT),
			"businessId"=>$post["businessId"],
			"content"=>$post["content"]
		);
		global $mysql;
		$mysql->DBInsertAsArray("`business_detail`",$obj);
		return array('status'=>'successful', 'errMsg' => '','businessDetailId'=> $obj["id"],'content'=>$post["content"]);
	}

	function deleteBusinessDetail($businessDetailId){
		global $mysql;
		$condition = "`id` = '$businessDetailId' ";
		$setValue = " `isDeleted` = 'true' ";
		$mysql->DBUpdateSomeCols("`business_detail`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}

	function editBusinessDetail($data){
		global $mysql;
		$id = $data["id"];
		$condition = "`id` = '$id' ";
		$setValue = " `content` = '".$data["content"]."'";
		$mysql->DBUpdateSomeCols("`business_detail`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => 'edit business detail ok');
	}
?>