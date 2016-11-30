<?php
header('Content-Type: application/json');
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'lib/medoo.php';

$db = json_decode(file_get_contents('python/database.json'), TRUE);
$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => $db['db'],
	'server' => 'localhost',
	'username' => $db['user'],
	'password' => $db['passwd'],
	'charset' => 'utf8'
]);
$nosho = ['file_sha1', 'configuration'];
$columns = $database->query("DESCRIBE songs")->fetchAll();
foreach ($columns as $col) {

	if (!in_array($col[0], $nosho))
		$out['columns'][] = $col[0];
}
$out['columns'][] = 'hashes';
$out['columns'][] = 'hashes/sec';
$table = $database->select("configurations", "fp_table", ['id' => $_GET['id']])[0];
$songs = $database->select("songs", "*", ['configuration' => $_GET['id']]);
foreach ($songs as $track) {
	$hashes = $database->count($table, ['song_id' => $track['song_id']]);
	$hashesperduration =  $track['duration'] ? round($hashes / $track['duration']) : '-';
	$out['songs'][] = array(
		(int)$track['song_id'],
		$track['song_name'],
		$track['fingerprinted'] == 1 ? 'yes' : 'no',
		date('d/m Y', $track['filecrdate']),
		(int)$track['duration'],
		$hashes,
		$hashesperduration
	);
}
echo json_encode($out,JSON_UNESCAPED_UNICODE);