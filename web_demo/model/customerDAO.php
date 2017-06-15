<?php

require_once 'ConnectDB.php';



class CustomerDTO
{
        public $cus_id;
	public $user_id;
	public $customer_id;
	public $name;
	public $last4;
	public $type;
	public $exp_month;
	public $exp_year;
	public $default;
}
/**
 *
 *
 */
 class CustomerDAO extends ConnectDB {

    //TODO - Insert your code here
    function CustomerDAO() {
	
    }
    
    public static function pulloutCustomer($row) 
    {
        $DTO = new CustomerDTO();

        $DTO->cus_id =  $row['cus_id'];
        $DTO->user_id = $row['user_id'];
        $DTO->customer_id = $row['customer_id'];
        $DTO->name = $row['name'];
        $DTO->last4 = $row['last4'];
        $DTO->type = $row['type'];
        $DTO->exp_month = $row['exp_month'];
        $DTO->exp_year = $row['exp_year'];
        $DTO->default = $row['default'];

        return $DTO;
    }
    
    public static function addCustomer(CustomerDTO $cus) 
    {
		$result = TRUE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
		    
		    $strSQL = "INSERT INTO `customer` (`user_id`, `customer_id`, `name`, `last4`, `type`, `exp_month`, `exp_year`, `default`) "
	                    . "VALUES ('$cus->user_id', '$cus->customer_id', '$cus->name', '$cus->last4', '$cus->type', '$cus->exp_month', '$cus->exp_year', '$cus->default');";
	        //Globals::Write_LogInfo($strSQL);
	        $result = mysql_query($strSQL, ConnectDB::$mLink);
			
			//DELETE DEFAULT
			if($cus->default=="1")
			{
			    $strSQL = "UPDATE `customer` SET `default` = '0' WHERE `user_id` = '$cus->user_id' AND `customer_id` != '$cus->customer_id' ";
			    $result = mysql_query($strSQL, ConnectDB::$mLink);
			}
		    
		    ConnectDB::CloseConnection();
	
		    return $result;
		} catch (Exception $e) {
		    ConnectDB::CloseConnection();
		    return FALSE;
		}
    }
    
    public static function update_CustomerDefault($uID,$cusID)
    {
		$result = TRUE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
			//SET DEFAULT
		    $strSQL = "UPDATE `customer` SET `default` = '1' WHERE `user_id` = '$uID' AND `customer_id` = '$cusID' ";
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    
		    //DELETE DEFAULT
		    $strSQL = "UPDATE `customer` SET `default` = '0' WHERE `user_id` = '$uID' AND `customer_id` != '$cusID' ";
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    ConnectDB::CloseConnection();
	
		    return true;
		} catch (Exception $e) {
		    ConnectDB::CloseConnection();
		    return FALSE;
		}
    }
    
    public static function update_CustomerExpDate($uID,$cusID,$expMonth,$expYear)
    {
		$result = TRUE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "UPDATE `customer` SET `exp_month` = '$expMonth',`exp_year` = '$expYear' WHERE `user_id` = '$uID' AND `customer_id` = '$cusID' ";
		    //Globals::Write_LogInfo($strSQL);
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    ConnectDB::CloseConnection();
	
		    return true;
		} catch (Exception $e) {
		    ConnectDB::CloseConnection();
		    return FALSE;
		}
    }

    public static function deleteCustomer($userID, $cusID)
    {
		$result = FALSE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
		    $strSQL = "Delete from `customer` where user_id='$userID' and customer_id = '$cusID'";
		    
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    
		    ConnectDB::CloseConnection();
			return TRUE;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
   
    public static function deleteCustomer_AllofACustomer($userID)
    {
		$result = FALSE;
	
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
		    $strSQL = "Delete from `customer` where user_id='$userID'";
		    
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
		    
		    ConnectDB::CloseConnection();
			return TRUE;
		} catch (Exception $e) {
		    return FALSE;
		}
    }
    
    public static function getCustomerByUserID($userid) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            $strSQL = "SELECT * from `customer` where `user_id` = '$userid' order by cus_id DESC;";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, CustomerDAO::pulloutCustomer($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }

        return $ds;
    }
    
    public static function getCustomer_ByCusID($cus_id) {
        $ds = null;
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            $strSQL = "SELECT * from `customer` where `cus_id` = '$cus_id'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }

            while ($row = mysql_fetch_array($result)) {
                 $ds = CustomerDAO::pulloutCustomer($row);
                 return $ds;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }
    }
    
    public static function getCustomer_ByCustomerID($cus_id) {
        $ds = null;
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            $strSQL = "SELECT * from `customer` where `customer_id` = '$cus_id'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }

            while ($row = mysql_fetch_array($result)) {
                 $ds = CustomerDAO::pulloutCustomer($row);
                 return $ds;
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }
    }
    
    public static function getTopCustomerIdByUserID($userid) 
    {
        $cus = null;
		try {
		    if (!ConnectDB::OpenConnection())
			return null;
	
		    $strSQL = "SELECT * from `customer` where `user_id` = '$userid' AND `default` = '1' LIMIT 0,1";
                    
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return null;
		    }
	
		    while ($row = mysql_fetch_array($result)) {
		    	
			$cus = CustomerDAO::pulloutCustomer($row);
		    }

		    ConnectDB::CloseConnection();
		    return $cus;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return $cus;
		}
	
		return $cus;
    }
    
    public static function checkUserExistOnSpaceOrBook($userid) 
    {
		try {
		    if (!ConnectDB::OpenConnection())
			return FALSE;
	
		    $strSQL = "SELECT sp.`user_fbusername` FROM `space` sp
						LEFT JOIN `user` u ON u.`fbusername`=sp.`user_fbusername`
						WHERE u.`fbid`='$userid'
						UNION ALL
						SELECT bk.`user_fbusername` FROM `booking` bk
						LEFT JOIN `user` u ON u.`fbusername`=bk.`user_fbusername`
						WHERE u.`fbid`='$userid'";
	
		    $result = mysql_query($strSQL, ConnectDB::$mLink);
	
		    if ($result == false || mysql_num_rows($result) <= 0) {
		    	ConnectDB::CloseConnection();
				return FALSE;
		    }

		    ConnectDB::CloseConnection();
		    return TRUE;
		} catch (Exception $e) {
			ConnectDB::CloseConnection();
		    return FALSE;
		}
    }
 }
