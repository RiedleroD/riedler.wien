<?php
	if(!array_key_exists('c',$_GET)){
		http_response_code(400);
		$err="no command given";
	}else switch($_GET['c']){
	case 'moresongs':
		if(array_key_exists('startdate',$_GET) && array_key_exists('max_amount',$_GET) && array_key_exists('ashtml',$_GET)){
			include("befuncs/db_music.php");
			include("befuncs/music.php");
			$db=new musicdb();
			$data = $db->get_songs($_GET['startdate'],(int)$_GET['max_amount'],
				array_key_exists('nooriginals',$_GET),
				array_key_exists('norremixes',$_GET),
				array_key_exists('nocommissions',$_GET),
				array_key_exists('norrequests',$_GET));
			if($_GET['ashtml']=='0'){
				http_response_code(501);
				echo 'json output support has been removed temporarily';
				die();
			}else if($_GET['ashtml']=='1'){
				echo_html_from_songlist($data);
				die();
			}else{
				http_response_code(400);
				$err="invalid value for ashtml: ".$_GET['ashtml'];
			}
		}else{
			http_response_code(400);
			$err="not all parameters are defined";
		}
		break;
	case 'votesong':
		if(array_key_exists('song',$_GET) && array_key_exists('type',$_GET)){
			include('befuncs/snips.php');//for session control
			if($_SESSION['userid']!=0){
				include('befuncs/db_music.php');
				$db=new musicdb();
				$db->set_vote($_GET['song'],$_SESSION['userid'],$_GET['type']);
				die();
			}else{
				http_response_code(403);
				$err='user not logged in';
			}
		}else{
			http_response_code(400);
			$err="not all parameters are defined";
		}
		break;
	case 'createaccount':
		if(array_key_exists('name',$_GET) && array_key_exists('passwd',$_GET) && array_key_exists('type',$_GET)){
			require_once('befuncs/snips.php');//for session control
			require_once('./befuncs/db_user.php');
			$db=new accountdb();
			$user = $db->get_user_by_id($_SESSION['userid']);
			if($user['type']=='Admin'){
				$db->add_user($_GET['name'],$_GET['passwd'],$_GET['type']);
			}else{
				http_response_code(403);
				$err='insufficient permissions';
			}
		}else{
			http_response_code(400);
			$err="not all parameters are defined";
		}
		break;
	default:
		http_response_code(400);
		$err="unknown command: {$_GET['c']}";
	}
?>
<!-- bare-minimum API documentation for curious minds -->
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"/>
		<title>Riedler's API</title>
		<link rel="icon" type="image/svg" href="favicon.svg"/>
		<style>
			table{border-collapse:collapse;margin:0.5em 0.25em}
			th,td{padding:0.25em 0.5em;vertical-align:top}
			b{color:red}
		</style>
	</head>
	<body>
		<span>You have to specify a command with the 'c' parameter and supply further parameters depending on the command.</span><br/>
		<span>Note that there's not a lot of input correctness checking being done, so you might get weird outputs if you don't adhere to the API rules.</span>
		<table border="1">
			<tr>
				<th>Command</th>
				<th>Parameter</th>
				<th>in/out Format</th>
				<th>Explanation</th>
			</tr>
			<tr>
				<td rowspan="8">moresongs</td>
				<td></td>
				<td></td>
				<td>Returns a list of Songs</td>
			</tr>
			<tr>
				<td>startdate</td>
				<td>YYYY-MM-DD</td>
				<td>The oldest disallowed date for the returned songs</td>
			</tr>
			<tr>
				<td>max_amount</td>
				<td>number</td>
				<td>The maximum amount of songs that should be returned</td>
			</tr>
			<tr>
				<td>nooriginals</td>
				<td></td>
				<td>If defined, originals are ommitted from results</td>
			</tr>
			<tr>
				<td>norremixes</td>
				<td></td>
				<td>If defined, rremixes are ommitted from results</td>
			</tr>
			<tr>
				<td>nocommissions</td>
				<td></td>
				<td>If defined, commissions are ommitted from results</td>
			</tr>
			<tr>
				<td>norrequests</td>
				<td></td>
				<td>If defined, rrequests are ommitted from results</td>
			</tr>
			<tr>
				<td>ashtml</td>
				<td>1|0</td>
				<td>if the returned data should be the html code to be inserted into the main grid or json</td>
			</tr>
			<tr>
				<td rowspan="3">votesong</td>
				<td></td>
				<td></td>
				<td>Likes / Dislikes a song (requires the user to be logged in)</td>
			</tr>
			<tr>
				<td>song</td>
				<td>number</td>
				<td>ID of the song to be liked</td>
			</tr>
			<tr>
				<td>type</td>
				<td>like|dislike</td>
				<td>if the song should be liked or disliked</td>
			</tr>
			<tr>
				<td rowspan="4">createaccount</td>
				<td></td>
				<td></td>
				<td>Creates a new user account (requires the user to be an admin)</td>
			</tr>
			<tr>
				<td>name</td>
				<td>string(32)</td>
				<td>a name for the user</td>
			</tr>
			<tr>
				<td>passwd</td>
				<td>string(64)</td>
				<td>sha-256 hash of the user's password</td>
			</tr>
			<tr>
				<td>type</td>
				<td>any of (<code>Administrator</code>, <code>User</code>)</td>
				<td>the role of the user</td>
			</tr>
		</table>
		<?php if(isset($err)) echo "<span>Error: <b>$err</b></span>"; ?>
	</body>
</html>