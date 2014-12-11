<?php
	// ---end of the page
	function addBudget($post){
		$count = count(getBudgetsByProjectName($_REQUEST["projectName"]));
		if($count > 0) 
			throw new Exception("项目 : '".$_REQUEST["projectName"]."' 已经存在!");
		$obj = array(
			"budgetId" => "budget-".date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT),
			"projectName"=>$post["projectName"],
			"custName"=>$post["custName"],
			"areaSize"=>$post["areaSize"],
			"totalFee"=>$post["totalFee"],
			"comments"=>$post["comments"],
			"isDeleted"=>false
		);
		global $mysql;
		$mysql->DBInsertAsArray("`budget`",$obj);
		return array('status'=>'successful', 'errMsg' => '');
	}

	function addBugetItem($post){
		foreach ($post as $key => $value) {
			$post[$key] = explode(">>><<<", $value);
		}
		$len = count($post["itemName"]);

		global $mysql;

		for ($i = 0; $i < $len; $i++) {
			$obj = array(
				"budgetItemId" => "budget-item-".date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT),
				"itemName"=>$post["itemName"][$i],
				"budgetId"=>$post["budgetId"][$i],
				"itemCode"=>$post["itemCode"][$i],
				"itemUnit"=>$post["itemUnit"][$i],
				"itemAmount"=>($post["itemAmount"][$i] ),
				"mainMaterialPrice"=>($post["mainMaterialPrice"][$i] ),
				"auxiliaryMaterialPrice"=>($post["auxiliaryMaterialPrice"][$i] ),
				"manpowerPrice"=>($post["manpowerPrice"][$i] ),
				"machineryPrice"=>($post["machineryPrice"][$i] ),
				"lossPercent"=>($post["lossPercent"][$i] ),
				"isDeleted"=>false,
				"remark"=>$post["remark"][$i],
			);
			$mysql->DBInsertAsArray("`budget_item`",$obj);
		}
		
		return array('status'=>'successful', 'errMsg' => '');
	}
	
	function delBudget ($id){
		global $mysql;
		$condition = "`budgetId` = '$id' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`Budget`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
	function delBudgetItem($ItemId){
		global $mysql;
		$condition = "`budgetItemId` = '$ItemId' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`budget_item`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
	function getBudgets (){
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget`", "*", " where `isDeleted` = 'false' ");
		foreach($arr as $key => $val) {
			$res[$key]['projectName'] = urlencode($val['projectName']);
			$res[$key]['budgetId'] = urlencode($val['budgetId']);
			$res[$key]['custName'] = urlencode($val['custName']);
			$res[$key]['areaSize'] = urlencode($val['areaSize']);
			$res[$key]['totalFee'] = urlencode($val['totalFee']);
			$res[$key]['comments'] = urlencode(addslashes($val['comments']));
		}
		return $res;
	}
	
	function getBudgetsByBudgetId ($budgetId){
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget`", "*", "where `budgetId` = '$budgetId' ");
		foreach($arr as $key => $val) {
			$res[$key]['projectName'] = urlencode($val['projectName']);
			$res[$key]['budgetId'] = urlencode($val['budgetId']);
			$res[$key]['custName'] = urlencode($val['custName']);
			$res[$key]['areaSize'] = urlencode($val['areaSize']);
			$res[$key]['totalFee'] = urlencode($val['totalFee']);
			$res[$key]['comments'] = urlencode(addslashes($val['comments']));
		}
		return $res;
	}
	function getBudgetItemsByBudgetIdGBK($budgetId){
		global $mysql;
		
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget_item`", "*", "where `budgetId` = '$budgetId' and `isDeleted` = 'false' ");
		foreach($arr as $key => $val) {
			if("小计" == $val['itemName']) continue;
			$res[$key]['budgetItemId'] = str2GBK($val['budgetItemId']);
			$res[$key]['itemName'] = str2GBK($val['itemName']);
			$res[$key]['budgetId'] = str2GBK($val['budgetId']);
			$res[$key]['itemCode'] = str2GBK($val['itemCode']);
			$res[$key]['itemUnit'] = str2GBK($val['itemUnit']);
			$res[$key]['itemAmount'] = $val['itemAmount'] ;
			$res[$key]['mainMaterialPrice'] = $val['mainMaterialPrice'] ;
			$res[$key]['auxiliaryMaterialPrice'] = $val['auxiliaryMaterialPrice'] ;
			$res[$key]['manpowerPrice'] = $val['manpowerPrice'] ;
			$res[$key]['machineryPrice'] = $val['machineryPrice'] ;
			$res[$key]['lossPercent'] = $val['lossPercent'] ;
			$res[$key]['remark'] = str2GBK($val['remark']);
		}
		usort($res, 'compareBudgetItem');
		return $res;
	}
	function getBudgetItemsByBudgetId ($budgetId) {
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget_item`", "*", "where `budgetId` = '$budgetId' and `isDeleted` = 'false' ");
		foreach($arr as $key => $val) {
			$res[$key]['budgetItemId'] = urlencode($val['budgetItemId']);
			$res[$key]['itemName'] = urlencode($val['itemName']);
			$res[$key]['budgetId'] = urlencode($val['budgetId']);
			$res[$key]['itemCode'] = urlencode($val['itemCode']);
			$res[$key]['itemUnit'] = urlencode($val['itemUnit']);
			$res[$key]['itemAmount'] = urlencode($val['itemAmount'] );
			$res[$key]['mainMaterialPrice'] = urlencode($val['mainMaterialPrice'] );
			$res[$key]['auxiliaryMaterialPrice'] = urlencode($val['auxiliaryMaterialPrice'] );
			$res[$key]['manpowerPrice'] = urlencode($val['manpowerPrice'] );
			$res[$key]['machineryPrice'] = urlencode($val['machineryPrice'] );
			$res[$key]['lossPercent'] = urlencode($val['lossPercent'] );
			$res[$key]['remark'] = urlencode(addslashes(nl2br(str_replace("\n", "<br />", $val['remark']))));
		}
		usort($res, 'compareBudgetItem');
		return $res;
	}
	function compareBudgetItem($arg1,$arg2){
		return strcasecmp($arg1["itemCode"],$arg2["itemCode"]);
	}
	function getBudgetsByProjectName ($projectName){
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget`", "*", "where `projectName` = '$projectName' and `isDeleted` = 'false' ");
		foreach($arr as $key => $val) {
			$res[$key]['projectName'] = urlencode($val['projectName']);
			$res[$key]['budgetId'] = urlencode($val['budgetId']);
			$res[$key]['custName'] = urlencode($val['custName']);
			$res[$key]['areaSize'] = urlencode($val['areaSize']);
			$res[$key]['totalFee'] = urlencode($val['totalFee']);
			$res[$key]['comments'] = urlencode(addslashes($val['comments']));
		}
		return $res;
	}

	function editBudget (array $pro){
		global $mysql;
		$setValue = "";
		foreach ($pro as $key => $val) {
			if ($key == "budgetId" || is_numeric ($key)) {
				continue;
			} else {
				$setValue .= " `".$key."` = '".$val."',";
			}
		}
		$setValue = substr($setValue, 0, -1);
		$condition = "`budgetId` = '".$pro['budgetId']."'";
		$mysql->DBUpdateSomeCols("`Budget`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
?>