<?php
	// needs an access token, bot id, group id
	require_once 'neeraj_credentials.php';

	$data = json_decode(file_get_contents('php://input'));

	if ($data == null) exit;

	if ($data->sender_type == "bot") exit;

	$text = $data->text;

	postMessage(strtoupper($text));

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