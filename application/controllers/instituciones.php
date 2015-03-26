<?php
	class Instituciones extends CI_Controller {
		public function index() {
			//$this->output->cache(10);
			$this->load->view('header');
			$this->load->view('instituciones');
			$this->load->view('footer');
		}
	}
?>