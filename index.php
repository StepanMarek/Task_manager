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
	})
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
					$zbyva = ($duration + $date) - time();
					$output = "";
					// return "do "  . $zbyva;
					if($zbyva < 60){
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
					echo "<div class='task indent' data-date='".$task["date"]."'>";
					echo "<div class='name'>" . shorten($task["name"], 25) . "</div>";
					echo "<div class='target'>Přiřazený člen: <span class='important'>".$task["target"]."</span></div>";
					if( $task["duration"] ){
						echo "<div>Zbývající čas: <span class='important'>".parseDuration($task["duration"], $task["date"])."</span></div>";
						// style='color: ".getdeadlineColor($task).";'
					}
					else {
						echo "<div>Zbývající čas: <span class='important'>Neomezeně</span></div>";
					}
					echo "<div>Priorita: <span class='important'>".$task["importancy"]."</span></div>";
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
					$count = 0;
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"]){
							continue;
						}
						parseTask($tasks[$i]);
						$count++;
					};
					if($count === 0){
						echo "<div class='indent'>Nemáte žádné úkoly.</div>";
					}
				};
			?>
			<h3>Ostatní úkoly:</h3>
			<?php
				if($tasks){
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] == $_COOKIE["user"]){
							continue;
						}
						parseTask($tasks[$i]);
					};
				};
			?>
		</table>
	</div>
</body>
</html>