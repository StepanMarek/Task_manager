<?php
/*
tasks: název, datum, cíl, popis, příloha, trvání, důležitost,  tvůrce, obor - jaké části hry se toto týká
pro hezké datum : "d.m.Y G:i"
*/

require("lib.php");

if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}

date_default_timezone_set("Europe/Prague");
$tasks = getTasks(false , "tasks.txt");
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Task manager 1.0</title>
		<script>
			function zarovnat (){
				obj = document.getElementById('prazdny');
				if(obj){
					obj.style.width = 3*window.innerWidth/10;
				};
			};
		</script>
	</head>
	<body onload="zarovnat();">
		<img src="logo_simple.jpg" class="nadpis">
		<!-- Zde začíná kód pro výpis aktuálních tasks, řadí se podle data, důležitosti a osoby, ke které směřují -->
		<div class="main">
			<table>
			<tr>
			<td id="task_cell">
			<?php
			if($tasks){
				$vykresleno = false;
				for($i=0;$i<count($tasks);$i++){
					if(!isset($tasks[$i]["name"]) || $tasks[$i]["name"] == "") continue;
					if($tasks[$i]["target"] == $_COOKIE["user"]){
						echo "<div class='task' onclick=\"location.href='task.php?id=".$tasks[$i]["date"]."'\">";
						echo "<table class='zahlavi_tab'>";
						echo "<tr>";
						echo "<td class='zahlavi'>Jméno</td>";
						echo "<td class='zahlavi_val'>".$tasks[$i]["name"]."</td>";
						echo "<td class='zahlavi'>Důležitost</td>";
						echo "<td class='zahlavi_val' style='color: ".getImportanceColor($tasks[$i]["importancy"])."'>".$tasks[$i]["importancy"]."</td>";
						echo "</tr><tr>";
						echo "<td class='zahlavi'>Splnit do</td>";
						if($tasks[$i]["duration"]){echo "<td class='zahlavi_val'  style='color: ".getDeadlineColor($tasks[$i])."'>".getDeadline($tasks[$i]["duration"],$tasks[$i]["date"])."</td>";}
						else{echo "<td class='zahlavi_val'>Libovolně dlouho</td>";}
						echo "</tr></table>";
						echo $tasks[$i]["description"];
						echo "</div>";
						$vykresleno = true;
					}
				};
			}
			if(!$vykresleno){
				echo "<div id='prazdny'>Nemáte žádné úkoly</div>";
			}
			?>
			</td>
			<!-- Zde se vytváří zjednodušený seznam VŠECH aktuálních úkolů, pouze dle data vytvoření a dokončení -->
			<td style="vertical-align: top;">
				<p>
					<a href="new_task.php">Nový úkol</a>
					<a href="sklad/sklad.php">Skladiště</a>
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
				if($tasks){
					for($i=0;$i<count($tasks);$i++){
						if(!isset($tasks[$i]["name"]) || $tasks[$i]["name"] == "") continue;
							echo "<div class='seznam' onclick=\"location.href='task.php?id=".$tasks[$i]["date"]."'\">";
						if(strlen($tasks[$i]["name"]) > 10){ echo "<span style='position: relative;'>".substr($tasks[$i]["name"],0,10)." ...</span>";}
						else{ echo "<span style='position: relative;'>".$tasks[$i]["name"]."</span>";}
						if(strlen($tasks[$i]["target"]) > 10){ echo "<span style='position: absolute; left: 90px;'>".substr($tasks[$i]["target"],0,10)." ...</span>";}
						else{ echo "<span style='position: absolute; left: 90px;'>".$tasks[$i]["target"]."</span>";}
						echo "<span style='position: absolute; left: 200px;'>".$tasks[$i]["importancy"]."</span>";
						echo "<span style='position: absolute; left: 300px;'>".$tasks[$i]["creator"]."</span>";
						if(isset($tasks[$i]["domain"])){echo "<span style='position: absolute; left: 400px;'>".$tasks[$i]["domain"]."</span>";}
						else{echo "<span style='position: absolute; left: 400px;'>Neuveden</span>";}
						echo "<span style='position: absolute; left: 550px;'>".date("d.m.Y G:i",$tasks[$i]["date"])."</span>";
						if($tasks[$i]["duration"]) echo "<span style='position: absolute; left: 700px; color: ".getDeadlineColor($tasks[$i]).";'>".getDeadline($tasks[$i]["duration"],$tasks[$i]["date"])."</span>";
						else echo "<span style='position: absolute; left: 700px;'>Libovolně dlouho</span>";
						echo "</div>";
					};
				}
				?>
			</td>
			</tr>
		</div>
	</body>
</html>