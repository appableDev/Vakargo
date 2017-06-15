<?php
session_start();

require_once '../model/Global.php';
require_once('../model/DAO.php');
require_once './sendemail.php';
require_once './result.php';


if(!isset($_REQUEST["email"]))
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

	$existeduser = UserDAO::getUserWithEmail($email);
        
	if ($existeduser != null) 
	{
	    $rs = UserDAO::isConfirmedUser($email, 1);

	    if($rs == "1")
	    {
		//TON TAI TAI KHOAN NAY
                $epass = rand(10000000, 99999999);
                
                UserDAO::UpdatePassword($epass, $existeduser->userid);
                
                $mail = Globals::initMail("Your vakargo account has been reset password", $existeduser->name, $existeduser->email);
                $body = "<div style=' font-family:Tahoma;'>
                    <style type='text/css'>
                        .text{
                            margin-top:10px;
                        }
                        .title{
                            color: #EA3556;
                            font-weight: bold;
                            width: 220px;
                            height: 20px;
                        }
                        .title img
                        {
                            margin-right: 20px;
                        }
                        .button{
                            color: #fff;
                            text-decoration: none;
                        }
                    </style>

                    <p class='text'>Dear $existeduser->name,</p>
                    <p class='text'>This is your new password</p>

                    <p class='text' style='font-size:20px; font-weight: bold; color: red;'>$epass</p>
                    
                    <p class='text'>Best regards,</p>
                    <p class='text'>The <i>vakargo</i> Team</p>
                    <p class='text'><img src='http://vakargo.com/images/vakargo_logo.png'</p>
                </div>";

                $mail->MsgHTML($body);
                
                $mail->Send();
                        
                
                //TAI KHOAN DA DUOC CONFIRM
                $rs = new ResultGet();
                $rs->code = 1;
                $rs->results = "New password has been send to your email";
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

//reset forgotpassword
    if(isset($_GET['action']))
{          
    if($_GET['action']=="reset")
    {
        $encrypt = mysqli_real_escape_string($connection,$_GET['encrypt']);
        $query = "SELECT userid FROM user where md5(90*13+id)='".$encrypt."'";
        $result = mysqli_query($connection,$query);
        $Results = mysqli_fetch_array($result);
        if(count($Results)>=1)
        {
 
        }
        else
        {
            $message = 'Invalid key please try again. <a href="http://vakargo.com/login-signup/#forget">Reset Forget Password?</a>';
        }
    }
    }
    elseif(isset($_POST['action']))
    {

        $encrypt  = mysqli_real_escape_string($connection,$_POST['action']);
        $password = mysqli_real_escape_string($connection,$_POST['password']);
        $query = "SELECT userid FROM user where md5(90*13+id)='".$encrypt."'";

        $result = mysqli_query($connection,$query);
        $Results = mysqli_fetch_array($result);
        if(count($Results)>=1)
        {
            $query = "update user set password='".md5($password)."' where userid='".$Results['userid']."'";
            mysqli_query($connection,$query);

            $message = "Your password changed sucessfully <a href=\"http://vakargo.com/login-signup/\">click here to login</a>.";
        }
        else
        {
            $message = 'Invalid key please try again. <a href="http://vakargo.com/login-signup/#forget">Reset Forget Password?</a>';
        }
    }
    else
    {
        header("location: /login-signup.php");
    }
