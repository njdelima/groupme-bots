<?php
	/*
	 * Runs as a cron job at 12pm Mon-Fri & 6pm Mon-Thu.
	 * Clears the list.
	 */
	require_once 'functions.php';
	file_put_contents($DIR . "list","");
?>
