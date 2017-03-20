<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$all_banking = $database->getBanking();

class PDF extends FPDF {
	function Header() {
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans', '', 28);
		$this->Cell(0,12,'Dance Education Center',0,1,'C');

		$this->SetFont('OpenSansB', '', 12);
		$this->Line(10,30,200,30);
		$this->Text(12, 36, 'Reference');
		$this->Text(45, 36, 'Description');
		$this->Text(100, 36, 'Bank');
		$this->Text(130, 36, 'Branch');
		$this->Text(180, 36, 'Amount');
		$this->Line(10,40,200,40);
	}

	function content($all_banking) {
		$this->AddPage();
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSansB', 'U', 12);
		$pay1 = array();
		$pay2 = array();
		$pay3 = array();
		$pay4 = array();
		for($i=0; $i<sizeof($all_banking); $i++) {
			if($all_banking[$i]['PaymentID']==1) {
				$pay1[$i] = $all_banking[$i];
			} else if($all_banking[$i]['PaymentID']==2) {
				$pay2[$i] = $all_banking[$i];
			} else if($all_banking[$i]['PaymentID']==3) {
				$pay3[$i] = $all_banking[$i];
			} else if($all_banking[$i]['PaymentID']==4) {
				$pay4[$i] = $all_banking[$i];
			}
		}
		sort($pay1);
		sort($pay2);
		sort($pay3);
		sort($pay4);

		$this->SetY(45);
		$tmp = array(0=>0, 1=>0, 2=>0, 3=>0);
		for($i=0; $i<4; $i++ ){
			switch ($i) {
				case 0:
					if(empty($pay1)) {
						break;
					}
					$this->Cell(0,10,'Cash',0,1,'L');
					$this->SetFont('OpenSans','',12);
					for($j=0;$j<sizeof($pay1);$j++) {
						$this->SetX(15);
						$this->Cell(32,6,$pay1[$j]['Reference'],0,0);
						$this->Cell(53,6,$pay1[$j]['Description'],0,0);
						$this->Cell(32,6,$pay1[$j]['Bank'],0,0);
						$this->Cell(48,6,$pay1[$j]['Branch'],0,0);
						$this->Cell(30,6,'$'.$pay1[$j]['Amount'],0,1);
						$tmp[0] += $pay1[$j]['Amount'];
					}
					$this->SetY($this->GetY()+5);
					$this->Line(135,$this->GetY(),200,$this->GetY());					
					$this->SetFont('OpenSansB','',12);
					$this->Cell(150,10,'Total Cash',0,0,'R');
					$this->Cell(0,10,'$ '.number_format($tmp[0],2),0,1,'R');
					$this->Cell(0,6,'',0,1);
					break;
				case 1:
					if(empty($pay2)) {
						break;
					}
					$this->SetFont('OpenSansB', 'U', 12);
					$this->Cell(0,10,'Cheque',0,1,'L');
					$this->SetFont('OpenSans','',12);
					for($j=0;$j<sizeof($pay2);$j++) {
						$this->SetX(15);
						$this->Cell(32,6,$pay2[$j]['Reference'],0,0);
						$this->Cell(53,6,$pay2[$j]['Description'],0,0);
						$this->Cell(32,6,$pay2[$j]['Bank'],0,0);
						$this->Cell(48,6,$pay2[$j]['Branch'],0,0);
						$this->Cell(30,6,'$'.$pay2[$j]['Amount'],0,1);
						$tmp[1] += $pay2[$j]['Amount'];
					}
					$this->SetY($this->GetY()+5);
					$this->Line(135,$this->GetY(),200,$this->GetY());					
					$this->SetFont('OpenSansB','',12);
					$this->Cell(155,10,'Total Cheque',0,0,'R');
					$this->Cell(0,10,'$ '.number_format($tmp[1],2),0,1,'R');
					$this->Cell(0,6,'',0,1);
					break;
				case 2:
					if(empty($pay3)) {
						break;
					}
					$this->SetFont('OpenSansB', 'U', 12);
					$this->Cell(0,10,'Credit Card',0,1,'L');
					$this->SetFont('OpenSans','',12);
					for($j=0;$j<sizeof($pay3);$j++) {
						$this->SetX(15);
						$this->Cell(32,6,$pay3[$j]['Reference'],0,0);
						$this->Cell(53,6,$pay3[$j]['Description'],0,0);
						$this->Cell(32,6,$pay3[$j]['Bank'],0,0);
						$this->Cell(48,6,$pay3[$j]['Branch'],0,0);
						$this->Cell(30,6,'$'.$pay3[$j]['Amount'],0,1);
						$tmp[2] += $pay3[$j]['Amount'];
					}
					$this->SetY($this->GetY()+5);
					$this->Line(135,$this->GetY(),200,$this->GetY());					
					$this->SetFont('OpenSansB','',12);
					$this->Cell(162,10,'Total Credit Card',0,0,'R');
					$this->Cell(0,10,'$ '.number_format($tmp[2],2),0,1,'R');
					$this->Cell(0,6,'',0,1);
					break;
				case 3:
					if(empty($pay4)) {
						break;
					}
					$this->SetFont('OpenSansB', 'U', 12);
					$this->Cell(0,10,'Direct Credit',0,1,'L');
					$this->SetFont('OpenSans','',12);
					for($j=0;$j<sizeof($pay4);$j++) {
						$this->SetX(15);
						$this->Cell(32,6,$pay4[$j]['Reference'],0,0);
						$this->Cell(53,6,$pay4[$j]['Description'],0,0);
						$this->Cell(32,6,$pay4[$j]['Bank'],0,0);
						$this->Cell(48,6,$pay4[$j]['Branch'],0,0);
						$this->Cell(30,6,'$'.$pay4[$j]['Amount'],0,1);
						$tmp[3] += $pay4[$j]['Amount'];
					}
					$this->SetY($this->GetY()+5);
					$this->Line(135,$this->GetY(),200,$this->GetY());					
					$this->SetFont('OpenSansB','',12);
					$this->Cell(150,10,'Total Direct Credit',0,0,'R');
					$this->Cell(0,10,'$ '.number_format($tmp[3],2),0,1,'R');
					$this->Cell(0,6,'',0,1);
					break;
				
				default:
					# code...
					break;
			}
			if($i==3){
				$this->SetY($this->GetY()+10);
				$this->Line(135,$this->GetY(), 200,$this->GetY());
				$this->Cell(157,10,'Total Banking',0,0,'R');
				$this->Cell(0,10,'$ '.number_format(array_sum($tmp),2),0,1,'R');
			}
		}
	}
}

$pdf=new PDF();
$title = 'Banking';
$pdf->SetTitle($title);
$pdf->content($all_banking);
$pdf->Output();

?>