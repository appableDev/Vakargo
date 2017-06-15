<?php
require_once './myMail.php';

$F_USERID = md5("userid");
$F_ISLOGIN = md5("islogin");
$F_EMAIL = md5("email");
$F_FBID = md5("fbid");
$F_ISCONFIRM = md5("isconfirmed");
$F_LASTLOGIN = md5("lastlogin");
$F_NAME = md5("name");
$F_FBUSERNAME = md5("fbusername");
$F_JOINSSINCE = md5("joinssince");

$domain = "http://localhost/vakargo_beta/";

$app_id = "674388342575528";
$app_secret = "9616e65d86e2ec2e83eccd8f72c1b42a";

class Globals 
{
    public static $app_id = "674388342575528";
    public static $app_secret = "9616e65d86e2ec2e83eccd8f72c1b42a";
    
    public static $flightStatID = "fa091429";
    public static $flightStatKey = "ea33c12dbc23a4a7af8ed2094cf6621b";

    public static $domain = "http://localhost/vakargo_beta/";
    public static $live_domain = "http://localhost/vakargo_beta/";
    
    public static $F_USERID = "ea8f538c94b6e352418254ed6474a81f";
    public static $F_ISLOGIN = "21c8bfa1ee279edee19731c32d1b158d";
    public static $F_EMAIL = "0c83f57c786a0b4a39efab23731c7ebc";
    public static $F_FBID = "743d9c0164281312563b1f13272b1f2f";
    public static $F_ISCONFIRM = "b211eb9ce5052b15ea1cf256ede35b04";
    public static $F_LASTLOGIN = "07354d749d1b4c52b8d1435af7a97e84";
    public static $F_NAME = "b068931cc450442b63f5b3d276ea4297";
    public static $F_FBUSERNAME = "acc3aa9d23f83e432ef86fd934b9700e";
    public static $F_JOINSSINCE = "07354d749d1b4c52b8d1435af7a97e84";
    
    public static $PROMOCODE = "VAKARGO4LIFE";
    
    public static $StripePrivateKey = "sk_test_APQHNdmvHiTmhmeU98rwPcxD";
    public static $StripePuplicKey = "pk_test_jOWhDy1GhU4QdN8jRvY6WJvp";
    
//    public static $StripePrivateKey = "sk_live_EDkIRsX7bT0mVbTJjwDIQquR";
//    public static $StripePuplicKey = "pk_live_7Sxb5rsVoUfVYTjQYbkp3Fwf";
    
    public static $Stripe_dev_client_id = "ca_3Lgz1qCzaxS7QeRFcdv4ZfB4bV2M6ggH";
    public static $Stripe_pro_client_id = "";
    
    public static $Twitter_API_public_key = "O1oR4fsQ1XIn4SAJHVKoqw";
    public static $Twitter_API_secret_key = "qfLNYOLlY8ivdnzOPfgMfKbEk9saTEpgtf4JcNzkP0";
    
    public static $uber_client_id = "cWrnEo8Zqk-OB9AqllGm-IXc2IumWS10";
    public static $uber_app_secret = "uvRwULGNtOJs78J0SKc-u7pB0N92pauuwBfMrmaC";
    
    public static function url_exists($url) 
    {
        if(@file_get_contents($url,0,NULL,0,1))
        {
            return true;
        }
        else
        { 
            return false;
        } 
    }
	
    public static function Write_LogInfo($strInfo) 
    {
        try
        {
            $now = getdate();
            $currentDate = $now["year"].($now["mon"]<10?'0':'').$now["mon"].($now["mday"]<10?'0':'').$now["mday"];
            $filePath='\logfiles\info_'.$currentDate.'.txt';
            $fp = fopen(realpath(dirname(__FILE__)).'/..'.$filePath, 'a');
            fwrite($fp, $strInfo."\r\n");
            fclose($fp);
        }
        catch (Exception $e){}
    }

    public static function Write_LogError($strError) 
    {
        try
        {
            $now = getdate();
            $currentDate = $now["year"].($now["mon"]<10?'0':'').$now["mon"].($now["mday"]<10?'0':'').$now["mday"];
            $filePath='\logfiles\error_'.$currentDate.'.txt';
            $fp = fopen(realpath(dirname(__FILE__)).'/..'.$filePath, 'a');
            fwrite($fp, $strError."\r\n");
            fclose($fp);
        }
        catch (Exception $e){}
    }
    
    function CutString($title,$num)
    {
        if(strlen($title)<=$num)
            return $title;

        $strTitle="";
        $arrTitle = explode(" ", $title);
        $strTemp="";
        for($i=0;$i<count($arrTitle);$i++)
        {
            if(strlen($strTitle." " .$arrTitle[$i])>($num-3))
            {
                    return $strTitle."...";
            }
            else
            {
                    $strTitle .= $arrTitle[$i]." ";
            }
        }
        return $strTitle;
    }
	
    function debug_to_console($data) {
        if(is_array($data) || is_object($data))
            {
                    echo("<script>console.log('PHP: ".json_encode($data)."');</script>");
            } else {
                    echo("<script>console.log('PHP: ".$data."');</script>");
            }
    }
    
    public static function initMail($title, $toname, $toemail) 
    {
        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPDebug = 0;                    // enables SMTP debug information (for testing)
        // 1 = errors and messages
        // 2 = messages only
        $mail->SMTPAuth = true;
        
        $mail->SMTPSecure = "ssl";
        $mail->Host = "email-smtp.us-west-2.amazonaws.com";     // Thiết lập thông tin của SMPT
        $mail->Port = 465;                     // Thiết lập cổng gửi email của máy
        $mail->Username = "AKIAJRQS4AX4UBBAKG6Q"; // SMTP account username
        $mail->Password = "AhLRZSaxNbL+OXJ/NRIuuwo7OL76FYDRgIFP1CRPHNVz";            // SMTP account password
        //Thiet lap thong tin nguoi gui va email nguoi gui
        $mail->SetFrom('automated@vakargo.com', 'vakargo');

        
        
        $mail->Subject = $title;
        $mail->AddAddress($toemail, $toname);
        $mail->CharSet = "utf-8";
        $mail->ContentType = "text/html; charset=ISO-8859-1";
        
        return $mail;
    }
    
    public static function curl_get_contents($url)
    {
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
      $data = curl_exec($ch);
      curl_close($ch);
      return $data;
    }
}

?>