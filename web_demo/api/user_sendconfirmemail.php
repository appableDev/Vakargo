<?php

require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if (isset($_REQUEST["fbid"])) 
{
    $fbid = $_REQUEST["fbid"];
    SendEmail::SendConfirmationEmail($fbid);
    
    $rs = new ResultGet();
    $rs->code = 1;
    echo json_encode($rs);
}
else
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameter";
    echo json_encode($rs);
}
?>