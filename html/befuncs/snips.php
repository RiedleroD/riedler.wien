<?php
	function genUsual($title,$style,$custom){
		echo
		'<!DOCTYPE html>'.
		'<html lang="en">'.
			'<head>'.
				'<meta charset="UTF-8"/>'.
				'<meta name="viewport" content="width=device-width, initial-scale=1.0">'.
				"<title>$title</title>".
				'<link rel="icon" type="image/svg" href="/favicon.svg"/>'.
				'<style>'.
					'@import "/style/default.css";'.
					$style.
				'</style>'.
				'<script async src="/jizz/default.js"></script>'.
				$custom.
			'</head>';
	}
	function genNavBar(){
		echo '<nav id="main_nav"><img id="logo" src="https://riedler.wien/favicon.svg"/>';
		$items = array(
			"Coding"=>"https://riedler.wien/coding/",
			"Music"=>"/music",
			"Home"=>"/");
		foreach($items as $txt=>$lnk){
			echo "<a href='$lnk' class='btn'>$txt</a>";
		}
		echo '</nav>';
	}
	function genFooter(){
		echo '<div id="overlay"></div>';
		echo '<footer>©2022 | Riedler &lt;<a href="mailto:admin@riedler.wien">admin@riedler.wien</a>&gt; | <a href="https://github.com/RiedleroD/riedler.wien">Contribute</a></footer>';
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
		return "https://riedler.wien/sfto/rwicons/$name.svg";
	}
	/* for music */
	function genAudioPlayer($id,$items){
		echo "<button class='btn play'>".
				"<audio preload='none' id='player$id'>";
			foreach($items as $type=>$mime){
				echo "<source src='./download/?id=$id&type=$type' type='$mime'/>";
			}
		echo '</audio></button>';
	}
	function echo_html_from_songlist($data){
		$lastdate=null;
		foreach($data as $song){
			echo "<a href='./play?id=${song['id']}'>".
				"<span>${song['name']}</span>".
				"<span>${song['status']}</span>".
				"<span>${song['requester']}</span>".
				"<span>${song['fdate']}</span>";
			if(count($song['files'])>0){
				echo '</a>';
				genAudioPlayer($song['id'],$song['files']);
			}else
				echo '<span></span></a>';
			$lastdate = $song['date'];
		}
		return $lastdate;
	}
?>