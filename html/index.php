<?php
	require_once("befuncs/snips.php");
	require_once("befuncs/db_music.php");
	require_once("befuncs/db_coding.php");
	genUsual("Riedler",['/style/home.css'],"");
?>
<body>
	<?php genNavBar(); ?>
	<div class="slant">
		<h1>Riedler</h1>
		<fieldset>
			<legend><h3>Support me!</h3></legend>
			<?php
				genHeBiLink(rwicon("lp"),"Liberapay","https://liberapay.com/Riedler");
				genHeBiLink(rwicon("kf"),"Ko-fi","https://ko-fi.com/riedler")
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Music</h3></legend>
			<?php
				$db=new musicdb();
				services_as_html($db->get_services());
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Coding</h3></legend>
			<?php
				$db=new codingdb();
				services_as_html($db->get_services());
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Social</h3></legend>
			<?php
				genHeBiLink(rwicon("mstdn"),"Mastodon","https://catcatnya.com/@Riedler");
				genHeBiLink(rwicon("tg"),"Telegram","https://t.me/@RiedleroD");
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Gaming</h3></legend>
			<?php
				genHeBiLink(rwicon("mnspr"),"Minesweeper","https://minesweeper.online/player/7449348");
				genHeBiLink(rwicon("spdrn"),"speedrun.com","https://www.speedrun.com/user/Riedler");
				genHeBiLink(rwicon("mmlb"),"Mega Man Leaderboards","https://megamanleaderboards.net/index.php?page=runner&name=Riedler");
				genHeBiLink(rwicon("yt"),"Youtube","https://www.youtube.com/channel/UCCvjTX0Sy29dkMSZ5V8rM5Q")
			?>
		</fieldset>
	</div>
	<?php genFooter(); ?>
</body>
</html>