<?php

require_once ('ConnectDB.php');

class CitiesDTO {

    public $geonameid;
    public $name;
    public $state;
    public $countryname;
    public $asciiname;
    public $check;

}

class City {
    public $name;
    public $id;
    public $check;
    public $country;
}

class CitiesDAO extends ConnectDB {

    //TODO - Insert your code here
    function CitiesDAO() {
        
    }

    public static function pulloutCityItem($row) {
        $cityDTO = new CitiesDTO();

        $cityDTO->countryname = $row['countryname'];
		$cityDTO->countrycode = $row['countrycode'];
        $cityDTO->name = $row['name'];
        $cityDTO->state = $row['statecode'];
        $cityDTO->geonameid = $row['geonameid'];
        $cityDTO->check = $row['check'];
        return $cityDTO;
    }

    public static function getCitesfromKeyword($keyword, $limit, $country) {
        $start = 0;
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            if ($country == "") 
            {
                $strSQL = "SELECT * from cities where name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%' limit 0, $limit"; // or alternatenames like '%$keyword%' limit 0, $limit";

                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    return $ds;
                }

                while ($row = mysql_fetch_array($result)) {
                    array_push($ds, CitiesDAO::pulloutCityItem($row));
                }
            }
            else
            {
                $strSQL = "SELECT * from cities where (name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%') and countryname = '$country' limit 0, $limit"; // or alternatenames like '%$keyword%' limit 0, $limit";
                
                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                   
                }
                else
                {
                    while ($row = mysql_fetch_array($result)) {
                        array_push($ds, CitiesDAO::pulloutCityItem($row));
                    }
                }
                
                $limit = $limit - count($ds);
                
                $strSQL = "SELECT * from cities where (name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%') and countryname != '$country' limit 0, $limit"; // or alternatenames like '%$keyword%' limit 0, $limit";
                
                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    
                }
                else
                {
                    while ($row = mysql_fetch_array($result)) {
                        array_push($ds, CitiesDAO::pulloutCityItem($row));
                    }
                }
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }


    public static function getAllCites() {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from cities";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, CitiesDAO::pulloutCityItem($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getCountriesfromKeyword($keyword, $limit = 0) {
        $start = 0;
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;
            
            $strSQL = "SELECT distinct countryname, countrycode from cities where countryname like '$keyword%' or countrycode like '$keyword%' limit 0, $limit";
            
            if($limit == 0)
                $strSQL = "SELECT distinct countryname, countrycode from cities where countryname like '$keyword%' or countrycode like '$keyword%'";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result)) {
                $cityDTO = new City();

                $cityDTO->id = $row['countrycode'];
                $cityDTO->name = $row['countryname'] . " (" . $cityDTO->id .")" ;
                $cityDTO->country = $row['countryname'];
                        
                array_push($ds, $cityDTO);
            }
            

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getAllCountries() 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT distinct countryname, countrycode from cities";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result)) 
            {
                $cityDTO = new City();

                $cityDTO->id = $row['countrycode'];
                $cityDTO->name = $row['countryname'];

                array_push($ds, $cityDTO);
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }

    public static function getCitesWithName($cityname) {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from cities where  name = '$cityname'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            while ($row = mysql_fetch_array($result)) {
                array_push($ds, CitiesDAO::pulloutCityItem($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
    
    public static function getCountryWithCountryID($id) 
    {
        $countryname = $id;
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT distinct countryname from cities where countrycode = '$id'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $id;
            }

            $row = mysql_fetch_array($result);
            $countryname = $row['countryname'];

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $countryname;
    }

    public static function getCityWithGeonamid($geonameid) {
        $city = new City();

        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT * from cities where  geonameid = '$geonameid'";

            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }

            $row = mysql_fetch_array($result);
            //$ds = CitiesDAO::pulloutCityItem($row);

            $city->id = $row['geonameid'];
            if ($row['statecode'] == "")
                $city->name = $row['name'] . ", " . $row['countryname'];
            else
                $city->name = $row['name'] . ", " . $row['statecode'] . ", " . $row['countryname'];

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $city;
    }

    public static function getMultiCitesfromKeyword($keyword, $limit, $country) {
        $start = 0;
        $ds = array();
        try 
        {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            if ($country == "") 
            {
                $strSQL = "SELECT * from cities where name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%' limit 0, $limit"; //  limit 0, $limit";

                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    return $ds;
                }

                while ($row = mysql_fetch_array($result)) {
                    $city = new City();
                    $city->id = $row['geonameid'];
                    if ($row['statecode'] == "")
                        $city->name = $row['name'] . ", " . $row['countryname'];
                    else
                        $city->name = $row['name'] . ", " . $row['statecode'] . ", " . $row['countryname'];
                    
                    $city->check = $row['check'];
                    $city->country = $row['countrycode'];
					$city->countryname = $row['countryname'];

                    array_push($ds, $city);
                }
            }
            else
            {
                $strSQL = "SELECT * from cities where (name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%') and countryname = '$country'  limit 0, $limit"; //  limit 0, $limit";

                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    
                }
                else
                {
                    while ($row = mysql_fetch_array($result)) {
                        $city = new City();
                        $city->id = $row['geonameid'];
                        if ($row['statecode'] == "")
                            $city->name = $row['name'] . ", " . $row['countryname'];
                        else
                            $city->name = $row['name'] . ", " . $row['statecode'] . ", " . $row['countryname'];

                        $city->check = $row['check'];
                        $city->country = $row['countrycode'];
                        
                        array_push($ds, $city);
                    }
                }
                
                $limit = $limit - count($ds);
                
                $strSQL = "SELECT * from cities where (name like '$keyword%' or asciiname like '$keyword%' or alternatenames like '$keyword%') and countryname != '$country'  limit 0, $limit"; //  limit 0, $limit";

                $result = mysql_query($strSQL, ConnectDB::$mLink);

                if ($result == false || mysql_num_rows($result) <= 0) {
                    
                }
                else
                {
                    while ($row = mysql_fetch_array($result)) {
                        $city = new City();
                        $city->id = $row['geonameid'];
                        if ($row['statecode'] == "")
                            $city->name = $row['name'] . ", " . $row['countryname'];
                        else
                            $city->name = $row['name'] . ", " . $row['statecode'] . ", " . $row['countryname'];

                        $city->check = $row['check'];
                        $city->country = $row['countrycode'];
                        
                        array_push($ds, $city);
                    }
                }
            }

            ConnectDB::CloseConnection();
        }
        catch (Exception $e) 
        {
            $result = FALSE;
        }

        return $ds;
    }

}

?>