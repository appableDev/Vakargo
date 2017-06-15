<?php
require_once('../model/scooterDAO.php');

class ResponseData{
    public $code;
    public $results;
}
$response = new ResponseData();
$response->code = 0;
$response->results = "Not enough parameters";

if( isset($_REQUEST['cityid']) )
{
    try
    {
        $scooterDTO = new ScooterDTO();
        $cityID = $_REQUEST['cityid'];
        $result = ScooterDAO::getScooterBycityID($cityID);
        $data = array('isHasScooterService'=>0,'message'=>"");
        
        if(!empty($result))
        {
            $result = $result[0];
            $data['isHasScooterService'] = 1;
            $data['message'] 		= $result->message;
            $response->code 		= 1;
            $response->results 		= $data;
        }
        else
        {
            $response->code = 1;
            $response->results = $data;
        }
    }
    catch (Exception $e)
    {
        $response->code = 307;
        $response->results = $e->json_body;
    }
}
echo json_encode($response);
?>
