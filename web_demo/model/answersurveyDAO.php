<?php

require_once 'ConnectDB.php';



class AnswerSurveyDTO
{
    public $answer_id;
    public $question_id;
    public $user_id;
    public $answer;
}

/**
 *
 *
 */
class AnswerSurveyDAO extends ConnectDB {

    function AnswerSurveyDAO() {
	
    }

    public static function pullout($row) 
    {
	$booking = new ReviewDTO();

	$booking->answer_id = $row['answer_id'];
	$booking->question_id = $row['question_id'];
	$booking->user_id = $row['user_id'];
	$booking->answer = $row['answer'];

	return $booking;
    }

    public static function getAnswerOfUser($fbusername) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from survey_answers where user_id = '$fbusername'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, AnswerSurveyDAO::pullout($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
}

?>