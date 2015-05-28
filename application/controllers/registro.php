<?php
class Registro extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model("registro_model", "registro", TRUE);
	}
	
	public function index() {
		$this->load->model("curso", "", TRUE);
		
		if ( $this->session->userdata('usuario') ) {
			$this->load->model('usuario_model', 'usuario', TRUE);
			$id_usuario = $this->session->userdata('usuario');
			$header['menu'] = $this->usuario->consultarCursosInscritos($id_usuario);
			$header['usuario'] = $this->usuario->consultarUsuarioPorID($id_usuario);
		} else {
			$this->load->model('curso', '', TRUE);
			$header['menu'] = $this->curso->listado();
		}
		
		$cseleccionados = $this->input->post('cursos');
		$cursos = "";
		
		$paises = $this->registro->leerPaises();
		$paises = $paises->result();
		$paises_arr = array();

		foreach ( $paises as $pais ) {
			if($pais->id_pais == 154) {  // ID de México
				$dato = new stdClass();
				$dato->id_pais = $pais->id_pais;
				$dato->pais = $pais->pais;
		
				$paises_arr[] = $dato;
			}
		}
		
		foreach ( $paises as $pais ) {
			if($pais->id_pais != 154) {  // ID de México 
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
			if ( $institucion->id_institucion != 483 ) {  // ID de Otra
				$dato = new stdClass();
				$dato->id_institucion = $institucion->id_institucion;
				$dato->institucion = $institucion->institucion;
				
				$instituciones_arr[] = $dato;
			}
		}
		
		foreach ( $instituciones as $institucion ) {
			if ( $institucion->id_institucion == 483 ) {  // ID de Otra
				$dato = new stdClass();
				$dato->id_institucion = $institucion->id_institucion;
				$dato->institucion = $institucion->institucion;
		
				$instituciones_arr[] = $dato;
			}
		}
		
		$entidades = $this->registro->leerEntidades();
		$entidades = $entidades->result();
		
		if( $this->session->userdata('cursos_seleccionados') ) {
			$cseleccionados = array();
			$dom = new DOMDocument();
			$dom->loadHTML($this->session->userdata('cursos_seleccionados'));
			
			$xpath = new DOMXPath($dom);
			$chks = $xpath->query('//input');
			
			foreach ( $chks as $chk ) {
				$cseleccionados[] = $chk->getAttribute("value");
			}
		}
		
		if ( $cseleccionados ) {
			$cursos = $this->registro->consultarCursos($cseleccionados);
			
			if ( $cursos ) {
				$cursos = $cursos->result();
			}
		}
		
		/*
		 * Quitar esta parte
		 */
		$cursos = $this->curso->listado();
		$cursos = $cursos->result();
		
		$data['paises'] = $paises_arr;
		$data['entidades'] = $entidades;
		$data['perfiles'] = $perfiles_arr;
		$data['instituciones'] = $instituciones_arr;
		$data['cursos'] = $cursos;
		
		$this->load->view('header', $header);
		$this->load->view('formulario', $data);
		$this->load->view('footer');
	}
	
	private function generarPassword($longitud = 8) {
		$caracteres = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$password = substr(str_shuffle($caracteres), 0, $longitud); 
		return $password;
	}
	
	public function revisarCorreoRepetido() {
		$correo = $this->input->post('correo');
	
		if($this->registro->verificarUsuarioExistente($correo)) {
			// Ya existe el usuario
			echo "false";
		} else {
			echo "true";
		}
	}
	
	private function crearComprobante($id_usuario, $consulta = "") {
		error_reporting(0);
		$this->load->library("Fecha");
		
		$data = $this->registro->consultarUsuarioPorID($id_usuario);
		$credenciales = $this->registro->consultarCredencialesPorID($id_usuario);
			
		list($fecha, $hora) = explode(" ", $data['fecha_alta']);
		$fecha = Fecha::MostrarFormatoLargo($fecha);
		$nombre = utf8_decode($data['nombre']);
		$ap_paterno = utf8_decode($data['ap_paterno']);
		$ap_materno = utf8_decode($data['ap_materno']);
		$nombre_completo = trim($nombre." ".$ap_paterno." ".$ap_materno);
		$institucion = utf8_decode(trim($data['institucion']));
			
		$header = '<p class="header"><img src="http://127.0.0.1/images/header_pdf.jpg" /></p>';
		$footer = '<p class="footer"><strong>Oficina del Consorcio Nacional de Recursos de Información Científica y Tecnológica</strong><br />Av. Insurgentes Sur 1582, Col. Crédito Constructor, Del. Benito Juárez, C.P. 03940 México D.F. – Tel: 5322 7700 ext. 4020 a la 4026</p>';
		$html = '<div class="contenido">';
		$html .= '<p class="titulo2">Consorcio Nacional de Recursos de Información Científica y Tecnológica</p>';
		$html .= '<p class="fecha">'.$fecha.'</p>';
		$html .= '<p class="remitente">Estimado(a) '.$nombre_completo.'<br />'.$institucion.'<br />P r e s e n t e:</p>';
		$html .= '<p>Bienvenido al Centro de Capacitación Virtual, una estrategia de aprendizaje en línea que ofrece el Consorcio Nacional de Recursos de Información Científica y Tecnológica, en colaboración con las editoriales e integradoras que forman parte del CONRICYT.</p>';
		$html .= '<p>Acabas de unirte a la comunidad de estudiantes, académicos, investigadores, bibliotecarios y referencistas interesados en desarrollar habilidades informativas sobre la búsqueda y recuperación de información científica y tecnológica.</p>';
		$html .= '<p>A continuación te enlistamos los principales pasos para el buen uso de tu cuenta en el Centro de Capacitación Virtual:</p>';
		$html .= '<ol>';
		$html .= '<li>Conserva en un lugar seguro tu clave de usuario y contraseña que a continuación se te proporciona.<br />';
		$html .= '<strong>Usuario: '.$credenciales['login'].'</strong><br />';
		$html .= '<strong>Contraseña: '.$credenciales['password'].'</strong>';
		$html .= '</li>';
		$html .= '<li>Revisa la lista de cursos que tenemos disponibles para ti en http://capacitacion.conricyt.mx/cursos/ y matricúlate en el curso de tu interés. (Puedes acceder a los contenidos del curso elegido las veces que así lo desees, siempre y cuando seas un usuario activo. Lee la información correspondiente a los contenidos de cada Curso.)</li>';
		$html .= '<li>Al acceder por primera vez a un curso, te sugerimos que modifiques tu perfil para que otros usuarios puedan conocer sobre de ti.</li>';
		$html .= '</ol>';
		$html .= '<p>Estamos complacidos de que hayas decidido unirte a este proyecto. Adjunto a este correo encontrarás un archivo PDF con los Términos y Condiciones sobre el uso de la plataforma del Centro de Capacitación Virtual.</p>';
		$html .= '<p>Para cualquier duda, comentario o sugerencia, escribe a centro.capacitacion@conricyt.mx o llámanos al (55) 5322 7700 ext. 4020 a la 4026.</p>';
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'http://localhost/css/pdf.css');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$stylesheet = curl_exec($ch);
		curl_close($ch);
	
		$this->load->library('pdf');
		$pdf = $this->pdf->load("c", "Letter", "", "", 20, 20, 45, 30, 10, 10);
		$pdf->SetHTMLHeader(utf8_encode($header));
		$pdf->SetHTMLFooter(utf8_encode($footer));
		$pdf->WriteHTML($stylesheet, 1);
		$pdf->WriteHTML(utf8_encode($html));
			
		if($consulta) {
			$pdf->Output('comprobante.pdf', 'D');
		} else {
			$contenido_pdf = $pdf->Output(utf8_encode('Comprobante de registro.pdf'), 'S');
			return $contenido_pdf;
		}
	}
	
	public function comprobante() {
		$id_usuario = $this->uri->segment(3);
		settype($id_usuario, "int");
			
		$this->crearComprobante($id_usuario, "consulta");
	}
	
	private function enviarCorreo($id_usuario, $correo, $remitente, $asunto, $body) {
		$this->load->library('phpmailer');
	
		$this->phpmailer->IsSMTP();
		$this->phpmailer->SMTPDebug  = 0;
		$this->phpmailer->SMTPAuth   = true;					// activa autenticación
		$this->phpmailer->Host       = "smtp.gmail.com";		// servidor de correo
		$this->phpmailer->Port       = 465;                     // puerto de salida que usa Gmail
		$this->phpmailer->SMTPSecure = 'ssl';					// protocolo de autenticación
		$this->phpmailer->Username   = "conricyt@gmail.com";
		$this->phpmailer->Password   = 'C0nR1c17p1x3l8lu3';
	
		$this->phpmailer->SetFrom('conricyt@gmail.com', 'CONRICyT');
		$this->phpmailer->AddReplyTo('no-replay@conacyt.mx', 'CONRICyT');
		$this->phpmailer->Subject    = utf8_encode($asunto);
		$this->phpmailer->AltBody    = utf8_encode($asunto);
	
		$this->phpmailer->MsgHTML($body);
	
		$this->phpmailer->AddAddress($correo, $remitente);
			
		$data = "";
		
		// Se adjuntan los archivos PDF
		$this->phpmailer->AddStringAttachment($this->crearComprobante($id_usuario),'comprobante.pdf');
		$this->phpmailer->AddAttachment('pdf/terminos_y_condiciones.pdf');
	
		$this->phpmailer->CharSet = 'UTF-8';
	
		if(!$this->phpmailer->Send()) {
			//echo "Error al enviar correo: " . $this->phpmailer->ErrorInfo;
		} else {
			//echo "Correo enviado";
		}
	}
	
	public function guardarRegistro() {
		$this->load->library("Cadena");
		$time = time();
		$respuesta = 0;
		
		$registro['fecha_alta'] = date('Y-m-d H:i:s');
		$registro['nombre'] = Cadena::formatearNombre($this->input->post('nombre'));
		$registro['ap_paterno'] = Cadena::formatearNombre($this->input->post('ap_paterno'));
		$registro['ap_materno'] = Cadena::formatearNombre($this->input->post('ap_materno'));
		$registro['correo'] = strtolower($this->input->post('correo'));
		$registro['login'] = $this->input->post('correo');
		$registro['password'] = $this->generarPassword();
		$registro['sexo'] = $this->input->post('sexo');
		$registro['pais'] = $this->input->post('pais');
		$registro['entidad'] = $this->input->post('entidad');
		$registro['id_perfil'] = $this->input->post('perfil');
		$registro['perfil'] = $this->input->post('otro_perfil');
		$registro['id_institucion'] = $this->input->post('institucion');
		$registro['institucion'] = $this->input->post('otra_institucion');
		
		// Array con los ID de los cursos seleccionados
		$cursos = $this->input->post('cursos');
		
		$id_usuario = $this->registro->consultarUsuarioExistente($registro['correo']);
		
		if ( !$id_usuario ) {
			$id_usuario = $this->registro->insertarUsuario($registro);
		} else {
			$id_usuario = $id_usuario->id_usuario;
		}
		
		// Bucle para insertar cada uno de los cursos seleccionados
		if ( $cursos ) {
			foreach ( $cursos as $val ) {
				$datos['usuario'] = $id_usuario;
				$datos['curso'] = $val;
				$datos['fecha_inscripcion'] = date('Y-m-d H:i:s');
				
				
				// Revisa que el usuario no esté ya registrado
				if ( !$this->registro->verificarUsuarioEnCurso($id_usuario, $val) ) {
					$this->registro->insertarUsuarioCurso($datos);
				}
			}
		}
		
		$id_usr_moodle = $this->registro->checkMoodleUserExists($registro['correo']);

		if($id_usr_moodle) {
			$id_usr_moodle = $id_usr_moodle->id;
		} else {
			// Inserta usuario en Moodle
			$usrData['auth'] = 'manual';
			$usrData['confirmed'] = '1';
			$usrData['mnethostid'] = '1';
			$usrData['username'] = $registro['correo'];
			$usrData['password'] = md5($registro['password']);
			$usrData['firstname'] = $registro['nombre'];
			$usrData['lastname'] = trim($registro['ap_paterno'].' '.$registro['ap_materno']);
			$usrData['email'] = $registro['correo'];
			$usrData['emailstop'] = '0';
			$usrData['city'] = utf8_encode('México');
			$usrData['country'] = 'MX';
			$usrData['lang'] = 'es_mx';
			$usrData['timezone'] = '99';
			$usrData['firstaccess'] = '0';
			$usrData['lastaccess'] = '0';
			$usrData['lastlogin'] = '0';
			$usrData['currentlogin'] = '0';
			$usrData['descriptionformat'] = '1';
			$usrData['mailformat'] = '1';
			$usrData['maildigest'] = '0';
			$usrData['maildisplay'] = '2';
			$usrData['autosubscribe'] = '1';
			$usrData['trackforums'] = '0';
			$usrData['timecreated'] = $time;
			$usrData['timemodified'] = $time;
			$usrData['trustbitmask'] = '0';
			$id_usr_moodle = $this->registro->insertMoodleUser($usrData);
		}
		
		if( $cursos ) {
			foreach ( $cursos as $val ) {
				// Obtenemos datos de la sede seleccionada
				$datos_curso = $this->registro->getShortnameByID($val);
					
				// Con los datos anteriores consultamos el curso en Moodle
				$curso_moodle = $this->registro->getMoodleCourse($datos_curso->nombre_corto);
					
				// Obtenemos el contexto de Moodle
				$contexto = $this->registro->getMoodleContext($curso_moodle->id);
					
				// Consultamos la matricula registrada
				$enrol = $this->registro->getMoodleEnrol($curso_moodle->id);
					
				// Matricula al usuario
				$userEnrollment['status'] = 0;
				$userEnrollment['enrolid'] = $enrol->id;
				$userEnrollment['userid'] = $id_usr_moodle;
				$userEnrollment['timestart'] = $time;
				$userEnrollment['timeend'] = 0;
				$userEnrollment['modifierid'] = 2;
				$userEnrollment['timecreated'] = $time;
				$userEnrollment['timemodified'] = $time;
				//print_r($userEnrollment);
				
				if ( !$this->registro->checkUserEnrollments($id_usr_moodle, $enrol->id) ) {
					$this->registro->insertUserEnrollments($userEnrollment);
				}
					
				// Se asigna el rol
				$roleAssignment['roleid'] = 5;
				$roleAssignment['contextid'] = $contexto->id;
				$roleAssignment['userid'] = $id_usr_moodle;
				$roleAssignment['timemodified'] = $time;
				$roleAssignment['modifierid'] = 2;
				$roleAssignment['component'] = '';
				$roleAssignment['itemid'] = 0;
				$roleAssignment['sortorder'] = 0;
				//print_r($roleAssignment);
				
				if ( !$this->registro->checkRoleAssignments($id_usr_moodle, $contexto->id) ) {
					if($this->registro->insertRoleAssignments($roleAssignment)) {
						$respuesta = $id_usuario;
					}
				}
			}
		}
		
		$remitente = trim($registro['nombre']." ".$registro['ap_paterno']." ".$registro['ap_materno']);
		$asunto = 'Comprobante de Registro al Centro de Capacitación Virtual CONRICYT';
		$body = '<table width="100%" border="1" cellspacing="0" cellpadding="10" border="0" bordercolor="#FFFFFF"><tr><td bgcolor="#005199" align="center"><font size="4" face="Arial" color="#e0e0e0"><strong>Comprobante de registro a las Jornadas de Capacitaci&oacute;n 2015.</strong></font></td></tr></table>';
		$body .= '<br /><br /><p><font size="3" face="Arial" color="#006699"><strong>&iexcl;Hola, '.$remitente.'!</strong></font></p>';
		$body .= '<p><font size="3" face="Arial" color="#006699">&iexcl;Tu registro al <em>Centro de Capacitaci&oacute;n Virtual del CONRICYT</em> se ha realizado con &eacute;xito!</font></p>';
		$body .= '<p><font size="3" face="Arial" color="#006699">Adjunto encontrar&aacute;s tu comprobante de registro que contiene tus claves de usuario y contrase&ntilde;a y en el que se te indica la forma de ingresar al Centro de Capacitaci&oacute;n Virtual del CONRICYT.</p>';
		$body .= '<p><font size="3" face="Arial" color="#006699">Asimismo se encuentran los t&eacute;rminos y condiciones sobre el uso del Centro de Capacitaci&oacute;n del CONRICYT.</font></p>';
		$body .= '<p><font size="3" face="Arial" color="#FF0000">Para cualquier duda o aclaraci&oacute;n pueden contactarnos en: centro.capacitacion@conricyt.mx y al tel&eacute;fono (55) 5322 7700 ext. 4020 a 4026</font></p>';
		$body .= '<table width="100%" border="1" cellspacing="0" cellpadding="10" border="0" bordercolor="#FFFFFF"><tr><td bgcolor="#e0e0e0" align="center"><font size="3" face="Arial" color="#005199"><strong>Consejo Nacional de Ciencia y Tecnolog&iacute;a (CONACYT)</strong></font></td></tr></table>';
		
		$this->enviarCorreo($id_usuario, $registro['correo'], $remitente, $asunto, $body);
		
		$this->session->unset_userdata('cursos_seleccionados');
		
		echo $respuesta;
	}
	
	public function matricular() {
		$curso = $this->input->post('curso');
		$usuario = $this->registro->consultarUsuarioPorID($this->session->userdata('usuario'));
		
		$time = time();
		$id_usuario = $usuario['id_usuario'];
		
		// Se inserta curso en nuestra base de datos
		$datos['usuario'] = $id_usuario;
		$datos['curso'] = $curso;
		$datos['fecha_inscripcion'] = date('Y-m-d H:i:s');
			
		// Revisa que el usuario no esté ya registrado
		if ( !$this->registro->verificarUsuarioEnCurso($id_usuario, $curso) ) {
			$this->registro->insertarUsuarioCurso($datos);
		}
		
		// Moodle
		$usr_moodle = $this->registro->checkMoodleUserExists($usuario['correo']);
		$id_usr_moodle = $usr_moodle->id;
		
		$datos_curso = $this->registro->getShortnameByID($curso);
		
		// Con los datos anteriores consultamos el curso en Moodle
		$curso_moodle = $this->registro->getMoodleCourse($datos_curso->nombre_corto);
		
		// Obtenemos el contexto de Moodle
		$contexto = $this->registro->getMoodleContext($curso_moodle->id);
		
		// Consultamos la matricula registrada
		$enrol = $this->registro->getMoodleEnrol($curso_moodle->id);
		
		// Matricula al usuario
		$userEnrollment['status'] = 0;
		$userEnrollment['enrolid'] = $enrol->id;
		$userEnrollment['userid'] = $id_usr_moodle;
		$userEnrollment['timestart'] = $time;
		$userEnrollment['timeend'] = 0;
		$userEnrollment['modifierid'] = 2;
		$userEnrollment['timecreated'] = $time;
		$userEnrollment['timemodified'] = $time;
		//print_r($userEnrollment);
			
		$this->registro->insertUserEnrollments($userEnrollment);
		
		// Se asigna el rol
		$roleAssignment['roleid'] = 5;
		$roleAssignment['contextid'] = $contexto->id;
		$roleAssignment['userid'] = $id_usr_moodle;
		$roleAssignment['timemodified'] = $time;
		$roleAssignment['modifierid'] = 2;
		$roleAssignment['component'] = '';
		$roleAssignment['itemid'] = 0;
		$roleAssignment['sortorder'] = 0;
		//print_r($roleAssignment);
			
		$this->registro->insertRoleAssignments($roleAssignment);
		
		redirect(base_url('usuario'));
	}
}
?>