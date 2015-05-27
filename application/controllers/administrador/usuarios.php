<?php
	class Usuarios extends CI_Controller {
		function __construct() {
			parent::__construct();
			$this->load->model("administrador_model", "administrador", TRUE);
		}
		
		public function suspender() {
			$this->load->view('header');
			print_r($this->administrador->buscarUsuariosInactivos()->result());
			$this->load->view('footer');
		}
	}
?>