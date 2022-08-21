<?php
	include("../../befuncs/snips.php");
	include("../../befuncs/music.php");
	include("../../befuncs/db_music.php");
	if(!array_key_exists('id',$_GET)){
		http_response_code(400);
		echo 'mandatory ID parameter not specified';
		exit();
	}
	$song = db_get_song_by_id($_GET['id']);
	if($song==null){
		http_response_code(404);
		echo 'ID doesn\'t match any song: '.$_GET['id'];
		exit;
	}
	genUsual('Riedler - '.$song['name'],'@import "../../style/play.css"','<script async src="../../jizz/musicplayer.js"></script>');
	$prevsong = db_get_previous_song($song['date']);
	$nextsong = db_get_next_song($song['date']);
	$prevsongwt = db_get_previous_song_with_type($song['date'],$song['type']);
	$nextsongwt = db_get_next_song_with_type($song['date'],$song['type']);
?>
<body>
	<?php genNavBar(); ?>
	<div id="play_nav">
		<div>
			<?php
				if($prevsong){
					echo '<a class="btn" href="./?id='.$prevsong['id'].'">Previous Song</a>';
				}
				if($prevsongwt){
					echo '<a class="btn type_'.$prevsongwt['type'].'" href="./?id='.$prevsongwt['id'].'">Previous '.$prevsongwt['type'].'</a>';
				}
			?>
		</div>
		<div>
			<a class="btn" href="../">Back</a>
			<?php echo "<h2 class='type_${song['type']}'>${song['name']}</h2>" ?>
		</div>
		<div>
			<?php
				if($nextsong){
					echo '<a class="btn" href="./?id='.$nextsong['id'].'">Next Song</a>';
				}
				if($nextsongwt){
					echo '<a class="btn type_'.$nextsongwt['type'].'" href="./?id='.$nextsongwt['id'].'">Next '.$nextsongwt['type'].'</a>';
				}
			?>
		</div>
	</div>
	<div id="play_content">
		<div id="play_info">
			<?php
				if($song['requester']){
					echo "<span>Requester</span><span>${song['requester']}</span>";
				}
				echo "<span>Release Date</span><span>${song['fdate']}</span>";
				echo "<span>Type</span><span class='type_${song['type']}'>${song['type']}</span>";
				echo "<span>Status</span><span>${song['status']}</span>";
				if($song['comment']){
					echo "<div>${song['comment']}</div>";
				}
			?>
		</div>
		<fieldset id="play_files">
			<?php
				$files=db_get_files_by_songid($song['id']);
				if(count($files)==0){
					echo '<legend>Due to copyright and/or skill issues, this track does not have any Files associated with it.</legend>';
				}else{
					echo '<legend><h3>Files:</h3></legend><div id="tracks">';
					include("../../befuncs/masterplayer.html");
					foreach($files as list($typeid,$mime,$typename)){
						genAudioPlayer($typeid,$song['id'],array($typeid=>$mime));
						genDownloadButton($song['id'],$typeid);
						echo "<span>$typename</span>";
					}
					echo '</div>';
				}
			?>
		</fieldset>
		<fieldset id="play_links">
			<legend><h3>Available on other services:</h3></legend>
			<?php
				foreach(db_get_links_by_songid($song['id']) as $service){
					$icon = rwicon($service['abbr']);
					$lnk = $service['link'];
					if(!strpos($lnk,'://')){
						$lnk='https://'.$lnk;
					}
					echo "<a href='$lnk' class='btn hebi'><img src='$icon'/>${service['name']}</a>";
				}
			?>
		</fieldset>
	</div>
	<?php genFooter(); ?>
</body>
</html>