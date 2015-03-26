<?php
	class Administrador extends CI_Controller {
		public function index() {
			$this->load->helper('form');
			$this->load->view('header');
			$this->load->view('administrador/inicio');
			$this->load->view('footer');
		}
	}
?>