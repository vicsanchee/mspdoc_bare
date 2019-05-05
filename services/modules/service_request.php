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
            $where 	.= " AND cms_service_request.category_id in(" . $category_id . ")";
        }
        if($created_by != "")
        {
            $where 	.= " AND cms_service_request.created_by in(" . $created_by . ")";
        }
        if($company_id != "")
        {
            $where 	.= " AND cms_service_request.employer_id in(" . $company_id . ")";
        }
        if($status_id != "")
        {
            $where 	.= " AND cms_service_request.status_id in(" . $status_id . ")";
        }

        $where 	.= " AND cms_service_request.is_active in(1)";


        $rs = db_query_list
        (
            "cms_service_request.service_no	
				, cms_service_request.employer_id
                , cms_service_request.client_id
                , cms_service_request.contact_person
                , cms_service_request.description
                , cms_service_request.bank_account_details
                , cms_service_request.unit_price
                , cms_service_request.qty
                , cms_service_request.amount
                , cms_service_request.sst
                , cms_service_request.total_amount
                , cms_service_request.payment_terms
                , cms_service_request.payable_to
                , cms_service_request.number_of_repayment
                , cms_service_request.each_repayment_amount
                , cms_service_request.advance_or_loan
                , cms_service_request.balance_advance_or_loan
                , cms_service_request.type_of_assets
                , cms_service_request.duration
                , cms_service_request.asset_remark
                , cms_service_request.date_required
                , cms_service_request.approvals
                , cms_service_request.attachment
                , cms_service_request.status_id
                , cms_service_request.remarks	
				, cms_service_request.created_date
				, cms_employees.name as created_by
				, (select descr from cms_master_list list where list.id = cms_service_request.category_id) as category_name
				, (select descr from cms_master_list list where list.id = cms_service_request.status_id) as status_name		
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

        $service_no                 = if_property_exist($params,'service_no');
        $category_id                = if_property_exist($params,'category_id');
        $employer_id                = if_property_exist($params,'employer_id');
        $client_id                  = if_property_exist($params,'client_id',null,array(null,""));
        $description                = if_property_exist($params,'description',null,array(null,""));
        $unit_price                 = if_property_exist($params,'unit_price',null,array(null,""));
        $qty                        = if_property_exist($params,'qty',null,array(null,""));
        $amount                     = if_property_exist($params,'amount',null,array(null,""));
        $sst                        = if_property_exist($params,'sst',null,array(null,""));
        $total_amount               = if_property_exist($params,'total_amount',null,array(null,""));
        $bank_account_details       = if_property_exist($params,'bank_account_details',null,array(null,""));
        $contact_person             = if_property_exist($params,'contact_person',null,array(null,""));
        $payment_terms              = if_property_exist($params,'payment_terms',null,array(null,""));
        $payable_to                 = if_property_exist($params,'payable_to',null,array(null,""));
        $number_of_repayment        = if_property_exist($params,'number_of_repayment',null,array(null,""));
        $each_repayment_amount      = if_property_exist($params,'each_repayment_amount',null,array(null,""));
        $advance_or_loan            = if_property_exist($params,'advance_or_loan',null,array(null,""));
        $balance_advance_or_loan    = if_property_exist($params,'balance_advance_or_loan',null,array(null,""));
        $date_required              = if_property_exist($params,'date_required');
        $type_of_assets             = if_property_exist($params,'type_of_assets',[],array([],""));
        $duration                   = if_property_exist($params,'duration',null,array(null,""));
        $asset_remark               = if_property_exist($params,'asset_remark',null,array(null,""));
        $status_id                  = if_property_exist($params,'status_id',null,array(null,""));
        $emp_id 	                = if_property_exist($params,'emp_id');
        $attachment 	            = if_property_exist($params,'attachment');

        $date_required	 	        = convert_to_date($date_required);

        if($service_no == '')
        {
        	$rs  = get_doc_primary_no('service_no', 'cms_service_request',$employer_id);
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
        	':service_no'		        => 	$service_no,
            ':category_id'	            => 	$category_id,
            ':employer_id'	            => 	$employer_id,
            ':client_id'	            => 	$client_id,
            ':contact_person'	        => 	$contact_person,
            ':description'	            => 	$description,
            ':bank_account_details'	    => 	$bank_account_details,
            ':unit_price'	            => 	$unit_price,
            ':qty'	                    => 	$qty,
            ':amount'	                => 	$amount,
            ':sst'	                    => 	$sst,
            ':total_amount'	            => 	$total_amount,
            ':payment_terms'	        => 	$payment_terms,
            ':payable_to'	            => 	$payable_to,
            ':number_of_repayment'	    => 	$number_of_repayment,
            ':each_repayment_amount'	=> 	$each_repayment_amount,
            ':advance_or_loan'	        => 	$advance_or_loan,
            ':balance_advance_or_loan'	=> 	$balance_advance_or_loan,
            ':type_of_assets'	        => 	json_encode($type_of_assets),
            ':duration'	                => 	$duration,
            ':asset_remark'	            => 	$asset_remark,
            ':date_required'	        => 	$date_required,
            ':attachment'	            => 	json_encode($attachment),
            ':status_id'	            => 	$status_id,

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
						, category_id
				        , employer_id
                        , client_id
                        , contact_person
                        , description
                        , bank_account_details
                        , unit_price
                        , qty
                        , amount
                        , sst
                        , total_amount
                        , payment_terms
                        , payable_to
                        , number_of_repayment
                        , each_repayment_amount
                        , advance_or_loan
                        , balance_advance_or_loan
                        , type_of_assets
                        , duration
                        , asset_remark
                        , date_format(date_required,'" . constant('UI_DATE_FORMAT') .  "') as date_required	
                        , approvals
                        , attachment
                        , status_id
				        , concat('" . constant("UPLOAD_DIR_URL") . "', 'service_request', '/',service_no, '/') as filepath
				        , date_format(created_date,'" . constant('UI_DATE_FORMAT') .  "') as created_date
				        , created_by
						, (select descr from cms_master_list list where list.id = cms_service_request.status_id) as status_name			
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


function service_request_verify_approval($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $service_no    				    = if_property_exist($params,'service_no');
        $approvals    				    = if_property_exist($params,'approvals');
        $status_id    				    = if_property_exist($params,'status_id');
        $module_id                      = if_property_exist($params, 'module_id');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $rs 				= db_query('remarks','cms_service_request',"service_no='" . $service_no. "'");
        $rs_status 		    = db_query('descr as status_name','cms_master_list list',"id='" . $status_id. "'");

        $data = array
        (
            ':service_no'				=> 	$service_no,
            ':status_id'			    => 	$status_id,
            ':approvals'	            => 	json_encode($approvals)
        );

        if(is_data_exist('cms_service_request', 'service_no', $service_no))
        {
            $data[':service_no']    = $service_no;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_service_request', 'service_no');
            $params->action 		= "edit";

            if($approvals->verify->verified == 1 && $approvals->approve->approved != 1)
            {
                send_email_verifier_approver_service_request($params,1);
            }
            else if($approvals->approve->approved == 1)
            {
                send_email_verifier_approver_service_request($params,2);
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


function service_request_add_edit_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $service_no     				= if_property_exist($params,'service_no');
        $remarks    				    = if_property_exist($params,'remarks');
        $emp_id	                        = if_property_exist($params, 'emp_id','');

        $data = array
        (
            ':service_no'			    => 	$service_no,
            ':remarks'	                => 	json_encode($remarks)
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


function send_email_verifier_approver_service_request($params,$type_id = 0)
{
    try
    {
        log_it(__FUNCTION__, $params,true);

        $service_no         = if_property_exist($params, 'service_no');
        $module_id          = if_property_exist($params, 'module_id');
        $emp_id	  	        = if_property_exist($params, 'emp_id');
        $files_arr			= [];

        if($type_id == 1 || $type_id == 2)
        {
            $template	        = db_query('template_content','cms_master_template',"id = 32");
        }
        else
        {
            $template	        = db_query('template_content','cms_master_template',"id = 31");
        }
        $email_content      = $template[0]['template_content'];

        $rs                 = db_query('attachment,cms_master_list.descr as category,cms_employees.office_email as created_by_email',
                            'cms_service_request 
                                      INNER JOIN cms_employees ON cms_service_request.created_by = cms_employees.id
                                      LEFT JOIN cms_master_list ON cms_service_request.category_id = cms_master_list.id',
                                "service_no='" . $service_no. "'");


        $rs_emp             = db_query('office_email','cms_employees',"id='" . $emp_id. "'");


       if(count($rs) > 0)
        {
            $files = json_decode($rs[0]['attachment']);
            for($i = 0; $i < count($files); $i++)
            {
                $filename 		= $files[$i];
                $files_arr[$i] 	= constant('DOC_FOLDER') . "service_request/" . $service_no . "/" . $filename;
            }

            $rs_sig	    = get_mail_signature($rs_emp[0]['office_email']);

            $rs_email   = get_verifier_approver($module_id);

            $subject 	    = 'Request for Service';

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
