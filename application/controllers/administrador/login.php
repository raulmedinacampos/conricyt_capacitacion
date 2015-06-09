<?php
class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('login_model', 'login', TRUE);
	}
	
	public function index() {
		$login = addslashes($this->input->post('admin_usuario'));
		$password = addslashes($this->input->post('admin_password'));
		
		if ( $login === 'admin' && $password === 'C0nr1cyT##' ) {
			$this->session->set_userdata('admin', '1');
			redirect(base_url('administrador/usuarios'));
		} else {
			redirect(base_url('administrador'));
		}
	}
	
	public function salir() {
		$this->session->sess_destroy();
		
		redirect('administrador');
	}
}
?>