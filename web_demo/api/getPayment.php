<?php
require_once('../model/DAO.php');
require_once './result.php';

$tt = new ResultGet();
$tt->code = 0;
$tt->count = 0;
$tt->results = "[]";
$tt->total = 0;

if (!isset($_REQUEST["userid"])) 
{
    $tt->results = "Not enough parameter";
    unset($rs->count);
    unset($rs->total);
    echo json_encode($tt);
}
else 
{
    if (isset($_REQUEST["userid"])) 
    {
	$userid = $_REQUEST["userid"];

        $total = CustomerDAO::getCustomerByUserID($userid);
        $tt->total = count($total);
        $tt->count = count($total);
        
        $tt->code = 1;
        $tt->results = $total;
        
	echo json_encode($tt);
    } 
    else 
    {
	echo json_encode($tt);
    }
}
?>