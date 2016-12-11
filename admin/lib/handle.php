<?php

header('Content-Type: application/json');

ini_set('display_errors', 1);
error_reporting(E_ERROR);
define('IMPORTSCRIPT', escapeshellcmd('/usr/bin/python /var/www/addremoveradmin/python/importAdds.py'));
define('ADDSFOLDER', '/mnt/Beijing/AddRemoverFiles/Reklamer/');
require_once 'medoo.php';
$db = json_decode(file_get_contents('database.json'), TRUE);

$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => $db['db'],
	'server' => 'localhost',
	'username' => $db['user'],
	'password' => $db['passwd'],
	'charset' => 'utf8'
]);


switch ($_GET['action']) {

	case 'add':
		$_POST = $_POST['add'];
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
		echo json_encode($_POST);
		break;

		case 'show':
			$nosho = ['file_sha1', 'configuration'];
			$columns = $database->query("DESCRIBE songs")->fetchAll();
			foreach ($columns as $col) {
				if (!in_array($col[0], $nosho))
					$out['columns'][] = $col[0];
			}
			$out['columns'][] = 'hashes';
			$out['columns'][] = 'hashes/sec';
			$table = $database->select("configurations", "fp_table", ['id' => $_POST['id']])[0];
			$songs = $database->select("songs", "*", ['configuration' => $_POST['id']]);
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
			break;

			case 'remove':
				echo json_encode($_POST['del']);
				#$database->query("DELETE from configurations WHERE id IN(" . implode(',', array_values($_POST['del'])) . ")");
			break;

			case 'update':
				$database->insert("states", [
					"date" => time(),
					"active" => $_POST['active'],
				]);
			break;

			case 'folders':
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
			echo json_encode($c,JSON_UNESCAPED_UNICODE);
			break;

			case 'sessions':
				$columns = $database->query("DESCRIBE sessions")->fetchAll();
				$sessions = $database->select("sessions", "*", ['ORDER' => 'id DESC', 'LIMIT' => 5]);
				foreach ($columns as $i => $col) {
					if (!in_array($col[0], $nosho))
								$out['columns'][] = $col[0];
				}

				foreach ($sessions as $session) {
						$out['rows'][] = $session;
				}

				echo json_encode($out,JSON_UNESCAPED_UNICODE);
			break;


			case 'events':
				$columns = $database->query("DESCRIBE eventlog")->fetchAll();
				$sessions = $database->select("eventlog", "*", ['session' => $_POST['id']]);
				foreach ($columns as $i => $col) {
					if (!in_array($col[0], $nosho))
								$out['columns'][] = $col[0];
				}
				foreach ($sessions as $session) {
						$out['rows'][] = $session;
				}
				echo json_encode($out,JSON_UNESCAPED_UNICODE);
			break;

			default:
			break;
}
