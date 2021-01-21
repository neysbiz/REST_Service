<?php

/*
class zmqtt {
        private $server         = 'localhost';
        $port           = 1883;
        $keepalive      = 5;
        $user           = 'kessel2';
        $pass           = 'kessel2';

	
}
*/
function doc_publish($msg, $topic) {
	$server 	= 'localhost';
	$port		= 1883;
	$keepalive	= 5;
	$user		= 'kessel2';
	$pass		= 'kessel2';

	$client = new Mosquitto\Client();
	$client->setCredentials($user, $pass );
	$client->onConnect('connect');
	$client->onDisconnect('disconnect');
	$client->onPublish('publish');
	$client->connect($server, $port, $keepalive);

	try {
		$client->loop();
		$mid = $client->publish($topic, $msg);
		$client->loop();
		}catch(Mosquitto\Exception $e){
/*
				echo 'Exception';
				return;
*/
				return 'Exception';
			}
    $client->disconnect();
    unset($client);

}

/*****************************************************************
 * Call back functions for MQTT library
 * ***************************************************************/					
function connect($r) {
/*
		if($r == 0) echo "{$r}-CONX-OK|";
		if($r == 1) echo "{$r}-Connection refused (unacceptable protocol version)|";
		if($r == 2) echo "{$r}-Connection refused (identifier rejected)|";
		if($r == 3) echo "{$r}-Connection refused (broker unavailable )|";        
*/
		if($r == 0) return "{$r}-CONX-OK|";
		if($r == 1) return "{$r}-Connection refused (unacceptable protocol version)|";
		if($r == 2) return "{$r}-Connection refused (identifier rejected)|";
		if($r == 3) return "{$r}-Connection refused (broker unavailable )|";        
}
 
function publish() {
        global $client;
//        echo "Mesage published:";
        return "Mesage published:";
}
 
function disconnect() {
//        echo "Disconnected|";
        return "Disconnected|";
}


function subscribe() {
	    //**Store the status to a global variable - debug purposes 
		$GLOBALS['statusmsg'] = $GLOBALS['statusmsg'] . "SUB-OK|";
}

function message($message) {
	    //**Store the status to a global variable - debug purposes
		$GLOBALS['statusmsg']  = "RX-OK|";
		
		//**Store the received message to a global variable
		$GLOBALS['rcv_message'] =  $message->payload;
}

?>
