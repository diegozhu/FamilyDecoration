<?php
	//获取项目下一个ItemCode编码
	function _getNextItemCode($budgetId){
		global $mysql;
		$sql = "SELECT DISTINCT LEFT( itemCode, 1 ) as code , itemName FROM  `budget_item` WHERE `isDeleted` = 'false' and `budgetId` = '?' and LEFT( itemCode, 1 ) NOT IN ('N','O','P','Q','R','S') order by code asc";
		$existItemCodes = $mysql->DBGetAsOneArray($sql,$budgetId);
		$ItemCodeList = array("A","B","C","D","E","F","G","H","I","J","K","L","M");
		foreach($ItemCodeList as $char){
			if(!in_array($char,$existItemCodes)){
				return $char;
			}
		}
		throw new Exception("超过itemCode最大值M");
	}
	//获取大项下一个小项的itemCode编码
	function _getNextBasicCode($budgetId,$ItemCode){
		global $mysql;
		$sql = "SELECT SUBSTRING(itemCode,3) as code  FROM `budget_item` where `isDeleted` = 'false' and `budgetId` = '?' and `itemCode` like '%?%' and SUBSTRING(itemCode,3) != \"\" order by code asc ";
		$existItemCodes = $mysql->DBGetAsOneArray($sql,$budgetId,$ItemCode);
		$count = 1;
		while(in_array($count."",$existItemCodes)){
			$count ++;
		}
		return $ItemCode."-".$count;
	}
	//添加大项
	function addBigItem($post){
		$itemCode = _getNextItemCode($post["budgetId"]);
		global $mysql;
		$fields = array('itemName','budgetId','itemUnit','itemAmount','mainMaterialPrice','auxiliaryMaterialPrice','manpowerPrice','machineryPrice','lossPercent','remark','basicItemId','basicSubItemId');
		$obj = array('itemCode'=>$itemCode,'budgetItemId' => "budget-item-".date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT));
		foreach($fields as $field){
			if(isset($post[$field]))
				$obj[$field] = $post[$field];
		}
		$mysql->DBInsertAsArray("`budget_item`",$obj);
		return array('status'=>'successful', 'errMsg' => '','itemCode'=>$itemCode);
	}
	//添加小项
	function addItem($post){
		$itemCode = _getNextBasicCode($post["budgetId"],$post["itemCode"]);
		global $mysql;
		$fields = array('itemName','budgetId','itemUnit','itemAmount','mainMaterialPrice','auxiliaryMaterialPrice','manpowerPrice','machineryPrice','lossPercent','remark','basicItemId','basicSubItemId');
		$obj = array('itemCode'=>$itemCode,'budgetItemId' => "budget-item-".date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT));
		foreach($fields as $field){
			if(isset($post[$field]))
				$obj[$field] = $post[$field];
		}
		$mysql->DBInsertAsArray("`budget_item`",$obj);
		return array('status'=>'successful', 'errMsg' => '','itemCode'=>$itemCode);
	}
	//修改项
	function editItem($post){
		global $mysql;
		$fields = array('itemName','budgetId','itemUnit','itemAmount','mainMaterialPrice','auxiliaryMaterialPrice','manpowerPrice','machineryPrice','lossPercent','remark','basicItemId','basicSubItemId');
		$obj = array('budgetItemId'=>$post["budgetItemId"]);
		foreach($fields as $field){
			if(isset($post[$field]))
				$obj[$field] = $post[$field];
		}
		$mysql->DBInsertAsArray("`budget_item`",$obj);
		return array('status'=>'successful', 'errMsg' => '');
	}
	
	//删除项，不区分大小项
	function delItem($budgetId,$itemId,$itemCode){
		global $mysql;
		if(strlen($itemCode) == 1){
			//删除大项		
			$mysql->DBUpdate("budget_item",array('isDeleted'=>true),"`budgetId` = '?' and `itemCode` like '%?%' ",array($budgetId,$itemCode));			
		}else{
			//删除小项
			$mysql->DBUpdate('budget_item',array('isDeleted'=>true),"`budgetItemId` = '?' ",array($ItemId));
			$sql = "SELECT budgetItemId FROM  `budget_item` WHERE `isDeleted` = 'false' and `budgetId` = '?' and SUBSTRING(itemCode,3) > ? ";
			$budgetItemId = $mysql->DBGetAsOneArray($sql,$budgetId,intval(substr($itemCode,3)));
			$mysql->DBUpdate('budget_item',array('isDeleted'=>true),"`budgetItemId` = '?' ",array($ItemId));
		}
		return array('status'=>'successful', 'errMsg' => '');
	}

	//添加预算
	function addBudget($post){
		global $mysql;
		$projectId = $post["projectId"];
		$budgets = $mysql->DBGetAsMap("SELECT b.*,p.projectName FROM `budget` b left join `project` p on b.budgetId=p.budgetId where b.`isDeleted` = 'false' and p.`projectId` = '?' ",$projectId);
		if(count($budgets) > 0) 
			throw new Exception("项目 : '$projectId' 已经存在预算!");
		$obj = array(
			"budgetId" => "budget-".date("YmdHis").str_pad(rand(0, 9999), 4, rand(0, 9), STR_PAD_LEFT),
			"custName"=>$post["custName"],
			"areaSize"=>$post["areaSize"],
			"totalFee"=>$post["totalFee"],
			"comments"=>$post["comments"],
			"isDeleted"=>false
		);
		$mysql->DBUpdate("project",array('budgetId'=>$obj["budgetId"]),"`projectId` = '?' and `isDeleted`='false' ",array($projectId));
		$mysql->DBInsertAsArray("`budget`",$obj);
		return array('status'=>'successful', 'errMsg' => '', "budgetId" => $obj["budgetId"]);
	}

	//删除预算
	function delBudget ($budgetId){
		global $mysql;
		$mysql->DBUpdate('project',array('budgetId'=>''),"`budgetId` = '?' ",array($budgetId));
		$mysql->DBUpdate('budget',array('isDeleted'=>true),"`budgetId` = '?' ",array($budgetId));
		$mysql->DBUpdate('budget_item',array('isDeleted'=>true),"`budgetId` = '?' ",array($budgetId));
		return array('status'=>'successful', 'errMsg' => '');
	}

	//供本地备份脚本使用
	function getBudgetIds (){
		global $mysql;
		$arr = $mysql->DBGetAsMap("SELECT b.budgetId,p.projectName FROM `budget` b left join `project` p on b.budgetId=p.budgetId where b.`isDeleted` = 'false' ");
		foreach($arr as $key => $val) {
			echo $val['budgetId'].">".str2GBK($val['projectName'])."\n";
		}
	}
	function getBudgets (){
		global $mysql;
		return $mysql->DBGetAsMap("SELECT b.*,p.projectName FROM `budget` b left join `project` p on b.budgetId=p.budgetId where b.`isDeleted` = 'false' ");
	}
	
	function getBudgetsByBudgetId ($budgetId){
		global $mysql;
		return $mysql->DBGetAsMap("SELECT b.*,p.projectName FROM `budget` b left join `project` p on b.budgetId=p.budgetId where b.`isDeleted` = 'false' and b.`budgetId` = '?' ",$budgetId);
	}
	
	function compareBudgetItem($arg1,$arg2){
		return strcasecmp($arg1["itemCode"],$arg2["itemCode"]);
	}
	//修改预算
	function editBudget (array $pro){
		global $mysql;
		$obj = array();
		$fields = array("custName","areaSize", "totalFee", "comments");
		foreach($fields as $field) {
			if (isset($pro[$field])) {
				$obj[$field] = $pro[$field];
			}
		}
		$mysql->DBUpdate('budget',$obj,"`budgetId` = '?'",array($pro['budgetId']));
		if(isset($pro['projectId']))
			$mysql->DBUpdate('project',array('budgetId'=>$pro['budgetId']),"`projectId` = '?'",array($pro['projectId']));
		return array('status'=>'successful', 'errMsg' => '');
	}
	

	
	function getBudgetItemsByBudgetId ($budgetId , $isGBK = false,$isNOPQRSAmount = true) {
		global $mysql;
		$res= array();
		$arr = $mysql->DBGetAsMap(" select * from `budget_item` where `budgetId` = '?' and `isDeleted` = 'false' ORDER BY LEFT( itemCode, 2 ) ASC , ( SUBSTRING( itemCode, 2 ) ) *1 DESC ",$budgetId);
		$count = 0;
		$smallCount = array(0,0,0,0);
		$directFee = 0;
		$isFirstSmallCount = true;
		$otherItems = array();
		$otherItems['N'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		$otherItems['O'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		$otherItems['P'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		$otherItems['Q'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		$otherItems['R'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
		$otherItems['S'] = array('budgetItemId'=>'NULLN','itemName'=>'','budgetId'=>'',
								'itemCode'=>'','itemUnit'=>'','itemAmount'=>'','mainMaterialPrice'=>'','auxiliaryMaterialPrice'=>'','manpowerPrice'=>'',
								'machineryPrice'=>'','mainMaterialTotalPrice'=>'','auxiliaryMaterialTotalPrice'=>'',
								'manpowerTotalPrice'=>'','machineryTotalPrice'=>'','lossPercent'=>'','remark'=>'');
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
?>