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
        else if($param->method === 'get_outbound_list')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo get_outbound_list($param->data);
        }
        else if($param->method === 'add_edit_outbound')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo add_edit_outbound($param->data);
        }
        else if($param->method === 'get_outbound_details')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo get_outbound_details($param->data);
        }
        else if($param->method === 'delete_outbound')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo delete_outbound($param->data);
        }
        else if($param->method === 'outbound_verify_approval')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo outbound_verify_approval($param->data);
        }
        else if($param->method === 'outbound_add_edit_remark')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo outbound_add_edit_remark($param->data);
        }
        else if($param->method === 'send_email_verifier_approver_outbound')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo send_email_verifier_approver_outbound($param->data);
        }
        else if($param->method === 'send_email_notification_outbound')
        {
            require_once constant('MODULES_DIR') 	. '/outbound_document.php';
            echo send_email_notification_outbound($param->data);
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
        /*else if($param->method === 'service_request_add_edit_remark')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo service_request_add_edit_remark($param->data);
        }
        else if($param->method === 'service_request_verify_approval')
        {
            require_once constant('MODULES_DIR') 	. '/service_request.php';
            echo service_request_verify_approval($param->data);
        }*/
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
