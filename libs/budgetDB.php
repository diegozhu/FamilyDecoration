<?php
	//获取项目下一个ItemCode
	function getNextItemCode($budgetId){
		global $mysql;
		$arr = $mysql->DBGetSomeRows("`budget_item`", " itemCode", " where `isDeleted` = 'false' and `budgetId` = '$budgetId' "," order by itemCode asc");
		$existItemCode = array();
		foreach($arr as $item){
			$itemCode = $item["itemCode"];
			if(strlen($itemCode) == 1 && !in_array($itemCode,array('N','O','P','Q','R','S'))){
				$existItemCode[$itemCode] = $itemCode;
			}
		}
		$ItemCodeList = array("A","B","C","D","E","F","G","H","I","J","K","L","M");
		$count = 0;
		foreach($ItemCodeList as $count){
			if(!in_array($count,$existItemCode)){
				return array('status'=>'successful', 'errMsg' => '','itemCode'=>$count);
			}
		}
		throw "超过itemCode最大值M";
	}
	//更新项目ItemCode
	function renewItemCode($budgetId,$ItemCode){
		global $mysql;
		$res= array();
		if(strlen($ItemCode) != 1){
			$ItemCode = substr($ItemCode, 0, 1);
		}
		$arr = $mysql->DBGetSomeRows("`budget_item`", " itemCode,budgetItemId", " where `isDeleted` = 'false' and `budgetId` = '$budgetId' and `itemCode` like '%$ItemCode%' "," order by itemCode asc");
		$itemCodeCount = 1;
		foreach($arr as $item) {
			$itemCode = $item['itemCode'];
			$budgetItemId = $item['budgetItemId'];
			if($itemCode == $ItemCode){
				continue; 
			}
			if($itemCode != $ItemCode."-".$itemCodeCount){
				//update
				$condition = "`budgetItemId` = '$budgetItemId' ";
				$setValue = " `itemCode` = '$ItemCode-$itemCodeCount'";
				$mysql->DBUpdateSomeCols("`budget_item`", $condition, $setValue);
			}
			$itemCodeCount++;
		}
		return $res;
	}
	//删除大项
	function delBigItem($budgetId,$itemCode){
		global $mysql;
		$condition = "`budgetId` = '$budgetId' and `itemCode` like '%$itemCode%' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`budget_item`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
	
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
				"basicItemId"=>$post["basicItemId"][$i],
				"basicSubItemId"=>$post["basicSubItemId"][$i]
			);
			$mysql->DBInsertAsArray("`budget_item`",$obj);
		}
		//更新下项的ItemCode
		renewItemCode($post["budgetId"][0],$post["itemCode"][0]);
		return array('status'=>'successful', 'errMsg' => '');
	}
	
	function delBudget ($id){
		global $mysql;
		$condition = "`budgetId` = '$id' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`Budget`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
	function delBudgetItem($budgetId,$ItemId,$ItemCode){
		global $mysql;
		$condition = "`budgetItemId` = '$ItemId' ";
		$setValue = " `isDeleted` = 'true'";
		$mysql->DBUpdateSomeCols("`budget_item`", $condition, $setValue);
		//更新下项的ItemCode
		renewItemCode($budgetId,$ItemCode);
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

	function getBudgetItemsByBudgetId ($budgetId , $isGBK = false,$isNOPQRSAmount = true) {
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetSomeRows("`budget_item`", "*", "where `budgetId` = '$budgetId' and `isDeleted` = 'false' "," order by itemCode asc");
		$count = 0;
		$smallCount = array(0,0,0,0);
		$directFee = 0;
		$isFirstSmallCount = true;
		foreach($arr as $val) {
			$itemCode = $val['itemCode'];
			$itemUnit = $val['itemUnit'];
			$itemAmount = $val['itemAmount'];
			$budgetId = $val['budgetId'];
			//这几项需要单独计算
			if(in_array($itemCode,array('N','O','P','Q','R','S'))){
				$otherItems[$itemCode] = $val;
				continue;
			}
			// itemCode  长度为1时认为是大项
			if(strlen($itemCode) == 1){
				if($isFirstSmallCount){
				//第一个大项出现时不输出小计
					$isFirstSmallCount = false;
				}else{
				//增加一行小计
				$res[$count++] = array('budgetItemId'=>'NULL'.$count,'itemName'=>$isGBK ? str2GBK('小计') : urlencode('小计'),'budgetId'=>$budgetId,
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>$smallCount[0],'auxiliaryMaterialTotalPrice'=>$smallCount[1],
								'manpowerTotalPrice'=>$smallCount[2],'machineryTotalPrice'=>$smallCount[3],'lossPercent'=>'','remark'=>'');
				$directFee+=$smallCount[0];
				$directFee+=$smallCount[1];
				$directFee+=$smallCount[2];
				$directFee+=$smallCount[3];
				$smallCount = array(0,0,0,0);
				}
			}
			//正常输出项
			$res[$count]['budgetItemId'] = $val['budgetItemId'];
			$res[$count]['itemName'] = $isGBK ? str2GBK($val['itemName']) :  urlencode($val['itemName']);
			$res[$count]['budgetId'] = $val['budgetId'];
			$res[$count]['itemCode'] = $val['itemCode'];
			$res[$count]['itemUnit'] = $isGBK ? str2GBK($val['itemUnit']) :  urlencode($val['itemUnit']);
			$res[$count]['itemAmount'] = $itemAmount;
			$res[$count]['mainMaterialPrice'] = $val['mainMaterialPrice'];
			$res[$count]['auxiliaryMaterialPrice'] = $val['auxiliaryMaterialPrice'];
			$res[$count]['manpowerPrice'] = $val['manpowerPrice'];
			$res[$count]['machineryPrice'] = $val['machineryPrice'];
			$res[$count]['lossPercent'] = $val['lossPercent'];
			$res[$count]['mainMaterialTotalPrice'] = $itemAmount * ($val['mainMaterialPrice'] + $val['lossPercent']);
			$res[$count]['auxiliaryMaterialTotalPrice'] =  $itemAmount * $val['auxiliaryMaterialPrice'];
			$res[$count]['manpowerTotalPrice'] = $itemAmount * $val['manpowerPrice'];
			$res[$count]['machineryTotalPrice'] = $itemAmount * $val['machineryPrice'];
			$res[$count]['remark'] = $val['remark'] == 'NULL' ? '' : ($isGBK ? str2GBK($val['remark']) :  urlencode(addslashes(nl2br(str_replace("\n", "<br />", $val['remark'])))));
			$res[$count]['basicItemId'] = $val['basicItemId'];
			$res[$count]['basicSubItemId'] = $val['basicSubItemId'];
			$smallCount[0] +=  $itemAmount * ($val['mainMaterialPrice'] + $val['lossPercent']);
			$smallCount[1] +=  $itemAmount * $val['auxiliaryMaterialPrice'];
			$smallCount[2] +=  $itemAmount * $val['manpowerPrice'];
			$smallCount[3] +=  $val['itemAmount'] * $val['machineryPrice'];
			//如果是大项的话，有些字段要清空
			if(strlen($itemCode) == 1){
				if(!in_array($itemCode,array('N','O','P','Q','R','S'))){
					$res[$count]['itemUnit'] = '';
					$res[$count]['mainMaterialTotalPrice'] = '';
					$res[$count]['itemAmount'] = '';
				}
				$res[$count]['mainMaterialPrice'] = '';
				$res[$count]['auxiliaryMaterialPrice'] = '';
				$res[$count]['manpowerPrice'] = '';
				$res[$count]['machineryPrice'] = '';
				$res[$count]['auxiliaryMaterialTotalPrice'] = '';
				$res[$count]['manpowerTotalPrice'] = '';
				$res[$count]['machineryTotalPrice'] = '';
				$res[$count]['lossPercent'] = '';
				$res[$count]['remark'] = '';
			}
			$count++;
		}
		//如果 isFirstSmallCount 还是初始化的状态true说明没有一行小计,false的时候，说明至少有一个大项输出了。
		if(!$isFirstSmallCount){
			//最后一行小计
			$res[$count++] = array('budgetItemId'=>'NULL'.$count,'itemName'=>$isGBK ? str2GBK('小计') : urlencode('小计'),'budgetId'=>$budgetId,
							'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
							'machineryPrice'=>'','mainMaterialTotalPrice'=>$smallCount[0],'auxiliaryMaterialTotalPrice'=>$smallCount[1],
							'manpowerTotalPrice'=>$smallCount[2],'machineryTotalPrice'=>$smallCount[3],'lossPercent'=>'','remark'=>'');
			$directFee+=$smallCount[0];
			$directFee+=$smallCount[1];
			$directFee+=$smallCount[2];
			$directFee+=$smallCount[3];
			$smallCount = array(0,0,0,0);
		}
		//增加一行空行
		$res[$count++] = array('budgetItemId'=>'NULL'.$count,'itemName'=>'','budgetId'=>$budgetId,'itemCode'=>'','itemUnit'=>'','itemAmount'=>'',
					'mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'','mainMaterialTotalPrice'=>'',
					'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		//计算其他项
		$totalFee = $directFee;
		//N 工程直接费
		$itemUnit = '元';
		$itemName = '工程直接费';
		$itemCode = 'N';
		$item = $otherItems[$itemCode];
		$budgetItemId = $item['budgetItemId'];
		$fee = $directFee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			$arr = editBudgetItem($item);
		}
		// O 设计费
		$itemUnit = '元';
		$itemName = '设计费3%';
		$itemCode = 'O';
		$item = $otherItems[$itemCode];
		$itemAmount = $item['itemAmount'];
		$budgetItemId = $item['budgetItemId'];
		$fee = $directFee * $itemAmount; 
		$totalFee += $fee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>$isNOPQRSAmount ? $itemAmount : '','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,
				'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			editBudgetItem($item);
		}
		// P 效果图
		$itemUnit = '张';
		$itemName = '效果图';
		$itemCode = 'P';
		$item = $otherItems[$itemCode];
		$itemAmount = $item['itemAmount'];
		$budgetItemId = $item['budgetItemId'];
		$fee = 500 * $itemAmount; 
		$totalFee += $fee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>$isNOPQRSAmount ? $itemAmount : '','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,
				'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			editBudgetItem($item);
		}
		// Q 5%管理费
		$itemUnit = '元';
		$itemName = '5%管理费';
		$itemCode = 'Q';
		$item = $otherItems[$itemCode];
		$itemAmount = $item['itemAmount'];
		$budgetItemId = $item['budgetItemId'];
		$fee = $directFee * $itemAmount; 
		$totalFee += $fee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>$isNOPQRSAmount ? $itemAmount : '','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,
				'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			editBudgetItem($item);
		}
		// R 税金
		$itemUnit = '元';
		$itemName = '税金';
		$itemCode = 'R';
		$item = $otherItems[$itemCode];
		$itemAmount = $item['itemAmount'];
		$budgetItemId = $item['budgetItemId'];
		$fee = $directFee * $itemAmount; 
		$totalFee += $fee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>$isNOPQRSAmount ? $itemAmount : '','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,
				'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			editBudgetItem($item);
		}
		// S 工程总造价
		$itemUnit = '元';
		$itemName = '工程总造价';
		$itemCode = 'S';
		$item = $otherItems[$itemCode];
		$budgetItemId = $item['budgetItemId'];
		$itemAmount = '';
		$fee = $totalFee;
		$res[$count++] = array('budgetItemId'=>$budgetItemId,'itemName'=>$isGBK ? str2GBK($itemName):urlencode($itemName),'budgetId'=>$budgetId,'itemCode'=>$itemCode,
				'itemUnit'=>$isGBK ? str2GBK($itemUnit):urlencode($itemUnit),'itemAmount'=>$isNOPQRSAmount ? $itemAmount : '','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'','machineryPrice'=>'',
				'mainMaterialTotalPrice'=>$fee,
				'auxiliaryMaterialTotalPrice'=>'','manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		if($fee != $item['mainMaterialPrice']){
			$item['mainMaterialPrice'] = $fee;
			// update
			editBudgetItem($item);
		}
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
	
	function editBudgetItem($pro){
		global $mysql;
		$setValue = "";
		foreach ($pro as $key => $val) {
			if(is_numeric ($key)) continue;
			if ($key == "itemAmount" || $key == "remark" || $key == "mainMaterialPrice") {
				$setValue .= " `".$key."` = '".$val."',";
			}
		}
		$setValue = substr($setValue, 0, -1);
		$condition = "`budgetItemId` = '".$pro['budgetItemId']."'";
		$mysql->DBUpdateSomeCols("`budget_item`", $condition, $setValue);
		return array('status'=>'successful', 'errMsg' => '');
	}
?>