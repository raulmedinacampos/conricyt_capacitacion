<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/** PHPExcel */
require_once 'PHPExcel.php';
/** PHPExcel_IOFactory */
require_once 'PHPExcel/IOFactory.php';
 
class Excel extends PHPExcel {
	public function __construct() {
		parent::__construct();
	}
}	

	?>