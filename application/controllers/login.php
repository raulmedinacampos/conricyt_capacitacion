<?php
class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('login_model', 'login', TRUE);
	}
	
	public function index() {
		$this->load->library('user_agent');
		
		$data['login'] = addslashes($this->input->post('usuario'));
		$data['password'] = addslashes($this->input->post('password'));
		
		$usuario = $this->login->verificarUsuario($data);
		
		if($usuario) {
			$this->session->set_userdata('usuario', $usuario->id_usuario);
			redirect(base_url('usuario'));
		} else {
			$pagina = $this->agent->referrer();
			
			$segmento = explode("/", $pagina);
			
			if ( $_SERVER['HTTP_REFERER'] == base_url() ) {
				$pagina = base_url('inicio');
			}
			
			if ( $segmento[sizeof($segmento)-1] != "error" ) {
				$pagina .= "/error";
			}
			
			redirect($pagina);
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
	
	public function recuperar_password() {
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
		$this->load->view('recuperar');
		$this->load->view('footer');
	}
}
?>