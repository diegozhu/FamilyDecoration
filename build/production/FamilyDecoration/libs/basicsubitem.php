<?php
	include_once "conn.php";

	/**
	 * [get all basic sub items]
	 * @return [type] [description]
	 */
	function getBasicSubItems ($parentId){
		try {
			global $mysql;
			$arr = $mysql->DBGetSomeRows("`basic_sub_item`", "*", "where `parentId` = '$parentId'");
			if ($arr) {
				// Url encode Chinese characters and then decode them, in order to avert garbled characters.
				foreach($arr as $key => $val) {
					$arr[$key]['subItemName'] = urlencode($val['subItemName']);
				}
				$arr = urldecode(json_encode($arr));
			}
			else {
				$arr = json_encode(array());
			}
			
			return $arr;
		}
		catch (Exception $e) {
			return json_encode(array('status' => 'failing', 'errMsg'=>$e->getMessage()));
		}
	}

	/**
	 * [add an item into basic_sub_item table]
	 * @param array $item [description]
	 */
	function addBasicSubItem (array $item){
		try {
			global $mysql;
			$mysql->DBInsert("`basic_sub_item`", "`subItemId`, `subItemName`, `subItemUnit`, `mainMaterialPrice`, `auxiliaryMaterialPrice`, `manpowerPrice`, `machineryPrice`, `lossPercent`, `parentId`, `cost`",
			 	"'".$item['subItemId']."', '".$item['subItemName']."', '".$item["subItemUnit"]."', ".$item["mainMaterialPrice"].", ".$item["auxiliaryMaterialPrice"].", ".$item["manpowerPrice"].", ".$item["machineryPrice"].", ".$item["lossPercent"].", '".$item["parentId"]."', ".$item["cost"]);
			return json_encode(array('status'=>'successful', 'errMsg' => ''));
		}
		catch (Exception $e) {
			return json_encode(array('status' => 'failing', 'errMsg'=>$e->getMessage()));
		}
	}

	/**
	 * [deleteBasicSubItem description]
	 * @param  [string] $itemId [description]
	 * @return [type]         [description]
	 */
	function deleteBasicSubItem ($itemId){
		try {
			global $mysql;
			$condition = "`subItemId` = '".$itemId."'";
			$mysql->DBDelete("`basic_sub_item`", $condition);
			return json_encode(array('status'=>'successful', 'errMsg' => ''));
		}
		catch (Exception $e) {
			return json_encode(array('status' => 'failing', 'errMsg'=>$e->getMessage()));
		}
	}

	function deleteBasicSubItemByParentId ($parentId){
		try {
			global $mysql;
			$condition = "`parentId` = '".$parentId."'";
			$mysql->DBDelete("`basic_sub_item`", $condition);
			return json_encode(array('status'=>'successful', 'errMsg' => ''));
		}
		catch (Exception $e) {
			return json_encode(array('status' => 'failing', 'errMsg'=>$e->getMessage()));
		}
	}

	/**
	 * [editBasicSubItem description]
	 * @param  [array] $item [description]
	 * @return [type]       [description]
	 */
	function editBasicSubItem (array $item){
		try {
			global $mysql;
			$setValue = "";
			foreach ($item as $key => $val) {
				if ($key == "subItemId" || is_numeric ($key)) {
					continue;
				}
				else {
					$setValue .= " `".$key."` = '".$val."',";
				}
			}
			$setValue = substr($setValue, 0, -1);
			$condition = "`subItemId` = '".$item['subItemId']."'";
			$mysql->DBUpdateSomeCols("`basic_sub_item`", $condition, $setValue);
			return json_encode(array('status'=>'successful', 'errMsg' => ''));
		}
		catch (Exception $e) {
			return json_encode(array('status' => 'failing', 'errMsg'=>$e->getMessage()));
		}
	}
?>