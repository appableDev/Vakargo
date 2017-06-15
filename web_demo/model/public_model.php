<?php
require_once 'ConnectDB.php';

class systemParameterDTO
{
	public $id;
	public $name;
	public $value;
    public $description;
}
class SystemParameterDAO extends ConnectDB 
{
    //TODO - Insert your code here
    function SystemParameterDAO() {
	
    }
    
    public static function pulloutSystemParameter($row) {
		$systemParameter = new systemParameterDTO();
	
		$systemParameter->id = $row['id'];
		$systemParameter->name = $row['name'];
		$systemParameter->value = $row['value'];
		$systemParameter->description = $row['description'];
		return $systemParameter;
    }
    
    public static function GetAllSystemParameter() 
    {
        $ds = array();
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "SELECT * FROM `systemparameters` ";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return FALSE;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
		    	array_push($ds, SystemParameterDAO::pulloutSystemParameter($row));
		    }

		    ConnectDB::CloseConnection();
		    return $ds;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return $ds;
		}
    }
    
    public static function GetSystemParameter($paramName) 
    {
        $systemParameter = null;
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "SELECT * FROM `systemparameters` WHERE `name`= '$paramName'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return FALSE;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
		    	$systemParameter = new SystemParameterDAO();
				$systemParameter = SystemParameterDAO::pulloutSystemParameter($row);
		    }

		    ConnectDB::CloseConnection();
		    return $systemParameter;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return $systemParameter;
		}
	
		return $systemParameter;
    }
    
    public static function updateSystemParameter($sysId,$sysValue) {
		$result = TRUE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "Update `systemparameters` set value = '$sysValue' where id = '$sysId'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    ConnectDB::CloseConnection();
	
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
}
/**
 *
 *
 */
?>
