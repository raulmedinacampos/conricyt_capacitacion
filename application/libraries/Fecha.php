<?php
class Fecha {
	public static function ConvertirMySQL($fecha, $hora = "") {
		$nFecha = explode("/", $fecha);
		$nFecha = array_reverse($nFecha);
		$nFecha = implode("-", $nFecha);

		return $nFecha;
	}
	
	public static function ConvertirNormal($fecha, $hora = "") {
		$nFecha = explode("-", $fecha);
		$nFecha = array_reverse($nFecha);
		$nFecha = implode("/", $nFecha);
	
		return $nFecha;
	}
	
	public static function MostrarFormatoLargo($fecha) {
		$arr_mes = array('', 'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio', 'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre');
		list($anio, $mes, $dia) = explode("-", $fecha);
		
		return "México, D.F., a ".(int)$dia." de ".$arr_mes[(int)$mes]." de ".$anio;
	}
}
?>