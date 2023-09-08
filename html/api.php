<?php

$err = null; // such logging. much debug

class APICommand{
	public string $protocol;
	public Closure $callback;
	public array $docs;
	public function __construct(string $protocol,array $docs,callable $callback){
		$this->protocol = $protocol;
		$this->callback = $callback;
		$this->docs = $docs;
	}
}

define('commandlist', [
	'moresongs' =>
	new APICommand(
		'GET',
		[
		'Returns a list of Songs',
		['The oldest disallowed date for the returned songs','YYYY-MM-DD'],
		'The maximum amount of songs that should be returned',
		'whether to omit originals from results',
		'whether to omit rremixes from results',
		'whether to omit commissions from results',
		'whether to omit commissions from results'
		],
		static function(
			string $startdate,
			int $max_amount,
			bool $nooriginals=false,
			bool $noremixes=false,
			bool $nocommissions=false,
			bool $norrequests=false)
		{
			require_once("befuncs/db_music.php");
			require_once("befuncs/music.php");
			
			$db=new musicdb();
			$data = $db->get_songs($startdate,$max_amount,$nooriginals,$noremixes,$nocommissions,$norrequests);
			echo_html_from_songlist($data);
		}
	),
	'votesong' =>
	new APICommand(
		'GET',
		[
		'Likes / Dislikes a song (requires the user to be logged in)',
		'ID of the song to be liked',
		['if the song should be liked or disliked','like | dislike']
		],
		static function(int $song,string $type){
			require_once('befuncs/snips.php');//for session control
			
			if($_SESSION['userid']!=0){
				require_once('befuncs/db_music.php');
				$db=new musicdb();
				$db->set_vote($song,$_SESSION['userid'],$type);
			}else{
				return [403,'user not logged in'];
			}
		}
	),
	'createaccount' =>
	new APICommand(
		'POST',
		[
		'Creates a new user account (requires the user to be an admin)',
		['a name for the user','string(32)'],
		['sha-256 hash of the user\'s password','string(64)'],
		['the role of the user','Administrator | User']
		],
		static function(string $name,string $passwd,string $type){
			require_once('befuncs/snips.php');//for session control
			require_once('./befuncs/db_user.php');
			
			$db=new accountdb();
			$user = $db->get_user_by_id($_SESSION['userid']);
			if($user['type']=='Admin'){
				$db->add_user($name,$passwd,$type);
			}else{
				return [403,'insufficient permissions'];
			}
		}
	)
]);

if(!array_key_exists('c',$_GET)){
	http_response_code(400);
	$err='no command given';
}else if(!array_key_exists($_GET['c'],commandlist)){
	http_response_code(400);
	$err='unknown command: '.$_GET['c'];
}else{
	$cmd = commandlist[$_GET['c']];
	$map = ['POST'=>$_POST,'GET'=>$_GET][$cmd->protocol];
	$reflection = new ReflectionFunction($cmd->callback);
	$params = [];
	
	foreach($reflection->getParameters() as $param){
		if(array_key_exists($param->name,$map)){
			$val = $map[$param->name];
			$type = $param->getType();
			if($type->allowsNull() && $val=='null'){
				$val = null;
			}else switch($type->getName()){
				case 'string':
					break;
				case 'bool':
					if($val === 'true'){
						$val = true;
					}else if($val === 'false'){
						$val = false;
					}else{
						$err = "parameter {$param->name} isn't a boolean ({$val})";
						goto err_goto;
					}
					break;
				case 'int':
					if(!ctype_digit($val)){
						$err = "parameter {$param->name} is not an integer ({$val})";
						goto err_goto;
					}
					$val = (int)$val;
					break;
			};
			
			$params[$param->name] = $val;
		}else if(!$param->isOptional()){
			$err="parameter {$param->name} is missing";
			goto err_goto;
		}
	}
	call_user_func_array($cmd->callback,$params);
}
err_goto:
if($err === null)
	die();
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
				<th>Protocol</th>
				<th>Parameter</th>
				<th>in/out Format</th>
				<th>Explanation</th>
			</tr>
			<?php
				foreach(commandlist as $name=>$cmd){
					$reflection = new ReflectionFunction($cmd->callback);
					$args = $reflection->getParameters();
					$nargs = $reflection->getNumberOfParameters();
					$rowspan = $nargs +1;
					echo "<tr><td rowspan=\"{$rowspan}\">{$name}</td><td rowspan=\"{$rowspan}\">{$cmd->protocol}</td><td></td><td></td><td>{$cmd->docs[0]}</td></tr>";
					$i = 1;
					foreach($args as $param){
						$doc = $cmd->docs[$i];
						if(is_array($doc)){
							$paramname = $doc[0];
							$paramtype = $doc[1];
						}else{
							$paramname = $doc;
							switch($param->getType()->getName()){
								case 'string':
									$paramtype = '';
									break;
								case 'bool':
									$paramtype = 'true | false';
									break;
								case 'int':
									$paramtype = 'number';
									break;
								default:
									$paramtype = 'unknown';
							}
						}
						echo "<tr><td>{$param->name}</td><td>{$paramtype}</td><td>{$paramname}</td></tr>";
						$i++;
					}
				}
			?>
		</table>
		<?php if(isset($err)) echo "<span>Error: <b>$err</b></span>"; ?>
	</body>
</html>