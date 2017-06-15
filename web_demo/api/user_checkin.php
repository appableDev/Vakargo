<?php
session_start();

require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if(!isset($_REQUEST["userId"]) || !isset($_REQUEST["city"]) || !isset($_REQUEST["long"]) || !isset($_REQUEST["lat"]) || !isset($_REQUEST["address"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["userId"]))
    {
	$fbid = $_REQUEST["userId"];
	$city = $_REQUEST["city"];
	$long = $_REQUEST["long"];
        $lat = $_REQUEST["lat"];
	$address = $_REQUEST["address"];
        
        $ch = new CheckinDTO();
        $ch->fbid = $fbid;
        $ch->city = $city;
        $ch->lat = $lat;
        $ch->long = $long;
        $ch->address = $address;
        $ch->timeadded = date("Y-m-d h:m:s");
        
	$existeduser = UserDAO::getUserWithFbID($fbid);
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($fbid);

	    if($rs == "1")
	    {
                $rs = CheckinDAO::addCheckin($ch);
                
                if($rs)
                {
                    //Thanh cong
                    $rs = new ResultGet();
                    $rs->code = 1;
                    $rs->results = "";

                    echo json_encode($rs);
                }
                else
                {
                    //Checkin bi loi
                    $rs = new ResultGet();
                    $rs->code = 207;
                    $rs->results = "Can not save your check-in data";

                    echo json_encode($rs);
                }
	    }
	    else
	    {
		//TAI KHOAN CHUA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 202;
                $rs->results = "This account is not confirmed";

                echo json_encode($rs);
	    }
	}
	else
	{
            $rs = new ResultGet();
            $rs->code = 203;
            $rs->results = "This account does not exist";

            echo json_encode($rs);
	}
    }
    else
    {
        $rs = new ResultGet();
        $rs->code = 0;
        $rs->results = "Not enough parameters";

        echo json_encode($rs);
    }
}
?>