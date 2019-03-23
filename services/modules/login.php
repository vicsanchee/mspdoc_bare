<?php
/**
 * @author 		Jamal
 * @date 		14-Nov-2015
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */

function check_login($params)
{
	try
	{
 		log_it(__FUNCTION__, $params);

		$username	= $params->username;
		$password	= $params->password;
		$device_info= if_property_exist($params, 'device_info',	'');
		$device_id  = if_property_exist($params, 'device_id',	0);

		if((isset($username)) && strval($username) === '')
		{
			return handle_fail_response('Username is mandatory');
		}
		
		if(constant('LDAP_SERVICE') == '')
		{
			if((isset($password)) && strval($password) === '')
			{
				return handle_fail_response('Password is mandatory');
			}
			
			$password_filter = " AND cms_employees.pswd = '" . $password . "'";
		}
		
		if((isset($password)) && strval($password) === '')
		{
			return handle_fail_response('Password is mandatory');
		}

		$rs = db_query("id as emp_id, name, email,office_email, username
						, is_admin, initial_pswd
						, (select id from cms_master_list where cms_master_list.id = cms_employees.access_level) as access_level
						
						, cms_emp_login_sessions.token
		                , accessible_ctg
						, malaysia_phone
						, concat('" . constant('UPLOAD_DIR_URL') . "', 'photos/',id,'.jpeg') as profile_pic
		                , super_admin ",
		    
						"cms_employees
						LEFT join cms_emp_login_sessions ON
						 	cms_employees.id = cms_emp_login_sessions.emp_id",
		    
						"cms_employees.username	= '". stripslashes($username) . "'" . $password_filter . " AND cms_employees.is_active = 1");


		if(count($rs) < 1 || !isset($rs))
		{
			return handle_fail_response('Invalid username or password');
		}
		else
		{
			if(constant('RESTRICT_DUPLICATE_LOGIN') == true)
			{
				if($rs[0]['token_emp_id'] != '')
				{
					return handle_fail_response('Duplicate Login, Please contact administrator');
				}
			}
			
			$params->emp_id 	= $rs[0]['emp_id'];
			$params->token		= $rs[0]['token'];

			if(is_login_token_valid($params) == FALSE)
			{
				$rs[0]['token'] = add_session($params);
			}
			
			
			$rs_access = db_query("cms_modules.descr, cms_employees_access.*",
						  		  "cms_employees_access INNER JOIN cms_modules ON cms_employees_access.module_id = cms_modules.id",
						   		  "cms_employees_access.emp_id	= ". $rs[0]['emp_id']);
			
			if(count($rs_access) < 1 || !isset($rs_access))
			{
				return handle_fail_response('No Accessibility level set for the user');
			}
			
			if($device_id != '')
			{
			    db_execute_sql("UPDATE cms_employees set device_info = '" . stripslashes(str_replace("'", "", $device_info)) . "',device_id='" . stripslashes($device_id) . "' WHERE id = " . $params->emp_id);
			}
			
			$rs[0]['access'] 	= $rs_access;
			$rs[0]['modules']	= arrange_accessibilty($rs_access);
			
			
			return handle_success_response('Success', $rs[0]);
		}

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function add_session($params)
{
	try
	{
		log_it(__FUNCTION__, $params);

		$token				= get_unique_id();
		$current_date		= get_current_date();
		$data 				= array
		(
			':token'   		=>  $token,
			':emp_id'    	=>  $params->emp_id,
			':login_time'	=>  $current_date,
			':last_active'	=>  $current_date,
			':client_ip'    =>  isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
		);
		$results 			= db_add($data, 'cms_emp_login_sessions');

		return $token;

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function update_session($params)
{
	try
	{
		log_it(__FUNCTION__, $params);

		$data = array
		(
			':token' 		=> 	$params->token,
			':emp_id'    	=>  $params->emp_id,
			':last_active'	=>  get_current_date(),
			':client_ip'    =>  isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : ''
		);
		$results = db_update($data,'cms_emp_login_sessions','token');

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function delete_session($params)
{
	try
	{
		log_it(__FUNCTION__, $params);

		$results = db_execute_sql("DELETE from cms_emp_login_sessions where token = '" . stripslashes($params->token) . "'");
		return handle_success_response('Success', TRUE);
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function is_login_token_valid($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		$valid = FALSE;

		if($params->token == '')
		{
			return FALSE;
		}

		$rs = db_query( "count(token) as token_count ",
						"cms_emp_login_sessions",
						"token	= '". stripslashes($params->token) . "'");

		if($rs[0]['token_count'] > 0)
		{
			$valid = TRUE;
			update_session($params);
		}

		return $valid;

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function arrange_accessibilty($rs)
{
	try
	{
		log_it(__FUNCTION__, $rs);
		
		$modules	= array();
		$access		= array();
		$rs_count	= count($rs);
		
		for($i = 0;$i < $rs_count;$i++)
		{
			if($rs[$i]['view_it'] == 1)
			{
				array_push($modules,$rs[$i]['module_id']);
			}
		}
		
		return $modules;
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

?>
