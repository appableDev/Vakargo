<?php
require_once './result.php';
require_once('../model/DAO.php');
require_once('../model/Global.php');
require_once('../libs/stripe-php-1.10.1/lib/Stripe.php');
Stripe::setApiKey(Globals::$StripePrivateKey);

$rs = new ResultGet();
$rs->code = 0;
$rs->results = "Not enough parameters";
unset($rs->count);
unset($rs->total);

if( isset($_REQUEST['card']) && $_REQUEST['card'] != "" && 
    isset($_REQUEST['name']) && $_REQUEST['name'] != "" && 
    isset($_REQUEST['email']) && $_REQUEST['email'] != "" && 
    isset($_REQUEST['userid']) && $_REQUEST['userid'] != ""   )
{
    try
    {
        $customer = Stripe_Customer::create(array(
            "description" => "Vakargo Customer: " . $_REQUEST['name'],
            "card" => $_REQUEST['card'],
            "email" => $_REQUEST['email']
          ));
        
        $cus = Stripe_Customer::retrieve($customer->id);
        
        $card = $cus->cards->retrieve($cus->cards->data[0]->id);
        $card->name = $_REQUEST['name'];
        $card->save();
        
        $cusDTO = new CustomerDTO();
        $cusDTO->user_id = $_REQUEST['userid'];
        $cusDTO->customer_id = $customer->id;
        $cusDTO->name = $card->name;
        $cusDTO->last4 = $card->last4;
        $cusDTO->type = $card->type;
        $cusDTO->exp_month = $card->exp_month;
        $cusDTO->exp_year = $card->exp_year;
        
        if(isset($_REQUEST['primary']) && $_REQUEST['primary']=="on")
        {
            $cusDTO->default = "1";
        }
        else
        {
            $cusDTO->default = "0";
        }
        
        $result = CustomerDAO::addCustomer($cusDTO);
        
        if($result)
        {
            $rs->code = 1;
            $rs->results = $customer->id;
        }
        else
        {
            $rs->code = 302;
            $rs->results = "Cannot add this card to your profile";
        }
    }
    catch (Exception $e)
    {
        $rs->code = 301;
        $rs->results = $e->json_body;
    }
}
else
{
    $rs->code = 0;
    $rs->results = "Not enought parameter";
}

echo json_encode($rs);
?>
