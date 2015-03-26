<?php
	class Mailer {
		public function enviaRegistro($datos) {
			require_once("recursos/phpmailer/class.phpmailer.php");
			
			$mail = new PHPMailer();
			
			$body = '
		<html>
		<body>
		<div id="contenedor" style="margin:0 auto; width:650px; font-family:Verdana, Geneva, Scada, sans-serif; font-size:12px;" >
		  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			  <td><img src="http://capacitacion.conricyt.mx/micrositio/images/header_correo.jpg" alt="Capacitaci&oacute;n Virtual" width="650" height="150" border="0" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td><table width="650" border="0" cellpadding="8" cellspacing="0" bgcolor="#F0F0F0" style="border:2px #999999 solid; border-radius:8px;">
				  <tr>
					<td><img src="sitio-img/titulo.png" width="624" height="59"></td>
				  </tr>
				  <tr>
					<td><p><font size="2" color="#000000" face="Verdana, Geneva, Scada, sans-serif">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse pharetra auctor arcu sed molestie. Proin volutpat, tellus quis fermentum consectetur, lacus eros ultricies nulla, sollicitudin semper nibh libero ac lectus. Donec ut fringilla quam, vitae consectetur diam. Phasellus vulputate eros elit, at cursus neque rutrum in. Praesent ac felis mauris. Duis tincidunt feugiat purus sit amet congue. Morbi dictum turpis nec est luctus blandit. Aenean commodo tortor non tellus placerat, in mollis justo rutrum. Aenean tincidunt enim id nibh scelerisque, non congue tortor commodo. Duis et velit massa. Cras pretium eget arcu quis feugiat.'.utf8_decode($datos['nombre']).'<br />'.$datos['correo'].'<br />'.$datos['pass'].'</font></p></td>
				  </tr>
				</table></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td><table width="650" border="0" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0" style="border:2px #999999 solid; border-radius:8px;">
				  <tr>
					<td><p align="center" style="margin-top:20px;"><font color="#666666" size="1" face="Verdana, Geneva, Scada, sans-serif">CONSORCIO NACIONAL DE RECURSOS DE INFORMACI&Oacute;N CIENT&Iacute;FICA Y TECNOL&Oacute;GICA (CONRICyT)<br />
						Copyright &copy; 2011 Derechos Reservados</font></p>
					  <p align="center" style="margin-bottom:20px;"><font color="#666666" size="1" face="Verdana, Geneva, Scada, sans-serif">Av. Insurgentes Sur 1582, Col. Cr&eacute;dito Constructor, Del. Benito Ju&aacute;rez <br />
						C.P.: 03940, M&eacute;xico, D.F. Tel: (55) 5322-7700. Ext. 4020, 4021, 4022</font></p></td>
				  </tr>
				</table></td>
			</tr>
		  </table>
		</div>
		</body>
		</html>';
			
			$mail->IsSMTP(); // se especifica que debe ser SMTP
			$mail->SMTPDebug  = 0;                     // debug
													   // 0 = no muestra nada
													   // 1 = errores y mensajes
													   // 2 = mensajes
			$mail->SMTPAuth   = true;					// activa autenticación
			$mail->Host       = "smtp.gmail.com";		// servidor de correo
			$mail->Port       = 465;                    // puerto de salida que usa Gmail
			$mail->SMTPSecure = 'ssl';					// protocolo de autenticación
			$mail->Username   = "rmedina.conricyt@gmail.com";
			$mail->Password   = 'nuREtuma13';
			
			$mail->SetFrom('rmedina.conricyt@gmail.com', 'CONRICyT');
			$mail->AddReplyTo('rmedina.conricyt@gmail.com', 'CONRICyT');
			$mail->Subject    = "Registro al Centro de Capacitación CONRICyT";
			$mail->AltBody    = "Tu registro al Centro de Capacitación CONRICyT se ha realizado con éxito";
			
			$mail->MsgHTML($body);
			
			$mail->AddAddress($datos['correo'], utf8_decode($datos['nombre']));
			
			if(!$mail->Send()) {
			  echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
			  echo "Message sent!";
			}
		}
		
		public function enviaCurso($datos) {
			require_once("recursos/phpmailer/class.phpmailer.php");
			
			$mail = new PHPMailer();
			
			$body = '
		<html>
		<body>
		<div id="contenedor" style="margin:0 auto; width:650px; font-family:Verdana, Geneva, Scada, sans-serif; font-size:12px;" >
		  <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
			<tr>
			  <td><img src="http://capacitacion.conricyt.mx/micrositio/images/header_correo.jpg" alt="Capacitaci&oacute;n Virtual" width="650" height="150" border="0" /></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td><table width="650" border="0" cellpadding="8" cellspacing="0" bgcolor="#F0F0F0" style="border:2px #999999 solid; border-radius:8px;">
				  <tr>
					<td><img src="sitio-img/titulo.png" width="624" height="59"></td>
				  </tr>
				  <tr>
					<td><p><font size="2" color="#000000" face="Verdana, Geneva, Scada, sans-serif">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Suspendisse pharetra auctor arcu sed molestie. Proin volutpat, tellus quis fermentum consectetur, lacus eros ultricies nulla, sollicitudin semper nibh libero ac lectus. Donec ut fringilla quam, vitae consectetur diam. Phasellus vulputate eros elit, at cursus neque rutrum in. Praesent ac felis mauris. Duis tincidunt feugiat purus sit amet congue. Morbi dictum turpis nec est luctus blandit. Aenean commodo tortor non tellus placerat, in mollis justo rutrum. Aenean tincidunt enim id nibh scelerisque, non congue tortor commodo. Duis et velit massa. Cras pretium eget arcu quis feugiat.</font></p></td>
				  </tr>
				</table></td>
			</tr>
			<tr>
			  <td>&nbsp;</td>
			</tr>
			<tr>
			  <td><table width="650" border="0" cellpadding="0" cellspacing="0" bgcolor="#F0F0F0" style="border:2px #999999 solid; border-radius:8px;">
				  <tr>
					<td><p align="center" style="margin-top:20px;"><font color="#666666" size="1" face="Verdana, Geneva, Scada, sans-serif">CONSORCIO NACIONAL DE RECURSOS DE INFORMACI&Oacute;N CIENT&Iacute;FICA Y TECNOL&Oacute;GICA (CONRICyT)<br />
						Copyright &copy; 2011 Derechos Reservados</font></p>
					  <p align="center" style="margin-bottom:20px;"><font color="#666666" size="1" face="Verdana, Geneva, Scada, sans-serif">Av. Insurgentes Sur 1582, Col. Cr&eacute;dito Constructor, Del. Benito Ju&aacute;rez <br />
						C.P.: 03940, M&eacute;xico, D.F. Tel: (55) 5322-7700. Ext. 4020, 4021, 4022</font></p></td>
				  </tr>
				</table></td>
			</tr>
		  </table>
		</div>
		</body>
		</html>';
			
			$mail->IsSMTP(); // se especifica que debe ser SMTP
			$mail->SMTPDebug  = 0;                     // debug
													   // 0 = no muestra nada
													   // 1 = errores y mensajes
													   // 2 = mensajes
			$mail->SMTPAuth   = true;					// activa autenticación
			$mail->Host       = "smtp.gmail.com";		// servidor de correo
			$mail->Port       = 465;                    // puerto de salida que usa Gmail
			$mail->SMTPSecure = 'ssl';					// protocolo de autenticación
			$mail->Username   = "rmedina.conricyt@gmail.com";
			$mail->Password   = 'nuREtuma13';
			
			$mail->SetFrom('rmedina.conricyt@gmail.com', 'CONRICyT');
			$mail->AddReplyTo('rmedina.conricyt@gmail.com', 'CONRICyT');
			$mail->Subject    = "Inscripción al curso";
			$mail->AltBody    = "Tu registro al Centro de Capacitación CONRICyT se ha realizado con éxito";
			
			$mail->MsgHTML($body);
			
			$mail->AddAddress($datos['correo'], utf8_decode($datos['nombre']));
			
			if(!$mail->Send()) {
			  echo "Mailer Error: " . $mail->ErrorInfo;
			} else {
			  echo "Message sent!";
			}
		}
	}
?>