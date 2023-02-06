<?php
	require_once("../../befuncs/snips.php");
	require_once("../../befuncs/music.php");
	require_once("../../befuncs/db_music.php");
	if(!array_key_exists('id',$_GET)){
		http_response_code(400);
		echo 'mandatory ID parameter not specified';
		exit();
	}
	$db=new musicdb();
	$song = $db->get_song_by_id($_GET['id']);
	if($song==null){
		http_response_code(404);
		echo 'ID doesn\'t match any song: '.$_GET['id'];
		exit;
	}
	genUsual(
		'Riedler - '.$song['name'],
		['/style/play.css'],
		'<script async src="/jizz/musicplayer.js"></script><script async src="/jizz/songlike.js"></script>'
	);
	$prevsong = $db->get_previous_song($song['date']);
	$nextsong = $db->get_next_song($song['date']);
	$prevsongwt = $db->get_previous_song_with_type($song['date'],$song['type']);
	$nextsongwt = $db->get_next_song_with_type($song['date'],$song['type']);
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
			if($_SESSION['userid']!=0){
				$like_attrs = 'songid='.$song['id'];
				$dislike_attrs = $like_attrs;
				$uservote = $db->get_single_vote($song['id'],$_SESSION['userid'])['type'];
				if($uservote=='Like'){
					$like_attrs.=' class="active"';
				}else if($uservote=='Dislike'){
					$dislike_attrs.=' class="active"';
				}
			}else{
				$like_attrs = 'disabled';
				$dislike_attrs = 'disabled';
			}
			?>
			<span>Release Date</span><span><?= $song['fdate'] ?></span>
			<span>Type</span><span class='type_<?= $song['type'] ?>'><?= $song['type'] ?></span>
			<span>Status</span><span><?= $song['status'] ?></span>
			<button id='like' <?= $like_attrs ?>>
				<?= $song['likes'] ?>
				<?= file_get_contents("../../resource/icons/like.svg") ?>
			</button>
			<button id='dislike' <?= $dislike_attrs ?>>
				<?= file_get_contents("../../resource/icons/like.svg") ?>
				<?= $song['dislikes'] ?>
			</button>
		</div>
		<?php
			if($song['comment']){
				echo "<fieldset id='play_comment'>${song['comment']}</fieldset>";
			}
		?>
		<fieldset id="play_files">
			<?php
				$files=$db->get_files_by_songid($song['id']);
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
				foreach($db->get_links_by_songid($song['id']) as $service){
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