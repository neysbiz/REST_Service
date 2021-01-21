<?php
require 'vendor/autoload.php';
$host = 'localhost';
$port = '8086';
$dbname = 'Project1';
$user = 'mqtt';
$pass = 'mqtt';

function randFloat($min, $max)
{
    $range = $max - $min;
    $num = $min + $range * mt_rand(0, 32767) / 32767;
    $num = round($num, 4);
    return (double) $num;
}

// directly get the database object
$database = InfluxDB\Client::fromDSN(sprintf('influxdb://mqtt:mqtt@%s:%s/%s', $host, $port, $dbname));
$client = $database->getClient();
//$database = $client->selectDB('Project1');
if($database->exists()) {
	echo 'db vorhanden';
}
// get the client to retrieve other databases
$result = $database->query('select * from flags');
$points = $result->getPoints();
echo"</br>";
//var_dump($points);
echo json_encode($points);

$start = microtime(true);
$countries = ['nl', 'gb', 'us', 'be', 'th', 'jp', 'nl', 'sa'];
$colors = ['orange', 'black', 'yellow', 'white', 'red', 'purple'];
$points = [];
for ($i = 0; $i < 10; $i++) {
    $points[] = new \InfluxDB\Point('flags', randFloat(1, 999), ['country' => $countries[array_rand($countries)]], ['color' => $colors[array_rand($colors)]], (int) shell_exec('date +%s%N') + mt_rand(1, 1000));
}

echo"</br>";
//var_dump($points);

/*
$points = [
	new Point(
//		'465',
//		'Heim',
//		['player' => '23', 'act' => 'penalty'],
//		exec('date +%s%N') // this will produce a nanosecond timestamp on Linux ONLY
	)
];
*/

// now just write your points like you normally would
//$result = $database->writePoints($points);

?>
