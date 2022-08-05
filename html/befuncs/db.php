<?php
	function _db_connect(){
		$db = new PDO("mysql:host=localhost;dbname=rwienmusic","riedlerwien");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}
	function _db_get_pq($db,$query,$args){
		$stmt = $db->prepare($query);
		$stmt->execute($args);
		return $stmt;
	}
	function _db_get_tpq($db,$query,$args,$types){
		$stmt = $db->prepare($query);
		for($i=0;$i<count($args);$i++){
			$stmt->bindparam($i+1,$args[$i],$types[$i]);
		}
		$stmt->execute();
		return $stmt;
	}
	function _hash_passwd($passwd){
		return hash('whirlpool',"riedler.wien".$passwd,true);
	}
	function db_get_services(){
		$db = _db_connect();
		return _db_get_pq($db,"SELECT abbr,mylink,name FROM Services ORDER BY prio ASC",[])->fetchAll();
	}
	function db_get_songs($start,$max){
		$db = _db_connect();
		$stmt = $db->prepare('SELECT s.id,s.name,s.type,s.status,r.name,DATE_FORMAT(s.date,"%d.%m.%Y"),NULL FROM Songs as s LEFT JOIN Users as r ON s.requesterid=r.id WHERE s.date<:maxdate ORDER BY date DESC LIMIT :maxresults');
		$stmt->bindparam(':maxdate',$start,PDO::PARAM_STR);
		$stmt->bindparam(':maxresults',$max,PDO::PARAM_INT);//whoever made PDO are idiots
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		$stmt = $db->prepare('SELECT ft.id,ft.mime FROM Files as f INNER JOIN Filetypes as ft ON f.filetypeid=ft.id where f.songid=?');
		for($i=0;$i<count($result);$i++){
			$stmt->bindparam(1,$result[$i][0],PDO::PARAM_INT);
			$stmt->execute();
			$result[$i][6]=array();
			foreach($stmt->fetchAll() as list($type,$mime)){
				$result[$i][6][$type]=$mime;
			}
		}
		return $result;
	}
	function db_get_song_by_id($id){
		$db = _db_connect();
		return _db_get_tpq($db,
			'SELECT name,type,status,requesterid,DATE_FORMAT(date,"%d.%m.%Y") FROM Songs WHERE id=?',
			[$id],[PDO::PARAM_INT])->fetch();
	}
	function db_get_filetype_by_id($id){
		$db = _db_connect();
		return _db_get_tpq($db,
			'SELECT name,mime,prio,ext FROM Filetypes WHERE id=?',
			[$id],[PDO::PARAM_INT])->fetch();
	}
?>