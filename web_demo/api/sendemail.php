<?php
require_once('../libs/phpmailer/class.phpmailer.php');
require_once('../model/DAO.php');
require_once '../model/Global.php';


class SendEmail 
{
    static function SendConfirmationEmail($fbid) 
    {
	$user = UserDAO::getUserWithFbID($fbid);
	$code = base64_encode($user->email);
        
	//Khởi động đối tượng
	$mail = new PHPMailer();

	/* =====================================
	 * THIET LAP THONG TIN GUI MAIL
	 * ===================================== */

	$mail->IsSMTP(); // Gọi đến class xử lý SMTP
	$mail->SMTPDebug = false;		    // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth = true;		  // Sử dụng đăng nhập vào account
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";     // Thiết lập thông tin của SMPT
	$mail->Port = 465;		     // Thiết lập cổng gửi email của máy
	$mail->Username = "vakargo@gmail.com"; // SMTP account username
	$mail->Password = "vakargo.06";	    // SMTP account password
	//Thiet lap thong tin nguoi gui va email nguoi gui
	$mail->SetFrom('vakargo@gmail.com', 'vakargo');

	//Thiết lập tiêu đề
	$mail->Subject = "vakargo notice: Please confirm your e-mail address!";
	$mail->AddAddress($user->email, $user->name);
	//Thiết lập định dạng font
	$mail->CharSet = "utf-8";

	$mail->ContentType = "text/html; charset=ISO-8859-1";
	//echo $newpassword;
	//Thiết lập nội dung chính của email

	$body = "<style type='text/css'>
	    @font-face {
		font-family: neutra_book;
		src: url('http://vakargo.com/images/Neutra2Text-Book.otf');
	    }
	</style>
	<img src='http://vakargo.com/images/vakargo_logo.png' height='70'/>
	<div style='font-family: Tahoma; line-height:1.4em;'>
	    <p style='margin-top:10px;'>Dear $user->name</p>
	    <p style='margin-top:10px;'>Welcome to vakargo! In order to get started, please confirm your e-mail address.<br>
	    <p class='text' style='margin-top:10px; font-size:14px; font-weight:bold; width: 150px; height: 25px; background-color: #EB3657;text-align: center; padding-top: 5px;'><a class='button' style='color: #fff; text-decoration: none;' href='" . Globals::$domain . "services/user_confirmaccount.php?code=$code'>Confirm your e-mail</a></p>
	    <p style='margin-top:10px;'>Best,</p>
	    <p style='margin-top:10px;'>vakargo Team</p>
	</div>";

	$mail->Body = $body;

	if (!$mail->Send()) {
	    //echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	    //echo "done";
	}
    }

    static function SendConfirmationEmail_forEmailSignUp($email) 
    {
	$user = UserDAO::getUserWithEmail($email);
	$code = base64_encode($email);
        
	//Khởi động đối tượng
	$mail = new PHPMailer();

	/* =====================================
	 * THIET LAP THONG TIN GUI MAIL
	 * ===================================== */

	$mail->IsSMTP(); // Gọi đến class xử lý SMTP
	$mail->SMTPDebug = false;		    // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth = true;		  // Sử dụng đăng nhập vào account
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";     // Thiết lập thông tin của SMPT
	$mail->Port = 465;		     // Thiết lập cổng gửi email của máy
	$mail->Username = "vakargo@gmail.com"; // SMTP account username
	$mail->Password = "vakargo.06";	    // SMTP account password
	//Thiet lap thong tin nguoi gui va email nguoi gui
	$mail->SetFrom('vakargo@gmail.com', 'vakargo');

	//Thiết lập tiêu đề
	$mail->Subject = "vakargo notice: Please confirm your e-mail address!";
	$mail->AddAddress($user->email, $user->name);
	//Thiết lập định dạng font
	$mail->CharSet = "utf-8";

	$mail->ContentType = "text/html; charset=ISO-8859-1";
	//echo $newpassword;
	//Thiết lập nội dung chính của email

	$body = "<style type='text/css'>
	    @font-face {
		font-family: neutra_book;
		src: url('http://vakargo.com/images/Neutra2Text-Book.otf');
	    }
	</style>
	<img src='http://vakargo.com/images/vakargo_logo.png' height='70'/>
	<div style='font-family: Tahoma; line-height:1.4em;'>
	    <p style='margin-top:10px;'>Dear $user->name</p>
	    <p style='margin-top:10px;'>Welcome to vakargo! In order to get started, please confirm your e-mail address.<br>
	    <p class='text' style='margin-top:10px; font-size:14px; font-weight:bold; width: 150px; height: 25px; background-color: #EB3657;text-align: center; padding-top: 5px;'><a class='button' style='color: #fff; text-decoration: none;' href='" . Globals::$domain . "services/user_confirmaccount.php?code=$code&type=email'>Confirm your e-mail</a></p>
	    <p style='margin-top:10px;'>Best,</p>
	    <p style='margin-top:10px;'>vakargo Team</p>
	</div>";

	$mail->Body = $body;

	if (!$mail->Send()) 
        {
	    
	}
        else 
        {
	    
	}
    }
    
    static function SendResetPasswordEmail($email) 
    {
	$user = UserDAO::getUserWithEmail($email);
	$code = base64_encode($email);
        
	//Khởi động đối tượng
	$mail = new PHPMailer();

	/* =====================================
	 * THIET LAP THONG TIN GUI MAIL
	 * ===================================== */

	$mail->IsSMTP(); // Gọi đến class xử lý SMTP
	$mail->SMTPDebug = false;		    // enables SMTP debug information (for testing)
	// 1 = errors and messages
	// 2 = messages only
	$mail->SMTPAuth = true;		  // Sử dụng đăng nhập vào account
	$mail->SMTPSecure = "ssl";
	$mail->Host = "smtp.gmail.com";     // Thiết lập thông tin của SMPT
	$mail->Port = 465;		     // Thiết lập cổng gửi email của máy
	$mail->Username = "vakargo@gmail.com"; // SMTP account username
	$mail->Password = "vakargo.06";	    // SMTP account password
	//Thiet lap thong tin nguoi gui va email nguoi gui
	$mail->SetFrom('vakargo@gmail.com', 'vakargo');

	//Thiết lập tiêu đề
	$mail->Subject = "vakargo notice: Please confirm your e-mail address!";
	$mail->AddAddress($user->email, $user->name);
	//Thiết lập định dạng font
	$mail->CharSet = "utf-8";

	$mail->ContentType = "text/html; charset=ISO-8859-1";
	//echo $newpassword;
	//Thiết lập nội dung chính của email

	$body = "<style type='text/css'>
	    @font-face {
		font-family: neutra_book;
		src: url('http://vakargo.com/images/Neutra2Text-Book.otf');
	    }
	</style>
	<img src='http://vakargo.com/images/vakargo_logo.png' height='70'/>
	<div style='font-family: Tahoma; line-height:1.4em;'>
	    <p style='margin-top:10px;'>Dear $user->name</p>
	    <p style='margin-top:10px;'>You have requested to reset your password. If you did, please click below link to continue:<br>
	    <p class='text' style='margin-top:10px; font-size:14px; font-weight:bold; width: 150px; height: 25px; background-color: #EB3657;text-align: center; padding-top: 5px;'><a class='button' style='color: #fff; text-decoration: none;' href='" . Globals::$domain . "resetpassword.php?code=$user->code'>Reset password</a></p>
	    <p style='margin-top:10px;'>Best,</p>
	    <p style='margin-top:10px;'>vakargo Team</p>
	</div>";

	$mail->Body = $body;

	if (!$mail->Send()) 
        {
	    
	}
        else 
        {
	    
	}
    }
}

?>