<?php
class CourseSectionDaov19{
	function save( $courseid, $section, $summary, $sequence, $visible, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov19:6:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		//registra al usuario
		$INSERT_COURSE_SECTIONS = 'INSERT INTO  '.$CFG_v19->prefix.'course_sections ('.
				'course, section, summary, sequence,visible) VALUES '.
				'(?,?,?,?,?)';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE_SECTIONS);
	
		mysqli_stmt_bind_param($stmt, 'isssi', $courseid, $section, $summary, $sequence, $visible);
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_course_section=mysqli_insert_id($dbhm19);
		$log->LogInfo("[CourseSectionDaov19:32:save]".$id_course_section."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[CourseSectionDaov19:34:save]closed statement");
	
	
		$courseSection = new stdClass();
		$courseSection->id = $id_course_section;
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[CourseSectionDaov19:44:save]---------<");
		return $courseSection;
	}
	
	
	function getMaxSequence(&$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov19:65:getMaxSequence]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$SELECT_MAX_SEQUENCE = 'SELECT MAX( sequence * 1) FROM '.$CFG_v19->prefix.'course_sections WHERE sequence IS NOT NULL';
	
		$log->LogInfo("[CourseSectionDaov19:74:getMaxSequence]".$SELECT_MAX_SEQUENCE);
	
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_MAX_SEQUENCE);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $maxSequence);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
	
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CourseSectionDaov19:85:getMaxSequence]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
	
		$log->LogInfo("[CourseSectionDaov19:92:getMaxSequence]".$maxSequence);
	
		if(!isset($maxSequence)){
			$maxSequence=1;
		}		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseSectionDaov19:103:getMaxSequence]---------<");
		return $maxSequence;
	}
	
	function deleteAllCourseSections($idCourse, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseSectionDaov19:93:deleteAllCourseSections]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_COURSE_SECTIONS = 'DELETE FROM '.$CFG_v19->prefix.'course_sections WHERE course=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE_SECTIONS);
		mysqli_stmt_bind_param($stmt, 'i', $idCourse );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[CourseSectionDaov19:109:deleteAllCourseSections]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseSectionDaov19:117:deleteAllCourseSections]---------<");
		return true;
	}
	
}
?>