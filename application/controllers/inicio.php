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
			if ( $this->session->userdata('usuario') ) {
				$this->load->model('usuario_model', 'usuario', TRUE);
				$id_usuario = $this->session->userdata('usuario');
				$data['query'] = $this->usuario->consultarCursosInscritos($id_usuario);
			} else {
				$data['query'] = $this->curso->listado();
			}
			$this->load->view('inicio', $data);
			$this->load->view('footer');
		}
	}
?>