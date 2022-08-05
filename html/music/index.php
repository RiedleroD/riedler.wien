<?php
	include("../befuncs/snips.php");
	include("../befuncs/db.php");
	genUsual("Riedler's Music","@import '../style/music.css'",'<script async src="../jizz/musicplayer.js"></script>');
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
		<br/>
		<div id="masterplayer" disabled>
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><path d="M25 90 49 74l0-48L25 10zM49 74 85 50l0 0L49 26z" fill="currentColor"/></svg>
			<div id="audioprog" alt="progress within the song">
				<div></div>
			</div>
			<span>0:00 / 0:00</span>
			<div id="audiovol" alt="music volume">
				<div></div>
			</div>
			<span>100%</span>
		</div>
	</fieldset>
	<div id="tracks">
		<a id="tracks_header">
			<span>Name</span>
			<span>status</span>
			<span>requester</span>
			<span>release date</span>
			<div></div>
		</a>
		<?php
			foreach(db_get_songs('2100',10) as list($id,$name,$type,$status,$requestername,$date,$files)){
				echo "<a href='./play?id=$id'>".
					"<span>$name</span>".
					"<span>$status</span>".
					"<span>$requestername</span>".
					"<span>$date</span>";
				if(count($files)>0){
					echo '</a>';
					genAudioPlayer($id,$files);
				}else
					echo '<span></span></a>';
			}
		?>
	</div>
	<?php genFooter(); ?>
</body>
</html>