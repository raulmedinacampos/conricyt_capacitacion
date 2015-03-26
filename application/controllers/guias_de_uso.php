<?php
	class Guias_de_uso extends CI_Controller {
		public function index() {
			//$this->output->cache(10);
			$this->load->view('header');
			$this->load->view('guias_de_uso');
			$this->load->view('footer');
		}
	}
?>