<?php

require_once 'ConnectDB.php';


class UserDublinDAO extends ConnectDB {

    //TODO - Insert your code here
    function UserDublinDAO() {
	
    }
   
    public static function getAllUser() 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user_dublin";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, UserDublinDAO::pulloutUser($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return FALSE;
	}

	return $ds;
    }
    
    
    public static function getUserWithEventCode($code) 
    {
        $ds = array();
        
	try {
	    if (!ConnectDB::OpenConnection())
		return $ds;

	    $strSQL = "SELECT * from user_dublin where eventcode = '$code'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, UserDAO::pulloutUser($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $ds;
	}

	return $ds;
    }

    public static function getUserWithEmail($email) 
    {
	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user_dublin where email = '$email'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$user = new UserDAO();
		$user = UserDAO::pulloutUser($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $user;
	}

	return $user;
    }

    public static function pulloutUser($row) 
    {
	$userDTO = new UserDTO();

	$userDTO->userid = $row['userid'];
	$userDTO->email = $row['email'];
	$userDTO->fbid = $row['fbid'];
	$userDTO->lastlogin = $row['lastlogin'];
	$userDTO->fullname = $row['name'];
	
	
	$userDTO->name = $row['name'];
	
	$userDTO->isconfirmed = (int) $row['isconfirmed'];
	$userDTO->fbusername = $row['fbusername'];
	$userDTO->code = $row['code'];
	
	$userDTO->rating = $row['rating'];
	
	$userDTO->suspended = $row['suspended'];
        
        $userDTO->recipient_id = $row['recipient_id'];
        $userDTO->customer_id = $row['customer_id'];
        $userDTO->joinssince = $row['joinssince'];
        
        $userDTO->eventcode = $row['eventcode'];
        $userDTO->longitude = $row['longitude'];
        $userDTO->latitude = $row['latitude'];
        $userDTO->location = $row['location'];
        
	return $userDTO;
    }

    public static function addUser(UserDTO $user) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $code = md5($user->fbid);
	    
	    $strSQL = "INSERT INTO `user_dublin`(`email`, `fbemail`, `fbid`, `fbusername`, `name`, `lastlogin`, `code`, `eventcode`,`joinssince`, `longitude`, `latitude`, `location`) VALUES ('$user->email','$user->fbemail','$user->fbid','$user->fbusername','$user->name','$user->lastlogin', '$code', '$eventcode','$user->joinssince','$user->longitude','$user->latitude','$user->location');";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function addUserWithCode(UserDTO $user, $code, $eventcode) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "INSERT INTO `user_dublin`(`email`, `fbemail`, `fbid`, `fbusername`, `name`, `lastlogin`, `code`, `eventcode`,`joinssince`, `longitude`, `latitude`, `location`) VALUES ('$user->email','$user->fbemail','$user->fbid','$user->fbusername','$user->name','$user->lastlogin', '$code', '$eventcode','$user->joinssince','$user->longitude','$user->latitude','$user->location');";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
}

?>