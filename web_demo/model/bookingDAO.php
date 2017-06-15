<?php

require_once 'ConnectDB.php';
require_once 'Global.php';

class BookingDTO
{
	public $booking_id;
	public $space_id ;
	public $user_fbusername;
        public $weight;
	public $pickuped;
	public $delivered;
	public $accepted;
	public $pickup_confirmation_number;
	public $delivery_confirmation_number;
        
        public $pay_from_booker;
        public $pay_for_shipper;
        
        public $booker_payment_token;
        
        public $viewed_by_shipper;
        public $viewed_by_booker;
        public $fbusername;
        public $email;
}
/**
 *
 *
 */
class BookingDAO extends ConnectDB 
{
    //TODO - Insert your code here
    function BookingDAO() {
	
    }

    public static function pulloutBooker($row) 
    {
	$booking = new BookingDTO();

	$booking->booking_id = $row['booking_id'];
	if(isset($row['user_fbusername']))
		$booking->user_fbusername = $row['user_fbusername'];
        
	$booking->space_id = $row['space_id'];
        
    if(isset($row['name']))
		$booking->name = $row['name'];
	$booking->weight = floatval($row['weight']);
	$booking->pickuped = intval($row['pickuped']);
	$booking->delivered = intval($row['delivered']);
	$booking->pickup_confirmation_number = $row['pikup_confirmation_number'];
	$booking->delivery_confirmation_number = $row['delivery_confirmation_number'];
        
    $booking->pay_for_shipper = intval($row['pay_for_shipper']);
	$booking->pay_from_booker = intval($row['pay_from_booker']);
        
    $booking->booker_payment_token = $row['booker_payment_token'];
        
	$booking->accepted = intval($row['accepted']);
	$booking->viewed_by_shipper = $row['viewed_by_shipper'];
	$booking->viewed_by_booker = $row['viewed_by_booker'];
	
	if(isset($row['fbusername']))
		$booking->fbusername = $row['fbusername'];
	if(isset($row['email']))
		$booking->email = $row['email'];

	return $booking;
    }

    public static function addBooking(BookingDTO $booking)
    {
	$result = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
            
            $now = date("Y-m-d H:i:s");
            
	    $strSQL = "INSERT INTO `booking`(`id`, `space_id`, `user_fbusername`, `weight`,`booker_payment_token`, `estimated_price` , `date_created`) VALUES (null,'$booking->space_id','$booking->user_fbusername','$booking->weight', '$booking->booker_payment_token', '$booking->estimated_price', '$now');";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
            $id = mysql_insert_id();
            
            $code = substr(md5($id), 16, 32);
            $strSQL = "UPDATE booking set booking_id = '" . $code . "' where id='$id'";
            $result = mysql_query($strSQL, ConnectDB::$mLink);
            
	    ConnectDB::CloseConnection();

	    return $code;
	} 
        catch (Exception $e) 
        {
	    return FALSE;
	}
    }

    public static function confirmBooking($bid) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update booking set accepted = '1' where booking_id = '$bid'";
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function pickupBooking($bid) 
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update booking set pickuped = '1' where booking_id = '$bid'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function deliveryBooking($bid) {
		$result = TRUE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "Update booking set delivered = '1' where booking_id = '$bid'";
	            
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    ConnectDB::CloseConnection();
	
		    return $result;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
    
    public static function updateConfirmationNumber($bid, $pickupConfirmationNumber, $deliveryConfimationNumber) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Update booking set accepted = '1', pikup_confirmation_number = '$pickupConfirmationNumber', delivery_confirmation_number = '$deliveryConfimationNumber' where booking_id = '$bid'";
//	    echo $strSQL; exit;
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateViewed($bid, $who)
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            if($who == "booker")
                $strSQL = "Update booking set viewed_by_booker = '1' where booking_id = '$bid'";
            else
                if($who == "shipper")
                    $strSQL = "Update booking set viewed_by_shipper = '1' where booking_id = '$bid'";
                
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateChargeCancelFee($bid, $who, $value)
    {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            if($who == "booker")
                $strSQL = "Update booking set pay_from_booker = '$value' where booking_id = '$bid'";
            else
                if($who == "shipper")
                    $strSQL = "Update booking set pay_for_shipper = '$value' where booking_id = '$bid'";
                
	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return $result;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function getOrderWeightTotal($space_id, $type) 
    {
	$totalweight = 0;

	try {
	    if (!ConnectDB::OpenConnection())
		return $totalweight;
	    
	    $strSQL = "select * from booking where space_id = '$space_id'";
	    
	    if($type == "past")
		$strSQL .= " and accepted = '1'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    //echo $strSQL;
	    
	    if ($result == false || mysql_num_rows($result) <= 0) 
	    {
                return $totalweight;
            }
	    
	    while ($row = mysql_fetch_array($result)) 
	    {
                $totalweight += $row['weight'];
            }
		    
	    ConnectDB::CloseConnection();
	 
	    return $totalweight;
	} catch (Exception $e) {
	    return $totalweight;
	}
    }
    
    public static function getOrderQuantityTotal($space_id, $type) 
    {
	$totalquantity = 0;

	try {
	    if (!ConnectDB::OpenConnection())
		return $totalquantity;
	    
	    $strSQL = "select * from booking where space_id = '$space_id'";
	    
	    if($type == "past")
		$strSQL .= " and accepted = '1'";
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    //echo $strSQL;
	    
	    if ($result == false || mysql_num_rows($result) <= 0) 
	    {
                return $totalquantity;
            }
	    
	    while ($row = mysql_fetch_array($result)) 
	    {
                $totalquantity += $row['quantity'];
            }
		    
	    ConnectDB::CloseConnection();
	 
	    return $totalquantity;
	}
        catch (Exception $e) 
        {
	    return $totalquantity;
	}
    }

    public static function getBookedUser_WithSpaceID($space_id, $type, $delivered=null) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select booking.*, fbusername, name, email, weight, accepted, space_id from booking, user where space_id = '$space_id' and user_fbusername = fbusername";
	    
	    if($type == "past")
                $strSQL .= " and accepted = '1'";
		
            if(isset($delivered))
                $strSQL .= " and delivered = '$delivered'";
	    
	    $strSQL .= " order by accepted desc";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getBookedOfUser($fbusername, $delivered=null) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
            {
                return FALSE;
            }
	    
            $strSQL = "select b.* from booking b join space s on b.space_id = s.space_id "
                    . "where s.user_fbusername = '$fbusername' and accepted = '1' ";
            
            if(isset($delivered))
                $strSQL .= " and b.delivered = '$delivered'";
            
//            echo "2. $strSQL  <br/>";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getNewBooking_WithSpaceID($fbusername, $type) 
    {
        $list = array();
        
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select space.space_id, count(booking_id) as total from booking, space where viewed_by_shipper = '0' and space.space_id = booking.space_id and space.user_fbusername = '$fbusername'";
	    
	    if($type == "past")
		$strSQL .= " and accepted = '1'";
	    
	    $strSQL .= "  group by space.space_id";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
            
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $list;
            }
            
            
            while ($row = mysql_fetch_array($result))
            {
                $list[$row['space_id']] = $row['total'];
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return $list;
        }

        return $list;
    }
    
    public static function getNewAcceptedBooking($fbusername, $type) 
    {
        $list = array();
        
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select space.space_id, count(booking_id) as total from booking, space where viewed_by_booker = '0' and space.space_id = booking.space_id and accepted = '1' and booking.user_fbusername = '$fbusername'";
	    $strSQL .= "  group by space.space_id";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
            
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $list;
            }
            
            
            while ($row = mysql_fetch_array($result))
            {
                $list[$row['space_id']] = $row['total'];
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return $list;
        }

        return $list;
    }
    
    
    public static function checkingUserBookedSpace($fbusername, $space_id) 
    {
	$rs = false;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from booking where user_fbusername = '$fbusername' and space_id = '$space_id' ";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $rs;
            }

            while ($row = mysql_fetch_array($result))
	    {
                $rs = true;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $rs = FALSE;
        }

        return $rs;
    }
    
    
    /**
    * Hàm này dùng trong Myprofile, Có xét điều kiện đã giao hàng hay chưa
    * 
    * @link http://vakargo.com/
    */
    public static function getItemOfUser($fbusername, $type, $start, $limit) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select booking.*, space_id, weight, accepted, fbusername, name, email from booking, user where user_fbusername = '$fbusername' and fbusername = user_fbusername ";
	    
	    if($type == "delivered")
		$strSQL .= " and delivered = '1' and accepted = '1'";
	    else
		$strSQL .= " and delivered = '0' and accepted = '1'";	    
            
	    $strSQL .= " order by date_created desc LIMIT $start, $limit";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    /**
    * Hàm này dùng trong Kiểm tra review, lấy tất cả các booking đã được accept
    * 
    * @link http://vakargo.com/
    */
    public static function getAllItemsOfUser($fbusername) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from booking where user_fbusername = '$fbusername' and accepted = '1' and pickuped = '1' and delivered = '1'";
	    
//            echo "1. $strSQL <br/>";
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    /**
    * Lấy danh sách các booking đến ngày và qua ngày pickup code, và trước 14 ngày sau delivery date
    * Để nhập pickup code
    * Dùng trong header.php
    * 
    * @link http://vakargo.com/
    */
    public static function getBookingsOfUser_OnPickupDate($fbusername, $currentDate, $pickup = null) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select b.* from booking b, space s where b.space_id = s.space_id and b.user_fbusername = '$fbusername' and DATEDIFF('$currentDate',s.delivery_date) < 14 and DATEDIFF('$currentDate',s.pickup_date) >= 0 and accepted = '1' ";
            
            if(isset($pickup) && $pickup != null)
                $strSQL .= " and pickuped = '$pickup' ";
                
	    $strSQL .= " order by b.date_created desc";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getTotalNumberBookOfUser($fbusername, $type) 
    {
	$total = 0;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
	    $strSQL = "select count(*) as total from booking where user_fbusername = '$fbusername'";
	    
	    if($type == "delivered")
		$strSQL .= " and delivered = '1' and accepted = '1'";
	    else
		$strSQL .= " and delivered = '0' and accepted = '1'";
	    
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
    
    public static function getBookingItem($book_id) 
    {
	$rs = null;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from booking, user where booking_id = '$book_id' and user_fbusername = fbusername";
	    //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $rs;
            }

            while ($row = mysql_fetch_array($result))
	    {
                $rs = BookingDAO::pulloutBooker($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $rs;
    }
    
    public static function checkingDeliveryCode( $book_id, $delivery_code) 
    {
	$rs = false;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from booking where delivery_confirmation_number = '$delivery_code' and booking_id = '$book_id' ";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
            	ConnectDB::CloseConnection();
                return $rs;
            }

            while ($row = mysql_fetch_array($result))
	    	{
                $rs = true;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
        	ConnectDB::CloseConnection();
            $rs = FALSE;
        }

        return $rs;
    }
    
    public static function checkingPickupCode( $book_id, $pickup_code) 
    {
	$rs = false;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from booking where `pikup_confirmation_number` = '$pickup_code' and booking_id = '$book_id' ";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
            	ConnectDB::CloseConnection();
                return $rs;
            }

            while ($row = mysql_fetch_array($result))
	    	{
                $rs = true;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
        	ConnectDB::CloseConnection();
            $rs = FALSE;
        }

        return $rs;
    }
    
  /**
    * Hàm này dùng trong Kiểm tra review, lấy tất cả các booking đã được accept mà space đã quá 14 ngày vẫn chưa delivered
    * 
    * @link http://vakargo.com/
    */
    public static function getAllItemsOfUser_SpaceOutOf14days($fbusername, $currentdate) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
            {
                return FALSE;
            }
	    
            $strSQL = "select * from booking b join space s on b.space_id = s.space_id and b.delivered='0' and DATEDIFF('$currentdate',s.delivery_date) > 14 "
                    . "where b.user_fbusername = '$fbusername' and accepted = '1' and delivered = '0'";
	    
//            echo "3. $strSQL  <br/>";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getAllItemsOfSpaceOfUser_OutOf14days($fbusername, $currentdate) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
            {
                return FALSE;
            }
	    
            $strSQL = "select b.* from booking b join space s on b.space_id = s.space_id and b.delivered='0' and DATEDIFF('$currentdate',s.delivery_date) > 14 "
                    . "where s.user_fbusername = '$fbusername' and accepted = '1' and delivered = '0'";
	    
//            echo "4. $strSQL  <br/>";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getAllbookings()
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
            {
                return $ds;
            }
	    
            $strSQL = "select * from booking";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = $ds;
        }

        return $ds;
    }
    
    public static function getMyBookings($userid, $start = 0, $limit = 0)
    {
	$ds = array();
        try 
        {
            if (!ConnectDB::OpenConnection())
            {
                return $ds;
            }
	    
            if($start == 0 && $limit == 0)
                $strSQL = "select * from booking where user_fbusername = '$userid'";
            else
                $strSQL = "select * from booking where user_fbusername = '$userid' LIMIT $start, $limit;";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, BookingDAO::pulloutBooker($row));
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = $ds;
        }

        return $ds;
    }
    
    public static function getTotalMyBookings($userid)
    {
	$total = 0;
        try 
        {
            if (!ConnectDB::OpenConnection())
            {
                return $total;
            }
	    
            $strSQL = "select count(*) as total from booking where user_fbusername = '$userid'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $total;
            }

            while ($row = mysql_fetch_array($result))
            {
                $total = $row['total'];
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $total = 0;
        }

        return $total;
    }
}

?>