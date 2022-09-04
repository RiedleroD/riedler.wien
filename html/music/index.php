<?php
	include("../befuncs/snips.php");
	include("../befuncs/music.php");
	include("../befuncs/db_music.php");
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
			services_as_html(db_get_services());
		?>
	</fieldset>
	<fieldset id="trackfieldset">
		<legend><h3>Tracks</h3></legend>
		<form id="filter_form">
			<input id="filter_originals" type="checkbox" checked autocomplete="off"/>
			<label for="filter_originals" class="btn hebi"><span></span><span>Originals</span></label>
			<input id="filter_commissions" type="checkbox" checked autocomplete="off"/>
			<label for="filter_commissions" class="btn hebi"><span></span><span>Commissions</span></label>
			<input id="filter_rremixes" type="checkbox" checked autocomplete="off"/>
			<label for="filter_rremixes" class="btn hebi"><span></span><span>RRemixes</span></label>
			<input id="filter_rrequests" type="checkbox" checked autocomplete="off"/>
			<label for="filter_rrequests" class="btn hebi"><span></span><span>RRequests</span></label>
			<br/>
			<span class="btn hebi"><input id="filter_amount" type="number" min="1" max="50" step="1" value="10" autocomplete="off"/>Results per load</span>
			<br/>
			<br/>
			<button type="submit" class="btn">Apply Filters</button>
		</form>
	</fieldset>
	<?php include("../befuncs/masterplayer.html") ?>
	<div id="tracks">
		<a id="tracks_header">
			<span>Name</span>
			<span>type</span>
			<span>requester</span>
			<span>release date</span>
			<div></div>
		</a>
		<?php
			$data = db_get_songs('2100-01-01',10);
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