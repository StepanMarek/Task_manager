<?php 
/* Funkce pro práci s úkoly */
function getTasks( $rest , $tasks_file ){
	$poradi = ["name","date","target","description","attachement","duration","importancy","creator","domain"];
	if(filesize($tasks_file) > 0){
		$sid = fopen($tasks_file, "r");
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
		return $vysledek;
	}
	else return false;
};

function shorten($str, $length){
	if(mb_strlen($str) > $length){
		return mb_substr($str, 0, $length-3)."...";
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
		if($user_ids[0] == $id && $user_ids[1] == hash("sha256",$passw)){
			return true;
			break;
		}
	};
	return $pole;
};

function logged( $addr ){
	if(isset($_COOKIE["id"])){
		if($_COOKIE["id"] == $addr)
			return true;
		else return false;
	}
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
?>