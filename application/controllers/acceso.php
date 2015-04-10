<?php
class Acceso extends CI_Controller {
	public function __construct() {
		parent::__construct();
		$this->load->model('acceso_model', 'acceso', TRUE);
	}
	
	public function verificarAcceso() {
		$this->load->helper('form');
		$this->load->model('registro_model', 'registro', TRUE);
		
		$id = $this->uri->segment(3);
		$shortname = $this->registro->getShortnameByID($id);
		$curso = $this->registro->getMoodleCourse($shortname->nombre_corto);
		$usuario = $this->acceso->getDataByID($this->session->userdata('usuario'));
		
		//$ruta_login = base_url('evaluacion/login/index.php');
		$ruta_login = 'http://moodle.dev/login/index.php?id='.$curso->id;
		
		echo '<script type="text/javascript" src="'.base_url('scripts/jquery-1.11.0.min.js').'"></script>';
		
		$attr = array(
			'id'	=>	'form1',
			'name'	=>	'form1'
		);
		echo form_open($ruta_login, $attr);
		
		$attr = array(
				'id'	=>	'username',
				'name'	=>	'username',
				'type'	=>	'hidden',
				'value'	=>	(isset($usuario->login)) ? $usuario->login : ""
		);
		echo form_input($attr);
		
		$attr = array(
				'id'	=>	'password',
				'name'	=>	'password',
				'type'	=>	'hidden',
				'value'	=>	(isset($usuario->password)) ? $usuario->password : ""
		);
		echo form_input($attr);
		echo form_close();
		
		echo '<script type="text/javascript">';
		echo '$(function() {$("#form1").submit();});';
		echo '</script>';
		
		echo '<h4>Estas accediendo a los cursos de capacitación ...</h4>';
	}
}
?>