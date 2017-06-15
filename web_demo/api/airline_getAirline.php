<?php
require_once('../model/DAO.php');
require_once './result.php';
$tt = new ResultGet();
$tt->code = 0;
$tt->count = 0;
$tt->results = "[]";
$tt->total = 0;

if(!isset($_REQUEST["q"]) || !isset($_REQUEST["limit"]))
{
    $tt->results = "Not enough parameter";
    echo json_encode($tt);
}
else
{
    if(isset($_REQUEST["q"]) && isset($_REQUEST["limit"]))
    {
        $limit = $_REQUEST["limit"];
        $keyword = $_REQUEST["q"];

        $total = AirlineDAO::getAirlinefromKeyword($keyword, 0);
        $tt->total = count($total);
        
        $rs = AirlineDAO::getAirlinefromKeyword($keyword, $_REQUEST["limit"]);
        $tt->count = count($rs);
        
        $tt->code = 1;
        $tt->results = $rs;
        
	echo json_encode($tt);
    }
    else
    {
        $tt->results = "Not enough parameter";
        echo json_encode($tt);
    }
}
?>