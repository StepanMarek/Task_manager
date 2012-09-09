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
		setcookie("id",$_SERVER["REMOTE_ADDR"],time()+60*15);
		setcookie("user",$login_by_post["name"]);
		header("Location: index.php");
	}
}
?>
<html>
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
</html>