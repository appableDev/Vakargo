<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';
require_once './result.php';

if (!isset($_REQUEST["myid"]) || !isset($_REQUEST["theirid"])) 
{
    $rs = new ResultGetSpace();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else 
{
    $theirid = $_REQUEST["theirid"];
    $myid = $_REQUEST["myid"];
    
    
    if($myid == "")
    {
        $mutualfriend = FbfriendDAO::getFBFriends($theirid, 20);
        $total = FbfriendDAO::countFBFriends($theirid);
        
        
        $rs = new ResultGet();
        $rs->code = 1;
        $rs->results = $mutualfriend;
        $rs->total = $total;
        $rs->count = 20;

        echo json_encode($rs);
        
    }
    else
    {
        $user = UserDAO::getUserWithFbID($myid);

        if($user == null)
        {
            $rs = new ResultGet();
            $rs->code = 203;
            $rs->results = "This account does not exist";

            echo json_encode($rs);
            exit();
        }
        
        $mutualfriend = FbfriendDAO::getFBMutualFriends($myid, $theirid);
        
        
        $rs = new ResultGet();
        $rs->code = 1;
        $rs->results = $mutualfriend;
        $rs->total = count($mutualfriend);
        $rs->count = count($mutualfriend);

        echo json_encode($rs);
        
    }
}
?>