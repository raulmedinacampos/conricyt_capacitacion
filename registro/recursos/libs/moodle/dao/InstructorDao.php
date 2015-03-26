<?php
class InstructorDao{
	function actualizarInstructorId($idInstructorMoodle, $idInstructor, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[InstructorDao:5:actualizarInstructorId]--------->");
		
		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE Instructores SET id_moodle19 = ? WHERE  Id_Instructor = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE Instructores SET id_moodle22 = ? WHERE  Id_Instructor = ?');
		}
		mysqli_stmt_bind_param($stmt, 'ii', $idInstructorMoodle, $idInstructor);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("registrar-ROLLBACK");
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		$log->LogInfo("[InstructorDao:22:actualizarInstructorId]---------<");
	}
	
	function getMoodleId($Id_Instructor, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[InstructorDao.getMoodleId:27]Consultando los id de moodle");
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'SELECT id_moodle19, id_moodle22 FROM Instructores WHERE Id_Instructor=?');
		mysqli_stmt_bind_param($stmt, 'i', $Id_Instructor);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_moodle19, $id_moodle22);
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[modificar:95]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
}
?>