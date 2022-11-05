<?php
	require_once("befuncs/snips.php");
	require_once("befuncs/db_music.php");
	require_once("befuncs/db_coding.php");
	genUsual("Riedler","@import 'style/home.css'","");
?>
<body>
	<?php genNavBar(); ?>
	<div class="slant">
		<h1>Riedler</h1>
		<h3 style="text-align:center">External Links</h3>
		<fieldset>
			<legend><h3>Support me!</h3></legend>
			<?php
				genHeBiLink(rwicon("lp"),"Liberapay","https://liberapay.com/Riedler");
				genHeBiLink(rwicon("fv"),"Fiverr","https://www.fiverr.com/s2/1e37f97239");
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
			<legend><h3>Userstyles</h3></legend>
			<?php
				genHeBiLink(rwicon("usw"),"Userstyles.world","https://userstyles.world/user/riedler");
				genHeBiLink(rwicon("gh"),"Userstyles Repository","https://github.com/RiedleroD/userstyles-riedler");
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Social</h3></legend>
			<?php
				genHeBiLink(rwicon("mstdn"),"Mastodon","https://mas.to/@Riedler");
				genHeBiLink(rwicon("tg"),"Telegram Channel","https://t.me/RiedlerM");
				genHeBiLink(rwicon("rddt"),"Subreddit","https://www.reddit.com/r/Riedler");
			?>
		</fieldset>
		<fieldset>
			<legend><h3>Gaming</h3></legend>
			<?php
				genHeBiLink(rwicon("spdrn"),"speedrun.com","https://www.speedrun.com/user/Riedler");
				genHeBiLink(rwicon("mmlb"),"Mega Man Leaderboards","https://megamanleaderboards.net/index.php?page=runner&name=Riedler");
				genHeBiLink(rwicon("yt"),"Youtube","https://www.youtube.com/channel/UCCvjTX0Sy29dkMSZ5V8rM5Q")
			?>
		</fieldset>
	</div>
	<?php genFooter(); ?>
</body>
</html>