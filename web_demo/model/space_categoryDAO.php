<?php

class Space_CategoryDTO
{
	public $space_id ;
	public $category_id;
	public $category_name;
        public $quantity;


	public function Space_CategoryDTO()
	{
	
	}
	
	public static function newSpace_CategoryDTO($space_id, $category_id, $quantity)
	{
	    $a = new Space_CategoryDTO();
	    $a->space_id = $space_id;
	    $a->category_id = $category_id;
            $a->quantity = $quantity;
	    return $a;
	}
}

/**
 * 
 */
class Space_CategoryDAO {

    function Space_CategoryDAO() {
	
    }

    public static function add(Space_CategoryDTO $cs) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

            $cs->timeadded = date("Y-m-d H:i:s");
	    $strSQL = "INSERT INTO `space_category`(`space_id`, `category_id`, `quantity`, `timeadded`) VALUES ('$cs->space_id','$cs->category_id','$cs->quantity', '$cs->timeadded')";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);
	    mysql_insert_id();
	    ConnectDB::CloseConnection();

	    return TRUE;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function deleteOfSpace($space_id) {
	$result = TRUE;

	try {
	    if (!ConnectDB::OpenConnection())
		return FALSE;

	    $strSQL = "Delete from `space_category` where space_id = '$space_id'";

	    $result = mysql_query($strSQL, ConnectDB::$mLink);

	    ConnectDB::CloseConnection();

	    return TRUE;
	} catch (Exception $e) {
	    return FALSE;
	}
    }
    
    public static function pulloutItem($row)
    {
		$csDTO = new Space_CategoryDTO();
	
        $csDTO->category_id = intval($row['category_id']);
		$csDTO->space_id = $row['space_id'];
		$csDTO->category_name= $row['category_name'];
        $csDTO->quantity= intval($row['quantity']);

        return $csDTO;
    }

    public static function getCategoryList($space_id) 
    {
        $ds = array();
        try {
            if (!ConnectDB::OpenConnection())
                return FALSE;

            $strSQL = "SELECT space_id, category_name, category.category_id as category_id, quantity from space_category, category where space_id = '$space_id' and category.category_id = space_category.category_id";
            
            $result = mysql_query($strSQL, ConnectDB::$mLink);

            if ($result == false || mysql_num_rows($result) <= 0) {
                return $ds;
            }
	    
            while ($row = mysql_fetch_array($result)) 
	    {
                array_push($ds, Space_CategoryDAO::pulloutItem($row));
            }

            ConnectDB::CloseConnection();
        } catch (Exception $e) {
            $result = FALSE;
        }

        return $ds;
    }
}

?>