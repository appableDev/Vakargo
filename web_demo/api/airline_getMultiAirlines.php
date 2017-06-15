<?php
require_once('../model/DAO.php');
$tt = new ResultGet();

if (!isset($_REQUEST["q"])) 
{
    $rs = false;

    echo "[]";
}
else 
{
    if (isset($_REQUEST["q"])) 
    {
	$keyword = $_REQUEST["q"];

	$rs = AirlineDAO::getAirlinefromKeyword($keyword, 10);

	echo json_encode($rs);
    }
    else 
    {
	echo "[]";
    }
}
?>