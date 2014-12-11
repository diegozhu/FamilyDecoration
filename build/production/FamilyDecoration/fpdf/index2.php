<?php
global $CellWidth,$FirstCellWidth,$titleLeft,$GfontSize; 
$FirstCellWidth  = array(11,41,9,10,   27,   26,   27,   23,10,95);
$CellWidth 		 = array(11,41,9,10,11,16,13,13,12,15,10,13,10,95);
$titleLeft       = array(12,32,63,72,236);
include_once "../libs/conn.php";
include_once '../libs/budgetDB.php'; 
include_once 'chinese.php';
include_once 'pdf_chinese.php';
global $custName;
global $projectName;
$GfontSize = 10;
$budget = getBudgetsByBudgetId($_REQUEST["budgetId"]);
$custName =  str2GBK(urldecode($budget[0]["custName"]));
$projectName = str2GBK(urldecode($budget[0]["projectName"]));
$projectComments = str2GBK(urldecode($budget[0]["comments"]));
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "download";
//不能放在for循环中，需要单独处理的项
$otherInfo = array('N','O','P','Q','R','S');
$otherInfoArray = array();
$budgetItems = getBudgetItemsByBudgetIdGBK($_REQUEST["budgetId"]);
$pdf=new PDF('L','mm', 'A4'); //创建新的FPDF对象 
$pdf->AddGBFont(); //设置中文字体 
$pdf->Open(); //开始创建PDF 
$pdf->AddPage(); //增加一页 
$pdf->SetFont('GB','',$GfontSize); //设置字体样式 
$pdf->AliasNbPages("__totalPage__");

$lineHeight = 6;
//ksort($budgetItems);
$totalFee = array("ATotal"=>0,"BTotal"=>0,"CTotal"=>0,"DTotal"=>0,"A"=>0,"B"=>0,"C"=>0,"D"=>0);
$isFirstTime = true;
foreach($budgetItems as $bItem){
	$itemName = $bItem["itemName"];
	$amount = $bItem["itemAmount"];
	$itemCode = $bItem["itemCode"];
	//最后需要特殊处理的几项不参与循环，放在后面单独处理
	if(in_array($itemCode,$otherInfo)){
		$otherInfoArray[$itemCode] = $bItem;
		continue;
	}
	//可能出线为空的空白数据，不参与计算，此处防止多出线为空的小计
	if("NULL" === $itemName || "" === $itemName || "NULL" === $itemCode || "" === $itemCode ){
		continue;
	}
	//通过单位为空判断是否是大项
	$isBigSubject = ("NULL" === $bItem["itemUnit"]);
	$mainMaterialPrice = $amount * ( $bItem["mainMaterialPrice"] + $bItem["lossPercent"] );
	$auxiliaryMaterialPrice = $amount*$bItem["auxiliaryMaterialPrice"];
	$manpowerPrice = $amount*$bItem["manpowerPrice"];
	$machineryPrice = $amount*$bItem["machineryPrice"];
	//一个大项
	if($isBigSubject){
		if($isFirstTime){
			$isFirstTime = false; // 第一次不输出小计
		}else{
			//输出小计
			$data = array('','小计','','','',$totalFee["A"],'',$totalFee["B"],'',$totalFee["C"],'',$totalFee["D"],'','');
			$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
			$pdf->writeCellLine($CellWidth,$lineHeight,"",1,0,'C');  // 空一行
			//总计累加
			$totalFee["ATotal"] += $totalFee["A"];
			$totalFee["BTotal"] += $totalFee["B"];
			$totalFee["CTotal"] += $totalFee["C"];
			$totalFee["DTotal"] += $totalFee["D"];
			//小计清空
			$totalFee["A"] = 0;
			$totalFee["B"] = 0;
			$totalFee["C"] = 0;
			$totalFee["D"] = 0;
		}
	}
	//累加计算小计
	$totalFee["A"] += $mainMaterialPrice;
	$totalFee["B"] += $auxiliaryMaterialPrice;
	$totalFee["C"] += $manpowerPrice;
	$totalFee["D"] += $machineryPrice;

	//正常输出大、小项数据，处理备注
	$remark = $bItem["remark"];
	$remark = str_replace(PHP_EOL,'',$remark);
	//echo $remark;
	//die();
	
	//$remark = explode('>>><<<',$remark);
	$remark = str_split($remark,65);
	$remarkLineCount = count($remark);
	$lineToOutput = ceil($remarkLineCount/2);
	$i = 0;
	//输出备注
	while($i < $remarkLineCount){
		$LineRemark = $remark[$i];
		if($isBigSubject){
			$data = array($itemCode,$itemName,'','','','','','','','','','','',$LineRemark);
		}else{
			$data = array($itemCode,$itemName,$bItem["itemUnit"],
				$amount,$bItem["mainMaterialPrice"],$mainMaterialPrice,
				$bItem["auxiliaryMaterialPrice"],$auxiliaryMaterialPrice,$bItem["manpowerPrice"],
				$manpowerPrice,$bItem["machineryPrice"],$machineryPrice,
				$bItem["lossPercent"],$LineRemark);
		}
		if(($i+1) != $lineToOutput)
			$data = array('','','','','','','','','','','','','',$remark[$i]);
		$fontSizes = array($GfontSize,$GfontSize,$GfontSize,$GfontSize,$GfontSize,
							$GfontSize,$GfontSize,$GfontSize,$GfontSize,$GfontSize,
							$GfontSize,$GfontSize,$GfontSize,8);
		$pdf->writeCellLine($CellWidth,$lineHeight,$data,$i == 0 ? 'LRT' : 'RL',0,'L',14,$fontSizes);
		$i++;
	}
}

//输出小计
$data = array('','小计','','','',$totalFee["A"],'',$totalFee["B"],'',$totalFee["C"],'',$totalFee["D"],'','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
$pdf->writeCellLine($CellWidth,$lineHeight,"",1,0,'C');  // 空一行
$totalFee["ATotal"] += $totalFee["A"];
$totalFee["BTotal"] += $totalFee["B"];
$totalFee["CTotal"] += $totalFee["C"];
$totalFee["DTotal"] += $totalFee["D"];
//输出特殊处理项
$projectTotalFee = 0;
//工程直接费
$projectFee = $totalFee["ATotal"]+$totalFee["BTotal"]+$totalFee["CTotal"]+$totalFee["DTotal"];
$projectTotalFee += $projectFee;
$data = array('N','工程直接费','元','','',$projectFee,'','','','','','','','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//设计费  
$amount = $otherInfoArray["O"]["itemAmount"];
$projectTotalFee += $projectFee*$amount;
$data = array('O','设计费3%','元','','',$projectFee*$amount,'','','','','','','','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//效果图
$amount = $otherInfoArray["P"]["itemAmount"];
$price = $otherInfoArray["P"]["mainMaterialPrice"];
$projectTotalFee += $price*$amount;
$data = array('P','效果图','元',$amount > 1 ? $amount : '',$price,$price*$amount,'','','','','','','','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//管理费  
$amount = $otherInfoArray["Q"]["itemAmount"];
$projectTotalFee += $projectFee*$amount;
$data = array('Q','5%管理费','元','','',$projectFee*$amount,'','','','','','','','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//税金 
$amount = $otherInfoArray["R"]["itemAmount"];
$data = array('R','0%税金','元','','',$projectFee*$amount,'','','','','','','','');
$projectTotalFee += $projectFee*$amount;
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//工程总造价
$data = array('S','工程总造价','元','','',$projectTotalFee,'','','','','','','','');
$pdf->writeCellLine($CellWidth,$lineHeight,$data,1,0,'C');
//输出其他
$otherInfo = explode('>>><<<',$projectComments);
$arrayCount = count($otherInfo);
while($arrayCount<3){
	array_push($otherInfo,'');
	$arrayCount++;
}
$titleHeightPosition = 7;
$pdf->SetFont('GB','',9); //设置字体样式 
$pdf->Cell(11,10,"");
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[0],0);
$pdf->SetFont('GB','',10); //设置字体样式 
$pdf->Cell(21,$titleHeightPosition,'客户签名：',0);
$pdf->SetFont('GB','',9); //设置字体样式 
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[1]);
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[2]);
$pdf->SetFont('GB','',10); //设置字体样式 
$pdf->Cell(21,$titleHeightPosition,'时间：',0,0,'R');
$pdf->Cell(16,$titleHeightPosition,'年',0,0,'R');
$pdf->Cell(11,$titleHeightPosition,'月',0,0,'R');
$pdf->Cell(11,$titleHeightPosition,'日',0,0,'R');
$pdf->SetFont('GB','',9); //设置字体样式 
$i = 3;
while($i < $arrayCount){
	$pdf->Ln();
	$pdf->Cell(11,21,"");
	$pdf->Cell(200,$titleHeightPosition,$otherInfo[$i]);
	$i++;
}
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,'    注： 1、 本报价单为合同附件， 具有同等法律效力， 业主签字后生效。');
$pdf->Ln();
$pdf->Output($projectName.".pdf", $action == "view" ? "I" : "D" );

$pdf->writeCellLine($CellWidth,$lineHeight,$data,0,0,'R');

?>  