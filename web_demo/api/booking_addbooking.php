<?php
require_once './result.php';
require_once('../model/DAO.php');
require_once('../model/Global.php');

$rs = new ResultGet();
$rs->code = 0;
$rs->results = "Not enough parameters";
unset($rs->count);
unset($rs->total);

if( isset($_REQUEST['spaceid']) && $_REQUEST['spaceid'] != "" && 
    isset($_REQUEST['userid']) && $_REQUEST['userid'] != "" && 
    isset($_REQUEST['customerid']) && $_REQUEST['customerid'] != "" && 
    isset($_REQUEST['weight']) && $_REQUEST['weight'] != "" &&
    isset($_REQUEST['estimated_price']) && $_REQUEST['estimated_price'] != "")
{
    try
    {
        $spaceid = $_REQUEST['spaceid'];
        $userid = $_REQUEST['userid'];
        $customerid = $_REQUEST['customerid'];
        $weight = $_REQUEST['weight'];
        $price = $_REQUEST['estimated_price'];
        
        $space = SpaceDAO::getSpaceWithSpaceID($spaceid);
        if($space == NULL)
        {
            $rs->code = 403;
            $rs->results = "This space does not exist";

            echo json_encode($rs);
            exit();
        }
        
        $user = UserDAO::getUserWithUserid($userid);
        if($user == NULL)
        {
            $rs->code = 203;
            $rs->results = "This account does not exist";

            echo json_encode($rs);
            exit();
        }
        
        $customer = customerDAO::getCustomer_ByCustomerID($customerid);
        
        if($customer == NULL)
        {
            $rs->code = 303;
            $rs->results = "Customer does not exist";

            echo json_encode($rs);
            exit();
        }
        
        if($customer->user_id != $user->userid)
        {
            $rs->code = 305;
            $rs->results = "Customer does not belong this user";

            echo json_encode($rs);
            exit();
        }
        
        $weightTotal = $space->max_weight;
        $sumOrderedWeight = BookingDAO::getOrderWeightTotal($space->space_id,  "past");
        
        if($weight > $weightTotal - $sumOrderedWeight)
        {
            $rs->code = 506;
            $rs->results = "This space is not enought weight for this booking";

            echo json_encode($rs);
            exit();
        }
        
        $booking = new BookingDTO();
        $booking->user_fbusername = $userid;
        $booking->space_id = $spaceid;
        $booking->weight = $weight;
        $booking->booker_payment_token = $customerid;
        $booking->estimated_price = $price;

        $result = BookingDAO::addBooking($booking);
            
        
        if($result != FALSE)
        {
            $rs->code = 1;
            $rs->results = "Add new booking successfully!";

            echo json_encode($rs);
        }
        else
        {
            $rs->code = 504;
                $rs->results = "Something wrong from database";
      
            echo json_encode($rs);
        }
    }
    catch (Exception $e)
    {
        $rs->code = 301;
        $rs->results = $e->json_body;

        echo json_encode($rs);
    }
}
else
{
    $rs->code = 0;
    
    if(isset($_REQUEST['spaceid']) && $_REQUEST['spaceid'] != "");
    else 
        $rs->results = "No spaceid";
    
         
    if(isset($_REQUEST['userid']) && $_REQUEST['userid'] != "") ;
    else
        $rs->results = "userid";
    
    if(isset($_REQUEST['customerid']) && $_REQUEST['customerid'] != "") ;
    else
        $rs->results = "userid";
    
    if(isset($_REQUEST['weight']) && $_REQUEST['weight'] != "");
    else
        $rs->results = "weight";
    
    if(isset($_REQUEST['estimated_price']) && $_REQUEST['estimated_price'] != "");
    else
        $rs->results = "price";
    

    echo json_encode($rs);  
}
?>
