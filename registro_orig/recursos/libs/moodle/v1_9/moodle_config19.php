<?php
// CONFIGURACION DE PARAMETROS DE CONEXION A BASE DE DATOS DEL MOODLE19
$CFG_v19 = new stdClass(); //Creación del objeto de conexion a base de datos con moodle
$CFG_v19->dbtype    = 'mysql'; // Tipo de base de datos que usa moodle
$CFG_v19->dbhost    = 'localhost'; // Servidor de la base de datos de Moodle
//$CFG_v19->dbhost    = 'mysql01.sined.svc.vb-anuies.mx'; // Servidor de la base de datos de Moodle
$CFG_v19->dbname    = 'moodle19'; //Nombre de la base de datos del moodle
$CFG_v19->dbuser    = 'moodle19'; // Nombre del usuario dela base de datos
$CFG_v19->dbpass    = '#UdN23aN'; //Contraseña de conexión con la base de datos de Moodle
$CFG_v19->prefix    = 'mdl_';     // Prefijos de la base de datos de Moodle
$CFG_v19->passwordsaltmain = 'P^#3k.5DfkEd>*5zRQZ[2I&Wp'; // Salt o semilla de encripcion de contraseñas usado en el archivo de configuración del Mooodle

/**
 * Function to connect to the moodle database v1.9
 * @return unknown
 */
function Sdx_ConectaBasev19(){
	global $CFG_v19;
	$dbh = mysqli_connect($CFG_v19->dbhost, $CFG_v19->dbuser, $CFG_v19->dbpass, $CFG_v19->dbname);
// 	mysqli_autocommit($dbh,false);
	return $dbh;
}

/**
 * Calculate hashed value from password using current hash mechanism.
 *
 * @param string password
 * @return string password hash
 */
function hash_internal_user_passwordv19($password) {
	global $CFG_v19;

	if (isset($CFG_v19->passwordsaltmain)) {
		return md5($password.$CFG_v19->passwordsaltmain);
	} else {
		return md5($password);
	}
}
class Configv19{}
?>