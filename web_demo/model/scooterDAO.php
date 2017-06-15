<?php

require_once 'ConnectDB.php';

class ScooterDTO {
    public $id;
    public $cityID;
    public $extra;
    public $message;
    
}

class ScooterDAO extends ConnectDB {

    function __construct() {
    	
   	}

    public static function getAllScooter() {
		$list = array();
		try {
		    if (!parent::OpenConnection())
			return FALSE;
	
		    $strSQL = "SELECT  * FROM `vakargoscooter`";
		    
		    $result = mysql_query($strSQL, parent::$mLink);
		    
		    if ($result == false || mysql_num_rows($result) <= 0) {
				return FALSE;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
				array_push($list, self::createScooterObj($row));
		    }
		    parent::CloseConnection();
		} catch (Exception $e) {
		    return FALSE;
		}
	
		return $list;
    }

    public static function createScooterObj($row) {
		$scooter = new ScooterDTO();
		$scooter->id = $row['id'];
		$scooter->cityID = $row['cityID'];
		$scooter->extra = $row['extra'];
		$scooter->message = $row['message'];
		return $scooter;
    }

    public static function addScooter(ScooterDTO $scooter) {
		$result = TRUE;
        if (!parent::OpenConnection())
			return FALSE;
		try {
		    $strSQL = "INSERT INTO `vakargoscooter`(`cityID`, `extra`,`message`) VALUES ('".
		    		  $scooter->cityID."','".$scooter->extra."','".
		    		  $scooter->message."');";
		    $result = mysql_query($strSQL, parent::$mLink);
			parent::CloseConnection();
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }

    public static function getScooterBycityID($cityID) {
		$list = array();
		try {
		    if (!parent::OpenConnection())
				return FALSE;
		    $strSQL = "SELECT  * FROM `vakargoscooter` WHERE cityID = ".$cityID;
		    $result = mysql_query($strSQL, parent::$mLink);
		    if ($result == false || mysql_num_rows($result) <= 0) {
				return FALSE;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
				array_push($list, self::createScooterObj($row));
		    }
			parent::CloseConnection();
		} catch (Exception $e) {
		    return FALSE;
		}
		return $list;
    }

}

?>