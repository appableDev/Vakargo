<?php

require_once 'ConnectDB.php';


class FlightDTO {

    public $flight_id;
    public $space_id;
    public $airlineid;
    public $flightnumber;
    public $date;
    public $departureCity;
    public $departureDate;
    public $departureAirportFsCode;
    
    public $arrivalCity;
    public $arrivalDate;
    public $arrivalAirportFsCode;
    
    public $flightDurations;
    public $status;
    
    public $timeadded;
    public $useradded;
    
    public $active;

    function FlightDTO() {
	$this->flight_id = "";
	$this->space_id = "";

	$this->airlineid = "";
	$this->flightnumber = "";
	$this->date = "";

	$this->departureCity = "";
	$this->departureDate = "";
	$this->departureAirportFsCode = "";

	$this->arrivalCity = "";
	$this->arrivalDate = "";
	$this->arrivalAirportFsCode = "";

	$this->flightDurations = "";
	$this->status = "";
	
	$this->active = "";
    }

}
/**
 * 
 * 
 * 
 */
class FlightDAO 
{
    
    function FlightDAO() {
	
    }
    
    static function getFlightWithSpaceId($spaceId) 
    {
	$rs = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
	    $strSQL = "SELECT * from flight where space_id = '$spaceId' and airline_code <> '' && flight_number <> '' ORDER BY timeadded ASC";
	    //echo($strSQL);
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

	    $flightDTO = new FlightDTO();
            while ($row = mysql_fetch_array($result)) {
                $flightDTO = FlightDAO::pulloutFlight($row);
		
		array_push($rs, $flightDTO);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }
        return $rs;
    }
    
    static function getFlightWithFlightId($flight_id) 
    {
	$flightDTO = new FlightDTO();
	
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
	    $strSQL = "SELECT * from flight where flight_id = '$flight_id'";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                $flightDTO = FlightDAO::pulloutFlight($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return null;
        }
        return $flightDTO;
    }
    
    static function getMultiFlightWithArrayFlightId($list_flight_id) 
    {
	$rs = array();
	
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
            
            foreach($list_flight_id as $f_id)
            {
                $strSQL = "SELECT * from flight where flight_id = '$f_id'";
                
                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    return FALSE;
                }
                
                $flightDTO = new FlightDTO();
                while ($row = mysql_fetch_array($result)) {
                    $flightDTO = FlightDAO::pulloutFlight($row);
                }
                
                array_push($rs, $flightDTO);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return null;
        }
        return $rs;
    }
    
    static function getAllFlightWithSpaceId($spaceId) {
	$rs = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
			$strSQL = "SELECT * from flight where space_id = '$spaceId'" . " ORDER BY timeadded ASC";
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

	    $flightDTO = new FlightDTO();
            while ($row = mysql_fetch_array($result)) {
                $flightDTO = FlightDAO::pulloutFlight($row);
		
			array_push($rs, $flightDTO);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }
        return $rs;
    }
    
    static function getAllFlights() {
	$rs = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
            $strSQL = "SELECT * from flight where airline_code <> '' and flight_number <> ''";
//	    echo $strSQL; exit();
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

	    $flightDTO = new FlightDTO();
            while ($row = mysql_fetch_array($result)) {
                $flightDTO = FlightDAO::pulloutFlight($row);
		$rs[] = $flightDTO;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }
        return $rs;
    }
    
    static function getAllInactiveFlightsInRange($range) 
    {
	$rs = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "SELECT * from flight where airline_code <> '' and flight_number <> '' and departure_city = '' and arrival_city = '' and DATEDIFF(date,'" . date("Y-m-d") . "') >= '0' and DATEDIFF(date,'" . date("Y-m-d") . "') <= '$range';";
	    
	    //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

	    $flightDTO = new FlightDTO();
            while ($row = mysql_fetch_array($result)) {
                $flightDTO = FlightDAO::pulloutFlight($row);
		$rs[] = $flightDTO;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }
        return $rs;
    }

    static function saveFlight($flight, $active) {
	$id = "";
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            $now = date("Y-m-d H:i:s");
            
	    $strSQL = "INSERT INTO `flight`(`flight_id`, `space_id`, `airline_code`, `flight_number`, 
		`date`, `flightId`, `departure_date`, `departure_airport_fscode`, 
		`departure_city`, `arrival_date`, `arrival_airport_fscode`,
	       `arrival_city`, `flight_durations`, `timeadded`, `useradded`, `active`) 
	
		VALUES (null,'$flight->space_id',
		    '$flight->airlineid','$flight->flightnumber',
		    '$flight->date', '$flight->flightId' ,'$flight->departureDate', '$flight->departureAirportFsCode', '$flight->departureCity',
		    '$flight->arrivalDate','$flight->arrivalAirportFsCode', '$flight->arrivalCity', '$flight->flightDurations', '$now', '$flight->useradded', '$active')";

	   //echo $strSQL;
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return "";
	}
    }
    
    static function updateFlight($flightId, $flight)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `flight` SET 
		    `departure_date`='$flight->departureDate', 
		    `departure_airport_fscode`='$flight->departureAirportFsCode', 
		    `departure_city`='$flight->departureCity', 
		    `arrival_date`='$flight->arrivalDate', 
		    `arrival_airport_fscode`='$flight->arrivalAirportFsCode', 
		    `arrival_city`='$flight->arrivalCity', 
		    `flight_durations`='$flight->flightDurations',
		    `active`='$flight->active',
		    `flightId`='$flight->flightId'
		    WHERE flight_id=$flightId";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function updateSpaceIDandActive($space_id, $flight_id)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `flight` SET `space_id`='$space_id', active = '1' WHERE flight_id='$flight_id'";
	    //echo $strSQL . "<br/>";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function updateSpaceID($space_id, $flight_id)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `flight` SET `space_id`='$space_id' WHERE flight_id='$flight_id'";
	    //echo $strSQL . "<br/>";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function updateDeactiveFlightWithSpaceid($space_id)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `flight` SET active = '0' WHERE `space_id`='$space_id'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function deleteEmptyFlight($username)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "DELETE FROM `flight` WHERE space_id = '0' and useradded = '" . $username . "'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    ConnectDB::CloseConnection();

	    return TRUE;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function deleteFlightsInSpace($space_id)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "DELETE FROM `flight` WHERE space_id = '$space_id' and active = '0'";
	    //echo $strSQL . "<br/>";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    ConnectDB::CloseConnection();

	    return TRUE;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function pulloutFlight($row) {
        $flightDTO = new FlightDTO();

        $flightDTO->flight_id= $row['flight_id'];
	$flightDTO->space_id= $row['space_id'];
	$flightDTO->airlineid= $row['airline_code'];
	$flightDTO->flightnumber= $row['flight_number'];
	$flightDTO->date= $row['date'];
	
	$flightDTO->departureDate= $row['departure_date'];
	$flightDTO->departureAirportFsCode= $row['departure_airport_fscode'];
	$flightDTO->departureCity= $row['departure_city'];
	
	$flightDTO->arrivalDate= $row['arrival_date'];
	$flightDTO->arrivalAirportFsCode= $row['arrival_airport_fscode'];
	$flightDTO->arrivalCity= $row['arrival_city'];
	
	$flightDTO->flightDurations= $row['flight_durations'];
	$flightDTO->active= $row['active'];
        return $flightDTO;
    }
}

?>