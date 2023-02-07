<?php
	require_once('db.php');
	class musicdb extends db{
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
				$notypes.=' AND type!=\'Original\'';
			if($norremixes)
				$notypes.=' AND type!=\'RRemix\'';
			if($nocommissions)
				$notypes.=' AND type!=\'Commission\'';
			if($norrequests)
				$notypes.=' AND type!=\'RRequested\'';
			$stmt = $this->db->prepare('SELECT * FROM SongsWithData WHERE date<:maxdate'.$notypes.' ORDER BY date DESC LIMIT :maxresults');
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
				'SELECT * FROM SongsWithData WHERE id=?',
				[$id],[PDO::PARAM_INT])->fetch();
		}
		public function get_previous_song($date){
			return $this->get_pq(
				'SELECT * FROM SongsWithData WHERE date<? ORDER BY date DESC LIMIT 1',
				[$date])->fetch();
		}
		public function get_next_song($date){
			return $this->get_pq(
				'SELECT * FROM SongsWithData WHERE date>? ORDER BY date ASC LIMIT 1',
				[$date])->fetch();
		}
		public function get_previous_song_with_type($date,$type){
			return $this->get_pq(
				'SELECT * FROM SongsWithData WHERE date<? AND type=? ORDER BY date DESC LIMIT 1',
				[$date,$type])->fetch();
		}
		public function get_next_song_with_type($date,$type){
			return $this->get_pq(
				'SELECT * FROM SongsWithData WHERE date>? AND type=? ORDER BY date ASC LIMIT 1',
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
		public function set_vote($songid,$userid,$type){
			$this->get_tpq(
				'DELETE FROM SongRatings WHERE songid=? AND userid=?',
				[$songid,$userid],[PDO::PARAM_INT,PDO::PARAM_INT]
			);
			$this->get_tpq(
				'INSERT INTO SongRatings VALUES (?,?,?)',
				[$songid,$userid,$type],[PDO::PARAM_INT,PDO::PARAM_INT,PDO::PARAM_STR]
			);
		}
		public function get_single_vote($songid,$userid){
			return $this->get_tpq(
				'SELECT type FROM SongRatings WHERE songid=? AND userid=?',
				[$songid,$userid],[PDO::PARAM_INT,PDO::PARAM_INT])->fetch();
		}
	}
?>