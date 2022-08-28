<?php
	include('db.php');
	//TODO: order by date & status once they're added
	const SELECTPROJECT =	'SELECT p.id as id,p.name as name,p.shortdesc as shortdesc,'.
							'p.status as status,p.date as date,DATE_FORMAT(date,"%m.%Y") as mydate FROM Projects as p';
	function _db_connect(){
		$db = new PDO("mysql:host=localhost;dbname=rwiencoding","riedlerwien");
		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $db;
	}
	function db_get_services(){
		$db = _db_connect();
		return _db_get_pq($db,"SELECT abbr,mylink,name FROM Services ORDER BY prio ASC",[])->fetchAll();
	}
	function db_get_project_by_id($id){
		return _db_get_tpq(_db_connect(),
			SELECTPROJECT.' WHERE p.id=?',
			[$id],[PDO::PARAM_INT])->fetch();
	}
	function db_get_projects(){
		return _db_get_pq(_db_connect(),
			SELECTPROJECT.' ORDER BY status ASC,date DESC',[])->fetchAll();
	}
	function db_get_links_by_pid($id){
		return _db_get_tpq(_db_connect(),
			'SELECT s.name as name,s.abbr as abbr,CONCAT(s.plink,l.link) as link FROM Links as l '.
			'JOIN Services as s ON l.serviceid=s.id '.
			'WHERE l.projectid=? ORDER BY s.prio',
			[$id],[PDO::PARAM_INT])->fetchAll();
	}
?>