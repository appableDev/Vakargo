<?php
session_start();

require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if(!isset($_REQUEST["email"]) || !isset($_REQUEST["epass"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    unset($rs->count);
    unset($rs->total);
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["email"]))
    {
	$email = $_REQUEST["email"];
        $epass = $_REQUEST["epass"];
        
	$existeduser = UserDAO::getUserWithEmail($email);
        
	if ($existeduser != null) 
	{
            $authen = UserDAO::checkPassword($email, $epass);
            
            if($authen == TRUE)
            {
                $rs = UserDAO::isConfirmedUser($email, 1);

                if($rs == "1")
                {
                    //DANG NHAP THANH CONG
                    $existeduser->email = $email;
                    $existeduser->lastlogin = date("Y-m-d H:i:s");
                    
                    if($existeduser->recipient_id == "")
                        $existeduser->haspayout = 0;
                    else
                        $existeduser->haspayout = 1;

                    UserDAO::UpdateUserInformation($existeduser);

                    unset($existeduser->code);
                    unset($existeduser->epass);
                    unset($existeduser->suspended);
                    unset($existeduser->recipient_id);
                    unset($existeduser->eventcode);
                    unset($existeduser->customer_id);
                    unset($existeduser->fullname);


                    //TAI KHOAN DA DUOC CONFIRM
                    $rs = new ResultGet();
                    $rs->code = 1;
                    $rs->results = $existeduser;
                    unset($rs->count);
                    unset($rs->total);


                    echo json_encode($rs);
                }
                else
                {
                    //TAI KHOAN CHUA DUOC CONFIRM
                    $rs = new ResultGet();
                    $rs->code = 202;
                    $rs->results = "This account is not confirmed";
                    unset($rs->count);
                    unset($rs->total);

                    echo json_encode($rs);
                }
            }
            else 
            {
                //TAI KHOAN CHUA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 205;
                $rs->results = "Log in fail!";
                unset($rs->count);
                unset($rs->total);

                echo json_encode($rs);
            }
	}
	else
	{
            $rs = new ResultGet();
            $rs->code = 203;
            $rs->results = "This account does not exist";
            unset($rs->count);
            unset($rs->total);

            echo json_encode($rs);
	}
    }
    else
    {
        $rs = new ResultGet();
        $rs->code = 0;
        $rs->results = "Not enough parameters";
        unset($rs->count);
        unset($rs->total);

        echo json_encode($rs);
    }
}
?>