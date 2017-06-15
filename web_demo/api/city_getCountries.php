<?php
require_once('../model/DAO.php');
require_once './result.php';

$tt = new ResultGet();
$tt->code = 0;
$tt->count = 0;
$tt->results = "[]";
$tt->total = 0;

if (!isset($_REQUEST["key"])) 
{
    $tt->results = "Not enough parameter";
    echo json_encode($tt);
}
else 
{
    if (isset($_REQUEST["key"])) 
    {
	$keyword = $_REQUEST["key"];

        $total = CitiesDAO::getCountriesfromKeyword($keyword, 0);
        $tt->total = count($total);
        
        $rs = CitiesDAO::getCountriesfromKeyword($keyword, 10);
        $tt->count = count($rs);
        
        for($i = 0; $i < count($rs); $i++)
        {
            unset($rs[$i]->check);
        }
        
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