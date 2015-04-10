<?php
	class Cursos extends CI_Controller {
		private $seed = 'vl{qoV#~EPL5Zs>EW#VW5nUCO';
		
		public function __construct() {
			parent::__construct();
			$this->load->model('curso', '', TRUE);
		}
		
		public function index() {
			//$this->output->cache(10);
			$data['query'] = $this->curso->listado();
			$this->load->view('header');
			$this->load->view('cursos', $data);
			$this->load->view('footer');
		}
		
		public function informacion() {
			$this->load->helper('form');
			$data['curso'] = $this->curso->detalle($this->uri->segment(2));
			$this->load->view('info_cursos', $data);
		}
		
		public function agregarCurso() {
			$this->load->helper('form');
			$html = '';
			$seleccion = $this->input->post('seleccion');
			$curso = $this->input->post('curso');
			$attr = array(
						  'id'		=>	"chk_".$seleccion,
						  'name'	=>	'cursos[]',
						  'checked'	=>	TRUE,
						  'value'	=>	$seleccion
						  );
			if($this->session->userdata('cursos_seleccionados')) {
				$html .= $this->session->userdata('cursos_seleccionados');
			}
			
			if(!strpos($html, $seleccion)) {
				if($this->session->userdata('cursos_seleccionados')) {
					$html .= "<br/>";
				}
				$html .= form_checkbox($attr);
				$html .= '<span>';
				$html .= $curso;
				$html .= '</span>';
			}
			
			$this->session->set_userdata('cursos_seleccionados', $html);
			echo $this->session->userdata('cursos_seleccionados');
		}
		
		public function limpiarLista() {
			$this->session->unset_userdata('cursos_seleccionados');
			redirect(base_url().'cursos/');
		}
		
		public function inscribirse() {
			$usuario = $this->input->post('usr_curso');
			$input_pass = $this->input->post('pass_curso');
			$pass = md5($this->input->post('pass_curso').$this->seed);
			$curso = $this->uri->segment(3);
			
			$estatus = 0;
			
			if($usuario && $input_pass && $curso) {
				$estatus = $this->curso->matricular($usuario, $input_pass, $curso);
			}
			
			switch($estatus) {
				case 1:	$mensaje = "Tu registro al curso se ha realizado";
					break;
				case 2: $mensaje = 'Antes de solicitar acceso al curso debes registrarte<p><a href="'.base_url().'registro">Regístrate aquí</a></p>';
					break;
				case 3: $mensaje = "Ha ocurrido un error al matricularte";
					break;
				case 4: $mensaje = "Ya estás registrado en este curso";
					break;
				case 10: $mensaje = "Favor de llenar los datos correctamente";
					break;
				default: $mensaje = "Ha ocurrido un error, por favor, ponte en contacto con el administrador";
			}
			
			echo utf8_encode($mensaje);
		}
	}
?>