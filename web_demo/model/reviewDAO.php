<?php

require_once 'ConnectDB.php';



class ReviewDTO
{
    public $review_id;
    public $reviewfrom;
    public $reviewto;
    public $mark;
    public $comment;
    public $date;
    
    public $bookingid;
}


    
class TotalAndCountReview
{
    public $sum;
    public $count;
}
    
/**
 *
 *
 */
class ReviewDAO extends ConnectDB {

    function ReviewDAO() {
	
    }

    public static function pullout($row) 
    {
	$booking = new ReviewDTO();

	$booking->review_id = $row['review_id'];
	$booking->reviewfrom = $row['reviewfrom'];
	$booking->reviewto = $row['reviewto'];
	$booking->comment = $row['comment'];
        $booking->bookingid = $row['forbooking_id'];
	$booking->date = $row['date'];
	$booking->mark = $row['mark'];

	return $booking;
    }

    public static function addReview(ReviewDTO $rv)
    {
	$result = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $strSQL = "INSERT INTO `reviews`(`review_id`, `reviewfrom`, `reviewto`, `forbooking_id`, `mark`, `comment`, `date`) VALUES (null, '$rv->reviewfrom','$rv->reviewto','$rv->bookingid','$rv->mark', '$rv->comment', '$rv->date')";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function updateReviewToZero($booking_id, $reviewto)
    {
	$result = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $strSQL = "UPDATE `reviews` SET  `mark` =  '0' WHERE  `forbooking_id` ='$booking_id' and reviewto = '$reviewto';";
            
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    $id = mysql_insert_id();
	    
	    ConnectDB::CloseConnection();

	    return $id;
	} catch (Exception $e) {
	    return FALSE;
	}
    }

    public static function deleteReviewOfUsername($username)
    {
	$result = FALSE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;
	    $strSQL = "Delete from `reviews` where reviewto = '$username'";
	    
	    
	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    
	    ConnectDB::CloseConnection();

	} catch (Exception $e) {
	    return FALSE;
	}
    }
   
    public static function getReviewsOfUser($fbusername, $start, $limit) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewto = '$fbusername' and mark > 0 LIMIT $start, $limit";

            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, ReviewDAO::pullout($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getAllReviewsOfUser_forbookingid($fbusername, $bid) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewto = '$fbusername' and forbooking_id = '$bid'";
		    
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, ReviewDAO::pullout($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getReviewForBooker_withBookingID($fbusername, $booking_id) 
    {
	$rw = null;
		
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewto = '$fbusername' and forbooking_id = '$booking_id'";	    
            //echo $strSQL;
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $rw;
            }

            while ($row = mysql_fetch_array($result))
            {
                $rw = ReviewDAO::pullout($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $rw = null;
        }

        return $rw;
    }
    
    public static function getReviewOfUserwithBookingID($fbusername, $booking_id) 
    {
	$rw = null;
		
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewfrom = '$fbusername' and forbooking_id = '$booking_id'";	    
            //echo $strSQL;
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $rw;
            }

            while ($row = mysql_fetch_array($result))
            {
                $rw = ReviewDAO::pullout($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $rw = null;
        }

        return $rw;
    }
    
    public static function getReview_OfUser_ForUser_withBookingID($from, $to, $booking_id) 
    {
	$rw = null;
		
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewfrom = '$from' and reviewto = '$to' and forbooking_id = '$booking_id'";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $rw;
            }

            while ($row = mysql_fetch_array($result))
            {
                $rw = ReviewDAO::pullout($row);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $rw = null;
        }

        return $rw;
    }
    
    
    public static function getTotalReviewOfUser($fbusername) 
    {
	$total = 0;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	   
	    $strSQL = "select count(*) as total from reviews where reviewto = '$fbusername'";
	    
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
    
    public static function getTotalAndCountReviewOfUser($fbusername) 
    {
	$tt = null;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	   
	    $strSQL = "SELECT SUM(mark) as summ, count(mark) as countt from reviews where reviewto = '$fbusername' and mark != 0";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return 0;
            }
	    
	    $tt = new TotalAndCountReview();
	    
            $row = mysql_fetch_array($result);
            $tt->sum = $row['summ'];
	    $tt->count = $row['countt'];

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = $tt;
        }

        return $tt;
    }
    
    public static function getTotalReviewScoreByReviewOfUser($fbusername) 
    {
		$totalreviewscore = 0;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	   
	   		 $strSQL = "SELECT SUM(rvsc.reviewscore) AS totalreviewscore 
						FROM (SELECT *, 
									CASE
										WHEN mark>=5 THEN 50
										WHEN mark>=4 THEN 40
										WHEN mark<4 THEN -100
										ELSE 0
									END reviewscore	
								FROM `reviews`
								WHERE `mark`>0
							) rvsc
						WHERE rvsc.reviewto='$fbusername' GROUP BY rvsc.reviewto ";
	    
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return 500;
            }

            $row = mysql_fetch_array($result);
            $totalreviewscore = 500+$row['totalreviewscore'];

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            return $totalreviewscore;
        }

        return $totalreviewscore;
    }
    
    public static function get_1Review_ToUser($fbusername, $start, $limit) 
    {
	$ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
	    
            $strSQL = "select * from reviews where reviewto = '$fbusername' and comment != '' ORDER BY review_id DESC LIMIT $start, $limit";

            $result = mysql_query($strSQL, ConnectDB::$mLink);
		   
            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result))
            {
                array_push($ds, ReviewDAO::pullout($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
}

?>