<?php
/**
 * @author 		Jamal
 * @date 		16-Nov-2015
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */
//require_once(dirname(__FILE__) . '/../config/config.inc.php');
//require_once(constant('SHARED_DIR') . '/dbfunctions.php');

function get_leave_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $start_index	= if_property_exist($params, 'start_index',	0);
        $limit	        = if_property_exist($params, 'limit',	MAX_NO_OF_RECORDS);
        $emp_id	        = if_property_exist($params, 'emp_id','');

        if($emp_id === NULL)
        {
            return handle_fail_response('Employee ID is mandatory');
        }

        $where	=	" cms_leave.id != '' AND cms_leave.is_active = 1";

        if($emp_id != "")
        {
			$where 	.=  " AND cms_leave.emp_id = " . $emp_id;
        }

        $rs = db_query_list
              (
                "cms_leave.id
				, cms_leave.emp_id
				, cms_leave.type_id
				, cms_master_list.descr as type
				, cms_leave.start_date as start_date
				, cms_leave.end_date as end_date
				, cms_leave.no_of_days
				, cms_leave.reason
				, cms_leave.doc_no
				, (select concat('" . constant("UPLOAD_DIR_URL")  . "', 'documents', '/', date_format(cms_documents.doc_date,'%m_%Y'), '/', cms_documents.emp_id, '/', cms_documents.filename) from cms_documents where cms_leave.doc_no = cms_documents.doc_no) as filepath
				, (select concat('" . constant("UPLOAD_DIR_URL")  . "', 'leave_doc', '/', cms_leave.emp_id, '/', cms_leave.filename)) as mc
				, cms_leave.verified
				, cms_leave.approved
				, (select half_day_opt
                  from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
                  and cms_leave.no_of_days = 0.5) as half_day_opt
                , IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
                  from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
                  and cms_leave_by_day.approved = 1),0) as sum_approved
                , IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
            	  from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
            	  and cms_leave_by_day.rejected = 1),0) as sum_rejected
                , (select name from cms_employees emp where emp.id = cms_leave.created_by) as created_by
                , cms_leave.created_date
                ",
                "
                cms_leave
				INNER JOIN cms_master_list ON (cms_leave.type_id = cms_master_list.id)
                ",
                $where, $start_index, $limit, "start_date", "DESC");


        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function get_leave_by_day($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $start_index	= if_property_exist($params, 'start_index',	0);
        $limit	        = if_property_exist($params, 'limit', 90);
		$leave_id       = if_property_exist($params, 'leave_id', '');

        if($leave_id === NULL)
        {
            return handle_fail_response('Leave ID is mandatory');
        }

        $where	=	" cms_leave_by_day.id != ''";

        if($leave_id != "")
        {
			$where 	.=  " AND cms_leave_by_day.leave_id = " . $leave_id . " AND cms_leave_by_day.is_active = 1";
        }

        $rs = db_query_list
              (
                "cms_leave_by_day.id
				, cms_leave_by_day.leave_id
				, cms_leave.type_id
				, cms_leave.no_of_days
				, date_format(cms_leave_by_day.leave_date,'" . constant('UI_DATE_FORMAT') .  "') as leave_date
				, cms_leave_by_day.leave_no_of_days
				, cms_leave_by_day.paid
				, cms_leave_by_day.approved
                , cms_leave_by_day.rejected
				, cms_leave_by_day.approved_by
				, cms_leave_by_day.approved_date
                , cms_leave_by_day.rejected_by
				, cms_leave_by_day.rejected_date
                ",
                "
                cms_leave_by_day
                    INNER JOIN cms_leave
                	    ON (cms_leave_by_day.leave_id = cms_leave.id)
                ",
                $where, $start_index, $limit, "cms_leave_by_day.leave_date", "ASC");

        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function cancel_leave_day($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id  		        = if_property_exist($params,'id');
        $leave_id           = if_property_exist($params,'leave_id');
        $no_of_days         = if_property_exist($params,'no_of_days');
        $emp_id		        = if_property_exist($params,'emp_id');

        if($id === NULL || $id == '')
        {
            return handle_fail_response('ID is mandatory');
        }

        $data = array
        (
            ':id'	  		=> $id,
            ':is_active'  	=> 0
        );

        $data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_leave_by_day','id');

        $data_leave = array
        (
            ':id'	  		=> $leave_id,
            ':no_of_days'  	=> $no_of_days
        );

        $data_leave = add_timestamp_to_array($data_leave,$emp_id, 1);
        $result     = db_update($data_leave, 'cms_leave','id');

        $list       = json_decode(get_leave_by_day($params));

        return handle_success_response('Success', $list->data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_leave_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $emp_id				= if_property_exist($params, 'emp_id','');
        $from_date 		=  date("Y").'-01-01';
        $to_date 		=  date("Y").'-12-31';
        $curr_year 	    =  date("Y");

        $where		=	" cms_emp_leave.id != ''";

        if($emp_id != "")
        {
            $where 	.=  " AND cms_emp_leave.emp_id = " . $emp_id . " AND cms_emp_leave.applicable_year = " . $curr_year . " AND cms_emp_leave.is_active = 1";
        }

        $rs = db_query
        (
            "cms_master_list.id as leave_id
                ,cms_master_list.descr as leave_type 
                ,cms_emp_leave.no_of_days as entitle_leave
				,cms_emp_leave.brought_forward as brought_forward
				,(select sum(leave_no_of_days) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = '" . $emp_id . "' AND cms_leave.type_id = cms_master_list.id  AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.paid = 1 AND cms_leave_by_day.leave_date >= '" . $from_date . "' AND cms_leave_by_day.leave_date <= '" . $to_date . "') as paid_leave_taken
				,(select sum(leave_no_of_days) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = '" . $emp_id . "' AND cms_leave.type_id = cms_master_list.id  AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.paid = 0 AND cms_leave_by_day.leave_date >= '" . $from_date . "' AND cms_leave_by_day.leave_date <= '" . $to_date . "') as unpaid_leave_taken
                ",
            "
                cms_emp_leave
                 INNER JOIN cms_master_list 
                        ON (cms_emp_leave.master_list_id = cms_master_list.id)
                ",
            $where);

        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function get_leave_entitle($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

		$type_id	= if_property_exist($params, 'type_id','');
        $emp_id		= if_property_exist($params, 'emp_id','');

		$where		=	" cms_emp_leave.id != ''";

		$curr_year 	=  date("Y");

        if($emp_id != "" && $type_id != "")
        {
			$where 	.=  " AND cms_emp_leave.emp_id = " . $emp_id . " AND cms_emp_leave.master_list_id = " . $type_id . " AND cms_emp_leave.applicable_year = " . $curr_year . " AND cms_emp_leave.is_active = 1";
        }

        $rs = db_query
              (
                "cms_emp_leave.no_of_days
				,cms_emp_leave.brought_forward
                ",
                "
                cms_emp_leave
                ",
                $where);

		if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            // return handle_success_response('Success', $rs);
            return $rs;
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_applied_leave($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

		$type_id		= if_property_exist($params, 'type_id','');
		$alt_type_id	= if_property_exist($params, 'alt_type_id','');
        $emp_id			= if_property_exist($params, 'emp_id','');

		$where			=	" cms_leave_by_day.id != ''";

		$from_date 	=  date("Y").'-01-01';
		$to_date 	=  date("Y").'-12-31';

        if($emp_id != "" && $type_id != "")
        {
			if($alt_type_id != "")
			{
				$type 	=  " AND (cms_leave.type_id = " . $type_id ." OR cms_leave.type_id = " . $alt_type_id .")";
			}
			else
			{
				$type 	=  " AND cms_leave.type_id = " . $type_id;
			}

			$where 	.=  " AND cms_leave.emp_id = " . $emp_id . " AND cms_leave_by_day.leave_date >= '" . $from_date . "' AND cms_leave_by_day.leave_date <= '" . $to_date . "' AND cms_leave.is_active = 1" . $type;
        }

        $rs = db_query
              (
                "sum(cms_leave_by_day.leave_no_of_days) as applied_days
                ",
                "
                cms_leave_by_day
				INNER JOIN cms_leave
                	ON (cms_leave_by_day.leave_id = cms_leave.id)
                ",
                $where);

		if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return $rs;
            // return handle_success_response('Success', $rs);
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_balance_leave($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

		// $leave_entitle			=	json_decode(get_leave_entitle($params));
		$leave_entitle			=	get_leave_entitle($params);
		// $leave_applied			=	json_decode(get_applied_leave($params));
		$leave_applied			=	get_applied_leave($params);

        log_it("[debug] get_balance_leave", $leave_entitle);

		$rs['leave_entitle']	=	$leave_entitle[0];
		$rs['leave_applied']	=	$leave_applied[0];

		if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_holidays($params)
{
    try
    {
		$where 	=  "";

        $rs = db_query_list
              (
                "cms_holidays.holiday,
				cms_holidays.holiday_desc
                ",
                "
                cms_holidays
				",
                $where, 0, 100, "holiday", "ASC");

		if(count($rs['list']) > 0)
        {
            return handle_success_response('Success', $rs);
        }
        else
        {
            return handle_fail_response("Unable to get holidays list");
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function add_edit_leave($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id		    					= if_property_exist($params,'id',"");
		$leave_data    					= if_property_exist($params,'leave_data');
		$no_of_days						= if_property_exist($params,'no_of_days');
		$type_id						= if_property_exist($params,'type_id');
		$reason							= if_property_exist($params,'reason');
		$filename						= if_property_exist($params,'filename');
		$filename_cert					= if_property_exist($params,'filename_cert');
		$emp_id 	        			= if_property_exist($params,'emp_id');
		$noe							= if_property_exist($params,'noe');
		$doc_no							= '';
		
		$start_date						= $leave_data[0] -> leave_date;
		$end_date						= $leave_data[count($leave_data)-1] -> leave_date;
		$start_date	 					= convert_to_date($start_date);
        $end_date 	     				= convert_to_date($end_date);

        if($id === NULL || $id == '')
        {
        	if($noe	!= "")
        	{
		        require_once('document.php');
		        $params->category_id	= 5;
		        $params->doc_date		= $start_date;
		        $params->remarks		= $reason;
		        $result 				= json_decode(add_document($params));
		        if($result->code == 0)
		        {
		        	$doc_no = $result->data->doc_no;
		        }
        	}
        }
        
        $data = array
        (
            ':emp_id'		=> $emp_id,
			':start_date'  	=> $start_date,
			':end_date'    	=> $end_date,
			':no_of_days'	=> $no_of_days,
			':type_id'		=> $type_id,
			':reason'		=> $reason,
        	':filename'		=> $filename_cert,
        	':doc_no'		=> $doc_no,
			':created_by'	=> $emp_id
        );

        if(is_data_exist('cms_leave', 'id', $id))
        {
            $data[':id']	= $id;
            $data 			= add_timestamp_to_array($data,$emp_id,1);
            $result 		= db_update($data, 'cms_leave', 'id');
        }
        else
        {
            $data 			= add_timestamp_to_array($data,$emp_id,0);
            $id 			= db_add($data, 'cms_leave');
        }

		if($id != '')
		{
			$params->leave_id	= $id;
			$params->start_date	= $start_date;
			$params->end_date	= $end_date;
			$params->email_opt	= 'ADD';
			add_edit_leave_by_day($params);
			send_leave_chat_notification($params);
			send_email_notification($params);
		}

       	$params->start_index 	= 0;
		$params->limit 		 	= MAX_NO_OF_RECORDS;
		$params->emp_id 		= $emp_id;

		$list       		= json_decode(get_leave_list($params));
		
		$return_data['doc_no']	= $doc_no;
		$return_data['id']		= $id;
		$return_data['listing']	= $list->data;
		
        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function upload_edit_leave($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id		    					= if_property_exist($params,'id');
		$filename     					= if_property_exist($params,'filename');
		$emp_id 	        			= if_property_exist($params,'emp_id');

		if($id === NULL || $id == '')
        {
            return handle_fail_response('ID is mandatory');
        }

        $data = array
        (
			':id'	  					=> $id,
            ':filename'					=> 	$filename
        );

		$data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_leave','id');

       	$params->start_index 	= 0;
		$params->limit 		 	= MAX_NO_OF_RECORDS;
		$params->emp_id 		= $emp_id;

		$list       = json_decode(get_leave_list($params));

        return handle_success_response('Success', $list->data);
    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function add_edit_leave_by_day($params)
{
	try
    {
		log_it(__FUNCTION__, $params);

		$id		    					= if_property_exist($params,'id');
		$leave_id		    			= if_property_exist($params,'leave_id');
		$leave_data    					= if_property_exist($params,'leave_data');
		$no_of_days						= if_property_exist($params,'no_of_days');
		$type_id						= if_property_exist($params,'type_id');
		$reason							= if_property_exist($params,'reason');
		$filename						= if_property_exist($params,'filename');
		$emp_id 	        			= if_property_exist($params,'emp_id');

		for($i=0; $i < count($leave_data); $i++)
		{
			$data = array
			(
				':leave_id'				=> 	$leave_id,
				':leave_date'			=>  $leave_data[$i] -> leave_date,
				':leave_no_of_days'		=>  $leave_data[$i] -> no_of_days,
                ':half_day_opt'		    =>  $leave_data[$i] -> half_day_opt,
				':created_by'			=>  $emp_id
			);

			$data 			= add_timestamp_to_array($data,$emp_id,0);
			$id 			= db_add($data, 'cms_leave_by_day');
		}
	}
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function delete_leave_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id  		= if_property_exist($params,'id');
        $emp_id		= if_property_exist($params, 'emp_id');
        $doc_no		= if_property_exist($params, 'doc_no');

        if($id === NULL || $id == '')
        {
            return handle_fail_response('ID is mandatory');
        }

        $data = array
        (
            ':id'	  		=> $id,
            ':is_active'  	=> 0
        );

        $data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_leave','id');
        
        if($doc_no != NULL && $doc_no != "")
        {
        	db_execute_sql("UPDATE cms_documents set is_active = 0 WHERE doc_no = '" . $doc_no . "'");
        }
        
        
        $list       = json_decode(get_leave_list($params));

		$params->email_opt	= 'DELETE';
		send_email_notification($params);

        return handle_success_response('Success', $list->data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function send_email_notification($params)
{
	$start_date	= if_property_exist($params, 'start_date');
	$end_date	= if_property_exist($params, 'end_date');
	$no_of_days	= if_property_exist($params,'no_of_days');
	$reason		= if_property_exist($params,'reason');
	$emp_id		= if_property_exist($params, 'emp_id');
	$option		= if_property_exist($params, 'email_opt');

	//Start - Send Email to CEO, HR and Finance
	$s_date = date('d-m-Y', strtotime($start_date));
	$e_date = date('d-m-Y', strtotime($end_date));

    $rs_emp		  	= db_query('name,(select employer_name from cms_master_employer where cms_master_employer.id = cms_employees.employer_id) as employer_name, reporting_to_id','cms_employees','id = '.$emp_id);
	$emp_name		= $rs_emp[0]['name'];
	$employer_name 	= $rs_emp[0]['employer_name'];

    $rs = db_query('name, email', 'cms_employees', 'id = ' . $rs_emp[0]['reporting_to_id']);

    if (count($rs) < 1 || !isset($rs))
    {
        return handle_fail_response('No client record found');
    }
    else
    {
        $subject 	= 'Leave Application by '.$emp_name;
		$template 	= file_get_contents(constant('TEMPLATE_DIR') . '/leave_notification.html');

        $replace 	= array('{APP_TITLE}', '{NAME}', '{EMPLOYEE}','{NO_OF_DAYS}','{START_DATE}','{END_DATE}','{REASON}','{EMPLOYER_NAME}');
		$with 		= array(constant('APPLICATION_TITLE'), $rs[0]['name'], $emp_name, $no_of_days,$s_date,$e_date,$reason,$employer_name);
		$body		= str_replace($replace, $with, $template);

        if
        (
            !smtpmailer
            (
    			$rs[0]['email'],
    			constant('MAIL_USERNAME'),
    			constant('MAIL_FROMNAME'),
    			$subject,
    			$body
    	    )
        )
		{
			return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
		}
    }
	//End - Send Email to CEO, HR and Finance
}

function get_leave_report($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$employee_id    = if_property_exist($params, 'employee_id','');
		$type_id   		= if_property_exist($params, 'type_id','');
		$from_date	    = if_property_exist($params, 'from_date','');
		$to_date	    = if_property_exist($params, 'to_date','');
		$paid       	= if_property_exist($params, 'paid','');
		$unpaid       	= if_property_exist($params, 'unpaid','');
		$emp_id	        = if_property_exist($params, 'emp_id','');
		$curr_year 		=  date("Y");
		$start_year		=  substr($from_date,0,4).'-01-01';
		$end_year 		=  substr($to_date,0,4).'-12-31';
		
		$where	=	"cms_leave.id != '' AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.leave_date >= '" . $from_date . "' AND cms_leave_by_day.leave_date <= '" . $to_date . "'";
		
		if($employee_id != "ALL")
		{
			$where 	.=  " AND cms_leave.emp_id = " . $employee_id;
		}
		if($type_id != "ALL")
		{
			$where 	.=  " AND cms_leave.type_id = " . $type_id;
		}
		if($paid == 1 && $unpaid == 0)
		{
			$where 	.=  " AND cms_leave_by_day.paid = " . $paid;
		}
		if($paid == 0 && $unpaid == 1)
		{
			$where 	.=  " AND cms_leave_by_day.paid = " . $paid;
		}
		
		$rs = db_execute_custom_sql(
				"SELECT
					cms_leave.id
					, cms_leave.emp_id as employee_id
					, cms_employees.name
					, cms_leave.type_id
					, (select descr from cms_master_list where cms_master_list.id = cms_leave.type_id
	) as type_name
					, date_format(cms_leave_by_day.leave_date,'" . constant('UI_DATE_FORMAT') .  "') as leave_date
					, cms_leave_by_day.leave_no_of_days
					, cms_leave_by_day.paid
					, cms_leave.reason
					, (select sum(cms_leave_by_day.is_active) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = cms_employees.id AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.leave_date >= '" . $from_date . "' AND cms_leave_by_day.leave_date <= '" . $to_date . "') as day_count
				
					, (select sum(leave_no_of_days) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = cms_employees.id AND (cms_leave.type_id = 47 OR cms_leave.type_id = 55) AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.paid = 1 AND cms_leave_by_day.leave_date >= '" . $start_year . "' AND cms_leave_by_day.leave_date <= '" . $end_year . "') as annual_leave_taken
					, (select sum(leave_no_of_days) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = cms_employees.id AND cms_leave.type_id = 48 AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.paid = 1 AND cms_leave_by_day.leave_date >= '" . $start_year . "' AND cms_leave_by_day.leave_date <= '" . $end_year . "') as medical_leave_taken
					, (select sum(leave_no_of_days) from cms_leave_by_day,cms_leave where cms_leave_by_day.leave_id = cms_leave.id AND cms_leave.emp_id = cms_employees.id AND cms_leave.is_active = 1 AND cms_leave_by_day.approved = 1 AND cms_leave_by_day.paid = 0 AND cms_leave_by_day.leave_date >= '" . $start_year . "' AND cms_leave_by_day.leave_date <= '" . $end_year . "') as unpaid_leave_taken
				
					, (select no_of_days from cms_emp_leave where cms_emp_leave.emp_id = cms_employees.id AND cms_emp_leave.master_list_id = 47 AND cms_emp_leave.applicable_year = " . $curr_year . " AND cms_emp_leave.is_active = 1) as annual_leave_entitle
					, (select no_of_days from cms_emp_leave where cms_emp_leave.emp_id = cms_employees.id AND cms_emp_leave.master_list_id = 48 AND cms_emp_leave.applicable_year = " . $curr_year . " AND cms_emp_leave.is_active = 1)  as medical_leave_entitle
					, (select brought_forward from cms_emp_leave where cms_emp_leave.emp_id = cms_employees.id AND cms_emp_leave.master_list_id = 47 AND cms_emp_leave.applicable_year = " . $curr_year . " AND cms_emp_leave.is_active = 1) as brought_forward
				FROM
					cms_leave
					INNER JOIN cms_employees
						ON (cms_leave.emp_id = cms_employees.id)
					INNER JOIN cms_leave_by_day
						ON cms_leave.id = cms_leave_by_day.leave_id
				WHERE
					".$where."
				ORDER BY
					cms_leave.emp_id ASC, cms_leave_by_day.leave_date ASC");
		
		
		if(count($rs) < 1 || !isset($rs))
		{
			return handle_fail_response('No record found');
		}
		else
		{
			return handle_success_response('Success', $rs);
		}
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_leave_dashboard_list($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$emp_id	    = if_property_exist($params, 'emp_id','');
		$year 		= if_property_exist($params, 'year','');
		
		if($year == '')
		{
			$year = "year(now())";
		}
		
		$rs = db_query("cms_employees.name,
				IFNULL((select no_of_days from cms_emp_leave where cms_employees.id = cms_emp_leave.emp_id and applicable_year = $year and master_list_id =47 limit 1),0)
				+
				IFNULL((select no_of_days from cms_emp_leave where cms_employees.id = cms_emp_leave.emp_id and applicable_year = $year and master_list_id =63 limit 1),0)
				as annual_leave,
				IFNULL((select no_of_days from cms_emp_leave where cms_employees.id = cms_emp_leave.emp_id and applicable_year = $year and master_list_id =48 limit 1),0) as med_leave,
				
				IFNULL((select sum(cms_leave_by_day.leave_no_of_days) from cms_leave_by_day INNER JOIN cms_leave on cms_leave_by_day.leave_id = cms_leave.id
				where cms_employees.id = cms_leave.emp_id and year(cms_leave_by_day.leave_date) = $year and type_id = 47 and cms_leave_by_day.approved = 1 and paid = 1),0) as annual_taken,
				
				IFNULL((select sum(cms_leave_by_day.leave_no_of_days) from cms_leave_by_day INNER JOIN cms_leave on cms_leave_by_day.leave_id = cms_leave.id
				where cms_employees.id = cms_leave.emp_id and year(cms_leave_by_day.leave_date) = $year and type_id = 48 and cms_leave_by_day.approved = 1 and paid = 1),0) as med_taken,
				
				IFNULL((select sum(cms_leave_by_day.leave_no_of_days) from cms_leave_by_day INNER JOIN cms_leave on cms_leave_by_day.leave_id = cms_leave.id
				where cms_employees.id = cms_leave.emp_id and year(cms_leave_by_day.leave_date) = $year and type_id = 55 and cms_leave_by_day.approved = 1 and paid = 1),0) as emergency_taken,
				
				IFNULL((select sum(cms_leave_by_day.leave_no_of_days) from cms_leave_by_day INNER JOIN cms_leave on cms_leave_by_day.leave_id = cms_leave.id
				where cms_employees.id = cms_leave.emp_id and year(cms_leave_by_day.leave_date) = $year  and cms_leave_by_day.approved = 1 and paid = 0),0) as unpaid_taken,
				
				IFNULL((select brought_forward from cms_emp_leave where cms_emp_leave.emp_id = cms_employees.id AND cms_emp_leave.master_list_id = 47
				AND cms_emp_leave.applicable_year = $year AND cms_emp_leave.is_active = 1),0) as brought_forward"
				,
				"cms_employees","is_active=1",'','','cms_employees.name','asc');
				
				
				if(count($rs) < 1 || !isset($rs))
				{
					return handle_fail_response('No record found');
				}
				else
				{
					return handle_success_response('Success', $rs);
				}
				
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function send_leave_chat_notification($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		
		$start_date	= if_property_exist($params, 'start_date');
		$end_date	= if_property_exist($params, 'end_date');
		$no_of_days	= if_property_exist($params, 'no_of_days');
		$type_name	= if_property_exist($params, 'type_name');
		$reason		= if_property_exist($params, 'reason');
		$emp_id		= if_property_exist($params, 'emp_id');
		$emp_name	= if_property_exist($params, 'emp_name');

		$s_date 	= date('d-m-Y', strtotime($start_date));
		$e_date 	= date('d-m-Y', strtotime($end_date));
		
		if(!isset($start_date) || $start_date == '')
		{
			return handle_fail_response('Start Date is mandatory');
		}
		
		if(!isset($end_date) || $end_date == '')
		{
			return handle_fail_response('End Date is mandatory');
		}
		
		$params->message 	= "\\n\\n*Module : Leave" . 
								"*\\n *Leave Type : " 	. $type_name	. 
								"*\\n *No Of Days : " 	. $no_of_days 	. 
								"*\\n *Start Date : " 	. $s_date 		. 
								"*\\n *End Date : " 	. $e_date  		. 
								"*\\n *Reason : " 		. $reason 		. "*";
		
		$rs	= db_query("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(json_field, '$.chat_user_id')),'') as id,name",
				'cms_employees',
				"id IN (SELECT reporting_to_id FROM cms_employees WHERE id = $emp_id AND cms_employees.is_active = 1)");
		
		require_once constant('MODULES_DIR') . '/chat.php';
		$params->to_chat_ids= json_decode(json_encode($rs));
		
		chat_post_message($params);
		
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function update_leave_attachment_filename($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$emp_id   	= if_property_exist($params, 'emp_id');
		$leave_id	= if_property_exist($params, 'leave_id');
		$doc_id		= if_property_exist($params, 'doc_id');
		$new_file	= if_property_exist($params, 'new_file');
		$type		= if_property_exist($params, 'type');
		
		if(!isset($emp_id) || $emp_id == '')
		{
			return handle_fail_response('Missing employee ID.');
		}
		if(!isset($leave_id) || $leave_id == '')
		{
			return handle_fail_response('Missing Leave ID.');
		}
		if(!isset($new_file) || $new_file == '')
		{
			return handle_fail_response('New Filename is missing');
		}
		if(!isset($type) || $type == '')
		{
			return handle_fail_response('Type is missing');
		}
		
		$data = array
		(
			':filename'	=>  $new_file
		);
		
		if($type == 'bill')
		{
			if($doc_id != '')
			{
				$data[':doc_no']= $doc_id;
				$data 			= add_timestamp_to_array($data,$emp_id,1);
				$result 		= db_update($data, 'cms_documents', 'doc_no');
			}
			
		}
		else if($type == 'cert')
		{
			$data[':id']	= $leave_id;
			$data 			= add_timestamp_to_array($data,$emp_id,1);
			$result 		= db_update($data, 'cms_leave', 'id');
			
		}
		
		$params->start_index 	= 0;
		$params->limit 		 	= MAX_NO_OF_RECORDS;
		$params->emp_id 		= $emp_id;
		
		$list       			= json_decode(get_leave_list($params));
		
		
		return handle_success_response('Success',$list->data);
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

?>
