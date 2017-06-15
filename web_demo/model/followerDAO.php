<?php

require_once 'ConnectDB.php';

class FollowerDTO {

    public $followerid;
    public $email;
    public $firstname;
    public $lastname;
    public $dateadded;
    public $isconfirmed;

}

/**
 *
 *
 */
class FollowerDAO extends ConnectDB {

    //TODO - Insert your code here
    function FollowerDAO() {
        
    }

    public static function isConfirmedFollower($email) {
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from follower where email = '$email'";
            //echo $strSQL;
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }
            $row = mysql_fetch_array($result);

            $userDTO = FollowerDAO::pulloutFollower($row);

            ConnectDB::CloseConnection();

            return $userDTO->isconfirmed;
        } catch (Exception $e) {
            return FALSE;
        }
        //return $ds;
    }

    public static function getAllFollower() {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from follower";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return FALSE;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, FollowerDAO::pulloutFollower($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return FALSE;
        }

        return $ds;
    }

    public static function getFollowerWithEmail($email) {

        $user = null;

        try {
            if (!ConnectDB::OpenConnection())
                return $user;

            $strSQL = "SELECT * from follower where email = '$email'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $user;
            }

            while ($row = mysql_fetch_array($result)) {
                $user = FollowerDAO::pulloutFollower($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return $user;
        }

        return $user;
    }

    public static function pulloutFollower($row) {
        $follower = new FollowerDTO();

        $follower->email = $row['email'];
        $follower->dateadded = $row['dateadded'];
        $follower->isconfirmed = $row['isconfirmed'];
        $follower->firstname =  $row['firstname'];
        $follower->lastname =  $row['lastname'];
        $follower->followerid = $row['followerid'];

        return $follower;
    }

    public static function addFollower(FollowerDTO $user) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `follower`(`followerid`, `email`, `firstname`, `lastname`, `dateadded`, `isconfirmed`) VALUES ('','$user->email', '$user->firstname', '$user->lastname','$user->dateadded', '1');";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
   public static function removeFollower($email) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "UPDATE follower set isconfirmed = '0' where email = '$email'";
            //echo $strSQL;
            //$strSQL = "DELETE from follower where email = '$email'";
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

            $strSQL = "Update follower set isconfirmed = '1' where email = '$email'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }
}

?>