<?php

	include_once("../classes/config.php");

	$_REQUEST = $Web->FilterString($_REQUEST);

	extract($_REQUEST);

	include_once("fireaction.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />

<title>E-Commerce Store Control Panel</title>

<link rel="stylesheet" type="text/css" href="css/style.css?v=<?php echo VERSION; ?>" />

<link rel="stylesheet" type="text/css" href="css/dropdown.css?v=<?php echo VERSION; ?>" />

<link rel="stylesheet" type="text/css" href="css/jdpicker.css?v=<?php echo VERSION; ?>" />

<link rel="stylesheet" type="text/css" href="css/colorpicker.css?v=<?php echo VERSION; ?>" />

<link rel="stylesheet" type="text/css" href="css/pagination.css?v=<?php echo VERSION; ?>" />

<link rel="stylesheet" type="text/css" href="css/grey.css?v=<?php echo VERSION; ?>" />

<script>

window.ApplicationUrl='<?php echo WEB_URL; ?>';

</script>

<script src="../js/jquery.js"></script>

<script src="../js/jquery.migrate.js"></script>

<script src="../js/jquery.easing.js"></script>

<script src="js/jquery.jdpicker.js"></script>

<script src="js/jquery.limit.js"></script>

<script src="js/colorpicker.js"></script>

<script src="js/eye.js"></script>

<script src="js/utils.js"></script>

<script src="../tiny_mce/jquery.tinymce.js"></script>

<script src="../js/tinymce.config.js"></script>

<script src="js/jquery.dropdown.js"></script>

<script src="js/common.js?v=<?php echo VERSION; ?>"></script>

<script src="js/client.js?v=<?php echo VERSION; ?>"></script>

</head>

<script>

$(document).ready(function(){

	$('#Sale_StartDate, #Sale_EndDate, #OrderDateFrom, #OrderDateTo').jdPicker();

});

</script>

<body>

<?php

if(isset($_SESSION[ADMIN_LOGGED_IN]))

{

	echo '<div class="main-continer">';

	include_once("header.php");

	if(isset($_REQUEST['page']))

	{

		$Page = $_REQUEST['page'].".php";

		if(file_exists($Page))

			include_once($Page);

		else

			include_once("content.php");

	}

	else

		include_once("content.php");

		

	include_once("footer.php");

	echo '</div>';

}

else

	include_once("login.php");

	

	if(isset($_SESSION[CONFIRMATION_MESSAGE]))

	{

		$Web->ShowAlert($_SESSION[CONFIRMATION_MESSAGE]);

		unset($_SESSION[CONFIRMATION_MESSAGE]);

	}

?>

</body>

</html>