<?php
require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if(!isset($_REQUEST["fbid"]) || !isset($_REQUEST["email"]) || !isset($_REQUEST["name"]))
{
    $rs = new ResultGet();
    $rs->code = 0;
    $rs->results = "Not enough parameters";
    
    echo json_encode($rs);
}
else
{
    if(isset($_REQUEST["fbid"]))
    {
	$fbid = $_REQUEST["fbid"];
	$username = $_REQUEST["fbid"];
	
	$email = $_REQUEST["email"];
	$name = $_REQUEST["name"];
        
	if ($username == "")
	    $username = $fbid;

	$existeduser = UserDAO::getUserWithEmail($email);
        
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($email, 1);

	    if($rs == "1")
	    {
		//DANG NHAP THANH CONG
                
                UserDAO::updateFacebookID($existeduser->userid, $fbid);
                
                $existeduser->fbemail = $email;
                $existeduser->name = $name;
                $existeduser->fbid = $username;
                $existeduser->lastlogin = date("Y-m-d H:i:s");
                        
                if($existeduser->recipient_id == "")
                    $existeduser->haspayout = 0;
                else
                    $existeduser->haspayout = 1;
                
                UserDAO::UpdateFBUserInformation($existeduser);
                
                unset($existeduser->epass);
                unset($existeduser->code);
                unset($existeduser->suspended);
                unset($existeduser->recipient_id);
                unset($existeduser->eventcode);
                unset($existeduser->customer_id);
                unset($existeduser->fullname);
                
                //TAI KHOAN DA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->results = $existeduser;

                echo json_encode($rs);
	    }
	    else
	    {
		//TAI KHOAN CHUA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 202;
                $rs->results = "This account is not confirmed";
                
                $existeduser->fbemail = $email;
                $existeduser->name = $name;
                $existeduser->lastlogin = date("Y-m-d H:i:s");
                
                UserDAO::UpdateUserInformation($existeduser);
                
                UserDAO::updateFacebookID($existeduser->userid, $fbid);
                SendEmail::SendConfirmationEmail_forEmailSignUp($email);

                echo json_encode($rs);
	    }
	}
	else
	{
            $user = new UserDTO();
            $user->email = $email;
            $user->fbid = $fbid;
            $user->name = $name;
            $user->lastlogin = date("Y-m-d H:i:s");
            $user->fbusername = $username;
            $user->joinssince = date("Y-m-d H:i:s");
            $user->fbemail = $email;
            
            $code = strtoupper(substr(md5($email), 0, 16));

            $id = UserDAO::addUserWithCode($user, $code, "FacebookMobile");
            $user->userid = $id;

            SendEmail::SendConfirmationEmail($fbid);
            
            $rs = new ResultGet();
            $rs->code = 208;
            $rs->results = $user;
            
            $rs->results = "Sign up with Facebook successfully.";

            echo json_encode($rs);
	}
    }
    else
    {
        $rs = new ResultGet();
        $rs->code = 0;
        $rs->results = "Not enough parameters";

        echo json_encode($rs);
    }
}
