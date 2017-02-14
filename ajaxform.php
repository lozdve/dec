<?php
include("include/database.php");


function updateFam($fam_id, $key, $fam_value) {
	global $database;
	echo $database->updateFamilyCode($fam_id, $key ,$fam_value);
}
function updateCls($cls_id, $key, $cls_value) {
	global $database;
	echo $database->updateClass($cls_id, $key ,$cls_value);
}

if(isset($_POST['fam_id'])){
	updateFam($_POST['fam_id'], $_POST['key'], $_POST['fam_value']);
}
if(isset($_POST['class_id'])){
	updateCls($_POST['class_id'], $_POST['key'], $_POST['cls_value']);
}

?>