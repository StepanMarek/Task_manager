<?php
require("lib.php");
if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}

function saveTask($post_array, $tasks_file){
	$vysledek = "?:;";
	$vysledek.=$post_array["name"]."?:";
	$vysledek.=time()."?:";
	$vysledek.=$post_array["target"]."?:";
	$vysledek.=$post_array["description"]."?:";
	if(isset($post_array["attachement"]))
		$vysledek.=$post_array["attachement"]."?:";
	else
		$vysledek.="?:";
	if($post_array["duration"] == "UNLIM")
		$vysledek.="?:";
	else
		$vysledek.=$post_array["duration"]."?:";
	$vysledek.=$post_array["importancy"]."?:";
	$vysledek.=$_COOKIE["user"]."?:";
	$vysledek.=$post_array["domain"];
	
	$sid = fopen($tasks_file, "a");
	fwrite($sid, $vysledek);
	fclose($sid);
};
$errors = [];
if(!isset($_POST["name"]) || $_POST["name"] == "")
	$errors["name"] = "Nezadali jste jméno úkolu";
else if(!isset($_POST["description"]) || $_POST["description"] == "")
	$errors["description"] = "Nenapsali jste žádný popis úkolu";
else if(!isset($_POST["target"]) || $_POST["target"] == "")
	$errors["target"] = "Nezadali jste, komu je tento úkol určen";
else{
	saveTask($_POST, "tasks.txt");
	header("Location: basic.php");
}

?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Vytvoření nového úkolu</title>
	</head>
	<body>
		<div id="new_task"><form method="post" action="new_task.php"  enctype="multipart/form-data">
		<table class="zahlavi_tab">
		<tr>
			<td class="zahlavi">
				Jméno
			</td>
			<td>
				<input type="text" name="name" onclick="this.select();" value="Město úkol">*
			</td>
		</tr>
		<tr>
			<td class="zahlavi">
				Cíl (kdo má tento úkol splnit)
			</td>
			<td>
				<input type="text" name="target" onclick="this.select();" value="Štěpán">*
			</td>
		</tr>
		<tr>
			<td class="zahlavi">
				Důležitost
			</td>
			<td>
				<select name="importancy">
					<option value="Nízká">Nízká</option>
					<option value="Střední">Střední</option>
					<option value="Vysoká">Vysoká</option>
				</select>*
			</td>
		</tr>
		<tr>
			<td class="zahlavi">
				Doba trvání (za jak dlouho by měl být úkol hotov)
			</td>
			<td>
				<input type="text" name="duration" onclick="this.select();" value="UNLIM">
				<select name="dur_input_type">
					<option value="hours">V hodinách</option>
					<option value="days">Ve dnech</option>
					<option value="months">V měsících</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="zahlavi">
				Obor
			</td>
			<td>
				<input type="text" name="domain">
			</td>
		</tr>
		<tr>
			<td class="zahlavi">
				Příloha
			</td>
			<td>
				<input type="file" name="attachement">
			</td>
		</tr>
		</table>
		<textarea class="popis" onclick="this.select();" name="description">Bez popisu</textarea>*
		<button type="submit" class="send">Vytvořit úkol</button>
		</form></div>
	</body>
</html>