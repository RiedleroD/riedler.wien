<?php
	$id=$_GET['id'];
	$type=$_GET["type"];
	if($id==NULL or $type==NULL){
		http_response_code(400);
		die();
	}
	include("../../befuncs/db_music.php");
	$name=db_get_song_by_id($id)["name"];
	if($name==NULL){
		http_response_code(404);
		die();
	}
	$ftype=db_get_filetype_by_id($type);
	if($ftype==NULL){
		http_response_code(404);
		die();
	}
	$fn=str_replace(' ','_',
		str_replace('/','-',
		str_replace('\'','',
		strtolower($name)))).'.'.$ftype['ext'];
	$fp='../../resource/tracks/'.$fn;
	if(!file_exists($fp)){
		http_response_code(404);
		echo "no file named '$fn'";
		die();
	}
	header('Location: '.$fp);
?>