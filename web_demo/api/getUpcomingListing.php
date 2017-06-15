<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';


if(!isset($_REQUEST["userId"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["userId"]))
    {
	$fbid = $_REQUEST["userId"];
        
	$existeduser = UserDAO::getUserWithFbID($fbid);
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($fbid);

	    if($rs == "1")
	    {
                $totalUpcommingSpace = SpaceDAO::getTotalNumberUpCommingSpace($fbid);
                $upcommingSpace = SpaceDAO::getUpCommingSpace($fbid, 0, 3);
                
                for($i = 0; $i < count($upcommingSpace); $i++)
                {
                    $arrayCategory = Space_CategoryDAO::getCategoryList($upcommingSpace[$i]->space_id);
                    for ($k = 0; $k < count($arrayCategory); $k++) 
                    {
                        unset($arrayCategory[$k]->space_id);
                        unset($arrayCategory[$k]->category_name);
                    }
                    
                    $upcommingSpace[$i]->categ_list = $arrayCategory;
                    
                    unset($upcommingSpace[$i]->dimensionx);
                    unset($upcommingSpace[$i]->dimensiony);
                    unset($upcommingSpace[$i]->dimensionz);
                    unset($upcommingSpace[$i]->list_flightid);
                    unset($upcommingSpace[$i]->date_create);
                    unset($upcommingSpace[$i]->active);
                }
                
                
                //Thanh cong
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->total = $totalUpcommingSpace + 0;
                $rs->count = count($upcommingSpace);
                $rs->results = $upcommingSpace;

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