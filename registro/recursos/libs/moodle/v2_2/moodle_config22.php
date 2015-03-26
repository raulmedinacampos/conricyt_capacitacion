<?php
// CONFIGURACION DE PARAMETROS DE CONEXION A BASE DE DATOS DEL MOODLE VERSIONES 2.2.X
$CFG_v22 = new stdClass(); //Creación del objeto de conexion a base de datos con moodle
$CFG_v22->dbtype    = 'mysql'; // Tipo de base de datos que usa moodle
$CFG_v22->dbhost    = 'localhost'; // Servidor de la base de datos de Moodle
$CFG_v22->dbname    = 'moodle'; //Nombre de la base de datos del moodle
$CFG_v22->dbuser    = 'root'; // Nombre del usuario dela base de datos
$CFG_v22->dbpass    = '';    //Contrasena de conexión con la base de datos de Moodle
$CFG_v22->prefix    = 'mdl_';       // Prefijos de la base de datos de Moodle
$CFG_v22->passwordsaltmain = 'vl{qoV#~EPL5Zs>EW#VW5nUCO';  // Salt o semilla de encripcion de contrase�as usado en el archivo de configuración del Mooodle

/**
 * Function to connect to the moodle database v1.9
 * @return unknown
 */
function Sdx_ConectaBasev22(){
	global $CFG_v22;
	$dbh = mysqli_connect($CFG_v22->dbhost, $CFG_v22->dbuser, $CFG_v22->dbpass, $CFG_v22->dbname);
	//mysqli_autocommit($dbh,false);
	return $dbh;
}

/**
 * Calculate hashed value from password using current hash mechanism.
 *
 * @param string password
 * @return string password hash
 */
function hash_internal_user_passwordv22($password) {
	global $CFG_v22;

	if (isset($CFG_v22->passwordsaltmain)) {
		return md5($password.$CFG_v22->passwordsaltmain);
	} else {
		return md5($password);
	}
}
class Configv22{
	
}
?>