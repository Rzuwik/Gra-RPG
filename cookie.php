<?php
/*
Created by Rzuwik
*/
ob_start();
session_start();

setcookie('cookies_enabled', 1, time()+86400);
if (!empty($_GET['a'])) {
	if (empty($_GET['a']))
		header("Location: index.php");
	else
		header("Location: index.php?a=".$_GET['a']);
} else header("Location: index.php");

ob_end_flush();
?>