<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$dteStart = $_GET['from']; 
$dteEnd   = $_GET['to']; 
// $newstart = DateTime::createFromFormat('d/m/Y', $dteStart);
// $newend = DateTime::createFromFormat('d/m/Y', $dteEnd);
// $newstart=$newstart->format('Y-m-d');
// $newend=$newend->format('Y-m-d');
// $dteStart = '2015-10-01';
// $dteEnd = '2016-11-01';
$newstart = date($dteStart);
$newend = date($dteEnd);
$res = $database->getJournalDetailByDate($newstart,$newend);
$sum = $database->getJournalDetailByGL($newstart,$newend);
$total = $database->getJournalDetailSum($newstart,$newend);
//var_dump($res);
//var_dump($total);
class PDF extends FPDF
{
	function Header() {
		global $dteStart, $dteEnd;
	    // Logo
	    $this->Image('./images/dec-pdf.jpg',10,15,190);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    $this->Cell(80);
	    // Line break
	    $this->Ln(25);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',12);		
		if($this->PageNO()==1) {
			$this->Line(195,70,13,70);
			$this->Text(13,75,"Date");	
			$this->Text(40,75,"Description");	
			$this->Text(100,75,"Code");	
			$this->Text(130,75,"Family");	
			$this->Text(175,75,'$ Amount');				
			$this->Line(195,78,13,78);		
			$this->SetFont('OpenSansB','',12);		
			$this->SetY(62);
			$this->Cell(0,0,$dteStart.' --- '.$dteEnd,0,0,'C');
			$this->SetY(80);
		}
		else {
			$this->Line(195,60,13,60);
			$this->Text(13,65,"Date");	
			$this->Text(40,65,"Description");	
			$this->Text(100,65,"Code");	
			$this->Text(130,65,"Family");	
			$this->Text(175,65,'$ Amount');				
			$this->Line(195,68,13,68);		
			$this->SetFont('OpenSansB','',12);		
			$this->SetY(70);
		}
	}



	function invContent($res,$sum, $total) {
		global $database;
		$this->AddPage();
		$this->SetAutoPageBreak(true, 10);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',10);
		$this->SetLineWidth(0.2);
		//$this->SetY(80);
		for($i=0;$i<sizeof($res);$i++) {
			$this->SetX(13);
			if($i==0) {
				$this->Cell(20);
				$this->SetFont('OpenSansB','',12);
				$this->Cell(135,14,$res[$i][1],0,0);
				$this->Cell(20,14,'$ '.number_format($sum[$res[$i][0]][0][1],2,'.',','),0,1);
				$this->SetX(13);				
				$this->SetFont('OpenSans','',10);
				$this->Cell(26,8,$res[$i]['Date'],0,0,'L');
				$this->Cell(60,8,$res[$i][5],0,0,'L');
				$this->Cell(26,8,$res[$i]['Code'],0,0,'L');
				$this->Cell(32,8,$res[$i][2],0,0,'L');
				$this->Cell(35,8,'$ '.number_format($res[$i]['Amount'],2,'.',','),0,1,'R');
			}
			else {
				if($res[$i][0] == $res[$i-1][0]) {
					$this->Cell(26,8,$res[$i]['Date'],0,0,'L');
					$this->Cell(60,8,$res[$i][5],0,0,'L');
					$this->Cell(26,8,$res[$i]['Code'],0,0,'L');
					$this->Cell(32,8,$res[$i][2],0,0,'L');
					$this->Cell(35,8,'$ '.number_format($res[$i]['Amount'],2,'.',','),0,1,'R');
				}
				else {			
					$this->Cell(20);	
					$this->SetFont('OpenSansB','',12);
					$this->Cell(135,14,$res[$i][1],0,0);
					$this->Cell(20,14,'$ '.number_format($sum[$res[$i][0]][0][1],2,'.',','),0,1);
					$this->SetX(13);
					$this->SetFont('OpenSans','',10);
					$this->Cell(26,8,$res[$i]['Date'],0,0,'L');
					$this->Cell(60,8,$res[$i][5],0,0,'L');
					$this->Cell(26,8,$res[$i]['Code'],0,0,'L');
					$this->Cell(32,8,$res[$i][2],0,0,'L');
					$this->Cell(35,8,'$ '.number_format($res[$i]['Amount'],2,'.',','),0,1,'R');
				}
			}
		}
		$this->SetFont('OpenSansB','',12);
		$this->Line(127, $this->GetY()+10, 185, $this->GetY()+10);

		$this->Text(132,$this->GetY()+18, "Total:           $ ".number_format($total[0][2],2,'.',','));
	}

	function SetDash($black=null, $white=null) {
        if($black!==null)
            $s=sprintf('[%.3F %.3F] 0 d',$black*$this->k,$white*$this->k);
        else
            $s='[] 0 d';
        $this->_out($s);
    }

}

$pdf = new PDF();
$title = 'Journal Detail';
$pdf->SetTitle($title);
$pdf->invContent($res, $sum, $total);
$pdf->Output();