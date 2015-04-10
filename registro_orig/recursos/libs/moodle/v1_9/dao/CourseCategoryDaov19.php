<?php
class CourseCategoryDaov19{
	function save( &$dbhm19, 
			$name, $parent, $description){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryDaov19:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		
		//registra al usuario
		$INSERT_COURSE_CAT = 'INSERT INTO '.$CFG_v19->prefix.'course_categories'.
							'(name,description,parent,sortorder,coursecount,visible,timemodified,depth,path,theme) '.
							'VALUES (?,?,?,999,0,1,0,0,\'\',null)';
		
		$log->LogInfo("[CourseCategoryDaov19:50:save]".$INSERT_COURSE_CAT);
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE_CAT);
		
		mysqli_stmt_bind_param($stmt, 'ssi',$name, $description, $parent);
		$log->LogInfo("[CourseCategoryDaov19:59:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso_cat=mysqli_insert_id($dbhm19);
		$courseCategory = new stdClass();
		$courseCategory->id = $id_curso_cat;
		$log->LogInfo("[CourseCategoryDaov19:70:save]".$courseCategory->id."");
		mysqli_stmt_close($stmt);
		
		//actualizar el path
		$path='';
		$depth=0;
		if($parent>0){
			$parent=$this->findById($dbhm19, $parent);
			$path=$parent->path;
			$depth=substr_count($path,'/')+1;
		}else{
			$depth=1;
		}
		$path.='/'.$courseCategory->id;
		$courseCategory->depth=$depth;
		$courseCategory->path=$path;
		$this->update($dbhm22, $courseCategory);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[CourseCategoryDaov19:77:save]---------<");
		return $courseCategory;
	}
	
	function findById(&$dbhm19, $id){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryDaov19:66:findById]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$SELECT_COURSE_CAT = 'SELECT id,name,description,parent,sortorder,coursecount,'.
								'visible,timemodified,depth,path,theme '.
								'FROM '.$CFG_v19->prefix.'course_categories WHERE id=?';
		
		$log->LogInfo("[CourseCategoryDaov19:77:findById]".$SELECT_COURSE_CAT);
		
		$courseCategory = new stdClass();
		
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_COURSE_CAT);
		mysqli_stmt_bind_param($stmt2, 'i', $id);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $courseCategory->id,$courseCategory->name,$courseCategory->description,
								$courseCategory->parent,$courseCategory->sortorder,$courseCategory->coursecount,
								$courseCategory->visible,$courseCategory->timemodified,$courseCategory->depth,
								$courseCategory->path,$courseCategory->theme);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CourseCategoryDaov19:94:findById]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseCategoryDaov19:105:findById]---------<");
		return $courseCategory;
	}
	
	function updateCourseCount(&$dbhm19, $id, $add){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryDaov19:112:updateCourseCount]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$courseCategory=$this->findById($dbhm19, $id);
		
		$courseCatTemp = new stdClass();
		$courseCatTemp->id=$courseCategory->id;
		if($add){
			$courseCatTemp->coursecount=$courseCategory->coursecount+1;
		}else{
			$courseCatTemp->coursecount=$courseCategory->coursecount-1;
		}
		$this->update($dbhm19, $courseCatTemp);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseCategoryDaov19:133:updateCourseCount]---------<");
		return $courseCategory;
	}
	
	
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryDaov19:156:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v19->prefix.'course_categories WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("[CourseCategoryDaov19:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseCategoryDaov19:179:delete]---------<");
		return true;
	}
	function update(&$dbhm19, $params){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseCategoryDaov19:186:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$params = (array)$params;
		$id = $params['id'];
		unset($params['id']);

		$sets = array();
		$types = array();
		foreach ($params as $field=>$value) {
			$sets[] = "$field=?";
			$types[] = (is_numeric($value)?'i':'s');
		}
		
		$params[] = $id; // last ? in WHERE condition
		$types[] ='i';

		$types = implode('', $types);
		$sets = implode(', ', $sets);
		
// 		//registra al usuario
		$UPDATE_COURSE = 'UPDATE '.$CFG_v19->prefix.'course_categories SET '.$sets.' WHERE id = ?';
		
		$log->LogInfo("[CourseCategoryDaov19:182:update]UPDATE_COURSE=".$UPDATE_COURSE."");
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $UPDATE_COURSE);
			
		call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt, $types), $this->refValues($params)));
		
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		$log->LogInfo("[CourseCategoryDaov19:199:update]---------<");
		return true;
	}
	function refValues($arr)
	{
		if (strnatcmp(phpversion(),'5.3') >= 0) //Reference is required for PHP 5.3+
		{
			$refs = array();
			foreach($arr as $key => $value)
				$refs[$key] = &$arr[$key];
			return $refs;
		}
		return $arr;
	}
}
?>