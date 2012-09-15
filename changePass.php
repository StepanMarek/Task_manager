<?php
// require("lib.php");
// if(isset($_COOKIE["user"])){
// 	if(!logged($_COOKIE["user"])){
// 		header("Location: login.php");
// 		exit;
// 	}
// }
// else {
// 	header("Location: login.php");
// 	exit;
// }
?>
<html>
<head>
	<meta charset="utf-8">


	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<title>Změna hesla</title>

	<script src="./javascript/prefixfree.min.js"></script>
	<script src="http://code.jquery.com/jquery-1.8.1.min.js"></script>
	<script src="./javascript/game_of_life.js"></script>
	<script>
	$(document).ready(function(){
		bg = new GOLBackground( document.body, 256, 256, 6, 2);
		bg.update(20);
		bg.render();
	})
	</script>
</head>
<body>
	<?php
	include("header.php");
	?>
	<div id="prihlaseni" style="width:400px;">
		<form method="POST" action="changePass.php">
			Zadej nové heslo:<br><input type="password" name="heslo"><br>
			<input class="send" type="submit" value="Vygenerovat hash">
			<br>
			<br>
			<?php
			if(isset($_POST["heslo"])){
				echo "Hash tvého hesla je<br><textarea style='width:100%;'>".hash("sha256",$_POST["heslo"] . $SALT)."</textarea>";
				echo "<br>Nyní založ úkol a popros v něm Jirku nebo Štěpána o změnu hesla. Do popisu úkolu nezapomeň přidat tento hash.";
			}
			?>
		</form>
	</div>	
</body>
</html>