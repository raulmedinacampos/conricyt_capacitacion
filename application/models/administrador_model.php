<?php
class Administrador_model extends CI_Controller {
	public function listarUsuarios($where) {
		$this->db->select('u.id_usuario, u.nombre, u.ap_paterno, u.ap_materno, u.login, u.password, i.institucion, u.institucion AS inst');
		$this->db->from('usuario u');
		$this->db->join('institucion i', 'u.id_institucion = i.id_institucion');
		
		if ( $where ) {
			$this->db->like($where);
		}
		$this->db->order_by('u.nombre, u.ap_paterno, u.ap_materno');
		/*$this->db->limit(1);*/
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
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