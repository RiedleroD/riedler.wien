<?php
	abstract class db{
		public $db;
		public function __construct(){
			$this->db = new PDO("mysql:host=localhost;dbname=rwien;charset=utf8mb4","riedlerwien");
			$this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		public function get_pq($query,$args){
			$stmt = $this->db->prepare($query);
			$stmt->execute($args);
			return $stmt;
		}
		public function get_tpq($query,$args,$types){
			$stmt = $this->db->prepare($query);
			for($i=0;$i<count($args);$i++){
				$stmt->bindparam($i+1,$args[$i],$types[$i]);
			}
			$stmt->execute();
			return $stmt;
		}
	}
?>