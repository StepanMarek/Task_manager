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

$cas_ted = time();
$post_array = $_POST;
if(isset($_FILES["attachement"]) && $_FILES["attachement"]["size"] > 0){
		$vysledek = "";
		$vysledek.=$_FILES["attachement"]["name"]."?:";
		move_uploaded_file($_FILES["attachement"]["tmp_name"],"./sklad/".$_FILES["attachement"]["name"]);
		$attach_st = "";
		if(filesize("./sklad/105110102111") > 0)
			$attach_str = "\n";
		$attach_str .= substr($_FILES["attachement"]["name"],0,strpos($_FILES["attachement"]["name"], "."))."?:";
		$attach_str .= "?:";
		$attach_str .= $post_array["importancy"]."?:";
		$attach_str .= $_FILES["attachement"]["type"]."?:";
		$attach_str .= $_FILES["attachement"]["name"]."?:";
		$attach_str .= $post_array["description"]."?:";
		$attach_str .= $post_array["tags"]."?:";
		$attach_str .= $cas_ted;
		$sid = fopen("./sklad/105110102111", "a");
		fwrite($sid, $attach_str);
		fclose($sid);
	}
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<title>Nový soubor</title>
		<script src="javascript/prefixfree.min.js"></script>
		<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
		<script src="javascript/game_of_life.js"></script>
		<script>
		var bg;
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
			$("input[type='text'], textarea").on("click",function(){
				this.select();
			})
		})
		</script>
	</head>
	<body onload="document.getElementById('new_task').style.left = 0;">
	<?php
		include("header.php");
	?>
	<div id="new_task">
		<h1 class="center">Nahrát soubor</h1>
		<form method="post" action="new_file.php" enctype="multipart/form-data">
			<table>
			<tr>
				<td class="zahlavi important">
					Vyberte soubor
				</td>
				<td>
					<input required type="file" name="attachement">
				</td>
			</tr>
			<tr>
				<td class="zahlavi important">
					Popis
				</td>
				<td>
					<input required class="long" type="text" id="jmeno_ukolu" name="description" placeholder="Je to nový model hospody" required>
				</td>
			</tr>
			<tr>
				<td class="zahlavi important">
					Tagy
				</td>
				<td>
					<input required class="long" type="text" id="jmeno_ukolu" name="tags" placeholder="oddělujte mezerami, např: animace třída questy gui apod.">
				</td>
			</tr>
			<tr>
				<td class="zahlavi">
					Důležitost
				</td>
				<td>
					<select name="importancy">
						<option value="Nízká">Nízká</option>
						<option value="Střední">Střední</option>
						<option value="Vysoká">Vysoká</option>
					</select>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="center">
					<input type="submit" value="Nahrát soubor" class="send"></input>
				</td>
			</tr>
			</table>
		</form>
	</div>
	</body>
</html>