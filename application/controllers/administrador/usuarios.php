<?php
class Usuarios extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model("administrador_model", "administrador", TRUE);
		
		if ( !$this->session->userdata('admin') ) {
			redirect(base_url());
		}
	}
	
	public function index() {
		$filtro = array();
		$filtro['nombre'] = addslashes($this->input->post('nombre'));
		$filtro['ap_paterno'] = addslashes($this->input->post('ap_paterno'));
		$filtro['ap_materno'] = addslashes($this->input->post('ap_materno'));
		$filtro['correo'] = addslashes($this->input->post('correo'));
		$filtro['i.institucion'] = addslashes($this->input->post('institucion'));
		
		$usuarios = $this->administrador->listarUsuarios($filtro);
		$usuarios_arr = array();
		
		if ( $usuarios ) {
			foreach ( $usuarios->result() as $usuario ) {
				$dato = new stdClass();
				$dato->id_usuario = $usuario->id_usuario;
				$dato->nombre = $usuario->nombre;
				$dato->ap_paterno = $usuario->ap_paterno;
				$dato->ap_materno = $usuario->ap_materno;
				$dato->login = $usuario->login;
				$dato->password = $usuario->password;
			
				if ( $usuario->inst ) {
					$dato->institucion = $usuario->inst;
				} else {
					$dato->institucion = $usuario->institucion;
				}
			
				$usuarios_arr[] = $dato;
			}
		}
		
		$data['usuarios'] = $usuarios_arr;
		$data['filtro'] = $filtro;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('administrador/listado', $data);
		$this->load->view('footer');
	}
	
	public function listarRegistrados() {		
		$array = array();
		$array['nombre'] = addslashes($this->input->post('nombre'));
		$array['ap_paterno'] = addslashes($this->input->post('ap_paterno'));
		$array['ap_materno'] = addslashes($this->input->post('ap_materno'));
		$array['correo'] = addslashes($this->input->post('correo'));
		$array['i.institucion'] = addslashes($this->input->post('institucion'));
		$usuarios = $this->administrador->listarUsuarios($array);
		$usuarios_arr = array();
		
		foreach ( $usuarios->result() as $usuario ) {
			$dato = new stdClass();
			$dato->id_usuario = $usuario->id_usuario;
			$dato->nombre = $usuario->nombre;
			$dato->ap_paterno = $usuario->ap_paterno;
			$dato->ap_materno = $usuario->ap_materno;
			$dato->login = $usuario->login;
			$dato->password = $usuario->password;
				
			if ( $usuario->inst ) {
				$dato->institucion = $usuario->inst;
			} else {
				$dato->institucion = $usuario->institucion;
			}
				
			$usuarios_arr[] = $dato;
		}
		
		echo json_encode($usuarios_arr);
	}
	
	public function suspender() {
		$this->load->view('header');
		print_r($this->administrador->buscarUsuariosInactivos()->result());
		$this->load->view('footer');
	}
}
?>