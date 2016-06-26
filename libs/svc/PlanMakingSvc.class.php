<?php

Class PlanMakingSvc extends BaseSvc{

	public static $map = array(
		'c1'=>'物业办理开工手续',
		'c2'=>'闭水试验房、成品保护、验房、成品保护',
		'c3'=>'厨房橱柜、电器',
		'c4'=>'卫生间卫浴、洁具',
		'c5'=>'空调及供热系统',
		'c6'=>'原墙体改动',
		'c7'=>'新建墙体',
		'c8'=>'黄强绿地及3D放样、放线',
		'c9'=>'地砖及石材预定',
		'c10'=>'开槽、布线、给排水改造',
		'c11'=>'试压、内部验收',
		'c12'=>'客户验收、拍照留底',
		'c13'=>'客厅、卧室家具',
		'c14'=>'水平线复准、材料进场',
		'c15'=>'修补线槽、做防水',
		'c16'=>'闭水试验、厨房贴砖',
		'c17'=>'墙、地面砖铺贴、内部验收',
		'c18'=>'客户验收、成品保护',
		'c19'=>'水平线复准、材料进场',
		'c20'=>'吊顶、木制品制作',
		'c21'=>'成品门、门窗套预定',
		'c22'=>'木线条预订、装饰面板定色封底',
		'c23'=>'客户验收、成品保护',
		'c24'=>'窗帘、强制、灯具、木地板',
		'c25'=>'吊顶面接缝、防锈处理',
		'c26'=>'成品门、门窗套安装',
		'c27'=>'厨房设备、卫生洁具、灯具安装',
		'c28'=>'木地板、踢脚线安装',
		'c29'=>'油漆修补',
		'c30'=>'墙纸铺贴及成品安装',
		'c31'=>'家政保洁',
		'c32'=>'竣工验收、保修单签单'
	);

	public static $pmap = array(
		'c1'=>'开工准备工程',
		'c2'=>'开工准备工程',
		'c3'=>'硬件预定',
		'c4'=>'硬件预定',
		'c5'=>'硬件预定',
		'c6'=>'土建工程',
		'c7'=>'土建工程',
		'c8'=>'形象保护',
		'c9'=>'石材预定',
		'c10'=>'水电工程',
		'c11'=>'水电工程',
		'c12'=>'水电工程',
		'c13'=>'家具预定',
		'c14'=>'泥工工程',
		'c15'=>'泥工工程',
		'c16'=>'泥工工程',
		'c17'=>'泥工工程',
		'c18'=>'泥工工程',
		'c19'=>'木工工程',
		'c20'=>'木工工程',
		'c21'=>'木工工程',
		'c22'=>'木工工程',
		'c23'=>'木工工程',
		'c24'=>'软装预定',
		'c25'=>'油漆工程',
		'c26'=>'成品安装工程',
		'c27'=>'成品安装工程',
		'c28'=>'成品安装工程',
		'c29'=>'油漆修补',
		'c30'=>'墙纸铺贴及成品安装',
		'c31'=>'家政保洁',
		'c32'=>'竣工验收、保修单签单'
	);

	public static $MainmaterialMap = array(
			'c3' => '厨房橱柜、电器',
			'c4'=>'卫生间卫浴、洁具',
			'c5'=>'空调及供热系统',
			'c9'=>'地砖及石材预定',
			'c13'=>'客厅、卧室家具',
			'c21'=>'成品门、门窗套预定',
			'c24'=>'窗帘、墙纸、灯具、木地板'
			);

	public function msgPreNotice(){
		global $TableMapping;
		global $mysql;
		$res = $mysql->DBGetAsOneArray('select paramValue from system where id = 9 or id = 10');
		$msg = $res[0];
		$days = $res[1];
		if($days == null || $days == "")
			return;
		$days = explode(',', $days);
		$startDays = array();
		foreach ($days as $value) {
			array_push($startDays, date('Y-m-d', strtotime("+$value day")));
		}
		$startDays = "'".join("','",$startDays)."'";
		
		//获取所有需要提醒的项目,按主材分
		$res = array();
		$r1 = $this->noticeOrder('c3',$startDays);
		$r2 = $this->noticeOrder('c4',$startDays);
		$r3 = $this->noticeOrder('c5',$startDays);
		$r4 = $this->noticeOrder('c9',$startDays);
		$r5 = $this->noticeOrder('c13',$startDays);
		$r6 = $this->noticeOrder('c21',$startDays);
		$r7 = $this->noticeOrder('c24',$startDays);
		$res = array_merge($r1,$r2,$r3,$r4,$r5,$r6,$r7);

		//所有需要发送短信的人
		$recievers = array();
		foreach ($res as $key => $value) {
			array_push($recievers, $value['salesman']);
			array_push($recievers, $value['designer']);
		}
		$recievers = array_unique($recievers);
		//查询这些人手机号
		$recievers = $mysql->DBGetAsMap("select name,mail,phone from user where name in ('".join("','",$recievers)."');");
		$users = array();
		//变成key为user name的map
		foreach ($recievers as $key => &$value) {
			$users[$value['name']] = array('phone'=>$value['phone'],'mail'=>$value['mail']);
		}
		print_r($res);
		echo "<br />\n";
		foreach ($res as $key => $item) {
			$this->sendMsg($item,$msg,$users);
		}
		return array('success'=>true);
	}

	//发短信,邮件
	private function sendMsg($item,$text,$users){
		//您好,{项目}还有{天}就要开始了,请提前订购{主材}!
		$text = str_replace('{项目}',$item['projectName'],$text);
		$text = str_replace('{几}',$item['daysleft'],$text);
		$text = str_replace('{主材}',PlanMakingSvc::$MainmaterialMap[$item['column']],$text);
		$salesman = $item['salesman'];
		$designer = $item['designer'];
		echo "$text<br />\n";
		if(isset($users[$salesman]) && strlen($users[$salesman]['phone']) == 11 ){ // 11位有效手机号
			$phoneNumber = $users[$salesman]['phone'];
			echo "send $text to $phoneNumber<br /> \n";
		}
		if(isset($users[$designer]) && strlen($users[$designer]['phone']) == 11 ){ // 11位有效手机号
			$phoneNumber = $users[$designer]['phone'];
			echo "send $text to $phoneNumber<br /> \n";
		}
		if(isset($users[$salesman]) && contains($users[$salesman]['mail'],'@')){ // 有效邮箱
			$mail = $users[$salesman]['mail'];
			echo "send email $text to $mail<br /> \n";
		}
		if(isset($users[$designer]) && contains($users[$designer]['mail'],'@')){ // 有效邮箱
			$mail = $users[$designer]['mail'];
			echo "send email $text to $mail<br /> \n";
		}
	}
	//获取需要提醒的主材对应项目
	private function noticeOrder($column,$startDays){
		global $TableMapping;
		global $mysql;
		//查询未结束的项目,并且主材没有在主材订购表中出现(没有订购主材的),并且小项开始时间还差n天的(n为系统配置的提醒时间),关联业务员,设计师
		$sql = "select r.*,p.salesman,p.designer from (
					select  TO_DAYS(SUBSTR($column,1,10))  - TO_DAYS(NOW()) as daysleft ,projectId,projectAddress as projectName from plan_making 
					where endTime > now() and isDeleted = 'false' and SUBSTR($column,1,10) in ($startDays) and projectId not in (
						select projectId from mainmaterial where materialType = '$column' and isDeleted = 'false')
					) r left join project p on p.projectId = r.projectId;";
		$d = $mysql->DBGetAsMap($sql);
		foreach ($d as $key => &$value) {
			$value['column'] = $column;
		}
		return $d;
	}

	public function getTimeSpanByProfessionType($q){
		/*	泥工	 0001
		木工 0002
		油漆工 0003
		水电工 0004
		力工	0005
		其他	0009*/
		$select = "select ";
		switch ($q['professionType']){
			case '0001':$select .= 'c7,c15,c17';break;
			case '0002':$select .= 'c20';break;
			case '0003':$select .= 'c8,c22,c25,c29';break;
			case '0004':$select .= 'c10';break;
			case '0005':$select .= 'c6';break;
			default:throw new Exception('找不到工种:'.$q['professionType']);
		}
		$select .= " from plan_making where isDeleted = 'false' and endTime > now() ";
		global $TableMapping;
		global $mysql;
		$res = $mysql->DBGetAsOneArray($select);
		if(count($res) == 0)
			throw new Exception("没有正在进行的项目!", 1);
		$start='2999-12-31';
		$end='1970-01-01';
		foreach ($res as $key => $value) {
			if($value != null && contains($value,'~')){
				$tmp = explode("~",$value);
				if($start>$tmp[0])
					$start = $tmp[0];
				if($end<$tmp[1])
					$end = $tmp[1];
			}
		}
		return array('startTime'=>$start,'endTime'=>$end);
	}

	public function getLaborPlanByProfessionType($q){
		/*	泥工	 0001
		木工 0002
		油漆工 0003
		水电工 0004
		力工	0005
		其他	0009*/
		$select = "select projectId,projectAddress as projectName,";
		switch ($q['professionType']){
			case '0001':$select .= 'c7,c15,c17';break;
			case '0002':$select .= 'c20';break;
			case '0003':$select .= 'c8,c22,c25,c29';break;
			case '0004':$select .= 'c10';break;
			case '0005':$select .= 'c6';break;
			default:throw new Exception('找不到工种:'.$q['professionType']);
		}
		$select .= " from plan_making where isDeleted = 'false' and endTime > now() ";
		global $TableMapping;
		global $mysql;
		$res = $mysql->DBGetAsMap($select);
		if(count($res) == 0)
			throw new Exception("没有正在进行的项目!", 1);
		foreach ($res as &$item) {
			$item['period'] = array();
			foreach ($item as $key => $value) {
				if($key == 'projectId' || $key =='period' || $key=='projectName')
					continue;
				if($value != null && contains($value,'~')){
					$time = explode("~",$value);
					$startTime = $time[0];
					$endTime = $time[1];
					$c = $key == 'c17' ? 1 : 0;
					array_push($item['period'], array('s'=>$startTime,'e'=>$endTime,'c'=> $c));
				}
				unset($item[$key]);
			}
		}
		return $res;
	}

	public function get($q){
		$q['_fields'] = 'id,projectId,projectAddress,startTime,endTime,custName,isDeleted,updateTime,createTime';
		return parent::get($q);
	}

	//get,横标转纵表
	public function getItems($q,$isDetailed = false){
		$res = null;
		if(isset($q['planId']))
			$res = parent::get(array('id'=>$q['planId']));
		if(isset($q['projectId']))
			$res = parent::get(array('projectId'=>$q['projectId']));
		if($res['total'] == 0)
			throw new Exception("没有找到项目计划!");
		$plan = $res['data'][0];
		$res = array();
		$count = 0;
		foreach (PlanMakingSvc::$map as $key => $value) {
			$startTime = '';
			$endTime = '';
			if(isset($plan[$key]) && contains($plan[$key],'~')){
				$time = explode("~",$plan[$key]);
				$startTime = $time[0];
				$endTime = $time[1];
			}
			$item = array(
				'serialNumber'=>++$count,
				'parentItemName'=>PlanMakingSvc::$pmap[$key],
				'itemName'=>$value,
				'startTime'=>$startTime,
				'endTime'=>$endTime,
				'id'=>$plan['projectId'].'-'.$key);
			if($isDetailed){
				//c3, c4, c5, c9, c13, c21, c24不可编辑
				$item['columnName'] = $key;
				$item['isEditable'] = in_array($key, array('c3', 'c4','c5','c9','c13','c21','c24')) ? false:true;
			}
			array_push($res, $item);
		}
		return $res;
	}

	public function updateItem($q){
		$temp = explode("-",$q['id']);   // projectId-columnName
		$update = array('@'.$temp[1]=>$q['@time'],'projectId'=>$temp[0]);
		return parent::update($update);
	}

	public function add($q){
		$q['@id'] = $this->getUUID();
		return parent::add($q);
	}
}
?>