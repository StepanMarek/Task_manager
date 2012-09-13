<?php
require("lib.php");
if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
}

if(isset($_POST["id"])){
	if(puvodni($_POST["id"])){
		header("Content-Disposition: attachement; filename='".$_POST["id"]."'");
		readfile("./sklad/".$_POST["id"]);
	}
};

date_default_timezone_set("Europe/Prague");
$pole_zaloh = getDirArray("sklad/105110102111", false);
?>
<html>
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script src="javascript/prefixfree.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
	<script src="./javascript/game_of_life.js"></script>
	<script>
	$(document).ready(function(){
		bg = new GOLBackground( document.body, 256, 256, 6, 2);
		bg.update(20);
		bg.render();

		// rozbalovací files
		$(".file").on("click",function(){
			var moreinfo = $(this).children(".moreinfo")
			if( moreinfo.css("display") == "none" ){
				moreinfo.css("display", "block");
			}
			else {
				moreinfo.css("display", "none");
			}
		})
	})
	</script>
	<title>Skladiště</title>
</head>
<body>
	<?php
	include("header.php");
	?>
	<form>
		Filtrovat podle tagů: <input type="text" placeholder="modely, dolni-patra, budova">
		<input type="submit" value="Filtrovat">
	</form>
	<?php 
	if($pole_zaloh){
		for($i=0;$i<count($pole_zaloh);$i++){
			if(getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"])
				$linktask = getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"];
			else $linktask = "Příloha není vázána";
			// přidat src a tags
			echo "<div class='file' data-src='". $pole_zaloh[$i]["download"] ."' data-tags=''>
				<span class='name'>".$pole_zaloh[$i]["name"]."</span>
				<span class='tags'>Tagy: ".implode(", ", explode(" ", $pole_zaloh[$i]["tags"]))."</span>
				<span class='date'>Přidáno ".date("d.m.Y G:i",$pole_zaloh[$i]["date"])."</span>
				<span class='download'>";
		
			if(substr($pole_zaloh[$i]["download"],0,7) != "http://"){
				echo "<form method='post' action='sklad.php'>
					<input type='hidden' value='".$pole_zaloh[$i]["download"]."' name='id'>
					<input type='submit' class='odkaz' value='Stáhnout'>
				</form>";
			}
			else{
				echo "<a href='".$pole_zaloh[$i]["download"]."'>Odkaz</a>";
			}
			echo "</span>";
			echo	"
				<div class='moreinfo'>
					<span>Důležitost: ".$pole_zaloh[$i]["importancy"]."</span>
					<span>Úkol: ".$linktask."</span>
					<span class='type'>".$pole_zaloh[$i]["type"]."</span>
					<span class='description'>".$pole_zaloh[$i]["description"]."</span>
				</div>
				</div>";
			}
	}
	else echo "nejsou k dispozici žádné materiály";
	?>
</body>
</html>