<?php

require_once 'ConnectDB.php';



class MessageDTO
{
	public $mess_id;
	public $from;
	public $to;
    public $header;
	public $subject;
	public $content;
	public $sendate;
    public $belong;
	public $star;
	public $read;
	public $responded;
	public $reservations;
	public $deleted_from;
	public $deleted_to;
}
/**
 *
 *
 */
class MessageDAO extends ConnectDB {

    //TODO - Insert your code here
    function MessageDAO() {
	
    }
    
    public static function pulloutMessage($row) 
    {
		$DTO = new MessageDTO();
	
		$DTO->mess_id = $row['mess_id'];
		$DTO->from = $row['from'];
		$DTO->to = $row['to'];
                $DTO->header = $row['header'];
		$DTO->subject = $row['subject'];
		$DTO->content = $row['content'];
		$DTO->sendate = $row['senddate'];
                $DTO->belong = $row['belong'];
		$DTO->star = $row['star'];
		$DTO->read = $row['read'];
		$DTO->responded = $row['responded'];
		$DTO->reservations = $row['reservations'];
		$DTO->deleted_from = $row['deleted_from'];
		$DTO->deleted_to = $row['deleted_to'];
		
		
		return $DTO;
    }
    
    public static function getMessageById($mess_id) 
    {
        $rs = null;
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            $strSQL = "SELECT * from message where `mess_id` = '$mess_id'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }

            while ($row = mysql_fetch_array($result)) {
                $rs=MessageDAO::pulloutMessage($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }

        return $rs;
    }
    
    public static function getMessage_BELONG($belong,$read=null,$deleted_to=null, $sortBy=null) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
			
			if(!isset($deleted_to) || empty($deleted_to)) $deleted_to=0;
				
	    	$strSQL = "SELECT * from message where `belong`='$belong' ";
	    	if(isset($read))
            {
            	$strSQL.=" and `read`='$read'";
            }
            if(isset($deleted_to))
            {
            	$strSQL.=" and `deleted_to`='$deleted_to'";
            }
            if(isset($sortBy) && $sortBy=="oldest")
	    	{
	    		$strSQL .= " ORDER BY `senddate` ";
	    	}
	    	else
	    	{
	    		$strSQL .= " ORDER BY `senddate` DESC ";
	    	}
	    	//Globals::Write_LogInfo($strSQL);
	    	$result = mysql_query($strSQL, ConnectDB::$mLink);
	
            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return FALSE;
            }
            while ($row = mysql_fetch_array($result)) {
        	array_push($ds, MessageDAO::pulloutMessage($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }

        return $ds;
    }
    
    public static function getMessage_TO_GroupBySpaceOrBook($userid,$read=null,$deleted_to=null, $sortBy=null) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            if(!isset($deleted_to) || empty($deleted_to)) $deleted_to=0;
            
            //$strSQL = "SELECT DISTINCT `from`,`to`,`belong` from message where `to` = '$userid'"; //and `deleted_to`='0'";
            $strSQL = "SELECT DISTINCT `from`,`to`,`belong` from message where `to` = '$userid'"; //and `deleted_to`='0'";
            if(isset($read))
            {
            	$strSQL.=" and `read`='$read'";
            }
            if(isset($deleted_to))
            {
            	$strSQL.=" and `deleted_to`='$deleted_to'";
            }
            if(isset($sortBy) && $sortBy=="oldest")
	    	{
	    		$strSQL.=" order by `from`,`to`,`belong`";
	    	}
	    	else
	    	{
	    		$strSQL.=" order by `from`,`to`,`belong` DESC";
	    	}
            
			//Globals::Write_LogInfo($strSQL);
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }

            while ($row = mysql_fetch_array($result)) {
            	$from=$row['from'];
            	$belong=$row['belong'];
            	$strSQL = "SELECT * from message where `from` = '$from' and `belong`='$belong' ORDER BY `senddate`  LIMIT 0,1";
//            	if(isset($sortBy) && $sortBy=="oldest")
//            	{
//            		$strSQL .= " ORDER BY `senddate`  LIMIT 0,1 ";
//            	}
//            	else
//            	{
//            		$strSQL .= " ORDER BY `senddate` DESC  LIMIT 0,1 ";
//            	}
            	//Globals::Write_LogInfo($strSQL);
            	$resultsub = mysql_query($strSQL, ConnectDB::$mLink);

	            if ($resultsub == false || mysql_num_rows($resultsub) <= 0) {
	                ConnectDB::CloseConnection();
	                return null;
	            }
	            while ($rowsub = mysql_fetch_array($resultsub)) {
                	array_push($ds, MessageDAO::pulloutMessage($rowsub));
	            }
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }

        return $ds;
    }

    public static function getMessage_TO($userid) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from message where `to` = '$userid' and `deleted_to`='0'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, MessageDAO::pulloutMessage($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }

        return $ds;
    }
    
    public static function getMessage_FROM($userid) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from message where `from` = $userid and `deleted_from`='0'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, MessageDAO::pulloutMessage($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }

        return $ds;
    }

    public static function addMessage(MessageDTO $m) 
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `message` (`mess_id`, `from`, `to`, `content`, `header`, `subject`, `senddate`, `belong`) "
                    . "VALUES (NULL, '$m->from', '$m->to', '$m->content', '$m->header', '$m->subject', '$m->sendate', '$m->belong');";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
    
    public static function update_viewedMessage($messID)
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "Update message set read = '1' where mess_id = '$messID'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
    
    public static function updateMessageByMsgIdnFlag($messID,$flagName, $flagValue)
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "Update message set `$flagName` = '$flagValue' where mess_id = '$messID'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }

	public static function updateMessageByBelongnFlag($belong,$flagName, $flagValue)
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "Update message set `$flagName` = '$flagValue' where belong = '$belong'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
        
    public static function getTotalMessageByFIdnFlag($fid,$flagName=null, $flagValue=null)
    {
        $totalMSG = 0;

        try {
            if (!ConnectDB::OpenConnection())
                return $totalMSG;

	        $strSQL = "";
	        if(!isset($flagName) || $flagName=="")
	           	$strSQL = "SELECT DISTINCT `belong` FROM message WHERE `to`='$fid' AND `deleted_to`='0' ";
	        else if(isset($flagName) && $flagName=="`read`")
	           	$strSQL = "SELECT DISTINCT `belong` FROM message WHERE `to`='$fid' AND $flagName = '$flagValue' AND `deleted_to`='0' ";
	        else
	        	$strSQL = "SELECT DISTINCT `belong` FROM message WHERE `to`='$fid' AND $flagName = '$flagValue' ";
			//Globals::Write_LogInfo($strSQL);
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
            	$totalMSG=0;
            }
            else
            {
                $totalMSG = mysql_num_rows($result);
            }
            ConnectDB::CloseConnection();
            return $totalMSG;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return $totalMSG;
        }
    }

}
?>