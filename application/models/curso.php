<?php
	class Curso extends CI_Model {
		public function listado() {
			$this->db->select('id_curso, curso, nombre_corto, ruta_imagen');
			$this->db->from('curso');
			$this->db->where('estatus >', 0);
			$query = $this->db->get();
			
			if($query->num_rows() > 0) {
				return $query;
			} else {
				return false;
			}
		}
		
		public function detalle($curso) {
			$this->db->select('id_curso, curso, nombre_corto, ruta_imagen, descripcion, estatus');
			$this->db->from('curso');
			$this->db->where('id_curso', $curso);
			$query = $this->db->get();
			$result = $query->row();
			
			if($query->num_rows() > 0) {
				return $result;
			} else {
				return false;
			}
		}
		
		private function usuarioExiste($usuario) {
			$this->db->select('id_usuario');
			$this->db->from('usuario');
			$this->db->where('correo', $usuario);
			$query_usuario = $this->db->get();
			
			if($query_usuario->num_rows() > 0) {
				return true;  // Usuario existente
			} else {
				return false;
			}
		}
		
		private function validaUsuario($usr, $pass) {
			$this->db = $this->load->database('dev', TRUE);
			$this->db->select('id_usuario');
			$this->db->from('usuario');
			$this->db->where('correo', $usr);
			$this->db->where('password', $pass);
			$this->db->where('estatus', 1);
			$query_usuario_local = $this->db->get();
			$row_usuario_local = $query_usuario_local->row();
			
			if($query_usuario_local->num_rows() > 0) {
				return true;
			} else {
				return false;
			}
		}
		
		private function revisaUsuarioEnCurso($id_usuario, $curso) {
			$this->db = $this->load->database('dev', TRUE);
			$this->db->select('id_curso, nombre_corto');
			$this->db->from('curso');
			$this->db->where('nombre_corto', $curso);
			$this->db->where('estatus', 1);
			$query_curso = $this->db->get();
			$row_curso = $query_curso->row();
			
			$this->db->select('usuario, curso');
			$this->db->from('usuario_curso');
			$this->db->where('usuario', $id_usuario);
			$this->db->where('curso', $row_curso->id_curso);
			$query_registro = $this->db->get();
			
			if($query_registro->num_rows() > 0) {
				return true;  // Ya está inscrito
			} else {
				return false;  // No está inscrito
			}
		}
		
		public function matricular($usr, $pass, $curso) {
			$this->db = $this->load->database('moodle', TRUE);
			$estatus = 0;
			// 1 -> OK. Matriculado al curso
			// 2 -> Usuario no existe
			// 3 -> Error al insertar curso
			// 4 -> Ya está inscrito
			$this->db->select('id, username');
			$this->db->from('mdl_user');
			$this->db->where('username', $usr);
			$query_usuario_moodle = $this->db->get();
			$row_usuario_moodle = $query_usuario_moodle->row();
			
			$this->db = $this->load->database('dev', TRUE);
			$this->db->select('id_usuario');
			$this->db->from('usuario');
			$this->db->where('correo', $usr);
			$this->db->where('password', $pass);
			$query_usuario_local = $this->db->get();
			$row_usuario_local = $query_usuario_local->row();
			
			$existe = $this->usuarioExiste($usr);
			
			if($existe) {
				$usuarioValido = $this->validaUsuario($usr, $pass);
				
				if($usuarioValido) {
					$inscrito = $this->revisaUsuarioEnCurso($row_usuario_local->id_usuario, $curso);
					
					if(!$inscrito) {
						$this->db->select('id_curso, nombre_corto');
						$this->db->from('curso');
						$this->db->where('nombre_corto', $curso);
						$this->db->where('estatus', 1);
						$query_curso_local = $this->db->get();
						$row_curso_local = $query_curso_local->row();
						
						$this->db = $this->load->database('moodle', TRUE);
						$this->db->select('id');
						$this->db->from('mdl_course');
						$this->db->where('shortname', $row_curso_local->nombre_corto);
						$query_curso_moodle = $this->db->get();
						$row_curso_moodle = $query_curso_moodle->row();
						
						// Se inserta en nuestra tabla				
						$query = "INSERT INTO usuario_curso VALUES(".$row_usuario_local->id_usuario.", ".$row_curso_local->id_curso.", NOW(), NULL, 1)";
						$this->db = $this->load->database('dev', TRUE);
						if($this->db->query($query)) {
							$estatus = 1;
						} else {
							$estatus = 3;
						}
						
						// Se inserta en la tabla de cursos de Moodle
						$this->db = $this->load->database('moodle', TRUE);
						$this->db->select('id');
						$this->db->from('mdl_context');
						$this->db->where('instanceid', $row_curso_moodle->id);
						$this->db->where('contextlevel', 50);
						$query_context = $this->db->get();
						$row_context = $query_context->row();
						
						//$this->db = $this->load->database('moodle', TRUE);
						$this->db->select('id');
						$this->db->from('mdl_enrol');
						$this->db->where('courseid', $row_curso_moodle->id);
						$this->db->where('enrol', 'manual');
						$query_enrol = $this->db->get();
						$row_enrol = $query_enrol->row();
						
						
						$status = 0;
						$enrolid = $row_enrol->id;
						$userid = $row_usuario_moodle->id;
						$timestart = time();
						$timeend = 0;
						$modifierid = 2;
						$timecreated = time();
						$timemodified = time();
						
						$query = "INSERT INTO mdl_user_enrolments VALUES(NULL, $status, $enrolid, $userid, $timestart, $timeend, $modifierid, $timecreated, $timemodified)";
						
						if($this->db->query($query)) {
							$estatus = 1;
						} else {
							$estatus = 3;
						}
						
						$roleid = 5;
						$contextid = $row_context->id;
						$userid = $row_usuario_moodle->id;
						$timemodified = time();
						$modifierid = 2;
						$component = '';
						$itemid = 0;
						$sortorder = 0;
						$query = "INSERT INTO mdl_role_assignments VALUES(NULL, $roleid, $contextid, $userid, $timemodified, $modifierid, '$component', $itemid, $sortorder)";
						
						if($this->db->query($query)) {
							$estatus = 1;
						} else {
							$estatus = 3;
						}
						$this->db->flush_cache();
					} else {
						$estatus = 4;
					}
				} else {
					$estatus = 10;
				}
			} else {
				$estatus = 2;
			}
			
			return $estatus;
		}
	}
?>