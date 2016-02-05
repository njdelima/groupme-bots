<?php
	/*
	 * Runs as a cron job just before mealtimes
	 */
	require_once 'functions.php';
	listNames();
	file_put_contents($DIR . "list","");
?>
