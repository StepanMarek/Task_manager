<?php
/*
tasks: název, datum, cíl, popis, příloha, trvání, důležitost,  tvůrce, obor - jaké části hry se toto týká
pro hezké datum : "d.m.Y G:i"
*/

require("users.php");

if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}
	
function getTasks( $rest , $tasks_file ){
	$poradi = ["name","date","target","description","attachement","duration","importancy","creator","domain"];

	$sid = fopen($tasks_file, "r");
	$pole = explode("\n",fread($sid,filesize($tasks_file)));
	$vysledek = [];
	for($i=0;$i<count($pole);$i++){
		$mezi = explode("?:", $pole[$i]);
		$asoc = [];
		for($j=0;$j<count($mezi);$j++){
			$asoc[$poradi[$j]] = $mezi[$j];
		};
		$vysledek[$i] = $asoc;
	};
	fclose($sid);
	return $vysledek;
};

function getImportanceColor ($imp){
	$pole = ["Nízká" => "#3D99FC", "Střední" => "#F3FC3D", "Vysoká" => "red"];
	if(isset($pole[$imp])) return $pole[$imp];
	else return "black";
};

date_default_timezone_set("Europe/Prague");
$tasks = getTasks(false , "tasks.txt");
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Task manager 0.5</title>
	</head>
	<body>
		<img src="logo_simple.jpg" class="nadpis">
		<!-- Zde začíná kód pro výpis aktuálních tasks, řadí se podle data, důležitosti a osoby, ke které směřují -->
		<div class="main">
			<table>
			<tr>
			<td id="task_cell">
			<?php
				for($i=0;$i<count($tasks);$i++){
					if($tasks[$i]["target"] == $_COOKIE["user"]){
						echo "<div class='task'>";
						echo "<table class='zahlavi_tab'>";
						echo "<tr>";
						echo "<td class='zahlavi'>Jméno</td>";
						echo "<td class='zahlavi_val'>".$tasks[$i]["name"]."</td>";
						echo "<td class='zahlavi'>Důležitost</td>";
						echo "<td class='zahlavi_val' style='color: ".getImportanceColor($tasks[$i]["importancy"])."'>".$tasks[$i]["importancy"]."</td>";
						echo "</tr><tr>";
						echo "<td class='zahlavi'>Do splnění</td>";
						if($tasks[$i]["duration"]){echo "<td class='zahlavi_val'>".$tasks[$i]["duration"]."</td>";}
						else{echo "<td class='zahlavi_val'>Libovolně dlouho</td>";}
						echo "</tr></table>";
						echo $tasks[$i]["description"];
						echo "</div>";
					}
				};
			?>
			</td>
			<!-- Zde se vytváří zjednodušený seznam VŠECH aktuálních úkolů, pouze dle data vytvoření a dokončení -->
			<td style="vertical-align: top;">
				<p>
					<a href="new_task.php">Nový úkol</a>
				</p>
				<div class="seznam">
					<span style='position: relative;' class="zahlavi_seznam">Jméno</span>
					<span style='position: absolute; left: 90px;' class="zahlavi_seznam">Komu</span>
					<span style='position: absolute; left: 200px;' class="zahlavi_seznam">Důležitost</span>
					<span style='position: absolute; left: 300px;' class="zahlavi_seznam">Vytvořil</span>
					<span style='position: absolute; left: 400px;' class="zahlavi_seznam">Obor</span>
					<span style='position: absolute; left: 550px;' class="zahlavi_seznam">Vytvořeno</span>
					<span style='position: absolute; left: 700px;' class="zahlavi_seznam">Splnit do</span>
				</div><?php
				for($i=0;$i<count($tasks);$i++){
					echo "<div class='seznam'>";
					if(strlen($tasks[$i]["name"]) > 10){ echo "<span style='position: relative;'>".substr($tasks[$i]["name"],0,10)." ...</span>";}
					else{ echo "<span style='position: relative;'>".$tasks[$i]["name"]."</span>";}
					if(strlen($tasks[$i]["target"]) > 10){ echo "<span style='position: absolute; left: 90px;'>".substr($tasks[$i]["target"],0,10)." ...</span>";}
					else{ echo "<span style='position: absolute; left: 90px;'>".$tasks[$i]["target"]."</span>";}
					echo "<span style='position: absolute; left: 200px;'>".$tasks[$i]["importancy"]."</span>";
					echo "<span style='position: absolute; left: 300px;'>".$tasks[$i]["creator"]."</span>";
					if(isset($tasks[$i]["domain"])){echo "<span style='position: absolute; left: 400px;'>".$tasks[$i]["domain"]."</span>";}
					else{echo "<span style='position: absolute; left: 400px;'>Neuveden</span>";}
					echo "<span style='position: absolute; left: 550px;'>".date("d.m.Y G:i",$tasks[$i]["date"])."</span>";
					if($tasks[$i]["duration"]) echo "<span style='position: absolute; left: 700px;'>".$tasks[$i]["duration"]."</span>";
					else echo "<span style='position: absolute; left: 700px;'>Libovolně dlouho</span>";
					echo "</div>";
				};
				?>
			</td>
			</tr>
		</div>
	</body>
</html>