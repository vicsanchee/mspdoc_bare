<?php
/**
 * @author  : Darus
 * @date    : 28 aug 2017
 */

// require_once(dirname(__FILE__) . '/../config/config.inc.php');
// require_once(constant('SHARED_DIR') . '/dbfunctions.php');

function get_appt_list($params)
{
    try 
    {
        log_it(__FUNCTION__, $params);

        $past_appt 	= if_property_exist($params, 'past_appt');
        $emp_id    	= if_property_exist($params, 'emp_id');
        $super_admin= if_property_exist($params, 'super_admin',0,array(0,""));
        
        
        if(!isset($emp_id) || $emp_id == '')
        {
            return handle_fail_response('Missing employee ID.');
        }

        if($super_admin != 0)
        {
        	$where = '1=1';
        }
        else
        {
        	$where = "cms_appt.created_by = " . $emp_id;
        }
        
        if ($past_appt == 0)
        {
            $where .= " AND (cms_appt.status = 0  OR cms_appt.status = 4)";
        }
        else if ($past_appt == 1)
        {
            $where .= " AND cms_appt.status != 0 AND cms_appt.status != 4";
        }
        
        $rs = db_query
        (
            "cms_appt.id,
			cms_employees.name as emp_name,
            cms_appt.date_time,
            cms_appt.date_time_to,
            cms_appt.client,
            cms_clients.name as client_name,
            cms_appt.pic,
            cms_appt_pic.name as pic_name,
            cms_appt_pic.email,
            cms_appt_pic.phone,
            cms_appt_pic.designation,
            cms_appt.remarks,
            cms_appt.status,
            cms_appt.followup_date,
            cms_appt.appt_category,
            cms_master_list.descr as category_name,
            CASE
    			WHEN cms_appt.lat is NULL AND cms_appt.out_lat is NULL THEN 0
    			WHEN cms_appt.lat is NOT NULL AND cms_appt.out_lat is NULL THEN 1
    			WHEN cms_appt.lat is NOT NULL AND cms_appt.out_lat is NOT NULL THEN 2
			END  as check_type,
            if(ISNULL(check_out_time),0,1) as check_status",
            "((((cms_appt
                LEFT JOIN cms_clients ON cms_appt.client = cms_clients.id)
                INNER JOIN cms_appt_pic ON cms_appt.pic = cms_appt_pic.id)
				INNER JOIN cms_employees ON cms_appt.created_by = cms_employees.id)
                LEFT JOIN cms_master_list ON cms_appt.appt_category = cms_master_list.id)",
             $where . " ORDER BY cms_employees.name ASC, DATE(cms_appt.date_time) ASC"
        );

//         log_it("[DEBUG] - ", $rs);
        if (!isset($rs) || empty($rs))
        {
            return handle_fail_response('No client record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function get_appt_list_new($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$past_appt 		= if_property_exist($params, 'past_appt');
		$emp_id    		= if_property_exist($params, 'emp_id');
		$office_email   = if_property_exist($params, 'office_email');
		$super_admin	= if_property_exist($params, 'super_admin',0,array(0,""));
		
		
		if(!isset($emp_id) || $emp_id == '')
		{
			return handle_fail_response('Missing employee ID.');
		}
		
		if($super_admin != 0)
		{
			$where = '1=1';
		}
		else
		{
			$where = "(cms_appt.created_by = " . $emp_id . 
					 " OR (select group_concat(email) from cms_appt_personal where cms_appt.id = cms_appt_personal.appt_id) 
					 like '%" . $office_email . "%')";
		}
		
		if ($past_appt == 0)
		{
			$where .= " AND (cms_appt.status = 149)";
		}
		else if ($past_appt == 1)
		{
			$where .= " AND cms_appt.status != 149";
		}
		
		$rs = db_query
		(
			"cms_appt.id
            ,cms_appt.date_time
            ,cms_appt.date_time_to
	    	,(select group_concat(email) from cms_appt_personal where cms_appt.id = cms_appt_personal.appt_id) as pic_email
	    	,appt_status.descr as status
			, cms_appt.status as status_id
			, appt_status.descr as status
			,cms_appt.appt_category as category_id
            ,cms_master_list.descr as category_name
	    	,cms_appt.subject,cms_appt.location
			, cms_appt.created_by
			, (select  group_concat(concat(email,'-',
				CASE
	    			WHEN cms_appt_personal.lat is NULL AND cms_appt_personal.out_lat is NULL THEN 0
	    			WHEN cms_appt_personal.lat is NOT NULL AND cms_appt_personal.out_lat is NULL THEN 1
	    			WHEN cms_appt_personal.lat is NOT NULL AND cms_appt_personal.out_lat is NOT NULL THEN 2
				END))
				from cms_appt_personal where cms_appt.id = cms_appt_personal.appt_id   
			   ) as check_type
			,cms_employees.name as emp_name",
			"cms_appt
           		LEFT JOIN cms_clients ON cms_appt.client = cms_clients.id
				INNER JOIN cms_employees ON cms_appt.created_by = cms_employees.id
                LEFT JOIN cms_master_list ON cms_appt.appt_category = cms_master_list.id
				LEFT JOIN cms_master_list appt_status ON cms_appt.status= appt_status.id",
				$where . " ORDER BY DATE(cms_appt.date_time) ASC"
				);
		
		//         log_it("[DEBUG] - ", $rs);
		if (!isset($rs) || empty($rs))
		{
			return handle_fail_response('No client record found');
		}
		else
		{
			return handle_success_response('Success', $rs);
		}
		
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function get_appt_report_list($params)
{
    try {
        log_it(__FUNCTION__, $params);

        $start_index    = if_property_exist($params, 'start_index', 0);
        $limit          = if_property_exist($params, 'limit', MAX_NO_OF_RECORDS);
        $emp_id         = if_property_exist($params, 'emp_id');
        $employee_id    = if_property_exist($params, 'employee_id');
        $client_id      = if_property_exist($params, 'client_id');
        $pic_id         = if_property_exist($params, 'pic_id');
        $date_to        = if_property_exist($params, 'date_to');
        $date_from      = if_property_exist($params, 'date_from');
        $where          = 'cms_appt.id != ""';

        if(isset($employee_id) && $employee_id !== '')
        {
            $where .= " AND cms_appt.created_by = " . $employee_id;
        }
        if(isset($client_id) && $client_id !== '')
        {
            $where .= " AND cms_appt.client = " . $client_id;
        }
        if(isset($pic_id) && $pic_id !== '')
        {
            $where .= " AND cms_appt.pic = " . $pic_id;
        }
        if(isset($date_from) && $date_from !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) >= '" . $date_from . "'";
        }
        if(isset($date_to) && $date_to !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) <= '" . $date_to . "'";
        }

        $rs = db_query_list
        (
            " cms_appt.id
            , (select name from cms_employees where cms_appt.created_by = cms_employees.id) as employee_name
            , cms_appt.date_time
            , cms_appt.date_time_to
            , IFNULL((select name from cms_clients where cms_appt.client = cms_clients.id),'') as client_name
			, (select group_concat(email) from cms_appt_personal where cms_appt.id = cms_appt_personal.appt_id) as pic_name
			, cms_appt.outcome_of_meeting
			, cms_appt.status
			, cms_master_list.descr as status_desc
            ",
            "cms_appt INNER JOIN cms_master_list ON cms_appt.status = cms_master_list.id", $where, $start_index, $limit, "id", "desc"
        );

        if (count($rs['list']) < 1 || !isset($rs['list']))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function get_appt_employee_list($params)
{
    try 
    {
        $rs = db_query('id,name','cms_employees');

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function get_client_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $emp_id             = if_property_exist($params, 'emp_id');
        $is_super_admin     = if_property_exist($params, 'is_super_admin');
        
        
        if($is_super_admin == 1)
        {
            $where = '1=1';
        }
        else if($emp_id != '')
        {
            $where  = "emp_id = " . $emp_id;
        }
        
        $rs = db_query
        (
            'cms_clients.id,
            cms_clients.name,
            cms_clients.industry,
			cms_clients.services_offered,
			cms_clients.remarks,
			cms_clients.source,
			cms_clients.sent_notification,
            cms_master_list.descr as industry_name, cms_employees.name as create_by',
            'cms_clients
                INNER JOIN cms_master_list ON cms_clients.industry = cms_master_list.id 
                LEFT JOIN cms_employees on cms_clients.emp_id = cms_employees.id',
            $where,'','','name'
        );

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No clients record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function get_pic_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $client_id          = if_property_exist($params, 'client_id');
        $emp_id             = if_property_exist($params, 'emp_id');
        $is_super_admin     = if_property_exist($params, 'is_super_admin');
        
        $where     = '';

        if($client_id === NULL)
        {
            return handle_fail_response('Missing client ID');
        }
        
        if($is_super_admin == 1)
        {
        }
        else if (isset($emp_id) && $emp_id !== '')
        {
            $where = " AND created_by = " . $emp_id;
        }

        $rs = db_query('id, name, email, phone, designation', 'cms_appt_pic', 'client = ' . $client_id . $where);

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No contact person record found.');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function get_client_category($params)
{
    try 
    {
        log_it(__FUNCTION__, $params);

        $rs = db_query("descr, id", "cms_master_list", "category_id = 22");

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No person record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function add_edit_appt($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id             = if_property_exist($params, 'id');
        $date_time      = if_property_exist($params, 'date_time');
        $date_time_to   = if_property_exist($params, 'date_time_to');
        $client         = if_property_exist($params, 'client');
        $pic            = if_property_exist($params, 'pic');
        $remarks        = if_property_exist($params, 'remarks');
        $status         = if_property_exist($params, 'status');
        $emp_id         = if_property_exist($params, 'emp_id');
        $followup_date  = if_property_exist($params, 'followup_date');
        $category       = if_property_exist($params, 'category');

        if (!$date_time || !$date_time_to || !$client || !$pic || !$category)
        {
            return handle_fail_response('Missing parameter');
        }

        if ($status == 4 && !$followup_date)
        {
            return handle_fail_response('Missing follow up date');
        }

        $data = array
        (
            ':date_time'    => $date_time,
            ':date_time_to' => $date_time_to,
            ':client'       => $client,
            ':pic'          => $pic,
            ':status'       => $status,
            ':followup_date'=> $followup_date,
            ':remarks'      => $remarks,
            ':appt_category'=> $category
        );

        if ($status == 1 || $status == '1')
        {
           $create_status = json_decode(create_timesheet_task($params));
           if($create_status->code == 1)
           {
               return json_encode($create_status);
           }
        }

        if ($id && is_data_exist('cms_appt', 'id', $id))
        {
            $data[':id']    = $id;
            $data[':status']= $status;
            $data           = add_timestamp_to_array($data, $emp_id, 1);
            $result         = db_update($data, 'cms_appt', 'id');
        }
        else
        {            
            if(is_date_bigger_than_current_date($date_time) == 0)
            {
                return handle_fail_response('Appointment date/time cannot be lesser than current date/time');
            }
            
            $create_ics = json_decode(send_appointment_ics($params));
            if($create_ics->code == 1)
            {
                return json_encode($create_ics);
            }
            
            $data[':status']= 0;
            $data           = add_timestamp_to_array($data, $emp_id, 0);
            $result         = db_add($data, 'cms_appt', 'id');
            
        }

        if ($result != '')
        {
            return handle_success_response('Success', $result);
        }
        else
        {
            return handle_fail_response('Add/Edit Appointments Failed');
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function add_edit_appt_new($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$id             	= if_property_exist($params, 'id');
		$to         		= if_property_exist($params, 'to');
		$subject			= if_property_exist($params, 'subject');
		$category       	= if_property_exist($params, 'category');
		$location			= if_property_exist($params, 'location');
		$date_time_start	= if_property_exist($params, 'date_time_start');
		$date_time_end		= if_property_exist($params, 'date_time_end');
		
		$date_pp_time_start	= if_property_exist($params, 'date_pp_time_start',null,array(null,""));
		$date_pp_time_end	= if_property_exist($params, 'date_pp_time_end',null,array(null,""));
		
		$descr				= urldecode(if_property_exist($params, 'descr'));
		$outcome			= if_property_exist($params, 'outcome');
		
		$status         	= if_property_exist($params, 'status');
		$emp_id         	= if_property_exist($params, 'emp_id');
		$emp_email			= if_property_exist($params, 'emp_email');
		
		if (!$date_time_start|| !$date_time_end|| !$to|| !$subject|| !$category)
		{
			return handle_fail_response('Missing parameter');
		}

		$data = array
		(
			':date_time'   			=> $date_time_start,
			':date_time_to' 		=> $date_time_end,
			':status'       		=> $status,
			':remarks'     			=> $descr,
			':appt_category' 		=> $category,
			':subject'				=> $subject,
			':location'				=> $location,
			':outcome_of_meeting'	=> $outcome
		);
		
		if ((int)$status == 150)
		{
			$create_status = json_decode(create_timesheet_task_new($params));
			if($create_status->code == 1)
			{
				return json_encode($create_status);	
			}
		}
		
		if ($id && is_data_exist('cms_appt', 'id', $id))
		{
			$data[':id']    = $id;
			$data[':status']= $status;
			$data           = add_timestamp_to_array($data, $emp_id, 1);
			$result         = db_update($data, 'cms_appt', 'id');
			
			if ((int)$status == 151 || (int)$status == 152) // follow up and postponed appointments
			{
				$data = array
				(
					':date_time'    => $date_pp_time_start,
					':date_time_to' => $date_pp_time_end,
					':remarks'      => $descr,
					':appt_category'=> $category,
					':subject'		=> $subject,
					':location'		=> $location,
					':status'		=> 149,  // make this a new appointment
					':followup_date'=> $date_pp_time_start
				);
				
				$data           = add_timestamp_to_array($data, $emp_id, 0);
				$id				= db_add($data, 'cms_appt');
			}
			
			$params->id		= $id;
			$params->fn		= "bg_send_appt_invite";
			$params->descr	= "";
			$params->to[]   = $params->emp_email;
			add_edit_appt_invites($params);
			
		}
		else
		{
			if(is_date_bigger_than_current_date($date_time_start) == 0)
			{
				return handle_fail_response('Appointment date/time cannot be lesser than current date/time');
			}
			
			$data           = add_timestamp_to_array($data, $emp_id, 0);
			$id				= db_add($data, 'cms_appt');
			
			$params->id		= $id;
			$params->fn		= "bg_send_appt_invite";
			$params->descr	= "";
			$params->to[]   = $params->emp_email;
			add_edit_appt_invites($params);
		}
		
		if ($id != '')
		{
			return handle_success_response('Success', $id);
		}
		else
		{
			return handle_fail_response('Add/Edit Appointments Failed');
		}
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function add_edit_appt_invites($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$p_email		= array();
		$appt_id        = if_property_exist($params, 'id');
		$email_arr		= if_property_exist($params, 'to');
		$emp_id         = if_property_exist($params, 'emp_id');
		
		
		if (!$appt_id|| !$email_arr|| !$emp_id)
		{
			return handle_fail_response('Missing parameter');
		}
		
		$email_arr_count 	= count($email_arr);
		
		for($i = 0; $i < $email_arr_count; $i++)
		{
			$data = array
			(
				':appt_id' 	=> $appt_id,
				':email' 	=> $email_arr[$i]
			);
			
			$rs = db_execute_custom_sql("SELECT id from cms_appt_personal where email='" . $email_arr[$i]. "' AND appt_id=" . $appt_id);

			if($rs == NULL)
			{
				$data		= add_timestamp_to_array($data, $emp_id, 0);
				$result		= db_add($data, 'cms_appt_personal');
				$p_email[] 	= $email_arr[$i];
			}
		}
		$params->to_filtered = $p_email;
		
// 		bg_send_appt_invite($params);
		
		$pid = run_in_background(constant('JOB_DIR') . "/background_fn.php  " . urlencode(json_encode($params)));
		
		return handle_success_response('Success', $appt_id);
		
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function create_timesheet_task_new($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$date_time    = if_property_exist($params, 'date_time_start');
		$date_time_to = if_property_exist($params, 'date_time_end');
		$to 		  = if_property_exist($params, 'to');
		$emp_id       = if_property_exist($params, 'emp_id');
		$category     = if_property_exist($params, 'category');
		
		$pic_name		= $to[0];
		$client_name	= 'UNKNOWN';
		$appt_type      = db_query("descr", "cms_master_list", "id = " . $category);
		$rs_client      = db_query("cms_appt_pic.name as pic_name, cms_clients.name as client_name",
								   "cms_appt_pic INNER JOIN cms_clients ON cms_appt_pic.client = cms_clients.id",
								   "cms_appt_pic.email = '" . $to[0]. "'");
		
		if (isset($rs_client) && count($rs_client) > 0)
		{
			$pic_name 		= $rs_client[0]['pic_name'];
			$client_name 	= $rs_client[0]['client_name'];
		}
		$task           =  $appt_type[0]['descr'] . " with " . $pic_name . " from " . $client_name;
		
		
		if (!$date_time)
		{
			return handle_fail_response('Saving in timesheet failed, missing date/time.');
		}
		else if (!$emp_id)
		{
			return handle_fail_response('Saving in timesheet failed, missing employee ID.');
		}
		
		
		$app_date  = date('Y-m-d', strtotime($date_time));
		
		// Get timesheet ID
		$timesheets = db_query
		(
			"id,supervisor_id",
			"cms_timesheet",
			"from_date <= '" . $app_date . "' AND to_date >= '" . $app_date . "' AND employee_id = " . $emp_id
		);
		
		if(count($timesheets) < 1 || !isset($timesheets))
		{
			return handle_fail_response('No timesheet found');
		}
		else
		{
			// Add tasks to timesheet
			foreach($timesheets as $timesheet)
			{
				$data = array
				(
					':timesheet_id'		=>  $timesheet['id'],
					':task_date'		=> 	$date_time,
					':from_time'		=>  date("H:i", strtotime($date_time)),
					':to_time'			=> 	date("H:i", strtotime($date_time_to)),
					':task'				=> 	$task,
					':created_by'		=>  $emp_id
				);
				
				$data   = add_timestamp_to_array($data,$emp_id,0);
				$result = db_add($data, 'cms_timesheet_task');
				
				if ($result != '')
				{
					return handle_success_response('Success', $result);
				}
				else
				{
					return handle_fail_response('Adding appointment as timesheet task failed');
				}
			}
		}
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function create_timesheet_task($params)
{
    try 
    {
        log_it(__FUNCTION__, $params);

        $date_time    = if_property_exist($params, 'date_time');
        $date_time_to = if_property_exist($params, 'date_time_to');
        $client       = if_property_exist($params, 'client');
        $pic          = if_property_exist($params, 'pic');
        $emp_id       = if_property_exist($params, 'emp_id');

        $pic_name    = db_query("name", "cms_appt_pic", "id = " . $pic);
        $client_name = db_query("name", "cms_clients", "id = " . $client);
        $task        = "Appointment with " . $pic_name[0]['name'] . " from " . $client_name[0]['name'];

        if (!$date_time)
        {
            return handle_fail_response('Saving in timesheet failed, missing date/time.');
        }
        else if (!$emp_id)
        {
            return handle_fail_response('Saving in timesheet failed, missing employee ID.');
        }

        
        $app_date  = date('Y-m-d', strtotime($date_time));
        // Get timesheet ID
        $timesheets = db_query
        (
            "id
            ,supervisor_id",
            "cms_timesheet",
            "from_date <= '" . $app_date . "' AND to_date >= '" . $app_date . "' AND employee_id = " . $emp_id
        );

        if(count($timesheets) < 1 || !isset($timesheets))
        {
            return handle_fail_response('No timesheet found');
        }
        else
        {
            // Add tasks to timesheet
            foreach($timesheets as $timesheet)
            {
                $data = array
    			(
    				':timesheet_id'		=>  $timesheet['id'],
    				':task_date'		=> 	$date_time,
    				':from_time'		=>  date("H:i", strtotime($date_time)),
    				':to_time'			=> 	date("H:i", strtotime($date_time_to)),
    				':task'				=> 	$task,
    				':created_by'		=>  $emp_id
    			);

                $data   = add_timestamp_to_array($data,$emp_id,0);
				$result = db_add($data, 'cms_timesheet_task');

                if ($result != '')
                {
                    return handle_success_response('Success', $result);
                }
                else
                {
                    return handle_fail_response('Adding appointment as timesheet task failed');
                }
            }
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function send_appointment_ics($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $start_date     = if_property_exist($params, 'date_time');
        $end_date       = if_property_exist($params, 'date_time_to');
        $client         = if_property_exist($params, 'client');
        $pic            = if_property_exist($params, 'pic');
        $emp_id         = if_property_exist($params, 'emp_id');
        $emp_name       = if_property_exist($params, 'emp_name');
        $emp_email      = if_property_exist($params, 'emp_email');
        $category       = if_property_exist($params, 'category');

        $pic_name       = db_query("name, email", "cms_appt_pic", "id = " . $pic);
        $client_name    = db_query("name", "cms_clients", "id = " . $client);
        $appt_type      = db_query("descr", "cms_master_list", "id = " . $category);
        $task           =  $appt_type[0]['descr'] . " with " . $pic_name[0]['name'] . " from " . $client_name[0]['name'];

        if (!$start_date)
        {
            return handle_fail_response('Saving in timesheet failed, missing date/time.');
        }
        else if (!$client)
        {
            return handle_fail_response('Client information is missing');
        }
        else if (!$pic)
        {
            return handle_fail_response('Person incharge information is missing');
        }
        else if (!$emp_id)
        {
            return handle_fail_response('Saving in timesheet failed, missing employee ID.');
        }

        if($pic_name[0]['email'] == '')
        {
            return handle_fail_response('Person incharge email address cannot be empty');
        }
        else
        {	
            include_once constant('LIB_DIR') . '/ics.php';
            $ics = new ICS(array
            (
                'description'   => $task,
                'dtstart'       => $start_date,
                'dtend'         => $end_date,
                'summary'       => "Meeting with " . $emp_name
            ));
            
            $ics_file_contents  = $ics->to_string();
            
            $ics_name           = date('Y-m-d', strtotime($start_date)) . '_' . $client_name[0]['name'] . '.ics';
            
            if(!is_dir(constant('LOG_DIR') . '/ics'))
            {
                mkdir(constant('LOG_DIR') . '/ics' ,0755,TRUE);
            }
            
            $fh = fopen(constant('LOG_DIR') . '/ics/' . $ics_name, 'w');
            if($fh)
            {
                fwrite($fh, $ics_file_contents);
                fclose($fh);
            }
            else 
            {
                return handle_fail_response('ERROR','Send Email to Client Fail. Please re-try again later');
            }
			
            $template 	= file_get_contents(constant('TEMPLATE_DIR') . '/appointment_notification.html');
            $replace 	= array('{APP_TITLE}', '{PIC_NAME}', '{USER_NAME}','{START_DATE}','{END_DATE}','{APPT_TYPE}','{MAIL_SIGNATURE}');
            $with 		= array(constant('APPLICATION_TITLE'), $pic_name[0]['name'], $emp_name, $start_date, $end_date,$appt_type[0]['descr'], constant('MAIL_SIGNATURE'));
            $body		= str_replace($replace, $with, $template);
            
            if(smtpmailer
                (
                    $pic_name[0]['email'],
                    constant('MAIL_USERNAME'),
                    constant('MAIL_FROMNAME'),
                    'Scheduled Appointments',
                    $body,
                    $emp_email, constant('LOG_DIR') . '/ics/' . $ics_name
                ))
            {
            	$msg_date 	= date('Y-m-d', strtotime($start_date)) . ' ' . date('H:i', strtotime($start_date)) . ' - ' . date('H:i', strtotime($end_date));
            	$msg 		= $emp_name . ' ' .  $appt_type[0]['descr'] . ' ' . $pic_name[0]['name']. '(' . $client_name[0]['name']. ') ON '  . $msg_date;
            	send_sms_to_super_admin($msg);
                return handle_success_response('Success','Send Email Success');
            }
            else
            {
                return handle_fail_response('ERROR','Send Email to Client Fail. Please re-try again later');
            }
            
            
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function add_edit_adhoc_appt($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id               = if_property_exist($params, 'id');
        $client           = if_property_exist($params, 'client');
        $pic              = if_property_exist($params, 'pic');
        $status           = if_property_exist($params, 'status', 0);
        $location         = if_property_exist($params, 'location');
        $checkin_datetime = if_property_exist($params, 'checkin_datetime');
        $emp_id           = if_property_exist($params, 'emp_id');

        if (!$client || !$pic || !$location || !$checkin_datetime)
        {
            return handle_fail_response('Missing client ID or person ID');
        }

        $data = array
        (
            ':client'          => $client,
            ':pic'             => $pic,
            ':adhoc'           => 1,
            ':status'          => $status,
            ':location'        => $location,
            ':checkin_datetime'=> $checkin_datetime
        );

        if ($id && is_data_exist('cms_appt', 'id', $id))
        {
            $data[':id']    = $id;
            $data           = add_timestamp_to_array($data, $emp_id, 1);
            $result         = db_update($data, 'cms_appt', 'id');
        }
        else
        {
            $data           = add_timestamp_to_array($data, $emp_id, 0);
            $result         = db_add($data, 'cms_appt', 'id');
        }

        if ($result != '')
        {
            return handle_success_response('Success', $result);
        }
        else
        {
            return handle_fail_response('Add/Edit Ad-hoc Failed');
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function add_edit_pic($params)
{
    try 
    {
        log_it(__FUNCTION__, $params);

        $id             = if_property_exist($params, 'id');
        $name           = if_property_exist($params, 'name');
        $client         = if_property_exist($params, 'client');
        $email          = if_property_exist($params, 'email');
        $phone          = if_property_exist($params, 'phone');
        $designation    = if_property_exist($params, 'designation');
        $emp_id         = if_property_exist($params, 'emp_id');
        $emp_name		= if_property_exist($params, 'emp_name');

        if (!$name || !$client || !$emp_id)
        {
            return handle_fail_response('Missing parameter(s)');
        }

        $data = array
        (
            ':name'         => $name,
            ':client'       => $client,
            ':email'        => $email,
            ':phone'        => $phone,
            ':designation'  => $designation
        );

        if ($id && is_data_exist('cms_appt_pic', 'id', $id))
        {
            $data[':id']    = $id;
            $data           = add_timestamp_to_array($data, $emp_id, 1);
            $result         = db_update($data, 'cms_appt_pic', 'id');
        }
        else
        {
            $data           = add_timestamp_to_array($data, $emp_id, 0);
            $result         = db_add($data, 'cms_appt_pic');
        }

        if ($result != '')
        {
        	$rs	= db_query("cms_employees.office_email,cms_employees.name,cms_master_employer.mail_signature"
        			,'cms_employees INNER JOIN 
					  cms_master_employer on cms_employees.employer_id = cms_master_employer.id'
        			,'cms_employees.id = 24');
        	
        	
        	$template 		= file_get_contents(constant('TEMPLATE_DIR') . '/pic_added_notification.html');
        	$replace 		= array('{NAME}', '{EMPLOYEE}','{PIC_NAME}','{PIC_EMAIL}','{PIC_PHONE}','{PIC_DESG}','{MAIL_SIGNATURE}','{APP_TITLE}');
        	$with 			= array($rs[0]['name'],$emp_name,$name,$email,$phone,$designation,$rs[0]['mail_signature'],constant('APPLICATION_TITLE'));
        	$body			= str_replace($replace, $with, $template);
        	
        	smtpmailer
        	(
        		$rs[0]['office_email'],
        		constant('MAIL_USERNAME'),
        		constant('MAIL_FROMNAME'),
        		"PIC Information Added / Changed",
        		$body
        	);
        	
            return handle_success_response('Success', $result);
        }
        else
        {
            return handle_fail_response('Add/Edit Person in charge Failed');
        }

    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function add_client($params)
{
    try 
    {
        log_it(__FUNCTION__, $params);

        $name       		= if_property_exist($params, 'name');
        $industry   		= if_property_exist($params, 'industry');
        $services_offered	= if_property_exist($params, 'services_offered');
        $source				= if_property_exist($params, 'source');
        $remarks			= if_property_exist($params, 'remarks');
        $sent_notification	= if_property_exist($params, 'sent_notification');
        $emp_id     		= if_property_exist($params, 'emp_id');
        $id         		= if_property_exist($params, 'id');

        if (!$name || !$industry || !$emp_id)
        {
            return handle_fail_response('Missing parameters');
        }

        $data = array
        (
            ':name'      			=> $name,
            ':industry'  			=> $industry,
        	':services_offered'  	=> $services_offered,
        	':source'  				=> $source,
        	':remarks'  			=> $remarks,
        	':sent_notification'  	=> $sent_notification
        );

        if ($id)
        {
            $data[':id']  = $id;
            $data           = add_timestamp_to_array($data, $emp_id, 1);
            $result         = db_update($data, 'cms_clients', 'id');
        }
        else
        {
        	$data[':emp_id']= $emp_id;
            $data           = add_timestamp_to_array($data, $emp_id, 0);
            $result         = db_add($data, 'cms_clients', 'id');
        }

        if($result != '')
        {
            return handle_success_response('Success', $result);
        }
        else
        {
            return handle_fail_response('Failed to add client');
        }
    } 
    catch (Exception $e) 
    {
        handle_exception($e);
    }
}

function get_appt_dashboard_list($params)
{
    try
    {

        log_it(__FUNCTION__, $params);

        $emp_id         = if_property_exist($params, 'emp_id');
        $date_to        = if_property_exist($params, 'date_to');
        $date_from      = if_property_exist($params, 'date_from');
        $where          = "cms_employees.is_active = 1";

        if(isset($date_from) && $date_from !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) >= '" . $date_from . "'";
        }
        if(isset($date_to) && $date_to !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) <= '" . $date_to . "'";
        }

        $where .= " AND (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id) != 0";
        
        $rs = db_query
        (
            " cms_employees.name,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id) as appt_total,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id and cms_appt.status = 0) as appt_open,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id and cms_appt.status = 4) as appt_follow_up,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id and cms_appt.status = 1) as appt_took_place,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id and cms_appt.status = 2) as appt_postponed,
            (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id and cms_appt.status = 3) as appt_cancelled
            ",
            "cms_employees", $where, '', '', "appt_total", "desc"
        );

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function get_appt_summary_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $emp_id         = if_property_exist($params, 'emp_id');
        $date_to        = if_property_exist($params, 'date_to');
        $date_from      = if_property_exist($params, 'date_from');
        $where          = "cms_employees.is_active = 1 ";
        
        if(isset($emp_id) && $emp_id !== '')
        {
            $where .= " AND cms_appt.created_by in (" . $emp_id . ")";
        }
        if(isset($date_from) && $date_from !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) >= '" . $date_from . "'";
        }
        if(isset($date_to) && $date_to !== '')
        {
            $where .= " AND DATE(cms_appt.date_time) <= '" . $date_to . "'";
        }

        $where .= " AND (select count(*) from cms_appt where cms_appt.created_by = cms_employees.id) != 0 group by cms_appt_personal.email";
        
        $rs = db_query
        (
            "    cms_employees.name as emp_name
				, cms_appt_personal.email
				, count(*) as meet_count
				, SEC_TO_TIME(sum(time_to_sec(cms_appt.date_time_to)- time_to_sec(cms_appt.date_time))) as total_hour
            ",
            "cms_appt
            INNER JOIN cms_employees on cms_appt.created_by = cms_employees.id
			INNER JOIN cms_appt_personal on cms_appt.id = cms_appt_personal.appt_id", $where, '', '', '', ''
        );

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function get_appt_summary_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $email = if_property_exist($params, 'email');

        if($email=== NULL)
        {
            return handle_fail_response('Email cannot be empty');
        }

        $rs = db_query
        (
            "date_format(cms_appt.date_time,'" . constant('DISPLAY_DATE_FORMAT') . "') as dates 
             ,concat( date_format(cms_appt.date_time,'%h:%i %p'), ' - ' , date_format(cms_appt.date_time_to,'%h:%i %p') ) as times 
             ,date_format(timediff(date_time_to,date_time),'%H Hours  %i Minutes') as duration
             ,IFNULL(cms_appt_personal.check_in_time,'') as check_in_time
			 ,IFNULL(cms_appt_personal.lat,'') as lat
			 ,IFNULL(cms_appt_personal.out_lat,'') as out_lat
			 ,IFNULL(cms_appt_personal.lng,'') as lng
			 ,IFNULL(cms_appt_personal.out_lng,'') as out_lng
			 ,IFNULL(cms_appt_personal.check_out_time,'') as check_out_time 
			 ,IFNULL(cms_appt_personal.check_in_address,'') as check_in_address
			 ,IFNULL(cms_appt_personal.check_out_address,'') as check_out_address
            ",
            "cms_appt
            	INNER JOIN cms_appt_personal on cms_appt.id = cms_appt_personal.appt_id", "cms_appt_personal.email in('" . $email.  "')", '', '', "cms_appt.date_time", "desc"
        );

        if (count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
            
       	return handle_success_response('Success', $rs);
        
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function update_appt_gps($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $lat        		= if_property_exist($params, 'lat');
        $lng        		= if_property_exist($params, 'lng');
        $emp_id     		= if_property_exist($params, 'emp_id');
        $emp_name   		= if_property_exist($params, 'emp_name');
        $id         		= if_property_exist($params, 'id');
        $check_type 		= if_property_exist($params, 'check_type');
        $check_type_desc 	= '';
        
        if($id === NULL)
        {
            return handle_fail_response('Id cannot be empty');
        }
        
        if($check_type == 0)
        {
            $data = array
            (
                ':id'               => $id,
                ':lat'              => $lat,
                ':lng'              => $lng,
                ':check_in_time'    => date(' H:i:s')
            );
            $check_type_desc = "CHECK-IN";
        }
        else 
        {
            $data = array
            (
                ':id'               => $id,
                ':out_lat'          => $lat,
                ':out_lng'          => $lng,
                ':check_out_time'   => date(' H:i:s')
            );
            $check_type_desc = "CHECK-OUT";
        }

        $data           = add_timestamp_to_array($data, $emp_id, 1);
        $result         = db_update($data, 'cms_appt', 'id');

        if($result != '')
        {
        	
        	$rs 			= db_query("device_id as push_id", "cms_employees","super_admin = 1");

        	if(count($rs) > 0)
        	{
        		$in_address = get_gmap_address($lat,$lng);
        		
        		$msg 		=
        		'{
					"data":
					{
						"msg"		: "' . $emp_name . " " . $check_type_desc . " at " . date('H:i:s') . '",
						"data"		: "' . $in_address . '"
					}
				}';

        		push_it_to_ios($rs,json_decode($msg));
        	}
        	
            $type           = array('check_type' => (int)($check_type + 1));
            $list           = json_decode(get_appt_list($params));
            
            $return_data    = array('list' => $list->data, 'type' => $type);
            
            return handle_success_response('Success', $return_data);
        }
        else
        {
            return handle_fail_response('Failed to add gps location');
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function update_appt_gps_new($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$id         		= if_property_exist($params, 'id');
		$lat        		= if_property_exist($params, 'lat');
		$lng        		= if_property_exist($params, 'lng');
		$emp_id     		= if_property_exist($params, 'emp_id');
		$emp_name   		= if_property_exist($params, 'emp_name');
		$email   			= if_property_exist($params, 'office_email');
		$check_type 		= if_property_exist($params, 'check_type');
		$check_type_desc 	= '';
		
		if($id === NULL)
		{
			return handle_fail_response('Id cannot be empty');
		}
		
		$in_address = get_gmap_address($lat,$lng);
		
		if($check_type == 0)
		{
			$data = array
			(
				':appt_id'         	=> $id,
				':lat'              => $lat,
				':lng'              => $lng,
				':check_in_time'    => date('H:i:s'),
				':check_in_address'	=> $in_address,
				":email"			=> $email
			);
			$check_type_desc = "CHECK-IN";
		}
		else
		{
			$data = array
			(
					':appt_id'         	=> $id,
					':out_lat'          => $lat,
					':out_lng'          => $lng,
					':check_out_time'   => date('H:i:s'),
					':check_out_address'=> $in_address,
					":email"			=> $email
			);
			$check_type_desc = "CHECK-OUT";
		}
		
		$data           = add_timestamp_to_array($data, $emp_id, 1);
		$result         = db_update($data, 'cms_appt_personal', 'appt_id',"email");

		if($result != '')
		{
			
			$rs 			= db_query("device_id as push_id", "cms_employees","super_admin = 1");
			
			if(count($rs) > 0)
			{
				$msg 		=
				'{
					"data":
					{
						"msg"		: "' . $emp_name . " " . $check_type_desc . " at " . date('H:i:s') . '",
						"data"		: "' . $in_address . '",
						"lat"		: "' . $lat . '",
						"lng"		: "' . $lng . '"
					}
				}';
				
				push_it_to_ios($rs,json_decode($msg));
			}
			
			$type           = array('check_type' => (int)($check_type + 1));
			$list           = json_decode(get_appt_list_new($params));
			
			$return_data    = array('list' => $list->data, 'type' => $type);
			
			return handle_success_response('Success', $return_data);
		}
		else
		{
			return handle_fail_response('Failed to add gps location');
		}
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function get_appt_invites($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$appt_id		= if_property_exist($params, 'appt_id');
		
		
		if(!isset($appt_id) || $appt_id== '')
		{
			return handle_fail_response('Missing Appoinment ID.');
		}
		
		$rs = db_query
		(	"cms_appt_personal.email
			,cms_appt_personal.lat
			,cms_appt_personal.lng
			,cms_appt_personal.check_in_time
			,cms_appt_personal.out_lat
			,cms_appt_personal.out_lng
			,cms_appt_personal.check_out_time
			,cms_appt_personal.invitation_send
			,cms_appt_personal.invitation_send_on
			,cms_appt_personal.check_in_address
			,cms_appt_personal.check_out_address
			,(select name from cms_employees where cms_appt_personal.created_by = cms_employees.id) as created_by
			,CASE
    			WHEN cms_appt_personal.lat is NULL AND cms_appt_personal.out_lat is NULL THEN 'NOT CHECKED IN'
    			WHEN cms_appt_personal.lat is NOT NULL AND cms_appt_personal.out_lat is NULL THEN 'CHECKED IN'
    			WHEN cms_appt_personal.lat is NOT NULL AND cms_appt_personal.out_lat is NOT NULL THEN 'CHECKED OUT'
			END as check_status",
			
			"cms_appt_personal",
			
			"appt_id = " . $appt_id . " AND cms_appt_personal.is_active = 1"
		);
		
		if (!isset($rs) || empty($rs))
		{
			return handle_fail_response('No Appointment record found');
		}

		return handle_success_response('Success', $rs);
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function bg_send_appt_invite($params)
{
	try
	{
		log_it(__FUNCTION__, $params, true);
		
		$appt_id 		= if_property_exist($params, 'id');
		$to 			= if_property_exist($params, 'to');
		$to_filtered	= if_property_exist($params, 'to_filtered');
		$start_date     = if_property_exist($params, 'date_time_start');
		$end_date       = if_property_exist($params, 'date_time_end');
		$emp_id         = if_property_exist($params, 'emp_id');
		$emp_name       = if_property_exist($params, 'emp_name');
		$emp_email      = if_property_exist($params, 'emp_email');
		$category       = if_property_exist($params, 'category');
		$sent_notify	= true;
		
		
		if(count($to_filtered) > 0)
		{
			$pic_name		= $to[0];
			$client_name	= 'UNKNOWN';
			
			$rs_remarks 	= db_query('remarks','cms_appt',"id =" . $appt_id);
			$appt_type      = db_query("descr", "cms_master_list", "id = " . $category);
			$rs_client      = db_query("cms_appt_pic.name as pic_name, cms_clients.name as client_name, cms_clients.sent_notification",
					"cms_appt_pic INNER JOIN cms_clients ON cms_appt_pic.client = cms_clients.id",
					"cms_appt_pic.email = '" . $to[0]. "'");
			
			
			if (isset($rs_client) && count($rs_client) > 0)
			{
				$pic_name 		= $rs_client[0]['pic_name'];
				$client_name 	= $rs_client[0]['client_name'];
				if((int)$rs_client[0]['sent_notification'] == 0)
				{
					$sent_notify = false;
				}
			}
			
			if($sent_notify == true)
			{
				$task           =  $appt_type[0]['descr'] . " with " . $pic_name . " from " . $client_name;
				
				include_once constant('LIB_DIR') . '/ics.php';
				$ics = new ICS(array
						(
								'description'   => $task,
								'dtstart'       => $start_date,
								'dtend'         => $end_date,
								'summary'       => "Meeting with " . $emp_name
						));
				
				$ics_file_contents  = $ics->to_string();
				$ics_name           = date('Y-m-d', strtotime($start_date)) . '_' . $appt_id. '.ics';
				
				if(!is_dir(constant('LOG_DIR') . '/ics'))
				{
					mkdir(constant('LOG_DIR') . '/ics' ,0755,TRUE);
				}
				
				$fh = fopen(constant('LOG_DIR') . '/ics/' . $ics_name, 'w');
				if($fh)
				{
					fwrite($fh, $ics_file_contents);
					fclose($fh);
				}
				else
				{
					log_it(__FUNCTION__, "Error creating ics file", true);
				}
				
				$template 	= $rs_remarks[0]['remarks'];
				$replace 	= array('{PIC_NAME}', '{USER_NAME}','{START_DATE}','{END_DATE}','{APPT_TYPE}','{MAIL_SIGNATURE}','{APP_TITLE}');
				$with 		= array("Sir / Madam", $emp_name, $start_date, $end_date,$appt_type[0]['descr'], constant('MAIL_SIGNATURE'),constant('APPLICATION_TITLE'));
				$body		= str_replace($replace, $with, $template);
				
				if(smtpmailer
						(
								implode(";", $to),
								constant('MAIL_USERNAME'),
								constant('MAIL_FROMNAME'),
								'Scheduled Appointments',
								$body,
								$emp_email, constant('LOG_DIR') . '/ics/' . $ics_name
								))
				{
					db_execute_custom_sql("UPDATE cms_appt_personal set invitation_send = 1, invitation_send_on=now() where id = " . $appt_id);
					$msg_date 	= date('Y-m-d', strtotime($start_date)) . ' ' . date('H:i', strtotime($start_date)) . ' - ' . date('H:i', strtotime($end_date));
					$msg 		= $emp_name . ' ' .  $appt_type[0]['descr'] . ' ' . $pic_name. '(' . $client_name . ') ON '  . $msg_date;
					send_sms_to_super_admin($msg);
					log_it(__FUNCTION__, "Send Appointment Email Success", true);
				}
			}
			else
			{
				log_it(__FUNCTION__, "Notification to client is disabled", true);
			}
		}
		else
		{
			log_it(__FUNCTION__, "No Email address to sent",true);
		}
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}
?>