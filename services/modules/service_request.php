<?php
/**
 * @author 		Sancheev
 * @date 		16-Nov-2018
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */
//require_once(dirname(__FILE__) . '/../config/config.inc.php');
//require_once(constant('SHARED_DIR') . '/dbfunctions.php');

function get_service_request_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $category_id	    = if_property_exist($params, 'category_id','');
        $created_by         = if_property_exist($params, 'created_by','');
        $company_id         = if_property_exist($params, 'company_id','');
        $status_id          = if_property_exist($params, 'status_id','');
        $complete_status_id = if_property_exist($params, 'complete_status_id','');
        $start_index	    = if_property_exist($params, 'start_index',	0);
        $limit	            = if_property_exist($params, 'limit',	MAX_NO_OF_RECORDS);
        $view_all           = if_property_exist($params, 'view_all',0);
        $is_admin           = if_property_exist($params, 'is_admin','');
        $emp_id	            = if_property_exist($params, 'emp_id','');

        if($view_all == 1)
        {
            $where	= "1 = 1";
        }
        else
        {
            $where = $emp_id . " IN (cms_service_request.created_by)";
        }

        if($category_id != "")
        {
            $where 	.= " AND JSON_UNQUOTE(cms_service_request.sr_data->'$.category_id') in(" . $category_id . ")";
        }
        if($created_by != "")
        {
            $where 	.= " AND cms_service_request.created_by in(" . $created_by . ")";
        }
        if($company_id != "")
        {
            $where 	.= " AND JSON_UNQUOTE(cms_service_request.sr_data->'$.employer_id') in(" . $company_id . ")";
        }
        if($status_id != "")
        {
            $where 	.= " AND JSON_UNQUOTE(cms_service_request.sr_data->'$.status_id') in(" . $status_id . ")";
        }

        $where 	.= " AND cms_service_request.is_active in(1)";


        $rs = db_query_list
        (
            "cms_service_request.service_no
				, cms_service_request.sr_data
				, cms_service_request.created_date
				, cms_employees.name as created_by
				, (select descr from cms_master_list list where list.id = JSON_UNQUOTE(cms_service_request.sr_data->'$.category_id')) as category_name
				, (select descr from cms_master_list list where list.id = JSON_UNQUOTE(cms_service_request.sr_data->'$.status_id')) as status_name
				, (select descr from cms_master_list list where list.id = ".$complete_status_id.") as complete_status_name			
                ",
            "
                cms_service_request
				LEFT JOIN cms_employees
                    ON (cms_service_request.created_by = cms_employees.id)
                ",
            $where, $start_index, $limit, "service_no",'DESC');

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

function add_edit_service_request($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $service_no         = if_property_exist($params,'service_no');
        $service_data       = if_property_exist($params,'service_data');
        $emp_id 	        = if_property_exist($params,'emp_id');

        if($service_no == '')
        {
        	$rs  = get_doc_primary_no('service_no', 'cms_service_request',$service_data->employer_id);
        	if($rs == false)
        	{
        		return handle_fail_response('Error generating service number. Please contact admin');
        	}
        	else
            {
                $service_no = $rs['service_no'];
            }
        }

        $data = array
        (
        	':service_no'		    => 	$service_no,
            ':sr_data'	            => 	json_encode($service_data)
        );

        if(is_data_exist('cms_service_request', 'service_no', $service_no))
        {
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_service_request', 'service_no');
            $params->action 		= "edit";
        }
        else
        {
            $data 					= add_timestamp_to_array($data,$emp_id,0);
            $id                		= db_add($data, 'cms_service_request');
            $params->service_no     = $service_no;
            $params->action 		= "add";
        }

        return handle_success_response('Success', $params);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_service_request_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $service_no	= if_property_exist($params,'service_no','');

        if($service_no === NULL || $service_no == '')
        {
            return handle_fail_response('Service Number is mandatory');
        }

        $rs = db_query("					
						service_no
				        , sr_data
				        , concat('" . constant("UPLOAD_DIR_URL") . "', 'service_request', '/',service_no, '/') as filepath
				        , created_date
				        , created_by
						, (select descr from cms_master_list list where list.id = JSON_UNQUOTE(cms_service_request.sr_data->'$.status_id')) as status_name			
					",
            "cms_service_request",
            "service_no = '". $service_no ."'");

        $data['details']	= $rs[0];

        return handle_success_response('Success', $data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function delete_service_request($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $service_no 	= if_property_exist($params,'service_no');
        $emp_id	        = if_property_exist($params, 'emp_id');

        $data = array
        (
            ':service_no'       => $service_no,
            ':is_active'        => 0
        );

        if(is_data_exist('cms_service_request', 'service_no', $service_no))
        {
            $data[':service_no']  	= $service_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_service_request', 'service_no');
            $params->action 		= "edit";
        }

        $return_data = array('service_no'   => $service_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function send_email_verifier_approver_service_request($params)
{
    try
    {
        log_it(__FUNCTION__, $params,true);

        $service_no         = if_property_exist($params, 'service_no');
        $module_id          = if_property_exist($params, 'module_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');
        $type_id  	        = if_property_exist($params, 'email_type');
        $files  	        = if_property_exist($params, 'attachment');

        if($type_id == 1 || $type_id == 2)
        {
            $template	        = db_query('template_content','cms_master_template',"id = 32");
        }
        else
        {
            $template	        = db_query('template_content','cms_master_template',"id = 31");
        }
        $email_content      = $template[0]['template_content'];

        $rs                 = db_query('cms_master_list.descr as category,cms_employees.office_email as created_by_email',
                            'cms_service_request 
                                      INNER JOIN cms_employees ON cms_service_request.created_by = cms_employees.id
                                      LEFT JOIN cms_master_list ON JSON_UNQUOTE(cms_service_request.sr_data->"$.category_id") = cms_master_list.id',
                                "service_no='" . $service_no. "'");


        $rs_emp             = db_query('office_email','cms_employees',"id='" . $emp_id. "'");


       if(count($rs) > 0)
        {
            for($i = 0; $i < count($files); $i++)
            {
                $filename 		= $files[$i];
                $files_arr[$i] 	= constant('DOC_FOLDER') . "service_request/" . $service_no . "/" . $filename;
            }

            $rs_sig	    = get_mail_signature($rs_emp[0]['office_email']);

            $rs_email   = get_verifier_approver($module_id);

            $subject 	    = 'Service Request';

            if($type_id == 1 || $type_id == 2)
            {
                $action_name    = ($type_id == 1) ? 'verified' : 'approved';
                $replace 	    = array('{SENDER_NAME}','{ACTION}','{SERVICE_NO}','{CATEGORY}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$action_name,$service_no,$rs[0]['category'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);            }
            else
            {
                $replace 	    = array('{SENDER_NAME}','{SERVICE_NO}','{CATEGORY}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$service_no,$rs[0]['category'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);
            }

            $body		= str_replace($replace, $with, $email_content);

            if
            (
            smtpmailer
            (
                $rs_email['email'],
                constant('MAIL_USERNAME'),
                constant('MAIL_FROMNAME'),
                $subject,
                $body,
                $rs[0]['created_by_email'],
                $files_arr
            )
            )
            {
                log_it(__FUNCTION__, "Send service request notification email success", true);
                return handle_success_response('Success', $params);
            }
            else
            {
                log_it(__FUNCTION__, "Send service request notification email failed", true);
                return handle_fail_response('Failed');
            }
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


?>
