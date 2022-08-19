<?php
	include("../befuncs/snips.php");
	include("../befuncs/db.php");
	genUsual("Riedler's Music","@import '../style/music.css'",
			 '<script async src="../jizz/musicplayer.js"></script><script async src="../jizz/musiclist.js"></script>');
?>
<body>
	<?php genNavBar(); ?>
	<h1>Riedler's Music</h1>
	<fieldset>
		<legend><h3>Support me!</h3></legend>
		<?php genHeBiLink(rwicon("lp"),"Liberapay","https://liberapay.com/Riedler"); ?>
		and
		<?php genHeBiLink(rwicon("pt"),"Patreon","https://patreon.com/RiedlerM"); ?>
		are ways to give me money, so I can make music without worrying about monetary issues.<br/>
		You can also comission a custom piece of music via 
		<?php genHeBiLink(rwicon("fv"),"Fiverr","https://www.fiverr.com/s2/1e37f97239"); ?>
		- prices vary between 5-30€.
	</fieldset>
	<fieldset>
		<legend><h3>Services</h3></legend>
		<?php
			foreach(db_get_services() as list($abbr, $mylink, $name)){
				if(!strpos($mylink,"://"))
					$mylink="https://".$mylink;
				genHeBiLink(rwicon($abbr),$name,$mylink);
			}
		?>
	</fieldset>
	<fieldset id="trackfieldset">
		<legend><h3>Tracks</h3></legend>
		<span>Placeholder for filter settings</span>
	</fieldset>
	<?php include("../befuncs/masterplayer.html") ?>
	<div id="tracks">
		<a id="tracks_header">
			<span>Name</span>
			<span>status</span>
			<span>requester</span>
			<span>release date</span>
			<div></div>
		</a>
		<?php
			$data = db_get_songs('2100',10);
			$lastdate = echo_html_from_songlist($data);
		?>
	</div>
	<?php
		if(db_get_song_count()>10)
			echo "<div id='loadmore' data-lastdate='$lastdate'><a class='btn'>Load More</a></div>";
	?>
	<?php genFooter(); ?>
</body>
</html>