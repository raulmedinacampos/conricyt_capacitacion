<?php
class Administrador extends CI_Controller {
	public function index() {
		if ( $this->session->userdata('admin') ) {
			redirect(base_url('administrador/usuarios'));
		}
		
		$this->load->helper('form');
		$this->load->view('administrador/header_admin');
		$this->load->view('administrador/inicio');
		$this->load->view('footer');
		
	}
}
?>