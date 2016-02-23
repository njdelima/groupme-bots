<?php
	/*
	 * This is the bot callback script. All messages from the group
	 * post to this script. This script decides is the message is a
	 * valid command, and does what's needed
	 */

	require_once 'name_lookup.php';

	$data = json_decode(file_get_contents('php://input'));

	if ($data == null) exit;

	if ($data->sender_type == "bot" || $data->sender_type == "system") exit;

	$text = $data->text;

	if ($text == "meow" || $text == "Meow") {
		postMessage("stfu karma whore");
		exit;
	}

	preg_match('/snape,? ?(make a|add)? ?save ?plate for (.+)/i', $text, $matches);

	if ($matches != null) {
		addName($matches[count($matches) - 1]);
		exit;
	}
	preg_match('/snape,? ?(remove|cancel) ?save ?plate for (.+)/i', $text, $matches);

	if ($matches != null) {
		removeName($matches[count($matches) - 1]);
		exit;
	}

	preg_match('/(snape,?|!) ?(add|make)(\s*)(save ?plate)?/i', $text, $matches);

	if ($matches != null) {
		$map = getNameMap();

        $name = array_search($data -> name, $map);

        $name === FALSE ? addName($data -> name) : addName($name);
    }

    preg_match('/(snape,?|!) ?(remove|cancel)(\s*)(save ?plate)?/i', $text, $matches);

    if ($matches != null) {
		$map = getNameMap();

        $name = array_search($data -> name, $map);

        $name === FALSE ? removeName($data -> name) : removeName($name);
    }

	preg_match('/(snape,?|!) ?(tbt|throwback)/i', $text, $matches);

	if ($matches != null) {
	        $directory = "pictures/";
	        $images = glob("" . $directory . "*.jpg");
	        $imgs = '';
	        foreach($images as $image) { $imgs[] = "$image"; }

	        $directory = "pictures/*/";
	        $images = glob("" . $directory . "*.jpg");
	        foreach($images as $image) { $imgs[] = "$image"; }

	        $directory = "pictures/*/*/";
	        $images = glob("" . $directory . "*.jpg");
	        foreach($images as $image) { $imgs[] = "$image"; }

	        $directory = "pictures/*/*/*/";
       		$images = glob("" . $directory . "*.jpg");
        	foreach($images as $image) { $imgs[] = "$image"; }

	        $directory = "pictures/*/*/*/*/";
	        $images = glob("" . $directory . "*.jpg");
	        foreach($images as $image) { $imgs[] = "$image"; }

        	shuffle($imgs);
		$response = json_decode(uploadImg("@".$imgs[5]));

		postImg($response->payload->url);
	}

	preg_match('/(snape,?|!) ?list(\s*)(save ?plates)?/i', $text, $matches);

	if ($matches != null) {
		listNames();
		exit;
	}
	
?>
