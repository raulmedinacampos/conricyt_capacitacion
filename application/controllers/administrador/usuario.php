<?php
	class Usuario extends CI_Controller {
		public function index() {
			$this->load->helper('form');
			$this->load->view('header');
			$this->load->view('administrador/inicio');
			$this->load->view('footer');
		}
		
		public function login() {
			$usuario = $this->input->post('usuario');
			$pass = $this->input->post('contrasenia');
			//echo $usuario."<br />".$pass;
			$this->load->model('usuario');
		}
	}
?>