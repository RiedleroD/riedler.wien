<?php
	include("../../befuncs/snips.php");
	include("../../befuncs/db.php");
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
	genUsual("Riedler - ".$song['name'],"@import '../../style/play.css'","");
	$prevsong = db_get_previous_song($song['date']);
	$nextsong = db_get_next_song($song['date']);
	$prevsongwt = db_get_previous_song_with_type($song['date'],$song['type']);
	$nextsongwt = db_get_next_song_with_type($song['date'],$song['type']);
?>
<body>
	<?php genNavBar(); ?>
	<div id="play_nav">
		<div><!-- TODO: color coded buttons by song types -->
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
		<div id="play_files">
		</div>
		<div id="play_links">
		</div>
	</div>
	<!-- TODO: audio player -->
	<!-- TODO: file formats with play buttons for audio player & download button & file size -->
	<!-- TODO: available on the following services: ... -->
	<!-- TODO: fix bug with navbar -->
	<!-- TODO: style text selections -->
	<?php genFooter(); ?>
</body>
</html>