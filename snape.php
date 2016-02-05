<?php
	/*
	 * This is the bot callback function. All messages from the group
	 * post to this script. This script decides is the message is a 
	 * valid command, and does what's needed
	 */

	require_once 'functions.php';

	$data = json_decode(file_get_contents('php://input'));

	if ($data == null) exit;

	if ($data->sender_type == "bot" || $data->sender_type == "system") exit;

	$text = $data->text;

	if ($text == "meow" || $text == "Meow") {
		postMessage("stfu karma whore");
	}

	preg_match('/snape,? (make a|add)? ?saveplate for (.+)/i', $text, $matches);

	if ($matches != null) {
		addName($matches[count($matches) - 1]);
	} else {
		preg_match('/snape,? (remove|cancel) ?saveplate for (.+)/i', $text, $matches);

		if ($matches != null) {
			removeName($matches[count($matches) - 1]);
		} else {
			preg_match('/snape,? list(.+)saveplates/i', $text, $matches);

			if ($matches != null) {
				listNames();
			}
		}
	}
?>