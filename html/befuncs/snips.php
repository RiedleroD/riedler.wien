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
				$custom.
			'</head>';
	}
	function genNavBar($items){
		echo '<nav id="main_nav"><img id="logo" src="https://riedler.wien/favicon.svg"/>';
		foreach($items as $txt=>$lnk){
			echo "<a href='$lnk' class='btn'>$txt</a>";
		}
		echo '</nav>';
	}
	function genFooter(){
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
		return "https://riedler.wien/sfto/rwicons/$name.svg";
	}
?>