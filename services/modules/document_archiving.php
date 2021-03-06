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

function get_document_archiving_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $client_id		= if_property_exist($params, 'client_id','');
        $created_by     = if_property_exist($params, 'created_by','');
        $type_id        = if_property_exist($params, 'type_id','');
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
            $where = $emp_id . " IN (cms_document_archiving.created_by)";
        }

        if($client_id != "")
        {
            $where 	.= " AND cms_document_archiving.client_id in(" . $client_id . ")";
        }
        if($created_by != "")
        {
            $where 	.= " AND cms_document_archiving.created_by in(" . $created_by . ")";
        }
        if($type_id != "")
        {
            $where 	.= " AND cms_document_archiving.doc_type in(" . $type_id . ")";
        }
        if($status_id != "")
        {
            $where 	.= " AND cms_document_archiving.status_id in(" . $status_id . ")";
        }

        $where 	.= " AND cms_document_archiving.is_active in(1)";


        $rs = db_query_list
        (
            "cms_document_archiving.doc_no
				, cms_document_archiving.doc_date
				, cms_document_archiving.client_id
				, cms_document_archiving.employer_id
				, IFNULL(cms_clients.name,'') as client_name
				, cms_document_archiving.from_date
				, cms_document_archiving.to_date
				, IF(cms_document_archiving.notify = 1, 'Yes','No') as notify
				, cms_document_archiving.notify_by
				, cms_document_archiving.notify_email
				, cms_document_archiving.attachment
				, cms_document_archiving.remarks
				, cms_document_archiving.notes
				, cms_document_archiving.approvals
				, cms_document_archiving.status_id
				, cms_document_archiving.created_date
				, (select descr from cms_master_list list where list.id = cms_document_archiving.doc_type) as doc_type
				, (select descr from cms_master_list list where list.id = cms_document_archiving.status_id) as status_name		
				, (select name from cms_employees emp where emp.id = cms_document_archiving.created_by) as created_by		
                ",
            "
                cms_document_archiving
				LEFT JOIN cms_clients
                    ON (cms_document_archiving.client_id = cms_clients.id)
                ",
            $where, $start_index, $limit, "doc_no",'DESC');

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

function add_edit_document_archiving($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $document_no    				= if_property_exist($params,'document_no');
        $document_date    				= if_property_exist($params,'document_date');
        $client_id      				= if_property_exist($params,'client_id');
        $employer_id      				= if_property_exist($params,'employer_id');
        $from_date      				= if_property_exist($params,'from_date');
        $to_date					    = if_property_exist($params,'to_date');
        $doc_type				        = if_property_exist($params,'doc_type');
        $notify				            = if_property_exist($params,'notify');
        $notify_date		            = if_property_exist($params,'notify_date');
        $notify_email			        = if_property_exist($params,'notify_email',[],array([],""));
        $status_id				        = if_property_exist($params,'status_id');
        $remark				            = if_property_exist($params,'remark');
        $emp_id 	        			= if_property_exist($params,'emp_id');
        $attachment 	        		= if_property_exist($params,'attachment');

        $document_date	 				= convert_to_date($document_date);
        $from_date	 				    = convert_to_date($from_date);
        $to_date	 				    = convert_to_date($to_date);
        $notify_date	 				= convert_to_date($notify_date);

        if($document_no == '')
        {
        	$rs  = get_doc_primary_no('doc_no', 'cms_document_archiving',$employer_id);
        	if($rs == false)
        	{
        		return handle_fail_response('Error generating document number. Please contact admin');
        	}
        	else
            {
                $document_no = $rs['doc_no'];
            }
        }

        $data = array
        (
        	':doc_no'					=> 	$document_no,
            ':doc_date'	                => 	$document_date,
            ':client_id'			    => 	$client_id,
            ':employer_id'			    => 	$employer_id,
            ':doc_type'			        => 	$doc_type,
            ':from_date'			    => 	$from_date,
            ':to_date'			        => 	$to_date,
            ':notify'			        => 	$notify,
            ':notify_by'			    => 	$notify_date,
            ':notify_email'			    => 	json_encode($notify_email),
            ':attachment'			    => 	json_encode($attachment),
            ':notes'	                => 	$remark,
            ':status_id'			    =>  $status_id
        );

        if(is_data_exist('cms_document_archiving', 'doc_no', $document_no))
        {
            $data[':doc_no']  	    = $document_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_document_archiving', 'doc_no');
            $params->action 		= "edit";
        }
        else
        {
            $data 					= add_timestamp_to_array($data,$emp_id,0);
            $id                		= db_add($data, 'cms_document_archiving');
            $params->document_no    = $document_no;
            $params->action 		= "add";
        }

        return handle_success_response('Success', $params);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_document_archiving_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $document_no	= if_property_exist($params,'document_no','');

        if($document_no === NULL || $document_no == '')
        {
            return handle_fail_response('Document Number is mandatory');
        }

        $rs = db_query("
						  doc_no		
						, date_format(doc_date,'" . constant('UI_DATE_FORMAT') .  "') as doc_date	
						, client_id
						, employer_id
						, doc_type
						, date_format(from_date,'" . constant('UI_DATE_FORMAT') .  "') as from_date	
						, date_format(to_date,'" . constant('UI_DATE_FORMAT') .  "') as to_date	
						, notify
						, date_format(notify_by,'" . constant('UI_DATE_FORMAT') .  "') as notify_by	
						, notify_email
						, attachment
						, concat('" . constant("UPLOAD_DIR_URL") . "', 'doc_archiving', '/',doc_no, '/') as filepath
						, remarks
						, notes
						, approvals
						, status_id						
						, is_active
						, date_format(created_date,'" . constant('UI_DATE_FORMAT') .  "') as created_date
						, created_by	
						, (select descr from cms_master_list list where list.id = cms_document_archiving.status_id) as status_name			
					",
            "cms_document_archiving",
            "doc_no = '". $document_no ."'");

        $data['details']	= $rs[0];

        return handle_success_response('Success', $data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function delete_document_archiving($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $document_no  	= if_property_exist($params,'document_no');
        $emp_id	        = if_property_exist($params, 'emp_id');

        $data = array
        (
            ':doc_no'       => $document_no,
            ':is_active'    => 0
        );

        if(is_data_exist('cms_document_archiving', 'doc_no', $document_no))
        {
            $data[':doc_no']  	    = $document_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_document_archiving', 'doc_no');
            $params->action 		= "edit";
        }

        $return_data = array('doc_no'   => $document_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function document_archiving_verify_approval($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $document_no    				= if_property_exist($params,'document_no');
        $approvals    				    = if_property_exist($params,'approvals');
        $status_id    				    = if_property_exist($params,'status_id');
        $module_id                      = if_property_exist($params, 'module_id');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $rs 				= db_query('remarks','cms_document_archiving',"doc_no='" . $document_no. "'");
        $rs_status 		    = db_query('descr as status_name','cms_master_list list',"id='" . $status_id. "'");

        $data = array
        (
            ':doc_no'					=> 	$document_no,
            ':status_id'			    => 	$status_id,
            ':approvals'	            => 	json_encode($approvals)
        );

        if(is_data_exist('cms_document_archiving', 'doc_no', $document_no))
        {
            $data[':doc_no']  	    = $document_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_document_archiving', 'doc_no');
            $params->action 		= "edit";

            if($approvals->verify->verified == 1 && $approvals->approve->approved != 1)
            {
                send_email_verifier_approver_archiving($params,1);
            }
            else if($approvals->approve->approved == 1)
            {
                send_email_verifier_approver_archiving($params,2);
            }
        }
        $params->remarks_new    = $rs_status[0]['status_name'];
        $params->remarks        = $rs[0]['remarks'];

        return handle_success_response('Success', $params);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function document_archiving_add_edit_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $document_no    				= if_property_exist($params,'document_no');
        $remarks    				    = if_property_exist($params,'remarks');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $data = array
        (
            ':doc_no'					=> 	$document_no,
            ':remarks'	                => 	json_encode($remarks)
        );

        if(is_data_exist('cms_document_archiving', 'doc_no', $document_no))
        {
            $data[':doc_no']  	    = $document_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_document_archiving', 'doc_no');
            $params->action 		= "edit";
        }

        $return_data = array('doc_no'   => $document_no);

        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}


function send_email_verifier_approver_archiving($params,$type_id = 0)
{
    try
    {
        log_it(__FUNCTION__, $params,true);

        $document_no        = if_property_exist($params, 'document_no');
        $module_id          = if_property_exist($params, 'module_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');
        $files_arr			= [];

        if($type_id == 1 || $type_id == 2)
        {
            $template	        = db_query('template_content','cms_master_template',"id = 30");
        }
        else
        {
            $template	        = db_query('template_content','cms_master_template',"id = 29");
        }
        $email_content      = $template[0]['template_content'];

        $rs                 = db_query('attachment,cms_master_list.descr as doc_type,cms_employees.office_email as created_by_email',
            'cms_document_archiving 
                                      LEFT JOIN cms_master_list ON cms_document_archiving.doc_type = cms_master_list.id
                                      LEFT JOIN cms_employees ON cms_document_archiving.created_by = cms_employees.id',
            "doc_no='" . $document_no. "'");

        $rs_emp             = db_query('office_email','cms_employees',"id='" . $emp_id. "'");

        if(count($rs) > 0)
        {

            $files = json_decode($rs[0]['attachment']);
            for($i = 0; $i < count($files); $i++)
            {
                $filename 		= $files[$i];
                $files_arr[$i] 	= constant('DOC_FOLDER') . "doc_archiving/" . $document_no . "/" . $filename;
            }

            $rs_sig	    = get_mail_signature($rs_emp[0]['office_email']);

            $rs_email   = get_verifier_approver($module_id);

            $subject 	    = 'Document Archiving';

            if($type_id == 1 || $type_id == 2)
            {
                $action_name    = ($type_id == 1) ? 'verified' : 'approved';
                $replace 	    = array('{SENDER_NAME}','{ACTION}','{DOC_NO}','{TYPE_NAME}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$action_name,$document_no,$rs[0]['doc_type'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);            }
            else
            {
                $replace 	    = array('{SENDER_NAME}','{DOC_NO}','{TYPE_NAME}','{APP_TITLE}','{MAIL_SIGNATURE}');
                $with 		    = array($rs_sig['name'],$document_no,$rs[0]['doc_type'],constant('APPLICATION_TITLE'),$rs_sig['mail_signature']);
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


?>
