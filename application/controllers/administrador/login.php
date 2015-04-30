<?php
class Login extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('usuario', '', TRUE);
	}
	
	public function index() {
		/*$this->load->library('form_validation');
		$usuario = $this->input->post('usuario');
		$pass = $this->input->post('contrasenia');
		$pass = md5($pass);
		$result = $this->usuario->login($usuario, $pass);
		
		if($result) {
			$session = array();
			foreach($result as $row) {
				$session = array(
					'id'		=>	$row->id_usuario,
					'usuario'	=>	$row->login
								);
			}
			redirect('administrador/cursos/nuevo');
		} else {
			redirect('administrador', 'refresh');
		}*/
	}
	
	public function logout() {
		$this->session->unset_userdata();
		session_destroy();
		redirect('administrador');
	}
}
?>