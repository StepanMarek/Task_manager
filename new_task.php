<?php
require("lib.php");
if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}

function saveTask($post_array, $tasks_file){
	$months = [31,28,31,30,31,30,31,31,30,31,30,31];
	$cas_ted = time();
	$vysledek = "?:;";
	$vysledek.=$post_array["name"]."?:";
	$vysledek.=$cas_ted."?:";
	$vysledek.=$post_array["target"]."?:";
	$vysledek.=$post_array["description"]."?:";
	if(isset($_FILES["attachement"])){
		$vysledek.=$_FILES["attachement"]["name"]."?:";
		move_uploaded_file($_FILES["attachement"]["tmp_name"],"./sklad/".$_FILES["attachement"]["name"]);
		$attach_str = "\n";
		$attach_str .= substr($_FILES["attachement"]["name"],0,strpos($_FILES["attachement"]["name"], "."))."?:";
		$attach_str .= $cas_ted."?:";
		$attach_str .= $post_array["importancy"]."?:";
		$attach_str .= $_FILES["attachement"]["type"]."?:";
		$attach_str .= $_FILES["attachement"]["name"]."?:";
		$attach_str .= "Příloha k úkolu ".$post_array["name"]."?:";
		$attach_str .= $cas_ted;
		$sid = fopen("./sklad/105110102111", "a");
		fwrite($sid, $attach_str);
		fclose($sid);
	}
	else
		$vysledek.="?:";
	if($post_array["duration"] == "UNLIM")
		$vysledek.="?:";
	else{	
		if($_POST["dur_input_type"] == "hours"){
			$vysledek.=($post_array["duration"]*3600)."?:";
		}
		if($_POST["dur_input_type"] == "days"){
			$vysledek.=($post_array["duration"]*3600*24)."?:";
		}
		if($_POST["dur_input_type"] == "months"){
			$dur = $post_array["duration"];
			$vysledny_cas = 0;
			while($dur > 12){
				$vysledny_cas += 365*24*3600;
				$dur -= 12;
			};
			$vysledny_cas += $dur*30*24*3600;
			$vysledek.=$dur."?:";
		}
		}
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