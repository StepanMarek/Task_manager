<?php
require("../lib.php");
if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: ../login.php");
	exit;
}

date_default_timezone_set("Europe/Prague");
$pole_zaloh = getDirArray("105110102111", false);
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="../styles.css">
		<title>Skladiště</title>
	</head>
	<body>
	<div style="width: 99%;position: absolute; left: 0.5%;">
		<div class="seznam">
			<span style='position: relative;' class="zahlavi_seznam">Jméno</span>
			<span style='position: absolute; left: 120px;' class="zahlavi_seznam">K Úkolu</span>
			<span style='position: absolute; left: 250px;' class="zahlavi_seznam">Důležitost</span>
			<span style='position: absolute; left: 350px;' class="zahlavi_seznam">Typ</span>
			<span style='position: absolute; left: 400px;' class="zahlavi_seznam">Stáhnout/Odkaz na soubor</span>
			<span style='position: absolute; left: 600px;' class="zahlavi_seznam">Popis</span>
			<span style='position: absolute; left: 700px;' class="zahlavi_seznam">Vytvořeno</span>
		</div>
		<div class="seznam"><?php 
		for($i=0;$i<count($pole_zaloh);$i++){
			if(getTask($pole_zaloh[$i]["link"], "../tasks.txt")["name"])
				$linktask = getTask($pole_zaloh[$i]["link"], "../tasks.txt")["name"];
			else $linktask = "Příloha není vázána";
			echo "
				<span style='position: relative;'>".$pole_zaloh[$i]["name"]."</span>
				<span style='position: absolute; left: 120px;'>".$linktask."</span>
				<span style='position: absolute; left: 250px;'>".$pole_zaloh[$i]["importancy"]."</span>
				<span style='position: absolute; left: 350px;'>".$pole_zaloh[$i]["type"]."</span>
				<span style='position: absolute; left: 400px;'>";
			
			if(substr($pole_zaloh[$i]["download"],0,7) != "http://"){
				echo	"<form method='post' action='../vec.php'>
						<input type='hidden' value='".$pole_zaloh[$i]["download"]."' name='id'>
						<button type='submit' class='odkaz'>Stáhnout</button>
					</form>";
			}
			else{
				echo "<a href='".$pole_zaloh[$i]["download"]."'>Odkaz</a>";
			}
				
			echo	"</span>
				<span style='position: absolute; left: 600px;'>".$pole_zaloh[$i]["description"]."</span>
				<span style='position: absolute; left: 700px;'>".date("d.m.Y G:i",$pole_zaloh[$i]["date"])."</span>";
			}
			?>
		</div>
	</div>
	</body>
</html>