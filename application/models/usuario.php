<?php
	class Usuario extends CI_Model {
		public function login($user, $pass) {
			$str = "SELECT id_usuario, login, password FROM usuario WHERE login = '$user' AND password = '$pass' LIMIT 1";
			$query = $this->db->query($str);
			
			if($query->num_rows() > 0) {
				return $query;
			} else {
				return false;
			}
		}
	}
?>