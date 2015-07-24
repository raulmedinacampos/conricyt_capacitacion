<?php
class Reporte extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('reporte_model', 'reporte', TRUE);
		$this->load->model('administrador_model', 'adreporte', TRUE);
		
		
		if ( !$this->session->userdata('admin') ) {
			redirect(base_url());
		}
	}
	
	public function por_genero() {
		$genero = $this->reporte->consultarPorGenero();
		$genero_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $genero->result() as $val ) {
			$dato = new stdClass();
			
			$dato->num = $num++;
			
			if ( $val->sexo == 'f' ) {
				$dato->genero = "Femenino";
			}
			
			if ( $val->sexo == 'm' ) {
				$dato->genero = "Masculino";
			}
			
			$dato->total = $val->total;
			
			$t += $val->total;
			
			$genero_arr[] = $dato;
		}
		
		$data['genero'] = $genero_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/genero', $data);
		$this->load->view('footer');
	}
	
	public function por_entidad() {
		$entidades = $this->reporte->leerEntidades();
		$entidades_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $entidades->result() as $entidad ) {
			$dato = new stdClass();
				
			$dato->num = $num++;
				
			$dato->entidad = $entidad->entidad;
			$dato->total = (int)$this->reporte->consultarPorEntidad($entidad->id_entidad);
				
			$t += $dato->total;
				
			$entidades_arr[] = $dato;
		}
		
		$data['entidades'] = $entidades_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/entidades', $data);
		$this->load->view('footer');
	}
	
	public function por_region() {
		$regiones = $this->reporte->leerRegiones();
		$regiones_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $regiones->result() as $region ) {
			$dato = new stdClass();
		
			$dato->num = $num++;
		
			$dato->region = $region->region;
			$dato->total = (int)$this->reporte->consultarPorRegion($region->id_region);
		
			$t += $dato->total;
		
			$regiones_arr[] = $dato;
		}
		
		$data['regiones'] = $regiones_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/regiones', $data);
		$this->load->view('footer');
	}
	
	public function por_institucion() {
		$instituciones = $this->reporte->consultarPorInstitucion();
		$instituciones_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $instituciones->result() as $institucion ) {
			$dato = new stdClass();
				
			$dato->num = $num++;
			$dato->institucion = $institucion->institucion;
			$dato->total = $institucion->total;
				
			$t += $institucion->total;
				
			$instituciones_arr[] = $dato;
		}
		
		$data['instituciones'] = $instituciones_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/instituciones', $data);
		$this->load->view('footer');
	}
	
	public function por_otra_institucion() {
		$instituciones = $this->reporte->consultarPorOtraInstitucion();
		$instituciones_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $instituciones->result() as $institucion ) {
			$dato = new stdClass();
				
			$dato->num = $num++;
			$dato->institucion = $institucion->institucion;
			$dato->total = $institucion->total;
				
			$t += $institucion->total;
				
			$instituciones_arr[] = $dato;
		}
		
		$data['instituciones'] = $instituciones_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/otras_instituciones', $data);
		$this->load->view('footer');
	}
	
	public function por_curso() {
		$cursos = $this->reporte->leerCursos();
		$cursos_arr = array();
		$num = 1;
		$t = 0;
		
		foreach ( $cursos->result() as $curso ) {
			$dato = new stdClass();
		
			$dato->num = $num++;
			$dato->curso = $curso->curso;
			$dato->total = $this->reporte->consultarPorCurso($curso->id_curso);
		
			$t += $dato->total;
		
			$cursos_arr[] = $dato;
		}
		
		$data['cursos'] = $cursos_arr;
		$data['total'] = $t;
		
		$this->load->view('administrador/header_admin');
		$this->load->view('reportes/cursos', $data);
		$this->load->view('footer');
	}
	
	public function reporteExcel()
	{
		$this->load->library('excel');
		$registros = $this->adreporte->listarUsuarios2();
		$registros_aux = $registros->result();
		$fila = 1;
		$xls = new PHPExcel();
		 
		$fields = $registros->list_fields();
		$col = 0;
		
		// Encabezados
		$xls->getActiveSheet()->getStyle('A1'.':G1')->getFont()->setBold(true);
		$xls->getActiveSheet()->getStyle('A1'.':G1')->getFont()->getColor()->setRGB('FFFFFF');
		$xls->getActiveSheet()->getStyle('A1'.':G1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('484F56');
		$xls->getActiveSheet()->getStyle('A1'.':G1')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		
		// Ancho de las columnas
		$xls->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
		$xls->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
		
		/*foreach ($fields as $field)
		{
			$xls->getActiveSheet()->setCellValueByColumnAndRow($col, 1, $field);
			$col++;
		}*/
		
		$xls->getActiveSheet()->setCellValueByColumnAndRow(0, 1, "ID");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(1, 1, "Nombre");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(2, 1, "Apellido Paterno");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(3, 1, "Apellido Materno");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(4, 1, "Correo");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(5, 1, "Institucion");
		$xls->getActiveSheet()->setCellValueByColumnAndRow(6, 1, "Password");
		
	
		$row = 2;
		foreach($registros_aux as $data)
		{
			$col = 0;
			foreach ($fields as $field)
			{
				$xls->getActiveSheet()->setCellValueByColumnAndRow($col, $row, $data->$field);
				$col++;
			}
	
			$row++;
		}
		
		
	
			
		// Renombramos la hoja de trabajo
		$xls->getActiveSheet()->setTitle('Registrados');
			
		// redireccionamos la salida al navegador del cliente (Excel2007)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="Registro Administrador.xlsx"');
		header('Cache-Control: max-age=0');
	
	
		$objWriter = PHPExcel_IOFactory::createWriter($xls, 'Excel2007');
		$objWriter->save('php://output');
			
			
		// end: setExcel
	}
	
}
?>