<?php
class Usuario extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('usuario_model', 'usuario', TRUE);
		
		if ( $this->session->userdata('usuario') ) {
			$this->load->model('usuario_model', 'usuario', TRUE);
		
			$id_usuario = $this->session->userdata('usuario');
			
			$header['menu'] = $this->usuario->consultarCursosInscritos($id_usuario);
			$header['usuario'] = $this->usuario->consultarUsuarioPorID($id_usuario);
			$this->load->view('header', $header);
		} else {
			redirect(base_url());
		}
	}
	
	public function index() {
		$this->load->helper('form');
		
		$id_usuario = $this->session->userdata('usuario');
		
		$data['cursos'] = $this->usuario->consultarCursosInscritos($id_usuario);
		$data['cursos_disponibles'] = $this->usuario->consultarCursosDisponibles($id_usuario);
		
		$this->load->view('usuario/principal', $data);
		$this->load->view('footer');
	}
}
?>