<?php
if(isset($_REQUEST['refreshAjax']))
	include_once("classes/config.php");
	
if(isset($_SESSION[USER_PRODUCTS_QUERY]))
{
	//sanitize post value
	if(!isset($_REQUEST['group_no']))
		$group_number = 0;
	else
		$group_number = $_REQUEST["group_no"];
	
	//throw HTTP error if group number is not valid
	/*
	if(!is_numeric($group_number))
	{
		header('HTTP/1.1 500 Invalid number!');
		exit();
	}
	*/
	
	//get current starting point of records
	$position = ($group_number * PRODUCT_ITEM_PER_GROUP);
	
	//Limit our results within a specified range.
	$Query = $_SESSION[USER_PRODUCTS_QUERY] . " LIMIT $position, ".PRODUCT_ITEM_PER_GROUP;
	$Products->ShowProducts($Query);
}
?>