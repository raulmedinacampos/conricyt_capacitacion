<?php
if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}

class AlumnoGrupoDao{
	function actualizarGruposId($idMoodle, $idAlumno, $id, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao:5:actualizarAlumnoId]--------->");

		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE Alumnos_X_Grupos SET id_moodle19 = ? WHERE Id_Alumno =? AND Id_Grupo = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE Alumnos_X_Grupos SET id_moodle22 = ? WHERE Id_Alumno =? AND Id_Grupo = ?');
		}
		mysqli_stmt_bind_param($stmt, 'iii', $idMoodle, $idAlumno, $id);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("registrar-ROLLBACK");
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		$log->LogDebug("[AlumnoGrupoDao:22:actualizarAlumnoId]---------<");
	}

	function getMoodleId($Id_Grupo, $idAlumno, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao:getMoodleId:31]------------>");
		$stmt = mysqli_stmt_init($dbh);
		$log->LogDebug("[AlumnoGrupoDao:getMoodleId:33]Id_Grupo=".$Id_Grupo);
		mysqli_stmt_prepare($stmt, "SELECT id_moodle19, id_moodle22 FROM Alumnos_X_Grupos WHERE Id_Alumno =? AND Id_Grupo=?");
		mysqli_stmt_bind_param($stmt, 'ii', $idAlumno, $Id_Grupo);
		mysqli_stmt_bind_result($stmt, $id_moodle19, $id_moodle22);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[AlumnoGrupoDao:getMoodleId:42]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogDebug("[AlumnoGrupoDao:getMoodleId:47]registros= $nr");
		if($nr==0){
			$log->LogDebug("[AlumnoGrupoDao:getMoodleId:49]no hay registros");
			return null;
		}
		
		
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		$log->LogDebug("[AlumnoGrupoDao:getMoodleId:57] Id_Grupo=$Id_Grupo, v1.9=$id_moodle19, v2.2=$id_moodle22");
		$log->LogDebug("[AlumnoGrupoDao:getMoodleId:58]------------<");
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
	
	
	//insertar
	function insert(&$dbh, $id_alumno, $id_curso, $id_grupo, $estatus_curso	){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("insert-------->");
		//registra al usuario
		$INSERT_CAT_CURSO = 'INSERT INTO Alumnos_X_Grupos ('.
				'Id_Alumno, Id_Curso, Id_Grupo, Estatus_Curso) VALUES '.
				'(?,?,?,?)';
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $INSERT_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'iiis', $id_alumno, $id_curso, $id_grupo, $estatus_curso	);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		
		$id_AlumnoGrupo=mysqli_insert_id($dbh);
		//$log->LogDebug("[AlumnoGrupoDao:58:save]".$id_user."");
		mysqli_stmt_close($stmt);
		// 		//$log->LogDebug("[AlumnoGrupoDao:58:save]closed statement");
		
		$AlumnoGrupo= new stdClass();
		$AlumnoGrupo->id = $id_AlumnoGrupo;
		
		//$log->LogDebug("[AlumnoGrupoDao:66:insert]---------<");
		return $AlumnoGrupo;
	}
	
	//actualizar
	function update(&$dbh, $estatus_curso, $id_alumno, $id_curso, $id_grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("insert-------->");
		//registra al usuario
		$UPDATE_CAT_CURSO = 'UPDATE Alumnos_X_Grupos SET '.
				'Estatus_Curso=? WHERE Id_Alumno = ? AND Id_Curso=? AND Id_Grupo=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $UPDATE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'siii', $estatus_curso, $id_alumno, $id_curso, $id_grupo);
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		return true;
	}
	//eliminar
	function delete(&$dbh,  $id_alumno, $id_curso, $id_grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao:121]delete-------->");
		//registra al usuario
		$DELETE_CAT_CURSO = 'DELETE FROM Alumnos_X_Grupos WHERE Id_Alumno = ? AND Id_Curso=? AND Id_Grupo=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $DELETE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'iii', $id_alumno, $id_curso, $id_grupo);
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		return true;
	}
	
	function deleteByGroup(&$dbh,  $id_curso, $id_grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao:121]delete-------->");
		//registra al usuario
		$DELETE_CAT_CURSO = 'DELETE FROM Alumnos_X_Grupos WHERE Id_Curso=? AND Id_Grupo=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $DELETE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'ii', $id_curso, $id_grupo);
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		return true;
	}
	
	//consultar usando filtros
	function findByGroup(&$dbh, $id_curso,$id_grupo){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("find-------->");
		//registra al usuario
		$FIND_CAT_CURSO = 'SELECT a.Id_Alumno,a.Id_Curso,a.Id_Grupo,a.Estatus_Curso,a.id_moodle19,a.id_moodle22, '.
			'CONCAT(Nombres, \' \', IFNULL(Paterno,\'\'), \' \', IFNULL(Materno,\'\')) AS Nombre '.
			'FROM Alumnos_X_Grupos a, Alumnos b '.
			'WHERE a.Id_Alumno = b.Id_Alumno '.
			'AND Id_Curso= ? AND Id_Grupo=?';
			
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
		mysqli_stmt_bind_param($stmt, 'ii',$id_curso,$id_grupo);

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_alumno, $id_curso, $id_grupo, $estatus_curso, $id_moodle19, $id_moodle22, $nombre);
		mysqli_stmt_store_result($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return null;
		}
	
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt)) {
			$bi=new stdClass();
			$bi->id_alumno=$id_alumno;
			$bi->id_curso=$id_curso;
			$bi->id_grupo=$id_grupo;
			$bi->estatus_curso=$estatus_curso;
			$bi->id_moodle19=$id_moodle19;
			$bi->id_moodle22=$id_moodle22;		
			$bi->nombre = $nombre;
			$arrayData[$i++]=$bi;
		}
	
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		return $arrayData;
	}
	
	//consultar usando filtros
	function findAlumnosSinGrupo(&$dbh, $id_curso){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao::findAlumnosSinGrupo:209]-------->");
		//registra al usuario
		$FIND_CAT_CURSO = 'SELECT ac.Id_Alumno,ac.Id_Curso, '.
			'CONCAT(Nombres, \' \', IFNULL(Paterno,\'\'), \' \', IFNULL(Materno,\'\')) AS Nombre '.
			'FROM Cursos c, Alumnos_X_Cursos ac, Alumnos a '.
			'WHERE c.Id_Curso = ac.Id_Curso '.
			'AND ac.Id_Alumno = a.Id_Alumno '.
			'AND c.Id_Curso = ? '.
			'AND ac.Id_Alumno NOT IN ( SELECT g.Id_Alumno FROM Alumnos_X_Grupos g WHERE g.Id_Curso = c.Id_Curso )';
	
		$log->LogDebug("[AlumnoGrupoDao::findAlumnosSinGrupo:219] ".$FIND_CAT_CURSO." - ".$id_curso);
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
		mysqli_stmt_bind_param($stmt, 'i',$id_curso);
	
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_alumno, $id_curso, $nombre);
		mysqli_stmt_store_result($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return null;
		}
		$log->LogDebug("[AlumnoGrupoDao::findAlumnosSinGrupo:234] rows=". mysqli_stmt_num_rows($stmt) );
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt)) {
			$bi=new stdClass();
			$bi->id_alumno=$id_alumno;
			$bi->id_curso=$id_curso;
			$bi->id_grupo=null;
			$bi->estatus_curso=null;
			$bi->id_moodle19=null;
			$bi->id_moodle22=null;
			$bi->nombre = $nombre;
			$arrayData[$i++]=$bi;
		}
	
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		return $arrayData;
	}
	function findGrupo(&$dbh, $Id_Curso, $idAlumno){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogDebug("[AlumnoGrupoDao:findGrupo:255]------------>");
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, "SELECT Id_Grupo FROM Alumnos_X_Grupos WHERE Id_Alumno =? AND Id_Curso=?");
		mysqli_stmt_bind_param($stmt, 'ii', $idAlumno, $Id_Curso);
		mysqli_stmt_bind_result($stmt, $Id_Grupo);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[AlumnoGrupoDao:findGrupo:266]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogDebug("[AlumnoGrupoDao:findGrupo:271]registros= $nr");
		if($nr==0){
			$log->LogError("[AlumnoGrupoDao:findGrupo:273]no hay registros");
			return null;
		}
	
	
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		$log->LogDebug("[AlumnoGrupoDao:findGrupo:281] Id_Grupo=".$Id_Grupo);
		$log->LogDebug("[AlumnoGrupoDao:findGrupo:282]------------<");
		return $Id_Grupo ;
	}
}
?>