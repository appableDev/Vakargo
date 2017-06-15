<?php

require_once 'ConnectDB.php';



class UserDTO
{
    public $userid ;
    public $name;
    public $email;

    public $fbemail;
	
    public $fbid;
    public $facebookid;
    public $lastlogin;
    public $fbusername;
    
    public $isconfirmed;
    public $rating;
    public $code;
    public $suspended;

    public $recipient_id;
    public $eventcode;
    public $customer_id;
    public $joinssince;
    public $fullname;
    
    public $longitude;
    public $latitude;
    public $location;
    public $epass;
}
/**
 *
 *
 */
class UserDAO extends ConnectDB {

    //TODO - Insert your code here
    function UserDAO() {
	
    }

    public static function isConfirmedUser($parameter, $isEmail = 0) {
	try {
	    if (!ConnectDB::OpenConnection())
		return -1;

	    $strSQL = "SELECT * from user where userid = '$parameter'";
            
            if($isEmail == 1)
                $strSQL = "SELECT * from user where email = '$parameter'";
	   
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return -1;
	    }
	    
	    $row = mysql_fetch_array($result);

	    $userDTO = UserDAO::pulloutUser($row);

	    ConnectDB::CloseConnection();

	    return $userDTO->isconfirmed;
	    
	} catch (Exception $e) {
	    return -1;
	}
    }

    public static function getAllUser() {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, UserDAO::pulloutUser($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return FALSE;
	}

	return $ds;
    }

    public static function getUserWithFbID($fbid) {

	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user where facebookid = '$fbid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$user = UserDAO::pulloutUser($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $user;
	}

	return $user;
    }
    
    public static function getUserWithUserid($userid) {

		$user = null;

		try {
		    if (!ConnectDB::OpenConnection())
				return $user;
	
		    $strSQL = "SELECT * from user where userid = '$userid'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
				return $user;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
				$user = UserDAO::pulloutUser($row);
				return $user;
		    }
			print_r($user);
		    ConnectDB::CloseConnection();
		} catch (Exception $e) {
		    return $user;
		}

		return $user;
    }

    public static function getUserWithFbUsername($fbusername) 
    {
	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user where fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$user = UserDAO::pulloutUser($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $user;
	}

	return $user;
    }
    
    
    public static function getUserWithEventCode($code) 
    {
        $ds = array();
        
	try {
	    if (!ConnectDB::OpenConnection())
		return $ds;

	    $strSQL = "SELECT * from user where eventcode = '$code'";
            
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
    
    public static function getUserWithFBEmail($email) 
    {
	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user where fbemail = '$email'";
	    
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

    public static function getUserWithEmail($email) 
    {
	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return $user;

	    $strSQL = "SELECT * from user where email = '$email'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $user;
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
    
    public static function getUserWithBetaCode($code) 
    {
	$user = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return $user;

	    $strSQL = "SELECT * from user where betacode = '$code'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) 
            {
		return $user;
	    }

	    while ($row = mysql_fetch_array($result)) 
            {
		$user = new UserDAO();
		$user = UserDAO::pulloutUser($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $user;
	}

	return $user;
    }
    
    public static function updateBetaCode($old, $new) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set betacode = '$new' where betacode = '$old'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

    public static function pulloutUser($row) 
    {
	$userDTO = new UserDTO();

	$userDTO->userid = $row['userid'];
	$userDTO->email = $row['email'];
        
	$userDTO->fbid = $row['facebookid'];
	$userDTO->fbusername = $row['facebookid'];
        $userDTO->fbemail = $row['fbemail'];
        
	$userDTO->lastlogin = $row['lastlogin'];
	$userDTO->fullname = $row['name'];
	
	//if(strlen ($userDTO->name) > 20)
	//$userDTO->name = substr($userDTO->name, 0, 17) . "...";
	
	$userDTO->name = $row['name']; //Globals::CutString($row['name'],20);
	
	$userDTO->isconfirmed = (int) $row['isconfirmed'];
	$userDTO->code = $row['code'];
	$userDTO->epass = $row['epass'];
	$userDTO->rating = $row['rating'];
	
	$userDTO->suspended = $row['suspended'];
        
        $userDTO->recipient_id = $row['recipient_id'];
        $userDTO->customer_id = $row['customer_id'];
        $userDTO->joinssince = $row['joinssince'];
        
        $userDTO->eventcode = $row['eventcode'];
        $userDTO->longitude = $row['longitude'];
        $userDTO->latitude = $row['latitude'];
        $userDTO->location = $row['location'];
        
        $userDTO->type = $row['type'];
        
	return $userDTO;
    }

    public static function addUser(UserDTO $user) 
    {
	$result = FALSE;

	try 
        {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $code = md5($user->fbid);
	    
	    $strSQL = "INSERT INTO `user`(`email`, `fbemail`, `fbid`, `fbusername`, `facebookid`, `name`, `lastlogin`, `code`,`joinssince`) VALUES ('$user->email','$user->fbemail','','','$user->fbid','$user->name','$user->lastlogin', '$code','$user->joinssince');";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            
            $id = mysql_insert_id();
            UserDAO::updateUserID($id);
            
	    ConnectDB::CloseConnection();

	    return $id;
	} 
        catch (Exception $e) 
        {
	    return FALSE;
	}
    }
    
    public static function addUserWithCode(UserDTO $user, $code, $eventcode) {
	$result = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "INSERT INTO `user`(`email`, `fbemail`, `fbid`, `fbusername`, `facebookid`, `name`, `lastlogin`, `code`, `eventcode`,`joinssince`, `longitude`, `latitude`, `location`, `epass`) VALUES ('$user->email','$user->fbemail','','','$user->fbid','$user->name','$user->lastlogin', '$code', '$eventcode','$user->joinssince','$user->longitude','$user->latitude','$user->location', '$user->epass');";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
           
            $id = mysql_insert_id();
            UserDAO::updateUserID($id);
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
  
    public static function confirmUser($fbid, $isEmail = 0) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set isconfirmed = '1' where fbid = '$fbid'";
            
            if($isEmail == 1)
                $strSQL = "Update user set isconfirmed = '1' where email = '$fbid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            
            $id = mysql_insert_id();
            UserDAO::updateUserID($id);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

    public static function UpdateReviewAvergae($average, $fbid) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set rating = '$average' where fbid = '$fbid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function UpdateFBUserInformation($newuser) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set fbemail = '$newuser->fbemail', name = '$newuser->name', lastlogin = '$newuser->lastlogin' where facebookid = '$newuser->fbid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function UpdateUserInformation($newuser) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set fbemail = '$newuser->fbemail', name = '$newuser->name', lastlogin = '$newuser->lastlogin' where userid = '$newuser->userid'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateUserID($userid)
    {
        $result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "Update `user` set fbid = '$userid', fbusername = '$userid' where userid = '$userid'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function deleteUser($fbusername) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Delete from user where fbusername = '$fbusername'";
	    
	    //$strSQL = "UPDATE user set deactivated = '1' where fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function suspendUser($fbusername) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set suspended = '1' where fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateRecipientID($recipient_id, $fbusername) {
		$result = TRUE;
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "Update user set recipient_id = '$recipient_id' where fbusername = '$fbusername'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    ConnectDB::CloseConnection();
	
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
    public static function updateRecipientIDByUserID($user_id,$recipient_id) {
		$result = TRUE;
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "Update user set recipient_id = '$recipient_id' where userid = '$user_id'";
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    ConnectDB::CloseConnection();
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
    public static function updateTempFacebookData($user_id,$fbid,$fbusername) {
		$result = TRUE;
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "Update user set fbid = '$fbid',fbusername='$fbusername' where userid = '$user_id'";
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    ConnectDB::CloseConnection();
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }

    public static function updateCustomerID($customer_id, $fbusername) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set customer_id = '$customer_id' where fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateCODE($userid, $code) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set code = '$code' where userid = '$userid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function checkPassword($email, $pass) 
    {
	$user = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from user where email = '$email' and epass = '$pass'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) 
            {
		return TRUE;
	    }

	    ConnectDB::CloseConnection();
	}
        catch (Exception $e) 
        {
	    return FALSE;
	}

	return TRUE;
    }

        
    public static function getUserWithCode($code) 
    {
        $ds = null;
	try {
	    if (!ConnectDB::OpenConnection())
		return $ds;

	    $strSQL = "SELECT * from user where code = '$code'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$ds = UserDAO::pulloutUser($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $ds;
	}

	return $ds;
    }
    
    public static function UpdatePassword($userid, $epass) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update user set epass = '$epass' where userid = '$userid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function returnPassword($userid)
    {
        $pass = "";

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "select epass from user where userid = '$userid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $pass;
            }
            
            while ($row = mysql_fetch_array($result)) 
            {
		$pass = $row['epass'];
	    }
            
	    ConnectDB::CloseConnection();

	    return $pass;
	} catch (Exception $e) {
	    return $pass;
	}
    }
    
    public static function updateFacebookID($userid, $facebookid)
    {
        $result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "Update `user` set facebookid = '$facebookid' where userid = '$userid'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            
            $id = mysql_insert_id();
            
            
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
}

?>