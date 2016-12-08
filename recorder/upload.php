<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);
$destination = '/mnt/Beijing/AddRemoverFiles/Sessions/';
$importer = '/var/www/adkiller/admin/python/importUpload.py ';

require_once '../admin/lib/medoo.php';
$db = json_decode(file_get_contents('../admin/lib/database.json'), TRUE);

$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => $db['db'],
	'server' => 'localhost',
	'username' => $db['user'],
	'password' => $db['passwd'],
	'charset' => 'utf8'
]);

if(!is_dir("recordings")){
	$res = mkdir("recordings",0777);
}

// pull the raw binary data from the POST array
$data = substr($_POST['data'], strpos($_POST['data'], ",") + 1);
// decode it
$decodedData = base64_decode($data);
$filename = date( 'Y-m-d-H-i-s' ) ;
$md5filename = md5($filename) ;
$filepath = $destination.'recordings/'.$md5filename.'.mp3';
$fp = fopen($filepath, 'wb');
fwrite($fp, $decodedData);
fclose($fp);

$database->insert("uploads", [
	"date" => time(),
	"file" => $md5filename,
]);
echo is_file($filepath)?'Uploaded'.PHP_EOL:''.PHP_EOL;

$cmd = "/usr/bin/python ".$importer.$filepath." ".$md5filename;
#echo $cmd.PHP_EOL;
echo passthru($cmd).PHP_EOL;
?>
