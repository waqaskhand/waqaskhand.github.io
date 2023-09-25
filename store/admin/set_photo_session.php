<?php
session_start();
$_SESSION['ImageType']		= $_REQUEST['ImageType'];
$_SESSION['ThumbWidth']		= $_REQUEST['Width'];
$_SESSION['ThumbHeight']	= $_REQUEST['Height'];
$_SESSION['Folder']			= $_REQUEST['Folder'];
$_SESSION['RecordID']		= $_REQUEST['RecordID'];
$_SESSION['TableName']		= $_REQUEST['TableName'];
$_SESSION['FieldName']		= $_REQUEST['FieldName'];
?>