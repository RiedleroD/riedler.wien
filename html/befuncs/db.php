<?php
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
?>