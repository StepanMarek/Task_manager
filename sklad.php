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

	<div class="file" data-src="sklad/kostel.jpg" data-tags="modely, budova, dolni-patra">
		<span class="name">Kostel</span>
		<span class="tags">Tagy: modely, budova, dolni-patra</span>
		<span class="date">Přidáno 12. 8. 2012</span>
		<span class="author">Přidal: Jan</span>
		<span class="download">Download</span>
		<div class="moreinfo">
			<span class="size">Velikost: 324kB</span>
			<span class="task">K Úkolu: Žádný</span>
			<span class="description">Popis: Model kostela, neotexturovaný</span><br>
			<span class="preview">
				<img src="http://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Kostel_Sv._Gorazda.jpg/170px-Kostel_Sv._Gorazda.jpg"></img>
			</span>
		</div>
	</div>
	
<br><br><br>
	<div style="width: 99%;position: absolute; left: 0.5%;">
		<?php 
		if($pole_zaloh){
			for($i=0;$i<count($pole_zaloh);$i++){
				if(getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"])
					$linktask = getTask($pole_zaloh[$i]["link"], "tasks.txt")["name"];
				else $linktask = "Příloha není vázána";
				echo "<div class=\"seznam\">
					<span style='position: relative;'>".$pole_zaloh[$i]["name"]."</span>
					<span style='position: absolute; left: 120px;'>".$linktask."</span>
					<span style='position: absolute; left: 300px;'>".$pole_zaloh[$i]["importancy"]."</span>
					<span style='position: absolute; left: 400px;'>".shorten($pole_zaloh[$i]["type"], 29)."</span>
					<span style='position: absolute; left: 650px;'>";
			
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
					<span style='position: absolute; left: 750px;'>".shorten($pole_zaloh[$i]["description"],22)."</span>
					<span style='position: absolute; left: 1000px;'>".date("d.m.Y G:i",$pole_zaloh[$i]["date"])."</span>
					</div>";
				}
		}
		else echo "nejsou k dispozici žádné materiály";
			?>
	</div>
	</body>
</html>