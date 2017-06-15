<?php

require_once 'ConnectDB.php';

class EventDTO {

    public $name;
    public $code;
}

class EventDAO extends ConnectDB {

    //TODO - Insert your code here
    function EventDAO() {
	
    }

    public static function getAllEvent() {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT  * FROM `event`";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return FALSE;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, EventDAO::pulloutEvent($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    return FALSE;
	}

	return $ds;
    }

    public static function pulloutEvent($row) {
	$event = new EventDTO();

	$event->name = $row['name'];
	$event->code = $row['code'];

	return $event;
    }

    public static function addEvent(EventDTO $event) {
	$result = TRUE;
        
        if (!ConnectDB::OpenConnection())
		return FALSE;

	try {
	    $strSQL = "INSERT INTO `event`(`name`, `code`) VALUES ('$event->name','$event->code');";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();
            
	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

//    public static function deleteFriend($fbusername) {
//	$result = TRUE;
//
//	try {
//	    if (!ConnectDB::OpenConnection())
//		return FALSE;
//
//	    $strSQL = "DELETE from fbfriendlist where owner_username = '$fbusername'";
//	    
//	    //echo $strSQL;
//	    $result = mysql_query($strSQL, ConnectDB::$mLink);
//
//	    ConnectDB::CloseConnection();
//
//	    return $result;
//	} catch (Exception $e) {
//	    return FALSE;
//	}
//    }

}

?>