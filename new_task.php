<?php
require("lib.php");
if(isset($_COOKIE["user"])){
	if(!logged($_COOKIE["user"])){
		header("Location: login.php");
		prodlouzit("id");
		exit;
	}
}
else {
	header("Location: login.php");
	exit;
}

function saveTask($post_array, $tasks_file){
	$months = [31,28,31,30,31,30,31,31,30,31,30,31];
	$cas_ted = time();
	if(filesize($tasks_file) > 0)
		$vysledek = "?:;";
	$vysledek.=$post_array["name"]."?:";
	$vysledek.=$cas_ted."?:";
	$vysledek.=$post_array["target"]."?:";
	$vysledek.=$post_array["description"]."?:";
	if(isset($_FILES["attachement"]) && $_FILES["attachement"]["size"] > 0){
		$vysledek.=$_FILES["attachement"]["name"]."?:";
		move_uploaded_file($_FILES["attachement"]["tmp_name"],"./sklad/".$_FILES["attachement"]["name"]);
		if(filesize("./sklad/105110102111") > 0)
			$attach_str = "\n";
		$attach_str .= substr($_FILES["attachement"]["name"],0,strpos($_FILES["attachement"]["name"], "."))."?:";
		$attach_str .= $cas_ted."?:";
		$attach_str .= $post_array["importancy"]."?:";
		$attach_str .= $_FILES["attachement"]["type"]."?:";
		$attach_str .= $_FILES["attachement"]["name"]."?:";
		$attach_str .= "Příloha k úkolu ".$post_array["name"]."?:";
		$attach_str .= $post_array["tags"]."?:";
		$attach_str .= $cas_ted;
		$sid = fopen("./sklad/105110102111", "a");
		fwrite($sid, $attach_str);
		fclose($sid);
	}
	else
		$vysledek.="?:";
	if($post_array["duration"] == ""){
		$vysledek.="?:";
	}
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
	header("Location: index.php");
}

?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Vytvoření nového úkolu</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="javascript/game_of_life.js"></script>
	<script src="javascript/prefixfree.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
	<script>
	function generate_name(dom){
		var slovesa = ["uvařit", "naprogramovat", "vymodelovat", "postavit", "nakreslit", "napsat", "vygenerovat", "přečíst"];
		var jmena = ["vajíčka", "engine", "hospodu", "mrakodrap", "Zanea", "dialogy", "spodní patra", "questy"];

		var quote = slovesa[Math.floor(slovesa.length*Math.random())] + " " + jmena[Math.floor(jmena.length*Math.random())];
		dom.placeholder = quote;
	}
	function generate_obor(dom){
		var obors = ["programování", "psaní", "kreslení", "texturování", "modelování", "skriptování", "design"];
		var quote = obors[Math.floor(obors.length*Math.random())];
		dom.placeholder = quote;
	}
	function zobrazitTagy(){
		var tr = document.getElementById("mistoProTagy");
		var td1 = document.createElement("td");
		var td2 = document.createElement("td");
		var input = document.createElement("input");
		td1.className = "zahlavi";
		td1.innerHTML = "Tagy";
		input.type = "text";
		input.className = "long";
		input.name = "tags";
		input.placeholder = "oddělujte mezerami, např: animace třída questy gui apod.";
		td2.appendChild(input);
		tr.appendChild(td1);
		tr.appendChild(td2);
	};
	
	var bg;
	$(document).on("keyup",function(e){
		if(e.keyCode == 121){
			setInterval( function(){
				bg.update(1);
				bg.render();
			}, 133 )
		}
	});
	$(document).ready(function(){
		generate_name( document.getElementById("jmeno_ukolu") );
		generate_obor( document.getElementById("obor") );

		bg = new GOLBackground( document.body, 256, 256, 6, 2);
		bg.update(20);
		bg.render();
		// pro pohyblivé pozadí:
		// setInterval( function(){
		// 	bg.update(1);
		// 	bg.render();
		// }, 150 )

		// u všech textových inputů dá tenhle eventhandler
		$("input[type='text'], textarea").on("click",function(){
			this.select();
		})
	})
	</script>
</head>
<body>
<?php
include("header.php");
?>
	<div id="new_task">
		<h1 class="center">Nový úkol</h1>
		<form method="post" action="new_task.php" enctype="multipart/form-data">
		<table>
			<tr>
				<td class="zahlavi important">
					Jméno úkolu
				</td>
				<td>
					<input required class="long" type="text" id="jmeno_ukolu" name="name" placeholder="Uvařit vajíčka">
				</td>
			</tr>
			<tr>
				<td class="zahlavi important">
					Přiřazený člen
				</td>
				<td>
					<select name="target">
						<option value="anyone">Kdokoli</option>
						<option value="Štěpán">Štěpán</option>
						<option value="Jirka">Jirka</option>
						<option value="Hynek">Hynek</option>
						<option value="Jan">Jan</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="zahlavi important">
					Důležitost
				</td>
				<td>
					<select name="importancy">
						<option value="Nízká">Nízká</option>
						<option value="Střední">Střední</option>
						<option value="Vysoká">Vysoká</option>
					</select>
				</td>
			</tr>
			<tr>
				<td class="zahlavi">
					Konečný termín
				</td>
				<td>
					<input type="text" name="duration" placeholder="žádný">
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
					<input type="text" class="long" name="domain" id="obor">
				</td>
			</tr>
			<tr>
				<td class="zahlavi">
					Příloha
				</td>
				<td>
					<input type="file" name="attachement" onchange="zobrazitTagy();">
				</td>
			</tr>
			<tr id="mistoProTagy">
			</tr>
			<tr>
				<td colspan="2" class="center">
					<textarea required class="popis" name="description" placeholder="Popis úkolu"></textarea>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" value="Vytvořit úkol" class="send"></input>
				</td>
			</tr>
		</table>
		
		</form>
	</div>
	
</body>
</html>
