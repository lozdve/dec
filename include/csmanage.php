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
	DataTables\Editor\Validate;

if(isset($_POST['family'])) {
	Editor::inst( $db, 'family' , 'FamilyID')
		->fields(
			Field::inst( 'Code' ),
			Field::inst( 'Last' ),
			Field::inst( 'Post1' ),
			Field::inst( 'Post2' ),
			Field::inst( 'Post3' ),
			Field::inst( 'Physical1' ),
			Field::inst( 'Physical2' ),
			Field::inst( 'Physical3' ),
			Field::inst( 'Phone' ),
			Field::inst( 'FamilyID' )	
		)
		->process( $_POST )
		->json();
}
else if(isset($_POST['class'])) {
	Editor::inst( $db, 'class' , 'ClassID')
		->fields(
			Field::inst( 'Code' ),
			Field::inst( 'Class' ),
			Field::inst( 'Session' ),
			Field::inst( 'Term' ),
			Field::inst( 'Exam' ),
			Field::inst( 'ExamAss' ),
			Field::inst( 'NextGrade' ),
			Field::inst( 'Deleted' ),
			Field::inst( 'GL' ),
			Field::inst( 'Cat' ),
			Field::inst( 'ClassID' )
		)
		->process( $_POST )
		->json();
}
else if(isset($_POST['cls_id']) && !isset($_POST['time_id'])) {
	Editor::inst( $db, 'times' , 'times.TimeID')
		->fields(
			Field::inst( 'studio.Studio' ),
			Field::inst( 'times.Day' ),
			Field::inst( 'times.Time' ),
			Field::inst( 'studio.StudioID' ),
			Field::inst( 'times.StudioID'),
			Field::inst( 'times.ClassID' ),
			Field::inst( 'times.TimeID' ),
			Field::inst( 'times.TermID' )
				->options('terms', 'TermID', array('Year', 'Term')),
			Field::inst( 'terms.Year' ),			
			Field::inst( 'terms.Term' )
		)
		->leftJoin('terms', 'times.TermID', '=', 'terms.TermID  AND classID='.$_POST['cls_id'])
		->leftJoin('studio', 'studio.StudioID', '=', 'times.StudioID')
		->process( $_POST )
		->json();
}
else if(isset($_POST['cls_id']) && isset($_POST['time_id'])) {
	Editor::inst( $db, 'attendance' , 'attendance.AttendID')
		->fields(
			Field::inst( 'attendance.Week01' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week02' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week03' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week04' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week05' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week06' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week07' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week08' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week09' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week10' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Week11' )
				->setFormatter( function($val, $data, $opts) {
						return strtoupper($val);
					}),
			Field::inst( 'attendance.Exam' ),
			Field::inst( 'attendance.Grade' ),
			Field::inst( 'attendance.StudentID' )
				->options('people','PersonID', array('First', 'Last')),
			Field::inst( 'people.First' ),
			Field::inst( 'people.Last' ),
			Field::inst( 'attendance.TimeID' ),
			Field::inst( 'attendance.TermID' ),
			Field::inst( 'attendance.ClassID' ),
			Field::inst( 'attendance.PayDefault' ),
			Field::inst( 'attendance.PayAmount' )
				->getFormatter( function($val, $data, $opts) {
					return number_format($val, 2, '.',',');
				})
		)
		->leftJoin('times','times.TimeID','=','attendance.TimeID AND attendance.TimeID='.$_POST['time_id'].' AND times.ClassID='.$_POST['cls_id'])
		->leftJoin('people', 'PersonID', '=', 'StudentID')
		->process( $_POST )
		->json();
}