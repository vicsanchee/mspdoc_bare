<?php
/**
 * @author 		Jamal
 * @date 		17-Jun-2012
 * @Note  		Please follow the indentation
 *         		Please follow the naming convention
 */

include_once 'helperfunctions.php';

function db_query_list($field_names, $tbl_name, $where='', $start_index = 0, $limit = MAX_NO_OF_RECORDS, $orderby = '', $sortorder = 'asc', $db = NULL)
{
	try
	{
		$sql = "SELECT " 	. $field_names 	. " " .
				"FROM "		. $tbl_name 	. " ";

		if($where != "")
			$sql.= "WHERE ". $where;

		if($orderby!='')
			$sql .= " ORDER BY " . $orderby . " " . $sortorder;

		if($db === NULL)
			$db =& init_read_connection();

		$sql .= ' limit ' . $start_index . ',' . $limit;

		if(LOG_TRANS)
			log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n" . $sql, SERVICE_LOG);

		$stmt	 = $db->query($sql);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$results)
		{
		    $err = $db->errorInfo();
			if($err[0] != '00000')
            {
                 throw new Exception(implode(":",$db->errorInfo()));
                 close_read_con($db);
                 die;
            }
		}

		if($db != NULL)
			close_read_con($db);

		$results = array
		(
			'list' 		=> $results,
			'rec_index' => ($start_index + $limit)
		);

		return $results;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function db_query($field_names,
				      $tbl_name,
				      $where		= '',
				      $limit		= '',
				      $index		= 0,
				      $orderby		= '',
				      $sortorder	= 'asc',
				      $db 			= NULL)

{
	try
	{
		$sql = "SELECT " 	. $field_names 	. " " .
			   "FROM "		. $tbl_name 	. " ";

		if($where != "")
			$sql.= "WHERE ". $where;

		if($orderby!='')
			$sql .= " ORDER BY " . $orderby . " " . $sortorder;

		if($db === NULL)
			$db =& init_read_connection();

		if($limit != '')
			$sql .= ' limit ' .$index . ',' . $limit;

		if(LOG_TRANS)
			log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n" . $sql, SERVICE_LOG);

		$stmt	 = $db->query($sql);
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$results)
		{
       		 $err = $db->errorInfo();
			 if($err[0] != '00000')
             {
                 throw new Exception(implode(":", $db->errorInfo()));
                 close_read_con($db);
                 die;
             }
		}

		if($db != NULL)
			close_read_con($db);

		return $results;

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

//use only for deletion
function db_execute_sql($sql)
{
	try
	{
		if(LOG_TRANS)
			log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n" . $sql, SERVICE_LOG);

		$db 	=& init_connection();
		$stmt	= $db->query($sql);
		if (!$stmt->rowCount())
		{
       		 $err = $db->errorInfo();
			 if($err[0] != '00000')
             {
                 throw new Exception(implode(":", $db->errorInfo()));
                 close_con($db);
                 die;
             }
		}
		
		$row_count = $stmt->rowCount();
		close_con($db);
		return $row_count;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function db_execute_custom_sql($sql,$fetch_type = PDO::FETCH_ASSOC)
{
    try
    {
        if(LOG_TRANS)
            log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n" . $sql, SERVICE_LOG);

        $db 	  =& init_connection();
        $stmt	  = $db->query($sql);
        $results  = $stmt->fetchAll($fetch_type);
		if (!$results)
		{
       		 $err = $db->errorInfo();
			 if($err[0] != '00000')
             {
                 throw new Exception(implode(":", $db->errorInfo()));
                 close_con($db);
                 die;
             }
		}

		if($db != NULL)
			close_con($db);

		return $results;
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function db_execute_multiple_query($sql)
{
	try
	{
		if(LOG_TRANS)
			log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n" . $sql, SERVICE_LOG);

		$db 	=& init_connection();
		$stmt	= $db->query($sql);
		$stmt	->nextRowset();
		$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
		if (!$results)
		{
			 $err = $db->errorInfo();
			 if($err[0] != '00000')
             {
                 throw new Exception(implode(":", $db->errorInfo()));
                 close_con($db);
                 die;
             }
		}
		
		close_con($db);
		return $results;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function db_add($array_data, $tbl_name)
{
	try
	{
		foreach ( $array_data as $key => $value)
		{
			$col_array[] 	= str_replace(":","",$key);
			$bind_array[]	= $key;
		}

		$cols 	= implode(",",$col_array);
	  	$binds  = implode(",",$bind_array);

$sql = <<<EOSQL
  		INSERT INTO $tbl_name
		($cols)
  		VALUES
  		($binds)
EOSQL;

		$db	 		= &init_connection();
		$query		= $db->prepare($sql);
		$results 	= $query->execute($array_data);

		log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n " . print_r($array_data,true) . " \r\n Result " . $results,SERVICE_LOG);

		if (!$results)
		{
       	  	$err = $db->errorInfo();
			if($err[0] != '00000')
            {
                log_trans("Error at " . __FUNCTION__ . "function : Error detail" . implode(":",$db->errorInfo()),ERROR_LOG_FILE);
                throw new Exception(implode(":", $db->errorInfo()));
                close_con($db);
                die;
            }
		}
		else
		{
			$last_insert_id = $db->lastInsertId();
			close_con($db);
			return $last_insert_id;
			
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function db_update($array_data, $tbl_name, $primary_key,$second_key = false)
{
	try
	{
		$values = array();
		$and    = '';
		
		foreach ( $array_data as $key => $value)
		{
			$col_array[] = str_replace(':','',$key) . ' = ' . $key;
		}

		$cols 	= implode(", ", $col_array);

		if($second_key != false)
		{
			$and = " AND $second_key = :$second_key";
		}
		
		
		$sql = <<<EOSQL
  		UPDATE $tbl_name SET $cols WHERE $primary_key = :$primary_key 
EOSQL;
		$sql	.= $and;
		log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n " . print_r($sql,true),SERVICE_LOG);
		$db 		= &init_connection();
		$query		= $db->prepare($sql);
		$results 	= $query->execute($array_data);

		log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n " . print_r($array_data,true) . " \r\n Result " . $results,SERVICE_LOG);

		if (!$results)
		{
			$err = $db->errorInfo();
			if($err[0] != '00000')
            {
                log_trans("Error at " . __FUNCTION__ . "function : Error detail" . implode(":",$db->errorInfo()),ERROR_LOG_FILE);
                throw new Exception(implode(":",$db->errorInfo()));
                close_con($db);
                die;
            }
		}
		else
		{
			close_con($db);
			return $results;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function db_replace($array_data, $tbl_name)
{
	try
	{
		foreach ( $array_data as $key => $value)
		{
			$col_array[] 	= str_replace(":","",$key);
			$bind_array[]	= $key;
		}
		$cols 	= implode(",",$col_array);
		$binds  = implode(",",$bind_array);

		$sql = <<<EOSQL
  		REPLACE INTO $tbl_name
		($cols)
  		VALUES
  		($binds)
EOSQL;
		$db 		=& init_connection();
		$query		= $db->prepare($sql);
		$results 	= $query->execute($array_data);

		log_trans("Function Details: " . __FUNCTION__ . "\r\n Param Details: \r\n " . print_r($array_data,true) . " \r\n Result " . $results,SERVICE_LOG);

		if (!$results)
		{
			$err = $db->errorInfo();
			if($err[0] != '00000')
            {
                log_trans("Error at " . __FUNCTION__ . "function : Error detail" . implode(":",$db->errorInfo()),ERROR_LOG_FILE);
                throw new Exception(implode(":",$db->errorInfo()));
                close_con($db);
                die;
            }
		}
		else
		{
			close_con($db);
			return $results;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function &init_connection()
{
	try
	{
		static $db;
	    if(!$db)
	    {
    		$db 	= new PDO("mysql:host="   . constant('DB_HOSTNAME') .
    				";port="   . constant('DB_PORT') .
    				";dbname=" . constant('DB_NAME'),
    				constant('DB_USERNAME'), constant('DB_PASSWORD'));

    		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    }
    	return $db;
	}
	catch(PDOException $e)
	{
		handle_exception($e);
		echo "unable to connect to database server";die;
	}
}

function &init_read_connection()
{
	try
	{
		static $slave_db;
		$db_constant    = json_decode(constant('SLAVE_DB'));
		$config         = $db_constant[array_rand($db_constant)];
		if(!$slave_db)
		{
			$slave_db 	= new PDO("mysql:host="   . $config->host .
					";port="   . $config->port .
					";dbname=" . $config->dbname,
					$config->username, $config->password);
			$slave_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		return $slave_db;
	}
	catch(PDOException $e)
	{
		handle_exception($e);
		print_r($e);
		echo "unable to connect to database server";die;
	}
}

function close_read_con($db)
{
	$slave_db = null;
}

function close_con($db)
{
	$db = null;
}

?>
