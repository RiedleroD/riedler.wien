<?php
	require_once('db.php');
	class codingdb extends db{
		//TODO: order by date & status once they're added
		const SELECTPROJECT ='SELECT p.id as id,p.name as name,p.shortdesc as shortdesc,'.
								'p.status as status,p.date as date,DATE_FORMAT(date,"%m.%Y") as mydate FROM CodingProjects as p';
		public function get_services(){
			return $this->get_pq("SELECT abbr,mylink,name FROM CodingServices WHERE id>0 ORDER BY prio ASC",[])->fetchAll();
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
				'SELECT s.name as name,s.abbr as abbr,CONCAT(s.plink,l.link) as link FROM CodingLinks as l '.
				'JOIN CodingServices as s ON l.serviceid=s.id '.
				'WHERE l.projectid=? ORDER BY s.prio',
				[$id],[PDO::PARAM_INT])->fetchAll();
		}
	}
?>