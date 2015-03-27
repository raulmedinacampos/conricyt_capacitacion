<?php
class Registro extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model("registro_model", "registro", TRUE);
	}
	
	public function index() {
		$this->load->helper('form');
		
		$paises = $this->registro->leerPaises();
		$paises = $paises->result();
		$paises_arr = array();

		foreach ( $paises as $pais ) {
			if($pais->id_pais == 156) {  // ID de México
				$dato = new stdClass();
				$dato->id_pais = $pais->id_pais;
				$dato->pais = $pais->pais;
		
				$paises_arr[] = $dato;
			}
		}
		
		foreach ( $paises as $pais ) {
			if($pais->id_pais != 156) {  // ID de México 
				$dato = new stdClass();
				$dato->id_pais = $pais->id_pais;
				$dato->pais = $pais->pais;
				
				$paises_arr[] = $dato;
			}
		}
		
		$perfiles = $this->registro->leerPerfiles();
		$perfiles = $perfiles->result();
		$perfiles_arr = array();
		
		foreach ( $perfiles as $perfil ) {
			if ( $perfil->id_perfil != 10) {  // ID de Otro
				$dato = new stdClass();
				$dato->id_perfil = $perfil->id_perfil;
				$dato->perfil = $perfil->perfil;
				
				$perfiles_arr[] = $dato;
			}
		}
		
		foreach ( $perfiles as $perfil ) {
			if ( $perfil->id_perfil == 10) {  // ID de Otro
				$dato = new stdClass();
				$dato->id_perfil = $perfil->id_perfil;
				$dato->perfil = $perfil->perfil;
		
				$perfiles_arr[] = $dato;
			}
		}
		
		$instituciones = $this->registro->leerInstituciones();
		$instituciones = $instituciones->result();
		$instituciones_arr = array();
		
		foreach ( $instituciones as $institucion ) {
			if ( $institucion->id_institucion != 484 ) {  // ID de Otra
				$dato = new stdClass();
				$dato->id_institucion = $institucion->id_institucion;
				$dato->institucion = $institucion->institucion;
				
				$instituciones_arr[] = $dato;
			}
		}
		
		foreach ( $instituciones as $institucion ) {
			if ( $institucion->id_institucion == 484 ) {  // ID de Otra
				$dato = new stdClass();
				$dato->id_institucion = $institucion->id_institucion;
				$dato->institucion = $institucion->institucion;
		
				$instituciones_arr[] = $dato;
			}
		}
		
		$data['paises'] = $paises_arr;
		$data['perfiles'] = $perfiles_arr;
		$data['instituciones'] = $instituciones_arr;
		
		$this->load->view('header');
		$this->load->view('formulario', $data);
		$this->load->view('footer');
	}
}
?>