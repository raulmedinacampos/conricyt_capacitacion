<?php
	class Contacto extends CI_Controller {
		public function index() {
			//$this->output->cache(10);
			$this->load->view('header');
			$this->load->view('contacto');
			$this->load->view('footer');
		}
	}
?>