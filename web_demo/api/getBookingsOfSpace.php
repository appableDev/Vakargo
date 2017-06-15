<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';

if(!isset($_REQUEST["userid"]) || !isset($_REQUEST["sid"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["userid"]))
    {
	$fbid = $_REQUEST["userid"];
        $space_id = $_REQUEST["sid"];
        
	$existeduser = UserDAO::getUserWithFbID($fbid);
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($fbid);

	    if($rs == "1")
	    {
                $upcommingSpace = BookingDAO::getBookedUser_WithSpaceID($space_id, "");
                
                
                //Thanh cong
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->total = count($upcommingSpace) + 0;
                $rs->count = count($upcommingSpace) + 0;
                $rs->results = $upcommingSpace;

                echo json_encode($rs);
                
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