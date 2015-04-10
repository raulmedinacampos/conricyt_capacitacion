<?php
class CourseDaov19{
	function save( &$dbhm19, 
			$fullname, $startdate, 
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency, $numsections, $enrolstartdate,	$enrolenddate){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov19:9:save]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$sortorder=$this->getSortOrder($dbhm19);	
		$format='weeks';
		$groupmode=2;
		$time = time();
		
		$expirynotify=1;
		$notifystudents=1;
		
		//registra al usuario
		$INSERT_COURSE = 'INSERT INTO '.$CFG_v19->prefix.'course'.
							'(category, sortorder, password, fullname,'.
							'shortname, idnumber, summary, format,'.
							'showgrades, modinfo, newsitems, teacher,'.
							'teachers, student, students, guest,'.
							'startdate, enrolperiod, numsections, marker,'.
							'maxbytes, showreports, visible, hiddensections,'.
							'groupmode, groupmodeforce, defaultgroupingid,lang,'.
							'theme, cost, currency,timecreated,'.
							'timemodified, metacourse, requested,restrictmodules,'.
							'expirynotify, expirythreshold, notifystudents,enrollable,'.
							'enrolstartdate, enrolenddate, enrol, defaultrole) '.
							'VALUES (?, ?, ?, ?,'.
							'?, ?, ?, ?,'.
							'1, null, 5, \'Teacher\','.
							'\'Teachers\', \'Student\', \'Students\', 0,'.
							'?, 0, ?, 0,'.
							'8388608, 0, 1, 0,'.
							'?, 0, 0, \'\','.
							'\'\', ?, ?, ?,'.
							'?, 0, 0, 0,'.
							'?, 864000, ?, 1,'.
							'?, ?, \'\', 0)';
		
		$log->LogInfo("[CourseDaov19:50:save]".$INSERT_COURSE);
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_COURSE);
		
		mysqli_stmt_bind_param($stmt, 'iiss'.'ssss'.'iiis'.'siii'.'iii', $category, $sortorder, $password,$fullname, 
								$shortname, $idNumber, $summary, $format,
								$startdate, $numsections, $groupmode, $cost,
								$currency, $time, $time, $expirynotify,
								$notifystudents, $enrolstartdate, $enrolenddate);
		$log->LogInfo("[CourseDaov19:59:save]hasta aqui");
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
	
		$id_curso=mysqli_insert_id($dbhm19);
		$course = new stdClass();
		$course->id = $id_curso;
		$log->LogInfo("[CourseDaov19:70:save]".$course->id."");
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
	
		$log->LogInfo("[CourseDaov19:77:save]---------<");
		return $course;
	}
	
	function getSortOrder(&$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov19:86:getSortOrder]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$SELECT_SORTORDER = 'SELECT MIN(sortorder) AS sortorder FROM '.$CFG_v19->prefix.'course WHERE sortorder>0';
		
		$log->LogInfo("[CourseDaov19:95:getSortOrder]".$SELECT_SORTORDER);
		
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_SORTORDER);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $sortorder);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogInfo("[CourseDaov19:86:save]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[CourseDaov19:113:getSortOrder]".$sortorder);
		
		if(!isset($sortorder)){
			$sortorder=100;
		}else{
			$sortorder-=1;
		}
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseDaov19:124:getSortOrder]---------<");
		return $sortorder;
	}
	
	function find($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov19:121:find]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$course = new stdClass();
		$SELECT_COURSE = 'SELECT id, category, sortorder, password, fullname,'.
							'shortname, idnumber, summary, format,'.
							'numsections, theme, cost, currency '.
							' FROM '.$CFG_v19->prefix.'course '.
							'WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $SELECT_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $course->id, $course->category, $course->sortorder, $course->password, $course->fullname, $course->shortname, 
						$course->idnumber, $course->summary, $course->format, $course->numsections, $course->theme, $course->cost, $course->currency);
		
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("[Contextv19:92:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseDaov19:147:find]---------<");
		return $course;
	}
	
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov19:156:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
	
		$DELETE_COURSE = 'DELETE FROM '.$CFG_v19->prefix.'course WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $DELETE_COURSE);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("[CourseDaov19:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[CourseDaov19:179:delete]---------<");
		return true;
	}
	function update(&$dbhm19, $idCourse, $fullname, $startdate, 
			$category, $shortname, $idNumber, $summary, 
			$password, $cost, $currency, $numsections, $enrolstartdate,	$enrolenddate){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[CourseDaov19:186:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$format='weeks';
		$time = time();
		
// 		//registra al usuario
		$UPDATE_COURSE = 'UPDATE '.$CFG_v19->prefix.'course SET '.
				'fullname=?,'.
				'startdate=?,'.
// 				'category=?,'.
				'shortname=?,'.
				'idnumber=?,'.
				'summary=?,'.
				'password=?,'.
				'cost=?,'.
				'currency=?,'.
				'enrolstartdate=?,'.
				'enrolenddate=?,'.
				'numsections=?,'.
				'format=?,'.
				'timemodified=? '.
				'WHERE id = ?';
		//$log->LogInfo("[CourseDaov19:204:update]UPDATE_COURSE=".$UPDATE_COURSE."");
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $UPDATE_COURSE);
// 		mysqli_stmt_bind_param($stmt, 'siissssssiiisii', $fullname, $startdate, $category, 
// 										 $shortname, $idNumber, $summary,
// 										 $password, $cost, $currency, $enrolstartdate, $enrolenddate, $numsections, 
// 										 $format, $time, $idCourse );
		mysqli_stmt_bind_param($stmt, 'sissssssiiisii', $fullname, $startdate,
		$shortname, $idNumber, $summary,
		$password, $cost, $currency, $enrolstartdate, $enrolenddate, $numsections,
		$format, $time, $idCourse );
		
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		$log->LogInfo("[CourseDaov19:195:update]---------<");
		return true;
	}
	
	function findByCategory(&$dbhm19, $params ){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[ContextDaov19:254:findByCategory]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
			$log->LogInfo("[ContextDaov19:64:find]localConn=".$localConn."");
		}
	
		$params = (array)$params;
		
		$SELECT_CONTEXT = 'SELECT id, category, sortorder, fullname, shortname,'.
				'idnumber, summary, format,startdate,'.
				'numsections FROM '.$CFG_v19->prefix.'course '.
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
	
		$stmt2 = mysqli_stmt_init($dbhm19);
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
	
		$log->LogInfo("[ContextDaov19:329:findByCategory]---------<");
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
}
?>