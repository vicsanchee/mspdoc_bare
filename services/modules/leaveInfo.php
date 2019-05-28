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

function get_leave_info_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $start_index	= if_property_exist($params, 'start_index',	0);
        $limit	        = if_property_exist($params, 'limit',	MAX_NO_OF_RECORDS);
        $type_id   		= if_property_exist($params, 'type_id','');
        $employee_id    = if_property_exist($params, 'employee_id','');
        $verified       = if_property_exist($params, 'verified','');
        $approved       = if_property_exist($params, 'approved','');
        $from_date	    = if_property_exist($params, 'from_date','');
        $to_date	    = if_property_exist($params, 'to_date','');
        $emp_id	        = if_property_exist($params, 'emp_id','');
        $is_supervisor	= if_property_exist($params, 'is_supervisor','0');
        $is_admin	    = if_property_exist($params, 'is_admin','0');

        if($emp_id === NULL)
        {
            return handle_fail_response('Employee ID is mandatory');
        }

        $where	=	" cms_leave.is_active = 1";

        if($from_date != '' && $to_date != '')
        {
            if($verified == 0 && $approved == 0)
            {
                $where	.= " AND ((cms_leave.start_date <= '" . $from_date . "' AND cms_leave.end_date >='" . $to_date . "') OR (cms_leave.start_date <= '" . $from_date . "' AND cms_leave.end_date <='" . $to_date . "') OR (cms_leave.start_date >= '" . $from_date . "' AND cms_leave.end_date >= '" . $to_date . "') OR (cms_leave.start_date >= '" . $from_date . "' AND cms_leave.end_date <= '" . $to_date . "'))";
            }
        }

        if($type_id != '')
        {
            $where 	.= " AND cms_leave.type_id in(" . $type_id . ")";
        }

        if($employee_id != '')
        {
            $where 	.= " AND cms_leave.emp_id in(" . $employee_id . ")";
        }

        if($verified != 0)
        {
            $where	.=	" AND cms_leave.verified = " . $verified . ' AND cms_leave.approved = 0';
        }

        if($approved != 0)
        {
            $where	.=	" AND IF( (IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
                    	from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
                    	and cms_leave_by_day.approved = 1),0)
                    	+ IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
                    	from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
                    	and cms_leave_by_day.rejected = 1),0)) = cms_leave.no_of_days,1,0) = " . $approved;
        }

        if($is_admin != 1)
        {
            if ($is_supervisor == 1)
            {
                $rs_emp = db_query('GROUP_CONCAT(id) as id', 'cms_employees', 'reporting_to_id = ' . $emp_id);
                if(count($rs_emp) > 0)
                {
                    $where 	.= " AND cms_leave.emp_id in(" . $rs_emp[0]['id'] . ")";
                }
            }
        }

        $rs = db_query_list
        (
            "cms_leave.id
            , cms_leave.emp_id
            , cms_employees.name
            , cms_leave.type_id
            , cms_master_list.descr as type
            , cms_leave.start_date as start_date
            , cms_leave.end_date as end_date
            , cms_leave.no_of_days
            , cms_leave.reason
            , cms_leave.filename
			,concat('" . constant("UPLOAD_DIR_URL") . "', 'leave_doc', '/',cms_leave.emp_id, '/', cms_leave.filename) as filepath
            , cms_leave.verified
            , cms_leave.approved
            , (select half_day_opt
                  from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
                  and cms_leave.no_of_days = 0.5) as half_day_opt
            , IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
        	           from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
        	           and cms_leave_by_day.rejected = 1),0) as rejected
            , IF( (IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
        	from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
        	and cms_leave_by_day.approved = 1),0)
        	+ IFNULL((select sum(cms_leave_by_day.leave_no_of_days) 
        	from cms_leave_by_day where cms_leave.id = cms_leave_by_day.leave_id 
        	and cms_leave_by_day.rejected = 1),0)) = cms_leave.no_of_days, 1, 0)  as all_approved
            , (select name from cms_employees emp where emp.id = cms_leave.created_by
            ) as created_by",
            "cms_leave
            INNER JOIN cms_master_list ON (cms_leave.type_id = cms_master_list.id)
            INNER JOIN cms_employees ON cms_leave.emp_id = cms_employees.id
            ",
            $where, $start_index, $limit, "all_approved ASC, start_date","DESC"
        );

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

/**
* Getting list of employees supervised by specified supervisor
*
* @param $id[int, String] The ID of the supervisor
* @return [String] String of SQL WHERE statement
*/
function get_underline_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id   		= if_property_exist($params, 'id');
        $for_fe     = if_property_exist($params, 'for_fe', false);

        if ($for_fe === NULL)
        {
            $for_fe = false;
        }
        $emps = db_query('id, name', 'cms_employees', 'reporting_to_id = ' . $id);
        $where = " AND (";

        if(count($emps) < 1 || !isset($emps))
        {
            return handle_fail_response('No record found for this Supervisor');
        }
        else
        {
            if ($for_fe)
            {
                return handle_success_response('Success', $emps);
            }
            foreach ($emps as $idx => $emp) {
                if ($idx != 0)
                {
                    $where .= "OR ";
                }
                $where .=  "cms_leave.emp_id = " . $emp['id'] . " ";
            }
            $where .= ") ";
            return $where;
        }
    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}

function get_leave_details_by_id($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $leave_id		= if_property_exist($params,'leave_id','');

        $rs = db_query("
        cms_leave.id
        , cms_leave.emp_id
        , cms_employees.name
        , cms_leave.type_id
        , cms_master_list.descr as type
        , date_format(cms_leave.start_date,'" . constant('UI_DATE_FORMAT') .  "') as start_date
        , date_format(cms_leave.end_date,'" . constant('UI_DATE_FORMAT') .  "') as end_date
        , cms_leave.no_of_days
        , cms_leave.reason
        , cms_leave.filename
        , cms_leave.verified
        , cms_leave.approved
        , (select name from cms_employees emp where emp.id = cms_leave.created_by
        ) as created_by
        ",
        "
        cms_leave
        INNER JOIN cms_master_list ON (cms_leave.type_id = cms_master_list.id)
        INNER JOIN cms_employees ON cms_leave.emp_id = cms_employees.id
        ",
        "cms_leave.id = ". $leave_id);

        return handle_success_response('Success', $rs);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function leave_edit_verify($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $leave_id       = if_property_exist($params,'leave_id');
        $emp_id         = if_property_exist($params,'emp_id');
        $verifier_name  = if_property_exist($params,'emp_name');
        $leave_remarks  = if_property_exist($params,'leave_remarks');
        $start_date     = if_property_exist($params,'start_date');
        $end_date       = if_property_exist($params,'end_date');
        $no_of_days     = if_property_exist($params,'no_of_days');
        $applicant_id   = if_property_exist($params,'applicant_id');
        $applicant_name = if_property_exist($params,'applicant_name');
        $type_name		= if_property_exist($params,'type_name');
        $reason         = if_property_exist($params,'reason');

        $start_date     = convert_date_to_format($start_date);
        $end_date       = convert_date_to_format($end_date);
        
        if($leave_id === NULL)
        {
            return handle_fail_response('Leave ID is mandatory');
        }
        
        $data = array
        (
            ':id'               => $leave_id,
            ':verified'         => 1,
            ':verified_by'      => $emp_id,
            ':verified_date'    => get_current_date(),
        );
        $results 		    = db_update($data, 'cms_leave', 'id');


        $rs_app		  	= db_query('employer_name'
                                    , 'cms_master_employer LEFT JOIN cms_employees ON cms_master_employer.id = cms_employees.employer_id'
                                    , 'cms_employees.id = '.$applicant_id);
        
        $employer_name  = $rs_app[0]['employer_name'];

        
        //CHAT PUSH NOTIFICATION
        $params->message 	= "\\n\\n*Module : Leave" .
          					  "*\\n *Application waiting your next course of action " .
          					  "*\\n *Name : " 			. $applicant_name	.
					          "*\\n *Leave Type : " 	. $type_name		.
					          "*\\n *No Of Days : " 	. $no_of_days 		.
					          "*\\n *Start Date : " 	. $start_date		.
					          "*\\n *End Date : " 		. $end_date			.
					          "*\\n *Reason : " 		. $reason 			. 
					          "*\\n *Verified By : " 	. $verifier_name	.
					          "*\\n *Verifier Remarks : " 	. $leave_remarks . "*";
        
        $rs	= db_query("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(json_field, '$.chat_user_id')),'') as id,name",
        		'cms_employees',
        		"access_level = 60 AND cms_employees.is_active = 1");
        
        require_once constant('MODULES_DIR') . '/chat.php';
        $params->to_chat_ids  = json_decode(json_encode($rs));
        chat_post_message($params);
        //CHAT PUSH NOTIFICATION
        
        
        
        
        //Start - Send Email to CEO
        $rs		  	= db_query('name,office_email','cms_employees','access_level = 60');
        for($i = 0; $i < count($rs); $i++)
        {
            $name  		= $rs[$i]['name'];
            $email  	= $rs[$i]['office_email'];
            $subject 	= 'Leave Verified By ' . $verifier_name;

            $template 	= file_get_contents(constant('TEMPLATE_DIR') . '/leave_verify_notification.html');
            $replace 	= array('{APP_TITLE}', '{NAME}', '{VERIFIED_BY}', '{EMPLOYEE}','{NO_OF_DAYS}','{START_DATE}','{END_DATE}','{REASON}','{EMPLOYER_NAME}');
            $with 		= array(constant('APPLICATION_TITLE'), $name, $verifier_name, $applicant_name, $no_of_days,$start_date,$end_date,$reason,$employer_name);
            $body		= str_replace($replace, $with, $template);

            if
            (
                !smtpmailer
                (
                    $email,
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
        //End - Send Email to CEO

        if($leave_remarks != '')
        {
            json_decode(leave_add_edit_remark($params));
        }

        return handle_success_response('Success', true);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function leave_add_edit_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $leave_id       = if_property_exist($params,'leave_id');
        $leave_remarks  = if_property_exist($params,'leave_remarks');
        $emp_id     	= if_property_exist($params,'emp_id');
        $emp_name     	= if_property_exist($params,'emp_name');
        $action    		= if_property_exist($params,'action');
        $subject        = 'Leave Remarks Added';

        $data = array
        (
            ':leave_id'			=> 	$leave_id,
            ':leave_remarks'	=>  $leave_remarks,
            ':action'			=>  $action,
            ':created_by'		=> 	$emp_id
        );

        $data 			= add_timestamp_to_array($data,$emp_id,0);
        $id 			= db_add($data, 'cms_leave_remark');

        $rs_emp    		= db_query("emp_id","cms_leave", "id = '" . $leave_id . "'");
        $to_emp_id  	= $rs_emp[0]['emp_id'];

        if(isset($to_emp_id) && $to_emp_id != '' )
        {
            $rs     = db_query("office_email, name, (select employer_name from cms_master_employer where cms_master_employer.id = cms_employees.employer_id) as employer_name","cms_employees", "id = " . $to_emp_id);
            if(isset($rs) && count($rs) > 0)
            {
            	//CHAT PUSH NOTIFICATION
            	$params->message 	= "\\n\\n*Module : Leave" .
              	"*\\n *Leave ID : " 	. $leave_id			.
              	"*\\n *Remarks : " 		. $leave_remarks . "*";
            	
            	$rs1	= db_query("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(json_field, '$.chat_user_id')),'') as id,name",
            			'cms_employees',
            			"id = $to_emp_id AND cms_employees.is_active = 1");
            	
            	require_once constant('MODULES_DIR') . '/chat.php';
            	$params->to_chat_ids  = json_decode(json_encode($rs1));
            	chat_post_message($params);
            	//CHAT PUSH NOTIFICATION
            	
            	
            	
                $template 	= file_get_contents(constant('TEMPLATE_DIR') . '/remarks_reminder.html');
                $replace 	= array('{APP_TITLE}', '{NAME}', '{USER}','{REMARKS}','{MAIL_SIGNATURE}','{LEAVE_ID}');
                $with 		= array(constant('APPLICATION_TITLE'), $rs[0]['name'], $emp_name, $leave_remarks, $rs[0]['employer_name'], $leave_id);
                $body		= str_replace($replace, $with, $template);

                if
                (
                    !smtpmailer
                    (
                        $rs[0]['office_email'],
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
        }

        if($action == '')
        {
            $list       = json_decode(get_leave_remark($params));
            return handle_success_response('Success', $list->data);
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function leave_edit_approve($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $leave_id      		= if_property_exist($params,'leave_id');
        $leave_data      	= if_property_exist($params,'leave_data');
        $paid_status		= if_property_exist($params,'paid_status');
        $approve_status     = if_property_exist($params,'approve_status');
        $approve_type       = if_property_exist($params,'approve_type');
        $rejected_status    = if_property_exist($params,'rejected_status');
        $emp_id     		= if_property_exist($params,'emp_id');
        $approved_name      = if_property_exist($params,'emp_name');
        
        $start_date         = if_property_exist($params,'start_date');
        $end_date           = if_property_exist($params,'end_date');
        $no_of_days         = if_property_exist($params,'no_of_days');
        $applicant_id       = if_property_exist($params,'applicant_id');
        $applicant_name     = if_property_exist($params,'applicant_name');
        $reason             = if_property_exist($params,'reason');
        $type_name			= if_property_exist($params,'type_name');
        
        $start_date         = convert_date_to_format($start_date);
        $end_date           = convert_date_to_format($end_date);
        $edited_date    	= get_current_date();

        if($leave_id === NULL)
        {
            return handle_fail_response('Leave ID is mandatory');
        }
        
        $data = array
        (
            ':id'               => $leave_id,
            ':approved'         => $approve_status,
            ':approved_by'      => $emp_id,
            ':approved_date'    => $edited_date,
        );
        $results 		    = db_update($data, 'cms_leave', 'id');

        for($i=0; $i < count($leave_data); $i++)
        {
            $id = $leave_data[$i] -> id;

            if($approve_status == 1 || $paid_status == 1)
            {
                $data = array
                (
                    ':paid'         	=> $paid_status,
                    ':approved'         => $approve_status,
                    ':approved_by'      => $emp_id,
                    ':approved_date'    => $edited_date
                );
            }
            
            if($rejected_status == 1)
            {
                $data = array
                (
                    ':rejected'         => $rejected_status,
                    ':rejected_by'      => $emp_id,
                    ':rejected_date'    => $edited_date
                );
            }

            $data[':id']    	= $id;
            $results 		    = db_update($data, 'cms_leave_by_day', 'id');
        }
        
        if($approve_status == 1)
        {
            if($paid_status == 1)
            {
                $action = 'Leave Approved (paid)';
            }
            else
            {
                $action = 'Leave Approved (unpaid)';
            }
        }
        else if($rejected_status == 1)
        {
            $action = 'Leave Rejected';
        }

        
        //CHAT PUSH NOTIFICATION
        $params->message 	= "\\n\\n*Module : Leave" .
          "*\\n *Your application on  " . $action .
          "*\\n *Leave Type : " 	. $type_name		.
          "*\\n *No Of Days : " 	. $no_of_days 		.
          "*\\n *Start Date : " 	. $start_date		.
          "*\\n *End Date : " 		. $end_date			.
          "*\\n *Reason : " 		. $reason 			.
          "*\\n *Approve By : " 	. $approved_name	. "*";
        
        $rs	= db_query("IFNULL(JSON_UNQUOTE(JSON_EXTRACT(json_field, '$.chat_user_id')),'') as id,name",
        		'cms_employees',
        		"id = $applicant_id AND cms_employees.is_active = 1");
        
        require_once constant('MODULES_DIR') . '/chat.php';
        $params->to_chat_ids  = json_decode(json_encode($rs));
        chat_post_message($params);
        //CHAT PUSH NOTIFICATION
        
        
        //Start - Send Email to Employee
        $rs		  	= db_query('name,office_email,(select employer_name from cms_master_employer where cms_master_employer.id = cms_employees.employer_id) as employer_name','cms_employees','id = "' . $applicant_id . '"');

        $name  			= $rs[0]['name'];
        $email  		= $rs[0]['office_email'];
        $employer_name	= $rs[0]['employer_name'];
        $subject 		= 'Leave Status Updated By '.$approved_name;

        $template 	= file_get_contents(constant('TEMPLATE_DIR') . '/leave_approve_notification.html');
        $replace 	= array('{APP_TITLE}', '{NAME}', '{APPROVED_BY}', '{ACTION}', '{EMPLOYEE}','{NO_OF_DAYS}','{START_DATE}','{END_DATE}','{REASON}','{EMPLOYER_NAME}');
        $with 		= array(constant('APPLICATION_TITLE'), $name, $approved_name, $action, $name, $no_of_days,$start_date,$end_date,$reason,$employer_name);
        $body		= str_replace($replace, $with, $template);

        if
        (
            !smtpmailer
            (
                $email,
                constant('MAIL_USERNAME'),
                constant('MAIL_FROMNAME'),
                $subject,
                $body
            )
        )
        {
            return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
        }
        //End - Send Email to Employee
        
        $list = true;
        if($approve_type == 'DAY')
        {
            $list = json_decode(get_leave_details_by_id($params));
            $list = $list->data;
        }
        
        return handle_success_response('Success', $list);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function delete_leave_by_id($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id  		= if_property_exist($params,'id');
        $emp_id		= if_property_exist($params, 'emp_id');

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
        //$list       = json_decode(get_leave_info_list($params));

        return handle_success_response('Success', true);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_leave_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $leave_id 	= if_property_exist($params, 'leave_id');
        $where		=	" cms_leave_remark.leave_id = '" . $leave_id . "' AND cms_leave_remark.is_active = 1";

        $rs = db_query
        (
            "cms_leave_remark.id
            , cms_leave_remark.leave_id
            , cms_leave_remark.leave_remarks
            , IFNULL(cms_leave_remark.action,'N/A') as action
            , (select name from cms_employees emp where emp.id = cms_leave_remark.created_by) as created_by
            , date_format(cms_leave_remark.created_date,'" . constant('UI_DATE_FORMAT') .  "') as created_date
            , cms_leave.emp_id as owner_emp_id
            ",
            "cms_leave_remark INNER JOIN cms_leave ON
            cms_leave_remark.leave_id = cms_leave.id
            ",
            $where, '','', 'created_date', 'DESC'
        );

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

function leave_delete_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id	        	= if_property_exist($params, 'id');
        $leave_id  		= if_property_exist($params,'leave_id');
        $emp_id	        = if_property_exist($params, 'emp_id');

        if($leave_id === NULL || $leave_id == '')
        {
            return handle_fail_response('Leave ID is mandatory');
        }

        $data = array
        (
            ':id'  		  => $id,
            ':is_active'  => 0
        );

        $data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_leave_remark','id');

        $list       = json_decode(get_leave_remark($params));

        return handle_success_response('Success', $list->data);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

?>
