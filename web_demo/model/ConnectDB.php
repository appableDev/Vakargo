<?php
require_once ('Global.php');
date_default_timezone_set('UTC');

function make_safe($variable) 
{
	ConnectDB::OpenConnection();

	$variable = htmlspecialchars(trim($variable), ENT_QUOTES);
		
    return $variable;
}
	
class ConnectDB
{
	public static $mHost = "localhost";
	public static $mLoginName = "root";
	public static $mPassword = "";
	public static $mDBName = "vakargo_db";
	public static $mLink;

	function ConnectDB() 
	{
	}
	
	public static function OpenConnection() 
	{
		ConnectDB::$mLink = mysql_connect(ConnectDB::$mHost, ConnectDB::$mLoginName, ConnectDB::$mPassword);
		if (!ConnectDB::$mLink) return false;
		
		if (!@mysql_select_db(ConnectDB::$mDBName, ConnectDB::$mLink)) 	return false;
		mysql_query("set names 'utf8'");
                mysql_set_charset('utf8');
				
		return true;
	}
	
//	public static function CloseConnection() 
//	{
//		mysql_close(ConnectDB::$mLink);
//	}
        public static function CloseConnection() 
        {
            if(gettype(ConnectDB::$mLink) == "resource") 
            {
                return mysql_close(ConnectDB::$mLink);
            }
            return false;
        }
}

?>