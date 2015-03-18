<?php
class PDF extends PDF_Chinese{
	
	function Header(){ //设置页眉 
	
		global $custName,$projectName,$CellWidth,$FirstCellWidth,$titleLeft,$GfontSize,$lineHeight; 
		
		$this->SetFont('GB','B',20); 
		$this->Image('../resources/img/logo.jpg',60,4,30,30); //增加一张图片，文件名为sight.jpg 
		$this->Text(95,30,'佳诚装饰室内装修装饰工程 预算单');
		$this->SetFont('GB','',$GfontSize); 
		$this->Ln(26); //换行 
		$this->Cell(40,10,"",0,0,'C');
		$this->Cell(30,10,"客户姓名 ：",0,0,'L');
		$this->Cell(30,10,$custName,0,0,'L');
		$this->Cell(60,10,"",0,0,'C');
		$this->Cell(30,10,"工程地址 ：",0,0,'L');
		$this->Cell(30,10,$projectName,0,0,'L');
		//$this->Line(10,50,280,50);
		$this->Ln(12); //换行 
		$titles = array('主材','辅材','人工','机械','损耗','');
		$CellHeight = 6;
		$borders = array('LT','LT','LT','LT','LT','LT','LT','LT','LT','LTR');
		$count = 0;
		$titleHeightPosition = 55;
		$this->Cell($FirstCellWidth[$count],$CellHeight,'',$borders[$count],0,'C');
		$this->Text($titleLeft[$count++],$titleHeightPosition,'编号');
		$this->Cell($FirstCellWidth[$count],$CellHeight,'',$borders[$count],0,'C');
		$this->Text($titleLeft[$count++],$titleHeightPosition,'项目名称');
		$this->Cell($FirstCellWidth[$count],$CellHeight,'',$borders[$count],0,'C');
		$this->Text($titleLeft[$count++],$titleHeightPosition,'单位');
		$this->Cell($FirstCellWidth[$count],$CellHeight,'',$borders[$count],0,'C');
		$this->Text($titleLeft[$count++],$titleHeightPosition,'数量');
		foreach($titles as $title){
			$this->Cell($FirstCellWidth[$count],$CellHeight,$title,$borders[$count++],0,'C');
		}
		$this->Text($titleLeft[4],$titleHeightPosition,'备注');
		$this->Ln();
		$titles 	= array('   ','   ','   ','   ','单价','总价','单价','总价','单价','总价','单价','总价','单价','');
		$borders 	= array( 'LRB', 'BR','BR', 'BR', 'BTR', 'BTR', 'BTR', 'BTR', 'BTR', 'BTR', 'BTR', 'BTR',  'BTR', 'BR');
		$CellHeight = 6;///array(15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15,15);

		$count = 0;
		foreach($titles as $title){
			$this->Cell($CellWidth[$count],$CellHeight,$title,$borders[$count++],0,'C');
		}
		
		$this->Ln();
	} 
	function Footer(){ //设置页脚  
		$this->SetY(-15); 
		global $GfontSize;
		$this->SetFont('GB','',$GfontSize); 
		$this->Cell(170,10,'第'.$this->PageNo().'页  共__totalPage__页',0,0,'R'); 
		//$this->Cell(220,10,date("Y-m-d"),0,0,'R'); 
	}
	// 输出一行表格
	function writeCellLine($widths,$txts,$borders,$positions,$aligns,$times=14,$fontSizes=10){
		global $lineHeight;
		$c = 0;
		$thisLineHeight = $lineHeight;
		//如果表格内容过大，则需要自动换行，因此，有时候不仅输出一行，
		//而是输出好几行，先遍历所有表格，判断需要输出多少行
		for( ; $c < $times ; $c ++){
			$thisLineFontSize = $fontSizes;
			if($fontSizes != null && is_array($fontSizes) && isset($fontSizes[$c])){
				$thisLineFontSize = $fontSizes[$c];
			}
			$this->SetFont('GB','',$thisLineFontSize);
			$txt = is_array($txts) ? $txts[$c] : $txts;
			$txt = ($txt === null || $txt === "NULL")? "" : $txt;
			
			$w = is_array($widths) ? $widths[$c] : $widths;
			$stringWidth = $this->GetStringWidth($txt);
			if($stringWidth > $w){
				// round 用于精确到小数点后两位，之后向上取整。
				$tmp = ceil(round($stringWidth/($w-$this->cMargin*2),2))*$lineHeight;
				$thisLineHeight = $thisLineHeight > $tmp ?  $thisLineHeight : $tmp;
				
			}
			
		}
		
		$c = 0;
		$lastCellHeight;
		//遍历所有表格，输出
		for( ; $c < $times ; $c ++){
			$thisLineFontSize = $fontSizes;
			if($fontSizes != null && is_array($fontSizes) && isset($fontSizes[$c])){
				$thisLineFontSize = $fontSizes[$c];
			}
			$this->SetFont('GB','',$thisLineFontSize);
			$txt = is_array($txts) ? $txts[$c] : $txts;
			$txt = ($txt === null || $txt === "NULL")? "" : $txt;
			$w = is_array($widths) ? $widths[$c] : $widths;
			$border = is_array($borders) ? $borders[$c] : $borders;
					
			$stringWidth = $this->GetStringWidth($txt);
			if($stringWidth > $w){
				$x = $this->getx();
				$y = $this->gety();
				$this->MultiCell($w,$lineHeight,$txt,$border,'R',false,$thisLineHeight);
				$lastCellHeight = $lineHeight;
				$this->setxy($x+$w,$y);
			}else{
				$this->Cell($w,$thisLineHeight,$txt,$border,'R',is_array($aligns) ? $aligns[$c] : $aligns);
				$lastCellHeight = $thisLineHeight;
			}
		}
		$x = $this->getx();
		$y = $this->gety();
		$this->setxy($x,$y+$thisLineHeight-$lastCellHeight);
		$this->Ln();
	}
}
?>