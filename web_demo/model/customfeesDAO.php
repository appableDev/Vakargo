<?php

require_once 'ConnectDB.php';


class customfeesDTO
{
	public $id ;
        public $countrycode;
        public $fee;
}
/**
 *
 *
 */
class customfeesDAO extends ConnectDB {

    //TODO - Insert your code here
    function customfeesDAO() {
	
    }

    public static function getfee_withCountrycode($code) {

	$fee = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from customsfees where countrycode = '$code'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $fee;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$fee = customfeesDAO::pullout($row);
	    }

	    ConnectDB::CloseConnection();
	}
        catch (Exception $e) 
        {
	    return $fee;
	}

	return $fee;
    }

    public static function pullout($row) 
    {
	$codeDTO = new customfeesDTO();
	$codeDTO->id = $row['id'];
        $codeDTO->countrycode = $row['countrycode'];
	$codeDTO->fee = $row['fee'];
	return $codeDTO;
    }

    
    public static function updateFee($countrycode, $fee) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "UPDATE `customsfees` set fee = '$fee' where countrycode = '$countrycode' ";
	  
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
  
}

?>