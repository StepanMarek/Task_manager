<?php
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
?>