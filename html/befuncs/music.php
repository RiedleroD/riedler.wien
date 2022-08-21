<?php
	function genAudioPlayer($id,$songid,$items){
		echo "<button class='btn play'>".
				"<audio preload='none' id='player$id'>";
			foreach($items as $type=>$mime){
				echo "<source src='/music/download/?id=$songid&type=$type' type='$mime'/>";
			}
		echo '</audio></button>';
	}
	function genDownloadButton($songid,$typeid){
		echo "<a href='/music/download/?id=$songid&type=$typeid' class='btn dl'></a>";
	}
	function echo_html_from_songlist($data){
		$lastdate=null;
		foreach($data as $song){
			echo "<a href='./play?id=${song['id']}'>".
				"<span>${song['name']}</span>".
				"<span>${song['type']}</span>".
				"<span>${song['requester']}</span>".
				"<span>${song['fdate']}</span>";
			if(count($song['files'])>0){
				echo '</a>';
				genAudioPlayer($song['id'],$song['id'],$song['files']);
			}else
				echo '<span></span></a>';
			$lastdate = $song['date'];
		}
		return $lastdate;
	}
?>