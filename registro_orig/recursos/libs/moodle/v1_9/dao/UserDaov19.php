<?php



class UserDaov19 {
	
	
	/**
	 * Guarda un usuario en moodle v1.9
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
	function save( $username, $password, $firstname, $surname, $email, $city, $country, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv19:26:save]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		//registra al usuario
		$INSERT_USER = 'INSERT INTO  '.$CFG_v19->prefix.'user ('.
		'auth, confirmed, mnethostid, username,password,'.
		'firstname,lastname,email,emailstop,city,'.
		'country, lang, timezone, firstaccess, lastaccess, '.
		'lastlogin, currentlogin, mailformat, maildigest, maildisplay, '.
		'htmleditor, ajax, autosubscribe, trackforums, timemodified, trustbitmask) VALUES '.
		'(\'manual\', 1, 1, ?,?,'.
		'?,?,?,0,?,'.
		'?, \'en_utf8\', 99, 0, 0,'.
		'0,0,1,0,2,'.
		'1,0,1,0,?,0)';
		
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $INSERT_USER);
		
		$country = $this->getCountry( $country );
		////$log->LogInfo("[Userv19:45:save]".$country."");
		$password = hash_internal_user_passwordv19($password);
		////$log->LogInfo("[Userv19:49:save]".$password."");
		$time = time();
		mysqli_stmt_bind_param($stmt, 'sssssssi', $username, $password, $firstname, $surname, $email, $city, $country, $time );
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		
		
		$id_user=mysqli_insert_id($dbhm19);
		//$log->LogInfo("[Userv19:58:save]".$id_user."");
		mysqli_stmt_close($stmt);
// 		//$log->LogInfo("[Userv19:58:save]closed statement");
		
		
		$user = new stdClass();
		$user->id = $id_user;
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		
		//$log->LogInfo("[Userv19:66:save]---------<");
		return $user;
	}
	
	function getCountry( $country ){
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
// 		//$log->LogInfo("[Userv19:73:getCountry]--------->");
		////$log->LogInfo("[Userv19:76:getCountry]".$country."");
		if(is_numeric($country)){
			$dbh=Sdx_ConectaBase();
			$stmt2 = mysqli_stmt_init($dbh);
			mysqli_stmt_prepare($stmt2, 'SELECT country_code FROM Cat_Paises WHERE Id_Pais = ?');
			mysqli_stmt_bind_param($stmt2, 'i', $country);
			mysqli_stmt_execute($stmt2);
			mysqli_stmt_bind_result($stmt2, $country);
			mysqli_stmt_store_result($stmt2);
			$nr=mysqli_stmt_num_rows($stmt2);
			$errorNum=mysqli_stmt_errno($stmt2);
			if($errorNum){
				//$log->LogError("[Userv19:104:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
				return "MX";
			}
			mysqli_stmt_fetch($stmt2);
			mysqli_stmt_free_result($stmt2);
			mysqli_stmt_close($stmt2);
		}
		
		if(!isset($country)){
			$country='MX';
		}
		////$log->LogInfo("[Userv19:90:getCountry]".$country."");
// 		//$log->LogInfo("[Userv19:91:getCountry]---------<");
		return $country;
	}
	
	function find($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv19:121:find]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$user = new stdClass();
		$SELECT_USER = 'SELECT id, username, firstname, lastname, email, city, country FROM '.$CFG_v19->prefix.'user '.
				'WHERE id=? ';
		
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $SELECT_USER);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_bind_result($stmt, $user->id, $user->username, $user->firstname, $user->lastname, $user->email, $user->city , $user->country);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$nr=mysqli_stmt_num_rows($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogError("[Contextv19:92:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return $errorNum;
		}
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_free_result($stmt);
		mysqli_stmt_close($stmt);
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[Userv19:147:find]---------<");
		return $user;
	}
	
	function delete($id, &$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv19:156:delete]--------->");
	
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$DELETE_USER = 'DELETE FROM '.$CFG_v19->prefix.'user WHERE id=? ';
	
		$stmt = mysqli_stmt_init($dbhm19);	
		mysqli_stmt_prepare($stmt, $DELETE_USER);
		mysqli_stmt_bind_param($stmt, 'i', $id );
		mysqli_stmt_execute($stmt);
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogError("[Userv19:171:delete]Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		mysqli_stmt_close($stmt);
	
		if($localConn ){
			mysqli_close($dbhm19);
		}
		//$log->LogInfo("[Userv19:179:delete]---------<");
		return true;
	}
	function update( $password, $firstname, $surname, $email, $city, $country, $idUser,&$dbhm19){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		//$log->LogInfo("[Userv19:186:update]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		
		$country = $this->getCountry( $country );
		$password = hash_internal_user_passwordv19($password);
		$time = time();
		
		//registra al usuario
		$UPDATE_USER = 'UPDATE '.$CFG_v19->prefix.'user SET '.
		'password=?,'.
		'firstname=?,'.
		'lastname=?,'.
		'email=?,'.
		'city=?,'.
		'country=?,'.
		'timemodified=? '.
		'WHERE id = ?';
		//$log->LogInfo("[Userv19:204:update]UPDATE_USER=".$UPDATE_USER."");
		$stmt = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt, $UPDATE_USER);	
		mysqli_stmt_bind_param($stmt, 'ssssssii', $password, $firstname, $surname, $email, $city, $country, $time, $idUser );
		mysqli_stmt_execute($stmt);
		
		$errorNum=mysqli_stmt_errno($stmt);
		if($errorNum){
			//$log->LogError("Error ".$errorNum.": ".mysqli_stmt_error($stmt).".");
			return false;
		}
		
		mysqli_stmt_close($stmt);
		
		//$log->LogInfo("[Userv19:195:update]---------<");
		return true;
	}
	
	function getLatesUsers(&$dbhm19, $ids){
		global $CFG_v19;
		$log = new KLogger ( LOGGER_FILE , LOGGER_LEVEL );
		$log->LogInfo("[UserDaov19:223:getLatesUsers]--------->");
		
		$localConn = false;
		if(!isset($dbhm19)){
			$dbhm19=Sdx_ConectaBasev19();
			$localConn = true;
		}
		$SELECT_ENROL = 'SELECT DISTINCT a.id, a.username, a.password, a.firstname, a.lastname, a.email, a.country, a.city '.
						'FROM '.$CFG_v19->prefix.'user a,'.$CFG_v19->prefix.'role_assignments c '.
						'WHERE a.id = c.userid AND c.roleid = 5 AND a.id NOT IN ('.$ids.')';
		
		$log->LogInfo("[UserDaov19:234:getLatesUsers]".$SELECT_ENROL);
		
		$stmt2 = mysqli_stmt_init($dbhm19);
		mysqli_stmt_prepare($stmt2, $SELECT_ENROL);
		mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $id, $username, $password, $firstname, $lastname, $email, $country, $city);
		
		mysqli_stmt_store_result($stmt2);
		$nr=mysqli_stmt_num_rows($stmt2);
		$log->LogInfo("[EnrolDaov19:72:findByCourse]numero de registros=".$nr);
		$errorNum=mysqli_stmt_errno($stmt2);
		if($errorNum){
			$log->LogError("[EnrolDaov19:72:findByCourse]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
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
		
		$log->LogInfo("[EnrolDaov19:92:findByCourse]".count($arrayData));
		
		if($localConn ){
			mysqli_close($dbhm19);
		}
		$log->LogInfo("[EnrolDaov19:111:findByCourse]---------<");
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
			//$log->LogError("[Userv19:104:find]Error ".$errorNum.": ".mysqli_stmt_error($stmt2).".");
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
?>