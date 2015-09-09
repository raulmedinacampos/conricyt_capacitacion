<?php
class Registro_model extends CI_Model {
	public function leerPaises() {
		$this->db->select("id_pais, pais");
		$this->db->from("cat_pais");
		$this->db->order_by("pais");
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function leerEntidades() {
		$this->db->select("id_entidad, entidad");
		$this->db->from("entidad");
		$this->db->where("estatus", 1);
		$this->db->order_by("entidad");
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function leerPerfiles() {
		$this->db->select("id_perfil, perfil");
		$this->db->from("cat_perfil");
		$this->db->where("estatus", 1);
		$this->db->order_by("perfil");
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function leerInstituciones() {
		$this->db->select("id_institucion, institucion");
		$this->db->from("institucion");
		$this->db->where("estatus", 1);
		$this->db->order_by("institucion");
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function consultarCursos($cursos) {
		$this->db->select("id_curso, curso");
		$this->db->from("curso");
		$this->db->where_in("nombre_corto", $cursos);
		$this->db->where("estatus >", 0);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query;
		}
	}
	
	public function consultarUsuarioExistente($correo) {
		$this->db->select('id_usuario');
		$this->db->from('usuario');
		$this->db->where('correo', $correo);
		$this->db->where('estatus >', 0);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function consultarUsuarioPorID($id) {
		$this->db->select('u.id_usuario, u.fecha_alta, u.nombre, u.ap_paterno, u.ap_materno, u.correo, i.institucion, u.institucion AS otra_institucion');
		$this->db->from('usuario u');
		$this->db->join('institucion i', 'u.id_institucion = i.id_institucion');
		$this->db->where('u.id_usuario', $id);
		$query = $this->db->get();
		
		if($query->num_rows() > 0) {
			return $query->row_array();
		}
	}
	
	public function consultarCredencialesPorID($id) {
		$this->db->select('u.login, u.password');
		$this->db->from('usuario u');
		$this->db->where('u.id_usuario', $id);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row_array();
		}
	}
	
	public function verificarUsuarioEnCurso($usuario, $curso) {
		$this->db->from('usuario_curso');
		$this->db->where('usuario', $usuario);
		$this->db->where('curso', $curso);
		$this->db->where('estatus', 1);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return true;
		}
	}
	
	public function verificarUsuarioExistente($correo) {
		$this->db->select('id_usuario');
		$this->db->from('usuario');
		$this->db->like('correo', $correo, 'none');
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return true;
		}
	}
	
	public function insertarUsuario($data) {
		if($this->db->insert('usuario', $data)) {
			return $this->db->insert_id();
		}
	}
	
	public function insertarUsuarioCurso($data) {
		if($this->db->insert('usuario_curso', $data)) {
			return true;
		}
	}
	
	/* Métodos para Moodle */
	public function getShortnameByID($id) {
		$this->db->select('id_curso, nombre_corto');
		$this->db->from('curso');
		$this->db->where('id_curso', $id);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleCourse($shortname) {
		$this->db->select('id');
		$this->db->from('mdl_course');
		$this->db->where('shortname', $shortname);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleContext($instance) {
		$this->db->select('id');
		$this->db->from('mdl_context');
		$this->db->where('instanceid', $instance);
		$this->db->where('contextlevel', 50);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function getMoodleEnrol($course) {
		$this->db->select('id');
		$this->db->from('mdl_enrol');
		$this->db->where('courseid', $course);
		$this->db->where('enrol', 'manual');
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function checkMoodleUserExists($mail) {
		$this->db->select('id');
		$this->db->from('mdl_user');
		$this->db->where('email', $mail);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return $query->row();
		}
	}
	
	public function checkUserEnrollments($user, $enrolid) {
		$this->db->from('mdl_user_enrolments');
		$this->db->where('userid', $user);
		$this->db->where('enrolid', $enrolid);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return true;
		}
	}
	
	public function checkRoleAssignments($user, $contexid) {
		$this->db->from('mdl_role_assignments');
		$this->db->where('userid', $user);
		$this->db->where('contextid', $contexid);
		$query = $this->db->get();
	
		if($query->num_rows() > 0) {
			return true;
		}
	}
	
	public function updateMoodleUser($id, $data) {
		$this->db->where('id', $id);
		$this->db->update('mdl_user', $data);
	}
	
	public function insertMoodleUser($data) {
		$usr = '';
		if($this->db->insert('mdl_user', $data)) {
			$usr = $this->db->insert_id();
			return $usr;
		}
	}
	
	public function insertUserEnrollments($data) {
		if($this->db->insert('mdl_user_enrolments', $data)) {
			return true;
		}
	}
	
	public function insertRoleAssignments($data) {
		if($this->db->insert('mdl_role_assignments', $data)) {
			return true;
		}
	}
}
?>