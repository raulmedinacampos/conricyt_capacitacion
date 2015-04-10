<?php
if(!class_exists("KLogger") ){
	require_once 'recursos/libs/KLogger/KLogger.php';
}

class CatCursoDao{
	function actualizarCatCursoId($idMoodle, $id, $moodle, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CatCursoDao:5:actualizarAlumnoId]--------->");

		$stmt = mysqli_stmt_init($dbh);
		if($moodle==MOODLE_V1_9){
			mysqli_stmt_prepare($stmt, 'UPDATE cat_cursos SET id_moodle19 = ? WHERE  Id_CatCurso = ?');
		} else if($moodle==MOODLE_V2_2){
			mysqli_stmt_prepare($stmt, 'UPDATE cat_cursos SET id_moodle22 = ? WHERE  Id_CatCurso = ?');
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
		$log->LogInfo("[CatCursoDao:22:actualizarAlumnoId]---------<");
	}

	function getMoodleId($Id, &$dbh){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[modificar:31]Consultando los id de moodle");
		$stmt = mysqli_stmt_init($dbh);
		$log->LogInfo("[CatCursoDao:getMoodleId]Id_CatCurso= $Id");
		mysqli_stmt_prepare($stmt, "SELECT id_moodle19, id_moodle22 FROM cat_cursos WHERE Id_CatCurso=?");
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
		$log->LogInfo("[CatCursoDao:getMoodleId]registros= $nr");
		if($nr==0){
			$log->LogInfo("[CatCursoDao:getMoodleId]no hay registros");
			return null;
		}
		
		
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		$log->LogInfo("[CatCursoDao:getMoodleId] Id_CatCurso=$Id, v1.9=$id_moodle19, v2.2=$id_moodle22");
		return array( 'id_moodle19'=>$id_moodle19,'id_moodle22'=>$id_moodle22 );
	}
	
	
	//insertar
	function insert(&$dbh, $nombre, $idPadre, $descripcion){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("insert-------->");
		//registra al usuario
		$INSERT_CAT_CURSO = 'INSERT INTO cat_cursos ('.
				'Nombre, Descripcion, Id_Padre) VALUES '.
				'(?,?,?)';
		
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $INSERT_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'ssi', $nombre, $descripcion, $idPadre);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		
		$id_catCurso=mysqli_insert_id($dbh);
		//$log->LogInfo("[CatCursoDao:58:save]".$id_user."");
		mysqli_stmt_close($stmt);
		// 		//$log->LogInfo("[CatCursoDao:58:save]closed statement");
		
		
		$catCurso= new stdClass();
		$catCurso->id = $id_catCurso;
		
		//$log->LogInfo("[CatCursoDao:66:insert]---------<");
		return $catCurso;
	}
	
	//actualizar
	function update(&$dbh, $nombre, $descripcion, $idPadre, $idCatCurso){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("insert-------->");
		//registra al usuario
		$UPDATE_CAT_CURSO = 'UPDATE cat_cursos SET '.
				'Nombre=?, Descripcion=?, Id_Padre=? WHERE id_catcurso=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $UPDATE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'ssii', $nombre, $descripcion, $idPadre, $idCatCurso);
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
	function delete(&$dbh,  $idCatCurso){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CatCursoDao:111]delete-------->");
		//registra al usuario
		$DELETE_CAT_CURSO = 'DELETE FROM cat_cursos  WHERE id_catcurso=? ';
	
		$stmt = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt, $DELETE_CAT_CURSO);
	
		mysqli_stmt_bind_param($stmt, 'i', $idCatCurso);
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
	function find(&$dbh, $nombre, $idPadre=null){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("find-------->");
		//registra al usuario
		$FIND_CAT_CURSO = 'SELECT id_catCurso, Nombre, Descripcion, Id_Padre , '.
		'(SELECT Nombre FROM cat_cursos WHERE id_catCurso=a.Id_Padre) AS nombrePadre'.
		' FROM cat_cursos a WHERE nombre LIKE ? ';
		
		$nombre='%'.$nombre.'%';
	
		$stmt = mysqli_stmt_init($dbh);
		if(isset($idPadre)&& is_numeric($idPadre)){
			$FIND_CAT_CURSO.=' AND id_padre=? ORDER BY Id_Padre,Nombre ';
			mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
			mysqli_stmt_bind_param($stmt, 'si', $nombre, $idPadre);
// 			$log->LogInfo("[CatCursoDao.find:149]FIND_CAT_CURSO=$FIND_CAT_CURSO");
		}else{
			$FIND_CAT_CURSO.=' ORDER BY Id_Padre,Nombre ';
// 			$log->LogInfo("[CatCursoDao.find:151]FIND_CAT_CURSO=$FIND_CAT_CURSO");
			mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
			mysqli_stmt_bind_param($stmt, 's', $nombre);
		}
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $name, $description, $idPadre, $nombrePadre);
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
			$bi->id=$id;
			$bi->nombre=$name;
			$bi->descripcion=$description;
			$bi->idPadre=$idPadre;
			$bi->nombrePadre=$nombrePadre;
				
			$arrayData[$i++]=$bi;
		}
	
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	
	
		return $arrayData;
	}
	
	//consultar usando filtros
	function findById(&$dbh, $Id){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("insert-------->");
		//registra al usuario
		$FIND_CAT_CURSO = 'SELECT id_catCurso, Nombre, Descripcion, Id_Padre FROM cat_cursos WHERE Id_CatCurso = ?  ';	

		$stmt = mysqli_stmt_init($dbh);
		
		mysqli_stmt_prepare($stmt, $FIND_CAT_CURSO);
		mysqli_stmt_bind_param($stmt, 'i', $Id);
		
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $id, $name, $description, $idPadre);
		mysqli_stmt_store_result($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return null;
		}
	
	
		mysqli_stmt_fetch($stmt);
		$bi=new stdClass();
		$bi->id=$id;
		$bi->nombre=$name;
		$bi->descripcion=$description;
		$bi->idPadre=$idPadre;
	
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	
		return $bi;
	}
}
?>