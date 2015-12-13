<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);
define('IMPORTSCRIPT', escapeshellcmd('/usr/bin/python /var/www/addremoveradmin/python/importAdds.py'));
define('ADDSFOLDER', '/mnt/Beijing/AddRemoverFiles/Reklamer/');
require_once 'lib/medoo.php';

$db = json_decode(file_get_contents('python/database.json'),TRUE);

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

			$output = shell_exec(IMPORTSCRIPT);
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
?><!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Untitled Form</title>
	<style>
		body {

			font-size: 12px;
		}

		label {
			text-transform: capitalize;
		}

		ul {
			list-style: none;
		}

		#form_container {
			width: 15%;
			float: left;
		}

		#list {
			width: 80%;
			float: right;
		}
	</style>
</head>
<body id="main_body">
<div id="form_container">
	<form id="add" method="post" action="">
		<ul>
			<li id="li_1">
				<label class="description" for="element_1">Navn</label>

				<div>
					<input id="name" name="name" class="element text medium" type="text" maxlength="255"
						   value=""/>
				</div>
			</li>
			<li id="li_11">
				<label class="description" for="element_11">mp3 Folder </label>

				<div>
					<select class="element select medium" id="folder" name="folder">
						<option value="" selected="selected"></option>
						<?php
						foreach ($c as $channel => $periods) {
							echo '<optgroup label="' . $channel . '">' . PHP_EOL;
							foreach ($periods as $period) {
								echo '<option value="' . $period . '">' . end(explode("/", $period)) . '</option>' . PHP_EOL;
							}
							echo '</optgroup>';
						}
						?>
					</select>
				</div>
			</li>
			<li id="li_2">
				<label class="description" for="element_2">database name </label>

				<div><!--
					<input id="dbname" name="dbname" class="element text medium" type="text" maxlength="255"
						   value=""/>-->
				</div>
			</li>
			<li id="li_3">
				<label class="description" for="element_3">Sample Rate</label>

				<div>
					<select class="element select medium" id="samplerate" name="samplerate">
						<option>48000</option>
						<option selected>44100</option>
						<option>22050</option>
						<option>11025</option>
					</select>
				</div>
			</li>
			<li id="li_4">
				<label class="description" for="element_4">window size </label>

				<div>
					<select class="element select medium" id="window_size" name="window_size">
						<option>16384</option>
						<option>8192</option>
						<option selected>4096</option>
						<option>2048</option>
						<option>1024</option>
						<option>512</option>
						<option>256</option>
					</select>
				</div>
			</li>
			<li id="li_5">
				<label class="description" for="element_5">overlap ratio</label>

				<div>
					<input id="overlap_ratio" size="5" name="overlap_ratio" class="element text small" type="text"
						   maxlength="5"
						   value="0.5"/>
				</div>
			</li>
			<li id="li_6">
				<label class="description" for="element_6">fan value </label>

				<div>
					<input id="fan_value" size="5" name="fan_value" class="element text small" type="number"
						   value="20"/>
				</div>
			</li>
			<li id="li_7">
				<label class="description" for="element_7">Neighborhood size </label>

				<div>
					<input id="neighborhood_size" name="neighborhood_size" class="element text small" type="number"
						   size="5"
						   value="20"/>
				</div>
			</li>
			<li id="li_8">
				<label class="description" for="element_8">min_hash_time_delta </label>

				<div>
					<input id="min_hash" name="min_hash" class="element text small" type="number" size="5"
						   value="0"/>
				</div>
			</li>
			<li id="li_9">
				<label class="description" for="element_9">max_hash_time_delta </label>

				<div>
					<input id="max_hash" name="max_hash" class="element text small" type="number" size="5"
						   value="200"/>
				</div>
			</li>
			<li id="li_10">
				<label class="description" for="element_10">fingerprint_reduction </label>

				<div>
					<input id="fingerprint_redux" name="fingerprint_redux" class="element text small" type="number"
						   maxlength="255"
						   value="20"/>
				</div>
			</li>
			<li class="buttons">
				<input id="saveForm" class="button_text" type="submit" name="submit" value="Import"/>
			</li>
		</ul>
	</form>
</div>
<div id="list">
	<form id="update" method="post" action="">
		<table border="1">
			<tr>
				<?php
				foreach ($columns as $header) {
					echo '<th';
					echo $header[0] == 'name' ? ' width=100' : '';
					echo '>' . $header[0] . '</th>';
				}
				echo '<th>Active</th>';
				echo '</tr>';

				foreach ($configs as $config) {
					echo '<tr>';
					foreach ($config as $i => $col) {
						$val = $col;
						if ($i == 'date') {
							$val = date("d/m H:i", $val);
						}
						if ($i == 'folder') {
							$val = str_ireplace(ADDSFOLDER, '', $val);
						}
						echo '<td nowrap align=center>' . $val . '</td>' . PHP_EOL;
					}
					echo '<td><input name="active" value="' . $config['id'] . '" type="radio" ' . ($config['id'] == $active[0] ? 'checked' : '') . '></td>' . PHP_EOL;
					echo '</tr>' . PHP_EOL;
				}
				?>
			</tr>
			<tr>
				<td colspan="13" style="text-align: right">
					<input class="button_text" type="submit" name="submit" value="Update"/></td>
			</tr>
			<tr>
				<td colspan="13"><?= $output ?></td>
			</tr>
		</table>
	</form>
</div>
</body>
</html>