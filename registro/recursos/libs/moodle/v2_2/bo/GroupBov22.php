<?php

if(!class_exists("Configv22")){
	include('recursos/libs/moodle/v2_2/moodle_config22.php');
}
if(!class_exists("GrupoDao")){
	include('recursos/libs/moodle/dao/GrupoDao.php');
}
if(!class_exists("AlumnoGrupoDao")){
	include('recursos/libs/moodle/dao/AlumnoGrupoDao.php');
}
if(!class_exists("GroupDaov22")){
	include('recursos/libs/moodle/v2_2/dao/GroupDaov22.php');
}
if(!class_exists("GroupMemberDaov22")){
	include('recursos/libs/moodle/v2_2/dao/GroupMemberDaov22.php');
}

class GroupBov22{
	function registrar($dbh, $Id_Curso, $Id_Grupo, $name){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov22:18:registrar]--------->");
		
		$dbhm22=Sdx_ConectaBasev22();
		
		//registro del curso
		$log->LogInfo("[GroupBov22:23:registrar]registro del curso");
		
		
		$groupDao = new GroupDaov22();
		$group=$groupDao->save($dbhm22, $Id_Curso, $name );
		if(is_numeric($group)){
			$log->LogError("registrar:31-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		$log->LogInfo("[GroupBov22:29:save]".$group->id);
		
		//guardando el id del curso de moodle
		$log->LogInfo("guardando el id del curso de moodle");
		$grupoDao = new GrupoDao();
		$result=$grupoDao->actualizarGruposId($group->id, $Id_Grupo, MOODLE_V2_2, $dbh);
		if(is_numeric($result)){
			$log->LogError("registrar:38-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		mysqli_commit($dbhm22);
		$log->LogInfo("[GroupBov22:44:registrar]---------<");
		return true;
	}
	
	function modificar(&$dbh, $groupid, $name){
		//consultar el curso para saber si tiene la misma cantidad de secciones
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("modificar-------->");
	
		$dbhm22=Sdx_ConectaBasev22();
	
		$groupDao = new GroupDaov22();
		$group = $groupDao->update($dbhm22, $groupid, $name);
		
		if(is_numeric($group)){
			$log->LogInfo("231:modificar-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
	
		mysqli_commit($dbhm22);
		$log->LogInfo("modificar--------<");
		return true;
	}
	
	function eliminarGrupo($groupid){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov22:eliminarGrupo]-------->");
		
		$dbhm22=Sdx_ConectaBasev22();
	
		//borrar alumnos
		$log->LogInfo("[GroupBov22:eliminarGrupo]borrar secciones");
		$groupMemberDaov22 = new GroupMemberDaov22();
		$obj = new stdClass();
		$obj->groupid = $groupid;
		$result=$groupMemberDaov22->delete($dbhm22, $obj);
		if(!$result){
			$log->LogError("[GroupBov22:eliminar]85-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		$groupDao = new GroupDaov22();
		$result=$groupDao->delete($groupid, $dbhm22);
		if(!$result){
			$log->LogError("[GroupBov22:eliminar]93-ROLLBACK");
			mysqli_rollback($dbhm22);
			return false;
		}
		
		mysqli_commit($dbhm22);
		$log->LogInfo("[GroupBov22:eliminar]--------<");
		return true;
	}
	
	function registrarAlumnos($dbh, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov22:105:registrarAlumno]--------->");
	
		$dbhm22=Sdx_ConectaBasev22();
	
		if( !is_array($alumnos) ){
			$alumnos[]=$alumnos;
		}
		$alumnoGrupoDao = new AlumnoGrupoDao();
		$groupMemberDaov22 = new GroupMemberDaov22();
		for($i=0; $i<count($alumnos); $i++){
			//registro del curso
			$log->LogInfo("[GroupBov22:114:registrarAlumno]registro del alumno");
			
			$groupStudent = $groupMemberDaov22->save($dbhm22, $alumnos[$i]->groupidv22 , $alumnos[$i]->useridv22 );
			$log->LogInfo("[GroupBov22:118:save]".$groupStudent->id);
			if(is_numeric($groupStudent)){
				$log->LogError("registrarAlumno:116-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
			
			$result=$alumnoGrupoDao->actualizarGruposId($groupStudent->id, $alumnos[$i]->id_alumno, $alumnos[$i]->id_grupo, MOODLE_V2_2, $dbh);
			if(is_numeric($result)){
				$log->LogError("registrar:38-ROLLBACK");
				mysqli_rollback($dbhm19);
				return false;
			}
		}
		mysqli_commit($dbhm22);
		$log->LogInfo("[GroupBov22:126:registrar]---------<");
		return true;
	}
	
	function borrarAlumnos($dbh, $alumnos){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[GroupBov22:143:borrarAlumnos]--------->");
	
		$dbhm22=Sdx_ConectaBasev22();
	
		if( !is_array($alumnos) ){
			$alumnos[]=$alumnos;
		}
		for($i=0; $i<count($alumnos); $i++){
			//registro del curso
			$log->LogInfo("[GroupBov22:152:borrarAlumnos]borrado del del alumno");
			$obj = new stdClass();
			$obj->id=$alumnos[$i]->idv22;
			
			$groupMemberDaov22 = new GroupMemberDaov22();
			$groupStudent = $groupMemberDaov22->delete($dbhm22, $obj);
			$log->LogInfo("[GroupBov22:158:borrarAlumnos]".$groupStudent);
			if(is_numeric($groupStudent)){
				$log->LogError("borrarAlumnos:147-ROLLBACK");
				mysqli_rollback($dbhm22);
				return false;
			}
		}
		mysqli_commit($dbhm22);
		$log->LogInfo("[GroupBov22:166:borrarAlumnos]---------<");
		return true;
	}
}
?>