<?php
global $CellWidth,$FirstCellWidth,$titleLeft,$GfontSize,$lineHeight,$UserBrowserClient; 
if(strpos($_SERVER["HTTP_USER_AGENT"],"Safari") && !strpos($_SERVER["HTTP_USER_AGENT"],"Chrome") ){
	$UserBrowserClient = 'safari';
}else if(strpos($_SERVER["HTTP_USER_AGENT"],"Mac") && strpos($_SERVER["HTTP_USER_AGENT"],"Chrome")){
	$UserBrowserClient = 'chrome_mac';
}else{
	$UserBrowserClient = 'default';
}

$FirstCellWidth=array(11,41,8,11,   27,   26,   27,   23,10,82);
//$FirstCellWidth	= array(11,41,9,14,   28,   28,   28,   28,10,95);
$CellWidth 	= array(11,41,8,11,11,16,13,13,12,15,10,13,10,82);
//$CellWidth 		= array(11,41,9,14,12,16,12,16,12,16,12,16,10,95);
$lineHeight 	= 6;
$titleLeft      = array(17,39,67.5,77,236);
//$titleLeft      = array(4,25,54.5,65,236);
$GfontSize		= 10;

include_once "../libs/conn.php";
include_once '../libs/budgetDB.php'; 
include_once 'chinese.php';
include_once 'pdf_chinese.php';
global $custName;
global $projectName;

$budget = getBudgetsByBudgetId($_REQUEST["budgetId"]);
if(count($budget) == 0){
	throw new Exception("no budget with id ".$_REQUEST["budgetId"]);
}
$custName =  str2GBK(urldecode($budget[0]["custName"]));
$projectName = str2GBK(urldecode($budget[0]["projectName"]));
$projectComments = str2GBK(urldecode($budget[0]["comments"]));
$action = isset($_REQUEST["action"]) ? $_REQUEST["action"] : "download";
$budgetItems = getBudgetItemsByBudgetId($_REQUEST["budgetId"],true,false);
$pdf=new PDF('L','mm', 'A4'); //创建新的FPDF对象 
$pdf->AddGBFont(); //设置中文字体 
$pdf->Open(); //开始创建PDF 
$pdf->AddPage(); //增加一页 
$pdf->SetFont("GB",'',$GfontSize); //设置字体样式 
$pdf->AliasNbPages("__totalPage__");
//echo $custName.":".$projectName;
foreach($budgetItems as $bItem){
	//保留小数点后两位,不足补0
	foreach($bItem as $key=> $val){
		if(is_numeric($val)){
			//echo $key.':'.$val.':'.round($val,2).'<br />';
			$tmp = round($val,2)."";
			$index = strpos($tmp, ".");
			if(!$index){
				$tmp .= ".00";
			}else if($index + 2== strlen($tmp)){
				$tmp .= "0";
			}else{
				// do nothing;
			}
			$bItem[$key] = $tmp;
		}
	}
	
	$itemName = $bItem["itemName"];
	$amount = $bItem["itemAmount"];
	$itemCode = $bItem["itemCode"];
	
	$remark = $bItem["remark"];
	$data = array($itemCode,$itemName,$bItem["itemUnit"],$amount,
				$bItem["mainMaterialPrice"],$bItem["mainMaterialTotalPrice"],
				$bItem["auxiliaryMaterialPrice"],$bItem["auxiliaryMaterialTotalPrice"],$bItem["manpowerPrice"],
				$bItem["manpowerTotalPrice"],$bItem["machineryPrice"],$bItem["machineryTotalPrice"],
				$bItem["lossPercent"],$remark);
	$fontSizes = array($GfontSize,$GfontSize,$GfontSize,$GfontSize,$GfontSize,
							$GfontSize,$GfontSize,$GfontSize,$GfontSize,$GfontSize,
							$GfontSize,$GfontSize,$GfontSize,8);
	$borders = array('LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LB','LBR');
	$align = array('C','L','C','C','C','C','C','C','C','C','C','C','C','L');
	$pdf->writeCellLine($CellWidth,$data,$borders,0,$align,14,$fontSizes);
}


//输出其他
$otherInfo = preg_split('/\n|\r\n|\r|\n\r/',$projectComments);
$arrayCount = count($otherInfo);
//补齐三行，防止备注不足三行，后面填表格有问题
while($arrayCount<3){
	array_push($otherInfo,'');
	$arrayCount++;
}
$titleHeightPosition = 7;
$pdf->SetFont("GB",'',9); //设置字体样式 
$pdf->Cell(11,10,"");
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[0],0);
$pdf->SetFont("GB",'',10); //设置字体样式 
$pdf->Cell(21,$titleHeightPosition,'客户签名：',0);
$pdf->SetFont("GB",'',9); //设置字体样式 
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[1]);
$pdf->Ln();
$pdf->Cell(11,21,"");
$pdf->Cell(200,$titleHeightPosition,$otherInfo[2]);
$pdf->SetFont("GB",'',10); //设置字体样式 
$pdf->Cell(21,$titleHeightPosition,'时间：',0,0,'R');
$pdf->Cell(16,$titleHeightPosition,'年',0,0,'R');
$pdf->Cell(11,$titleHeightPosition,'月',0,0,'R');
$pdf->Cell(11,$titleHeightPosition,'日',0,0,'R');
$pdf->SetFont("GB",'',9); //设置字体样式 
$i = 3;
while($i < $arrayCount){
	$pdf->Ln();
	$pdf->Cell(11,21,"");
	$pdf->Cell(200,$titleHeightPosition,$otherInfo[$i]);
	$i++;
}
$pdf->Ln();
$pdf->Cell(11,21,"");
//$pdf->Cell(200,$titleHeightPosition,'    注： 1、 本报价单为合同附件， 具有同等法律效力， 业主签字后生效。');
$pdf->Ln();
$pdf->Output($projectName.".pdf", $action == "view" ? "I" : "D" );

$pdf->writeCellLine($CellWidth,$data,0,0,'R');

?>  