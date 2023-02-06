<?php
	require_once('db.php');
	class musicdb extends db{
		const SELECTSONG = 'SELECT s.id as id,s.name as name,s.type,status,r.name as requester,DATE_FORMAT(date,"%d.%m.%Y") as fdate,date,c.comment FROM Songs as s '.
		'LEFT JOIN Users as r ON s.requesterid=r.id '.
		'LEFT JOIN Comments as c ON c.songid=s.id';
		public function __construct(){
			$this->db = new PDO("mysql:host=localhost;dbname=rwienmusic","riedlerwien");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		public function get_services(){
			return $this->get_pq("SELECT abbr,mylink,name FROM Services ORDER BY prio ASC",[])->fetchAll();
		}
		public function get_song_count(){
			return (int)($this->get_pq("SELECT COUNT(id) FROM Songs",[])->fetch()[0]);
		}
		public function get_songs($start,$max,$nooriginals=false,$norremixes=false,$nocommissions=false,$norrequests=false){
			$notypes = '';
			if($nooriginals)
				$notypes.=' AND s.type!=\'Original\'';
			if($norremixes)
				$notypes.=' AND s.type!=\'RRemix\'';
			if($nocommissions)
				$notypes.=' AND s.type!=\'Commission\'';
			if($norrequests)
				$notypes.=' AND s.type!=\'RRequested\'';
			$stmt = $this->db->prepare($this::SELECTSONG.' WHERE s.date<:maxdate'.$notypes.' ORDER BY date DESC LIMIT :maxresults');
			$stmt->bindparam(':maxdate',$start,PDO::PARAM_STR);
			$stmt->bindparam(':maxresults',$max,PDO::PARAM_INT);//whoever made PDO are idiots
			$stmt->execute();
			$result = $stmt->fetchAll();
			
			for($i=0;$i<count($result);$i++){
				$result[$i]['files']=array();
				foreach($this->get_files_by_songid($result[$i]['id']) as list($type,$mime,$name)){
					$result[$i]['files'][$type]=$mime;
				}
			}
			
			return $result;
		}
		public function get_song_by_id($id){
			return $this->get_tpq(
				$this::SELECTSONG.' WHERE s.id=?',
				[$id],[PDO::PARAM_INT])->fetch();
		}
		public function get_previous_song($date){
			return $this->get_pq(
				$this::SELECTSONG.' WHERE date<? ORDER BY date DESC LIMIT 1',
				[$date])->fetch();
		}
		public function get_next_song($date){
			return $this->get_pq(
				$this::SELECTSONG.' WHERE date>? ORDER BY date ASC LIMIT 1',
				[$date])->fetch();
		}
		public function get_previous_song_with_type($date,$type){
			return $this->get_pq(
				$this::SELECTSONG.' WHERE date<? AND s.type=? ORDER BY date DESC LIMIT 1',
				[$date,$type])->fetch();
		}
		public function get_next_song_with_type($date,$type){
			return $this->get_pq(
				$this::SELECTSONG.' WHERE date>? AND s.type=? ORDER BY date ASC LIMIT 1',
				[$date,$type])->fetch();
		}
		public function get_filetype_by_id($id){
			return $this->get_tpq(
				'SELECT name,mime,prio,ext FROM Filetypes WHERE id=?',
				[$id],[PDO::PARAM_INT])->fetch();
		}
		public function get_links_by_songid($id){
			return $this->get_tpq(
				'SELECT s.name as name,s.abbr,CONCAT(s.songlink,l.link) as link FROM Links as l '.
				'JOIN Services as s ON l.serviceid=s.id '.
				'WHERE l.songid=? ORDER BY s.prio',
				[$id],[PDO::PARAM_INT])->fetchAll();
		}
		public function get_files_by_songid($id){
			return $this->get_tpq(
				'SELECT ft.id,ft.mime,ft.name FROM Files as f INNER JOIN Filetypes as ft ON f.filetypeid=ft.id where f.songid=?',
				[$id],[PDO::PARAM_INT])->fetchAll();
		}
	}
?>