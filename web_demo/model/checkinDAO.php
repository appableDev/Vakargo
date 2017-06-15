<?php

require_once ('ConnectDB.php');

class CheckinDTO {

    public $checkin_id;
    public $fbid;
    public $city;
    public $lat;
    public $long;
    public $address;
    public $timeadded;

}

class CheckinDAO extends ConnectDB 
{
    //TODO - Insert your code here
    function CheckinDAO() {
        
    }

    public static function pullout($row) {
        $checkinDTO = new CheckinDTO();

        $checkinDTO->checkin_id = $row['checkin_id'];
        $checkinDTO->fbid = $row['fbid'];
        $checkinDTO->city = $row['city'];
        $checkinDTO->lat = $row['lat'];
        $checkinDTO->long = $row['long'];
        $checkinDTO->address = $row['address'];
        $checkinDTO->timeadded = $row['timeadded'];
        
        return $checkinDTO;
    }

    public static function addCheckin(CheckinDTO $ch) 
    {
        $result = TRUE;

        try 
        {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `checkin` (`fbid`, `city`, `lat`, `long`, ` address`, `timeadded`) "
                    . "VALUES ('$ch->fbid', '$ch->city', '$ch->lat', '$ch->long', '$ch->address', '$ch->timeadded');";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        }
        catch (Exception $e) 
        {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
}

?>