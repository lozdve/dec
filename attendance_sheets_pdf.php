<?php
include("include/session.php");
global $database;
$config = $database->getConfigs();
require('fpdf.php');
$termid = $_GET['termid'];
$attendinfo = $database->getClassByTermID(($termid));
// var_dump($database->getClassByTermID($termid));
// var_dump($attendinfo);
class PDF extends FPDF
{
	private $cate_arr = array();
	function Header() {
	    // Arial bold 15
	    $this->SetFont('Arial','B',15);
	    // Move to the right
	    $this->Cell(80);
	    // Line break
	    $this->Ln(1);
		$this->AddFont('OpenSans','','OpenSans.php');
		$this->AddFont('OpenSansB','','OpenSans-Bold.php');
		$this->SetFont('OpenSans','',12);
	}

	function sheetCatalog() {
		global $database,$termid;
		$this->AddPage();
		// var_dump($attendinfo);
		$cls = array();
		$classes_in_term = $database->getClassByTermIDGroup($termid);
		// var_dump($classes_in_term);
		for($i=0;$i<sizeof($classes_in_term);$i++) {
			$tmp = $database->getClassDetail($classes_in_term[$i][20]);
			if($tmp != null)
				array_push($cls,$tmp[0][2]);
		}
		// var_dump($cls);
		$cls_link = array();
		$tmp = null;
		for($i=0;$i<sizeof($cls);$i++) {
			${"tmp$i"} = $this->AddLink();
			$this->Cell(50,7,$cls[$i],0,1,'L',0,${"tmp$i"});
		}

		// $tmp0 = $this->AddLink();
		// $this->Cell(50,7,$cls[0],0,1,'',0,$tmp0);
		// $link = $this->Addlink();
		// $this->Write(5,'fdsafasd',$link);
	}

	function drawSheetHeader() {		
		global $database,$termid;
		$terminfo = $database->getTermByTermID($termid);
		$this->AddPage();
		// var_dump($terminfo);
		// $this->SetLink($anchor);

		$this->SetFont('OpenSans','',16);
		$this->Cell(165);

		$this->Cell(20,7,'Term '.$terminfo[0][2].', '.$terminfo[0][3],0,1,'R');

		/*draw sheet top line */
		$this->SetXY(12,35);
		$this->SetFont('OpenSans','',12);
		$this->SetLineWidth(0.2);
		$this->SetFillColor(192,171,171);
		$this->Cell(53,7,'Week',1,0,'',1);

		$this->Cell(9,7,'1',1,0,'C',1);
		$this->Cell(9,7,'2',1,0,'C',1);
		$this->Cell(9,7,'3',1,0,'C',1);
		$this->Cell(9,7,'4',1,0,'C',1);
		$this->Cell(9,7,'5',1,0,'C',1);
		$this->Cell(9,7,'6',1,0,'C',1);
		$this->Cell(9,7,'7',1,0,'C',1);
		$this->Cell(9,7,'8',1,0,'C',1);
		$this->Cell(9,7,'9',1,0,'C',1);
		$this->Cell(9,7,'10',1,0,'C',1);
		$this->Cell(9,7,'11',1,0,'C',1);
		$this->Cell(9,7,'',1,0,'C',1);
		$this->Cell(9,7,'',1,0,'C',1);
		$this->Cell(9,7,'',1,1,'C',1);
	}

	function drawEachStudent($attendinfo) {
		global $database;	
		// var_dump($attendinfo);
		$timeinfo = $database->getTimeByTimeID($attendinfo[0][2]);
		$this->SetFont('OpenSans','',16);
		// $this->Text(130,24,$timeinfo[0][4] .' '.$timeinfo[0][5]);
		$this->SetLink(1);
		$this->SetY(11);
		$this->Cell(20,7,$database->getStudioDetail($timeinfo[0][1])[0][1],0,'R');
		$this->SetY(20);
		$this->Cell(20,7,$database->getClassDetail($timeinfo[0][2])[0][2],0,'R');
		$this->Cell(145);
		$aptime = new DateTime($timeinfo[0][5]);
		$this->Cell(20,7,$timeinfo[0][4] .'   '.$aptime->format('g:i A'),0,1,'R');
		$this->SetY(42);
		$j = 2;
		for($i=1;$i<sizeof($attendinfo);$i++) {

			if($attendinfo[$i][2]==$attendinfo[$i-1][2]) {
				$stu_name = $database->getStudentByID($attendinfo[$i-1][1]);
				if($stu_name != null) {
					$this->SetFont('OpenSans','',10);
					$this->SetLineWidth(0.1);
					$this->SetFillColor(192,171,171);

					$this->SetX(12);
					$this->Cell(53,7,$stu_name[0][3].' '.$stu_name[0][5],1,0,'',0);

					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,0,'C',0);
					$this->Cell(9,7,'',1,1,'C',0);
				}
			}
			else{
				$stu_name = $database->getStudentByID($attendinfo[$i-1][1]);
				if($stu_name != null) {

				$this->SetFont('OpenSans','',10);
				$this->SetLineWidth(0.1);
				$this->SetFillColor(192,171,171);
				$this->SetX(12);
				$this->Cell(53,7,$stu_name[0][3].' '.$stu_name[0][5],1,0,'',0);

				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,0,'C',0);
				$this->Cell(9,7,'',1,1,'C',0);
				}


				$this->drawSheetHeader();
				$timeinfo = $database->getTimeByTimeID($attendinfo[$i][2]);
				$last_time = $database->getTimeByTimeID($attendinfo[$i-1][2]);
				$time_format = new DateTime($timeinfo[0][5]);
				// var_dump($timeinfo);
				// var_dump($database->getClassDetail($timeinfo[0][2])[0][2]);
				$tmp_studio = $database->getStudioDetail($timeinfo[0][1]);
				$tmp_class = $database->getClassDetail($timeinfo[0][2]);
				if($tmp_studio !=null && $tmp_class != null) {
					$this->SetFont('OpenSans','',16);
					$this->SetY(11);
					$this->Cell(20,7,$tmp_studio[0][1],0,'R');
					$this->SetY(20);
					$this->Cell(20,7,$tmp_class[0][2],0,'R');
					$this->Cell(145);
					$this->Cell(20,7,$timeinfo[0][4].'   '.$time_format->format('g:i A'),0,1,'R');
					$this->SetY(42);
					if($timeinfo[0][2] != $last_time[0][2]) {
						$this->SetLink($j);
						$j++;
					}
				}
				continue;		
			}
		}
	}

}


	$pdf = new PDF();
	$title = 'Attendance Sheets';
	$pdf->SetTitle($title);
	$pdf->sheetCatalog();
	$pdf->drawSheetHeader();
	$pdf->drawEachStudent($attendinfo);
	$pdf->Output();


?>