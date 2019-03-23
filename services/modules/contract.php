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

function get_contract_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $candidate_name	= if_property_exist($params, 'candidate_name','');
        $status_id      = if_property_exist($params, 'status_id','');
        $client_id		= if_property_exist($params, 'client_id','');
		$created_by     = if_property_exist($params, 'created_by','');
		$cont_status    = if_property_exist($params, 'cont_status','');
		$po_no		    = if_property_exist($params, 'po_no','');
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
        	$where = $emp_id . " IN (cms_contracts.created_by,cms_contracts.sales_approval,cms_contracts.recruiter_id)";
        }
       
        if($candidate_name!= "")
        {
        	$where 	.=  " AND cms_contracts.employee_name = '%" . $candidate_name . "%'";
        }
        if($status_id != "")
        {
            $where 	.=  " AND cms_contracts.is_active = " . $status_id;
        }
        if($client_id != "")
        {
            $where 	.= " AND cms_contracts.client_id in(" . $client_id . ")";
        }
        if($created_by != "")
        {
            $where 	.= " AND cms_contracts.created_by in(" . $created_by . ")";
        }
        if($po_no != "")
        {
            $where 	.= " AND cms_contracts.pono in(" . $po_no . ")";
        }
        if($cont_status != "")
        {
        	$where 	.= " AND cms_contracts.status_id in(" . $cont_status . ")";
        }
        
        $rs = db_query_list
              (
                "cms_contracts.contract_no
				, cms_contracts.employee_name
				, cms_contracts.designation
        	    , IFNULL(cms_clients.name,'') as client_name
				, cms_contracts.contract_date
				, cms_contracts.pono
				, client_hiring_manager_name as hiring_manager_name
				, cms_contracts.hiring_manager_telno
				, cms_contracts.hiring_manager_email
				, cms_contracts.client_invoice_contact_person
				, cms_contracts.client_invoice_address_to
				, cms_contracts.billing_amount
				, cms_contracts.billing_amount_with_gst
				, cms_contracts.one_time_fee
				, cms_contracts.desc_one_time_fee
				, date_format(cms_contracts.start_date,'" . constant('UI_DATE_FORMAT') .  "') as start_date
				, date_format(cms_contracts.end_date,'" . constant('UI_DATE_FORMAT') .  "') as end_date
				, cms_contracts.duration
				, cms_contracts.epf
				, cms_contracts.socso
				, cms_contracts.salary
				, cms_contracts.daily_salary
				, cms_contracts.flight_ticket_cost
				, cms_contracts.temp_accommodation_cost
				, cms_contracts.laptop_cost
				, cms_contracts.notice_period_buyout
				, cms_contracts.ep_cost
				, cms_contracts.dp_cost
				, cms_contracts.overseas_visa_cost
				, cms_contracts.outpatient_medical_cost
				, cms_contracts.medical_insurance_cost
				, IF(cms_contracts.travelling_claim_applicable = 1, 'Yes','No') as travelling_claim_applicable
				, IF(cms_contracts.medical_claim_applicable = 1, 'Yes','No') as medical_claim_applicable
				, IF(cms_contracts.overtime_applicable = 1, 'Yes','No') as overtime_applicable
				, cms_contracts.medical_leave_day_by_client
				, cms_contracts.annual_leave_day_by_client
				, IF(cms_contracts.replacement_leave_applicable = 1, 'Yes','No') as replacement_leave_applicable
				, cms_contracts.approved_sales_head
                , cms_contracts.approved_hr
				, cms_contracts.approved_accounts
				, cms_contracts.approved_ceo
				, cms_contracts.issued_offer
				, cms_contracts.sales_approval
				, cms_contracts.eis
				, cms_contracts.hrdf
				, cms_contracts.email
				, cms_country.name as nationality
				, (select employer_name from cms_master_employer where cms_master_employer.id = cms_contracts.employer_id) as employer_name
				, (select descr from cms_master_list where cms_master_list.id = cms_contracts.notice_period_id) as notice_period
				, (select descr from cms_master_list where cms_master_list.id = cms_contracts.new_contract_or_extension) as contract_type
				, (select name from cms_employees where cms_employees.id = cms_contracts.requestor_id) as requestor_name
				, (select amount from cms_contract_emp_allowance where cms_contract_emp_allowance.contract_no = cms_contracts.contract_no and cms_contract_emp_allowance.master_list_id = 15 limit 1) as transport_allowance
				, (select amount from cms_contract_emp_allowance where cms_contract_emp_allowance.contract_no = cms_contracts.contract_no and cms_contract_emp_allowance.master_list_id = 16 limit 1) as handphone_allowance
				, (select amount from cms_contract_emp_allowance where cms_contract_emp_allowance.contract_no = cms_contracts.contract_no and cms_contract_emp_allowance.master_list_id = 18 limit 1) as other_allowance
        	    , IF(cms_contracts.is_active = 1, 'ACTIVE','IN-ACTIVE') as status
                , (select name from cms_employees emp where emp.id = cms_contracts.created_by) as created_by
				, cms_contracts.billing_of_month
				, cms_contracts.amount_paid_to_external_recruiter
				, cms_contracts.amount_paid_to_external_sales
				, cms_contracts.referred_amount
				, IFNULL(tbl_status.descr,'') as contract_status
                ",
                "
                cms_contracts
				LEFT JOIN cms_clients
                    ON (cms_contracts.client_id = cms_clients.id)
				LEFT JOIN cms_master_list as tbl_status
                    ON (cms_contracts.status_id = tbl_status.id)
				LEFT JOIN cms_country
                    ON (cms_contracts.nationality_id = cms_country.id)
                ",
                $where, $start_index, $limit, "contract_no",'DESC');
				
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

function get_contract_allowance_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $contract_no    = if_property_exist($params, 'contract_no');

        if($contract_no === NULL || $contract_no == "")
        {
            return handle_fail_response('Contract Number is mandatory');
        }

        $rs = db_query
        (
        	"cms_contract_emp_allowance.id
			, concat(cms_master_category.descr, ' - ',cms_master_list.descr) as allowance_type
       		, master_list_id as type_id
			, cms_contract_emp_allowance.amount 
			, cms_contract_emp_allowance.active_status
			, cms_master_list.category_id         
            ",
            "
           		cms_contract_emp_allowance 
				INNER JOIN cms_master_list ON cms_contract_emp_allowance.master_list_id = cms_master_list.id
				INNER JOIN cms_master_category ON cms_master_list.category_id = cms_master_category.id
            ",
            "cms_contract_emp_allowance.contract_no = " . $contract_no . " AND cms_contract_emp_allowance.active_status = 1"
        );

        
        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
       
        return handle_success_response('Success', $rs ? $rs : array());

    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function get_contract_dependent_list($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    = if_property_exist($params, 'contract_no');
		
		if($contract_no === NULL || $contract_no == "")
		{
			return handle_fail_response('Contract Number is mandatory');
		}
		
		$rs = db_query
		(
			"cms_contract_emp_dependent.id
			, concat(cms_master_category.descr, ' - ',cms_master_list.descr) as dependent_type
       		, master_list_id as type_id
			, cms_contract_emp_dependent.quantity
			, cms_contract_emp_dependent.amount
			, cms_contract_emp_dependent.active_status
			, cms_master_list.category_id
            ",
			"
           		cms_contract_emp_dependent
				INNER JOIN cms_master_list ON cms_contract_emp_dependent.master_list_id = cms_master_list.id
				INNER JOIN cms_master_category ON cms_master_list.category_id = cms_master_category.id
            ",
				"cms_contract_emp_dependent.contract_no = " . $contract_no . " AND cms_contract_emp_dependent.active_status = 1"
				);
		
		
		if(count($rs) < 1 || !isset($rs))
		{
			return handle_fail_response('No record found');
		}
		
		return handle_success_response('Success', $rs ? $rs : array());
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_contract_reference_list($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    = if_property_exist($params, 'contract_no');
		
		if($contract_no === NULL || $contract_no == "")
		{
			return handle_fail_response('Contract Number is mandatory');
		}
		
		$rs = db_query
		(
				"cms_contract_emp_reference.id
				, cms_contract_emp_reference.name
				, cms_contract_emp_reference.contact_no
				, cms_contract_emp_reference.email
				, cms_contract_emp_reference.company_name
				, cms_contract_emp_reference.designation
				, cms_contract_emp_reference.remarks
				, cms_contract_emp_reference.relationship_id
				, cms_master_list.descr as relationship
				, cms_contract_emp_reference.active_status
            ",
				"
           		cms_contract_emp_reference
				INNER JOIN cms_master_list ON cms_contract_emp_reference.relationship_id = cms_master_list.id
				INNER JOIN cms_master_category ON cms_master_list.category_id = cms_master_category.id
            ",
				"cms_contract_emp_reference.contract_no = " . $contract_no . " AND cms_contract_emp_reference.active_status = 1"
				);
		
		
		if(count($rs) < 1 || !isset($rs))
		{
			return handle_fail_response('No record found');
		}
		
		return handle_success_response('Success', $rs ? $rs : array());
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function add_edit_contracts($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id		    					= if_property_exist($params,'id');
		$employee_name 					= if_property_exist($params,'employee_name');
		$designation					= if_property_exist($params,'designation');
        $contract_date					= if_property_exist($params,'contract_date');
        
        $resume_source					= if_property_exist($params,'resume_source');
        $referred_by					= if_property_exist($params,'referred_by');
        $referred_amount				= if_property_exist($params,'referred_amount',0,array(0,""));
        
        $external_recruiter_id			= if_property_exist($params,'external_recruiter_id',0,array(0,""));
        $amount_paid_to_external_recruiter= if_property_exist($params,'amount_paid_to_external_recruiter');
        $external_sales_id				= if_property_exist($params,'external_sales_id',0,array(0,""));
        $amount_paid_to_external_sales	= if_property_exist($params,'amount_paid_to_external_sales');

        $requestor_id					= if_property_exist($params,'requestor_id',0,array(0,""));
		$request_date					= if_property_exist($params,'request_date');
		$recruiter_id					= if_property_exist($params,'recruiter_id');
		$employer_id     				= if_property_exist($params,'employer_id');	
		$client_id      				= if_property_exist($params,'client_id');
		$client_name      				= if_property_exist($params,'client_name');
		
		$nric							= if_property_exist($params,'nric');
		$nationality_id					= if_property_exist($params,'nationality_id');
		$email							= if_property_exist($params,'email');
		$sex_id							= if_property_exist($params,'sex_id');
		$marital_status_id				= if_property_exist($params,'marital_status_id');
		$home_address					= if_property_exist($params,'home_address');
		$candidate_contact_no			= if_property_exist($params,'candidate_contact_no','');
        $pono							= if_property_exist($params,'pono');
		$hiring_manager_name      		= if_property_exist($params,'hiring_manager_name');
		$hiring_manager_telno      		= if_property_exist($params,'hiring_manager_telno');		
        $hiring_manager_email			= if_property_exist($params,'hiring_manager_email');		
		$client_invoice_contact_person	= if_property_exist($params,'client_invoice_contact_person');
		$client_invoice_address_to		= if_property_exist($params,'client_invoice_address_to');
		$start_date    					= if_property_exist($params,'start_date');
		$end_date    					= if_property_exist($params,'end_date');
        $duration      					= if_property_exist($params,'duration');
        $billing_amount            		= if_property_exist($params,'billing_amount',0,array(0,""));		
        $billing_amount_with_gst		= if_property_exist($params,'billing_amount_with_gst',0,array(0,""));
        
        $one_time_fee					= if_property_exist($params,'one_time_fee',0,array(0,""));
        $desc_one_time_fee				= if_property_exist($params,'desc_one_time_fee');
        
        $billing_of_month				= if_property_exist($params,'billing_of_month',0,array(0,""));
        $billing_cycle_id  				= if_property_exist($params,'billing_cycle_id',0,array(0,""));
        $salary             			= if_property_exist($params,'salary',0,array(0,""));
        $daily_salary					= if_property_exist($params,'daily_salary',0,array(0,""));
        $bonus     						= if_property_exist($params,'bonus',0,array(0,""));
        $epcost   						= if_property_exist($params,'epcost',0,array(0,""));
        $dpcost							= if_property_exist($params,'dpcost',0,array(0,""));	
        $overseas_visa_cost				= if_property_exist($params,'overseas_visa_cost',0,array(0,""));
        $outpatient_medical_cost   		= if_property_exist($params,'outpatient_medical_cost',0,array(0,""));
        $medical_insurance_cost    		= if_property_exist($params,'medical_insurance_cost',0,array(0,""));
        $laptop_cost					= if_property_exist($params,'laptop_cost',0,array(0,""));
        $temp_accommodation_cost		= if_property_exist($params,'temp_accommodation_cost',0,array(0,""));
        $mobilization_cost				= if_property_exist($params,'mobilization_cost',0,array(0,""));
        $flight_ticket_cost				= if_property_exist($params,'flight_ticket_cost',0,array(0,""));
        $notice_period_buyout			= if_property_exist($params,'notice_period_buyout',0,array(0,""));
        $other_cost						= if_property_exist($params,'other_cost',0,array(0,""));
        $other_cost_remarks				= if_property_exist($params,'other_cost_remarks',0,array("",""));
        
        
        $recruiter_commission			= if_property_exist($params,'recruiter_commission',0,array(0,""));			
        $epf      						= if_property_exist($params,'epf',0,array(0,""));
        $socso             				= if_property_exist($params,'socso',0,array(0,""));
        $total_company_cost     		= if_property_exist($params,'total_company_cost',0,array(0,""));
        $overtime_rate   				= if_property_exist($params,'overtime_rate',0,array(0,""));
        $working_days_id				= if_property_exist($params,'working_days_id',0,array(0,""));	
        $annual_leave					= if_property_exist($params,'annual_leave',0,array(0,""));
        $medical_leave    				= if_property_exist($params,'medical_leave',0,array(0,""));
        $travelling_claim    			= if_property_exist($params,'travelling_claim',0,array(0,""));
        $medical_claim      			= if_property_exist($params,'medical_claim',0,array(0,""));
        
        $ep_required					= if_property_exist($params,'ep_required',0,array(0,""));
        $overtime_applicable    		= if_property_exist($params,'overtime_applicable',0,array(0,""));
        $medical_leave_by_client     	= if_property_exist($params,'medical_leave_by_client',0,array(0,""));	
        $annual_leave_by_client			= if_property_exist($params,'annual_leave_by_client',0,array(0,""));
		$annual_leave_encash_allow		= if_property_exist($params,'annual_leave_encash_allow');
		$replacement_leave_applicable	= if_property_exist($params,'replacement_leave_applicable');
        $new_contract     				= if_property_exist($params,'new_contract');	
        $employment_type_id				= if_property_exist($params,'employment_type_id',0,array(0,""));
        $notice_period_id				= if_property_exist($params,'notice_period_id',0,array(0,""));	
        $total_contract_value			= if_property_exist($params,'total_contract_value',0,array(0,""));
        $sales_commission    			= if_property_exist($params,'sales_commission',0,array(0,""));
		$notification_email_to    		= if_property_exist($params,'notification_email_to');
        $notification_month      		= if_property_exist($params,'notification_month');
        $client_to_hire_allow   		= if_property_exist($params,'client_to_hire_allow');
        $client_location     			= if_property_exist($params,'client_location');
		$reference_check_applicable		= if_property_exist($params,'reference_check_applicable');
        $is_active   					= if_property_exist($params,'is_active');
		
		$sales_approval					= if_property_exist($params,'sales_approval');
		$eis							= if_property_exist($params,'eis',0,array(0,""));
		$hrdf							= if_property_exist($params,'hrdf',0,array(0,""));
		$remarks						= if_property_exist($params,'hrdf',"");
		
		$current_company				= if_property_exist($params,'current_company',"");
		$current_ep_expiry_date			= if_property_exist($params,'current_ep_expiry_date',"");
		$apply_ep_on_date				= if_property_exist($params,'apply_ep_on_date',"");
		$able_to_obtain_noc				= if_property_exist($params,'able_to_obtain_noc',"");
		$require_to_exit_country		= if_property_exist($params,'require_to_exit_country',"");
		$billing_type					= if_property_exist($params,'billing_type',0);
		
		$emp_id 	        			= if_property_exist($params,'emp_id');
		$status_id						= if_property_exist($params,'status_id');
		
		$attachment 	        		= if_property_exist($params,'attachment');
		$allowance						= if_property_exist($params,'allowance');
		$dependent						= if_property_exist($params,'dependent');
		$reference						= if_property_exist($params,'reference');
		
		
		$contract_date 	 				= convert_to_date($contract_date);
		$request_date 	 				= convert_to_date($request_date);
        $start_date 	 				= convert_to_date($start_date);
        $end_date 	     				= convert_to_date($end_date); 
        $current_ep_expiry_date			= convert_to_date($current_ep_expiry_date);
        $apply_ep_on_date				= convert_to_date($apply_ep_on_date);
        $params->fn						= "bg_send_contract_notification";
        
        
        if($pono != '')
        {
        	$status_id = 176;
        }
        
        
        $data = array
        (
            ':contract_date'					=> 	$contract_date,	
			':requestor_id'						=> 	$requestor_id,
			':request_date'						=> 	$request_date,
			':recruiter_id'						=> 	$recruiter_id,
			':nric'								=> 	$nric,	
        	':nationality_id'					=> 	$nationality_id,	
        	':email'							=> 	$email,	
			':sex_id'							=> 	$sex_id,
			':marital_status_id'				=> 	$marital_status_id,			
			':employee_name'					=>  $employee_name,
        	':designation'						=>  $designation,
			':employer_id'			    		=>  $employer_id,
			':client_id'						=> 	$client_id,
			':pono'								=> 	$pono,
			':client_hiring_manager_name'		=> 	$hiring_manager_name,
			':hiring_manager_telno'				=> 	$hiring_manager_telno,
			':hiring_manager_email'				=> 	$hiring_manager_email,					
			':client_invoice_contact_person'	=>  $client_invoice_contact_person,
			':client_invoice_address_to'		=>  $client_invoice_address_to,
			':start_date'             			=>  $start_date,
			':end_date'             			=>  $end_date,		
			':duration'							=> 	$duration,
			':billing_amount'					=> 	$billing_amount,
			':billing_amount_with_gst'			=> 	$billing_amount_with_gst,		
        	':billing_of_month'					=>  $billing_of_month,
			':billing_cycle_id'             	=>  $billing_cycle_id,		
			':salary'							=>  $salary,
        	':daily_salary'						=>  $daily_salary,
			':bonus'							=> 	$bonus,
			':ep_cost'							=> 	$epcost,
			':dp_cost'							=> 	$dpcost,		
			':overseas_visa_cost'				=>  $overseas_visa_cost,
			':outpatient_medical_cost'       	=>  $outpatient_medical_cost,		
			':medical_insurance_cost'			=>  $medical_insurance_cost,			
			':laptop_cost'						=>  $laptop_cost,
			':temp_accommodation_cost'			=>  $temp_accommodation_cost,
			':mobilization_cost'				=>  $mobilization_cost,
			':flight_ticket_cost'				=>  $flight_ticket_cost,
			':notice_period_buyout'				=>  $notice_period_buyout,
			':other_cost'						=>  $other_cost,
        	':other_cost_remarks'				=>  $other_cost_remarks,
			':recruiter_commission'				=>  $recruiter_commission,						
            ':epf'            					=>  $epf,  		
			':socso'							=> 	$socso,
			':overtime_rate'					=> 	$overtime_rate,	
			':working_days_id'					=>  $working_days_id,
			':annual_leave'             		=>  $annual_leave,		
			':medical_leave'					=>  $medical_leave,			
			':travelling_claim_applicable'		=> 	$travelling_claim,
			':medical_claim_applicable'			=> 	$medical_claim,
			':overtime_applicable'				=> 	$overtime_applicable,		
			':medical_leave_day_by_client'		=>  $medical_leave_by_client,
			':annual_leave_day_by_client' 		=>  $annual_leave_by_client,		
			':annual_leave_encash_allowed'		=>  $annual_leave_encash_allow,	
			':replacement_leave_applicable'		=> 	$replacement_leave_applicable,		
			':new_contract_or_extension'		=>  $new_contract,
			':employment_type_id'				=>  $employment_type_id,
			':notice_period_id'					=>  $notice_period_id,
			':total_contract_value'             =>  $total_contract_value,		
			':sales_commission'					=>  $sales_commission,		
			':notification_email_to'			=> 	$notification_email_to,
			':notification_month'				=> 	$notification_month,
			':allow_for_client_to_hire'			=> 	$client_to_hire_allow,		
			':location'							=>  $client_location,
			':reference_check_applicable'		=>  $reference_check_applicable,
			':is_active'             			=>  $is_active,	
        	
        	':sales_approval'          	 		=>  $sales_approval,
        	':eis'          	 				=>  $eis,
        	':hrdf'          	 				=>  $hrdf,
        	':ep_required'          	 		=>  $ep_required,
        	':remarks'          	 			=>  $remarks,
        		
        	':current_company_name'				=> $current_company,
        	':current_ep_expiry_date'			=> $current_ep_expiry_date,
        	':apply_ep_on'						=> $apply_ep_on_date,
        	':able_to_obtain_noc'				=> $able_to_obtain_noc,
        	':require_to_exit_country'			=> $require_to_exit_country,
        	':billing_type'						=> $billing_type,
        	
        	':resume_source'					=> $resume_source,
        	':external_recruiter_id'			=> $external_recruiter_id,
        	':amount_paid_to_external_recruiter'=> $amount_paid_to_external_recruiter,
        	':external_sales_id'				=> $external_sales_id,
        	':amount_paid_to_external_sales'	=> $amount_paid_to_external_sales,
        	':referred_by'						=> $referred_by,
        	':referred_amount'					=> $referred_amount,
        	':one_time_fee'						=> $one_time_fee,
        	':desc_one_time_fee'				=> $desc_one_time_fee,
        	':home_address'						=> $home_address,
        	':candidate_contact_no'				=> $candidate_contact_no,
        	':status_id'						=> $status_id,
        	':client_name'						=> $client_name
        );
		     
        if(is_data_exist('cms_contracts', 'contract_no', $id))
        {
            $data[':contract_no']	= $id;
            $data 					= add_timestamp_to_array($data,$emp_id,1);
            $result 				= db_update($data, 'cms_contracts', 'contract_no');
			$params->action 		= "edit";
        }
        else
        {
            $data 					= add_timestamp_to_array($data,$emp_id,0);
            $id 					= db_add($data, 'cms_contracts');
            $params->id				= $id;
			$params->action 		= "add";
        }
		
        if(count($attachment) > 0)
        {
        	$result = add_edit_attachments($params);
    	}
    	
    	if(count($allowance) > 0)
    	{
    		$rs_allowance = add_edit_contract_allowance($params);
    	}
    	
    	if(count($dependent) > 0)
    	{
    		$rs_dependent = add_edit_contract_dependent($params);
    	}
    	
    	if(count($reference) > 0)
    	{
    		$rs_reference = add_edit_contract_reference($params);
    	}
        
    	
    	if((int)$status_id == 174)
    	{
    		$rs		  	= db_query('name,office_email','cms_employees','id=' . $recruiter_id);
    		if(count($rs) > 0)
    		{
    			$params->to_name	= $rs[0]['name'];
    			$params->email  	= $rs[0]['office_email'];
//     			initial_email_notification($params);
    			$pid 				= run_in_background(constant('JOB_DIR') . "/background_fn.php  " . urlencode(json_encode($params)));
    		}
    	}
        
    	if($pono != '')
    	{
	        if($sales_approval !== NULL && $sales_approval != "")
	        {
				$rs		  	= db_query('name,office_email','cms_employees','id=' . $sales_approval);
				if(count($rs) > 0)
				{
					$params->to_name	= $rs[0]['name'];
					$params->email  	= $rs[0]['office_email'];
// 					initial_email_notification($params);
					$pid 				= run_in_background(constant('JOB_DIR') . "/background_fn.php  " . urlencode(json_encode($params)));
				}
	        }
    	}
        
        $return_data = array('id' 			=> $id, 
        					 'allowance' 	=> $allowance, 
        					 'dependent' 	=> $dependent);
        
        return handle_success_response('Success', $return_data);
    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function add_edit_attachments($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		
		$attachment	= if_property_exist($params,'attachment');
		$id			= if_property_exist($params,'id');
		$emp_id		= if_property_exist($params,'emp_id');
		
		if(count($attachment) > 0)
		{
			$count_data = count($attachment);
			for($i = 0; $i < $count_data; $i++)
			{
				$filename			= if_property_exist($attachment[$i],'filename');
				$remarks			= if_property_exist($attachment[$i],'remarks',"");
				$doc_type_id		= if_property_exist($attachment[$i],'doc_type_id');
				
				$data = array
				(
					':contract_no'	=> 	$id,
					':filename'		=> 	$filename,
					':remarks'		=> 	$remarks,
					':doc_type_id'	=> 	$doc_type_id,
				);
				
				$rs_attach = db_query("id", "cms_contract_documents", "doc_type_id = " . $doc_type_id . " AND contract_no = " . $id);
				
				if(count($rs_attach) > 0)
				{
					$data[':id']	= $rs_attach[0]['id'];
					$data 			= add_timestamp_to_array($data,$emp_id,1);
					$result 		= db_update($data, 'cms_contract_documents', 'id');
				}
				else 
				{
					$data 			= add_timestamp_to_array($data,$emp_id,0);
					$result			= db_add($data, 'cms_contract_documents');
				}
			}
		}
		
		return handle_success_response('Success', true);
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function update_attachments_filename($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no= if_property_exist($params,'contract_no');
		$doc_type_id= if_property_exist($params,'doc_type_id');
		$filename	= if_property_exist($params,'filename');
		$emp_id		= if_property_exist($params,'emp_id');
		
		$row_effected = db_execute_sql("UPDATE cms_contract_documents set filename='" . $filename . "' WHERE  doc_type_id = " . $doc_type_id . " AND contract_no = " . $contract_no);
		
		
		return handle_success_response('Success', $row_effected);
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function add_edit_contract_allowance($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $allowance	= if_property_exist($params,'allowance');
        $contract_id= if_property_exist($params,'id');	//contract no
        $emp_id		= if_property_exist($params,'emp_id');
        
        $count_data = count($allowance);
        for($i = 0; $i < $count_data; $i++)
        {
        	$allowance_id	= if_property_exist($allowance[$i],'id');
        	$type_id		= if_property_exist($allowance[$i],'type_id');
        	$amount			= if_property_exist($allowance[$i],'amount');
        	$active_status	= if_property_exist($allowance[$i],'active_status');
        	
        	$data = array
        	(
        		':contract_no'		=> $contract_id,
        		':master_list_id'	=> $type_id,
        		':amount'			=> $amount,
        		':active_status'	=> $active_status
        	);
        	
        	if(is_data_exist('cms_contract_emp_allowance', 'id', $allowance_id))
        	{
        		$data[':id']	= $allowance_id;
        		$data 			= add_timestamp_to_array($data,$emp_id,1);
        		$result 		= db_update($data, 'cms_contract_emp_allowance', 'id');
        	}
        	else
        	{
        		$data 			= add_timestamp_to_array($data,$emp_id,0);
        		$id 			= db_add($data, 'cms_contract_emp_allowance');
        	}
        }
        
        $rs 	= json_decode(get_contract_allowance_list($params));

        return handle_success_response('Success', $rs->data);
    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function add_edit_contract_dependent($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$dependent	= if_property_exist($params,'dependent');
		$contract_id= if_property_exist($params,'id');	//contract no
		$emp_id		= if_property_exist($params,'emp_id');
		
		$count_data = count($dependent);
		for($i = 0; $i < $count_data; $i++)
		{
			$dependent_id	= if_property_exist($dependent[$i],'id');
			$type_id		= if_property_exist($dependent[$i],'type_id');
			$quantity		= if_property_exist($dependent[$i],'quantity');
			$amount			= if_property_exist($dependent[$i],'amount');
			$active_status	= if_property_exist($dependent[$i],'active_status');
			
			$data = array
			(
				':contract_no'		=> $contract_id,
				':master_list_id'	=> $type_id,
				':quantity'			=> $quantity,
				':amount'			=> $amount,
				':active_status'	=> $active_status
			);
			
			if(is_data_exist('cms_contract_emp_dependent', 'id', $dependent_id))
			{
				$data[':id']	= $dependent_id;
				$data 			= add_timestamp_to_array($data,$emp_id,1);
				$result 		= db_update($data, 'cms_contract_emp_dependent', 'id');
			}
			else
			{
				$data 			= add_timestamp_to_array($data,$emp_id,0);
				$id 			= db_add($data, 'cms_contract_emp_dependent');
			}
		}
		
		$rs 	= json_decode(get_contract_dependent_list($params));
		
		return handle_success_response('Success', $rs->data);
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function add_edit_contract_reference($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$reference	= if_property_exist($params,'reference');
		$contract_id= if_property_exist($params,'id');	//contract no
		$emp_id		= if_property_exist($params,'emp_id');
		
		$count_data = count($reference);
		for($i = 0; $i < $count_data; $i++)
		{
			$reference_id	= if_property_exist($reference[$i],'id');
			$name			= if_property_exist($reference[$i],'name');
			$contact_no		= if_property_exist($reference[$i],'contact_no');
			$email			= if_property_exist($reference[$i],'email');
			$company_name	= if_property_exist($reference[$i],'company_name');
			$designation	= if_property_exist($reference[$i],'designation');
			$relationship_id= if_property_exist($reference[$i],'relationship_id');
			$remarks		= if_property_exist($reference[$i],'remarks');
			$remarks_by		= if_property_exist($reference[$i],'remarks_by');
			$active_status	= if_property_exist($reference[$i],'active_status');
			
			$data = array
			(
				':contract_no'		=> $contract_id,
				':name'				=> $name,
				':contact_no'		=> $contact_no,
				':email'			=> $email,
				':company_name'		=> $company_name,
				':designation'		=> $designation,
				':relationship_id'	=> $relationship_id,
				':remarks'			=> $remarks,
				':remarks_by'		=> $remarks_by,
				':active_status'	=> $active_status
			);
			
			if(is_data_exist('cms_contract_emp_reference', 'id', $reference_id))
			{
				$data[':id']	= $reference_id;
				$data 			= add_timestamp_to_array($data,$emp_id,1);
				$result 		= db_update($data, 'cms_contract_emp_reference', 'id');
			}
			else
			{
				$data 			= add_timestamp_to_array($data,$emp_id,0);
				$id 			= db_add($data, 'cms_contract_emp_reference');
			}
		}
		
		$rs 	= json_decode(get_contract_reference_list($params));
		
		return handle_success_response('Success', $rs->data);
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function offer_letter_exist($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    		= if_property_exist($params,'contract_no');
		
		if($contract_no=== NULL || $contract_no== '')
		{
		 	return handle_fail_response('Contract No. is mandatory');
		}

		$rs = db_query("count(*) as id_count"
						, "cms_contract_documents INNER JOIN 
						   cms_upload_doc ON cms_contract_documents.doc_type_id = cms_upload_doc.id"
						, 
						"cms_upload_doc.name = 'offer_letter' AND 
						 cms_contract_documents.contract_no = " . $contract_no . " limit 1");
		
		if(isset($rs) && $rs != NULL)
		{
			$rec_exist = $rs[0]['id_count'];
		}
		else
		{
			$rec_exist = false;
		}
		
		return handle_success_response('Success', $rec_exist);
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function reference_feedback_exist($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    		= if_property_exist($params,'contract_no');
		
		if($contract_no === NULL || $contract_no == '')
		{
			return handle_fail_response('Contract No. is mandatory');
		}
		
		$rs = db_query("count(*) as id_count"
				, "cms_contract_emp_reference"
				,"(remarks IS NOT NULL AND remarks != '') AND cms_contract_emp_reference.contract_no = " . $contract_no);
				
		if((int)$rs[0]['id_count'] === 3)
		{
			$rec_exist = true;
		}
		else
		{
			$rec_exist = false;
		}
		
		return handle_success_response('Success', $rec_exist);
				
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_contract_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $contract_no	= if_property_exist($params,'contract_no','');

        if($contract_no === NULL || $contract_no == '')
        {
            return handle_fail_response('Contract Number is mandatory');
        }

        
        $rs = db_query("
						  contract_no		
						, date_format(contract_date,'" . constant('UI_DATE_FORMAT') .  "') as contract_date	
						, requestor_id
						, date_format(request_date,'" . constant('UI_DATE_FORMAT') .  "') as request_date	
						, recruiter_id
						, nric
						, sex_id
						, marital_status_id
						, home_address
						, candidate_contact_no
						, employee_name
						, designation
						, employer_id
						, client_id
						, pono
						, client_hiring_manager_name as hiring_manager_name
						, hiring_manager_telno
						, hiring_manager_email		
						, client_invoice_contact_person
						, client_invoice_address_to
						, date_format(start_date,'" . constant('UI_DATE_FORMAT') .  "') as start_date
						, date_format(end_date,'" . constant('UI_DATE_FORMAT') .  "') as end_date		
						, duration
						, billing_amount
						, billing_amount_with_gst
						, one_time_fee
						, desc_one_time_fee
						, billing_of_month
						, billing_cycle_id
						, salary
						, bonus
						, ep_cost as epcost
						, dp_cost as dpcost
						, overseas_visa_cost
						, outpatient_medical_cost
						, medical_insurance_cost						
						, laptop_cost
						, temp_accommodation_cost
						, mobilization_cost
						, flight_ticket_cost
						, notice_period_buyout
						, other_cost
						, other_cost_remarks
						, recruiter_commission					
						, epf
						, socso
						, overtime_rate
						, working_days_id
						, annual_leave
						, medical_leave
						, travelling_claim_applicable as travelling_claim
						, medical_claim_applicable as medical_claim
						, overtime_applicable
						, medical_leave_day_by_client as medical_leave_by_client
						, annual_leave_day_by_client as annual_leave_by_client
						, annual_leave_encash_allowed as annual_leave_encash_allow
						, replacement_leave_applicable
						, new_contract_or_extension as new_contract
						, employment_type_id
						, notice_period_id
						, total_contract_value
						, sales_commission
						, notification_email_to
						, notification_month
						, allow_for_client_to_hire as client_to_hire_allow
						, location as client_location
						, reference_check_applicable
						, is_active
						, filename						
						, doc_resume
						, doc_passport_copy
						, doc_education
						, doc_experience_letter
						, doc_noc
						, doc_incometax_clearance
						, doc_salary_slip
						, doc_reference_check_doc
						, doc_birth_cert
						, doc_marriage_cert
						, doc_dependant_photo
						, doc_dependant_passport
						, concat('" . constant("UPLOAD_DIR_URL")  . "','contracts/', cms_contracts.contract_no, '/') as docpath					
						, created_by	
						, sales_approval
						, nationality_id
						, email
						, eis
						, hrdf
						, remarks
						, ep_required
						, issued_offer
						, date_format(onboard_date,'" . constant('UI_DATE_FORMAT') .  "') as onboard_date
						, (select count(*) from cms_employees where contract_no = cms_contracts.contract_no) as	emp_rec_exist
						
						, cms_contracts.current_company_name
						, cms_contracts.current_ep_expiry_date
						, cms_contracts.apply_ep_on
						, cms_contracts.able_to_obtain_noc
						, cms_contracts.require_to_exit_country
						, cms_contracts.billing_type
						, cms_contracts.resume_source
						
						, cms_contracts.external_recruiter_id
						, cms_contracts.amount_paid_to_external_recruiter
						, cms_contracts.external_sales_id
						, cms_contracts.amount_paid_to_external_sales
						, cms_contracts.referred_by
						, cms_contracts.referred_amount
						, cms_contracts.status_id
						, cms_contracts.client_name

			
					",
            "cms_contracts",
            "contract_no = ". $contract_no);

		$rs_allowance 	= json_decode(get_contract_allowance_list($params));
		$rs_dependent 	= json_decode(get_contract_dependent_list($params));
		$rs_attachment	= json_decode(get_contract_doc_list($params));
		$rs_ref  		= json_decode(get_contract_reference_list($params));
		
				
		$data['details']	= $rs[0];
		$data['allowance'] 	= $rs_allowance->data;
		$data['dependent'] 	= $rs_dependent->data;
		$data['attachment'] = $rs_attachment->data;
		$data['reference']	= $rs_ref->data;

		return handle_success_response('Success', $data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_contract_doc_list($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    = if_property_exist($params, 'contract_no');
		
		if($contract_no === NULL || $contract_no == "")
		{
			return handle_fail_response('Contract Number is mandatory');
		}
		
		$rs = db_query
		(
			" cms_contract_documents.id
			, cms_contract_documents.contract_no
       		, cms_contract_documents.filename
			, cms_contract_documents.remarks
			, cms_contract_documents.doc_type_id
			, concat('" . constant("UPLOAD_DIR_URL")  . "','contracts/', cms_contract_documents.contract_no, '/') as docpath	
            ",
			"
           		cms_contract_documents
				INNER JOIN cms_upload_doc ON cms_contract_documents.doc_type_id = cms_upload_doc.id
            ",
				"cms_contract_documents.contract_no = " . $contract_no
		);
		
		if(count($rs) < 1 || !isset($rs))
		{
			return handle_fail_response('No record found');
		}
		
		return handle_success_response('Success', $rs);
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_allowance_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id	= if_property_exist($params,'id','');

        if($id === NULL || $id == '')
        {
            return handle_fail_response('Allowance ID is mandatory');
        }

        
        $rs = db_query("
						  id		
						, contract_no		
						, master_list_id
						, amount
						, active_status
						, created_by					
					",
            "cms_contract_emp_allowance",
            "id = ". $id);

        return handle_success_response('Success', $rs);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function get_contract_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $contract_no = if_property_exist($params, 'contract_no');
		$where	=	" cms_contract_remark.contract_no = '" . $contract_no . "' AND cms_contract_remark.is_active = 1";

        $rs = db_query
        (
            "cms_contract_remark.id
			, cms_contract_remark.contract_no
			, cms_contract_remark.contract_remarks
			, IFNULL(cms_contract_remark.action,'N/A') as action
			, (select name from cms_employees emp where emp.id = cms_contract_remark.created_by) as created_by
			, date_format(cms_contract_remark.created_date,'" . constant('UI_DATE_FORMAT') .  "') as created_date 
            , cms_contracts.employer_id as owner_emp_id
			",
            "cms_contract_remark INNER JOIN cms_contracts ON
             cms_contract_remark.contract_no = cms_contracts.contract_no
            ",
            $where, '','', 'cms_contract_remark.created_date', 'DESC');

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

function contract_add_edit_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $contract_no    	= if_property_exist($params,'contract_no');
        $contract_remarks   = if_property_exist($params,'contract_remarks');
		$action				= if_property_exist($params,'action');
		$level				= if_property_exist($params,'level');
		$user_id     		= if_property_exist($params,'user_id');
		$user_name     		= if_property_exist($params,'user_name');
		$notify_emp_id		= if_property_exist($params,'notify_emp_id');
	
		
		$params->subject	= 'Contract Remarks Added';	
		$action_desc 		= $action;
		
		if($action == 'approve')
		{
			$params->approved 		= 1;
			$params->subject		= 'Contract Approved'.'('.$level.')';
			$action_desc 			= 'approved';
			if($level == 'OFFER_HR')
			{
				$params->subject	= 'Contract Offer Letter Issued'.'('.$level.')';
				$action_desc = 'OL issued';
			}
			contract_approve($params);			
		}
		if($action == 'cancel')
		{
			$params->approved  		= 0;
			$params->subject		= 'Approval Cancelled'.'('.$level.')';
			$action_desc 			= 'cancelled';
			contract_approve($params);			
		}
		
		$data = array
        (
            ':contract_no'		=> 	$contract_no,				
			':contract_remarks'	=>  $contract_remarks,
			':action'			=>  trim($action_desc.'-'.strtolower($level)),
			':created_by'		=> 	$user_id
        );
		
		$data	= add_timestamp_to_array($data,$user_id,0);
        $id		= db_add($data, 'cms_contract_remark');		
		
        if($notify_emp_id != "")
        {
			$rs		  	= db_query('name,email','cms_employees','id =' . $notify_emp_id);
			if(count($rs) > 0)
			{
				$params->to_name  	= $rs[0]['name'];
				$params->email  	= $rs[0]['email'];
				approve_email_notification($params);
			}
        }
        
        $rs = json_decode(get_contract_remark($params));
        return handle_success_response('Success', $rs->data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function contract_approve($params)
{
	log_it(__FUNCTION__, $params);

	$contract_no    	= if_property_exist($params,'contract_no');
	$contract_remarks   = if_property_exist($params,'contract_remarks');
    $level	         	= if_property_exist($params,'level');
	$user_id     		= if_property_exist($params,'user_id');
	$user_name     		= if_property_exist($params,'user_name');
	$status         	= if_property_exist($params,'approved');
	$action				= if_property_exist($params,'action');		
    $curr_date	    	= get_current_date();
	$next_level 		=  '';
	$prev_level 		=  '';	
		
	if($level == 'SALES_HEAD')
	{
		$data = array
        (
         	':approved_sales_head'			=> $status,
         	':approved_sales_head_by'		=> $user_id,
         	':approved_sales_head_date'    	=> $curr_date,
        	':status_id'					=> 176
     	);
		$next_level =  58;
	}
		
	if($level == 'HR')
	{
		$data = array
     	(
     		':approved_hr'				=> $status,
            ':approved_hr_by'			=> $user_id,
            ':approved_hr_date'    		=> $curr_date,
     		':status_id'				=> 176
         );
		 $next_level =  59;
		 $prev_level =  57;
	}
		
	if($level == 'ACCOUNTS')
	{
		$data = array
        (
        	':approved_accounts'		=> $status,
         	':approved_accounts_by'		=> $user_id,
        	':approved_accounts_date'	=> $curr_date,
        	':status_id'				=> 176
       	);
		$next_level =  60;
		$prev_level =  58;
	}
		
	if($level == 'CEO')
	{
		$data = array
		(
			':approved_ceo'				=> $status,
			':approved_ceo_by'			=> $user_id,
			':approved_ceo_date'    	=> $curr_date,
			':status_id'				=> 176
		);	
		$next_level =  58;	
		$prev_level =  59;			
	}
	
	if($level == 'OFFER_HR')
	{
		$data = array
		(
			':issued_offer'				=> $status,
			':issued_offer_by'			=> $user_id,
			':issued_offer_date'    	=> $curr_date,
			':status_id'				=> 177
		);	
		$next_level =  61;	
		$prev_level =  60;
		send_offer_notification($params);
	}
		
	$data[':contract_no']   = $contract_no;
	$results 		    	= db_update($data, 'cms_contracts', 'contract_no');
	
	if($next_level != '')
	{
		$rs		  	= db_query('name,office_email','cms_employees','access_level = '.$next_level.' AND allow_contract_approve = 1');
		for($i = 0; $i < count($rs); $i++)
		{
			$params->to_name  	= $rs[$i]['name'];
			$params->email  	= $rs[$i]['office_email'];
			approve_email_notification($params);
		}
	}
	
	if($prev_level != '' && $action == 'cancel')
	{
		$rs		  	= db_query('name,office_email','cms_employees','access_level = '.$prev_level.' AND allow_contract_approve = 1');
		
		if($rs )
		for($i = 0; $i < count($rs); $i++)
		{
			$params->to_name  	= $rs[$i]['name'];
			$params->email  	= $rs[$i]['office_email'];
			approve_email_notification($params);
		}
	}

}

function initial_email_notification($params)
{	
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
					
	if(!smtpmailer
		(
			$email,
			constant('MAIL_USERNAME'),
			constant('MAIL_FROMNAME'),
			$subject,
			$body
		))
	{
		return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
	}
}

function approve_email_notification($params)
{	
	$contract_no    	= if_property_exist($params,'contract_no');
	$contract_remarks   = if_property_exist($params,'contract_remarks');
	$user_name     		= if_property_exist($params,'user_name');
	$to_name        	= if_property_exist($params,'to_name');
	$email        		= if_property_exist($params,'email');
	$subject        	= if_property_exist($params,'subject');
			
	$result			= db_query('employee_name','cms_contracts','contract_no = ' . $contract_no.'');
	$employee_name	= $result[0]['employee_name'];			
			
	$template 	= file_get_contents(constant('TEMPLATE_DIR') . '/remarks_contracts_reminder.html');
	$replace 	= array('{APP_TITLE}', '{NAME}', '{USER}','{REMARKS}','{MAIL_SIGNATURE}','{CONTRACT_NAME}','{CONTRACT_NO}');
	$with 		= array(constant('APPLICATION_TITLE'), $to_name, $user_name, $contract_remarks,constant('MAIL_SIGNATURE'),$employee_name,$contract_no);
	$body		= str_replace($replace, $with, $template);
					
	if(!smtpmailer
		(
			$email,
			constant('MAIL_USERNAME'),
			constant('MAIL_FROMNAME'),
			$subject,
			$body
		))
	{
		return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
	}
}

function send_offer_notification($params)
{
	try 
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    = if_property_exist($params,'contract_no');
		
		$result			= db_query("cms_contracts.email as candidate_email,
									cms_contracts.employee_name as candidate_name,
									cms_contracts.designation, 
									cms_master_employer.employer_name,
									cms_master_employer.mail_signature,
									cms_employees.office_email as recruiter_email, 
									hr.office_email as hr_email,
						   (
								SELECT filename
								FROM cms_contract_documents INNER JOIN 
								cms_upload_doc ON cms_contract_documents.doc_type_id = cms_upload_doc.id  
								WHERE cms_upload_doc.name='offer_letter'
								AND cms_contract_documents.contract_no = " . $contract_no . " limit 1
							) as filename
						   "
							,'cms_contracts 
							  INNER JOIN cms_master_employer on cms_contracts.employer_id = cms_master_employer.id
							  INNER JOIN cms_employees on cms_contracts.recruiter_id = cms_employees.id
							  INNER JOIN cms_employees hr on cms_contracts.issued_offer_by = hr.id'
							,'cms_contracts.contract_no = ' . $contract_no . '');
		
		$download_param	= urlencode(encrypt_string(json_encode(
						  array
						  (
							   "path" 		=> "contracts/" . $contract_no . "/" . $result[0]['filename'], 
							   "field" 		=> array("candidate_viewed","candidate_viewed_dt"), 
							   "field_val" 	=> array("1",get_current_date()),
							   "tbl" 		=> "cms_contracts",
							   "filter" 	=> "contract_no",
							   "val" 		=> $contract_no,
							   "filename"	=> $result[0]['filename']	
							))));
	
		$template 		= file_get_contents(constant('TEMPLATE_DIR') . '/offer_letter_notification.html');
		$replace 		= array('{NAME}', '{EMPLOYER}','{LINK}','{PARAM}','{APP_TITLE}','{MAIL_SIGNATURE}');
		$with 			= array($result[0]['candidate_name'],$result[0]['employer_name'],constant('DOWNLOAD_URL'),$download_param,
								constant('APPLICATION_TITLE'),$result[0]['mail_signature']);
		$body			= str_replace($replace, $with, $template);
		
		if(!smtpmailer
		(
			$result[0]['candidate_email'],
			constant('MAIL_USERNAME'),
			constant('MAIL_FROMNAME'),
			"CONGRATULATIONS - " . $result[0]['candidate_name'] . " (" . $result[0]['designation']. ")",
			$body,($result[0]['recruiter_email'] . ";" . $result[0]['hr_email'])
		))
		{
			return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function contract_delete_remark($params)
{
    try
    {
        log_it(__FUNCTION__, $params);
    
        $id	        	= if_property_exist($params, 'id');
		$contract_no  	= if_property_exist($params,'contract_no');
		$emp_id	        = if_property_exist($params, 'emp_id');
        
        if($contract_no === NULL || $contract_no == '')
        {
            return handle_fail_response('Contract No. is mandatory');
        }

        $data = array
        (
            ':id'  		  => $id,
			':is_active'  => 0         
        );
        
        $data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_contract_remark','id');     
        $list       = json_decode(get_contract_remark($params));

        return handle_success_response('Success', $list->data);
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function create_emp_data_from_contract($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$contract_no    = if_property_exist($params,'contract_no');
		$onboard_date	= if_property_exist($params,'onboard_date');
		$emp_id  		= if_property_exist($params,'emp_id');
		
		
		if($contract_no === NULL || $contract_no== '')
		{
			return handle_fail_response('Please provide Contract No.');
		}
		if($onboard_date === NULL || $onboard_date == '')
		{
			return handle_fail_response('Please provide Onboard Date');
		}
		$onboard_date	= convert_to_date($onboard_date);
		
		$data = array
		(
			':onboard_date' => $onboard_date,
			':contract_no'  => $contract_no
		);
		
		$data 		= add_timestamp_to_array($data,$emp_id, 1);
		$result 	= db_update($data, 'cms_contracts','contract_no');
		
		$rs	= db_query('
						cms_contracts.employee_name
						, cms_contracts.designation
						,cms_contracts.nric
						,cms_contracts.sex_id
						,cms_contracts.nationality_id
						,cms_contracts.employer_id
						,cms_contracts.nationality_id
						,cms_contracts.email
						,cms_contracts.employee_name
						,cms_contracts.onboard_date
						,cms_contracts.end_date
						,cms_contracts.salary
						,cms_contracts.notice_period_id
						,cms_contracts.contract_no
						,cms_master_employer.mail_signature
						, (select office_email from cms_employees where id = 24) as admin_email
						, (select name from cms_employees where Id = 24) as admin_name
						'
						,'cms_contracts INNER JOIN cms_master_employer on cms_contracts.employer_id = cms_master_employer.id '
						,"contract_no = " . $contract_no);

		if(count($rs) > 0)
		{
			$data = array
			(
				':name'  			=> $rs[0]['employee_name'],
				':designation'  	=> $rs[0]['designation'],
				':ic_passport'  	=> $rs[0]['nric'],
				':sex_id'  			=> $rs[0]['sex_id'],
				':nationality'  	=> $rs[0]['nationality_id'],
				':employer_id' 		=> $rs[0]['employer_id'],
				':home_country'		=> $rs[0]['nationality_id'],
				':email'  			=> $rs[0]['email'],
// 				':username'  		=> $rs[0]['employee_name'],
// 				':pswd'  			=> $rs[0]['employee_name'],
				':work_start_date' 	=> $rs[0]['onboard_date'],
				':work_end_date' 	=> $rs[0]['end_date'],
				':salary'  			=> $rs[0]['salary'],
				':notice_period_id'	=> $rs[0]['notice_period_id'],
				':contract_no'  	=> $rs[0]['contract_no'],
				':is_active'  		=> 0
			);
			
			$data			= add_timestamp_to_array($data,$emp_id,0);
			$id				= db_add($data, 'cms_employees');		
			
			
			
			
			$template 		= file_get_contents(constant('TEMPLATE_DIR') . '/employee_data_created_notification.html');
			$replace 		= array('{NAME}', '{CANDIDATE_NAME}','{MAIL_SIGNATURE}','{APP_TITLE}');
			$with 			= array($rs[0]['admin_name'],$rs[0]['employee_name'],$rs[0]['mail_signature'],constant('APPLICATION_TITLE'));
			$body			= str_replace($replace, $with, $template);
			
			if(!smtpmailer
			(
				$rs[0]['admin_email'],
				constant('MAIL_USERNAME'),
				constant('MAIL_FROMNAME'),
				"Employee has been created",
				$body
			))
			{
				return handle_fail_response('ERROR','Send Email to admin fail. Please re-try again later');
			}
			
			return handle_success_response('Success',true);
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function revoke_contract_approval($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		
		$contract_no= if_property_exist($params,'contract_no');	//contract no
		$sh			= if_property_exist($params,'sh');
		$hr			= if_property_exist($params,'hr');
		$acc		= if_property_exist($params,'acc');
		$ceo		= if_property_exist($params,'ceo');
		$io			= if_property_exist($params,'io');
		$emp_id		= if_property_exist($params,'emp_id');
		$result		= false;
		
		$data = array
		(
			':contract_no'			=> $contract_no,
			':approved_sales_head'	=> $sh,
			':approved_hr'			=> $hr,
			':approved_accounts'	=> $acc,
			':approved_ceo'			=> $ceo,
			':issued_offer'			=> $io,
		);
			
		if(is_data_exist('cms_contracts', 'contract_no', $contract_no))
		{
			$data 			= add_timestamp_to_array($data,$emp_id,1);
			$result 		= db_update($data, 'cms_contracts', 'contract_no');
		}
		
		return handle_success_response('Success', $result);
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_contract_report($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$from_date	    = if_property_exist($params, 'from_date','');
		$to_date	    = if_property_exist($params, 'to_date','');
		$recruiter_id   = if_property_exist($params, 'recruiter_id','');
		$emp_id	        = if_property_exist($params, 'emp_id','');
		$from_date		=  substr($from_date,6,4).'-'.substr($from_date,3,2).'-'.substr($from_date,0,2);
		$to_date		=  substr($to_date,6,4).'-'.substr($to_date,3,2).'-'.substr($to_date,0,2);
		$where	=	"cms_contracts.contract_no != '' AND cms_contracts.is_active = 1 AND cms_contracts.contract_date >= '" . $from_date . "' AND cms_contracts.contract_date <= '" . $to_date . "'";
		
		if($recruiter_id != "ALL")
		{
			$where 	.=  " AND cms_contracts.recruiter_id = " . $recruiter_id;
		}
		
		$rs = db_execute_custom_sql(
				"SELECT
					cms_contracts.contract_no
					, cms_contracts.contract_date
					, cms_contracts.recruiter_id
					, (select name from cms_employees where cms_employees.id = cms_contracts.recruiter_id
	) as recruiter_name
					, (select descr from cms_master_list where cms_master_list.id = cms_contracts.client_id
	) as client_name
					, (select count(cms_contracts.contract_no) from cms_contracts where cms_contracts.recruiter_id = cms_employees.id) as recruiter_count
				FROM
					cms_contracts
					INNER JOIN cms_employees
						ON (cms_contracts.recruiter_id = cms_employees.id)
				WHERE
					".$where."
				ORDER BY
					cms_contracts.recruiter_id ASC, cms_contracts.contract_date ASC");
		
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

?>
