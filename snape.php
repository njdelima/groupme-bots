<?php
	/*
	 * This is the bot callback function. All messages from the group
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
        exit;
    }

    preg_match('/(snape,?|!) ?(remove|cancel)(\s*)(save ?plate)?/i', $text, $matches);

    if ($matches != null) {
		$map = getNameMap();

        $name = array_search($data -> name, $map);
        
        $name === FALSE ? removeName($data -> name) : removeName($name);
        exit;
    }

	preg_match('/(snape,?|!) ?list(\s*)(save ?plates)?/i', $text, $matches);

	if ($matches != null) {
		listNames();
		exit;
	}
	
?>