<?php

require_once 'ConnectDB.php';

class FbfriendDTO {

    public $owner_username;
    public $friend_username;
    public $friend_id;
    public $friend_name;

}

class FbfriendDAO extends ConnectDB {

    //TODO - Insert your code here
    function FbfriendDAO() {
	
    }

    public static function getFBMutualFriends($owner, $guest) {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT  a.owner_username, a.friend_username, a.friend_id, a.friend_name FROM `fbfriendlist` a, `fbfriendlist` b WHERE a.owner_username = '$owner' AND b.owner_username = '$guest' AND a.friend_username = b.friend_username";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, FbfriendDAO::pulloutUser($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return $ds;
	}

	return $ds;
    }
    
    public static function getFBFriends($guest, $limit = NULL) {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            if($limit == null)
                $strSQL = "SELECT * FROM `fbfriendlist` WHERE owner_username = '$guest'";
            else
                $strSQL = "SELECT * FROM `fbfriendlist` WHERE owner_username = '$guest' LIMIT 0, $limit";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, FbfriendDAO::pulloutUser($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return FALSE;
	}

	return $ds;
    }
    
    public static function countFBFriends($guest) {
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            $strSQL = "SELECT count(*) as total FROM `fbfriendlist` WHERE owner_username = '$guest'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    while ($row = mysql_fetch_array($result)) {
		return $row['total'];
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return 0;
	}

	return 0;
    }

    public static function pulloutUser($row) {
	$userDTO = new FbfriendDTO();

	$userDTO->owner_username = $row['owner_username'];
	$userDTO->friend_username = $row['friend_username'];
	$userDTO->friend_name = $row['friend_name'];
	$userDTO->friend_id = $row['friend_id'];
	return $userDTO;
    }

    public static function addfriend(FbfriendDTO $user) {
	$result = TRUE;

	try {
	    $strSQL = "INSERT INTO `fbfriendlist`(`owner_username`, `friend_username`, `friend_id`,  `friend_name`) VALUES ('" . $user->owner_username . "','$user->friend_username','$user->friend_id','$user->friend_name');";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

    public static function deleteFriend($fbusername) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "DELETE from fbfriendlist where owner_username = '$fbusername'";
	    
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

}

?>