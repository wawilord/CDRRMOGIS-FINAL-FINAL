<?php
/*
 * _____________________________________________________________________________________
 *
 * TITLE: 			The Barangay Disaster Report Form
 * DESCRIPTION: 	Code for the encoding of disaster reports by the barangay in
 * 					'disaster_reports' table.
 * _____________________________________________________________________________________
 *
 * RETURN KEYWORDS:
 *
 * success: *Date Time Added*	-> 	If encoded successfully
 * error: *message for error* 	-> 	If encoded unsuccessfully
 *______________________________________________________________________________________
 *
 * VARIABLES:
 *
 * $_POST['DECLARE']
 * $_POST['TOTALLY']
 * $_POST['PARTIALLY']
 * $_POST['DEAD']
 * $_POST['INJURED']
 * $_POST['MISSING']
 *
 *__________________________________________________________________________________________________________
 *
 * Note: you can send a custom message by typing keywords 'msg:', 'success:', 'error:', and 'warning:' 
 *		 followed by your message. Just make sure that there are no printed or echoed before the keyword.
 *__________________________________________________________________________________________________________
 *
 */
 
session_start();
include('../form/connection.php');
include ('../function/functions.php');
$db = new db();

$form_DECLARE = $db->connection->real_escape_string($_POST['DECLARE']);
$form_BRGY = $db->connection->real_escape_string($_POST['BARANGAY']);
$form_REPORTID = $db->connection->real_escape_string($_POST['REPORTID']);
$form_TOTALLY = $db->connection->real_escape_string($_POST['TOTALLY']);
$form_PARTIALLY = $db->connection->real_escape_string($_POST['PARTIALLY']);
$form_DEAD = $db->connection->real_escape_string($_POST['DEAD']);
$form_INJURED = $db->connection->real_escape_string($_POST['INJURED']);
$form_MISSING = $db->connection->real_escape_string($_POST['MISSING']);
$limit = 10000;

if($form_TOTALLY < 0 || $form_PARTIALLY < 0 || $form_DEAD < 0 || $form_INJURED < 0 || $form_MISSING < 0) {
	echo "error: Numbers can't be negative;";
	exit;
}
else if($form_TOTALLY > $limit || $form_PARTIALLY > $limit || $form_DEAD > $limit || $form_INJURED > $limit || $form_MISSING > $limit) {
	echo "error: Numbers can't exceed barangay population;";
	exit;	
}
if(!ctype_digit($form_TOTALLY) || !ctype_digit($form_PARTIALLY) || !ctype_digit($form_DEAD) || !ctype_digit($form_INJURED) || !ctype_digit($form_MISSING)) {
	echo "error: Numeric input is required.";
}

$sql = "INSERT INTO disaster_reports (
		BARANGAY, 
		DECLAREID, 
		UPLOADER,
		DMGTOTALLY,
		DMGPARTIALLY,
		CSLTDEAD,
		CSLTINJURED,
		CSLTMISSING, 
		ISVERIFIED) 
		VALUES (
		$form_BRGY,
		$form_DECLARE,
		'$form_REPORTID',
		$form_TOTALLY,
		$form_PARTIALLY,
		$form_DEAD,
		$form_INJURED,
		$form_MISSING,
		1
		)";

if($db->connection->query($sql)) {
	$newID = $db->connection->insert_id;
	$sql2 = "SELECT DATEADDED FROM disaster_reports WHERE ID = $newID";
	
	$result = $db->connection->query($sql2);
	$row = $result->fetch_assoc();
	
	echo 'success: ' . $newID . '_' . converttoformaldatetimestring($row["DATEADDED"]);
	console_log('USER(' . $form_REPORTID . ') submitted a verified disaster report(' . $newID . ')', '../../system/log.txt');
}
else {
	echo 'error: Failure in report submission.';
}
?>