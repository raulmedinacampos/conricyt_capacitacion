<?php
class Acceso_model extends CI_Model {
	public function getDataByID($id) {
		$this->db->select('id_usuario, login, password');
		$this->db->from('usuario');
		$this->db->where('id_usuario', $id);
		$this->db->where('estatus >', 0);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getDataByMail($mail) {
		$this->db->select('id_usuario, login, password');
		$this->db->from('usuario');
		$this->db->where('correo', $mail);
		$this->db->where('estatus >', 0);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
}
?>