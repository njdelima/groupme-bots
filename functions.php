<?php
	// needs an access token, bot id, group id
	require_once 'neeraj_credentials.php';

	$DIR = "/var/www/gtpkt.org/groupme/save_plates/";

	function addName($name) {
        if ( ( date("N") < 5 ) || ( date("N") == 5 && date("G") < 12 ) ) {
        	file_put_contents($DIR . "list", $name . "\n", FILE_APPEND);
			postMessage("Added saveplate for " . $name . ".");
        } else {
        	postMessage("No saveplates until Monday.");
        }
	}
	function removeName($name) {
		$oldList = file_get_contents($DIR . "list");
		$newList = str_ireplace($name . "\n", "", $oldList);
		if ($newList == $oldList) {
			postMessage("Sorry! I couldn't find " . $name . " on the list.");
		} else {
			file_put_contents($DIR . "list", $newList);
			postMessage("Removed saveplate for " . $name . ".");
		}
	}

	function listNames() {
		$meal = date("G") < 12 ? "Lunch" : "Dinner";
		$message = $meal . " saveplates for " . date("l, jS \of F") . ":\n\n";
		$message = $message . file_get_contents($DIR . "list");

		postMessage($message);
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