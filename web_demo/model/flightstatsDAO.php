<?php

require_once ('ConnectDB.php');

class FlightStatsDTO {
    public $geonameid;
    public $name;
    public $alternatenames;
    public $asciiname;
    public $state;
    public $statecode;
    public $countrycode;
    public $countryname;

}
    
class CountryDTO
{
    public $countrycode;
    public $countryname;
}


class FlightStatsDAO extends ConnectDB {

    //TODO - Insert your code here
    function FlightStatsDAO() {
	
    }

    public static function pulloutCityItem($row) {
	$cityDTO = new FlightStatsDTO();
	
	$cityDTO->geonameid = $row['geonameid'];
	$cityDTO->name = $row['name'];
	$cityDTO->alternatenames = $row['alternatenames'];
	$cityDTO->asciiname = $row['asciiname'];
	$cityDTO->state = $row['state'];
	$cityDTO->statecode = $row['statecode'];
	$cityDTO->countrycode = $row['countrycode'];
	$cityDTO->countryname = $row['countryname'];
	
	return $cityDTO;
    }
    public static function getAllCountry($start) 
    {
	$ds = array();
	try {
	    $strSQL = "SELECT DISTINCT countrycode, countryname FROM  `cities_info` WHERE countryname != ''";
	    echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$c = new CountryDTO();
		$c->countrycode = $row[countrycode];
		$c->countryname = $row[countryname];
		
		array_push($ds, $c);
	    }
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function FindACity($name, $state, $country) 
    {
	$ds = null;
	try {
	    if($country == "US")
		$strSQL = "SELECT * from flightstats where cityname = '$name' and state='$state' and country = '$country'";
	    else
		$strSQL = "SELECT * from flightstats where cityname = '$name' and country = '$country'";
	    
	    //echo $strSQL;
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$ds = FlightStatsDAO::pulloutCityItem($row);
	    }

	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function FindMergedACity($name, $state, $country) 
    {
	$ds = null;
	try {
	    if($country == "US")
		$strSQL = "SELECT * from flightstats where cityname = '$name' and state='$state' and country = '$country' and `check`='1'";
	    else
		$strSQL = "SELECT * from flightstats where cityname = '$name' and country = '$country' and `check`='1'";
	    
	    echo $strSQL . "<br/>";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$ds = FlightStatsDAO::pulloutCityItem($row);
	    }

	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function updateCountry ($c)
    {
	try {
	    $strSQL = "Update cities_info set countryname = '$c->countryname' where countrycode='$c->countrycode'";
	    echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false) {
		return FALSE;
	    }
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return TRUE;
    }
    
    public static function updateCheckedCity ($city)
    {
	try {
	    if (!ConnectDB::OpenConnection())
                return FALSE;
	    
	    $strSQL = "Update flightstats set `check` = '1' where id='$city->id'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false) {
		return FALSE;
	    }
	    
            ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return TRUE;
    }
    
    public static function updateDubplicateCity ($city)
    {
	try {
	    $strSQL = "Update flightstats set `check` = '-' where id='$city->id'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false) {
		return FALSE;
	    }
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return TRUE;
    }

    public static function getMultiCitesfromKeyword($keyword, $limit) 
    {
	$start = 0;
	$ds = array();
	try {
	    $strSQL = "SELECT * from cities where name like '%$keyword%' or asciiname like '%$keyword%' limit 0, $limit"; // or alternatenames like '%$keyword%' limit 0, $limit";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) 
	    {
		$city = new City();
		$city->id = $row['geonameid'] . "-" . $row['citycode'];
		if($row['state'] == "")
		    $city->name = $row['name'] . ", " . $row['countryname'];
		else
		    $city->name = $row['name'] . ", " . $row['state'] . ", " . $row['countryname'];
		
		array_push($ds, $city);
	    }
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

}

?>