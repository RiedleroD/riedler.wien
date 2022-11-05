<?php
	abstract class db{
		abstract public function __construct();
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
		public static function hash_passwd($passwd){
			return hash('whirlpool',"riedler.wien".$passwd,true);
		}
		abstract public function get_services();
	}
?>