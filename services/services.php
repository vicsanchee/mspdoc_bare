<?php
/**
 * @author 		Jamal
 * @date 		14-Nov-2015
 * @modify 		Jamal
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */


php_meta_nocache();
require_once(dirname(__FILE__) . '/config/config-dev.inc.php');
require_once(constant('SHARED_DIR')     . '/dbfunctions.php');

if(isset($_SERVER['REQUEST_METHOD']))
{
	log_trans("Business Layer: " . @file_get_contents('php://input'), SERVICE_LOG);

	if(strlen(@file_get_contents('php://input')) > 0)
	{
		$rec_data 	= stripslashes(@file_get_contents('php://input'));
		$param 		= json_decode($rec_data);

		
		//service exposed without token
		if($param->method === 'login')
		{
			require_once constant('MODULES_DIR')  . '/login.php';
			echo check_login($param);
			return;
		}

		if(!isset($param->token))
		{
			echo handle_fail_response('Unable to get token');
			return;
		}
		
		if(is_token_valid($param->token) == FALSE)
		{
			echo handle_fail_response('Invalid Token');
			return;
		}

		//service exposed with token

		else if($param->method === 'get_employees_details')
		{
		    require_once constant('MODULES_DIR') 	. '/employees.php';
		    echo get_employees_details($param->data);
		}

        else if($param->method === 'view_faq_list')
        {
        	require_once constant('MODULES_DIR') 	. '/faq.php';
        	echo view_faq_list($param->data);
        }
        else if($param->method === 'add_edit_faq')
        {
        	require_once constant('MODULES_DIR') 	. '/faq.php';
        	echo add_edit_faq($param->data);
        }
        else if($param->method === 'get_faq_edit_details')
        {
        	require_once constant('MODULES_DIR') 	. '/faq.php';
        	echo get_faq_edit_details($param->data);
        }
        else if($param->method === 'delete_faq_details')
        {
        	require_once constant('MODULES_DIR') 	. '/faq.php';
        	echo delete_faq_details($param->data);
        }
        else if($param->method === 'get_inout_list')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo get_inout_list($param->data);
        }
        else if($param->method === 'add_edit_inout')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo add_edit_inout($param->data);
        }
        else if($param->method === 'get_inout_details')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo get_inout_details($param->data);
        }
        else if($param->method === 'delete_inout')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo delete_inout($param->data);
        }
        else if($param->method === 'inout_verify_approval')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo inout_verify_approval($param->data);
        }
        else if($param->method === 'inout_add_edit_remark')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo inout_add_edit_remark($param->data);
        }
        else if($param->method === 'send_email_verifier_approver_inout')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo send_email_verifier_approver_inout($param->data);
        }
        else if($param->method === 'send_email_notification_inout')
        {
            require_once constant('MODULES_DIR') 	. '/inout_documents.php';
            echo send_email_notification_inout($param->data);
        }
        else if($param->method === 'get_document_archiving_list')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo get_document_archiving_list($param->data);
        }
        else if($param->method === 'add_edit_document_archiving')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo add_edit_document_archiving($param->data);
        }
        else if($param->method === 'get_document_archiving_details')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo get_document_archiving_details($param->data);
        }
        else if($param->method === 'delete_document_archiving')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo delete_document_archiving($param->data);
        }
        else if($param->method === 'document_archiving_verify_approval')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo document_archiving_verify_approval($param->data);
        }
        else if($param->method === 'document_archiving_add_edit_remark')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo document_archiving_add_edit_remark($param->data);
        }
        else if($param->method === 'send_email_verifier_approver_archiving')
        {
            require_once constant('MODULES_DIR') 	. '/document_archiving.php';
            echo send_email_verifier_approver_archiving($param->data);
        }


        else if($param->method === 'get_service_request_list')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo get_service_request_list($param->data);
        }
        else if($param->method === 'add_edit_service_request')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo add_edit_service_request($param->data);
        }
        else if($param->method === 'send_email_verifier_approver_service_request')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo send_email_verifier_approver_service_request($param->data);
        }
        else if($param->method === 'get_service_request_details')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo get_service_request_details($param->data);
        }
        else if($param->method === 'delete_service_request')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo delete_service_request($param->data);
        }
        else if($param->method === 'service_request_add_edit_remark')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo service_request_add_edit_remark($param->data);
        }
        else if($param->method === 'service_request_verify_approval')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo service_request_verify_approval($param->data);
        }


        else if($param->method === 'add_edit_leave')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo add_edit_leave($param->data);
        }
        else if($param->method === 'upload_edit_leave')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo upload_edit_leave($param->data);
        }
        else if($param->method === 'get_leave_list')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo get_leave_list($param->data);
        }
        else if($param->method === 'get_balance_leave')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo get_balance_leave($param->data);
        }
        else if($param->method === 'get_leave_details')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo get_leave_details($param->data);
        }
        else if($param->method === 'get_holidays')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo get_holidays($param->data);
        }
        else if($param->method === 'delete_leave_details')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo delete_leave_details($param->data);
        }
        else if($param->method === 'get_leave_by_day')
        {
            require_once constant('MODULES_DIR') . '/leave.php';
            echo get_leave_by_day($param->data);
        }
        else if($param->method === 'get_leave_report')
        {
            require_once constant('MODULES_DIR') 	. '/leave.php';
            echo get_leave_report($param->data);
        }
        else if($param->method === 'get_leave_dashboard_list')
        {
            require_once constant('MODULES_DIR') 	. '/leave.php';
            echo get_leave_dashboard_list($param->data);
        }
        else if($param->method === 'update_leave_attachment_filename')
        {
            require_once constant('MODULES_DIR') 	. '/leave.php';
            echo update_leave_attachment_filename($param->data);
        }
        else if($param->method === 'get_leave_info_list')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo get_leave_info_list($param->data);
        }
        else if($param->method === 'leave_edit_verify')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo leave_edit_verify($param->data);
        }
        else if($param->method === 'leave_edit_approve')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo leave_edit_approve($param->data);
        }
        else if($param->method === 'delete_leave_by_id')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo delete_leave_by_id($param->data);
        }
        else if($param->method === 'leave_add_edit_remark')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo leave_add_edit_remark($param->data);
        }
        else if($param->method === 'get_leave_remark')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo get_leave_remark($param->data);
        }
        else if($param->method === 'leave_delete_remark')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo leave_delete_remark($param->data);
        }
        else if($param->method === 'get_underline_list')
        {
            require_once constant('MODULES_DIR') . '/leaveInfo.php';
            echo get_underline_list($param->data);
        }
		else
		{
			echo handle_fail_response('Please provide valid method');
		}
	}
	else
	{
		echo handle_fail_response('Please provide valid inputs');
	}
}

function is_token_valid($token)
{
	try
	{
		
		log_it(__FUNCTION__, $token,false);
		$valid = FALSE;
		
		if($token == '')
		{
			return FALSE;
		}
		
		$rs = db_query( "count(token) as token_count ",
				"cms_emp_login_sessions",
				"token	= '". stripslashes($token) . "'");
		
		if($rs[0]['token_count'] > 0)
		{
			$valid = TRUE;
		}
		
		return $valid;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function php_meta_nocache()
{
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); // Date in the past
}
?>
