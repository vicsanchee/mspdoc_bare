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

function get_outbound_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $client_id		= if_property_exist($params, 'client_id','');
        $created_by     = if_property_exist($params, 'created_by','');
        $category_id    = if_property_exist($params, 'category_id','');
        $status_id      = if_property_exist($params, 'status_id','');
        $start_index	= if_property_exist($params, 'start_index',	0);
        $limit	        = if_property_exist($params, 'limit',	MAX_NO_OF_RECORDS);
        $view_all       = if_property_exist($params, 'view_all',0);
        $is_admin       = if_property_exist($params, 'is_admin','');
        $emp_id	        = if_property_exist($params, 'emp_id','');

        if($view_all == 1)
        {
            $where	= "1 = 1";
        }
        else
        {
            $where = $emp_id . " IN (cms_outbound_document.created_by)";
        }

        if($client_id != "")
        {
            $where 	.= " AND cms_outbound_document.client_id in(" . $client_id . ")";
        }
        if($created_by != "")
        {
            $where 	.= " AND cms_outbound_document.created_by in(" . $created_by . ")";
        }
        if($category_id != "")
        {
            $where 	.= " AND cms_outbound_document.ctg_id in(" . $category_id . ")";
        }
        if($status_id != "")
        {
            $where 	.= " AND cms_outbound_document.status_id in(" . $status_id . ")";
        }

        $where 	.= " AND cms_outbound_document.is_active in(1)";


        $rs = db_query_list
        (
            "cms_outbound_document.outbound_no
				, cms_outbound_document.outbound_date
				, cms_outbound_document.client_id
				, cms_outbound_document.employer_id
				, IFNULL(cms_clients.name,'') as client_name
				, cms_outbound_document.attention_to
				, cms_outbound_document.email_content
				, cms_outbound_document.email
				, cms_outbound_document.attachment
				, cms_outbound_document.remarks
				, cms_outbound_document.amount
				, cms_outbound_document.notes
				, cms_outbound_document.approvals
				, cms_outbound_document.status_id
				, cms_outbound_document.created_date
				, (select descr from cms_master_list list where list.id = cms_outbound_document.ctg_id) as category_name
				, (select descr from cms_master_list list where list.id = cms_outbound_document.status_id) as status_name
				, (select name from cms_employees emp where emp.id = cms_outbound_document.created_by) as created_by		
                ",
            "
                cms_outbound_document
				LEFT JOIN cms_clients
                    ON (cms_outbound_document.client_id = cms_clients.id)
                ",
            $where, $start_index, $limit, "outbound_no",'DESC');

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

function add_edit_outbound($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no    				= if_property_exist($params,'outbound_no');
        $outbound_date    				= if_property_exist($params,'outbound_date');
        $client_id      				= if_property_exist($params,'client_id',null,array(null,""));
        $employer_id      				= if_property_exist($params,'employer_id',null,array(null,""));
        $attention_to      				= if_property_exist($params,'attention_to');
        $status_id					    = if_property_exist($params,'status_id',null,array(null,""));
        $email_content				    = if_property_exist($params,'email_content');
        $email				            = if_property_exist($params,'email',[],array([],""));
        $category_id		            = if_property_exist($params,'category_id',null,array(null,""));
        $amount				            = if_property_exist($params,'amount',null,array(null,""));
        $remark				            = if_property_exist($params,'remark');
        $emp_id 	        			= if_property_exist($params,'emp_id');
        $attachment 	        		= if_property_exist($params,'attachment');

        $outbound_date	 				= convert_to_date($outbound_date);

        if($outbound_no == '')
        {
            $rs  = get_doc_primary_no('outbound_no', 'cms_outbound_document',$employer_id);
            if($rs == false)
            {
            	return handle_fail_response('Error generating document number. Please contact admin');
            }
            else
            {
                $outbound_no = $rs['outbound_no'];
            }
        }

        $data = array
        (
        	':outbound_no'			    => 	$outbound_no,
            ':outbound_date'	        => 	$outbound_date,
            ':client_id'			    => 	$client_id,
            ':employer_id'			    => 	$employer_id,
            ':attention_to'			    => 	$attention_to,
            ':status_id'			    =>  $status_id,
            ':email_content'	        => 	$email_content,
            ':email'					=> 	json_encode($email),
            ':attachment'			    => 	json_encode($attachment),
            ':amount'	                => 	$amount,
            ':notes'	                => 	$remark,
            ':ctg_id'	                => 	$category_id
        );

        if(is_data_exist('cms_outbound_document', 'outbound_no', $outbound_no))
        {
        	$data[':outbound_no']  	= $outbound_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_outbound_document', 'outbound_no');
            $params->action 		= "edit";
        }
        else
        {
            $data 					= add_timestamp_to_array($data,$emp_id,0);
            $id                		= db_add($data, 'cms_outbound_document');
            $params->outbound_no    = $outbound_no;
            $params->action 		= "add";
        }

        return handle_success_response('Success', $params);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function get_outbound_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no	= if_property_exist($params,'outbound_no','');

        if($outbound_no === NULL || $outbound_no == '')
        {
            return handle_fail_response('Outbound Document Number is mandatory');
        }

        $rs = db_query("
						  outbound_no		
						, date_format(outbound_date,'" . constant('UI_DATE_FORMAT') .  "') as outbound_date	
						, client_id
						, employer_id
						, attention_to
						, email_content
						, email
						, attachment
						, concat('" . constant("UPLOAD_DIR_URL") . "', 'outbound_document', '/',outbound_no, '/') as filepath
						, remarks
						, approvals
						, status_id
						, ctg_id
						, amount
						, notes
						, is_active
						, date_format(created_date,'" . constant('UI_DATE_FORMAT') .  "') as created_date
						, created_by
						, (select descr from cms_master_list list where list.id = cms_outbound_document.status_id) as status_name			
					",
            "cms_outbound_document",
            "outbound_no = '". $outbound_no ."'");

        $data['details']	= $rs[0];

        return handle_success_response('Success', $data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function delete_outbound($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no  	= if_property_exist($params,'outbound_no');
        $emp_id	        = if_property_exist($params, 'emp_id');

        $data = array
        (
            ':outbound_no'  => $outbound_no,
            ':is_active'    => 0
        );

        if(is_data_exist('cms_outbound_document', 'outbound_no', $outbound_no))
        {
            $data[':outbound_no']  	= $outbound_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_outbound_document', 'outbound_no');
            $params->action 		= "edit";
        }

        $return_data = array('outbound_no'   => $outbound_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function outbound_verify_approval($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no    				= if_property_exist($params,'outbound_no');
        $approvals    				    = if_property_exist($params,'approvals');
        $status_id    				    = if_property_exist($params,'status_id');
        $module_id                      = if_property_exist($params, 'module_id');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $data = array
        (
            ':outbound_no'			    => 	$outbound_no,
            ':status_id'			    => 	$status_id,
            ':approvals'	            => 	json_encode($approvals)
        );

        if(is_data_exist('cms_outbound_document', 'outbound_no', $outbound_no))
        {
            $data[':outbound_no']  	= $outbound_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_outbound_document', 'outbound_no');
            $params->action 		= "edit";

            if($approvals->verify->verified == 1 && $approvals->approve->approved != 1)
            {
                send_email_verifier_approver_outbound($params,1);
            }
            else if($approvals->approve->approved == 1)
            {
                send_email_verifier_approver_outbound($params,2);
            }
        }

        $return_data = array('outbound_no'   => $outbound_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function outbound_add_edit_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no    				= if_property_exist($params,'outbound_no');
        $remarks    				    = if_property_exist($params,'remarks');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $data = array
        (
            ':outbound_no'			    => 	$outbound_no,
            ':remarks'	                => 	json_encode($remarks)
        );

        if(is_data_exist('cms_outbound_document', 'outbound_no', $outbound_no))
        {
            $data[':outbound_no']  	= $outbound_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_outbound_document', 'outbound_no');
            $params->action 		= "edit";
        }

        $return_data = array('outbound_no'   => $outbound_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function send_email_verifier_approver_outbound($params,$type_id = 0)
{
    try
    {
        log_it(__FUNCTION__, $params,true);

        $outbound_no        = if_property_exist($params, 'outbound_no');
        $module_id          = if_property_exist($params, 'module_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');
        $files_arr			= [];

        if($type_id == 1 || $type_id == 2)
        {
            $template	        = db_query('template_content','cms_master_template',"id = 28");
        }
        else
        {
            $template	        = db_query('template_content','cms_master_template',"id = 27");
        }
        $email_content      = $template[0]['template_content'];


        $rs                 = db_query('attachment,cms_master_list.descr as category,cms_employees.office_email as created_by_email',
                             'cms_outbound_document 
                                      LEFT JOIN cms_master_list ON cms_outbound_document.ctg_id = cms_master_list.id
                                      LEFT JOIN cms_employees ON cms_outbound_document.created_by = cms_employees.id',
                               "outbound_no='" . $outbound_no. "'");

        $rs_emp             = db_query('office_email','cms_employees',"id='" . $emp_id. "'");

        if(count($rs) > 0)
        {

            $files = json_decode($rs[0]['attachment']);
            for($i = 0; $i < count($files); $i++)
            {
                $filename 		= $files[$i];
                $files_arr[$i] 	= constant('DOC_FOLDER') . "outbound_document/" . $outbound_no . "/" . $filename;
            }

            $rs_sig	    = get_mail_signature($rs_emp[0]['office_email']);

            $rs_email   = get_verifier_approver($module_id);

            $subject 	= 'Outbound Document';
            if($type_id == 1 || $type_id == 2)
            {
                $action_name    = ($type_id == 1) ? 'verified' : 'approved';
                $replace 	    = array('{SENDER_NAME}','{ACTION}','{DOC_NO}','{CATEGORY_NAME}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$action_name,$outbound_no,$rs[0]['category'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);
            }
            else
            {
                $replace 	    = array('{SENDER_NAME}','{DOC_NO}','{CATEGORY_NAME}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$outbound_no,$rs[0]['category'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);
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
                log_it(__FUNCTION__, "Send outbound Notification Email Success", true);
                return handle_success_response('Success', $params);
            }
            else
            {
                log_it(__FUNCTION__, "Send outbound Notification Email Failed", true);
                return handle_fail_response('Failed');
            }
        }
        else
        {

        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function send_email_notification_outbound($params)
{
    try
    {
        log_it(__FUNCTION__, $params,true);

        $outbound_no        = if_property_exist($params, 'outbound_no');
        $status_id 	        = if_property_exist($params, 'status_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');
        $files_arr			= [];

        $rs 				= db_query('
                                        email_content,attention_to
                                        , attachment
                                        , notes
                                        , remarks
                                        , cms_outbound_document.email as to_email
                                        , cms_master_list.descr as category
                                        , cms_employees.office_email as created_by_email
                                        ',
                              'cms_outbound_document 
                                        LEFT JOIN cms_master_list ON cms_outbound_document.ctg_id = cms_master_list.id
                                        LEFT JOIN cms_employees ON cms_outbound_document.created_by = cms_employees.id',
                                "outbound_no='" . $outbound_no. "'");

        $rs_status 		    = db_query('descr as status_name','cms_master_list list',"id='" . $status_id. "'");

        $rs_emp             = db_query('office_email','cms_employees',"id='" . $emp_id. "'");

        $to_email_arr = json_decode($rs[0]['to_email']);

        if(count($rs) > 0 && count($to_email_arr) > 0)
        {
            $files = json_decode($rs[0]['attachment']);
            for($i = 0; $i < count($files); $i++)
            {
                $filename 		= $files[$i];
                $files_arr[$i] 	= constant('DOC_FOLDER') . "outbound_document/" . $outbound_no . "/" . $filename;
            }

            $to_email = implode(";",$to_email_arr);

            $rs_sig	    = get_mail_signature($rs_emp[0]['office_email']);

            $subject 	= 'Outbound Document';
            $replace 	= array('{SENDER_NAME}','{DOC_NO}','{CATEGORY_NAME}','{APP_TITLE}','{MAIL_SIGNATURE}');
            $with 		= array($rs_sig['name'],$outbound_no,$rs[0]['category'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);
            $body		= str_replace($replace, $with, urldecode($rs[0]['email_content']));

            if
            (
            smtpmailer
            (
                $to_email,
                constant('MAIL_USERNAME'),
                constant('MAIL_FROMNAME'),
                $subject,
                $body,
                $rs[0]['created_by_email'],
                $files_arr
            )
            )
            {
                edit_outbound_status($params);
                $params->remarks_new    = $rs_status[0]['status_name'];
                $params->remarks        = $rs[0]['remarks'];
                log_it(__FUNCTION__, "Send outbound Notification Email Success", true);
                return handle_success_response('Success', $params);
            }
            else
            {
                log_it(__FUNCTION__, "Send outbound Notification Email Failed", true);
                return handle_fail_response('Failed');
            }
        }
        else
        {
            log_it(__FUNCTION__, "Send outbound Notification Email Failed", true);
            return handle_fail_response('Failed');
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function edit_outbound_status($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $outbound_no        = if_property_exist($params, 'outbound_no');
        $status_id 	        = if_property_exist($params, 'status_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');

        $data = array
        (
            ':outbound_no'			    => 	$outbound_no,
            ':status_id'                => 	$status_id
        );

        if(is_data_exist('cms_outbound_document', 'outbound_no', $outbound_no))
        {
            $data[':outbound_no']  	= $outbound_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_outbound_document', 'outbound_no');
            $params->action 		= "edit";
        }

        $return_data = array('outbound_no'   => $outbound_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


?>
