<?php
session_start();

require_once('../model/DAO.php');
class ResponseData{
    public $code;
    public $results;
}
$response = new ResponseData();

if(isset($_REQUEST["userId"]) && isset($_REQUEST["recipientId"]))
{
    try
    {
        $user_id = $_REQUEST["userId"];
        $recipient_id = $_REQUEST["recipientId"];
        $existeduser = UserDAO::getUserWithUserid($user_id);
        
        if ($existeduser != null) 
        {
            $result = UserDAO::updateRecipientIDByUserID($user_id,$recipient_id);
            $data = array('isUpdateSuccess'=>0);
            $response->code = 1;
            if($result)
            {
                $data['isUpdateSuccess']=1;
                $response->results =  $data;
            }
            else 
            {
                $data['isUpdateSuccess']=0;
                $response->results =  $data;
            }
        }
        else
        {
            $response->code = 203;
            $response->results = "This account does not exist!";
        }
    }
    catch (Exception $e)
    {
        $response->code = 301;
        $response->results = $e->json_body;
    }

}
else
{
    $response->code = 0;
    $response->results = "Not enough parameters";
}

echo json_encode($response);
?>