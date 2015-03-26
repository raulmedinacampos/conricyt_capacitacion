<?php
	class Inicio extends CI_Controller {
		public function index() {
			//$this->output->cache(10);
			$this->load->view('header');
			$this->load->view('inicio');
			$this->load->view('footer');
		}
		
		public function metodo_de_prueba() {
			$this->load->view('header');
			$this->load->view('inicio');
			$this->load->view('footer');
		}
	}
?>