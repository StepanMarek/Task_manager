<?php 
$SALT = "ášlw%ě38+d§/)";

function sklonuj($cislo, $varianty){
	if($cislo == 1){
		return $cislo . " " . $varianty[0];
	}
	elseif($cislo > 1 && $cislo < 5){
		return $cislo . " " . $varianty[1];
	}
	else {
		return $cislo . " " . $varianty[2];
	}
}
function parseDuration($duration, $date){
	if($duration < 0){
		return "Dokončeno";
	}
	$zbyva = ($duration + $date) - time();
	$output = "";

	if($zbyva < 0){
		$output .= "Vypršel";
	}
	elseif($zbyva < 60){
		$output .= sklonuj($zbyva,array("sekunda","sekundy","sekund"));
	}
	elseif($zbyva/60 < 60){
		$output .= sklonuj(round($zbyva/60),array("minuta","minuty","minut"));
	}
	elseif($zbyva/60/60 < 24){
		$output .= sklonuj(round($zbyva/60/60),array("hodina","hodiny","hodin"));
	}
	elseif($zbyva/60/60/24 < 7){
		$output .= sklonuj(round($zbyva/60/60/24),array("den", "dny", "dní"));
	}
	elseif($zbyva/60/60/24/7 < 4){
		$output .= sklonuj(round($zbyva/60/60/24/7),array("týden","týdny","týdnů"));
	}
	else {
		$output .= $zbyva;
	}
	return $output;
}

/* Funkce pro práci s úkoly */
function getTasks( $rest , $tasks_file ){
	$poradi = ["name","date","target","description","attachement","duration","importancy","creator","domain"];
	$sid = fopen($tasks_file, "r");
	if(filesize($tasks_file) > 0){
		$pole = explode("?:;",fread($sid,filesize($tasks_file)));
		$vysledek = [];
		for($i=0;$i<count($pole);$i++){
			$mezi = explode("?:", $pole[$i]);
			$asoc = [];
			for($j=0;$j<count($mezi) && $j < count($poradi);$j++){
				$asoc[$poradi[$j]] = $mezi[$j];
			};
			$vysledek[$i] = $asoc;
		};
		fclose($sid);
		if(!isset($vysledek[0]["target"]))
			return false;
		return $vysledek;
	}
	else return false;
};

function shorten($str, $length){
	if(strlen($str) > $length){
		return mb_substr($str, 0, $length-3, 'UTF-8')."...";
	}
	else {
		return $str;
	}
}

function getTask($crTime, $tasks_file){
	$pole = getTasks(false, $tasks_file);
	for($i=0;$i<count($pole);$i++){
		if($crTime == $pole[$i]["date"]) return $pole[$i];
	};
	return false;
};

function getImportanceColor ($imp){
	$pole = ["Nízká" => "#3D99FC", "Střední" => "#F3FC3D", "Vysoká" => "red"];
	if(isset($pole[$imp])) return $pole[$imp];
	else return "black";
};

function getDeadline($mtime,$crtime){
	$months = [31,28,31,30,31,30,31,31,30,31,30,31];
	$vysledek = "";
	$cas = $mtime+$crtime;
	$vysledek = date("d.m.Y G:i", $cas);
	return $vysledek;
};

function getDeadlineColor($obj){
	if(time()>$obj["duration"]+$obj["date"] && $obj["duration"])
		return "red";
	else return "black";
};

function deleteTask( $neco ){
	$sid = fopen("tasks.txt","r");
	$velke_pole = explode("?:;",fread($sid,filesize("tasks.txt")));
	$novy_soubor = "";
	for($i = 0;$i<count($velke_pole);$i++){
		if( $neco == explode("?:", $velke_pole[$i])[0] )
			continue;
		else{
			if($novy_soubor != "")
				$novy_soubor.="?:;";
			$novy_soubor.=$velke_pole[$i];
		}
	}
	fclose($sid);
	$sid = fopen("tasks.txt","w");
	fwrite($sid, $novy_soubor);
	fclose($sid);
};

function finishTask( $neco ){
	$sid = fopen("tasks.txt","r");
	$velke_pole = explode("?:;",fread($sid,filesize("tasks.txt")));
	$novy_soubor = "";
	for($i = 0;$i<count($velke_pole);$i++){
	$male_pole = explode("?:", $velke_pole[$i]);
		if( $neco == $male_pole[0] ){
			$male_pole[5] = -1;
			if($i != 0)
				$novy_soubor.="?:;";
			$novy_soubor.= implode("?:",$male_pole);
		}
		else{
			if($i != 0)
				$novy_soubor.="?:;";
			$novy_soubor.=$velke_pole[$i];
		}
	}
	fclose($sid);
	$sid = fopen("tasks.txt","w");
	fwrite($sid, $novy_soubor);
	fclose($sid);
};

/* Funkce pro práci s uživately */
function user_exists( $id ){
	$sid = fopen("12157914","r");
	$user_table = explode(":",fread($sid,filesize("12157914")));
	for($i=0;$i<count($user_table);$i++){
		if($id == explode(",",$user_table[$i])[0])
			return true;
	};
	return false;
};

function login_user($id,$passw){
	$sid = fopen("12157914","r");
	$content = fread($sid, filesize("./".$file_key));
	$user_table = explode(":",$content);
	$pole = [];
	for($i=0;$i<count($user_table);$i++){
		$user_ids = explode(",",$user_table[$i]);
		if($user_ids[0] == $id && $user_ids[1] == hash("sha256",$passw . $SALT)){
			return true;
			break;
		}
	};
	return $pole;
};

function logged( $user ){
	if(isset($_COOKIE["id"])){
		if($_COOKIE["id"] == hash("sha256",$user))
			return true;
		else return false;
	}
};

function prodlouzit( $cookId ){
	setcookie($cookId,$_COOKIE[$cookId],time()+60*15);
};
/* Funkce pro práci se soubory */
function getDirArray($file_name, $rest){
	$poradi = ["name","link","importancy","type","download","description","tags","date"];
	if(filesize($file_name) > 0){
		$sid = fopen($file_name, "r");
		$pole = explode("\n",fread($sid,filesize($file_name)));
		$vysledek = [];
		for($i=0;$i<count($pole);$i++){
			$mezi = explode("?:", $pole[$i]);
			$asoc = [];
			if($rest){
				if($rest == $mezi[1]){}
				else continue;
			}
			for($j=0;$j<count($mezi);$j++){
				$asoc[$poradi[$j]] = $mezi[$j];
			};
			$vysledek[] = $asoc;
		};
		fclose($sid);
		return $vysledek;
	}
	else {
		return false;
	}
}

function puvodni($filename){
	if($filename == "")
		return false;
	if(!is_file("sklad/".$filename))
		return false;
	if(strpos($filename,"./"))
		return false;
	if(strpos($filename, "sklad.php"))
		return false;
	if(strpos($filename, "105110102111"))
		return false;
	return true;
};

function deleteFile( $neco ){
	$sid = fopen("sklad/105110102111","r");
	$velke_pole = explode("\n",fread($sid,filesize("sklad/105110102111")));
	$novy_soubor = "";
	for($i = 0;$i<count($velke_pole);$i++){
		if( $neco == explode("?:", $velke_pole[$i])[4] ){
			unlink("sklad/".$neco);
			continue;
			}
		else{
			if($i != 0)
				$novy_soubor.="\n";
			$novy_soubor.=$velke_pole[$i];
		}
	}
	fclose($sid);
	$sid = fopen("sklad/105110102111","w");
	fwrite($sid, $novy_soubor);
	fclose($sid);
};
?>