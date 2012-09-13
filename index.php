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

if(isset($_POST["toDel"]))
	deleteTask($_POST["toDel"]);

date_default_timezone_set("Europe/Prague");
$tasks = getTasks(false , "tasks.txt");
?>
<html>
<head>
	<meta charset="utf-8">
	<!-- <link rel="stylesheet" type="text/css" href="styles.css"> -->
	<title>Task manager 1.0</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="javascript/prefixfree.min.js"></script>
	<script src="javascript/game_of_life.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
	<script>
	$(document).ready(function(){
		bg = new GOLBackground( document.body, 256, 256, 6, 2);
		bg.update(20);
		bg.render();
		// pro pohyblivé pozadí:
		// setInterval( function(){
		// 	bg.update(1);
		// 	bg.render();
		// }, 150 )

		// $("#task_list .task").on("mouseover", function(){
		// 	var load_height = $(this).height();
		// 	console.log(load_height)
		// 	$(this).css("height", load_height + 'px');
		// });
		$("#task_list").height($("#task_list").height()+250)
	})
	
	function trySubmit(form){
		if(confirm("Opravdu smazat "+form.children[0].value+"?"))
			form.submit();
	};
	</script>
</head>
<body>
<?php
include("header.php");
?>
 	<div id="task_list">
 		<h1 class="center">Přehled úkolů</h1>
		<div id="list">
			<h3>Moje úkoly:</h3>
			<?php
				function sklonuj($cislo, $varianty){
					if($cislo == 1){
						return $cislo . " " . $varianty[0];
					}
					elseif($cislo > 1 && $cislo < 5){
						return $cislo . " " . $varianty[1];
					}
					else {
						return $cislo . " " . $varianty[2];
					}
				}
				function parseDuration($duration, $date){
					if($duration < 0){
						return "Dokončeno";
					}
					$zbyva = ($duration + $date) - time();
					$output = "";

					if($zbyva < 0){
						$output .= "Vypršel";
					}
					elseif($zbyva < 60){
						$output .= sklonuj($zbyva,array("sekunda","sekundy","sekund"));
					}
					elseif($zbyva/60 < 60){
						$output .= sklonuj(round($zbyva/60),array("minuta","minuty","minut"));
					}
					elseif($zbyva/60/60 < 24){
						$output .= sklonuj(round($zbyva/60/60),array("hodina","hodiny","hodin"));
					}
					elseif($zbyva/60/60/24 < 7){
						$output .= sklonuj(round($zbyva/60/60/24),array("den", "dny", "dní"));
					}
					elseif($zbyva/60/60/24/7 < 4){
						$output .= sklonuj(round($zbyva/60/60/24/7),array("týden","týdny","týdnů"));
					}
					else {
						$output .= $zbyva;
					}
					return $output;
				}
				function parseTask($task){
					$shortname = shorten($task["name"], 18);

					echo "<div class='task indent ".($task["duration"] < 0 ? "completed" : "")."' data-date='".$task["date"]."'>";

					echo "<div class='remover' title='Odstranit úkol'><div class='remover' title='Odstranit úkol' onclick='trySubmit(this.children[0]);'><form method='post' action='index.php'><input type='hidden' value='".$task["name"]."' name='toDel'></form></div></div>";
					echo "<div class='completer' title='Dokončit úkol'></div>";

					echo "<div class='name'>" . $shortname . "</div>";
					echo "<div class='target'>Přiřazený člen: <span class='important'>".$task["target"]."</span></div>";
					if( $task["duration"] ){
						echo "<div>Zbývající čas: <span class='important'>".parseDuration($task["duration"], $task["date"])."</span></div>";
					}
					else {
						echo "<div>Zbývající čas: <span class='important'>Neomezeně</span></div>";
					}
					echo "<div>Priorita: <span class='important'>".$task["importancy"]."</span></div>";
					if($shortname != $task["name"])
						echo "<div>Celý název: <span class='important'>".$task["name"]."</span></div>";
					echo "<div>Zadavatel: <span class='important'>".$task["creator"]."</span></div>";
					if( isset($task["domain"]) ){
						echo "<div>Obor: <span class='important'>".$task["domain"]."</span></div>";
					}
					else {
						echo "<div>Obor: <span class='important'>Neuveden</span></div>";
					}
					echo "<div>Čas vytvoření: <span class='important'>".date("d.m.Y G:i",$task["date"])."</span></div>";
					echo "<div>".$task["description"]."</div>";
					echo "</div>";
				}

				if($tasks){
					$length = 0;
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"]){
							$length++;
						}
					}

					$count = 0;

					echo "<div class='polovina'>";

					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"]){
							continue;
						}
						if($count == ceil($length/2))
							echo "</div><div class='polovina'>";
						parseTask($tasks[$i]);
						$count++;
					};
					echo "</div>";
					echo "<div class='cleaner'></div>";
					if($count === 0){
						echo "<div class='indent'>Nemáte žádné úkoly.</div>";
					}
				};
			?>
			<div class="cleaner"></div>
			<h3>Ostatní úkoly:</h3>
			<?php
				if($tasks){
					$length = 0;
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"]){
							$length++;
						}
					}
					$count = 0;
					echo "<div class='polovina'>";
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] == $_COOKIE["user"]){
							continue;
						}
						if($count == ceil($length/2))
							echo "</div><div class='polovina'>";
						parseTask($tasks[$i]);
						$count++;
					};
					echo "</div>";
					echo "<div class='cleaner'></div>";
				};
			?>

		</div>
	</div>
</body>
</html>