<?php
/*
TODO:
ideas: pouze popis, náhlé nápady, které je třeba zapsat, možnost přílohy
skladiště: artworky, staré verze, skripty levelů, a vůbec všechny soubory, které je lepší skladovat, možnost odkazů přes úkoly
účty: přihlašování pod svým jménem a heslem, ohlašování přiřazení k úkolům, kalendář termínů
*/
require("lib.php");
$form_sent = isset($_POST["jmeno"]) && isset($_POST["heslo"]);
$zprava = "unchanged";

if($form_sent){
	$login_by_post = ["name" => $_POST["jmeno"], "password" => $_POST["heslo"]];
	$bad = [];
	
	if(!user_exists($login_by_post["name"]))
		$bad["name"] = "Špatné uživatelské jméno";
		
	elseif(!(login_user($login_by_post["name"], $login_by_post["password"]))){
		$bad["password"] = "Špatné heslo";
	}
	else{
		setcookie("id",hash("sha256",$login_by_post["name"]),time()+60*15);
		setcookie("user",$login_by_post["name"]);
		header("Location: index.php");
	}
}
?>
<html>
<head>
	<meta charset="utf-8">


	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<title>Přihlášení</title>

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
	<div id="prihlaseni" style=<?php if( isset($bad["name"]) || isset($bad["password"]) ) echo "'box-shadow: 0 0 10px #F00;'"?>>
		<form method="POST" action="login.php">
			Jméno: <br><input type="text" name="jmeno"><br>
			Heslo: <br><input type="password" name="heslo"><br>
			<input class="send" type="submit" value="Přihlásit se do systému">
		</form>
	</div>	
</body>
</html>

<!-- <html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
		<title>Přihlášení</title>
	</head>
	<body>
	<div id="prihlaseni">
		<form method="POST" action="login.php">
			Jméno: <br><input type="text" name="jmeno"><br><?php if(isset($bad["name"])) echo "<span style='color: red'>".$bad["name"]."</span><br>"?>
			Heslo: <br><input type="password" name="heslo"><br><?php if(isset($bad["password"])) echo "<span style='color: red'>".$bad["password"]."</span><br>"?>
			<button type="submit">Přihlásit se do systému</button>
		</form>
	</div>
	</body>
</html> -->