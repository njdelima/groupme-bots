<?php
	// needs an access token, bot id, group id
	require_once 'neeraj_credentials.php';

	$data = json_decode(file_get_contents('php://input'));

	if ($data == null) exit;

	if ($data->sender_type == "bot" || $data->sender_type == "system") exit;

	$text = $data->text;

	preg_match('/snape,? make a saveplate for (.+)/i', $text, $matches);

	if ($matches != null) {
		addName($matches[1]);
	} else {
		preg_match('/snape,? remove saveplate for (.+)/i', $text, $matches);

		if ($matches != null) {
			removeName($matches[1]);
		}
	}

	function addName($name) {
		file_put_contents("list", $name . "\n", FILE_APPEND);
		postMessage("Added saveplate for " . $name . ".");
	}
	function removeName($name) {
		$oldList = file_get_contents("list");
		$newList = str_replace($name . "\n", "", $oldList);
		if ($newList == $oldList) {
			postMessage("Sorry! I couldn't find " . $name . "on the list.");
		} else {
			file_put_contents("list", $newList);
			postMessage("Removed saveplate for " . $name . ".");
		}
	}

	function postMessage($message) {
		global $bot_id;

		//build the url
		$message = urlencode($message);
		$url = 'https://api.groupme.com/v3/bots/post?bot_id='.$bot_id.'&text='.$message;

		// send the message

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array());
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$result = curl_exec($ch);
	}
?>