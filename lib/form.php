<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);
define('IMPORTSCRIPT', escapeshellcmd('/usr/bin/python /var/www/addremoveradmin/python/importAdds.py'));
define('ADDSFOLDER', '/mnt/Beijing/AddRemoverFiles/Reklamer/');
require_once 'medoo.php';
$db = json_decode(file_get_contents('python/database.json'), TRUE);

$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => $db['db'],
	'server' => 'localhost',
	'username' => $db['user'],
	'password' => $db['passwd'],
	'charset' => 'utf8'
]);
if ($_POST) {
	switch ($_POST['submit']) {
		case 'Update':
			$database->insert("states", [
				"date" => time(),
				"active" => $_POST['active'],
			]);
			$database->query("DELETE from configurations WHERE id IN(" . implode(',', array_values($_POST['del'])) . ")");
			break;
		case 'Import':
			$database->insert("configurations", [
				"name" => $_POST['name'],
				"date" => time(),
				"folder" => $_POST['folder'],
				"samplerate" => $_POST['samplerate'],
				"window_size" => $_POST['window_size'],
				"overlap_ratio" => $_POST['overlap_ratio'],
				"fan_value" => $_POST['fan_value'],
				"neighborhood_size" => $_POST['neighborhood_size'],
				"min_hash" => $_POST['min_hash'],
				"max_hash" => $_POST['max_hash'],
				"fingerprint_redux" => $_POST['fingerprint_redux']
			]);

			echo 'Running import script '.IMPORTSCRIPT.PHP_EOL;
			ob_start();
			passthru(IMPORTSCRIPT);
			$output = ob_get_clean(); 
			echo $output;
	#		$output = shell_exec(IMPORTSCRIPT);
			break;
	}
}

$columns = $database->query("DESCRIBE configurations")->fetchAll();
$active = $database->query("SELECT active FROM states ORDER BY id DESC")->fetch();
$configs = $database->select("configurations", "*");

$channels = glob(ADDSFOLDER . '*');
foreach ($channels as $channel) {
	$dates = glob($channel . '/*', GLOB_ONLYDIR);
	$channel = end(explode("/", $channel));
	foreach ($dates as $period) {
		$c[$channel][] = $period;
	}
}
