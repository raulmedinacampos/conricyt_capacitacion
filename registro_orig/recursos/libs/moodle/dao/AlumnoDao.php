<?php
class AlumnoDao{
	function actualizarAlumnoId($idAlumnoMoodle, $idAlumno, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[AlumnoDao:5:actualizarAlumnoId]--------->");
		
		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE Alumnos SET id_moodle19 = ? WHERE  Id_Alumno = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE Alumnos SET id_moodle22 = ? WHERE  Id_Alumno = ?');
		}
		mysqli_stmt_bind_param($stmt, 'ii', $idAlumnoMoodle, $idAlumno);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("registrar-ROLLBACK");
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		$log->LogInfo("[AlumnoDao:22:actualizarAlumnoId]---------<");
	}
	
	function getMoodleId($Id_Alumno, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[AlumnoDao:getMoodleId:27]----------->");
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'SELECT id_moodle19, id_moodle22 FROM Alumnos WHERE Id_Alumno=?');
		mysqli_stmt_bind_param($stmt, 'i', $Id_Alumno);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_moodle19, $id_moodle22);
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[AlumnoDao:getMoodleId:37]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		$log->LogInfo("[AlumnoDao:getMoodleId:43]-----------<");
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
	
	function findMoodleIds(&$dbh){
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			$SELECT_ENROL = 'SELECT Id_Alumno, id_moodle19 FROM alumnos WHERE id_moodle19 IS NOT NULL ORDER BY 1';
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			$SELECT_ENROL = 'SELECT Id_Alumno, id_moodle22 FROM alumnos WHERE id_moodle22 IS NOT NULL ORDER BY 1';
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			$SELECT_ENROL = 'SELECT Id_Alumno, id_moodle19, id_moodle22 FROM alumnos WHERE id_moodle22 IS NOT NULL OR id_moodle19 IS NOT NULL ORDER BY 1';
		}
	
		$stmt2 = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt2, $SELECT_ENROL);
		mysqli_stmt_execute($stmt2);
		if(MOODLE_ACTIVE==MOODLE_V1_9){
			mysqli_stmt_bind_result($stmt2, $id, $id_moodle19);
		}else if(MOODLE_ACTIVE==MOODLE_V2_2){
			mysqli_stmt_bind_result($stmt2, $id, $id_moodle22);
		}else if(MOODLE_ACTIVE==MOODLE_BOTH){
			mysqli_stmt_bind_result($stmt2, $id, $id_moodle19, $id_moodle22);
		}
		
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt2)) {
			$bi=new stdClass();
			$bi->id=$id;
			if(MOODLE_ACTIVE==MOODLE_V1_9){
				$bi->id_moodle19=$id_moodle19;
			}else if(MOODLE_ACTIVE==MOODLE_V2_2){
				$bi->id_moodle22=$id_moodle22;
			}else if(MOODLE_ACTIVE==MOODLE_BOTH){
				$bi->id_moodle19=$id_moodle19;
				$bi->id_moodle22=$id_moodle22;
			}
			
			$arrayData[$i++]=$bi;
		}
	
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
	
		return $arrayData;
	}
	
	function insertFromMoodle(&$dbh, $Nombres, $Paterno, $Id_Pais, $Email, $Usuario, $con, $externalId, $flag){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'INSERT INTO Alumnos (Nombres, Paterno, 	Id_Pais, Email, Usuario, Contra,'.($flag?'id_moodle19':'id_moodle22').') VALUES  (?, ?, ?,  ?, ?, ?, ?)');
		mysqli_stmt_bind_param($stmt, 'ssisssi', $Nombres, $Paterno, $Id_Pais, $Email, $Usuario, $con, $externalId);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[AlumnoDao:100:insertFromMoodle]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		$Id_Alumno=mysqli_insert_id($dbh);
		mysqli_stmt_close($stmt);
		return $Id_Alumno;
	}
}
?>