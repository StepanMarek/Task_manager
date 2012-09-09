<?php
require("lib.php");
if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}

if(isset($_POST["id"])){
	if(puvodni($_POST["id"]))
		header("Content-Disposition: attachement; filename='".$_POST["id"]."'");
		readfile($_POST["id"]);
};

date_default_timezone_set("Europe/Prague");
$pole_zaloh = getDirArray("sklad/105110102111", false);
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<script src="javascript/prefixfree.min.js"></script>
		<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
		<title>Skladiště</title>
	</head>
	<body>
<?php
include("header.php");
?>
	<div style="width: 99%;position: absolute; left: 0.5%;">
		<div class="seznam">
			<span style='position: relative;' class="zahlavi_seznam">Jméno</span>
			<span style='position: absolute; left: 120px;' class="zahlavi_seznam">K Úkolu</span>
			<span style='position: absolute; left: 250px;' class="zahlavi_seznam">Důležitost</span>
			<span style='position: absolute; left: 350px;' class="zahlavi_seznam">Typ</span>
			<span style='position: absolute; left: 550px;' class="zahlavi_seznam">Stáhnout/Odkaz na soubor</span>
			<span style='position: absolute; left: 750px;' class="zahlavi_seznam">Popis</span>
			<span style='position: absolute; left: 1000px;' class="zahlavi_seznam">Vytvořeno</span>
		</div>
		<?php 
		if($pole_zaloh){
			for($i=0;$i<count($pole_zaloh);$i++){
				if(getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"])
					$linktask = getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"];
				else $linktask = "Příloha není vázána";
				echo "<div class=\"seznam\">
					<span style='position: relative;'>".$pole_zaloh[$i]["name"]."</span>
					<span style='position: absolute; left: 120px;'>".$linktask."</span>
					<span style='position: absolute; left: 250px;'>".$pole_zaloh[$i]["importancy"]."</span>
					<span style='position: absolute; left: 350px;'>".$pole_zaloh[$i]["type"]."</span>
					<span style='position: absolute; left: 550px;'>";
			
				if(substr($pole_zaloh[$i]["download"],0,7) != "http://"){
					echo	"<form method='post' action='sklad.php'>
						<input type='hidden' value='".$pole_zaloh[$i]["download"]."' name='id'>
						<button type='submit' class='odkaz'>Stáhnout</button>
					</form>";
				}
				else{
					echo "<a href='".$pole_zaloh[$i]["download"]."'>Odkaz</a>";
				}
				
				echo	"</span>
					<span style='position: absolute; left: 750px;'>".$pole_zaloh[$i]["description"]."</span>
					<span style='position: absolute; left: 1000px;'>".date("d.m.Y G:i",$pole_zaloh[$i]["date"])."</span>
					</div>";
				}
		}
		else echo "nejsou k dispozici žádné materiály";
			?>
	</div>
	</body>
</html>