<?php ob_start();

require_once '../model/Global.php';
require_once '../model/DAO.php';

class UserBaseInfo
{
    public $facebookid;
    public $name;
    public $email;
    public $rating;
    public $totalscore;
}

class ResultGet
{
    public $code;
//    public $count;
//    public $total;
    public $results;
}

?>