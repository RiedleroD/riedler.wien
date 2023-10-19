<?php
	sessionSetup();
	
	require_once('db_user.php');
	
	function genUsual($title,$styles,$custom){
		?>
		<!DOCTYPE html>
		<html lang="en">
			<head>
				<meta charset="UTF-8"/>
				<meta name="viewport" content="width=device-width, initial-scale=1.0">
				<title><?= $title ?></title>
				<link rel="icon" type="image/svg" href="/favicon.svg"/>
				<?php
					array_unshift($styles,"/style/default.css");
					foreach($styles as $style){
						echo "<link rel='stylesheet' href='$style'>";
					}
				?>
				<script async src="/jizz/default.js"></script>
				<?= $custom ?>
			</head>
		<?php
	}
	function genNavBar(){
		?>
		<nav id="main_nav">
			<div>
				<?php
					$uri = array_key_exists('redirect_to',$_GET) ?
                        $_GET['redirect_to'] :
                        $_SERVER['REQUEST_URI'];
					$params = http_build_query(array(
						'redirect_to' => $uri,
					));
					//TODO: replace login btn with account btn when logged in
					if($_SESSION['userid']==0){
						echo '<a href="/login/?' . $params . '" class="btn" id="loginbtn">Login</a>';
					}else{
						echo '<a href="/login/" class="btn" id="loginbtn">Logout</a>';
						
						$udb = new accountdb();
						$user = $udb->get_user_by_id($_SESSION['userid']);
						
						if($user['type']=='Admin'){
							echo '<a href="/admin/" class="btn">Admin Tools</a>';
						}
					}
				?>
				<div id="navpad_left"></div>
			</div>
			<img id="logo" src="https://riedler.wien/favicon.svg"/>
			<div>
				<div id="navpad_right"></div>
		<?php
		
		$items = array(
			"Coding"=>"/coding/",
			"Music"=>"/music",
			"Home"=>"/");
		foreach($items as $txt=>$lnk){
			echo "<a href='$lnk' class='btn'>$txt</a>";
		}
		echo '</div></nav>';
	}
	function genFooter(){
		echo '<div id="overlay"></div>';
		echo '<footer>©2022 | Riedler &lt;<a href="mailto:admin@riedler.wien">admin@riedler.wien</a>&gt; | <a href="https://github.com/RiedleroD/riedler.wien">Contribute</a></footer>';
	}
	function sessionSetup(){
		session_start();
		if(!array_key_exists('userid',$_SESSION)){
			$_SESSION['userid'] = 0;
		}
	}
	#HeBi → Heterogenous & Bilateral
	#meaning it's two-sided with different content in each side.
	#in this case it's an icon left and some text right
	function genHeBiLink($img,$txt,$href){
		if($img==null){
			$img='<div></div>';
		}else{
			$img="<img src='$img'/>";
		}
		echo " <a href='$href' class='btn hebi'>$img$txt</a> ";
	}
	function genHeBi($img,$txt){
		if($img==null){
			$img='<div></div>';
		}else{
			$img="<img src='$img'/>";
		}
		echo " <span class='btn hebi'>$img$txt</span> ";
	}
	function genImgLink($img,$href,$alt=null){
		if($alt){
			$tmp=" alt='Link to $alt'";
		}else{
			$tmp='';
		}
		echo "<a href='$href'><img src='$img'$tmp/></a>";
	}
	#no idea what sfto means, but it's been the folder with all my images for
	#quite some time, so it's staying that way.
	#TODO: replace with databse function
	function sfto($name){
		return "https://riedler.wien/sfto/$name";
	}
	function rwicon($name){
		if(strlen($name)>5){
			throw new Exception("rwicon name length can't be longer than 5 characters. Instead, $name is ".strlen($name));
		}
		return "/resource/rwicons/$name.svg";
	}
	function services_as_html($services){
		foreach($services as list($abbr, $mylink, $name)){
			genHeBiLink(rwicon($abbr),$name,complete_link_protocol($mylink));
		}
	}
	function complete_link_protocol($link){
		if(strpos($link,"://"))
			return $link;//return original link if protocol is already specified
		return "https://".$link;
	}
?>