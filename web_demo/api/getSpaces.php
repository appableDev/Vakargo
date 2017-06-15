<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';

function getTotalScore($fid)
{
    $facebook=SocialDAO::getSocialByOwnernSocialType($fid,"facebook");
    $twitter=SocialDAO::getSocialByOwnernSocialType($fid,"twitter");
    $instagram=SocialDAO::getSocialByOwnernSocialType($fid,"instagram");
    $likedIn=SocialDAO::getSocialByOwnernSocialType($fid,"likedIn");
    $airbnb=SocialDAO::getSocialByOwnernSocialType($fid,"airbnb");
    $uber=SocialDAO::getSocialByOwnernSocialType($fid,"uber");
    $lyft=SocialDAO::getSocialByOwnernSocialType($fid,"lyft");
    
    $totalSocial = 0;
    
    $totalSocial+=ReviewDAO::getTotalReviewScoreByReviewOfUser($fid);
    $totalSocial+=(isset($facebook) && isset($facebook->social_score))?intval($facebook->social_score):0;
    $totalSocial+=(isset($twitter) && isset($twitter->social_score))?intval($twitter->social_score):0;
    $totalSocial+=(isset($instagram) && isset($instagram->social_score))?intval($instagram->social_score):0;
    $totalSocial+=(isset($likedIn) && isset($likedIn->social_score))?intval($likedIn->social_score):0;
    $totalSocial+=(isset($airbnb) && isset($airbnb->social_score))?intval($airbnb->social_score):0;
    $totalSocial+=(isset($uber) && isset($uber->social_score))?intval($uber->social_score):0;
    $totalSocial+=(isset($lyft) && isset($lyft->social_score))?intval($lyft->social_score):0;

    return $totalSocial;
}


try
{
    $unit = "lb";
    if(isset($_REQUEST['unit']) && ($_REQUEST['unit'] == "kg" || $_REQUEST['unit'] == "lb"))
        $unit = $_REQUEST['unit'];

    $pagesize = 10;

    $query = "";
    $add = "";
    $sort = "";
    if(isset($_REQUEST['sort']))
    {
        if ($_REQUEST['sort'] == "date") 
        {
            $sort = "date";
        } 
        else
        if ($_REQUEST['sort'] == "mutualfriends") 
        {
            $sort = "mutualfriends";
        } 
        else
        if ($_REQUEST['sort'] == "rating") 
        {
            $sort = "rating";
        } 
        else
        if ($_REQUEST['sort'] == "setprice") 
        {
            $sort = "setprice";
        } 
        else
        if ($_REQUEST['sort'] == "perweightprice") 
        {
            $sort = "perweightprice";
        } 
        else
        if ($_REQUEST['sort'] == "totalprice") 
        {
            $sort = "totalprice";
        } 
        else
        if ($_REQUEST['sort'] == "totalsocialscore") 
        {
            $sort = "totalsocialscore";
        }
        else
        if ($_REQUEST['sort'] == "lc") 
        {
            $sort = "lc";
        }
        else
            $sort = "date";
    }
    else
    {
        $sort = "lc";
    }

    if (isset($_REQUEST['min_w']) && $_REQUEST['min_w'] != "" && is_numeric($_REQUEST['min_w']) && $_REQUEST['min_w'] != "0")
        $add = ", (setprice + (price * " . $_REQUEST['min_w'] . ")) as totalprice";

    $query .= "SELECT space.* $add, space.max_weight - SUM( booking.weight) AS availableweight ";

    $query .= "FROM space ";
    $query .= " LEFT JOIN booking ON space.space_id = booking.space_id and accepted = '1'";


    if($sort == 'rating')
        $query .= " JOIN user ON space.user_fbusername = user.fbusername ";

    $isFirstCondition = true;

    $keyword = "";
    $q = "";
    if (isset($_REQUEST["city"])) 
    {
        $keyword = $_REQUEST["city"];
        $q = $_REQUEST["city"];
        $keyword = preg_replace("/,.*/im", "", $keyword);
    }

    $citycode = "";

    {
        $categlist = "0,1,2,3";
        if(isset($_REQUEST['categ']))
           $categlist = $_REQUEST['categ'];

        $sum_category = preg_split("/,/", $categlist);

        if (count($sum_category) > 0)
        {
            $query .= "JOIN space_category on space.space_id = space_category.space_id AND (";
            $totalcategory = count($sum_category);
            for ($i = 0; $i < $totalcategory; $i++) {
                $item = array_pop($sum_category);

                if ($i == 0)
                    $query .= " category_id = " . $item;
                else
                    $query .= " or category_id = " . $item;
            }

            $query .= ")";
        }
    }

    $query .= " where active = '1' and pickup_date >= '" . date("Y-m-d") . "' and delivery_location like '%$keyword%'";

    
    if(isset($_REQUEST['userid']) && $_REQUEST['userid'] != "")
        $query .= " and space.user_fbusername <> '".$_REQUEST['userid']."' ";
    
    $isFirstCondition = false;

    $min_weight = 0;
    if (isset($_REQUEST['min_w']) && $_REQUEST['min_w'] != "" && is_numeric($_REQUEST['min_w'])) 
    {    
        $min_weight = $_REQUEST['min_w'];
    }

    $min_days = 0;
    if (isset($_REQUEST['min_d']) && $_REQUEST['min_d'] != "" && is_numeric($_REQUEST['min_d'])) {
        $min_days = $_REQUEST['min_d'];

        if ($isFirstCondition == false)
            $query .= " AND ";
        else
            $isFirstCondition = false;

        $query .= " DATEDIFF(pickup_date,'" . date("Y-m-d") . "') >= " . $min_days;
    }

    $max_days = 365;
    if (isset($_REQUEST['max_d']) && $_REQUEST['max_d'] != "" && is_numeric($_REQUEST['max_d'])) {
        $max_days = $_REQUEST['max_d'];
        if ($isFirstCondition == false)
            $query .= " AND ";
        else
            $isFirstCondition = false;
        $query .= " DATEDIFF(pickup_date,'" . date("Y-m-d") . "') <= " . $max_days;
    }
    
    if(isset($_REQUEST['itemfromAmazon']))
    {
        $isamazon = 0;
        if($_REQUEST['itemfromAmazon'] == 0)
        {
            $query .= " and amazon = 0 ";
        }
        else
        {
            $query .= " and amazon = 1 ";
        }
    }
    
    
    if(isset($_REQUEST['deliverytovakargoscooter']))
    {
        $isscooter = 0;
        if($_REQUEST['deliverytovakargoscooter'] == 0)
        {
            $query .= " and isvakargoscooter = 0 ";
        }
        else
        {
            $query .= " and isvakargoscooter = 1 ";
        }
    }

    $miw = "";
    $maw = "";

    if($min_weight != 0)
        $miw = $min_weight;
    if($unit == "kg")
        $miw = $min_weight * 2.20462;

    if($miw > 0)
    {
        $maw = " and max_weight >= " . $miw;
        $miw = " and availableweight  >= " . $miw;
    }
    else
    {
        $miw = "";
    }

    $query .= " GROUP BY space.space_id HAVING ((availableweight is NULL $maw) or (availableweight > 0 $miw)) ";

    //$min_price = 0;
    $max_price = 0;

    if (isset($_REQUEST['min_w']) && $_REQUEST['min_w'] != "" && is_numeric($_REQUEST['min_w']) && $_REQUEST['min_w'] != "0") 
    {
        if (isset($_REQUEST['max_p']) && $_REQUEST['max_p'] != "" && is_numeric($_REQUEST['max_p'])) 
        {
            $max_price = $_REQUEST['max_p'];

            if($max_price > 0)
            {
                if ($isFirstCondition == false)
                    $query .= " AND ";
                else
                    $isFirstCondition = false;

                $query .= " totalprice <= '" . $max_price . "'";
            }
        }
    }

    $sort_date = "";
    $sort_mutualfriends = "";
    $sort_rating = "";
    $sort_setprice = "";
    $sort_perweightprice = "";
    $sort_totalprice = "";
    $sort_location = "";
    $sort_social = "";

    if ($sort != "") 
    {
        if ($sort == "date") 
        {
            $query .= " ORDER BY delivery_date ASC";
            $sort_date = " selected ";
        } 
        else
        if ($sort == "mutualfriends") 
        {
            $sort_mutualfriends = " selected ";
        } 
        else
        if ($sort == "rating") 
        {
            $query .= " ORDER BY rating DESC";
            $sort_rating = " selected ";
        } 
        else
        if ($sort == "setprice") 
        {
            $query .= " ORDER BY setprice ASC";
            $sort_setprice = " selected ";
        } 
        else
        if ($sort == "perweightprice") 
        {
            $query .= " ORDER BY price ASC";
            $sort_perweightprice = " selected ";
        } 
        else
        if ($sort == "totalprice") 
        {
            $query .= " ORDER BY totalprice ASC";
            $sort_totalprice = " selected ";
        } 
        else
        if ($sort == "totalsocialscore") 
        {
            $sort_social = " selected ";
        } 
        else
        if ($sort == "lc") 
        {
            $sort_location = " selected ";
        }
        else
            $sort_location = " selected "; // maybe not execute here
    }
    else
    {
        $sort_location = " selected "; // maybe not execute here
    }



    if($sort == 'mutualfriends')
    {
        $query = "SELECT * from ($query) sp";

        $query .= " LEFT JOIN
                    (
                        SELECT space.* , COUNT( b.friend_username ) AS mutualfriends
                        FROM space
                        LEFT JOIN fbfriendlist a ON a.owner_username = space.user_fbusername
                        AND a.owner_username <>  '".$_SESSION[Globals::$F_FBUSERNAME]."'
                        LEFT JOIN fbfriendlist b ON a.friend_username = b.friend_username
                        AND b.owner_username =  '".$_SESSION[Globals::$F_FBUSERNAME]."'
                        GROUP BY space.space_id
                    ) fr	

                    ON sp.space_id = fr.space_id
                    ORDER BY mutualfriends DESC";
    }

    if($sort == 'totalsocialscore')
    {
        $query = "SELECT * from ($query) sp";

        $query .= " LEFT JOIN
                    (
                    SELECT sp.*,(500+ COALESCE(rv.totalreviewscore, '0') +  SUM(sc.social_score)) AS totalsocialscore
                                    FROM `space` sp
                                    LEFT JOIN social sc ON sc.owner_username = sp.user_fbusername 
                                    LEFT JOIN (
                                            SELECT rvsc.reviewto, SUM(rvsc.reviewscore) AS totalreviewscore 
                                            FROM (SELECT reviewto, 
                                                    CASE
                                                            WHEN mark>=5 THEN 50
                                                            WHEN mark>=4 THEN 40
                                                            WHEN mark<4 THEN -100
                                                            ELSE 0
                                                    END reviewscore	
                                                    FROM `reviews`
                                                    WHERE `mark`>0
                                                    ) rvsc
                                            GROUP BY rvsc.reviewto
                                            ) rv ON rv.reviewto=sp.user_fbusername 
                                    GROUP BY sp.space_id
                    ) fr	

                    ON sp.space_id = fr.space_id
                    ORDER BY totalsocialscore DESC";

                    //echo $query;
    }


    //===============================================================================================
    //===============================================================================================
    //===============================================================================================
    $query1 = $query;
    $query2 = $query;


    $start = 0;
    $limit = 10;
    if(isset($_REQUEST["start"]) && $_REQUEST["start"] != "" && is_numeric($_REQUEST["start"]) && isset($_REQUEST["limit"]) && $_REQUEST["limit"] != "" && is_numeric($_REQUEST["limit"]))
    {
        $start = $_REQUEST["start"];
        $limit = $_REQUEST["limit"];
    }
    
    if($sort == "lc")
    {
        $isSortByLocation = "1";
    }
    else
    {
        $query1 .= " LIMIT $start, $limit";
        $isSortByLocation = "0";
    }


    if($sort == 'mutualfriends' || $sort == 'totalsocialscore')
    {
        $query2 = str_replace("SELECT *", "SELECT count(*) as total ", $query2);
    }
    else
    {
        $query2 = preg_replace("/LIMIT.*?/", "", $query2);
        $query2 = "SELECT COUNT( * ) AS total FROM (" . $query2 . ") AS sp";
    }


    $param = $_SERVER['REQUEST_URI'];
    $indexx = strpos($param, "?");
    $param = substr($param, $indexx + 1 , strlen($param));



    //===============================================================================================
    //===============================================================================================
    //===============================================================================================

    $spacelist = SpaceDAO::getSpaceWithQuery($query1);
    $totalspace = SpaceDAO::getTotalSpaceSearch($query2);



    $totalSpaces = count($spacelist);
    $idx=0;
    
    $spacelist_final = array();

    foreach ($spacelist as $space) 
    {
        $user = UserDAO::getUserWithUserid($space->userid);
        
        if($user == null)
        {
            $totalSpaces--;
            continue;
        }
        
        $idx++;
        
        $baseinfo = new UserBaseInfo();
        $baseinfo->email = $user->email;
        $baseinfo->facebookid   = $user->fbid;
        $baseinfo->name = $user->name;
        $baseinfo->rating = floatval($user->rating);
        $baseinfo->userid = $user->userid;
        
        $space->type = $user->type;
        
        $total = ReviewDAO::getTotalAndCountReviewOfUser($user->fbid);
        $baseinfo->totalreview = floatval($total->count);
        $baseinfo->totalscore = 0; //getTotalScore($user->fbid);
        
        $space->userid = $baseinfo;
        
        unset($space->categ_list);

        $pickupdate = new DateTime($space->pickup_date);
        $space->pickup_date = $pickupdate->format("F d, Y");
        $deliverydate = new DateTime($space->delivery_date);
        $space->delivery_date = $deliverydate->format("F d, Y");


        if($unit == "kg")
        {
            $space->max_weight = number_format($space->max_weight / 2.20462, 2);
            $space->price = number_format($price * 2.20462, 2);
        }

        $space->price = floatval($space->price);
        $space->setprice = floatval($space->setprice);
        
        if($space->active == 1)
            $space->visible = 1;
        else
            $space->visible = 0;

        unset($space->active);
                    
                    
        unset($space->dimensionx);
        unset($space->dimensiony);
        unset($space->dimensionz);
//        unset($space->max_weight);

        unset($space->active);
        unset($space->note);
        unset($space->list_flightid);
        unset($space->date_create);
        
        array_push($spacelist_final, $space);
    }

    $rs = new ResultGet();
    $rs->code = 1;
    $rs->count = count($spacelist_final);
    $rs->total = $totalSpaces;
    $rs->results = $spacelist_final;
}
catch (Exception $e)
{
    $rs = new ResultGet();
    $rs->code = 301;
    unset($rs->count);
    unset($rs->total);
    $rs->results = $e->getMessage();
}

echo json_encode($rs);
         
