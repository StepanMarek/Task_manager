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
		$(".filtering .tags").on("keyup",function(){
			if($(this).attr("value") == ""){
				$(".file").css("display","block")
				return;
			}
			var search = $(this).attr("value").split(" ");
			
			$(".file").each(function(){
				$(this).css("display","none");
				var tags = $(this).data("tags").split(" ");
				for(var i in search){
					if($.inArray(search[i], tags) >= 0){
						$(this).css("display","block");
					}
				}
			})
		})
	});
	</script>
	<title>Skladiště</title>
</head>
<body>
	<?php
	include("header.php");
	?>
	<div class="filtering">
		Filtrovat podle tagů: <input class="tags" type="text" placeholder="modely, dolni-patra, budova"><br>
		Filtrovat podle jména: <input type="text" placeholder="Jan/Hynek/Štěpán/Jirka"><br>
	</div>
	<?php 
	if($pole_zaloh){
		for($i=0;$i<count($pole_zaloh);$i++){
			if(getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"])
				$linktask = getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"];
			else $linktask = "Příloha není vázána";
			// přidat src a tags
			echo "<div class='file' data-src='". $pole_zaloh[$i]["download"] ."' data-tags='".$pole_zaloh[$i]["tags"]."'>
				<img class='icon' src='http://www.stdicon.com/".$pole_zaloh[$i]["download"]."?size=92&default=http://www.stdicon.com/application/octet-stream'>
				<span class='name'>".$pole_zaloh[$i]["name"]."</span>
				<span class='tags'><span class='important'>Tagy:</span> ".implode(", ", explode(" ", $pole_zaloh[$i]["tags"]))."</span><br>
				<span class='date'><span class='important'>Přidáno</span> ".date("d.m.Y G:i",intval($pole_zaloh[$i]["date"]))."</span>
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
					<br>
					<span><span class='important'>Důležitost:</span> ".$pole_zaloh[$i]["importancy"]."</span>
					<span><span class='important'>Úkol:</span> ".$linktask."</span><br>
					<span class='description'>".$pole_zaloh[$i]["description"]."</span>
				</div>
				</div>";
			}
	}
	else echo "nejsou k dispozici žádné materiály";
	?>
</body>
</html>