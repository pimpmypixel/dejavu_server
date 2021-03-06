<?php
require_once 'lib/medoo.php';
define('ADDSFOLDER', '/mnt/Beijing/AddRemoverFiles/Reklamer/');
$db = json_decode(file_get_contents('lib/database.json'), TRUE);

$database = new medoo([
	'database_type' => 'mysql',
	'database_name' => $db['db'],
	'server' => 'localhost',
	'username' => $db['user'],
	'password' => $db['passwd'],
	'charset' => 'utf8'
]);

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
	<title>Commercials DB Admin</title>
	<link rel="stylesheet" type="text/css" href="lib/style.css">
	<script src="lib/jquery.js"></script>
	<script src="lib/js.js"></script>
</head>
<body id="main_body">
<div id="form_container">
		<ul>
			<li id="li_0">

				<div>
					<a href="form.php">Refresh</a>
				</div>
			</li>
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
					</select>
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
				<button id="saveForm" onclick="javascript:void(0)" class="button_text">Import</button>
			</li>
		</ul>
</div>
<div id="list">
		<table border="0">
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
					echo '<tr class="config" id="' . $config['id'] . '">';
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
					echo '<td class="del"><input class="delbox" name="del[]" value="' . $config['id'] . '" type="checkbox"></td>' . PHP_EOL;
					echo '</tr>' . PHP_EOL;
				}
				?>
			</tr>
			<tr>
				<td colspan="13" style="text-align: right">
					<input class="button_text" type="submit" name="submit" value="Update"/></td>
				<td colspan="2" style="text-align: right">
					<button class="button_text" id="delete" onClick="javascript:void(0)">X</button></td>
			</tr>
			<tr>
				<td colspan="15"><?= $output ?></td>
			</tr>
		</table>
</div>
<div id="songs">
	<table border="0" cellpadding="5"></table>
</div>
<div id="sessions">
	<table border="0" cellpadding="5"></table>
</div>
</body>
</html>
