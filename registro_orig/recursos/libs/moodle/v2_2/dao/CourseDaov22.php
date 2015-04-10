<?php
class CourseDaov22{
	function save(&$dbhm22, 
			$fullname, $startdate, 
			$category, $shortname, $idNumber, $summary, $numsections ){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:26:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$sortorder=$this->getSortOrder($dbhm22);	
		$format='weeks';
		$groupmode=2;
		$time = time();
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO  '.$CFG_v22->prefix.'course ('.
				'category, sortorder, fullname, shortname,'.
				'idnumber, summary, summaryformat, format,'.
				'showgrades, modinfo, newsitems, startdate,'.
				'numsections, marker, maxbytes, legacyfiles,'.
				'showreports, visible, visibleold, hiddensections,'. 
				'groupmode, groupmodeforce, defaultgroupingid, lang,'. 
				'theme, timecreated, timemodified, requested, '.
				'restrictmodules, enablecompletion, completionstartonenrol, completionnotify) VALUES '.
				'(?,?,?,?,'.
				'?,?,1,?,'.
				'1,null,5,?,'.
				'?,0, 8388608, 0,'.
				'0, 1, 1, 0,'.
				'?, 0, 0, \'\','.
				'\'\',?,?, 0,'.
				'0, 0, 0, 0)';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		mysqli_stmt_bind_param($stmt, 'iiss'.'sssi'.'iiii', $category, $sortorder, $fullname, $shortname, 
										$idNumber, $summary, $format, $startdate,
										$numsections, $groupmode, $time, $time );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}

		$id_course=mysqli_insert_id($dbhm22);
		$log->LogInfo("[CourseDaov22:58:save]".$id_course."");
		mysqli_stmt_close($stmt);
		$log->LogInfo("[CourseDaov22:58:save]closed statement");
	
		$course = new stdClass();
		$course->id = $id_course;
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
	
		$log->LogInfo("[CourseDaov22:66:save]---------<");
		return $course;
	}
	
	function getSortOrder(&$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:86:getSortOrder]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_SORTORDER = 'SELECT MIN(sortorder) AS sortorder FROM '.$CFG_v22->prefix.'course WHERE sortorder>1';
		
		$log->LogInfo("[CourseDaov22:95:getSortOrder]".$SELECT_SORTORDER);
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_SORTORDER);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $sortorder);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CourseDaov22:86:save]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[CourseDaov22:113:getSortOrder]".$sortorder);
		
		if(!isset($sortorder)){
			$sortorder=10000;
		}else{
			$sortorder-=1;
		}
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[CourseDaov22:124:getSortOrder]---------<");
		return $sortorder;
	}
	
	
	function delete($idCourse, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:118:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v22->prefix.'course WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $idCourse );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[CourseDaov22:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogError("[CourseDaov22:179:delete]---------<");
		return true;
	}
	
	
	
	function update(&$dbhm22, $idCourse, $fullname, $startdate, $category, $shortname, $idNumber, $summary, $numsections){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:186:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$time = time();
		$format='weeks';
	
// 		//registra al usuario
		$UPDATE_COURSE = 'UPDATE '.$CFG_v22->prefix.'course SET '.
				'fullname=?,'.
				'startdate=?,'.
// 				'category=?,'.
				'shortname=?,'.
				'idnumber=?,'.
				'summary=?,'.
				'numsections=?,'.
				'format=?,'.
				'timemodified=? '.
				'WHERE id = ?';
		$log->LogInfo("[CourseDaov22:174:update]UPDATE_COURSE=".$UPDATE_COURSE."");
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $UPDATE_COURSE);
// 		mysqli_stmt_bind_param($stmt, 'siisssisii', $fullname, $startdate, $category, $shortname, $idNumber, $summary, $numsections,$format,$time, $idCourse);
		mysqli_stmt_bind_param($stmt, 'sisssisii', $fullname, $startdate, $shortname, $idNumber, $summary, $numsections,$format,$time, $idCourse);
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		$log->LogInfo("[CourseDaov22:225:update]---------<");
		return true;
	}
	
	function findByCategory(&$dbhm22, $params ){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[ContextDaov22:195:findByCategory]--------->");

		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
			$log->LogInfo("[ContextDaov22:64:find]localConn=".$localConn."");
		}
		$params = (array)$params;
		$SELECT_CONTEXT = 'SELECT id, category, sortorder, fullname, shortname,'.
				'idnumber, summary, format,startdate,'.
				'numsections FROM '.$CFG_v22->prefix.'course '.
				'WHERE ';
		
		$where=array();
		$types=array();
		$values=array();

		foreach ($params as $field=>$value) {
			$where[] = "$field = ?";
			$types[] = (is_numeric($value)?'i':'s');
		}

		$where=implode(' AND ', $where);
		$types=implode('', $types);
		
		$SELECT_CONTEXT.=$where;
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_CONTEXT);
		
		call_user_func_array('mysqli_stmt_bind_param', array_merge (array($stmt2, $types), $this->refValues($params)));
		
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $id, $category, $sortorder, $fullname, $shortname,$idnumber, $summary, $format,$startdate,$numsections);
		mysqli_stmt_store_result($stmt2);
	
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return null;
		}
	
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt2)) {
			$bi=new stdClass();
			$bi->id=$id;
			$bi->category=$category;
			$bi->sortorder=$sortorder;
			$bi->fullname=$fullname;
			$bi->shortname=$shortname;
			$bi->idnumber=$idnumber;
			$bi->summary=$summary;
			$bi->format=$format;
			$bi->startdate=$startdate;
			$bi->numsections=$numsections;
				
			$arrayData[$i++]=$bi;
		}
	
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
	
		$log->LogInfo("[ContextDaov22:259:findByCategory]---------<");
		return $arrayData;
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
	
	function getByShortname($shortname, &$dbhm22) {
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov22:278:getByShortname]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_COURSE = 'SELECT id FROM '.$CFG_v22->prefix.'course WHERE shortname=?';
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_COURSE);		
		mysqli_stmt_bind_param($stmt2, 's', $shortname);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $idCourse);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_close($stmt2);
		
		return $idCourse;
	}
	
}
?>