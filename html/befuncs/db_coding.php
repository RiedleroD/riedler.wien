<?php
	require_once('db.php');
	class codingdb extends db{
		public $db;
		//TODO: order by date & status once they're added
		const SELECTPROJECT ='SELECT p.id as id,p.name as name,p.shortdesc as shortdesc,'.
								'p.status as status,p.date as date,DATE_FORMAT(date,"%m.%Y") as mydate FROM Projects as p';
		public function __construct(){
			$this->db = new PDO("mysql:host=localhost;dbname=rwiencoding","riedlerwien");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		public function get_services(){
			return $this->get_pq("SELECT abbr,mylink,name FROM Services WHERE id>0 ORDER BY prio ASC",[])->fetchAll();
		}
		public function get_project_by_id($id){
			return $this->get_tpq(
				$this::SELECTPROJECT.' WHERE p.id=?',
				[$id],[PDO::PARAM_INT])->fetch();
		}
		public function get_projects(){
			return $this->get_pq(
				$this::SELECTPROJECT.' ORDER BY status ASC,date DESC',
				[])->fetchAll();
		}
		public function get_links_by_pid($id){
			return $this->get_tpq(
				'SELECT s.name as name,s.abbr as abbr,CONCAT(s.plink,l.link) as link FROM Links as l '.
				'JOIN Services as s ON l.serviceid=s.id '.
				'WHERE l.projectid=? ORDER BY s.prio',
				[$id],[PDO::PARAM_INT])->fetchAll();
		}
	}
?>