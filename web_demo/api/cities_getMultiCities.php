<?php
session_start();

require_once('../model/DAO.php');
require_once './result.php';
$tt = new ResultGet();

if (!isset($_REQUEST["q"])) {
    $rs = false;

    echo "[]";
} else {
    if (isset($_REQUEST["q"])) {
	$keyword = $_REQUEST["q"];

        if(isset($_COOKIE['country']))
            $rs = CitiesDAO::getMultiCitesfromKeyword($keyword, 10, $_COOKIE['country']);
        else
            $rs = CitiesDAO::getMultiCitesfromKeyword($keyword, 10, "");

	echo json_encode($rs);
    } else {
	echo "[]";
    }
}
?>