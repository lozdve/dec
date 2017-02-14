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
$newstart = date($dteStart);
$newend = date($dteEnd);
$res = $database->getJournalSumByDate($newstart,$newend);

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
		$this->Line(195,70,13,70);
		$this->Text(30,75,"Description");	
		$this->Text(150,75,'$Total Amount');				
		$this->Line(195,78,13,78);		
		$this->SetFont('OpenSansB','',12);		
		$this->SetY(62);
		$this->Cell(0,0,$dteStart.' --- '.$dteEnd,0,0,'C');
	}



	function invContent($res) {
		global $database;
		$this->AddPage();
		$this->SetAutoPageBreak(true, 70);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',10);
		$this->SetLineWidth(0.2);
		$this->SetY(80);
		$total = 0;
		for($i=0;$i<sizeof($res);$i++) {
			$this->SetX(30);
			$this->Cell(125,8,$res[$i]['Description'],0,0);
			//$this->Cell(100);
			$this->Cell(25,8,'$ '.number_format($res[$i]['SUM(Amount)'],2,'.',','),0,1,'R');
			$total += $res[$i]['SUM(Amount)'];
		}
		$this->SetFont('OpenSansB','',12);
		$this->Line(127, $this->GetY()+10, 185, $this->GetY()+10);

		$this->Text(132,$this->GetY()+18, "Total:           $ ".number_format($total,2,'.',','));
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
$title = 'Journal Summary';
$pdf->SetTitle($title);
$pdf->invContent($res);
$pdf->Output();