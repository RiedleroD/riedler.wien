<?php
	require_once('db.php');
	class accountdb extends db{
		public function login($name,$passwd){
			$user = $this->get_user_by_name($name);
			if($user == null){
				return false;
			}
			
			$hash = hash('sha256',$passwd);
			
			$result = ($hash == $user['passwd']);
			
			if($result)
				$_SESSION['userid'] = $user['id'];
			
			return $result;
		}
		public function logout(){
			$_SESSION['userid'] = 0;
		}
		public function get_user_by_name($name){
			return $this->get_pq(
				'SELECT id,passwd,type FROM Users WHERE name=?',
				[$name])->fetch();
		}
		public function get_user_by_id($id){
			return $this->get_tpq(
				'SELECT name,passwd,type FROM Users WHERE id=?',
				[$id],[PDO::PARAM_INT])->fetch();
		}
	}
?>