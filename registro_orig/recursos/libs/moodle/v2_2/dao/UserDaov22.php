<?php

if(!class_exists("KLogger")){
	require_once 'recursos/libs/KLogger/KLogger.php';
}
class UserDaov22 {
	
	
	/**
	 * Guarda un usuario en moodle v2.2
	 * @param unknown_type $id
	 * @param unknown_type $username
	 * @param unknown_type $password
	 * @param unknown_type $firstname
	 * @param unknown_type $surname
	 * @param unknown_type $email
	 * @param unknown_type $city
	 * @param unknown_type $country
	 * @return unknown
	 */
	function save( $username, $password, $firstname, $surname, $email, $city, $country, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv22:24:save]--------->");
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		//registra al usuario
		/*$INSERT_USER = 'INSERT INTO  '.$CFG_v22->prefix.'user ('.
		'auth, confirmed, mnethostid, username,password,'.
		'firstname,lastname,email,emailstop,city,'.
		'country, lang, timezone, firstaccess, lastaccess, '.
		'lastlogin, currentlogin, descriptionformat, mailformat, maildigest, maildisplay, '.
		'htmleditor, ajax, autosubscribe, trackforums, timecreated, timemodified, trustbitmask) VALUES '.
		'(\'manual\', 1, 1, ?,?,'.
		'?,?,?,0,?,'.
		'?, \'en\', 99, 0, 0,'.
		'0,0,1,1,0,2,'.
		'1,0,1,0,?,?,0)';*/
		$INSERT_USER = 'INSERT INTO  '.$CFG_v22->prefix.'user ('.
		'auth, confirmed, mnethostid, username,password,'.
		'firstname,lastname,email,emailstop,city,'.
		'country, lang, timezone, firstaccess, lastaccess, '.
		'lastlogin, currentlogin, descriptionformat, mailformat, maildigest, maildisplay, '.
		'htmleditor, autosubscribe, trackforums, timecreated, timemodified, trustbitmask) VALUES '.
		'(\'manual\', 1, 1, ?,?,'.
		'?,?,?,0,?,'.
		'?, \'es_mx\', 99, 0, 0,'.
		'0,0,1,1,0,2,'.
		'1,1,0,?,?,0)';
		
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $INSERT_USER);
		
		$country = $this->getCountry( $country );
		//$log->LogInfo("[Userv22:42:save]".$country."");
		$password = hash_internal_user_passwordv22($password);
		//$log->LogInfo("[Userv22:44:save]".$password."");
		$time = time();
		mysqli_stmt_bind_param($stmt, 'sssssssii', $username, $password, utf8_decode($firstname), utf8_decode($surname), $email, $city, $country, $time, $time);
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		
		$id_user=mysqli_insert_id($dbhm22);
		//$log->LogInfo("[Userv22:57:save]".$id_user."");
		mysqli_stmt_close($stmt);
// 		//$log->LogInfo("[Userv22:58:save]closed statement");
		
		$user = new stdClass();
// 		$user->context = $c;
		$user->id = $id_user;
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		
		//$log->LogInfo("[Userv22:66:save]---------<");
		return $user;
	}
	
	function getCountry( $country ){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
// 		//$log->LogInfo("[Userv22:73:getCountry]--------->");
		////$log->LogInfo("[Userv22:76:getCountry]".$country."");
		if(is_numeric($country)){
			$dbh=Sdx_ConectaBase();
			$stmt2 = mysqli_stmt_init($dbh);
			mysqli_stmt_prepare($stmt2, 'SELECT country_code FROM cat_paises WHERE id_pais = ?');
			mysqli_stmt_bind_param($stmt2, 'i', $country);
			mysqli_stmt_execute($stmt2);
			mysqli_stmt_bind_result($stmt2, $country);
			mysqli_stmt_store_result($stmt2);
			$nr=mysqli_stmt_num_rows($stmt2);
			$errorNum=mysqli_stmt_errno($stmt2);
			if($errorNum){
				//$log->LogInfo("[Userv22:103:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
				return "MX";
			}
			mysqli_stmt_fetch($stmt2);
			mysqli_stmt_free_result($stmt2);
			mysqli_stmt_close($stmt2);
		}
		
		if(!isset($country)){
			$country='MX';
		}
		////$log->LogInfo("[Userv22:90:getCountry]".$country."");
// 		//$log->LogInfo("[Userv22:91:getCountry]---------<");
		return $country;
	}
	
	function find($id, &$dbhm22){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		
		$SELECT_USER = 'SELECT id, username, firstname, lastname, email, city, country FROM '.$CFG_v22->prefix.'user '.
				'WHERE id=? ';
		
		$stmt = mysqli_stmt_init($dbhm22);
		
		mysqli_stmt_prepare($stmt, $SELECT_USER);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_bind_result($stmt, $user->id, $user->username, $user->firstname, $user->lastname, $user->email, $user->city , $user->country);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		$user = new stdClass();
		mysqli_stmt_store_result($stmt);
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		return $user;
	}
	function delete($id, &$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv22:156:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		$DELETE_USER = 'DELETE FROM '.$CFG_v22->prefix.'user WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $DELETE_USER);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("[Contextv22:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm22);
		}
		//$log->LogInfo("[Userv22:179:delete]---------<");
		return true;
	}
	function update(  $password, $firstname, $surname, $email, $city, $country, $idUser,&$dbhm22){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv22:177:update]--------->");
	
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
	
		//registra al usuario
		$UPDATE_USER = 'UPDATE '.$CFG_v22->prefix.'user SET '.
				'password=?,'.
				'firstname=?,'.
				'lastname=?,'.
				'email=?,'.
				'city=?,'.
				'country=?,'.
				'timemodified=? '.
				'WHERE id = ?';
	
		$stmt = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt, $UPDATE_USER);
	
		$country = $this->getCountry( $country );
		////$log->LogInfo("[Userv22:45:save]".$country."");
		$password = hash_internal_user_passwordv22($password);
		////$log->LogInfo("[Userv22:49:save]".$password."");
		$time = time();
		mysqli_stmt_bind_param($stmt, 'ssssssii', $password, $firstname, $surname, $email, $city, $country, $time, $idUser );
		mysqli_stmt_execute($stmt);
	
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogInfo("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
	
		mysqli_stmt_close($stmt);
	
		//$log->LogInfo("[Userv22:216:update]---------<");
		return true;
	}
	
	
	function getLatesUsers(&$dbhm22, $ids){
		global $CFG_v22;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserDaov22:223:getLatesUsers]--------->");
		
		$localConn = false;
		if(!isset($dbhm22)){
			$dbhm22=Sdx_ConectaBasev22();
			$localConn = true;
		}
		$SELECT_ENROL = 'SELECT DISTINCT a.id, a.username, a.password, a.firstname, a.lastname, a.email, a.country, a.city '.
						'FROM '.$CFG_v22->prefix.'user a,'.$CFG_v22->prefix.'role_assignments c '.
						'WHERE a.id = c.userid AND c.roleid = 5 AND a.id NOT IN ('.$ids.')';
		
		$log->LogInfo("[UserDaov22:234:getLatesUsers]".$SELECT_ENROL);
		
		$stmt2 = mysqli_stmt_init($dbhm22);
		mysqli_stmt_prepare($stmt2, $SELECT_ENROL);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $id, $username, $password, $firstname, $lastname, $email, $country, $city);
		
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		$log->LogInfo("[UserDaov22:237:findByCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[UserDaov22:240:findByCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return $errorNum;
		}
		$arrayData = array();
		$i=0;
		while (mysqli_stmt_fetch($stmt2)) {
			$bi=new stdClass();
			$bi->id=$id;
			$bi->username=$username;
			$bi->password=$password;
			$bi->firstname=$firstname;
			$bi->lastname=$lastname;
			$bi->email=$email;
			$bi->country= $this->getCountryId( $country);
			$bi->city=$city;
				
			$arrayData[$i++]=$bi;
		}
		
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		$log->LogInfo("[UserDaov22:262:findByCourse]".count($arrayData));
		
		if($localConn ){
			mysqli_close($dbhm22);
		}
		$log->LogInfo("[UserDaov22:267:findByCourse]---------<");
		return $arrayData;
	}
	
	function getCountryId( $country ){
		
		$dbh=Sdx_ConectaBase();
		$stmt2 = mysqli_stmt_init($dbh);
		mysqli_stmt_prepare($stmt2, 'SELECT Id_Pais FROM Cat_Paises WHERE country_code = ?');
		mysqli_stmt_bind_param($stmt2, 's', $country);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $country);
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum || $nr==0){
			//$log->LogError("[Userv22:104:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
			return 156;
		}
		mysqli_stmt_fetch($stmt2);
		mysqli_stmt_free_result($stmt2);
		mysqli_stmt_close($stmt2);
		
		if(!isset($country)){
			$country=156;
		}
		
		return $country;
	}
}