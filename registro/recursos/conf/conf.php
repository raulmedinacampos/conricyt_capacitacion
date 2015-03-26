<?php
// Archivo de configuracion del sistema
// SERVIDOR DE DESARROLLO
/*define('HOST_DATA_BASE', '192.168.0.50');              // Servidor que tiene corriendo la base de datos.
define('DATA_BASE_NAME', 'capacitacion_registro');           // Nombre de la Base de Datos de maestra.
define('LOGIN_ACCESO_DATA_BASE', 'casa_tibet');    // Login de Acceso a la Base de Datos.
define('PASS_ACCESO_DATA_BASE', 'SeaTNUWjwchP6C2b'); // Psssword de Acceso a la Base de Datos.*/
define('HOST_DATA_BASE', '127.0.0.1');              // Servidor que tiene corriendo la base de datos.
define('DATA_BASE_NAME', 'capacitacion_registro');           // Nombre de la Base de Datos de maestra.
define('LOGIN_ACCESO_DATA_BASE', 'root');    // Login de Acceso a la Base de Datos.
define('PASS_ACCESO_DATA_BASE', ''); // Psssword de Acceso a la Base de Datos.
define('LLAVE', '!@#$%^&*()_+=-{}][;";/?<>.,');         // Llave para Tokens y Passwords

// ********** CONFIGURACION CON LA INTERACCION DE MOODLE Y EL SISTEMA
define('MOODLE_ACTIVE', 'v2.2'); // Bandera para indicar la version activa de moodle. Valores permitidos: v1.9, v2.2 y BOTH
define('MOODLE_V1_9', 'v1.9'); // Constante para las versiones de Moodle v1.9
define('MOODLE_V2_2', 'v2.2'); // Constante para las versiones de Moodle v2.2
//define('MOODLE_BOTH', 'BOTH'); // Constante para usar ambas versiones de Moodle al mismo tiempo
define('MOODLE_BOTH', 'NO'); // Constante para usar ambas versiones de Moodle al mismo tiempo
define('LOGGER_FILE', 'log/moodle.log'); //Constante que se usa para llevar una bitacora de movimientos del sistema
define('LOGGER_LEVEL', 1); // Nivel de bitacora del sistema 0 es para producción, 1 es para desarrollo

