<?php
class Administrador_model extends CI_Controller {
	public function buscarUsuariosInactivos() {
		$this->db->select("ue.id, l.userid, l.courseid, l.timeaccess");
		$this->db->from("mdl_user_lastaccess l");
		$this->db->join("mdl_user_enrolments ue", "l.userid = ue.userid");
		$this->db->join("mdl_enrol e", "ue.enrolid = e.id");
		$this->db->where("e.courseid = l.courseid");
		$this->db->where("e.status = 0");
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function actualizarEstatusUsuario($id, $estatus) {
		$this->db->set("status", $estatus);
		$this->db->where("id", $id);
		
		if ( $this->db->update("mdl_user_enrolments" )) {
			return true;
		}
	}
}
?>