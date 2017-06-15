<?php
session_start();
require_once '../model/Global.php';
require_once('../model/DAO.php');

if(isset($_REQUEST['code']))
{
    $email = base64_decode($_REQUEST['code']);

    $user = UserDAO::getUserWithEmail($email);
    if($user != null)
    {
        UserDAO::confirmUserWithEmail($email);

        $_SESSION[Globals::$F_ISLOGIN] = "1";
        $_SESSION[Globals::$F_EMAIL] = $user->email;
        $_SESSION[Globals::$F_FBID] = $user->fbid;
        $_SESSION[Globals::$F_FBUSERNAME] = $user->fbusername;
        $_SESSION[Globals::$F_ISCONFIRM] = $user->isconfirmed;
        $_SESSION[Globals::$F_LASTLOGIN] = $user->lastlogin;
        $_SESSION[Globals::$F_NAME] = $user->name;
        $_SESSION[Globals::$F_JOINSSINCE] = $user->joinssince;
    }
}
header('Location: ../index.php');

?>