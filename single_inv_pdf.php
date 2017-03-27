<?php
$inv_no = $_GET['inv_no'];

include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$inv_no = $_GET['inv_no'];
$termid = $_GET['term_id'];
$termyear = substr($inv_no, 0, 4);
$termt = substr($inv_no, 4, 1);
$paydefault = substr($inv_no, -1);
$famid = substr($inv_no, 5, -1);
$fam_detail = $database->getFamilyDetailbyID($famid);
$people_detail = $database->getPeopleDetailbyID($famid);
$invidx_no = $database->getTypeIInvidxByFamAndTerm($famid, $termid);
// var_dump($invidx_no);

class PDF extends FPDF
{
	function Header() {

		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetLineWidth(0.1);
		// Logo
		if($this->PageNo()==1){
	    	$this->Image('./images/dec-pdf.jpg',10,15,190);
	    	 // Move to the right
		    $this->Cell(80);
		    // Line break
		    $this->Ln(25);
		}
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	   
		$this->SetFont('OpenSansB','',10);

		//table header
		
	}

	function subHeader($fam_detail, $inv_no) {
		// var_dump($inv_no);
		$date = new DateTime($inv_no[0]['Date']);
		$fam_name=$fam_detail[0]['Last'];
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
    	$this->AddPage();
    	$this->Line(200,100,10,100);
		$this->Text(12,105,"Class");	
		$this->Text(73,105,"Qty Attended");
		$this->Text(105,105,'$Class');
		$this->Text(125,105,'$Term');
		$this->Text(143,105,'Discount')	;
		$this->Text(165,105,'$Exam');	
		$this->Text(185,105,'$Total');				
		$this->Line(200,108,10,108);
		$this->SetY(120);
		$this->SetFont('OpenSansB','',12);
		$this->Text(18,65,"Invoices To: ");
		$this->SetFont('OpenSans','',12);
		$this->Text(50,65,"$fam_name");
		$fam_email = $fam_detail[0]['Post1'];
		if($fam_email != null)
			$this->Text(50,75,"$fam_email");
		$this->SetFont('OpenSansB','',12);
		$this->Text(140,65,"Date:");		
		$this->Text(136,71,"Inv No:");		
		$this->Text(129,77,"Reference:");
		$this->Text(130,83,"Account#: ");
		$this->SetY(64);

		$this->SetFont('OpenSans','',12);
		$this->Cell(0,0,$date->format('d/m/Y'),0,0,'R');
		$this->SetY(70);				
		$this->Cell(0,0,$inv_no[0]['InvNo'],0,0,'R');
		$this->SetY(76);
		$this->Cell(0,0,$inv_no[0]['Reference'],0,0,'R');
		$this->SetY(82);
		$this->Cell(0,0,$fam_detail[0][0],0,0,'R');
	}

	function invContent($inv_no) {
		global $database;
		$cur_total = 0;
		$invtrans = $database->getInvtransByNo($inv_no);
		// var_dump($invtrans);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetLineWidth(0.1);

		$this->SetXY(10,115);
		for($i=0; $i<sizeof($invtrans); $i++) {
			if($i == 0) {
				$this->SetFont('OpenSansB','',12);
				$this->Cell(80,5,$invtrans[0]['First'].' '.$invtrans[0]['Last'],0,1,'L');
				$this->Cell(0,2,'',0,1);
				$this->SetFont('OpenSans','',10);	
				$this->Cell(74,6,$invtrans[0]['Description'],0,0,'L');
				$this->Cell(20,6,$invtrans[0]['QtyAttend'],0,0,'L');
				$this->Cell(19,6,'$'.$invtrans[0]['Session'],0,0,'L');
				$this->Cell(22,6,'$'.$invtrans[0]['Term'],0,0,'L');
				$this->Cell(20,6,($invtrans[0]['discount']==0)?'  0':'10%',0,0,'L');
				$this->Cell(20,6,'$'.$invtrans[0]['Exam'],0,0,'L');
				$this->Cell(20,6,'$'.($invtrans[0]['Term']+ $invtrans[0]['Exam']),0,1,'L');
				$cur_total += $invtrans[0]['Term'] + $invtrans[0]['Exam'];
			}
			else if($invtrans[$i]['StudentID'] == $invtrans[$i-1]['StudentID']) {
				$this->Cell(74,6,$invtrans[$i]['Description'],0,0,'L');
				$this->Cell(20,6,$invtrans[$i]['QtyAttend'],0,0,'L');
				$this->Cell(19,6,'$'.$invtrans[$i]['Session'],0,0,'L');
				$this->Cell(22,6,'$'.$invtrans[$i]['Term'],0,0,'L');
				$this->Cell(20,6,($invtrans[$i]['discount']==0)? '  0':'10%',0,0,'L');
				$this->Cell(20,6,'$'.$invtrans[$i]['Exam'],0,0,'L');
				$this->Cell(20,6,'$'.($invtrans[$i]['Term']+$invtrans[$i]['Exam']),0,1,'L');
				$cur_total += $invtrans[$i]['Term'] + $invtrans[$i]['Exam'];
			}
			else if ($invtrans[$i]['StudentID'] != $invtrans[$i-1]['StudentID']) {
				$this->SetFont('OpenSansB','',12);
				$this->Cell(0,6,'',0,1);
				$this->Cell(80,5,$invtrans[$i]['First'].' '.$invtrans[$i]['Last'],0,1,'L');
				$this->Cell(0,2,'',0,1);
				$this->SetFont('OpenSans','',10);
				$this->Cell(74,6,$invtrans[$i]['Description'],0,0,'L');
				$this->Cell(20,6,$invtrans[$i]['QtyAttend'],0,0,'L');
				$this->Cell(19,6,'$'.$invtrans[$i]['Session'],0,0,'L');
				$this->Cell(22,6,'$'.$invtrans[$i]['Term'],0,0,'L');
				$this->Cell(20,6,($invtrans[$i]['discount']==0)?'  0':'10%',0,0,'L');
				$this->Cell(20,6,'$'.$invtrans[$i]['Exam'],0,0,'L');
				$this->Cell(20,6,'$'.($invtrans[$i]['Term']+$invtrans[$i]['Exam']),0,1,'L');
				$cur_total += $invtrans[$i]['Term'] + $invtrans[$i]['Exam'];
			}
			if ($i == sizeof($invtrans)-1) {
				$this->SetFont('OpenSansB','',10);
				$this->Cell(0,4,'',0,1);
				$this->Cell(0,4,$this->Line(150,$this->GetY(),198,$this->GetY()),0,1,'R');
				$this->Cell(160,6,'Sub-Total: ',0,0,'R');
				$this->Cell(25,6,'$'.number_format($cur_total,2,'.',','),0,1,'R');
				$this->Cell(0,4,'',0,1);
				$this->Cell(0,0,$this->Line(150,$this->GetY(),198,$this->GetY()),0,1,'R');
			}
		}
		$this->SetY($this->GetY() + 15);
	}

	function pastInv($famid, $termid) {
		global $database;
		$past_total = 0;
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');

		$this->SetLineWidth(0.2);

		$owing = $database->moneyOwingByFamily($famid);
		$sumamount = 0;
		for($i=0; $i<sizeof($owing); $i++) {
			$sumamount += $owing[$i][0];
		}
		if($sumamount == 0) 
			return null;

		for($i=0; $i<sizeof($owing); $i++) {			
			if($owing[$i][0] == 0)
				continue;
			else {
				$tmp = $database->getInvidxByFamAndTerm($famid, $owing[$i]['TermID']);
				$tmpterm = $database->getTermByTermID($owing[$i]['TermID']);
				// $this->SetX(12);
				$this->SetFont('OpenSansB','U',12);
				if($this->GetY() > 250) {
					$this->Cell(0,0,'','LRB',1);
					$this->AddPage();
				}
				$this->Cell(0,15,'  '.$tmpterm[0]['Year'].', Term '.$tmpterm[0]['Term'],'LRT',1);
				
				if($this->GetY() < 11 ) {
					$this->Cell(0,0,'','T',1);
				}
				$this->SetFont('OpenSans','',10);
				for($j=0;$j<sizeof($tmp);$j++) {
					//Term Heading

					//Term Payment Detail
					$this->Cell(58,7,str_pad($tmp[$j]['Date'],44," ", STR_PAD_BOTH),'L',0,'L');
					$this->Cell(97,7,$tmp[$j]['Description'],0,0,'L');
					$this->Cell(0,7,str_pad('$'.$tmp[$j]['Amount'],10),'R',1,'R');
					if($this->GetY() > 265) {
						$this->Cell(0,0,'','LRB',1);
						$this->AddPage();
						$this->Cell(0,0,'','T',1);
					}
				}

				$past_total += floatval($owing[$i][0]);

				if($this->GetY() > 265) {
					$this->Cell(0,0,'','B',1);
					$this->AddPage();
					$this->Cell(0,0,'','T',1);	
				}
				$this->Cell(0,6,$this->Line(170,$this->GetY()+2,198,$this->GetY()+2),'LR',1,'R');
				$this->SetFont('OpenSansB','',10);
				$this->Cell(0,7,str_pad('$'.$owing[$i][0],15," ",STR_PAD_BOTH),'LR',1,'R');				

				if($this->GetY() > 265) {
					$this->Cell(0,0,'','B',1);
					$this->AddPage();
					$this->Cell(0,0,'','T',1);	
				}
				if($i==sizeof($owing)-1) {
					$this->Cell(0,3,'','LR',1);
					$this->Cell(0,7,'Total Owing: '.str_pad('$'.number_format($past_total,2,'.',','),20," ",STR_PAD_BOTH),'LR',1,'R');
				}
				$this->SetFont('OpenSans','',10);
				$this->Cell(0,0,'','B',1);
			}
		}		
	}

	function paymentStat($termyear, $termt, $inv_no) {
		global $database;
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');

		$people_detail = $database->getInvtransForInitials($inv_no);
		// var_dump($people_detail);
		$patclar = $people_detail[0]['Last'] .' '. $people_detail[0]['First'];
		for($i=1;$i<sizeof($people_detail); $i++) {
			$patclar .= ', '.substr($people_detail[$i]['First'], 0, 1);
		}
		
		if($this->GetY() > 222) {
			$this->AddPage();

			$this->SetFont('OpenSansB', 'u', 12);
			$this->Cell(0,8,'Internet Banking',0,1);

			$this->SetFont('OpenSans', '', 10);
			$this->MultiCell(0, 5, 'Payment is due 7 days from the date of this invoice unless prior arrangements have been made with DEC management.',0,1);
			$this->Cell(0,8,'Internet banking number: Westpac 03-1548-0097949-000',0,1);
			$this->Cell(0,3,'',0,1);
			$this->Cell(0,6,'Particulars: '.$patclar,0,1);
			$this->Cell(0,6,'Code: Matua',0,1);
			$this->Cell(0,6,'Reference: '.$termyear. ' Term '. $termt);
		}
		else {
			$this->SetY(-70);
			$this->SetFont('OpenSansB', 'u', 12);
			$this->Cell(0,8,'Internet Banking',0,1);

			$this->SetFont('OpenSans', '', 10);
			$this->MultiCell(0, 5, 'Payment is due 7 days from the date of this invoice unless prior arrangements have been made with DEC management.',0,1);
			$this->Cell(0,8,'Internet banking number: Westpac 03-1548-0097949-000',0,1);
			$this->Cell(0,3,'',0,1);
			$this->Cell(0,6,'Particulars: '.$patclar,0,1);
			$this->Cell(0,6,'Code: Matua',0,1);
			$this->Cell(0,6,'Reference: '.$termyear. ' Term '. $termt);
		}
	}
}

$pdf = new PDF();
$title = $inv_no . " Statement";
$pdf->SetTitle($title);
$pdf->subHeader($fam_detail, $invidx_no);
$pdf->invContent($inv_no);
$pdf->pastInv($famid,$termid);
$pdf->paymentStat($termyear,$termt, $inv_no);
$pdf->Output();