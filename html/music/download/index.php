<?php
	$id=$_GET['id'];
	$type=$_GET["type"];
	if($id==NULL or $type==NULL){
		http_response_code(400);
		die();
	}
	include("../../befuncs/db.php");
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
		strtolower($name))).'.'.$ftype['ext'];
	$fp='../../resource/tracks/'.$fn;
	if(!file_exists($fp)){
		http_response_code(404);
		echo "no file named '$fn'";
		die();
	}
	$fsize=filesize($fp);
	header('Content-Description: File Transfer');
	header('Content-Type: '.$ftype['mime']);
	header('Content-Disposition: attachment; filename="'.$fn.'"');
	header('Expires: 0');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	header('Content-Length: '.$fsize);
	flush();
	readfile($fp);
?>