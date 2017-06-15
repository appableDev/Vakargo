<?php
require_once ('ConnectDB.php');

class AirlineDTO
{
	public $airlineid ;
	public $airplinename;
        public $iata;
        public $icao;
        public $extra;
        public $country;
        public $n;
        
}
class Airline {
    public $name;
    public $id;
}


class AirlineDAO extends ConnectDB {

    //TODO - Insert your code here
    function AirlineDAO() {
        
    }

    public static function pulloutAirlineItem($row) {
        $airlineDTO = new AirlineDTO();

        $airlineDTO->airlineid = $row['airlineid'];
        $airlineDTO->airplinename = $row['airplinename'];
        $airlineDTO->iata = $row['iata'];
        $airlineDTO->icao = $row['icao'];
        $airlineDTO->country = $row['country'];
        $airlineDTO->extra = $row['extra'];
        $airlineDTO->n = $row['n'];
        return $airlineDTO;
    }

    public static function getAirlinefromKeyword($keyword, $limit) {
        $start = 0;
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            if($limit != 0)
                $strSQL = "SELECT * from airline where (airplinename like '$keyword%' or iata like '%$keyword%') and iata <> '' and n = 'Y' limit 0, $limit";
            else
                $strSQL = "SELECT * from airline where (airplinename like '$keyword%' or iata like '%$keyword%') and iata <> '' and n = 'Y'";
            //echo $strSQL;
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result)) 
            {
                $airl = new Airline();
                $temp = AirlineDAO::pulloutAirlineItem($row);
                $airl->id = $temp->iata;
                
                if($temp->iata != "")
                    $airl->name = $temp->airplinename . " ($temp->iata)";
                else
                    $airl->name = $temp->airplinename;
                
                array_push($ds, $airl);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }

}

?>