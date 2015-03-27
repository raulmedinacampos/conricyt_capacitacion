<?php
class Registro_model extends CI_Model {
	public function leerPaises() {
		$this->db->select("id_pais, pais");
		$this->db->from("cat_paises");
		$this->db->order_by("pais");
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
}
?>