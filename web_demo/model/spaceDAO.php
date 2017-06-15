<?php

require_once 'ConnectDB.php';

class SpaceDTO {

    public $space_id;
    public $userid;
    public $pickup_date;
    public $pickup_location;
    public $delivery_date;
    public $delivery_location;
    public $movingway;
    public $max_weight;
    public $price;
    public $setprice;
    public $dimensionx;
    public $dimensiony;
    public $dimensionz;
    public $note;
    public $list_flightid;
    
    public $date_create;
    public $active;
    
//    Mới chỉ dùng trong API
    public $categ_list;
    
    public $amazon;
    public $address;
    public $insurance;

    public function SpaceDTO() 
    {
	$this->space_id = "";
	$this->userid = "";

	$this->pickup_date = "";
	$this->pickup_location = "";

	$this->delivery_date = "";
	$this->delivery_location = "";

	$this->max_weight = "";
	$this->price = "";
	$this->setprice = "";
	$this->dimensionx = "";
	$this->dimensiony = "";
	$this->dimensionz = "";
	$this->note = "";
	$this->date_create = "";
	$this->active = 1;
	$this->list_flightid = "";
        $this->movingway = "";
    }

}

/**
 *
 *
 */
class SpaceDAO extends ConnectDB {

    //TODO - Insert your code here
    function SpaceDAO() {
	
    }
    
    public static function getSpaceByCurrentDate($currentDate, $dayNumber, $isDeliveryOrPickup)
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

        $strSQL="";
        if($isDeliveryOrPickup=="Delivery") 
        {
            $strSQL = "SELECT sp.* from space sp " .
                        " LEFT JOIN  booking bk ON sp.space_id=bk.space_id ".
                        " where bk.delivered='0' AND DATEDIFF('$currentDate',sp.delivery_date)='$dayNumber' ".
                        " order by space_id";
        }
        else
        {
            $strSQL = "SELECT sp.* from space sp " .
                        " LEFT JOIN  booking bk ON sp.space_id=bk.space_id ".
                        " where bk.pickuped='0' AND DATEDIFF('$currentDate',sp.pickup_date)='$dayNumber' ".
                        " order by space_id";
        }   
            //echo $strSQL . "<br />";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                    return $ds;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, SpaceDAO::pullout($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            ConnectDB::CloseConnection();
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getSpaceByLastTransactionDate($currentDate, $dayNumber) 
    {
		$ds = array();
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	        
        	$strSQL = "SELECT sp.* from space sp " .
	    			" LEFT JOIN  booking bk ON sp.space_id=bk.space_id ".
	    			" where (bk.pickuped = '0' OR bk.delivered = '0') AND DATEDIFF('$currentDate',sp.delivery_date) = '$dayNumber' ".
	    			" order by space_id";
		    //echo $strSQL . "<br />";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return $ds;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
				array_push($ds, SpaceDAO::pullout($row));
		    }
	
		    ConnectDB::CloseConnection();
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    $result = FALSE;
		}
	
		return $ds;
    }

    public static function getSpaceWithSpaceID($space_id) {
	$rs = null;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from space where space_id = '$space_id'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $rs;
	    }

	    while ($row = mysql_fetch_array($result)) {
		$rs = SpaceDAO::pullout($row);
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {

	    return $rs;
	}
	return $rs;
    }
    
    public static function getCurrentSpace($fbusername, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
            
	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername' and pickup_date < '$now' and delivery_date >= '$now' order by pickup_date desc limit $start, $limit";
	    //echo $strSQL;

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getUpCommingSpace($fbusername, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
            
	    //$strSQL = "SELECT * from space where user_fbusername = '$fbusername' and pickup_date >= '$now' order by pickup_date desc limit $start, $limit";
            
//            $strSQL = "	SELECT DISTINCT s.* FROM `space` s LEFT JOIN `booking` b ON s.space_id = b.space_id 
//						AND ( b.delivered = '0')
//						WHERE s.user_fbusername = '$fbusername'  AND DATEDIFF('$now', s.delivery_date) <= 14
//						ORDER BY pickup_date
//						desc limit $start, $limit";
               $strSQL = "SELECT DISTINCT s.* FROM `space` s 
                                LEFT JOIN `booking` b ON (s.space_id = b.space_id AND ( b.delivered = '0')) 
                                WHERE s.user_fbusername = '1580020610' 
                                and (DATEDIFF(s.pickup_date, '$now') >= 0)
                                ORDER BY pickup_date desc limit $start, $limit";
            
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getPastSpace($fbusername, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
            
	    $now = date("Y-m-d");
	    //$strSQL = "SELECT * from space where user_fbusername = '$fbusername' and pickup_date < '$now' order by pickup_date desc limit $start, $limit";
//	    $strSQL = "SELECT DISTINCT s.* FROM `space` s
//					LEFT JOIN `booking` b ON s.`space_id` = b.`space_id`
//					WHERE s.user_fbusername = '$fbusername' AND (s.pickup_date < '$now' AND  b.`delivered` = '1')
//					ORDER BY pickup_date DESC limit $start, $limit";
            
            
            
            $strSQL = " SELECT * FROM `space` 
                        WHERE 
                        (
                            (
                                space.space_id IN (SELECT booking.space_id FROM booking WHERE accepted = '1') 
                                AND
                                (
                                    space.space_id NOT IN 
                                    (
                                        SELECT DISTINCT s.space_id FROM `space` s JOIN `booking` b
                                        ON s.space_id = b.space_id 
                                        AND ( 
                                                b.delivered = '0' AND DATEDIFF('$now', s.delivery_date) < 14
                                             )
                                        WHERE s.user_fbusername = '$fbusername'
                                    )
                                )
                            ) 
                            OR (space.space_id NOT IN (SELECT booking.space_id FROM booking WHERE accepted = '1') AND DATEDIFF('$now', space.delivery_date) > 0)
                        )
                        AND space.user_fbusername = '$fbusername' 
                        ORDER BY pickup_date DESC limit $start, $limit";
		
         
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if($result == false || mysql_num_rows($result) <= 0) 
            {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function getSpacesOfUsername($fbusername) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function getSpacesOfUsername_OutOf14days($fbusername) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername' and DATEDIFF('$currentdate', delivery_date) > 14";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function deleteSpacesOfUsername($fbusername) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "DELETE from space where user_fbusername = '$fbusername'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getInProgressSpace($fbusername, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername' and delivery_date >= '$now' limit $start, $limit";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getDeliveredSpace($fbusername, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername' and delivery_date < '$now' limit $start, $limit";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function getAllSpaces() {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $strSQL = "SELECT * FROM space";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }
	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }
    
    public static function getAllSpacesInactive() 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $strSQL = "SELECT * FROM space where active = '0'";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }
	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getTotalNumberCurrentSpace($fbusername) 
    {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    $strSQL = "SELECT count(*) as total from space where user_fbusername = '$fbusername' and pickup_date < '$now' and '$now' <= delivery_date ";
	    //echo $strSQL;
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    $row = mysql_fetch_array($result);
	    $total = $row['total'];

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }
    
    public static function getTotalNumberUpCommingSpace($fbusername) 
    {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    //$strSQL = "SELECT count(*) as total from space where user_fbusername = '$fbusername' and pickup_date >= '$now'";

//            $strSQL = " SELECT COUNT(DISTINCT s.space_id) AS total FROM `space` s LEFT JOIN `booking` b ON s.space_id = b.space_id 
//						AND ( b.delivered = '0')
//						WHERE s.user_fbusername = '$fbusername'  AND DATEDIFF('$now', s.delivery_date) <= 14";
            
            $strSQL = "SELECT COUNT(DISTINCT s.space_id, s.pickup_date) AS total FROM `space` s 
                                LEFT JOIN `booking` b ON (s.space_id = b.space_id AND ( b.delivered = '0')) 
                                WHERE s.user_fbusername = '$fbusername' and (DATEDIFF(s.pickup_date, '$now') >= 0)
                                ORDER BY pickup_date desc";
            
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    $row = mysql_fetch_array($result);
	    $total = $row['total'];

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }

    public static function getTotalNumberPastSpace($fbusername) 
    {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    //$strSQL = "SELECT count(*) as total from space where user_fbusername = '$fbusername' and pickup_date < '$now'";
//	    $strSQL = "SELECT count(DISTINCT s.id) as total FROM `space` s
//					LEFT JOIN `booking` b ON s.`space_id`=b.`space_id`
//					WHERE s.user_fbusername = '$fbusername' AND (s.pickup_date < '$now' OR  b.`delivered`=1)";
	    
            
            $strSQL = " SELECT count(*) as total FROM `space` 
                        WHERE 
                        (
                            (
                                space.space_id IN (SELECT booking.space_id FROM booking WHERE accepted = '1') 
                                AND
                                (
                                    space.space_id NOT IN 
                                    (
                                        SELECT DISTINCT s.space_id FROM `space` s JOIN `booking` b
                                        ON s.space_id = b.space_id 
                                        AND ( 
                                                b.delivered = '0' AND DATEDIFF('$now', s.delivery_date) < 14
                                             )
                                        WHERE s.user_fbusername = '$fbusername'
                                    )
                                )
                            ) 
                            OR (space.space_id NOT IN (SELECT booking.space_id FROM booking WHERE accepted = '1') AND DATEDIFF('$now', space.delivery_date) >= 0)
                        )
                        AND space.user_fbusername = '$fbusername'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    $row = mysql_fetch_array($result);
	    $total = $row['total'];

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }

    public static function getTotalNumberInProgressSpace($fbusername) 
    {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
	    $strSQL = "SELECT count(*) as total from space where user_fbusername = $fbusername and delivery_date > '$now'";
	    //echo $strSQL;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    $row = mysql_fetch_array($result);
	    $total = $row['total'];

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }

    public static function pullout($row) 
    {
	$spaceDTO = new SpaceDTO();

	$spaceDTO->space_id = $row['space_id'];

	$spaceDTO->userid = $row['user_fbusername'];
	$spaceDTO->pickup_date = $row['pickup_date'];
	$spaceDTO->pickup_location = $row['pickup_location'];

	$spaceDTO->delivery_date = $row['delivery_date'];
	$spaceDTO->delivery_location = $row['delivery_location'];
        
        $spaceDTO->movingway = $row['movingway'];
        

	if (isset($row['availableweight']))
	    $spaceDTO->max_weight = floatval($row['availableweight']);
	else
	    $spaceDTO->max_weight = floatval($row['max_weight']);

	$spaceDTO->price = floatval($row['price']);
	$spaceDTO->setprice = floatval($row['setprice']);
	$spaceDTO->dimensionx = floatval($row['dimensionx']);
	$spaceDTO->dimensiony = floatval($row['dimensiony']);
	$spaceDTO->dimensionz = floatval($row['dimensionz']);
	$spaceDTO->note = $row['note'];
	$spaceDTO->date_create = $row['date_created'];
	$spaceDTO->active = $row['active'] + 0;
	$spaceDTO->list_flightid = $row['list_flightid'];
        
	$spaceDTO->amazon = $row['amazon'] + 0;
	$spaceDTO->address = $row['address'];
	$spaceDTO->insurance = $row['insurance'] + 0;
        $spaceDTO->isvakargoscooter = $row['isvakargoscooter'] + 0;
        
	return $spaceDTO;
    }

    public static function addSpace(SpaceDTO $space) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $space->date_create = date("Y-m-d");
            $now = date("Y-m-d H:i:s");
            
	    $strSQL = "INSERT INTO `space`(`id`, `user_fbusername`, `pickup_date`, `pickup_location`, `delivery_date`, `delivery_location`, `max_weight`, `price`, `setprice` ,`dimensionx`, `dimensiony`, `dimensionz`, `note`, `amazon`, `address`, `insurance`,`list_flightid`, `date_created`, `active`, `movingway`, `isvakargoscooter`)
		VALUES (null,'$space->userid', '$space->pickup_date','$space->pickup_location','$space->delivery_date','$space->delivery_location','$space->max_weight','$space->price','$space->setprice','$space->dimensionx','$space->dimensiony','$space->dimensionz','$space->note','$space->amazon','$space->address','$space->insurance','$space->list_flightid','$now','$space->active', '$space->movingway', '$space->drop_off_for_vakargo_scooter')";

            //echo $strSQL;
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            $id = mysql_insert_id();
            
            $code = substr(md5($id), 0, 16);
            $strSQL = "UPDATE space set space_id = '" . $code . "' where id='$id'";
            $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    ConnectDB::CloseConnection();

	    return $code;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    static function updateSpaceStatus($space_id, $status)
    {
	$result = TRUE;
	try 
        {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `space` SET `active`='$status' WHERE space_id='$space_id';";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    ConnectDB::CloseConnection();
            
            return $result;
	} 
        catch (Exception $e) 
        {
	    return FALSE;
	}
    }
    
    static function updateListFlightid($space_id, $listflight)
    {
	$result = TRUE;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "UPDATE `space` SET `list_flightid`='$listflight' WHERE space_id='$space_id'";
	    //echo $strSQL;
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

    public static function getSpaceWithQuery($strSQL) {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    public static function getTotalSpaceSearch($strSQL) {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }
	    
	    while ($row = mysql_fetch_array($result)) {
		$total = $row['total'];
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }
    
    public static function getFlightArrayOfASpace($space_id)
    {
	$flightarray = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    
	    $strSQL = "Select list_flightid from space where space = '$space_id'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    if ($result == false || mysql_num_rows($result) <= 0) 
	    {
		return 0;
	    }
	    
	    while ($row = mysql_fetch_array($result)) {
		$listflight = $row['list_flightid'];
	    }
	    
	    $flightarray = preg_split("/,/", $listflight);
	    
	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	}

	return $flightarray;
    }
    
    
    
    /**
    * Lấy danh sách các space đến và qua ngày delivery date và nhỏ hơn 14 ngày
    * 
    * 
    * @link http://vakargo.com/
    */
    public static function getSpace_OutOfDeliveryDate_InOf14days($fbusername, $currentDate) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
            
	    $strSQL = "SELECT * from space where user_fbusername = '$fbusername' and DATEDIFF('$currentDate', delivery_date) < 14 and DATEDIFF('$currentDate', delivery_date) >= 0";

            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = FALSE;
	}

	return $ds;
    }

    
    
    //=========================================================================================================
    //=========================================================================================================
    //=========================================================================================================
    
    public static function getMyListing($userid, $start, $limit) 
    {
	$ds = array();
	try {
	    if (!ConnectDB::OpenConnection())
		return $ds;
            
	    $now = date("Y-m-d");
            
            $strSQL = " SELECT * FROM `space` 
                        WHERE space.user_fbusername = '$userid' 
                        ORDER BY space.pickup_date DESC limit $start, $limit";
		
         
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if($result == false || mysql_num_rows($result) <= 0) 
            {
		return $ds;
	    }

	    while ($row = mysql_fetch_array($result)) {
		array_push($ds, SpaceDAO::pullout($row));
	    }

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $ds;
	}

	return $ds;
    }
    
    public static function getMyListing_TotalCount($userid) 
    {
	$total = 0;
	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $now = date("Y-m-d");
            
            $strSQL = " SELECT count(*) as total FROM `space` 
                        WHERE space.user_fbusername = '$userid'";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    if ($result == false || mysql_num_rows($result) <= 0) {
		return 0;
	    }

	    $row = mysql_fetch_array($result);
	    $total = $row['total'];

	    ConnectDB::CloseConnection();
	} catch (Exception $e) {
	    $result = $total;
	}

	return $total;
    }
}

?>