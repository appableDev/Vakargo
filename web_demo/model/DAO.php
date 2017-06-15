<?php
include_once ('ConnectDB.php');
require_once ('citiesDAO.php');
require_once ('userDAO.php');
require_once ('spaceDAO.php');
require_once ('flightDAO.php');
require_once ('airlineDAO.php');
require_once ('subscriberDAO.php');
require_once ('followerDAO.php');
require_once ('space_categoryDAO.php');
require_once ('bookingDAO.php');
require_once ('flightstatsDAO.php');
require_once ('fbfriendDAO.php');
require_once ('reviewDAO.php');
require_once ('codeDAO.php');
require_once ('feedbackDAO.php');
require_once ('eventDAO.php');
require_once ('messageDAO.php');
require_once ('Result.php');
require_once ('public_model.php');
require_once ('customerDAO.php');
require_once ('socialDAO.php');
require_once ('customfeesDAO.php');
require_once ('checkinDAO.php');

require_once ('answersurveyDAO.php');
require_once ('userDublinDAO.php');

class DAO {

    static function raiseResultTRUEorFALSE($rs) {
        $tt = new Result();
        if ($rs == FALSE) {
            $tt->result = -1;
            echo json_encode($tt);
        } else {
            $tt->result = 1;
            echo json_encode($tt);
        }
    }
    static function raiseResultTRUEorFALSEwithMessage($rs, $message) {
        $tt = new Result();
        if ($rs == FALSE) {
            $tt->result = -1;
            $tt->message = $message;
            echo json_encode($tt);
        } else {
            $tt->result = 1;
            $tt->message = $message;
            echo json_encode($tt);
        }
    }
    
    static function raiseResultError($rs) {
        $tt = new Result();
        $tt->result = $rs;
        echo json_encode($tt);
    }
    
    static function raiseResultErrorWithMessage($rs, $message) 
    {
        $tt = new Result();
        $tt->result = $rs;
        $tt->message = $message;
        echo json_encode($tt);
    }

    static function raiseResult($rs) {
        $tt = new Result();
        if ($rs == FALSE) {
            $tt->result = $rs;
            echo json_encode($tt);
        } else {
            if ($rs != null && count($rs) > 0) {
                $tt->result = $rs;
                echo json_encode($tt);
            } else {
                $tt->result = $rs;
                echo json_encode($rs);
            }
        }
    }
    
    static function raiseResultAddCallback($rs, $callback) {
        $tt = new Result();
        if ($rs == FALSE) 
	{
            $tt->result = $rs;
	    $tt->callback = $callback;
            echo json_encode($tt);
        } else {
            if ($rs != null && count($rs) > 0) {
                $tt->result = $rs;
		$tt->callback = $callback;
                echo json_encode($tt);
            } else {
                $tt->result = $rs;
		$tt->callback = $callback;
                echo json_encode($rs);
            }
        }
    }

}

?>