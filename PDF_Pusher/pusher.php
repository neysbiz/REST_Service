<?php
header("Content-Type:application/json");
require("../api/data.php");

class pusher
	{
	// variables
	//	Code generation
		private static $min = 1000;
		private static $max = 9999;

	// document path
		private static $path = '../PDF/'; //ggf. Netzwerkfreigabe auf Fileserver

	//	MQTT
	//	private $topic = 'kessel3';

	//constructor
	function pusher() {

		}

	function call_doc($topic, $doc) {


//if(!empty($_GET['doc']))
//	{
//	$doc=$_GET['doc'];

	//check if document exists
	$filename = self::$path; //$path;
	$filename .= strtolower($doc.'.pdf');

	if(file_exists( $filename )) {
		$code = random_int(self::$min,self::$max);
		$msg = $doc."&".$code;
		doc_publish($msg, $topic);
		$this->response(200,"Document ".$filename." Found",$code);

		}
		else
		{
		$this->response(200,"Document Not Found",NULL);
		}
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
}

?>
