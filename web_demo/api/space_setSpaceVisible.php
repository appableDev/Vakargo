<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';


if(!isset($_REQUEST["spaceid"]) || !isset($_REQUEST["visible"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    $space_id = $_REQUEST["spaceid"];
    $visible = $_REQUEST["visible"];

    $space = SpaceDAO::getSpaceWithSpaceID($space_id);
    
    if ($space != null) 
    {
        if($visible == 0 || $visible == 1)
        {
            $rs = SpaceDAO::updateSpaceStatus($space_id, $visible);
            
            if($rs == TRUE)
            {
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->results = "Updated successfully!";

                echo json_encode($rs);
            }
            else
            {
                $rs = new ResultGet();
                $rs->code = 404;
                $rs->results = "Something wrong from database";

                echo json_encode($rs);
            }
        }
        else
        {
            $rs = new ResultGet();
            $rs->code = 409;
            $rs->results = "The visible value is invalid!";

            echo json_encode($rs);
        }
    }
    else
    {
        $rs = new ResultGet();
        $rs->code = 403;
        $rs->results = "This space does not exist";

        echo json_encode($rs);
    }
}