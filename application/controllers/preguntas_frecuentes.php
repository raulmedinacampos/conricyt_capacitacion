<?php
	class Preguntas_frecuentes extends CI_Controller {
		public function index() {
			//$this->output->cache(10);
			$this->load->view('header');
			$this->load->view('preguntas_frecuentes');
			$this->load->view('footer');
		}
	}
?>