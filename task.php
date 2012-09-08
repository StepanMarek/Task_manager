<?php
	require("lib.php");
	
	if(!logged($_SERVER["REMOTE_ADDR"]) || !isset($_COOKIE["user"])){
	header("Location: login.php");
	exit;
	}
	
	if(!isset($_GET["id"])){
		header("Location: basic.php");
		exit;
	}
	else {
		$task = getTask($_GET["id"], "tasks.txt");
	};
	date_default_timezone_set("Europe/Prague");
	$pole_zaloh = getDirArray("sklad/105110102111", $_GET["id"]);
?>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="styles.css">
	</head>
	<body>
		<div class="task_self">
			<table class="zahlavi_tab">
				<tr>
				<td align="center" colspan="6">
					<h2 align="center"><?php
						echo $task["name"];
					?></h2>
				</td>
				</tr>
				<tr>
				<td class="zahlavi_s">
					Komu
				</td>
				<td class="zahlavi_val_s">
				<?php
					echo $task["target"];
				?>
				</td>
				<td class="zahlavi_s">
					Přidán
				</td>
				<td class="zahlavi_val_s">
				<?php
					echo date("d.m.Y G:i",$task["date"]);
				?>
				</td>
				<td class="zahlavi_s">
					Do
				</td>
				<td class="zahlavi_val_s">
				<?php
					if($task["duration"]){echo $task["duration"];}
					else{echo "UNLIM";}
				?>
				</td>
				</tr>
				<tr>
				<td class="zahlavi_s">
					Tvůrce
				</td>
				<td class="zahlavi_val_s">
				<?php
					echo $task["creator"];
				?>
				</td>
				<td class="zahlavi_s">
					Důležitost
				</td>
				<td class="zahlavi_val_s" style="color: <?php echo getImportanceColor($task["importancy"]);?>">
				<?php
					echo $task["importancy"];
				?>
				</td>
				<td class="zahlavi_s">
					Obor
				</td>
				<td class="zahlavi_val_s">
				<?php
					echo $task["domain"];
				?>
				</td>
				</tr>
			</table>
			<p class="zahlavi">
				Popis
			</p>
			<p>
			<?php
			echo $task["description"];
			?>
			</p>
			<p class="zahlavi">
				Pomocné materiály
			</p>
			<p>
				<table>
					<?php
						for($i=0;$i<count($pole_zaloh);$i++){
							echo "<tr>";
							echo "<td>".$pole_zaloh[$i]["name"];
							echo "</td>";
							echo "<td><button>Stáhnout</button>";
							echo "</td>";
							echo "</tr>";
						};
					?>
				</table>
			</p>
		</div>
	</body>
</html>