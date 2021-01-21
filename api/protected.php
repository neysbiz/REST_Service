<?php
include_once '../config/database.php';
include '../PDF_Pusher/pusher.php';
require "../vendor/autoload.php";
use \Firebase\JWT\JWT;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");


//$secret_key = "YOUR_SECRET_KEY";
$privateKey = file_get_contents('../mykey.pem');
$jwt = null;
//$jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJUSEVfSVNTVUVSIiwiYXVkIjoiVEhFX0FVRElFTkNFIiwiaWF0IjoxNjA5NTg4Njk0LCJuYmYiOjE2MDk1ODg3MDQsImV4cCI6MTYwOTU4ODc1NCwiZGF0YSI6eyJpZCI6IjIiLCJmaXJzdG5hbWUiOiJBbmRyZWFzIiwibGFzdG5hbWUiOiJOZXkiLCJlbWFpbCI6InRlc3QxQHRlc3QuY29tIn19._i_v4gVRIKIjYkBMXW84eXaIaefPVsQNh-IaxRUluVM';
$databaseService = new DatabaseService();
$conn = $databaseService->getConnection();

$data = json_decode(file_get_contents("php://input"));
/*
$header = apache_request_headers();
echo $header['Authorization'];
echo $_SERVER['PHP_AUTH_USER'];
*/

$authHeader = $_SERVER['HTTP_AUTHORIZATION'];
/*
foreach ($data as $header => $value) {
	json_encode(array(
		$header => $value
));
}
*/
/* -------------- */
$arr = explode(" ", $authHeader);


/*
echo json_encode(array(
    "message" => "sd" .$arr[1]
));
*/

$jwt = $arr[1];
if($jwt){

    try {

        $decoded = JWT::decode($jwt, $privateKey, array('HS256'));

        // Access is granted. Add code of the operation here

				if(!empty($_GET['doc']))
					{
					$doc=$_GET['doc'];

					$pusher = new Pusher();
					$pusher->call_doc('kessel3', $doc);
					}
/*
        echo json_encode(array(
            "message" => "Access granted:",
//            "error" => $e->getMessage()
        ));
*/
    }catch (Exception $e){

    http_response_code(401);

    echo json_encode(array(
        "message" => "Access denied.",
        "error" => $e->getMessage()
    ));
}

}
?>
