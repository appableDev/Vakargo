<?php
require_once 'ConnectDB.php';



class SocialDTO
{
    public $owner_username;
    public $social_type;
    public $social_userid;
    public $social_username;
    public $friends;
    public $followers;
    public $following;
    public $connections;
    public $social_score;
}
/**
 *
 *
 */
 class SocialDAO extends ConnectDB {

    //TODO - Insert your code here
    function SocialDAO() {
	
    }
    
    public static function pulloutSocial($row) 
    {
        $DTO = new SocialDTO();

        $DTO->owner_username = $row['owner_username'];
        $DTO->social_type = $row['social_type'];
        $DTO->social_userid = $row['social_userid'];
        $DTO->social_username = $row['social_username'];
        $DTO->friends = $row['friends'];
        $DTO->followers = $row['followers'];
        $DTO->following = $row['following'];
        $DTO->connections = $row['connections'];
        $DTO->social_score = $row['social_score'];
		
        return $DTO;
    }
    
    public static function getSocialByOwnernSocialType($owner_username, $social_type) 
    {
        $rs = null;
        try {
            if (!ConnectDB::OpenConnection())
                return null;

            $strSQL = "SELECT * from `social` where `owner_username` = '$owner_username' AND `social_type`='$social_type'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);
	
            if ($result == false || mysql_num_rows($result) <= 0) {
                ConnectDB::CloseConnection();
                return null;
            }
            while ($row = mysql_fetch_array($result)) {
        		$rs=SocialDAO::pulloutSocial($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return null;
        }

        return $rs;
    }
    
    public static function deleteSocialByOwnernSocialType($owner_username, $social_type) 
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "DELETE from `social` where `owner_username` = '$owner_username' AND `social_type`='$social_type'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
    
    public static function addSocial(SocialDTO $social) 
    {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `social` (`owner_username`,`social_type`, `social_userid`, `social_username`, `friends`, `followers`, `following`, `connections`, `social_score`) "
                    . "VALUES ('$social->owner_username', '$social->social_type', '$social->social_userid', '$social->social_username', '$social->friends', '$social->followers', '$social->following', '$social->connections', '$social->social_score');";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            ConnectDB::CloseConnection();
            return FALSE;
        }
    }
 }
?>
