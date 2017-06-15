<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';


if(!isset($_REQUEST["userid"]))
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
	$userid = $_REQUEST["userid"];
        
	$existeduser = UserDAO::getUserWithUserid($userid);
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($userid);

	    if($rs == "1")
	    {
                $totalPastSpace = BookingDAO::getTotalMyBookings($userid);
                
                if(!isset($_REQUEST['start']) || !isset($_REQUEST['limit']))
                    $pastSpace = BookingDAO::getMyBookings($userid);
                else
                    $pastSpace = BookingDAO::getMyBookings($userid, $start, $limit);
                
                for($i = 0; $i < count($pastSpace); $i++)
                {
                    unset($pastSpace[$i]->pickup_confirmation_number);
                    unset($pastSpace[$i]->delivery_confirmation_number);
                    unset($pastSpace[$i]->booker_payment_token);
                    unset($pastSpace[$i]->viewed_by_shipper);
                    unset($pastSpace[$i]->viewed_by_booker);
                    unset($pastSpace[$i]->fbusername);
                    unset($pastSpace[$i]->email);
                    
                    $space = SpaceDAO::getSpaceWithSpaceID($pastSpace[$i]->space_id);
                    $user = UserDAO::getUserWithUserid($space->userid);
                    
                    $pastSpace[$i]->owner->fbid = $user->fbid;
                    $pastSpace[$i]->owner->userid = $user->userid;
                    $pastSpace[$i]->owner->name = $user->name;
                    
                    $pastSpace[$i]->deliveryLocation = $space->delivery_location;
                    $pastSpace[$i]->deliveryDate = $space->delivery_date;
                    
                    $pastSpace[$i]->baseprice = $space->setprice;
                    $pastSpace[$i]->pricebyweight = $space->price;
                    
                    $arrayCategory = Space_CategoryDAO::getCategoryList($space->space_id);
                    for ($k = 0; $k < count($arrayCategory); $k++) 
                    {
                        unset($arrayCategory[$k]->space_id);
                        unset($arrayCategory[$k]->category_name);
                    }

                    $pastSpace[$i]->categ_list = $arrayCategory;
                }
                
                
                
                //Thanh cong
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->total = $totalPastSpace + 0;
                $rs->count = count($pastSpace);
                $rs->results = $pastSpace;

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