<?php
	const SELECTSONG = 'SELECT s.id as id,s.name as name,type,status,r.name as requester,DATE_FORMAT(date,"%d.%m.%Y") as fdate,date,c.comment FROM Songs as s '.
	'LEFT JOIN Users as r ON s.requesterid=r.id '.
	'LEFT JOIN Comments as c ON c.songid=s.id';
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
	function db_get_song_count(){
		$db = _db_connect();
		return (int)(_db_get_pq($db,"SELECT COUNT(id) FROM Songs",[])->fetch()[0]);
	}
	function db_get_songs($start,$max){
		$db = _db_connect();
		$stmt = $db->prepare(SELECTSONG.' WHERE s.date<:maxdate ORDER BY date DESC LIMIT :maxresults');
		$stmt->bindparam(':maxdate',$start,PDO::PARAM_STR);
		$stmt->bindparam(':maxresults',$max,PDO::PARAM_INT);//whoever made PDO are idiots
		$stmt->execute();
		$result = $stmt->fetchAll();
		
		for($i=0;$i<count($result);$i++){
			$result[$i]['files']=array();
			foreach(_db_get_files_by_songid($db,$result[$i]['id']) as list($type,$mime,$name)){
				$result[$i]['files'][$type]=$mime;
			}
		}
		
		return $result;
	}
	function db_get_song_by_id($id){
		$db = _db_connect();
		return _db_get_tpq($db,
			SELECTSONG.' WHERE s.id=?',
			[$id],[PDO::PARAM_INT])->fetch();
	}
	function db_get_previous_song($date){
		return _db_get_pq(_db_connect(),
			SELECTSONG.' WHERE date<? ORDER BY date DESC LIMIT 1',
			[$date])->fetch();
	}
	function db_get_next_song($date){
		return _db_get_pq(_db_connect(),
			SELECTSONG.' WHERE date>? ORDER BY date ASC LIMIT 1',
			[$date])->fetch();
	}
	function db_get_previous_song_with_type($date,$type){
		return _db_get_pq(_db_connect(),
			SELECTSONG.' WHERE date<? AND type=? ORDER BY date DESC LIMIT 1',
			[$date,$type])->fetch();
	}
	function db_get_next_song_with_type($date,$type){
		return _db_get_pq(_db_connect(),
			SELECTSONG.' WHERE date>? AND type=? ORDER BY date ASC LIMIT 1',
			[$date,$type])->fetch();
	}
	function db_get_filetype_by_id($id){
		return _db_get_tpq(_db_connect(),
			'SELECT name,mime,prio,ext FROM Filetypes WHERE id=?',
			[$id],[PDO::PARAM_INT])->fetch();
	}
	function db_get_links_by_songid($id){
		return _db_get_tpq(_db_connect(),
			'SELECT s.name as name,s.abbr,CONCAT(s.songlink,l.link) as link FROM Links as l '.
			'JOIN Services as s ON l.serviceid=s.id '.
			'WHERE l.songid=? ORDER BY s.prio',
			[$id],[PDO::PARAM_INT])->fetchAll();
	}
	function _db_get_files_by_songid($db,$id){
		return _db_get_tpq($db,
			'SELECT ft.id,ft.mime,ft.name FROM Files as f INNER JOIN Filetypes as ft ON f.filetypeid=ft.id where f.songid=?',
			[$id],[PDO::PARAM_INT])->fetchAll();
	}
	function db_get_files_by_songid($id){
		return _db_get_files_by_songid(_db_connect(),$id);
	}
?>