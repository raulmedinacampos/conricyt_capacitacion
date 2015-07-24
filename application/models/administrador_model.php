<?php
class Administrador_model extends CI_Controller {
	public function listarUsuarios($where="") {
		/*$this->db->select('u.id_usuario, u.nombre, u.ap_paterno, u.ap_materno, u.login, u.password, i.institucion, u.institucion AS inst');
		$this->db->from('usuario u');
		$this->db->join('institucion i', 'u.id_institucion = i.id_institucion');*/
		
		$select = 'SELECT "u"."id_usuario", "u"."nombre", "u"."ap_paterno", "u"."ap_materno", "u"."login", "u"."password", "i"."institucion", "u"."institucion" AS inst FROM "usuario" u JOIN "institucion" i ON "u"."id_institucion" = "i"."id_institucion"';
		$order = 'ORDER BY "u"."nombre", "u"."ap_paterno", "u"."ap_materno"';				
		
		$nombre = "";
		$ap_paterno = "";
		$correo = "";
		$institucion = "";
		$ap_materno = "";
		
		if ( $where != "") {	
			
			if($where['nombre']!= "")
				$nombre=$where['nombre'];
			
			if($where['ap_paterno']!= "")
				$ap_paterno=$where['ap_paterno'];
			
			if($where['ap_materno']!= "")
				$ap_materno=$where['ap_materno'];
			
			if($where['correo']!= "")
				$correo=$where['correo'];
			
			if($where['i.institucion']!= "")
				$institucion=$where['i.institucion'];
			
			$like = "WHERE nombre ILIKE '%".$nombre."%' ESCAPE '!' AND ap_paterno ILIKE '%".$ap_paterno."%' ESCAPE '!' AND ap_materno ILIKE '%".$ap_materno."%' ESCAPE '!' AND correo ILIKE '%".$correo."%' ESCAPE '!' AND i.institucion ILIKE '%".$institucion."%' ESCAPE '!' ";
			
			$query = $this->db->query($select . $like . $order );
			//print_r($where);
			
			$this->db->like($where);
			
		}
		else $query = $this->db->query($select . $order );
		
		//$this->db->order_by('u.nombre, u.ap_paterno, u.ap_materno');
		//$this->db->limit(1);*/
		//$query = $this->db->get();
		
		//$cq = $this->db->last_query();
		//print_r($cq);
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function listarUsuarios2() {
		$this->db->select('u.id_usuario, u.nombre, u.ap_paterno, u.ap_materno, u.login, i.institucion, u.password,');
		$this->db->from('usuario u');
		$this->db->join('institucion i', 'u.id_institucion = i.id_institucion');
	
		$this->db->order_by('u.nombre, u.ap_paterno, u.ap_materno');
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