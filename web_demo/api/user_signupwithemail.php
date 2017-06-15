<?php
session_start();

require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if(!isset($_REQUEST["email"]) || !isset($_REQUEST["epass"]) || !isset($_REQUEST["name"]))
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
	$name = $_REQUEST["name"];
        $epass = $_REQUEST["epass"];

	$existeduser = UserDAO::getUserWithEmail($email);
        
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($email, 1);

	    if($rs == "1")
	    {
		//DANG NHAP THANH CONG
                $existeduser->email = $email;
                $existeduser->lastlogin = date("Y-m-d H:i:s");
                
                UserDAO::UpdateUserInformation($existeduser);
                
                unset($existeduser->code);
                unset($existeduser->suspended);
                unset($existeduser->recipient_id);
                unset($existeduser->eventcode);
                unset($existeduser->customer_id);
                unset($existeduser->fullname);
                
                //TAI KHOAN DA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 201;
                $rs->results =  "This account had been signed up before.";
                unset($rs->count);
                unset($rs->total);

                echo json_encode($rs);
                
                SendEmail::SendConfirmationEmail_forEmailSignUp($email);
                UserDAO::UpdatePassword($existeduser->userid, rand(10000, 99999) . "%*%" . $epass);
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
                
                if($existeduser->fbid != "")
                { 
                    SendEmail::SendConfirmationEmail_forEmailSignUp($email);
                    UserDAO::UpdatePassword($existeduser->userid, rand(10000, 99999) . "%*%" . $epass);
                }
	    }
            
            
	}
	else
	{
            if(strpos($email, "@") == false)
            {
                $rs = new ResultGet();
                $rs->code = 206;
                $rs->results = "The email is invalid!";
                unset($rs->count);
                unset($rs->total);

                echo json_encode($rs);
            }
            else 
            {
                $user = new UserDTO();
                $user->email = $email;
                $user->name = $name;
                $user->lastlogin = date("Y-m-d H:i:s");
                $user->joinssince = date("Y-m-d H:i:s");
                $user->fbemail = "";
                $user->epass = $user->epass = rand(10000, 99999) . "%*%" . $epass;
                
                $code = strtoupper(substr(md5($email), 0, 16));
                
                $id = UserDAO::addUserWithCode($user, $code, "EmailMobile");
                
                $user = UserDAO::getUserWithUserid($id);
                
                unset($user->code);
                unset($user->suspended);
                unset($user->recipient_id);
                unset($user->eventcode);
                unset($user->customer_id);
                unset($user->fullname);

                SendEmail::SendConfirmationEmail_forEmailSignUp($email);

                $rs = new ResultGet();
                $rs->code = 1;
                $rs->results = $user;
                unset($rs->count);
                unset($rs->total);

                echo json_encode($rs);
            }
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