<?php

require_once 'ConnectDB.php';


class SubscriberDTO {

    public $subscribeid;
    public $email;
    public $dateadded;
    public $isconfirmed;

}
/**
 *
 *
 */
class SubscriberDAO extends ConnectDB {

    //TODO - Insert your code here
    function SubscriberDAO() {
        
    }

    public static function isConfirmedSubscriber($email) {
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from subscriber where email = '$email'";
            //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }
            $row = mysql_fetch_array($result);

            $userDTO = SubscriberDAO::pulloutSubscriber($row);

            ConnectDB::CloseConnection();

            return $userDTO->isconfirmed;
        } catch (Exception $e) {
            return FALSE;
        }
        return $ds;
    }

    public static function getAllSubscriber() {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from subscriber";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, SubscriberDAO::pulloutSubscriber($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }

        return $ds;
    }

    public static function getSubscriberWithEmail($email) {

        $user = null;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from subscriber where email = '$email'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                $user = SubscriberDAO::pulloutSubscriber($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return $user;
        }

        return $user;
    }

    public static function pulloutSubscriber($row) {
        $subscriber = new SubscriberDTO();

        $subscriber->email = $row['email'];
        $subscriber->dateadded = $row['dateadded'];
        $subscriber->isconfirmed = $row['isconfirmed'];
        $subscriber->subscribeid = $row['subscribeid'];

        return $subscriber;
    }

    public static function addSubscriber(SubscriberDTO $user) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `subscriber`(`subscribeid`, `email`, `dateadded`, `isconfirmed`) VALUES ('','$user->email', '$user->dateadded', '1');";
            //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
   public static function removeSubscriber($email) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "UPDATE subscriber set isconfirmed = '0' where email = '$email'";
            //echo $strSQL;
            //$strSQL = "DELETE from subscriber where email = '$email'";
            //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }

    public static function confirmEmail($email) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "Update subscriber set isconfirmed = '1' where email = '$email'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }

}

?>