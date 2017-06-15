<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';

class ResultGetSpace
{
    public $code;
    public $results;
}

class UserBaseInfo
{
    public $facebookid;
    public $name;
    public $email;
    public $rating;
    public $score;
    public $totalreview;
    public $review;
}

function getTotalScore($fid)
{
    class Score
    {
        public $facebook;
        public $twitter;
        public $instagram;
        public $likedIn;
        public $airbnb;
        public $uber;
        public $lyft;
        public $total;
    }
    $score = new Score();
    
    $facebook=SocialDAO::getSocialByOwnernSocialType($fid,"facebook");
    $twitter=SocialDAO::getSocialByOwnernSocialType($fid,"twitter");
    $instagram=SocialDAO::getSocialByOwnernSocialType($fid,"instagram");
    $likedIn=SocialDAO::getSocialByOwnernSocialType($fid,"likedIn");
    $airbnb=SocialDAO::getSocialByOwnernSocialType($fid,"airbnb");
    $uber=SocialDAO::getSocialByOwnernSocialType($fid,"uber");
    $lyft=SocialDAO::getSocialByOwnernSocialType($fid,"lyft");
    
    $totalSocial = 0;
    
    $totalSocial+=ReviewDAO::getTotalReviewScoreByReviewOfUser($fid);
    $score->facebook = (isset($facebook) && isset($facebook->social_score))?intval($facebook->social_score):0;
    $score->twitter = (isset($twitter) && isset($twitter->social_score))?intval($twitter->social_score):0;
    $score->instagram = (isset($instagram) && isset($instagram->social_score))?intval($instagram->social_score):0;
    $score->likedIn = (isset($likedIn) && isset($likedIn->social_score))?intval($likedIn->social_score):0;
    $score->airbnb = (isset($airbnb) && isset($airbnb->social_score))?intval($airbnb->social_score):0;
    $score->uber = (isset($uber) && isset($uber->social_score))?intval($uber->social_score):0;
    $score->lyft = (isset($lyft) && isset($lyft->social_score))?intval($lyft->social_score):0;

    $totalSocial = $totalSocial + $score->facebook + $score->twitter + $score->instagram + $score->likedIn + $score->airbnb + $score->uber + $score->lyft;
    $score->total = $totalSocial;
    return $score;
}

if (!isset($_REQUEST["uid"])) 
{
    $rs = new ResultGetSpace();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else 
{
    $user = UserDAO::getUserWithFbUsername($_REQUEST["uid"]);
    $baseinfo = new UserBaseInfo();
    $baseinfo->email = $user->email;
    $baseinfo->facebookid   = $user->fbid;
    $baseinfo->name = $user->name;
    $baseinfo->rating = floatval($user->rating);
	
    $baseinfo->membersince = $user->joinssince;

    $total = ReviewDAO::getTotalAndCountReviewOfUser($user->fbid);
    $baseinfo->totalreview = floatval($total->count);
    $baseinfo->score = getTotalScore($user->fbid);
    $space->userid = $baseinfo;
    
    
    
    $review = ReviewDAO::get_1Review_ToUser($user->fbid, 0, 1);
    
    if(count($review) != 0)
    {
        $review[0]->is = "";
        $b = BookingDAO::getBookingItem($review[0]->bookingid);
        if($b->user_fbusername == $user->fbid)
            $review[0]->is = "booker";
        else
            $review[0]->is = "shipper";
        
        $user2 = UserDAO::getUserWithFbUsername($review[0]->reviewfrom);
        $review[0]->reviewfromname = $user2->name;
        $review[0]->mark = intval($review[0]->mark);
		
        $baseinfo->review = $review[0];
    }
    else
        $baseinfo->review = null;
    
    
    if($space != null)
    {
        $rs = new ResultGetSpace();
        $rs->code = 1;
        $rs->results = $space;

        echo json_encode($rs);
    }
    else
    {
        $rs = new ResultGetSpace();
        $rs->code = 0;
        $rs->results = "No data found";

        echo json_encode($rs);
    }
}
?>