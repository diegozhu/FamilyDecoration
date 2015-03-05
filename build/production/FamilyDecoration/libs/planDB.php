<?php

	function addPlan($post){
		$projectId = $post["projectId"];
		$plans = getPlanByProjectId($projectId);
		if(count($plans) != 0){
			throw new Exception("plan with projectId:$projectId already exist!");
		}
		$obj = array(
			"id"=>date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT),
			"projectId"=>$projectId,
			"prework"=>$post["prework"],
			"matPrepare"=>$post["matPrepare"],
			"waterPower"=>$post["waterPower"],
			"cementBasic"=>$post["cementBasic"],
			"cementAdvanced"=>$post["cementAdvanced"],
			"wallFloor"=>$post["wallFloor"],
			"cleaning"=>$post["cleaning"],
			"woods"=>$post["woods"],
			"painting"=>$post["painting"]
		);
		global $mysql;
		$mysql->DBInsertAsArray("`plan`",$obj);
		return array('status'=>'successful', 'errMsg' => '','planId'=> $obj["id"]);
	}

	function deletePlan($planId){
		global $mysql;
		$condition = "`id` = '$planId' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`plan`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}

	function deletePlanByProjectId($projectId){
		global $mysql;
		$condition = "`projectId` = '$projectId' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`plan`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}

	function getPlan($id){
		global $mysql;
		$arr = $mysql->DBGetSomeRows("`plan`", " * "," where id = '$id' " ,"");
		$count = 0;
		$res = array();
		foreach($arr as $key => $val) {
		    $res[$count]["id"] = $val["id"];
		    $res[$count]["projectId"] = $val["projectId"];
		    $res[$count]["createTime"] = $val["createTime"];
			$res[$count]["prework"] = $val["prework"];
			$res[$count]["matPrepare"] = $val["matPrepare"];
			$res[$count]["waterPower"] = $val["waterPower"];
			$res[$count]["cementBasic"] = $val["cementBasic"];
			$res[$count]["cementAdvanced"] = $val["cementAdvanced"];
			$res[$count]["wallFloor"] = $val["wallFloor"];
			$res[$count]["cleaning"] = $val["cleaning"];
			$res[$count]["woods"] = $val["woods"];
			$res[$count]["painting"] = $val["painting"];
		    $count ++;
        }
		return $res;
	}

	function getPlanByProjectId($loglistId){
		global $mysql;
		$arr = $mysql->DBGetSomeRows("`plan`", " * "," where projectId = '$loglistId' and isDeleted = 'false' " ," order by createTime ");
		$count = 0;
		$res = array();
		foreach($arr as $key => $val) {
		    $res[$count]["id"] = $val["id"];
		    $res[$count]["projectId"] = $val["projectId"];
		    $res[$count]["createTime"] = $val["createTime"];
			$res[$count]["prework"] = $val["prework"];
			$res[$count]["matPrepare"] = $val["matPrepare"];
			$res[$count]["waterPower"] = $val["waterPower"];
			$res[$count]["cementBasic"] = $val["cementBasic"];
			$res[$count]["cementAdvanced"] = $val["cementAdvanced"];
			$res[$count]["wallFloor"] = $val["wallFloor"];
			$res[$count]["cleaning"] = $val["cleaning"];
			$res[$count]["woods"] = $val["woods"];
			$res[$count]["painting"] = $val["painting"];
		    $count ++;
        }
		return $res;
	};

	function editPlan($data){
		global $mysql;
		$condition = "`id` = '".$data["id"]."' ";
		$setValue = " `projectId` = '".$data["projectId"]."'";
		$setValue = $setValue." , `prework` = '".$data["prework"]."'";
		$setValue = $setValue." , `matPrepare` = '".$data["matPrepare"]."'";
		$setValue = $setValue." , `waterPower` = '".$data["waterPower"]."'";
		$setValue = $setValue." , `cementBasic` = '".$data["cementBasic"]."'";
		$setValue = $setValue." , `cementAdvanced` = '".$data["cementAdvanced"]."'";
		$setValue = $setValue." , `wallFloor` = '".$data["wallFloor"]."'";
		$setValue = $setValue." , `cleaning` = '".$data["cleaning"]."'";
		$setValue = $setValue." , `woods` = '".$data["woods"]."'";
		$setValue = $setValue." , `painting` = '".$data["painting"]."'";
		$mysql->DBUpdateSomeCols("`plan`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => 'edit plan ok');
	}
?>