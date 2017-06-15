<?php
require_once '../model/Global.php';
require_once '../model/DAO.php';

$space = new SpaceDTO();

$data = $_REQUEST['data'];
$userid = $_REQUEST['userid'];

if(isset($_REQUEST['parameter']))
{
    $parameter = $_REQUEST['parameter'];
}

$data = json_decode($data);


$spaceinfo = $data->spaceinfo;
$travelplan = $data->travelplan;
$pickupinfo = $data->pickupinfo;
$deliveryinfo = $data->deliveryinfo;

//Space Info
$max_weight = $spaceinfo->maxweight;
$max_weight_unit = $spaceinfo->maxweight_unit;

$dimentionX = $spaceinfo->dimension->d_width;
$dimentionY = $spaceinfo->dimension->d_height;
$dimentionZ = $spaceinfo->dimension->d_depth;
$dimension_unit = $spaceinfo->dimension->unit;

$minimumbaseprice = $spaceinfo->minimumbaseprice;
$price = $spaceinfo->priceperweight;
$setprice = $spaceinfo->minimumbaseprice;
$itemallowed = $spaceinfo->itemallowed;

//Pickup Info
$latestPickupDate = $pickupinfo->latestPickupDate;
$pickupLocation = $pickupinfo->pickupAddresses[0]->city;
$amazonAddress = $pickupinfo->pickupAddresses[1]->city;

//Delivery Info
$drop_off_for_vakargo_scooter = $deliveryinfo->drop_off_for_vakargo_scooter;
$deliveryLocation = $deliveryinfo->deliveryAddressInfo->city;
$personal_delivery = $deliveryinfo->personal_delivery;
$latestDeliverDate = $deliveryinfo->latestDeliverDate;

$note = $data->note;

//Travel Plan
$iDrive = $travelplan->iDrive;
$routes = $travelplan->routes;


//===========================================================
$space->pickup_date = $latestPickupDate;
$space->delivery_date = $latestDeliverDate;
$space->price = $price;

$max_weight = $max_weight;

if ($max_weight_unit == "kg")
    $max_weight = number_format($max_weight / 2.20462, 2);


$space->max_weight = $max_weight;

$dimentionX = $dimentionX;
$dimentionY = $dimentionY;
$dimentionZ = $dimentionZ;
if ($dimension_unit == "cm") 
{
    $dimentionX = number_format($dimentionX * (1 * 2.54), 2);
    $dimentionY = number_format($dimentionY * (1 * 2.54), 2);
    $dimentionZ = number_format($dimentionZ * (1 * 2.54), 2);
}
$space->dimensionx = $dimentionX;
$space->dimensiony = $dimentionY;
$space->dimensionz = $dimentionZ;

$dateadded = date("Y-m-d h:m:s");
$space->date_create = $dateadded;
$space->setprice =  $setprice;
$space->note = mysql_escape_string($note);

$space->active = "1";

//echo $_SESSION[$F_FBUSERNAME];
$space->userid = $userid;


//$space->list_flightid = $_REQUEST['listflight'];
//===========================================================
$space->pickup_location = $pickupLocation;
$space->pickup_location = $deliveryLocation;

if($iDrive == false)
    $space->movingway = "flying";
else
    $space->movingway = "driving";

if($amazonAddress != "")
{
    $space->amazon = "1";
    $space->address = $amazonAddress;
}
else
{
    $space->amazon = "0";
    $space->address = "";
}

$space->insurance = "1";
$space->list_flightid = "";

$space_id = SpaceDAO::addSpace($space);

//===================================== ADD FLIGHT ==================================
$listflight = array();
for($i = 0; $i < count($routes); $i++)
{
    if($routes[$i]->isDriving == true)
    {
        $flightDTO = new FlightDTO();
        $flightDTO->airlineid = "";
        $flightDTO->flightnumber = "";
        $flightDTO->date = "";

        $flightDTO->departureDate = "";

        $flightDTO->departureAirportFsCode = "";
        $flightDTO->arrivalAirportFsCode = "";

        $flightDTO->departureCity = $routes[$i]->departure;
        $flightDTO->arrivalCity = $routes[$i]->destination;

        $flightDTO->flightId = "";
        $flightDTO->space_id = $space_id;

        $fid = FlightDAO::saveFlight($flightDTO, '1');
    }
    else 
    {
        $flightDTO = new FlightDTO();
        $flightDTO->airlineid = $routes[$i]->airlinecode;
        $flightDTO->flightnumber = $routes[$i]->flightNumber;
        $flightDTO->date = $routes[$i]->departureDate;

        $flightDTO->departureDate = "";

        $flightDTO->departureAirportFsCode = "";
        $flightDTO->arrivalAirportFsCode = "";

        $flightDTO->departureCity = $routes[$i]->departure;
        $flightDTO->arrivalCity = $routes[$i]->destination;

        $flightDTO->flightId = "";
        $flightDTO->space_id = $space_id;

        $fid = FlightDAO::saveFlight($flightDTO, '1');
    }
    
    array_push($listflight, $fid);
}

//=================================== END ADD FLIGHT ================================
$str_listflight = "";
for($i = 0; $i < count($listflight); $i++)
{
    if($i == 0)
        $str_listflight = $listflight[$i];
    else
        $str_listflight .= "," . $listflight[$i];
}

SpaceDAO::updateListFlightid($space_id, $str_listflight);


if($space_id != FALSE)
{
    $result = TRUE;

    if(isset($itemallowed->electronic))
    {
	$cs = Space_CategoryDTO::newSpace_CategoryDTO($space_id, "0", 0);
	$result = Space_CategoryDAO::add($cs);
    }
    
    if(isset($itemallowed->clothes))
    {
	$cs = Space_CategoryDTO::newSpace_CategoryDTO($space_id, "1", 0);
	$result = Space_CategoryDAO::add($cs);
    }
    
    if(isset($itemallowed->books))
    {
	$cs = Space_CategoryDTO::newSpace_CategoryDTO($space_id, "2", 0);
	$result = Space_CategoryDAO::add($cs);
    }
}
else
{
}
?>