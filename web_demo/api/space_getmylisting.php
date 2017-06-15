<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';


if(!isset($_REQUEST["userid"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["userid"]))
    {
	$userid = $_REQUEST["userid"];
        
	$existeduser = UserDAO::getUserWithUserid($userid);
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($userid);

	    if($rs == "1")
	    {
                $totalPastSpace = SpaceDAO::getMyListing_TotalCount($userid);
                
                if(!isset($_REQUEST['start']) || !isset($_REQUEST['limit']))
                    $pastSpace = SpaceDAO::getMyListing($userid, 0, 3);
                else
                    $pastSpace = SpaceDAO::getMyListing($userid, $_REQUEST['start'], $_REQUEST['limit']);
                
                for($i = 0; $i < count($pastSpace); $i++)
                {
                    $arrayCategory = Space_CategoryDAO::getCategoryList($pastSpace[$i]->space_id);
                    for ($k = 0; $k < count($arrayCategory); $k++) 
                    {
                        unset($arrayCategory[$k]->space_id);
                        unset($arrayCategory[$k]->category_name);
                    }
                    
                    $pastSpace[$i]->categ_list = $arrayCategory;
                    
                    if($pastSpace[$i]->active == 1)
                        $pastSpace[$i]->visible = 1;
                    else
                        $pastSpace[$i]->visible = 0;

                    unset($pastSpace[$i]->active);
                    
                    unset($pastSpace[$i]->dimensionx);
                    unset($pastSpace[$i]->dimensiony);
                    unset($pastSpace[$i]->dimensionz);
                    unset($pastSpace[$i]->list_flightid);
                    unset($pastSpace[$i]->date_create);
                    unset($pastSpace[$i]->active);
                }
                
                //Thanh cong
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->total = $totalPastSpace + 0;
                $rs->count = count($pastSpace);
                $rs->results = $pastSpace;

                echo json_encode($rs);
	    }
	    else
	    {
		//TAI KHOAN CHUA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 202;
                $rs->results = "This account is not confirmed";

                echo json_encode($rs);
	    }
	}
	else
	{
            $rs = new ResultGet();
            $rs->code = 203;
            $rs->results = "This account does not exist";

            echo json_encode($rs);
	}
    }
    else
    {
        $rs = new ResultGet();
        $rs->code = 0;
        $rs->results = "Not enough parameters";

        echo json_encode($rs);
    }
}