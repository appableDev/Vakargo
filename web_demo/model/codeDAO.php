<?php

require_once 'ConnectDB.php';


class CodeDTO
{
	public $email ;
        public $fbid;
        public $code;
}
/**
 *
 *
 */
class CodeDAO extends ConnectDB {

    //TODO - Insert your code here
    function CodeDAO() {
	
    }

    public static function getCodeWithFbID($fbid) {

	$code = new CodeDTO();

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from registration_code where fbid = '$fbid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$code = CodeDAO::pullout($row);
	    }

	    ConnectDB::CloseConnection();
	}
        catch (Exception $e) 
        {
	    return $code;
	}

	return $code;
    }
    
    public static function getCodeWithEmail($email) {

	$code = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from registration_code where email = '$email'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$code = CodeDAO::pullout($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $code;
	}

	return $code;
    }

    public static function pullout($row) {
	$codeDTO = new CodeDTO();
	$codeDTO->fbid = $row['fbid'];
        $codeDTO->email = $row['email'];
	$codeDTO->code = $row['code'];
	return $codeDTO;
    }

    public static function insertCode($fbid, $code, $email) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "INSERT INTO `registration_code`(`fbid`, `code`, `email`) VALUES ('$fbid', '$code', '$email');";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateEmail($fbid, $email) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "UPDATE `registration_code` set email = '$email' where fbid = '$fbid' ";
	  
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateFBID($fbid, $email) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "UPDATE `registration_code` set fbid = '$fbid' where email = '$email'";
	
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
}

?>