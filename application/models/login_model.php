<?php
class Login_model extends CI_Model {
	public function verificarUsuario($data) {
		$this->db->select('id_usuario');
		$this->db->from('usuario');
		$this->db->where($data);
		$this->db->where('estatus >', 0);
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query->row();
		}
	}
}
?>