<?php
	require_once('db.php');
	
	enum UserRole {
		case User;
		case Admin;
	}
	
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
		public function add_user(string $name,string $passwdhash,UserRole $role){
			
			if(strlen($passwdhash)!=64)
				throw new Exception("invalid password hash");
			if($this->get_pq('SELECT id FROM Users WHERE name=?',[$name])->fetch())
				throw new Exception("name already exists");
			
			do{
				$id = rand(2,2**16);//assume minimum 16 bit integer (max. 66k users)
				$exists = $this->get_tpq(
					'SELECT id FROM Users WHERE id=?',
					[$id],[PDO::PARAM_INT])->fetch();
			}while($exists!==false);
			
			$this->get_tpq(
				'INSERT INTO Users (id,name,passwd,type) VALUES (?,?,?,?)',
				[$id,$name,$passwdhash,$role->name],
				[PDO::PARAM_INT,PDO::PARAM_STR,PDO::PARAM_STR,PDO::PARAM_STR]);
		}
	}
?>