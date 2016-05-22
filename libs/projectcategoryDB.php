<?php
	include_once "conn.php";

	function getProjectCategoryItems (){
		global $mysql;
		$sql = "select *, YEAR(`projectTime`) as projectYear , MONTH(`projectTime`) as projectMonth from `project` where `isDeleted` = 'false' and `isFrozen` = 0 ";
		$projects = $mysql->DBGetAsMap($sql);
		for ($i=0; $i < count($projects); $i++) {
			$projects[$i]["serialNumber"] = $i + 1;
			$businessId = $projects[$i]["businessId"];
			$projectId = $projects[$i]["projectId"];
			if ($businessId) {
				$customer = $mysql->DBGetAsMap("select customer from business where id = '?' and `isDeleted` = 'false' and `isFrozen` = 'false' and `isTransfered` = 'false' and `isDead` = 'false' ", $businessId);
				if (count($customer) > 0) {
					$projects[$i]["customer"] = $customer[0]["customer"];
				}
				else {
					$projects[$i]["customer"] = '';
				}
			}
			else {
				$projects[$i]["customer"] = '';
			}
			$proCheck = $mysql->DBGetAsMap("select tilerProCheck, woodProCheck from plan where projectId = '?' and `isDeleted` = 'false' ", $projectId);
			if (count($proCheck) > 0) {
				$projects[$i]["tilerProCheck"] = $proCheck[0]["tilerProCheck"];
				$projects[$i]["woodProCheck"] = $proCheck[0]["woodProCheck"];
			}
			else {
				$projects[$i]["tilerProCheck"] = "";
				$projects[$i]["woodProCheck"] = "";
			}
			$projectProgress = $mysql->DBGetAsMap("select `progress` from `progress` where `projectId` = '?' and `isDeleted` = 'false' ORDER BY createTime DESC", $projectId);
			if (count($projectProgress) > 0) {
				$projects[$i]["projectProgress"] = $projectProgress[0]["progress"];
			}
			else {
				$projects[$i]["projectProgress"] = '';
			}
		}
		return $projects;
	}
?>
