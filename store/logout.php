<?php
include_once("classes/config.php");
unset($_SESSION[WEB_USER_SESSION]);
$Web->Redirect(WEB_URL);
?>