<?php

/*
 * Example PHP implementation used for the index.html example
 */

// DataTables PHP library
include( "DataTables.php" );

// Alias Editor classes so they are easy to use
use
	DataTables\Editor,
	DataTables\Editor\Field,
	DataTables\Editor\Format,
	DataTables\Editor\Mjoin,
	DataTables\Editor\Upload,
	DataTables\Editor\Options,
	DataTables\Editor\Validate;

// Build our Editor instance and process the data coming from _POST
if( isset($_POST['boo_stu']) && $_POST['boo_stu'] == '1'){
	Editor::inst( $db, 'people' , 'PersonID')
		->fields(
			Field::inst( 'First' ),
			Field::inst( 'Middle' ),
			Field::inst( 'Last' ),
			Field::inst( 'Mobile' ),
			Field::inst( 'Medical' ),
			Field::inst( 'DOB' )
				->setFormatter( function ( $val, $data, $opts ){
					return date('Y-m-d', strtotime($val));
				}),
			Field::inst( 'FamilyID' ),
			Field::inst( 'Student' )
		)
		->where('Student', 1)
		->where('FamilyID', $_POST['famID'])
		->process( $_POST )
		->json();
}
else if (isset($_POST['boo_stu']) && $_POST['boo_stu'] == '0') {
	Editor::inst( $db, 'people' , 'PersonID')
	->fields(
		Field::inst( 'First' ),
		Field::inst( 'Middle' ),
		Field::inst( 'Last' ),
		Field::inst( 'Mobile' ),
		Field::inst( 'Medical' ),
		Field::inst( 'DOB' ),
		Field::inst( 'FamilyID' ),
		Field::inst( 'Student' )
	)
	->where('Student', 0)
	->where('FamilyID', $_POST['famID'])
	->process( $_POST )
	->json();
}

else if (isset($_POST['personid'])) {	
	$join_query = 'attendance.StudentID=';
	$pid_str = explode(",", $_POST['personid']);
	for($i=0;$i<sizeof($pid_str);$i++) {
	    if($i != sizeof($pid_str)-1)
	        $join_query .= $pid_str[$i] . ' OR attendance.StudentID=';
	    else 
	        $join_query .= $pid_str[$i];        
    }
    if($_POST['termid'] != null) {
    	Editor::inst( $db, 'people' , 'PersonID')
		->fields(
			Field::inst( 'people.First' ),
			Field::inst( 'people.Last' ),
			Field::inst( 'class.Class' ),
			Field::inst( 'times.Day' ),
			Field::inst( 'times.Time' )
				->getFormatter( function ( $val, $data, $opts ){
					return date('g:ia', strtotime($val));
				}),
			Field::inst( 'attendance.PayDefault' ),
			Field::inst( 'attendance.PayAmount' )
		)
		->leftJoin('attendance', 'attendance.StudentID', '=', 'people.PersonID AND ('.$join_query.') AND attendance.TermID= '.$_POST['termid'])
		->leftJoin('class', 'attendance.ClassID', '=', 'class.ClassID')
		->leftJoin('times', 'attendance.TimeID', '=', 'times.TimeID')
		->process( $_POST )
		->json();
    }	
}

else if (isset($_POST['trans'])) {
	Editor::inst( $db, 'invidx', 'TransID')
		->fields(
			Field::inst( 'invidx.InvNo' ),
			Field::inst( 'invidx.Date' ),
			Field::inst( 'invidx.Reference' ),
			Field::inst( 'invidx.Description' ),
			Field::inst( 'invidx.Amount' ),
			Field::inst( 'invidx.TermID' ),
			Field::inst( 'terms.Year' ),
			Field::inst( 'terms.Term' )
		)
		->leftJoin('terms', 'terms.TermID', '=', 'invidx.TermID AND invidx.FamID='.$_POST['famID'])
		->process( $_POST )
		->json();
}

else if (isset($_POST['inv_no']) && isset($_POST['termid'])) {
	$inv_no = $_POST['inv_no'];
	$famid = $_POST['famid'];
	// var_dump($inv_no);
	Editor::inst( $db, 'invtrans', 'IndDetID')
		->fields(
			Field::inst( 'people.First' ),
			Field::inst( 'people.Last' ),
			Field::inst( 'invtrans.Description' ),
			Field::inst( 'invtrans.QtyAttend' ),
			Field::inst( 'invtrans.Session' ),
			Field::inst( 'invtrans.Term' ),
			Field::inst( 'invtrans.Exam'),
			Field::inst( 'invtrans.InvNo' ),
			Field::inst( 'invtrans.IndDetID'),
			Field::inst( 'invtrans.discount'),
			Field::inst( 'invtrans.StudentID')
				->options( Options::inst()
					->table('people')
					->value('PersonID')
					->label(array('First', 'Last'))
					->where(function($q) {
						$q->where('FamilyID', $_POST['famid'], '=');
					}))
		)
		->leftJoin('people', 'people.PersonID', '=', 'invtrans.StudentID AND invtrans.InvNo=\'' .$_POST['inv_no']. '\'')
		->process( $_POST )
		->json();
}