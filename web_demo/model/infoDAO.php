<?php
require_once 'ConnectDB.php';
require_once 'Global.php';

class InfoDTO
{
    public $infoId;
    public $infoType;
    public $infoDescription;
    
}

class InfoDAO extends ConnectDB {

    function InfoDAO() {
	
    }

    public static function pulloutInfo($row) 
    {
		$info = new InfoDTO();
	
		$info->infoId = $row['infoId'];
		$info->infoType = $row['infoType'];
		$info->infoDescription = $row['infoDescription'];
		
		return $info;
    }
    
    public static function GetInfoByType($infoType) 
    {
        $info = null;
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "SELECT * FROM `info` WHERE `infoType`= '$infoType'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return FALSE;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
		    	$info = new InfoDAO();
				$info = InfoDAO::pulloutInfo($row);
		    }

		    ConnectDB::CloseConnection();
		    return $info;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return $info;
		}
	
		return $info;
    }
    
    public static function UpdateInfo($infoType, $infoDescription)
    {
		$result = FALSE;
		$infoDescription=preg_replace("/mso-bidi-font-family: .*?;/","mso-bidi-font-family: proxima_nova;",$infoDescription);
		//$infoDescription=preg_replace("/<strong style=\"font-weight: bold;\">/","<strong style=\"font-family: 'Freight Sans Bold', 'lucida grande',tahoma,verdana,arial,sans-serif !important; font-weight: bold;\">",$infoDescription);
		//$infoDescription=preg_replace("/<strong>/","<strong style=\"font-family: 'Freight Sans Bold', 'lucida grande',tahoma,verdana,arial,sans-serif !important; font-weight: bold;\">",$infoDescription);
		$infoDescription=mysql_escape_string($infoDescription);
	
		try {
		    if (!ConnectDB::OpenConnection())
				return FALSE;
		    
		    $strSQL = "Update `info` set infoDescription = '$infoDescription' where infoType = '$infoType'";
		    
		    //Globals::Write_LogInfo($strSQL);
	         
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    
		    ConnectDB::CloseConnection();
	
		    return true;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return FALSE;
		}
    }
}
?>
