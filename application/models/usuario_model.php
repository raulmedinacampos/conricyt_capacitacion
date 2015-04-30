<?php
	class Usuario_model extends CI_Model {
		public function consultarUsuarioPorID($id) {
			$this->db->select('u.nombre, u.ap_paterno, u.ap_materno, u.sexo');
			$this->db->from('usuario u');
			$this->db->where('u.id_usuario', $id);
			$query = $this->db->get();
			
			if ( $query->num_rows() > 0 ) {
				return $query->row();
			}
		}
		
		public function consultarCursosInscritos($usuario) {
			$this->db->select('c.id_curso, c.curso, c.nombre_corto, c.ruta_imagen');
			$this->db->from('usuario_curso uc');
			$this->db->join('curso c', 'uc.curso = c.id_curso');
			$this->db->where('uc.usuario', $usuario);
			$query = $this->db->get();
			
			if ( $query->num_rows() > 0 ) {
				return $query;
			}
		}
		
		public function consultarCursosDisponibles($usuario) {
			$this->db->select('c.id_curso, c.curso, c.nombre_corto, c.estatus');
			$this->db->from('curso c');
			$this->db->where('c.id_curso NOT IN(', "SELECT curso FROM usuario_curso WHERE usuario = $usuario)", FALSE);
			$this->db->where('c.estatus >', 0);
			$query = $this->db->get();
			
			if ( $query->num_rows() > 0 ) {
				return $query;
			}
		}
	}
?>