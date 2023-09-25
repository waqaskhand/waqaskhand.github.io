<?php
include_once("../classes/config.php");
unset($_SESSION[ADMIN_LOGGED_IN]);
header("location:index.php");
?>