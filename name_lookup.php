<?php
    require_once 'functions.php';

    $overrides = array(
        // real name => joined name
        'Ben Mains' => 'Ben Mayonnaise',
        'James Pewitt' => 'James P',
        'Tommaso Pieroncini' => 'Tommy P'
    );

    function getNameMap()
    {
        global $group_id;
        global $access_token;
    	global $overrides;
        
        // download all the messages
        $url_base = "https://api.groupme.com/v3/groups/".$group_id."/messages?token=".$access_token."&limit=100&";
        $before_id = '';
        $messages = array();

        do {
        	if($before_id != ''){
        		$url = $url_base.'before_id='.$before_id;
        	} else {
        		$url = $url_base;
        	}
    	
        	$response = json_decode(file_get_contents($url));
        	if(empty($response)){
        		// no response, no results
        		break;
        	}
        	$response = $response->response;
    	
        	$count = $response->count;
        	$messagesNew = $response->messages;
        	$messages = array_merge($messages, $messagesNew);
    	
        	$before_id = end($messagesNew)->id;
        } while($count > 0);

        // sort only the system messages for name changes
        $system = array();
        $system_all = array();
        foreach($messages as $message){
        	if($message->system == true){
        		$system_all[$message->created_at] = $message->text;
        		if(strpos($message->text, 'changed name') !== false){
        			$system[$message->created_at] = $message->text;
        		}
        	}
        }

        // build maps
        $system = array_reverse($system);

        $map = $overrides;
        foreach($system as $change){
        	preg_match('/(.+?) changed name to (.+)/', $change, $matches);
        	$orig = $matches[1];
        	$new = $matches[2];

        	if(in_array($orig, array_values($map))){
        		$keys = array_keys($map, $orig);
        		$key = $keys[0];
        		$map[$key] = $new;
    		
        		if($key == $new){
        			unset($map[$key]);
        		}
    		
        		continue;
        	}

        	if(!isset($map[$orig])){
        		$map[$orig] = $new;
        		continue;
        	}
        }
        
        return $map;
    }
?>
