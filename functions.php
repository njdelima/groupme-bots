<?php
	// needs an access token, bot id, group id
	require_once 'neeraj_credentials.php';

	$DIR = "/var/www/gtpkt.org/groupme/save_plates/";

	/**
	 * Adds a name to the list.
	 * Only adds if current time is Mon-Thu
	 * or Friday before 12.
	 */
	function addName($name) {
		global $DIR;
        	if ( ( date("N") < 5 ) || ( date("N") == 5 && date("G") < 12 ) ) {
        		file_put_contents($DIR . "list", $name . "\n", FILE_APPEND);
			postMessage("Added saveplate for " . $name . ".");
        	} else {
        		postMessage("No saveplates until Monday.");
        	}
	}
	/**
	 * Removes a name from the list.
	 */
	function removeName($name) {
		global $DIR;
		$oldList = file_get_contents($DIR . "list");
		$newList = str_ireplace($name . "\n", "", $oldList);
		if ($newList == $oldList) {
			postMessage("Sorry! I couldn't find " . $name . " on the list.");
		} else {
			file_put_contents($DIR . "list", $newList);
			postMessage("Removed saveplate for " . $name . ".");
		}
	}
	/**
	 * Posts a message to the group with the current list.
	 */
	function listNames() {
		global $DIR;
		$meal = date("G") < 12 ? "Lunch" : "Dinner";
		$message = $meal . " saveplates for " . date("l, jS \of F") . ":\n\n";
		$message = $message . file_get_contents($DIR . "list");

		postMessage($message);
	}

	/**
	 * Uploads an image to groupme's image service
	 * so that it can be later used with the API.
	 */
	function uploadImg($img) {
        	global $access_token;
        	$url = "https://image.groupme.com/pictures";
        	$postfields = array("file" => $img, "access_token" => $access_token);
        	logger($postfields);
        	$ch = curl_init($url);
        	curl_setopt($ch, CURLOPT_POST, true);
        	curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		// curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));
        	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        	$result = curl_exec($ch);

		//$result = print_r(json_decode(curl_exec($ch)), true);
       		logger(print_r(json_decode($result), true));
        	curl_close($ch);
        	return $result;
    	}

	/**
	 * Posts an image specified by the url to the group.
	 * Image needs to have already been uploaded to Groupme's
	 * image service - see uploadImg()
	 */
	function postImg($imgUrl) {
		global $bot_id;

		$url = 'https://api.groupme.com/v3/bots/post';

		$postfields["attachments"][] = array("type" => "image", "url" => $imgUrl);

		$postfields["bot_id"] = $bot_id;
		$postfields["text"] = "#TBT";

		$ch = curl_init($url);

		$postfields = json_encode($postfields);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length: ' . strlen($postfields)));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		$c = curl_exec($ch);
		return $c;
	}

	/**
	 * Posts a message to the group.
	 */
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

	/**
	 * Logs text to the log file.
	 */
	function logger($text) {
        	file_put_contents('logger.txt', $text."\n", FILE_APPEND);
        	return $text;
	}
?>
