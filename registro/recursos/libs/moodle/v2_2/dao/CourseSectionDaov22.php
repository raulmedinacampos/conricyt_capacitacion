<?php
class CourseSectionDaov22{
	
	function save( $courseid, $section,  $name, $summary, $summaryformat, $sequence, $visible, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov22:26:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		
		$INSERT_COURSE_SECTIONS = 'INSERT INTO  '.$CFG_v22->prefix.'course_sections ('.
				'course, section, name, summary, summaryformat, sequence, visible) VALUES '.
				'(?,?,?,?,?,?,?)';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE_SECTIONS);
	
		$time = time();
		mysqli_stmt_bind_param($stmt, 'iissisi', $courseid, $section,  $name, $summary, $summaryformat, $sequence, $visible );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
	
		$id_course_section=mysqli_insert_id($dbhm22);
		$log->LogInfo("[CourseSectionDaov22:58:save]".$id_course_section."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[CourseSectionDaov22:58:save]closed statement");
	
	
		$courseSection = new stdClass();
		$courseSection->id = $id_course_section;
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[CourseSectionDaov22:66:save]---------<");
		return $courseSection;
	}
	
	function getMaxSequence( &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov22:65:getMaxSequence]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_MAX_SEQUENCE = 'SELECT MAX( sequence * 1) FROM '.$CFG_v22->prefix.'course_sections WHERE sequence IS NOT NULL';
		
		$log->LogInfo("[CourseSectionDaov22:74:getMaxSequence]".$SELECT_MAX_SEQUENCE);
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_MAX_SEQUENCE);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $maxSequence);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CourseSectionDaov22:85:getMaxSequence]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[CourseSectionDaov22:92:getMaxSequence]".$maxSequence);
		
		if(!isset($maxSequence)){
			$maxSequence=1;
		}		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[CourseSectionDaov22:88:getMaxSequence]---------<");
		return $maxSequence;
	}
	
	function deleteAllCourseSections($idCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov22:93:deleteAllCourseSections]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_COURSE_SECTIONS = 'DELETE FROM '.$CFG_v22->prefix.'course_sections WHERE course=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE_SECTIONS);
		mysqli_stmt_bind_param($stmt, 'i', $idCourse );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[CourseSectionDaov22:109:deleteAllCourseSections]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[CourseSectionDaov22:117:deleteAllCourseSections]---------<");
		return true;
	}
	
}
?>