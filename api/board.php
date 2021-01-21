<?php
header("Content-Type:application/json");

function doc_publish($msg, $topic) {
	$server 	= 'localhost';
	$port		= 1883;
	$keepalive	= 5;
	$user		= 'kessel1';
	$pass		= 'kessel1';

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
    response(200,"Erfolgreich",$msg);
		}catch(Mosquitto\Exception $e){
/*
				echo 'Exception';
				return;
*/
        response(400,"Exception",NULL);

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


function response($status,$status_message,$data)
{
        header("HTTP/1.1 ".$status);

        $response['status']=$status;
        $response['status_message']=$status_message;
        $response['data']=$data;

        $json_response = json_encode($response);
        echo $json_response;
}

/* ---------------------------------------------- */
//doc_publish('2&3', 'board');

if(!empty($_GET['home']) || !empty($_GET['guest']) )
	{
    $home=$_GET['home'];
    $guest=$_GET['guest'];
    $result=$home;
    $result.='&';
    $result.=$guest;
    doc_publish($result, 'board');
	// $pusher = new Pusher();
	// $pusher->call_doc('board', $doc);
	}

?>
