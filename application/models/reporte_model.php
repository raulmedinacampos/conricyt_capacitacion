<?php
class Reporte_model extends CI_Model {
	public function leerEntidades() {
		$this->db->select('e.id_entidad, e.entidad');
		$this->db->from('entidad e');
		$this->db->where('e.estatus', 1);
		$this->db->order_by('e.entidad');
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function leerRegiones() {
		$this->db->select('r.id_region, r.region');
		$this->db->from('region r');
		$this->db->where('r.estatus', 1);
		$this->db->order_by('r.region');
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function leerCursos() {
		$this->db->select('c.id_curso, c.curso');
		$this->db->from('curso c');
		$this->db->where('c.estatus >', 0);
		$this->db->order_by('c.curso');
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function consultarPorGenero() {
		$this->db->select('u.sexo, COUNT(*) AS total', FALSE);
		$this->db->from('usuario u');
		$this->db->where('u.estatus', 1);
		$this->db->group_by('u.sexo');
		$this->db->order_by('u.sexo');
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function consultarPorEntidad($entidad) {
		$this->db->select('u.entidad, COUNT(*) AS total', FALSE);
		$this->db->from('usuario u');
		$this->db->where('u.entidad', $entidad);
		$this->db->where('u.estatus', 1);
		$this->db->group_by('u.entidad');
		$query = $this->db->get();
		
		if ( $query->num_rows() > 0 ) {
			$total = $query->row();
			return $total->total;
		}
	}
	
	public function consultarPorRegion($region) {
		$this->db->select('e.region, COUNT(*) AS total', FALSE);
		$this->db->from('usuario u');
		$this->db->join('entidad e', 'u.entidad = e.id_entidad');
		$this->db->where('e.region', $region);
		$this->db->where('u.estatus', 1);
		$this->db->group_by('e.region');
		$query = $this->db->get();
	
		if ( $query->num_rows() > 0 ) {
			$total = $query->row();
			return $total->total;
		}
	}
	
	public function consultarPorInstitucion() {
		$this->db->select('i.institucion, COUNT(*) AS total', FALSE);
		$this->db->from('usuario u');
		$this->db->join('institucion i', 'u.id_institucion = i.id_institucion');
		$this->db->where('u.estatus', 1);
		$this->db->group_by('i.institucion');
		$this->db->order_by('i.institucion');
		$query = $this->db->get();
	
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function consultarPorOtraInstitucion() {
		$this->db->select('u.institucion, COUNT(*) AS total', FALSE);
		$this->db->from('usuario u');
		$this->db->where('u.institucion <>', "''", FALSE);
		$this->db->where('u.estatus', 1);
		$this->db->group_by('u.institucion');
		$this->db->order_by('u.institucion');
		$query = $this->db->get();
	
		if ( $query->num_rows() > 0 ) {
			return $query;
		}
	}
	
	public function consultarPorCurso($curso) {
		$this->db->select('uc.curso, COUNT(*) AS total', FALSE);
		$this->db->from('usuario_curso uc');
		$this->db->where('uc.curso', $curso);
		$this->db->where('uc.estatus', 1);
		$this->db->group_by('uc.curso');
		$query = $this->db->get();
	
		if ( $query->num_rows() > 0 ) {
			$total = $query->row();
			return $total->total;
		}
	}
}
?>