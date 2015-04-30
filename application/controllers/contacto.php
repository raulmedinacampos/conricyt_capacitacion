<?php
	class Contacto extends CI_Controller {
		function __construct() {
			parent::__construct();
			
			if ( $this->session->userdata('usuario') ) {
				$this->load->model('usuario_model', 'usuario', TRUE);
				$id_usuario = $this->session->userdata('usuario');
				$header['menu'] = $this->usuario->consultarCursosInscritos($id_usuario);
				$header['usuario'] = $this->usuario->consultarUsuarioPorID($id_usuario);
			} else {
				$this->load->model('curso', '', TRUE);
				$header['menu'] = $this->curso->listado();
			}
			
			$this->load->view('header', $header);
		}
		
		public function index() {
			//$this->output->cache(10);
			$this->load->view('contacto');
			$this->load->view('footer');
		}
	}
?>