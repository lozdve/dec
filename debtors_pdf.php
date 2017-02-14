<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$asat = $_GET['to'];
$new_asat = DateTime::createFromFormat('Y-m-d', $asat);
$new_asat=$new_asat->format('d/m/Y');
$res = $database->debtorsReport($asat);
$det = $database->debtorsFamilyReport($asat);
// $res = null;
// var_dump($res);
// var_dump($total);
class PDF extends FPDF
{
	function Header() {
		global $new_asat;
		$today = date('D, d M Y');
	    // Logo
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    // Line break
	    $this->Ln(25);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',16);		
		$this->Line(195,35,13,35);
		$this->Text(13,25,"Debtors AS AT ".$new_asat);	
		$this->SetFont('OpenSans','',10);		
		$this->Text(165,25, $today);
		$this->SetY(40);
	}

	function invContent($res, $amt) {
		global $database;
		$this->AddPage();
		$this->SetAutoPageBreak(true, 10);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',10);
		$this->SetLineWidth(0.2);
		//$this->SetY(80);
		for($i=0;$i<sizeof($res);$i++) {
			$this->SetX(20);
			$this->Cell(35,6,$res[$i]['Code'],0,0);
			$this->Cell(60,6,$res[$i]['Last'],0,0);
			$this->Cell(55,6,$res[$i]['Phone'],0,0);
			$this->Cell(55,6,'$ '.number_format($res[$i]['SUM(Amount)'],2,'.',','),0,1);
		}
		$this->SetY($this->GetY()+15);
		$this->Cell(30);
		$this->Cell(150,0,'','B',1);
		$this->SetFont('OpenSansB','',12);
		$this->Cell($this->GetPageWidth()-30, 10,'$ '.number_format($amt[0]['SUM(Amount)'],2,'.',','),0,0,'R');
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
$pdf->invContent($det, $res);
$pdf->Output();