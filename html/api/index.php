<?php
	if(!array_key_exists('c',$_GET)){
		http_response_code(400);
		$err="no command given";
	}else if($_GET['c']=='moresongs'){
		if(array_key_exists('startdate',$_GET) && array_key_exists('max_amount',$_GET) && array_key_exists('ashtml',$_GET)){
			include("../befuncs/db.php");
			include("../befuncs/snips.php");
			$data = db_get_songs($_GET['startdate'],(int)$_GET['max_amount']);
			if($_GET['ashtml']=='0'){
				header('Content-Type: application/json');
				echo_json_from_songlist($data);
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
				<td rowspan="4">moresongs</td>
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
				<td>ashtml</td>
				<td>1|0</td>
				<td>if the returned data should be the html code to be inserted into the main grid or json</td>
			</tr>
		</table>
		<?php if(isset($err)) echo "<span>Error: <b>$err</b></span>"; ?>
	</body>
</html>