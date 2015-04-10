<?php
class CursoDao{
	function actualizarCursoId($idCursoMoodle, $idCurso, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CursoDao:5:actualizarCursoId]--------->");
		
		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE Cursos SET id_moodle19 = ? WHERE  Id_Curso = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE Cursos SET id_moodle22 = ? WHERE  Id_Curso = ?');
		}
		mysqli_stmt_bind_param($stmt, 'ii', $idCursoMoodle, $idCurso);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("registrar-ROLLBACK");
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		$log->LogInfo("[CursoDao:22:actualizarCursoId]---------<");
	}
	
	function getMoodleId($Id_Curso, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CursoDao.getMoodleId:27]Consultando los id de moodle");
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'SELECT id_moodle19, id_moodle22 FROM Cursos WHERE Id_Curso=?');
		mysqli_stmt_bind_param($stmt, 'i', $Id_Curso);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_moodle19, $id_moodle22);
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[CursoDao.getMoodleId:37]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
	
	function deleteInstructor(&$dbh, $Id_Curso, $Id_Instructor){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[deleteInstructor:48] deleteInstructor");
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'DELETE FROM Cursos_X_Instructor WHERE Id_Curso=? AND Id_Instructor=?');
		mysqli_stmt_bind_param($stmt, 'ii',  $Id_Curso, $Id_Instructor);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[deleteInstructor:56]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		return true;
	}
	
	function addInstructor(&$dbh, $Id_Curso, $Id_Instructor){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[deleteInstructor:48] deleteInstructor");
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, 'INSERT INTO Cursos_X_Instructor (Id_Curso, Id_Instructor) VALUES (?,?)');
		mysqli_stmt_bind_param($stmt, 'ii', $Id_Curso, $Id_Instructor); 
		mysqli_stmt_execute($stmt);
		$Id_CursoInstructor=mysqli_insert_id($dbh);
		mysqli_stmt_close($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[deleteInstructor:56]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_close($stmt);
		$cursosInstructor=new stdClass();
		$cursosInstructor->id=$Id_CursoInstructor;
		
		return $cursosInstructor;
	}
	
	function findById(&$dbh, $id){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CursoDao:87:findById]--------->");
	
		
		$SELECT_COURSE = 'SELECT Id_Curso, Nombre_Curso, Periodo, Fecha_Inicio, '.
				'Fecha_Final, Horario, Horas, Modalidad, '.
				'Perfil_Participante, Min_Participantes, Max_Participantes, Costo, '.
				'Id_Disciplina, Id_TipoEvento, id_moodle19, id_moodle22, '.
				'Id_CatCurso '.
				'FROM Cursos WHERE Id_Curso=?';
	
		$log->LogInfo("[CursoDao:77:findById]".$SELECT_COURSE);
	
		$course = new stdClass();
	
		$stmt2 = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt2, $SELECT_COURSE);
		mysqli_stmt_bind_param($stmt2, 'i', $id);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $course->Id_Curso,$course->Nombre_Curso,$course->Periodo,$course->Fecha_Inicio,
		$course->Fecha_Final,$course->Horario,$course->Horas,$course->Modalidad,
		$course->Perfil_Participante,$course->Min_Participantes,$course->Max_Participantes,$course->Costo,
		$course->Id_Disciplina,$course->Id_TipoEvento,$course->id_moodle19,$course->id_moodle22,$course->Id_CatCurso);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
	
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CursoDao:94:findById]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
	
	
		$log->LogInfo("[CursoDao:105:findById]---------<");
		return $course;
	}
}
?>