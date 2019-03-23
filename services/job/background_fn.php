<?php
/**
 * @author 		Jamal
 * @date 		28-Aug-2018
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */

require_once(dirname(__FILE__) . '/../server.php');

$param = '';
// log_it("background", json_decode(urldecode($argv[1])));
if(isset($argv[1]))
{
	$param = json_decode(urldecode($argv[1]));
	run_fn_in_background($param);
}

function run_fn_in_background($params)
{
    try 
    {
        log_it(__FUNCTION__, $params, true);

        $fn 	= if_property_exist($params, 'fn');
        
        call_user_func($fn,$params);
        
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

function bg_send_contract_notification($params)
{
	try
	{
		log_it(__FUNCTION__, $params, true);
		
		$employee_name 		= if_property_exist($params,'employee_name');
		$to_name        	= if_property_exist($params,'to_name');
		$email        		= if_property_exist($params,'email');
		$action	        	= if_property_exist($params,'action');
		$emp_id 	        = if_property_exist($params,'emp_id');
		
		$rs		  	= db_query('username','cms_employees','id = ' .$emp_id. '');
		$username  	= $rs[0]['username'];
		
		if($action == 'add')
		{
			$action_text 	= 'created';
			$subject 		= 'New contract created';
		}
		else if($action == 'edit')
		{
			$action_text 	= 'updated';
			$subject 		= 'Contract updated';
		}
		
		$template 	= file_get_contents(constant('TEMPLATE_DIR') . '/contract_notification.html');
		$replace 	= array('{APP_TITLE}', '{NAME}', '{CONTRACT_NAME}', '{USER}','{ACTION}','{MAIL_SIGNATURE}');
		$with 		= array(constant('APPLICATION_TITLE'), $to_name, $employee_name, $username, $action_text, constant('MAIL_SIGNATURE'));
		$body		= str_replace($replace, $with, $template);
		
		if(smtpmailer
		(
			$email,
			constant('MAIL_USERNAME'),
			constant('MAIL_FROMNAME'),
			$subject,
			$body
		))
		{
			log_it(__FUNCTION__, "Send Contract Notification Email Success", true);
		}
		else
		{
			log_it(__FUNCTION__, "Send Contract Notification Email Failed", true);
		}
		
		
		
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}



?>