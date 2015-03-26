<?php
	class Cursos extends CI_Controller {
		public function index() {
			$this->load->model('curso', '', TRUE);
			$data['query'] = $this->curso->listado();
			$this->load->view('header');
			$this->load->view('administrador/cursos/listado', $data);
			$this->load->view('footer');
		}
		
		public function nuevo() {
			$this->load->helper('form');
			$this->load->view('header');
			$this->load->view('administrador/cursos/nuevo');
			$this->load->view('footer');
		}
		
		public function editar() {
			$this->load->model('curso', '', TRUE);
			$this->load->helper('form');
			$data['curso'] = $this->curso->detalle($this->uri->segment(4));
			$this->load->view('header');
			$this->load->view('administrador/cursos/editar', $data);
			$this->load->view('footer');
		}
	}
?>