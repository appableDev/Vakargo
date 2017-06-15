<?php
require_once('../model/DAO.php');
require_once './result.php';

$tt = new ResultGet();
$tt->code = 0;
$tt->count = 0;
$tt->results = "[]";
$tt->total = 0;

if (!isset($_REQUEST["sid"])) 
{
    $tt->results = "Not enough parameter";
    unset($tt->count);
    unset($tt->total);
    echo json_encode($tt);
}
else 
{
    if (isset($_REQUEST["sid"])) 
    {
        $space = SpaceDAO::getSpaceWithSpaceID($_REQUEST['sid']);
        
        if($space != NULL)
        {
            $flightlist = $space->list_flightid;
            $array_flightid = explode(",", $flightlist);
            $array_flight = array();
            
            for($i = 0; $i < count($array_flightid); $i++)
            {
                $fid = $array_flightid[$i];
                
                $flight = FlightDAO::getFlightWithFlightId($fid);
                unset($flight->date);
                unset($flight->departureAirportFsCode);
                unset($flight->arrivalAirportFsCode);
                unset($flight->timeadded);
                unset($flight->useradded);
                unset($flight->active);
                
                if($flight->airlineid == "" || $flight->flightnumber == "")
                {
                    $flight->isDriving = true;
                }
                else
                {
                    $flight->isDriving = true;
                }
                array_push($array_flight, $flight);
            }
            
            $tt->code = 1;
            $tt->total = count($array_flight);
            $tt->count = count($array_flight);
            $tt->results = $array_flight;
            
            echo json_encode($tt);
        }
        else
        {
            $tt->code = 403;
            $tt->results = "Space not found";
            unset($tt->count);
            unset($tt->total);
            echo json_encode($tt);
        }
    } 
    else 
    {
	echo json_encode($tt);
    }
}
?>