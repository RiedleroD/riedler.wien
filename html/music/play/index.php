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
					echo '<a class="btn" href="./?id='.$prevsongwt['id'].'">Previous '.$prevsongwt['type'].'</a>';
				}
			?>
		</div>
		<div>
			<a class="btn" href="../">Back</a>
			<h2><?php echo $song['name'] ?></h2>
			<?php
				if($song['requester']){
					echo '<span>Requested by '.$song['requester'].'</span>';
				}
			?>
		</div>
		<div>
			<?php
				if($nextsong){
					echo '<a class="btn" href="./?id='.$nextsong['id'].'">Next Song</a>';
				}
				if($nextsongwt){
					echo '<a class="btn" href="./?id='.$nextsongwt['id'].'">Next '.$nextsongwt['type'].'</a>';
				}
			?>
		</div>
	</div>
	<!-- TODO: audio player -->
	<!-- TODO: file formats with play buttons for audio player & download button & file size -->
	<!-- TODO: erscheinungsjahr -->
	<!-- TODO: song type -->
	<!-- TODO: requested by: ... -->
	<!-- TODO: available on the following services: ... -->
	<?php genFooter(); ?>
</body>
</html>