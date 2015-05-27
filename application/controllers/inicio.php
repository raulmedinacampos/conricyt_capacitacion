<?php
	class Inicio extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model('curso', '', TRUE);
			
			if ( $this->session->userdata('usuario') ) {
				$this->load->model('usuario_model', 'usuario', TRUE);
				$id_usuario = $this->session->userdata('usuario');
				$header['menu'] = $this->usuario->consultarCursosInscritos($id_usuario);
				$header['usuario'] = $this->usuario->consultarUsuarioPorID($id_usuario);
			} else {
				$header['menu'] = $this->curso->listado();
			}
			
			$this->load->view('header', $header);
		}
		
		public function index() {
			//$this->output->cache(10);
			$cap_arr = array();
			
			if ( $this->session->userdata('usuario') ) {
				$this->load->model('usuario_model', 'usuario', TRUE);
				$id_usuario = $this->session->userdata('usuario');
				$capacitaciones = $this->usuario->consultarCursosInscritos($id_usuario);
			} else {
				$capacitaciones = $this->curso->listado();
			}
			
			foreach ( $capacitaciones->result() as $capacitacion ) {
				$calificacion = $this->curso->obtenerCalificacionCurso($capacitacion->id_curso);
				$datos = new stdClass();
				$datos->id_curso = $capacitacion->id_curso;
				$datos->curso = $capacitacion->curso;
				$datos->nombre_corto = $capacitacion->nombre_corto;
				$datos->ruta_imagen = $capacitacion->ruta_imagen;
				$datos->descripcion = $capacitacion->descripcion;
				$datos->calif = ($calificacion) ? $calificacion : 0;
				$cap_arr[] = $datos;
			}
			
			$data['query'] = $cap_arr;
			$this->load->view('inicio', $data);
			$this->load->view('footer');
		}
	}
?>