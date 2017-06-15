<?php

require_once 'ConnectDB.php';

class FeedbackDTO {

    public $id;
    public $email;
    public $name;
    public $content;
}

/**
 *
 *
 */
class FeedbackDAO extends ConnectDB {

    //TODO - Insert your code here
    function FeedbackDAO() {
        
    }

 

    public static function addFeedback(FeedbackDTO $user) {
        $result = TRUE;

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "INSERT INTO `feedback`(`id`, `name`, `email`, `content`) VALUES (null,'$user->name', '$user->email', '$user->content');";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            ConnectDB::CloseConnection();

            return $result;
        } catch (Exception $e) {
            return FALSE;
        }
    }
    
  
    
}

?>