<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$total_amount=0;
$fam_id = $_GET['fam_id'];
$inv_detail=$database->getInvidxByFam($fam_id);
$term_id_arr=array();
$fam_name=$database->getFamilyDetailbyID($fam_id)[0][2];
for($i=0;$i<sizeof($inv_detail);$i++) {
	if($i==0) {
		$term_id_arr[]=$inv_detail[0][10];
	}
	else if($inv_detail[$i][10]!=$inv_detail[$i-1][10]) {
		$term_id_arr[]=$inv_detail[$i][10];
	}
	else{
		continue;
	}
}
//var_dump($inv_detail);
class PDF extends FPDF
{
	function Header() {
	    // Logo
	    $this->Image('./images/dec-pdf.jpg',10,15,190);
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    $this->Cell(80);
	    // Line break
	    $this->Ln(25);
		$this->SetFont('OpenSansB','',10);
		if($this->PageNo()==1){
			$this->Line(200,100,10,100);
			$this->Text(12,105,"Class");	
			$this->Text(83,105,"Qty Attended");
			$this->Text(125,105,'$Class');
			$this->Text(145,105,'$Term');	
			$this->Text(165,105,'$Exam');	
			$this->Text(185,105,'$Total');				
			$this->Line(200,108,10,108);
			$this->SetY(120);
		}
		else {
			$this->Line(200,65,10,65);
			$this->Text(12,70,"Class");	
			$this->Text(83,70,"Qty Attended");
			$this->Text(125,70,'$Class');
			$this->Text(145,70,'$Term');	
			$this->Text(165,70,'$Exam');	
			$this->Text(185,70,'$Total');				
			$this->Line(200,73,10,73);
			$this->SetY(80);
		}
	}

	function subHeader($fam_name, $fam_email, $fam_id) {
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
    	$this->AddPage();
		$this->SetFont('OpenSansB','',12);
		$this->Text(18,65,"Statement To: ");
		$this->SetFont('OpenSans','',12);
		$this->Text(50,65,"$fam_name");
		if($fam_email != null)
			$this->Text(50,75,"$fam_name");
		$this->SetFont('OpenSansB','',12);
		$this->Text(140,65,"Date:");
		$this->Text(130,75,"Account#: ");
		$this->SetY(64);
		$this->Cell(0,0,date('d/m/Y'),0,0,'R');
		$this->SetY(74);		
		$this->Cell(0,0,$fam_id,0,0,'R');
	}

	function Footer() {
		global $fam_name, $total_amount;
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetY(240);
		$this->SetFont('OpenSansB','',14);
		$this->Cell(0,0,'REMITTANCE ADVICE',0,2,'C');

		$this->SetFont('OpenSansB','',12);
		$this->Text(15,255, 'Return to:');
		$this->SetFont('OpenSans','',12);
		$this->Text(15,264,'Dance Education Center');
		$this->Text(15,270,'228A Levers Road');
		$this->Text(15,276,'Matua');
		$this->Text(15,282,'Tauranga');
		$this->SetFont('OpenSansB','',12);
		$this->Text(135,255, 'Payment From:');
		$this->SetFont('OpenSans','',12);
		$this->Text(135,264,"$fam_name");
		$this->Text(135,272,"Total Owing:  $ ".number_format((float)$total_amount,2,'.',','));
		$this->Text(135,280,"Amount Paid:");
		$this->Line(165,282,190,282);

		$this->SetXY(30,120);
		$this->SetLineWidth(0.1);
		$this->SetDash(4,2);
		$this->Line(200,235,10,235);
		$this->SetDash();
	}


	function invContent($inv, $term) {
		global $database;
		$this->SetAutoPageBreak(true, 70);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetLineWidth(0.1);

		$this->SetXY(12,120);
		$total_inv = $database->getInvidxByFam($inv[0][7]);
		global $total_amount;
		for($i=0;$i<sizeof($total_inv);$i++) {
			$total_amount += $total_inv[$i]['Amount'];
		}
		for($i=0;$i<sizeof($term);$i++) {
			$y=$this->GetY();
			$term_info=$database->getTermByTermID($term[$i]);
			$term_title = $term_info[0]['Year'] . "  Term " . $term_info[0]['Term'];
			
			$inv_info=$database->getInvidxByFamAndTerm($inv[0][7],$term[$i]);
			$this->Cell(10);

			//calculate term amount
			$term_amount=0.00;
			// var_dump($inv_info);
			for($j=0;$j<sizeof($inv_info);$j++) {		
				// echo "term before: ".number_format($inv_info[$j]['Amount'], 2);	
				// echo " + ".	$term_amount . "***";
				// $term_amount = number_format($term_amount+$inv_info[$j]['Amount'],2,'.',',');
				// $term_amount = str_replace(",", "", $term_amount);
				$term_amount += (float)$inv_info[$j]['Amount'];
				// echo $term_amount."---";
			}
			$term_amount = number_format($term_amount,2);
			// echo "<br>";
			if($term_amount != 0){
				$this->SetX(12);
				$this->Line(13,$y+7,38,$y+7);
				$y += 12;
				$this->SetFont('OpenSansB','',12);
				$this->Cell(0,8,$term_title,0,2);
				$this->SetFont('OpenSans','',10);
				for($k=0;$k<sizeof($inv_info);$k++) {
					$this->SetX(22);
				 	$amt = '$ '.number_format((float)$inv_info[$k]['Amount'],2,'.',',');
					$this->Cell(30,8,$inv_info[$k]['Date'],0,0);
					$this->Cell(130,8,$inv_info[$k]['Reference'],0,0);
					$this->Cell(0,8,$amt,0,1);
					$this->Cell(12);
					$y += 6;
				}
				$y+=5;
				$this->SetFont('OpenSansB','',10);
				$y+=7;
				$this->Cell(110);
				$cellborder = '0,20,0,0';
				$this->Cell(50,8,"Owing For  ".$term_title,'T',0);
				$this->Cell(0,8,'$ '.$term_amount,'T',1);
				// echo "<br>";
				// echo "<br>";
			}
			else
				continue;
			$this->Cell(2);
		}
		$this->SetY($this->GetY() + 10);
		$this->SetFont('OpenSans','',12);
		$this->Line($this->GetX()+122, $this->GetY()-5, $this->GetX()+190, $this->GetY()-5);
		$this->Cell(0,0,'TOTAL OWING:     $ '.number_format((float)$total_amount,2,'.',','),0,0,'R');
		$this->Line($this->GetX()-68, $this->GetY()+4, $this->GetX(), $this->GetY()+4);
		$this->Line($this->GetX()-68, $this->GetY()+6, $this->GetX(), $this->GetY()+6);
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
$title = 'Family Statement';
$pdf->SetTitle($title);
$pdf->subHeader($fam_name, null, $fam_id);
$pdf->invContent($inv_detail,$term_id_arr);
$pdf->Output();