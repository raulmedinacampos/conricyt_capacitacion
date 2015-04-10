<?php
if(!class_exists("KLo	ger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
if(!class_exists("AlumnoGrupoDao") ){
	require_once 'recursos/libs/moodle/dao/AlumnoGrupoDao.php';
}


class GrupoDao{
	function actualizarGruposId($idMoodle, $id, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoDao:5:actualizarAlumnoId]--------->");

		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE Grupos SET id_moodle19 = ? WHERE  Id_Grupo = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE Grupos SET id_moodle22 = ? WHERE  Id_Grupo = ?');
		}
		mysqli_stmt_bind_param($stmt, 'ii', $idMoodle, $id);
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("registrar-ROLLBACK");
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
		$log->LogInfo("[GrupoDao:22:actualizarAlumnoId]---------<");
	}

	function getMoodleId($Id, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoDao:getMoodleId:35]Consultando los id de moodle");
		$stmt = mysqli_stmt_init($dbh);
		$log->LogInfo("[GrupoDao:getMoodleId]Id_Grupo= $Id");
		mysqli_stmt_prepare($stmt, "SELECT id_moodle19, id_moodle22 FROM Grupos WHERE Id_Grupo=?");
		mysqli_stmt_bind_param($stmt, 'i', $Id);
		mysqli_stmt_bind_result($stmt, $id_moodle19, $id_moodle22);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[modificar:45]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$nr=mysqli_stmt_num_rows($stmt);
		$log->LogInfo("[GrupoDao:getMoodleId]registros= $nr");
		if($nr==0){
			$log->LogInfo("[GrupoDao:getMoodleId]no hay registros");
			return null;
		}
		
		
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		$log->LogInfo("[GrupoDao:61:getMoodleId] Id_Grupo=$Id, v1.9=$id_moodle19, v2.2=$id_moodle22");
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
	
	
	//insertar
	function insert(&$dbh, $idCurso, $nombre, $descripcion=''){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("insert-------->");
		//registra al usuario
		$INSERT_CAT_CURSO = 'INSERT INTO Grupos ('.
				'Id_Curso, Nombre_Grupo,Descripcion) VALUES '.
				'(?,?,?)';
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $INSERT_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'iss', $idCurso, $nombre, $descripcion);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		$id_Grupo=mysqli_insert_id($dbh);
		//$log->LogInfo("[GrupoDao:58:save]".$id_user."");
		mysqli_stmt_close($stmt);
		
		$grupo= new stdClass();
		$grupo->id_grupo = $id_Grupo;
		
		//$log->LogInfo("[GrupoDao:66:insert]---------<");
		return $grupo;
	}
	
	//actualizar
	function update(&$dbh, $idGrupos, $nombre, $descripcion=''){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("insert-------->");
		//registra al usuario
		$UPDATE_CAT_CURSO = 'UPDATE Grupos SET Nombre_Grupo=?, Descripcion=? WHERE id_Grupo=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $UPDATE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'ssi', $nombre, $descripcion,$idGrupos);
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
	function delete(&$dbh,  $idGrupos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoDao:111]delete-------->");
		//registra al usuario
		$DELETE_CAT_CURSO = 'DELETE FROM Grupos  WHERE id_Grupo=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $DELETE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'i', $idGrupos);
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
	function findByCourse(&$dbh, $idCurso, $traerAlumnos=false){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GrupoDao::findByCourse:144]-------->");
		//registra al usuario
		$FIND_CAT_CURSO = 'SELECT Id_Grupo,Id_Curso,Nombre_Grupo,Descripcion,id_moodle19,id_moodle22'.
		' FROM Grupos a WHERE Id_Curso= ? ORDER BY Nombre_Grupo ';
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
		mysqli_stmt_bind_param($stmt, 'i',$idCurso);

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id_grupo, $id_curso ,$nombre_grupo, $descripcion, $id_moodle19, $id_moodle22);
		mysqli_stmt_store_result($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return null;
		}
	
		$alumnoGrupoDao = new AlumnoGrupoDao();
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt)) {
			$bi=new stdClass();
			$bi->id_grupo=$id_grupo;
			$bi->id_curso=$id_curso;
			$bi->nombre_grupo=$nombre_grupo;
			$bi->descripcion=$descripcion;
			$bi->id_moodle19=$id_moodle19;
			$bi->id_moodle22=$id_moodle22;
			if($traerAlumnos){
				$bi->alumnos= $alumnoGrupoDao->findByGroup($dbh, $idCurso, $id_grupo);
			}
			$arrayData[$i++]=$bi;
		}
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		
		if($traerAlumnos){
			$bi=new stdClass();
			$bi->id_grupo=null;
			$bi->id_curso=$id_curso;
			$bi->nombre_grupo='Sin Grupo';
			$bi->descripcion=null;
			$bi->id_moodle19=null;
			$bi->id_moodle22=null;
			$bi->alumnos=$alumnoGrupoDao->findAlumnosSinGrupo($dbh, $idCurso);
			$arrayData[]=$bi;
		}
		$log->LogInfo("[GrupoDao::findByCourse:192]".count($arrayData));
		$log->LogInfo("[GrupoDao::findByCourse:192]--------<");
		return $arrayData;
	}
	
	
	
}
?>