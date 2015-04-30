<?php
class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('login_model', 'login', TRUE);
	}
	
	public function index() {
		$data['login'] = addslashes($this->input->post('usuario'));
		$data['password'] = addslashes($this->input->post('password'));
		
		$usuario = $this->login->verificarUsuario($data);
		
		if($usuario) {
			$this->session->set_userdata('usuario', $usuario->id_usuario);
			redirect(base_url('usuario'));
		} else {
			redirect($_SERVER['HTTP_REFERER']);
		}
	}
	
	public function entrar() {
		$this->load->model("registro_model", "registro", TRUE);
		
		$data['login'] = addslashes($this->input->post('usr_modal'));
		$data['password'] = addslashes($this->input->post('pass_modal'));
		$curso = addslashes($this->input->post('curso_modal'));
		
		$usuario = $this->login->verificarUsuario($data);
		
		if ( $usuario ) {
			$this->session->set_userdata('usuario', $usuario->id_usuario);
			
			if ( $this->registro->verificarUsuarioEnCurso($usuario->id_usuario, $curso) ) {
				echo "ok";
			} else {
				echo "reg";
			}
		} else {
			echo "error";
		}
	}
	
	public function salir() {
		$this->session->sess_destroy();
		
		redirect(base_url());
	}
}
?>