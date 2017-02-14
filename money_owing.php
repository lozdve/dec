<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$res = $database->moneyOwingReport();
// echo $res[0][1];
// var_dump($res);
// var_dump($total);
class PDF extends FPDF
{
	function Header() {
		global $dteStart, $dteEnd;
	    // Logo
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    // Line break
	    $this->Ln(25);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',24);		
		$this->Line(195,35,13,35);
		$this->Text(13,25,"Money Owing");	
		$this->SetY(40);
	}



	function invContent($res) {
		global $database;
		$this->AddPage();
		$this->SetAutoPageBreak(true, 10);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',10);
		$this->SetLineWidth(0.2);
		//$this->SetY(80);
		for($i=0;$i<sizeof($res);$i++) {
			$this->SetX(14);
			$this->Cell(55,6,$res[$i]['Last'],0,0);
			if($res[$i]['Post1']!=null) {
				$this->Cell(75,6,$res[$i]['Post1'],0,0);
			} else {
				$this->Cell(75,6,$res[$i]['Physical1'].', '.$res[$i]['Physical2'].', '.$res[$i]['Physical3'],0,0);
			}
			$this->Cell(30,6,$res[$i]['Phone'],0,0);
			$this->Cell(40,6,'$ '.number_format($res[$i]['SUM(Amount)'],2,'.',','),0,1);
		}
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
$pdf->invContent($res);
$pdf->Output();