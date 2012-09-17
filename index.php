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

if(isset($_POST["toDel"]))
	deleteTask($_POST["toDel"]);
	
if(isset($_POST["toFinish"]))
	finishTask($_POST["toFinish"]);

date_default_timezone_set("Europe/Prague");
$tasks = getTasks(false , "tasks.txt");
?>
<html>
<head>
	<meta charset="utf-8">
	<title>Task manager 1.0</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="javascript/prefixfree.min.js"></script>
	<script src="javascript/game_of_life.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
	<script>
	$(document).on("keyup",function(e){
		if(e.keyCode == 121){
			setInterval( function(){
				bg.update(1);
				bg.render();
			}, 133 )
		}
	});
	$(document).ready(function(){
		bg = new GOLBackground( document.body, 256, 256, 6, 2);
		bg.update(20);
		bg.render();
		$("#task_list").height($("#task_list").height()+250)
	});
	
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
				function parseTask($task){
					$shortname = shorten($task["name"], 22);

					echo "<div class='task indent ".($task["duration"] < 0 ? "completed" : "")."' data-date='".$task["date"]."'>";

					echo "<div class='remover' title='Odstranit úkol' onclick='trySubmit(this.children[0]);'><form method='post' action='index.php'><input type='hidden' value='".$task["name"]."' name='toDel'></form></div>";
					echo "<div class='completer' title='Dokončit úkol' onclick='this.children[0].submit();'><form method='post' action='index.php'><input type='hidden' value='".$task["name"]."' name='toFinish'></form></div>";

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

				if($tasks !== false){
					$length = 0;
					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"] && $tasks[$i]["target"] != "anyone"){
							$length++;
						}
					}

					$count = 0;

					echo "<div class='polovina'>";

					for($i=0;$i<count($tasks);$i++){
						if($tasks[$i]["target"] != $_COOKIE["user"] && $tasks[$i]["target"] != "anyone"){
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
						if($tasks[$i]["target"] == $_COOKIE["user"] || $tasks[$i]["target"] == "anyone"){
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