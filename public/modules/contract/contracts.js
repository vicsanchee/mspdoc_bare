var FORM_STATE 		= 0;
var RECORD_INDEX 	= 0;
var SESSIONS_DATA	= '';
var REF_ROW			= '';
var btn_save,btn_save_remarks,btn_save_revoke_approval; 
CONTRACT_ID		= ''; 
CREATED_BY		= '';
CLIENT_ID		= '';
CURRENT_PATH	= 	'../../';
var btn_onboard_save;

$.fn.data_table_features = function()
{
	try
	{
		if (!$.fn.dataTable.isDataTable( '#tbl_list' ))
		{
			table = $('#tbl_list').DataTable
			({
				"searching"	: false,
				"paging"	: false,
				"info"		: false,
//				 "ordering": false
				"order": []
			});
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.data_table_destroy = function()
{
	try
	{
		if ($.fn.dataTable.isDataTable('#tbl_list') )
		{
			table.destroy();
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.reset_form = function(form)
{
    try
    {
        FORM_STATE		= 0;
        
        if(form == 'list')
        {
            $('#txt_name_search')		.val('').change();
            $('#dd_status_search')		.val('').change();
            $('#dd_client_search')		.val('').change();
            $('#dd_created_by_search')	.val('').change();
            $('#dd_stage_status_search').val(JSON.parse("[174,175,176]")).change();
        }
        else if(form == 'form')
        {			
        	CONTRACT_ID		= '';
        	CREATED_BY		= '';
        	CLIENT_ID		= '';
        	$('#dd_contract_type')					.val('').change();
        	$('#contract_date')						.val('');
        	
        	$('#dd_resume_source')					.val('').change();
        	$('#txt_referral_name')					.val('');
        	$('#txt_referral_amount')				.val('');
        	
        	$('#dd_ext_rec')						.val('').change();
        	$('#txt_ext_rec_amount')				.val('0');
        	$('#dd_ext_sales')						.val('').change();
        	$('#txt_ext_sales_amount')				.val('0');
        	
        	$('#dd_requestor_name')					.val('').change();
			$('#request_date')						.val('');
			$('#dd_recruiter_name')					.val('').change();
			$('#dd_employer')						.val('').change();
        	
			$('#txt_employee_name')					.val('');
			$('#txt_designation')					.val('');
			$('#txt_nric')							.val('');
			$('#dd_nationality')					.val('').change();
			$('#txt_email')							.val('');
			$('#dd_sex')							.val('').change();
			$('#dd_marital_status')					.val('').change();	
			$('#txt_home_address')					.val('');
			$('#txt_contact_no')					.val('');
			$('#s2id_dd_client')					.show();
			$('#dd_client')							.val('').change().attr('required','required');
			$('#lbl_client_name')					.val('').hide();
			$('#txt_hiring_manager_name')			.val('');
			$('#txt_hiring_manager_telno')			.val('');
			$('#txt_hiring_manager_email')			.val('');
			$('#txt_client_invoice_contact_person')	.val('');
			$('#txt_client_invoice_address_to')		.val('');
			$('#dd_employment_type')				.val('').change();	
			$('#txt_client_location')				.val('');
			$('#start_date')						.val('');
			$('#end_date')							.val('');
			$('#txt_duration')						.val('');
			$('#dd_notice_period')					.val('').change();
			$('#txt_billing_amount')				.val('');
			$('#txt_billing_amount_with_gst')		.val('');
			
			$('#txt_one_time_fees')					.val('');
			$('#txt_fee_descr')						.val('');
			
			$('#txt_billing_of_month')				.val('');
			$('#dd_billing_cycle')					.val('').change();
			$('#dd_working_days')					.val('').change();
			$('#txt_overtime_rate')					.val('');
			$('#txt_annual_leave')					.val('');
			$('#txt_medical_leave')					.val('');	
			$('#txt_annual_leave_by_client')		.val('');
			$('#txt_medical_leave_by_client')		.val('');
			$('#dd_notification_email_to')			.val('').change();
			$('#notification_month')				.val('');
			$('#dd_sales_approval')					.val('').change();
			$('#txt_eis')							.val('');
			$('#txt_hrdf')							.val('');
			$('#txt_remarks')						.val('');
			$('#onboard_date')						.val('');
			
			
			$('#txt_salary')						.val('');
			$('#txt_daily_salary')					.val('');
			$('#txt_bonus')							.val('');
			$('#txt_epf')							.val('');		
			$('#txt_socso')							.val('');
			$('#txt_epcost')						.val('');			
			$('#txt_dpcost')						.val('');
			$('#txt_overseas_visa_cost')			.val('');
			$('#txt_laptop_cost')					.val('');
			$('#txt_medical_insurance_cost')		.val('');
			$('#txt_outpatient_medical_cost')		.val('');
			$('#txt_temp_accommodation_cost')		.val('');
			$('#txt_mobilization_cost')				.val('');
			$('#txt_flight_ticket_cost')			.val('');
			$('#txt_notice_period_buyout')			.val('');
			$('#txt_other_cost')					.val('');
			$('#txt_other_cost_remarks')			.val('');
			$('#txt_recruiter_commission')			.val('');							
			$('#txt_total_contract_value')			.val('');	
			$('#dd_status')							.val('174').change();
			
			$('#txt_sales_commission')				.val('');
			
			$('#chk_ep_required')					.prop('checked',false);
			$('#chk_overtime_applicable')			.prop('checked',false);	
			$('#chk_client_to_hire_allow')			.prop('checked',false);	
			$('#chk_replacement_leave_applicable')	.prop('checked',false);
			$('#chk_annual_leave_encash_allow')		.prop('checked',false);
			$('#chk_is_active')						.prop('checked',true);	
			$('#chk_reference_check')				.prop('checked',false);
			$('#chk_travelling_claim')				.prop('checked',false);
			$('#chk_medical_claim')					.prop('checked',false);		
			$('#txt_pono')							.val('');
			
			$('.form-group').each(function () { $(this).removeClass('has-error'); });
			$('.help-block').each(function () { $(this).remove(); });
			
			$('.attachment_doc')				.val('').attr('data','');
			$('.doc_link')						.html('');
			$('.document')						.html('');
			$('#div_onboard_date')				.hide();
			
	       	$('#btn_save')						.show();
	       	$('#btn_cancel')					.show();

	       	$('#dd_td_allowance')				.val('').change();
			$('#txt_amount')					.val('');
	       	$("#tbl_allowance tbody")			.find("tr:not('#base_row')").remove();
	       	
	       	
	       	$('#dd_td_dependent')				.val('').change();
	       	$('#dd_dp_qty')						.val("0").change();
			$('#txt_td_dependent_amount')		.val('');
	       	$("#tbl_dependent tbody")			.find("tr:not('#base_row_dependent')").remove();
	       	
	       	$('#txt_td_ref_name')				.val('');
			$('#txt_td_ref_contact')			.val('');
			$('#txt_td_ref_email')				.val('');
			$('#txt_td_ref_comp_name')			.val('');
			$('#txt_td_ref_desg')				.val('');
			$('#dd_td_ref_relation')			.val('').change();
			$('#txt_td_ref_remarks')			.val('');
	       	$("#tbl_reference tbody")			.find("tr:not('#base_row_reference')").remove();
	       	
	       	
	       	$('#txt_current_company')			.val('');
	       	$('#txt_current_ep_expiry_date')	.val('');
	       	$('#txt_apply_ep_on_date')			.val('');
	       	$('#chk_noc')						.prop('checked',false);
	       	$('#chk_leave_country_required')	.prop('checked',false);
	       	$('#dd_billing_type')				.val(0).change();
	       	
			$.fn.reset_upload_form();

        }
		else if(form == 'allowance_form')
        {
			$('#dd_td_allowance')	.val('').change();
			$('#txt_amount')		.val('');	
		}
		else if(form == 'dependent_form')
        {
			$('#dd_td_dependent')			.val('').change();
			$('#dd_dp_qty')					.val("0").change();
			$('#txt_td_dependent_amount')	.val('');
		}
		else if(form == 'reference_form')
        {
			$('#txt_td_ref_name')				.val('');
			$('#txt_td_ref_contact')			.val('');
			$('#txt_td_ref_email')				.val('');
			$('#txt_td_ref_comp_name')			.val('');
			$('#txt_td_ref_desg')				.val('');
			$('#dd_td_ref_relation')			.val('').change();
			$('#txt_td_ref_remarks')			.val('');
		}
		else if(form == 'remark_modal')
        {
			$('#ct_no')								.val('');	
			$('#chk_level')							.val('');
			$('#ct_remark')							.val('');							
		}
		else if(form == 'remark_list_modal')
        {
			$('#contract_no')						.val('');	
			$('#contract_remark')					.val('');						
		}

    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.populate_detail_form = function(data)
{
	try
	{
		var data 	= JSON.parse(data);
		$.fn.show_hide_form	('EDIT');
	 	$('#h4_primary_no').text('Contract Number : ' + data.contract_no);
			
	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_contract_details',{contract_no : data.contract_no}),	
			function(return_data)
			{
				if(return_data.data)
				{
					let data 								= return_data.data.details;
					CONTRACT_ID								= data.contract_no;	
					CREATED_BY								= data.created_by;
					CLIENT_ID								= data.client_id;
					$('#detail_form')						.parsley().destroy();
					
					
					$('#dd_contract_type')					.val(data.new_contract).change();
					$('#contract_date')						.val(data.contract_date);
					
					$('#dd_resume_source')					.val(data.resume_source).change();
		        	$('#txt_referral_name')					.val(data.referred_by);
		        	$('#txt_referral_amount')				.val(data.referred_amount);
		        	
		        	$('#dd_ext_rec')						.val(data.external_recruiter_id).change();
		        	$('#txt_ext_rec_amount')				.val(data.amount_paid_to_external_recruiter);
		        	$('#dd_ext_sales')						.val(data.external_sales_id).change();
		        	$('#txt_ext_sales_amount')				.val(data.amount_paid_to_external_sales);
					
					
					$('#dd_requestor_name')					.val(data.requestor_id).change();
					$('#request_date')					    .val(data.request_date);
					$('#dd_recruiter_name')					.val(data.recruiter_id).change();
					$('#dd_employer')						.val(data.employer_id).change();
					
					$('#txt_employee_name')					.val(data.employee_name)
					$('#txt_designation')					.val(data.designation)
					$('#txt_nric')							.val(data.nric);
					$('#dd_nationality')					.val(data.nationality_id).change();
					$('#txt_email')							.val(data.email);
					
					$('#dd_sex')							.val(data.sex_id).change();
					$('#dd_marital_status')					.val(data.marital_status_id).change();
					$('#txt_home_address')					.val(data.home_address);
					$('#txt_contact_no')					.val(data.candidate_contact_no);
					$('#dd_client')							.val(data.client_id).change();
					$('#lbl_client_name')					.html(data.client_name);
					
					$('#txt_hiring_manager_name')			.val(data.hiring_manager_name);
					$('#txt_hiring_manager_telno')			.val(data.hiring_manager_telno);
					$('#txt_hiring_manager_email')			.val(data.hiring_manager_email);
					$('#txt_client_invoice_contact_person')	.val(data.client_invoice_contact_person);
					$('#txt_client_invoice_address_to')		.val(data.client_invoice_address_to);	
					$('#dd_employment_type')				.val(data.employment_type_id).change();	
					$('#txt_client_location')				.val(data.client_location);
					$('#start_date')						.val(data.start_date);
   					$('#end_date')							.val(data.end_date);
   					$('#txt_duration')						.val(data.duration);
   					$('#dd_notice_period')					.val(data.notice_period_id).change();
   					$('#txt_billing_amount')				.val(data.billing_amount);
   					$('#txt_billing_amount_with_gst')		.val(data.billing_amount_with_gst);
   					
   					$('#txt_one_time_fees')					.val(data.one_time_fee);
   					$('#txt_fee_descr')						.val(data.desc_one_time_fee);
   					
   					$('#txt_billing_of_month')				.val(data.billing_of_month);
					$('#dd_billing_cycle')					.val(data.billing_cycle_id).change();
					$('#dd_working_days')					.val(data.working_days_id).change();
					$('#txt_overtime_rate')					.val(data.overtime_rate);
					$('#txt_annual_leave')					.val(data.annual_leave);
					$('#txt_medical_leave')					.val(data.medical_leave);
					$('#txt_annual_leave_by_client')		.val(data.annual_leave_by_client);
					$('#txt_medical_leave_by_client')		.val(data.medical_leave_by_client);
					$('#dd_notification_email_to')			.val(data.notification_email_to).change();
					$('#notification_month')				.val(data.notification_month);
					$('#dd_sales_approval')					.val(data.sales_approval).change();
					$('#txt_eis')							.val(data.eis);
					$('#txt_hrdf')							.val(data.hrdf);
					$('#txt_remarks')						.val(data.remarks);
					$('#onboard_date')						.val(data.onboard_date);
					
					$('#txt_salary')						.val(data.salary);		
					$('#txt_daily_salary')					.val(data.daily_salary);
					$('#txt_bonus')							.val(data.bonus);
					$('#txt_epf')							.val(data.epf);	
					$('#txt_socso')							.val(data.socso);
					$('#txt_epcost')						.val(data.epcost);
					$('#txt_dpcost')						.val(data.dpcost);
					$('#txt_overseas_visa_cost')			.val(data.overseas_visa_cost);
					$('#txt_laptop_cost')					.val(data.laptop_cost);
					$('#txt_outpatient_medical_cost')		.val(data.outpatient_medical_cost);
					$('#txt_medical_insurance_cost')		.val(data.medical_insurance_cost);
					$('#txt_temp_accommodation_cost')		.val(data.temp_accommodation_cost);
					$('#txt_mobilization_cost')				.val(data.mobilization_cost);
					$('#txt_flight_ticket_cost')			.val(data.flight_ticket_cost);
					$('#txt_notice_period_buyout')			.val(data.notice_period_buyout);
					$('#txt_other_cost')					.val(data.other_cost);
					$('#txt_other_cost_remarks')			.val(data.other_cost_remarks);
					$('#txt_recruiter_commission')			.val(data.recruiter_commission);
					$('#txt_total_contract_value')			.val(data.total_contract_value);
					$('#dd_status')							.val(data.status_id).change();
					$('#txt_sales_commission')				.val(data.sales_commission);	
					
					$('#chk_ep_required')					.prop('checked', parseInt(data.ep_required)					? true : false);
					$('#chk_overtime_applicable')			.prop('checked', parseInt(data.overtime_applicable)			? true : false);
					$('#chk_client_to_hire_allow')			.prop('checked', parseInt(data.client_to_hire_allow) 		? true : false);
					$('#chk_replacement_leave_applicable')	.prop('checked', parseInt(data.replacement_leave_applicable)? true : false);
					$('#chk_annual_leave_encash_allow')		.prop('checked', parseInt(data.annual_leave_encash_allow)	? true : false);
					$('#chk_is_active')						.prop('checked', parseInt(data.is_active)					? true : false);			
					$('#chk_reference_check')				.prop('checked', parseInt(data.reference_check_applicable)	? true : false);
					$('#chk_travelling_claim')				.prop('checked', parseInt(data.travelling_claim) 			? true : false);
					$('#chk_medical_claim')					.prop('checked', parseInt(data.medical_claim)				? true : false);		

					$('#txt_current_company')				.val(data.current_company_name);
			       	$('#txt_current_ep_expiry_date')		.val(data.current_ep_expiry_date);
			       	$('#txt_apply_ep_on_date')				.val(data.apply_ep_on);
			       	$('#chk_noc')							.prop('checked',parseInt(data.able_to_obtain_noc)			? true : false);
			       	$('#chk_leave_country_required')		.prop('checked',parseInt(data.require_to_exit_country)		? true : false);
			       	$('#dd_billing_type')					.val(data.billing_type).change();
					
					
					if((data.pono).trim() == '')
					{
						$('#dd_pono_status')				.val('no').change();	
						$('#div_pono')						.hide();
					}
					else
					{
						$('#txt_pono')						.val(data.pono);
						$('#dd_pono_status')				.val('yes').change();
						$('#div_pono')						.show();
					}
					
					if(parseInt(SESSIONS_DATA.emp_id) != parseInt(data.created_by))
					{
						$('#s2id_dd_client').hide();
						$('#dd_client').removeAttr('required');
						$('#lbl_client_name').show();
					}
					
					
					$.fn.populate_attachments(return_data.data.attachment);
					$.fn.populate_allowance_list_form(return_data.data.allowance);
					$.fn.populate_dependent_list_form(return_data.data.dependent);
					$.fn.populate_reference_list_form(return_data.data.reference);
					
					
					if(data.issued_offer == 1)
			    	{
			        	 $('#btn_new')		.hide();
			        	 $('#btn_save')		.hide();
			        	 $('#btn_cancel')	.hide();
			        	 $('#btn_sub_save')	.hide();
			        	 $('#btn_sub_reset').hide();
			        	 $('#div_onboard_date').show();
			    	}
					
					if(data.emp_rec_exist == 1)
					{
						$('#btn_onboard_save').hide();
						$('#div_emp_record').html('Employee Record exist for this Contract');
					}
					
					$.fn.set_validation_form();
				}
			},true
		);	
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.populate_attachments = function(data)
{
	$('.attachment_doc').val('').attr('data','');
	$('.doc_link').html('');
	$('.document').html('');
	
	let attach_inputs = $(".attachment_doc");
	loop1:
	for(x = 0; x < attach_inputs.length; x++)
	{
		loop2:
		for(i = 0; i < data.length; i++)
		{
			if("hidden_doc_" + data[i]['doc_type_id'] == attach_inputs[x].id)
			{
				$('#document_link_' + data[i]['doc_type_id']).html($.fn.construct_filepath(data[i].docpath, data[i]['filename']));
				$('#hidden_doc_' + data[i]['doc_type_id']).val(data[i]['filename']).attr("data", JSON.stringify(data[i]));
				break loop2;
			}
			
		}
	}
}

$.fn.construct_filepath = function(path,file)
{
	let return_string = '';
	if(file != "" && file != null)
	{
		return_string = '<a class="btn btn-primary" style="width:100px" href="'+ path +  file + '"  target="_blank">View</a>';
	}
	return return_string;
}


$.fn.set_edit_form = function(data)
{
	FORM_STATE		= 1;
	$('#btn_save')			.html('<i class="fa fa-edit"></i> Update');
};

$.fn.set_sub_edit_form = function(data)
{
	FORM_STATE		= 1;
	$('#btn_sub_save')			.html('<i class="fa fa-edit"></i> Update');
};

$.fn.save_edit_form = function()
{
	try
	{			
		if($('#detail_form').parsley( 'validate' ) == false)
		{
			btn_save.stop();
			return;
		}
						
		var data	= 
		{
			id								: CONTRACT_ID,
			new_contract					: $('#dd_contract_type').val(),
			contract_date					: $('#contract_date').val(),
			
			resume_source					: $('#dd_resume_source').val(),
			referred_by						: $('#txt_referral_name').val(),
			referred_amount					: $('#txt_referral_amount').val(),
        	
			external_recruiter_id			: $('#dd_ext_rec').val(),
			amount_paid_to_external_recruiter: $('#txt_ext_rec_amount').val(),
			external_sales_id				: $('#dd_ext_sales').val(),
			amount_paid_to_external_sales	: $('#txt_ext_sales_amount').val(),
			
			requestor_id					: $('#dd_requestor_name').val(),
			request_date 					: $('#request_date').val(),
			recruiter_id					: $('#dd_recruiter_name').val(),
			employer_id						: $('#dd_employer').val(),		
			employee_name					: $('#txt_employee_name').val(),
			designation						: $('#txt_designation').val(),
			nric							: $('#txt_nric').val(),
			nationality_id					: $('#dd_nationality').val(),
			email							: $('#txt_email').val(),
			sex_id							: $('#dd_sex').val(),
			marital_status_id				: $('#dd_marital_status').val(),
			home_address					: $('#txt_home_address').val(),
			candidate_contact_no			: $('#txt_contact_no').val(),
			client_id						: '',
			client_name						: '',
			hiring_manager_name				: $('#txt_hiring_manager_name').val(),
			hiring_manager_telno			: $('#txt_hiring_manager_telno').val(),
			hiring_manager_email			: $('#txt_hiring_manager_email').val(),
			client_invoice_contact_person	: $('#txt_client_invoice_contact_person').val(),
			client_invoice_address_to		: $('#txt_client_invoice_address_to').val(),
			employment_type_id				: $('#dd_employment_type').val(),
			client_location					: $('#txt_client_location').val(),
			start_date 						: $('#start_date').val(),
	        end_date   						: $('#end_date').val(),
	        duration             			: $('#txt_duration').val(),
	        notice_period_id				: $('#dd_notice_period').val(),	
	        billing_amount     				: $('#txt_billing_amount').val(),
	        billing_amount_with_gst   		: $('#txt_billing_amount_with_gst').val(),
	        
	        one_time_fee     				: $('#txt_one_time_fees').val(),
	        desc_one_time_fee     			: $('#txt_fee_descr').val(),
	        
	        billing_of_month				: $('#txt_billing_of_month').val(),
			billing_cycle_id				: $('#dd_billing_cycle').val(),
			working_days_id					: $('#dd_working_days').val(),
			overtime_rate     				: $('#txt_overtime_rate').val(),
			annual_leave					: $('#txt_annual_leave').val(),
			medical_leave					: $('#txt_medical_leave').val(),
			annual_leave_by_client   		: $('#txt_annual_leave_by_client').val(),
			medical_leave_by_client     	: $('#txt_medical_leave_by_client').val(),
			notification_email_to   		: $('#dd_notification_email_to').val(),
			notification_month				: $('#notification_month').val(),
			sales_approval					: $('#dd_sales_approval').val(),
			eis								: $('#txt_eis').val(),
			hrdf							: $('#txt_hrdf').val(),
			remarks							: $('#txt_remarks').val(),
			
			salary							: $('#txt_salary').val(),	
			daily_salary					: $('#txt_daily_salary').val(),
			bonus							: $('#txt_bonus').val(),
			epf								: $('#txt_epf').val(),		
			socso							: $('#txt_socso').val(),
			epcost							: $('#txt_epcost').val(),			
			dpcost     						: $('#txt_dpcost').val(),
	        overseas_visa_cost   			: $('#txt_overseas_visa_cost').val(),
	        laptop_cost						: $('#txt_laptop_cost').val(),
	        outpatient_medical_cost			: $('#txt_outpatient_medical_cost').val(),
			medical_insurance_cost			: $('#txt_medical_insurance_cost').val(),
			
			temp_accommodation_cost			: $('#txt_temp_accommodation_cost').val(),
			mobilization_cost				: $('#txt_mobilization_cost').val(),
			flight_ticket_cost				: $('#txt_flight_ticket_cost').val(),
			notice_period_buyout			: $('#txt_notice_period_buyout').val(),
			other_cost						: $('#txt_other_cost').val(),
			other_cost_remarks				: $('#txt_other_cost_remarks').val(),
			recruiter_commission			: $('#txt_recruiter_commission').val(),		
			total_contract_value			: $('#txt_total_contract_value').val(),			
			sales_commission     			: $('#txt_sales_commission').val(),
			
			ep_required						: $('#chk_ep_required').is(':checked')					? 1 : 0,
			overtime_applicable				: $('#chk_overtime_applicable').is(':checked')			? 1 : 0,
			client_to_hire_allow			: $('#chk_client_to_hire_allow').is(':checked') 		? 1 : 0,
			replacement_leave_applicable	: $('#chk_replacement_leave_applicable').is(':checked')	? 1 : 0,
			annual_leave_encash_allow		: $('#chk_annual_leave_encash_allow').is(':checked')	? 1 : 0,
			is_active						: $('#chk_is_active').is(':checked') 					? 1 : 0,
			reference_check_applicable		: $('#chk_reference_check').is(':checked') 				? 1 : 0,									
			travelling_claim				: $('#chk_travelling_claim').is(':checked') 			? 1 : 0,
			medical_claim					: $('#chk_medical_claim').is(':checked')				? 1 : 0,		
			pono							: $('#txt_pono').val(),
			
			current_company					: $('#txt_current_company').val(),				
	       	current_ep_expiry_date			: $('#txt_current_ep_expiry_date').val(),
	       	apply_ep_on_date				: $('#txt_apply_ep_on_date').val(),
	       	able_to_obtain_noc				: $('#chk_noc').is(':checked') ? 1 : 0,
	       	require_to_exit_country			: $('#chk_leave_country_required').is(':checked') ? 1 : 0,
	       	billing_type					: $('#dd_billing_type').val(),		
			
			emp_id 							: SESSIONS_DATA.emp_id,
			status_id						: $('#dd_status').val(),
			attachment						: [],
			allowance						: [],
			dependent						: [],
			reference						: []
	 	};
		
		if(Number.isInteger(parseInt(CONTRACT_ID)) == false || (SESSIONS_DATA.emp_id == CREATED_BY))
		{
			data.client_id 	= $('#dd_client').val();
			data.client_name= $('#dd_client option:selected').text();
		}
		else
		{
			data.client_id 	= CLIENT_ID;
			data.client_name= $('#lbl_client_name').html();
		}
				
		let attach_inputs = $(".attachment_doc");
		for(let i = 0; i < attach_inputs.length;i++)
		{
			if($(attach_inputs[i]).val() != '')
			{
				data.attachment.push(JSON.parse($(attach_inputs[i]).attr('data')));
			}
		}
		
		let allowance_inputs = $(".btn_allowance");
		for(let i = 0; i < allowance_inputs.length;i++)
		{
			data.allowance.push(JSON.parse($(allowance_inputs[i]).attr('data')));
		}
		
		let dependent_inputs = $(".btn_dependent");
		for(let i = 0; i < dependent_inputs.length;i++)
		{
			data.dependent.push(JSON.parse($(dependent_inputs[i]).attr('data')));
		}
		
		let reference_inputs = $(".btn_reference");
		for(let i = 0; i < reference_inputs.length;i++)
		{
			data.reference.push(JSON.parse($(reference_inputs[i]).attr('data')));
		}

		$.fn.write_data
		(
			$.fn.generate_parameter('add_edit_contracts', data),	
			function(return_data)
			{
				if(return_data.data)
				{
					$.fn.set_edit_form();
					
					CONTRACT_ID = return_data.data.id;
					$.fn.perform_upload();
					
					$('#h4_primary_no').text('Contract Number : ' + return_data.data.id);
					$.fn.show_right_success_noty('Data has been recorded successfully');
				}
			},false, btn_save
		);

	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.add_attachment_data = function()
{
	try
	{
		var data	= 
		{
			id			: CONTRACT_ID,
			emp_id 		: SESSIONS_DATA.emp_id,
			attachment	: []
		}
		
		let attach_inputs = $(".attachment_doc");
		for(let i = 0; i < attach_inputs.length;i++)
		{
			if($(attach_inputs[i]).val() != '')
			{
				data.attachment.push(JSON.parse($(attach_inputs[i]).attr('data')));
			}
		}
		
		$.fn.write_data
		(
			$.fn.generate_parameter('add_edit_attachments', data),	
			function(return_data)
			{
				if(return_data.data)
				{
					$.fn.perform_upload();
					$.fn.show_right_success_noty('Data has been recorded successfully');
				}
			},false, btn_save
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.perform_upload = function()
{
	$.fn.upload_file(function() 
	{
	});
}

$.fn.create_emp_data = function()
{
	try
	{	
		if($('#onboard_date').val() == '')
		{
			$.fn.show_right_error_noty('Please provide onboard date');
			btn_onboard_save.stop();
			return;
		}
						
		var data	= 
		{
			contract_no 	: CONTRACT_ID,
			onboard_date 	: $('#onboard_date').val(),
			emp_id 			: SESSIONS_DATA.emp_id
	 	};
										
	 	$.fn.write_data
		(
			$.fn.generate_parameter('create_emp_data_from_contract', data),	
			function(return_data)
			{
				if(return_data.data)
				{
					$.fn.show_right_success_noty('Data has been recorded successfully');
					$('#btn_onboard_save').hide();
				}
				
			},false, btn_onboard_save
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.get_list = function(is_scroll)
{
	try
	{
		let status_search = [""];
		if($('#dd_stage_status_search').val() != null)
		{
			status_search = $('#dd_stage_status_search').val();
		}
		
		var data	= 
		{
			candidate_name	: $('#txt_name_search')	.val(),
			status_id 		: $('#dd_status_search').val(),
			client_id 		: $('#dd_client_search').val(),
			created_by 		: $('#dd_created_by_search').val(),
			cont_status 	: status_search.toString(),
			po_no   		: $('#txt_pono_search').val(),	
			view_all		: MODULE_ACCESS.view_it_all, // SESSIONS_DATA.allow_contract_view_all,
			start_index		: RECORD_INDEX,
			limit			: LIST_PAGE_LIMIT,			
			is_admin		: SESSIONS_DATA.is_admin,		
			emp_id			: SESSIONS_DATA.emp_id
	 	};
	 	
	 	if(is_scroll)
	 	{
	 		data.start_index =  RECORD_INDEX;
	 	}
	 	
	 	$.fn.fetch_data_for_table
		(
			$.fn.generate_parameter('get_contract_list',data),
			$.fn.populate_list_form,is_scroll,'tbl_list'
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.populate_list_form = function(data,is_scroll)
{
	try
	{	
		if(is_scroll == false)
		{
			$('#tbl_list > tbody').empty();
		}
		
		if (data.list.length > 0) // check if there is any data, precaution
		{
			let row			= '';
			let data_val 	= '';
			let status		= '';
			if(data.rec_index)
			{
				RECORD_INDEX = data.rec_index;
			}
			data = data.list;
			
			let access_level			= SESSIONS_DATA.access_level;
			let allow_contract_approve	= MODULE_ACCESS.approve_it;
			
			for(var i = 0; i < data.length; i++)
			{
				status = '';
				data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");
				row += '<tr id="TR_ROW_' + i + '"  data-value=\'' + data_val + '\'>' +
							'<td>' + data[i].employee_name	+ '</td>' + 
							'<td>' + data[i].client_name 	+ '</td>' + 
							'<td>' + data[i].created_by 	+ '</td>' + 
							'<td>' + data[i].contract_status 	+ '</td>';
								
				
				if((data[i].pono).trim() == '')
				{
					row += '<td><span class="label label-danger"> PENDING PO No. </span></td>';
					row += '<td></td>';
				}
				else
				{
					if(data[i].approved_sales_head == 0)
					{
						status += '<i class="fa fa-minus-circle text-warning"> Pending(SH)</i><br/>';
					}
					else if(data[i].approved_sales_head == 1)
					{
						status += '<i class="fa fa-check-circle text-success"> Approved(SH)</i><br/>';
					}
					
					if(data[i].approved_hr == 0)
					{
						status += '<i class="fa fa-minus-circle text-warning"> Pending(HR)</i><br/>';
					}
					else if(data[i].approved_hr == 1)
					{
						status += '<i class="fa fa-check-circle text-success"> Approved(HR)</i><br/>';
					}
					
					if(data[i].approved_accounts == 0)
					{
						status += '<i class="fa fa-minus-circle text-warning"> Pending(ACC)</i><br/>';
					}
					else if(data[i].approved_accounts == 1)
					{
						status += '<i class="fa fa-check-circle text-success"> Approved(ACC)</i><br/>';
					}
					
					if(data[i].approved_ceo == 0)
					{
						status += '<i class="fa fa-minus-circle text-warning"> Pending(CEO)</i><br/>';
					}
					else if(data[i].approved_ceo == 1)
					{
						status += '<i class="fa fa-check-circle text-success"> Approved(CEO)</i><br/>';
					}
					
					if(data[i].issued_offer == 0)
					{
						status += '<i class="fa fa-minus-circle text-warning"> Pending Offer Letter(HR)</i><br/>';
					}
					else if(data[i].issued_offer == 1)
					{
						status += '<i class="fa fa-check-circle text-success"> Issued Offer Letter(HR)</i><br/>';
					}
					
					row += '<td>' + status + '</td>';
					
					if(data[i].approved_sales_head == 0)
					{
						if(access_level == 57 || allow_contract_approve == 1)
						{
							row += '<td><input type="checkbox" id="chk_is_approve" name="chk_is_approve" data-value=\'' + data_val + '\' onchange="$.fn.do_approve(unescape($(this).attr(\'data-value\')),$(this).is(\':checked\'),\'SALES_HEAD\')"> Approve</td>';
						}
						else
						{
							row += '<td>-</td>';
						}
						
					}
					else if(data[i].approved_hr == 0)
					{
						if(access_level == 58)
						{
							row += '<td><input type="checkbox" id="chk_is_approve" name="chk_is_approve" data-value=\'' + data_val + '\' onchange="$.fn.do_approve(unescape($(this).attr(\'data-value\')),$(this).is(\':checked\'),\'HR\')"> Approve</td>';
						}
						else if(access_level == 57)
						{
							row += '<td><a  class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.cancel_approve(unescape($(this).attr(\'data-value\')),\'SALES_HEAD\')">Cancel</a></td>';
						}
						else
						{
							row += '<td>-</td>';
						}
						
					}
					else if(data[i].approved_accounts == 0)
					{
						if(access_level == 59)
						{
							row += '<td><input type="checkbox" id="chk_is_approve" name="chk_is_approve" data-value=\'' + data_val + '\' onchange="$.fn.do_approve(unescape($(this).attr(\'data-value\')),$(this).is(\':checked\'),\'ACCOUNTS\')"> Approve</td>';
						}
						else if(access_level == 58)
						{
							row += '<td><a  class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.cancel_approve(unescape($(this).attr(\'data-value\')),\'HR\')">Cancel</a></td>';
						}
						else
						{
							row += '<td>-</td>';
						}
						
					}
					else if(data[i].approved_ceo == 0)
					{
						if(access_level == 60)
						{
							row += '<td><input type="checkbox" id="chk_is_approve" name="chk_is_approve" data-value=\'' + data_val + '\' onchange="$.fn.do_approve(unescape($(this).attr(\'data-value\')),$(this).is(\':checked\'),\'CEO\')"> Approve</td>';
						}
						else if(access_level == 59)
						{
							row += '<td><a  class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.cancel_approve(unescape($(this).attr(\'data-value\')),\'ACCOUNTS\')">Cancel</a></td>';
						}
						else
						{
							row += '<td>-</td>';
						}
						
					}
					else if(data[i].issued_offer == 0)
					{
						if(access_level == 58)
						{
							row += '<td><input type="checkbox" id="chk_is_approve" name="chk_is_approve" data-value=\'' + data_val + '\' onchange="$.fn.do_approve(unescape($(this).attr(\'data-value\')),$(this).is(\':checked\'),\'OFFER_HR\')"> Issued(Offer Letter)</td>';
						}
						else if(access_level == 60)
						{
							row += '<td><a  class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.cancel_approve(unescape($(this).attr(\'data-value\')),\'CEO\')">Cancel</a></td>';
						}
						else
						{
							row += '<td>-</td>';
						}
					}
					else
					{
						if(access_level == 58)
						{
							row += '<td><a  class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.cancel_approve(unescape($(this).attr(\'data-value\')),\'OFFER_HR\')">Cancel(Offer Letter)</a></td>';
						}
						else
						{
							row += '<td>-</td>';
						}
					}

				}
				
					row += '<td width="10%">';
					    row += '<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Summary" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_contract_summary(unescape($(this).attr(\'data-value\')))"><i class="fa fa-list"></i></a>';
					    
					    if(parseInt(MODULE_ACCESS.revoke_approval) == 1)
					    {
					    	row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="right" title="Revoke Approval" href="javascript:void(0)" onclick="$.fn.display_revoke_approval(unescape($(this).closest(\'tr\').attr(\'data-value\')))"><i class="fa fa-undo"></i></a>';
					    }
					    
					    row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Comments" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_remark(unescape($(this).attr(\'data-value\')))"><i class="fa fa-external-link"></i></a>';
						row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Details" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.populate_detail_form(unescape($(this).attr(\'data-value\')), $(this).closest(\'tr\').prop(\'id\') )"><i class="fa fa-sign-in"></i></a>';
					row += '</td>';
				
				row += '</tr>';
							
			}
			$('#tbl_list tbody').append(row);
			$('#div_load_more').show();
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};


$.fn.populate_allowance_list_form = function(data)
{
	try
	{	
		if (data) // check if there is any data, precaution 
		{
			$("#tbl_allowance tbody").find("tr:not('#base_row')").remove();
			
			let row			= '';
			let data_val 	= '';
			for(var i = 0; i < data.length; i++)
			{
				data_val = JSON.stringify(data[i]); //.replace(/'/,"");
					
				row += '<tr>' +
							'<td>' + data[i].allowance_type					+ '</td>' + 
							'<td>' + parseFloat(data[i].amount).toFixed(2) 	+ '</td>' +
							'<td>' +
								'<button type="button" class="btn btn-primary rotate-45 btn_allowance"' +
									'onClick="$.fn.delete_allowance(this);"' +
									' data=\'' + data_val + '\'>' +
									'<i class="fa fa-plus fa-fw" aria-hidden="true"></i>' +
								'</button>' +
	                        '</td>' + 
					  '</tr>';
			}
			$('#base_row').before(row);
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.delete_allowance = function(obj)
{
	try
	{
		let data 			= JSON.parse($(obj).attr('data'));
		data.active_status 	= 0;
		$(obj).attr('data',JSON.stringify(data));
		$(obj).closest('tr').hide('slow');
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.add_allowance = function()
{
	try
	{
		let data = JSON.stringify({"id" : 0, "type_id" : $('#dd_td_allowance').val(),"amount" : parseFloat($('#txt_td_amount').val()).toFixed(2),"active_status" : 1});
		let row = '<tr>' +
						'<td>' + $('#dd_td_allowance option:selected').text()			+ '</td>' + 
						'<td>' + parseFloat($('#txt_td_amount').val()).toFixed(2) 		+ '</td>' +
						'<td>' +
							'<button type="button" class="btn btn-primary rotate-45 btn_allowance"' +
								'onClick="$(this).closest(\'tr\').hide(\'slow\', function(){$(this).closest(\'tr\').remove();});"' +
								' data=\'' + data + '\'>' +
								'<i class="fa fa-plus fa-fw" aria-hidden="true"></i>' +
							'</button>' +
                        '</td>' + 
				  '</tr>';
		
		$.fn.reset_form('allowance_form');
		$('#base_row').before(row);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.populate_dependent_list_form = function(data)
{
	try
	{	
		if (data) // check if there is any data, precaution 
		{
			$("#tbl_dependent tbody").find("tr:not('#base_row_dependent')").remove();
			
			let row			= '';
			let data_val 	= '';
			for(var i = 0; i < data.length; i++)
			{
				data_val = JSON.stringify(data[i]); //.replace(/'/,"");
					
				row += `<tr>
							<td>${data[i].dependent_type}</td>
							<td>${data[i].quantity}</td>
							<td>${parseFloat(data[i].amount).toFixed(2)}</td>
							<td>
								<button type="button" class="btn btn-primary rotate-45 btn_dependent"
									onClick="$.fn.delete_allowance(this);"
									data='${data_val}'>
									<i class="fa fa-plus fa-fw" aria-hidden="true"></i>
								</button>
	                        </td>
					  </tr>`;
			}
			$('#base_row_dependent').before(row);
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.delete_dependent = function(obj)
{
	try
	{
		let data 			= JSON.parse($(obj).attr('data'));
		data.active_status 	= 0;
		$(obj).attr('data',JSON.stringify(data));
		$(obj).closest('tr').hide('slow');
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.add_dependent = function()
{
	try
	{
		let data = JSON.stringify({ "id" 			: 0,
									"type_id" 		: $('#dd_td_dependent').val(),
									"quantity" 		: $('#dd_dp_qty').val(),
									"amount" 		: parseFloat($('#txt_td_dependent_amount').val()).toFixed(2),
									"active_status" : 1});
		
		let row = `<tr>
						<td>${$('#dd_td_dependent option:selected').text()}</td> 
						<td>${$('#dd_dp_qty').val()}</td>
						<td>${parseFloat($('#txt_td_dependent_amount').val()).toFixed(2)}</td>
						<td>
							<button type="button" class="btn btn-primary rotate-45 btn_dependent"
								onClick="$(this).closest('tr').hide('slow', function(){$(this).closest(tr).remove();});"
								 data='${data}'>
								<i class="fa fa-plus fa-fw" aria-hidden="true"></i>
							</button>
                        </td> 
				  </tr>`;
		
		$.fn.reset_form('dependent_form');
		$('#base_row_dependent').before(row);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};


$.fn.populate_reference_list_form = function(data)
{
	try
	{	
		if (data) // check if there is any data, precaution 
		{
			$("#tbl_reference tbody").find("tr:not('#base_row_reference')").remove();
			
			let row			= '';
			let data_val 	= '';
			for(var i = 0; i < data.length; i++)
			{
				data_val = JSON.stringify(data[i]); //.replace(/'/,"");
					
				row += '<tr>' +
							'<td>' + data[i].name							+ '</td>' +
							'<td>' + data[i].contact_no						+ '</td>' +
							'<td>' + data[i].email							+ '</td>' +
							'<td>' + data[i].company_name					+ '</td>' +
							'<td>' + data[i].designation					+ '</td>' +
							'<td>' + data[i].relationship					+ '</td>' +
							'<td><span>' + data[i].remarks.substring(0, 30)	+ '</span>' +
							
							'<br/><a class="tooltips" class="tooltips" data-toggle="tooltip" data-placement="left"' +
							'title="View Remarks" href="javascript:void(0)"' +
							'data=\'' + data_val + '\' onclick="$.fn.view_reference_remark(this)">' +
							'<i class="fa fa-external-link"></i></a>'
							
							+ '</td>' +
							
							'<td>' +
								'<button type="button" class="btn btn-primary rotate-45 btn_reference"' +
									'onClick="$.fn.delete_reference(this);"' +
									' data=\'' + data_val + '\'>' +
									'<i class="fa fa-plus fa-fw" aria-hidden="true"></i>' +
								'</button>' +
	                        '</td>' + 
					  '</tr>';
			}
			$('#base_row_reference').before(row);
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.delete_reference = function(obj)
{
	try
	{
		let data 			= JSON.parse($(obj).attr('data'));
		data.active_status 	= 0;
		$(obj).attr('data',JSON.stringify(data));
		$(obj).closest('tr').hide('slow');
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.view_reference_remark = function(obj)
{
	try
    {
		let data 			= JSON.parse($($(obj)).closest('tr').find('button').attr('data'));
//		let data = JSON.parse($(obj).attr('data'));
		$('#txt_reference_remark')	.val(data.remarks);
//		$('#reference_row_id')		.val($(obj));
		REF_ROW 					= $(obj);
		
		$('#reference_remark_modal').modal();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }

}

$.fn.add_reference = function()
{
	try
	{
		let data = JSON.stringify({"id" 				: 0, 
									"name" 				: $('#txt_td_ref_name').val(),
									"contact_no" 		: $('#txt_td_ref_contact').val(),
									"email" 			: $('#txt_td_ref_email').val(),
									"company_name" 		: $('#txt_td_ref_comp_name').val(),
									"designation" 		: $('#txt_td_ref_desg').val(),
									"relationship_id" 	: $('#dd_td_ref_relation').val(),
									"remarks" 			: $('#txt_td_ref_remarks').val(),
									"remarks_by" 		: SESSIONS_DATA.emp_id,
									"active_status" 	: 1});

		let row = '<tr>' +
						'<td>' + $('#txt_td_ref_name').val()					+ '</td>' +
						'<td>' + $('#txt_td_ref_contact').val()					+ '</td>' +
						'<td>' + $('#txt_td_ref_email').val()					+ '</td>' +
						'<td>' + $('#txt_td_ref_comp_name').val()				+ '</td>' +
						'<td>' + $('#txt_td_ref_desg').val()					+ '</td>' +
						'<td>' + $('#dd_td_ref_relation option:selected').text()+ '</td>' +
						'<td><span>' + $('#txt_td_ref_remarks').val() 			+ '</span>' +			
						
						'<br/><a class="tooltips" class="tooltips" data-toggle="tooltip" data-placement="left"' +
						'title="View Remarks" href="javascript:void(0)"' +
						'data=\'' + data + '\' onclick="$.fn.view_reference_remark(this)">' +
						'<i class="fa fa-external-link"></i></a>'
						
						+ '</td>' +
						
						'<td>' +
							'<button type="button" class="btn btn-primary rotate-45 btn_reference"' +
								'onClick="$(this).closest(\'tr\').hide(\'slow\', function(){$(this).closest(\'tr\').remove();});"' +
								' data=\'' + data + '\'>' +
								'<i class="fa fa-plus fa-fw" aria-hidden="true"></i>' +
							'</button>' +
                        '</td>' + 
				  '</tr>';
		
		$.fn.reset_form('reference_form');
		$('#base_row_reference').before(row);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};



$.fn.show_hide_form = function(form_status)
{
    $.fn.reset_form('form');

    if(form_status == 'NEW')
    {
        $('#list_div')		.hide(400);
        $('#new_div')		.show(400);
        $('#h4_primary_no')	.text('Contract Number : -');
        $('#btn_save')		.html('<i class="fa fa-check"> </i> Save');
        $('#detail_form').parsley().destroy();
		$.fn.init_upload_file();
		
		$.fn.set_validation_form();
    }
    else if(form_status == 'EDIT')
    {
        $('#list_div')		.hide(400);   
        $('#new_div')		.show(400);
        
        $.fn.set_edit_form();
		$.fn.init_upload_file();
		$.fn.reset_form('allowance_form');
		$.fn.reset_form('dependent_form');
		$.fn.reset_form('reference_form');
    }
    else if(form_status == 'BACK')
    {	
        $('#list_div')		.show(400);
        $('#new_div')		.hide(400);
        
    }
	else if(form_status == 'INFO')
    {
        $('#list_div')		.hide(400);
        $('#info_div')		.show(400);
        
    }
	else if(form_status == 'INFO_BACK')
    {
        $('#list_div')		.show(400);
        $('#info_div')		.hide(400);
    }
    
    if(MODULE_ACCESS.create_it == 0)
	{
    	 $('#btn_onboard_save').hide()
    	 $('#btn_new')		.hide();
    	 $('#btn_save')		.hide();
    	 $('#btn_cancel')	.hide();
	}
    else
    {
//    	$('#btn_onboard_save').show()
    	$('#btn_new')		.show();
	   	$('#btn_save')		.show();
	   	$('#btn_cancel')	.show();
    }
};

$.fn.prepare_form = function()
{
    try
    {
        $('#contract_date,#request_date,#start_date,#end_date,#onboard_date,#txt_current_ep_expiry_date,#txt_apply_ep_on_date').datepicker({dateFormat: 'dd-mm-yy'});
        $('.populate').select2();
        $('.tooltips').tooltip();
        
        $.fn.set_validation_form();
        
        if(MODULE_ACCESS.create_it == 0)
    	{
        	 $('#btn_new')		.hide();
        	 $('#btn_save')		.hide();
        	 $('#btn_cancel')	.hide();
    	}
        
        $('#dd_stage_status_search').val(JSON.parse("[174,175,176]")).change();
        
		$.fn.get_list();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.set_validation_form = function()
{
	$('#detail_form').parsley
    ({
        successClass	: 'has-success',
        errorClass		: 'has-error',
        errors			:
        {
            classHandler: function(el)
            {
                return $(el).closest('.form-group');
            },
            errorsWrapper	: '<ul class=\"help-block list-unstyled\"></ul>',
            errorElem		: '<li></li>'
        }
    });
}

$.fn.view_contract_summary = function(data)
{
	try
	{
		data = JSON.parse(data);
		
		let allow_billing;
		let allowance_amount 	= 0;
		let billing_amount 		= 0;
		let dependent_amount 	= 0;
		let allow_div 			= '';
		let billing_div 		= '';
		let dependent_div 		= '';
		let monthly_allowance_amount = 0;
		let monthly_dependent_amount = 0;
		
		
		$.fn.fetch_data
		(
			$.fn.generate_parameter('get_contract_allowance_list',{contract_no : data.contract_no}),	
			function(return_data)
			{
				if(return_data)
				{
					allow_billing = return_data.data;
				}
			},false,false,false,true
		);
		
		for(let i = 0; i < allow_billing.length; i++)
		{
			if(allow_billing[i].category_id == 7)
			{
				allowance_amount += parseFloat(allow_billing[i].amount);
				
				allow_div += '<tr class="tmp">' +
			  					'<td>' + allow_billing[i].allowance_type + '</td>' +
			  					'<td>:</td>' +
			  					'<td>' + parseFloat(allow_billing[i].amount).toFixed(2)  + '</td>' +
			  				'</tr>';
				
			}
			if(allow_billing[i].category_id == 25)
			{
				billing_amount += parseFloat(allow_billing[i].amount);
				
				billing_div += '<tr>' +
								'<td>' + allow_billing[i].allowance_type + '</td>' +
								'<td>:</td>' +
								'<td>' + parseFloat(allow_billing[i].amount).toFixed(2)  + '</td>' +
							'</tr>';
				
			}
		}
		
		$.fn.fetch_data
		(
			$.fn.generate_parameter('get_contract_dependent_list',{contract_no : data.contract_no}),	
			function(return_data)
			{
				if(return_data)
				{
					dependent_list = return_data.data;
				}
			},false,false,false,true
		);
		
		$('.dependent_row').remove();
		
		for(let i = 0; i < dependent_list.length; i++)
		{
			dependent_amount += parseFloat(dependent_list[i].amount);
            
			monthly_dependent_amount += (parseFloat(dependent_list[i].amount)  / parseFloat(data.billing_of_month));
			
			dependent_div += `<tr class="dependent_row">
								<td>${dependent_list[i].dependent_type}</td>
			  					<td>${parseFloat(dependent_list[i].amount).toFixed(2)}</td>
			  					<td>${dependent_list[i].quantity}</td>
			  					<td>${(parseFloat(dependent_list[i].amount)  / parseFloat(data.billing_of_month)).toFixed(2) }</td>
			  				</tr>`;
		}
		
		
		data.duration != null							? data.duration 				= data.duration : data.duration = 0;
		data.billing_amount != null						? data.billing_amount 			= data.billing_amount : data.billing_amount = 0;
		data.billing_amount_with_gst != null			? data.billing_amount_with_gst 	= data.billing_amount_with_gst : data.billing_amount_with_gst = 0;
		data.salary != null								? data.salary 					= data.salary : data.salary = 0;
		data.basic_salary != null						? data.basic_salary 			= data.basic_salary : data.basic_salary = 0;
		data.transport_allowance != null				? data.transport_allowance 		= data.transport_allowance : data.transport_allowance = 0;
		data.handphone_allowance != null				? data.handphone_allowance 		= data.handphone_allowance : data.handphone_allowance = 0;
		data.other_allowance != null					? data.other_allowance 			= data.other_allowance : data.other_allowance = 0;
		data.epf != null								? data.epf 						= data.epf : data.epf = 0;
		data.socso != null								? data.socso 					= data.socso : data.socso = 0;
		data.eis != null								? data.eis 						= data.eis : data.eis = 0;
		data.hrdf != null								? data.hrdf 					= data.hrdf : data.hrdf = 0;
		
		
		data.referred_amount != null					? data.referred_amount 					= parseFloat(data.referred_amount / parseInt(data.duration))  : data.referred_amount = 0;
		data.amount_paid_to_external_recruiter != null	? data.amount_paid_to_external_recruiter= parseFloat(data.amount_paid_to_external_recruiter  / parseInt(data.duration)) : data.amount_paid_to_external_recruiter = 0;
		data.amount_paid_to_external_sales != null		? data.amount_paid_to_external_sales 	= parseFloat(data.amount_paid_to_external_sales / parseInt(data.duration)) : data.amount_paid_to_external_sales = 0;
		
		
		
		data.flight_ticket_cost != null					? data.flight_ticket_cost 		= data.flight_ticket_cost : data.flight_ticket_cost = 0;
		data.temp_accommodation_cost != null			? data.temp_accommodation_cost 	= data.temp_accommodation_cost : data.temp_accommodation_cost = 0;
		data.laptop_cost != null						? data.laptop_cost 				= data.laptop_cost : data.laptop_cost = 0;
		data.notice_period_buyout != null				? data.notice_period_buyout 	= data.notice_period_buyout : data.notice_period_buyout = 0;		
		data.ep_cost != null							? data.ep_cost 					= data.ep_cost : data.ep_cost = 0;
		data.dp_cost != null							? data.dp_cost 					= data.dp_cost : data.dp_cost = 0;
		data.overseas_visa_cost != null					? data.overseas_visa_cost 		= data.overseas_visa_cost : data.overseas_visa_cost = 0;
		data.outpatient_medical_cost != null			? data.outpatient_medical_cost 	= data.outpatient_medical_cost : data.outpatient_medical_cost = 0;
		data.medical_insurance_cost != null				? data.medical_insurance_cost 	= data.medical_insurance_cost : data.medical_insurance_cost = 0;
		
		$('#h4_contract_no')							.html('Contract Number - ' + data.contract_no);
		$('#div_contract_type')							.html(data.contract_type);
		$('#div_contract_date')							.html(data.contract_date);
		$('#div_requestor_name')						.html(data.requestor_name);
		$('#div_employer')								.html(data.employer_name);
		$('#div_employee_name')							.html(data.employee_name);
		$('#div_designation')							.html(data.designation);
		$('#div_nationality')							.html(data.nationality);
		$('#div_email')									.html(data.email);
		$('#div_client')								.html(data.client_name);
		$('#div_hiring_manager_name')					.html(data.hiring_manager_name);
		$('#div_hiring_manager_telno')					.html(data.hiring_manager_telno);
		$('#div_hiring_manager_email')					.html(data.hiring_manager_email);
		$('#div_client_invoice_contact_person')			.html(data.client_invoice_contact_person);
		$('#div_client_invoice_address_to')				.html(data.client_invoice_address_to);	
		$('#div_start_date')							.html(data.start_date);
   		$('#div_end_date')								.html(data.end_date);
		$('#div_duration')								.html(data.duration);
		$('#div_notice_period')							.html(data.notice_period);
		$('#div_billing_amount')						.html(parseFloat(data.billing_amount).toFixed(2));
   		$('#div_billing_amount_with_gst')				.html(parseFloat(data.billing_amount_with_gst).toFixed(2));	
		$('#div_salary')								.html(parseFloat(data.salary).toFixed(2));
		$('#div_daily_salary')							.html(parseFloat(data.daily_salary).toFixed(2));
		
		$('#div_daily_salary1').after(allow_div);

		var total_salary 								= (parseFloat(data.salary) 
														+ parseFloat(allowance_amount)).toFixed(2);
		
		$('#div_total_salary')							.html('<b>' + total_salary + '</b>');
		$('#div_epf')									.html(parseFloat(data.epf).toFixed(2));
		$('#div_socso')									.html(parseFloat(data.socso).toFixed(2));
		$('#div_eis')									.html(parseFloat(data.eis).toFixed(2));
		$('#div_hrdf')									.html(parseFloat(data.hrdf).toFixed(2));
		
		$('#div_referral')								.html(parseFloat(data.referred_amount).toFixed(2) + " (Monthly)");
		$('#div_ext_recruiter')							.html(parseFloat(data.amount_paid_to_external_recruiter).toFixed(2) + " (Monthly)");
		$('#div_ext_sales')								.html(parseFloat(data.amount_paid_to_external_sales).toFixed(2) + " (Monthly)");
		
		$('#div_flight_ticket_cost')					.html(parseFloat(data.flight_ticket_cost).toFixed(2));
		$('#div_temp_accommodation_cost')				.html(parseFloat(data.temp_accommodation_cost).toFixed(2));
		$('#div_laptop_cost')							.html(parseFloat(data.laptop_cost).toFixed(2));
		$('#div_notice_period_buyout')					.html(parseFloat(data.notice_period_buyout).toFixed(2));
		$('#div_epcost')								.html(parseFloat(data.ep_cost).toFixed(2));
		$('#div_dpcost')								.html(parseFloat(data.dp_cost).toFixed(2));
		$('#div_overseas_visa_cost')					.html(parseFloat(data.overseas_visa_cost).toFixed(2));	
		$('#div_outpatient_medical_cost')				.html(parseFloat(data.outpatient_medical_cost).toFixed(2));
		$('#div_medical_insurance_cost')				.html(parseFloat(data.medical_insurance_cost).toFixed(2));
		
		$('#div_flight_ticket_cost_duration')			.html(1);
		$('#div_temp_accommodation_cost_duration')		.html(1);
		$('#div_laptop_cost_duration')					.html(1);
		$('#div_notice_period_buyout_duration')			.html(1);
		$('#div_epcost_duration')						.html(1);
		$('#div_dpcost_duration')						.html(1);
		$('#div_overseas_visa_cost_duration')			.html(1);	
		$('#div_outpatient_medical_cost_duration')		.html(1);
		$('#div_medical_insurance_cost_duration')		.html(1);
		
		var monthly_flight_ticket_cost 					= (parseFloat(data.flight_ticket_cost) 		/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_temp_accommodation_cost 			= (parseFloat(data.temp_accommodation_cost)	/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_laptop_cost 						= (parseFloat(data.laptop_cost)				/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_notice_period_buyout 				= (parseFloat(data.notice_period_buyout)	/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_epcost 								= (parseFloat(data.ep_cost)					/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_dpcost 								= (parseFloat(data.dp_cost)					/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_overseas_visa_cost 					= (parseFloat(data.overseas_visa_cost)		/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_outpatient_medical_cost 			= (parseFloat(data.outpatient_medical_cost)	/ parseFloat(data.billing_of_month)).toFixed(2);
		var monthly_medical_insurance_cost 				= (parseFloat(data.medical_insurance_cost)	/ parseFloat(data.billing_of_month)).toFixed(2);
		
		
		
		
		$('#div_monthly_flight_ticket_cost')			.html(monthly_flight_ticket_cost);
		$('#div_monthly_temp_accommodation_cost')		.html(monthly_temp_accommodation_cost);
		$('#div_monthly_laptop_cost')					.html(monthly_laptop_cost);
		$('#div_monthly_notice_period_buyout')			.html(monthly_notice_period_buyout);
		$('#div_monthly_epcost')						.html(monthly_epcost);
		$('#div_monthly_dpcost')						.html(monthly_dpcost);
		$('#div_monthly_overseas_visa_cost')			.html(monthly_overseas_visa_cost);	
		$('#div_monthly_outpatient_medical_cost')		.html(monthly_outpatient_medical_cost);
		$('#div_monthly_medical_insurance_cost')		.html(monthly_medical_insurance_cost);
		
		$('#tbl_summary_cost_body').append(dependent_div);
		
		var total_cost_to_company 						= (parseFloat(total_salary) 						+ 
														  parseFloat(data.epf)								+ 
														  parseFloat(data.socso)							+ 
														  parseFloat(data.eis)								+
														  parseFloat(data.hrdf)								+
														  parseFloat(data.referred_amount)					+
														  parseFloat(data.amount_paid_to_external_recruiter)+
														  parseFloat(data.amount_paid_to_external_sales)	+
														  parseFloat(monthly_flight_ticket_cost)			+
														  parseFloat(monthly_temp_accommodation_cost) 		+ 
														  parseFloat(monthly_laptop_cost)					+
														  parseFloat(monthly_notice_period_buyout) 			+ 
														  parseFloat(monthly_epcost)						+
														  parseFloat(monthly_dpcost) 						+ 
														  parseFloat(monthly_overseas_visa_cost) 			+
														  parseFloat(monthly_outpatient_medical_cost)		+ 
														  parseFloat(monthly_medical_insurance_cost) 		+ 
														  parseFloat(monthly_dependent_amount)).toFixed(2);
		
		var monthly_gross_profit_margin 				= ((parseFloat(data.billing_amount) + parseFloat(billing_amount)) - parseFloat(total_cost_to_company)).toFixed(2);
		var total_gross_profit							= (parseFloat(monthly_gross_profit_margin) * parseFloat(data.duration)).toFixed(2);
		var gross_profit_percentage 					= ((parseFloat(monthly_gross_profit_margin)* 100) / parseFloat(data.billing_amount)).toFixed(2);
		if(isNaN(gross_profit_percentage))
		{
			gross_profit_percentage = (parseFloat(0)).toFixed(2);
		}
		
		$('#div_total_cost_to_company')					.html('<b>'+total_cost_to_company+'</b>');	
		$('#div_monthly_gross_profit_margin')			.html('<b>'+monthly_gross_profit_margin+'</b>');		
		$('#div_total_gross_profit')					.html('<b>'+total_gross_profit+'</b>');
		$('#div_gross_profit_percentage')				.html('<b>'+gross_profit_percentage+' %</b>');
		
		$('#div_travelling_claim_applicable')			.html(data.travelling_claim_applicable);
		$('#div_medical_claim_applicable')				.html(data.medical_claim_applicable);
		$('#div_overtime_claim_applicable')				.html(data.overtime_applicable);
		$('#div_medical_leave_day_by_client')			.html(data.medical_leave_day_by_client);
		$('#div_annual_leave_day_by_client')			.html(data.annual_leave_day_by_client);
		$('#div_replacement_leave_applicable')			.html(data.replacement_leave_applicable);
		
		var sales_head_status 		= '';
		var hr_status 				= '';
		var finance_status 			= '';
		var ceo_status 				= '';
		var offer_letter_status 	= '';
		(data.approved_sales_head == 1) 				? sales_head_status = '<span class="text-success">Approved</span>' : sales_head_status = '<span class="text-danger">Pending...</span>';
		(data.approved_hr == 1) 						? hr_status = '<span class="text-success">Approved</span>' : hr_status = '<span class="text-danger">Pending...</span>';
		(data.approved_accounts == 1) 					? finance_status = '<span class="text-success">Approved</span>' : finance_status = '<span class="text-danger">Pending...</span>';
		(data.approved_ceo == 1) 						? ceo_status = '<span class="text-success">Approved</span>' : ceo_status = '<span class="text-danger">Pending...</span>';
		(data.issued_offer == 1) 						? offer_letter_status = '<span class="text-success">Issued</span>' : offer_letter_status = '<span class="text-danger">Pending...</span>';
		
		$('#sales_head_status')							.html(sales_head_status);
		$('#hr_status')									.html(hr_status);
		$('#finance_status')							.html(finance_status);
		$('#ceo_status')								.html(ceo_status);
		$('#offer_letter_status')						.html(offer_letter_status);
		

		$.fn.show_hide_form('INFO');
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.do_approve = function(data,check_status,level)
{
    try
    {
		data = JSON.parse(data);
		
		if(check_status == true)
		{
			if(data.issued_offer == 0 && data.approved_ceo == 1)
			{
				$.fn.fetch_data
				(
					$.fn.generate_parameter('offer_letter_exist',{contract_no : data.contract_no}),	
					function(return_data)
					{
						if(return_data.data)
						{
							if(return_data.data == 0)
							{
								$.fn.show_right_error_noty('Please upload offer letter');
								$('#chk_is_approve').prop('checked', false);
							}
							else
							{
								$('#ct_no')				.val(data.contract_no);
								$('#chk_level')       	.val(level);
								$('#remarkModal')   	.modal();
							}
						}
					},true,false,true
				);
			}
			else
			{
				$('#ct_no')				.val(data.contract_no);
				$('#chk_level')       	.val(level);
				$('#remarkModal')   	.modal();
			}
		}
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.display_revoke_approval = function(data)
{
    try
    {
        data = JSON.parse(data);
       
        $('#chk_sales_head_approval')	.prop( "checked", false );
        $('#chk_hr_approval')			.prop( "checked", false );
        $('#chk_accounts_approval')		.prop( "checked", false );
        $('#chk_ceo_approval')			.prop( "checked", false );
        $('#chk_offer_letter_approval')	.prop( "checked", false );
        
        if(data.approved_sales_head == 1)
    	{
        	$('#chk_sales_head_approval').prop( "checked", true );
    	}
        if(data.approved_hr == 1)
    	{
        	$('#chk_hr_approval').prop( "checked", true );
    	}
        if(data.approved_accounts == 1)
    	{
        	$('#chk_accounts_approval').prop( "checked", true );
    	}
        if(data.approved_ceo == 1)
    	{
        	$('#chk_ceo_approval').prop( "checked", true );
    	}
        if(data.issued_offer == 1)
    	{
        	$('#chk_offer_letter_approval').prop( "checked", true );
    	}
        
        $('#revoke_contract_no').val(data.contract_no);
        $('#div_revoke_approval_title').html("Revoke Approval For " + data.employee_name);
		$('#approval_revoke_modal')    .modal();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.revoke_approval = function()
{
	try
	{

		var data	= 
		{
			contract_no		: $('#revoke_contract_no').val(),
			emp_id 			: SESSIONS_DATA.emp_id,
			sh				: $('#chk_sales_head_approval')	.is(':checked') ? 1 : 0,
			hr				: $('#chk_hr_approval')			.is(':checked') ? 1 : 0,
			acc				: $('#chk_accounts_approval')	.is(':checked') ? 1 : 0,
			ceo				: $('#chk_ceo_approval')		.is(':checked') ? 1 : 0,
			io				: $('#chk_offer_letter_approval').is(':checked')? 1 : 0
	 	};
										
	 	$.fn.write_data
		(
			$.fn.generate_parameter('revoke_contract_approval', data),	
			function(return_data)
			{
				if(return_data.data)
				{
					$('#revoke_contract_no').val('');
					RECORD_INDEX = 0;
					$.fn.get_list(false);
				}
				
			},false, btn_save_revoke_approval
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};


$.fn.view_remark = function(data)
{
    try
    {
        data = JSON.parse(data);
        
        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_contract_remark',{contract_no	: data.contract_no}),
            function(return_data)
            {
                if(return_data)
                {
					$.fn.populate_remark_list_form(return_data.data);
                }
            },false,false,false,true
        );
		
		$('#contract_no').val(data.contract_no);
		$('#remarkListModal')   .modal();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.cancel_approve = function(data,level)
{
    try
    {
    	bootbox.prompt("Please enter cancelling remarks", function(result) 
        {
            if (result !== null && result !== '') 
            {
		        data = JSON.parse(data);
				$('#ct_no')				.val(data.contract_no);
				$('#ct_remark')			.val(result);
				$('#chk_level')       	.val(level);
				$.fn.add_edit_remark('cancel');
         	} 
         	else
         	{
         		$.fn.show_right_error_noty('Cancel remarks is mandatory');
         	}
        });
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.add_edit_remark = function(action)
{
    try
    {
       if(action == 'approve' || action == 'cancel')
	   {
    	   let result_ref_exist = '';
    	   if(action == 'approve' && $('#chk_level').val() == 'CEO')
		   {
    		   if(($('#chk_ref_overwrite').length == 0 || $('#chk_ref_overwrite').length == 1) && $('#chk_ref_overwrite').is(':checked') != 1)
    		   {
	    		   $.fn.fetch_data
		   		   (
		   				$.fn.generate_parameter('reference_feedback_exist', {contract_no : $('#ct_no').val()}),
		   				function(return_data)
		   				{
		   					result_ref_exist = return_data.data;
		   	
		   				},false, btn_save_remarks, false, true
		   			);
			   
	    		   if(result_ref_exist == false)
				   {
	    			   let ref_check = '<div class="form-group">' +
	                                   '<label class="checkbox-inline">' +
	                                   '<input type="checkbox" id="chk_ref_overwrite" name="chk_ref_overwrite">No reference check remarks found, it is deemed necessary, Do you want to overwrite it?' +
	                                   '</label></div>';
	    			   
	    			   
	    			   $('#div_overwrite_ref').html(ref_check).show('slow');
	    			   return;
				   }
    		   }
		   }
    	   
		    var remarks = ($('#ct_remark').val()).trim();
			var data	=
			{
				contract_no			: $('#ct_no')			.val(),
				contract_remarks	: remarks,
				action				: action,
				level 				: $('#chk_level')		.val(),
				user_id 			: SESSIONS_DATA.emp_id,
				user_name			: SESSIONS_DATA.name,
				notify_emp_id		: $('#dd_send_to').val()
			};
	   }
	   if(action == '')
	   {
		    var remarks = ($('#contract_remark').val()).trim();
		   	var data	=
			{
				contract_no			: $('#contract_no')			.val(),
				contract_remarks	: remarks,
				action				: '',
				level 				: '',
				user_id				: SESSIONS_DATA.emp_id,
				user_name			: SESSIONS_DATA.name,
				notify_emp_id		: $('#dd_send_to').val()
			};
	   }

	   if(remarks != '')
	   {
			$.fn.write_data
			(
				$.fn.generate_parameter('contract_add_edit_remark', data),
				function(return_data)
				{
					if(return_data.data)
					{   
						$.fn.show_right_success_noty('Data has been recorded successfully');
						if(action == 'approve' || action == 'cancel')
						{
							RECORD_INDEX = 0;
							$.fn.get_list(false);
							$.fn.reset_form('remark_modal');
						}
						if(action == '')
						{
							$.fn.populate_remark_list_form(return_data.data);
							$.fn.reset_form('remark_list_modal');
						}
					}
	
				},false, btn_save_remarks
			);
			
		}
		else
		{
			$.fn.show_right_error_noty('Remarks is mandatory');
			btn_save_remarks.stop();
		}
	    $('#remarkModal').modal('hide')
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.populate_remark_list_form = function(data)
{
	try
	{
		if (data) // check if there is any data, precaution 
		{
			var row			    = '';
			var data_val 	    = '';
			$('#tbl_remark_list tbody').html('');

			for(var i = 0; i < data.length; i++)
			{
				data_val = escape(JSON.stringify(data[i]));

				row += '<tr>'+
							'<td><a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_form(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="Delete data "><i class="fa fa-trash-o"/></a></td>' +
							'<td>' + data[i].contract_remarks  	+ '</td>' +
							'<td>' + data[i].created_by		+ '</td>' +							
							'<td>' + data[i].created_date	+ '</td>' +
							'<td>' + data[i].action			+ '</td>';
				row += '</tr>';

			}
			$('#tbl_remark_list tbody').html(row);
			$('.back-to-top-badge').removeClass('back-to-top-badge-visible');
		}
		else
		{
			$('#tbl_remark_list > tbody').empty();
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.delete_form = function(data)
{
	try
	{
		data = JSON.parse(data);
		if(data.id == '')
		{
			$.fn.show_right_error_noty('Remark ID cannot be empty');
			return;
		}
		
		var data	= 
		{
			id				: data.id,
			contract_no 	: data.contract_no,
			emp_id 			: SESSIONS_DATA.emp_id
	 	};
										
	 	$.fn.write_data
		(
			$.fn.generate_parameter('contract_delete_remark', data),	
			function(return_data)
			{
				if(return_data)
				{
					$('#tbl_remark_list > tbody').empty();
					$.fn.populate_remark_list_form(return_data.data, false);
					$.fn.show_right_success_noty('Data has been deleted successfully');
				}
				
			},false, btn_save
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.form_load = function()
{
    try
    {
        $.fn.prepare_form();
        $.fn.bind_command_events();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.bind_command_events = function()
{
    try
    {
        $('#btn_back, #btn_cancel').click( function(e)
        {
            e.preventDefault();
            $.fn.show_hide_form('BACK');
			RECORD_INDEX = 0;
			$.fn.get_list(false);
        });
		
        $('#btn_load_more').click( function(e)
		{
			e.preventDefault();
			$.fn.get_list(true);
		});
        
		$('#btn_info_back').click( function(e)
        {
            e.preventDefault();
            $('.tmp').remove();
            $.fn.show_hide_form('INFO_BACK');
        });
				
		$('#btn_search').click( function(e)
		{
			e.preventDefault();			
			RECORD_INDEX = 0;
			$.fn.get_list(false);
		});

        $('#btn_new').click( function(e)
        {
            e.preventDefault();
            $.fn.show_hide_form('NEW');
        });
		
		$('#btn_reset').click( function(e)
		{
			e.preventDefault();
			$.fn.reset_form('list');
			RECORD_INDEX = 0;
			$.fn.get_list(false);
		});
				
		$('#btn_save').click( function(e)
		{
			e.preventDefault();
			btn_save = Ladda.create(this);
	 		btn_save.start();
			$.fn.save_edit_form();
			
		});
		
		$('#btn_onboard_save').click( function(e)
		{
			e.preventDefault();
			btn_onboard_save = Ladda.create(this);
			btn_onboard_save.start();
			$.fn.create_emp_data();
		});
		
		$('#dd_pono_status').change( function(e)
		{
			var pono_status = $('#dd_pono_status').val();
			if(pono_status == 'yes')
			{
				$('#div_pono').show();
			}
			if(pono_status == 'no')
			{
				$('#div_pono').hide();
				$('#txt_pono').val('');
			}
		});		

        $('#btn_approve').click( function(e)
        {
            e.preventDefault();
			btn_save_remarks = Ladda.create(this);
	 		btn_save_remarks.start(); 
			$.fn.add_edit_remark("approve");
        });
		
		$('#btn_add_remark').click( function(e)
        {
            e.preventDefault();
			btn_save_remarks = Ladda.create(this);
	 		btn_save_remarks.start(); 
			$.fn.add_edit_remark(""); 
			$('#remarkListModal').modal('hide');
        });
		
		$('#dd_nationality').change(function(e)
		{
			if($(this).val() != 1321)
			{
				$('#txt_eis,#txt_hrdf').val(0);
				$('#txt_eis').attr('disabled','disabled').removeAttr('enabled').removeAttr('onchange');
				$('#txt_hrdf').attr('disabled','disabled').removeAttr('enabled');
			}
			else
			{
				
				$('#txt_eis').attr('enabled','enabled').removeAttr('disabled');
				$('#txt_hrdf').attr('enabled','enabled').removeAttr('disabled');
			}
		});
		
		$('#dd_gst_sst').change( function(e)
		{
			$.fn.fill_amount_with_gst($('#txt_billing_amount').val());
		});
		
		$('#btn_add_allowance').click( function(e)
        {
            e.preventDefault();
	 		$.fn.add_allowance();
        });
		
		$('#btn_add_dependent').click( function(e)
        {
            e.preventDefault();
	 		$.fn.add_dependent();
        });
		
		$('#btn_add_reference').click( function(e)
        {
            e.preventDefault();
	 		$.fn.add_reference();
        });
		
		$('#end_date, #start_date').change( function(e)
		{
			if($('#start_date').val() != '' && $('#end_date').val() != '')
			{
				let start_date = new Date($('#start_date').val().split('-')[2], $('#start_date').val().split('-')[1], $('#start_date').val().split('-')[0]);
				let end_date = new Date(($('#end_date').val().split('-')[2]), $('#end_date').val().split('-')[1], $('#end_date').val().split('-')[0]);
				end_date.setDate(end_date.getDate() + 15);
				$('#txt_duration').val($.fn.month_diff(start_date,end_date));
				$('#txt_billing_of_month').val($('#txt_duration').val());
			}
		});
		
		$('#txt_salary').keypress( function(e)
		{
			$('#txt_daily_salary').val(0);
			
		});
		$( "#txt_salary" ).keyup(function()
		{
			$('#dd_epf_percentage').trigger('change');
		});
		
		$('#txt_daily_salary').keypress( function(e)
		{
			$('#txt_salary').val(0);
		});
		
		
		$('#dd_ext_rec').change(function(e)
		{
			let data = $('option:selected',this).attr('data');
			
			if(data != undefined)
			{
				data = JSON.parse(data);
				$('#txt_ext_rec_amount').val(data.amount);
			}
			
		});
		
		$('#dd_ext_sales').change(function(e)
		{
			let data = $('option:selected',this).attr('data');
			if(data != undefined)
			{
				data = JSON.parse(data);
				$('#txt_ext_sales_amount').val(data.amount);
			}
		});
		
		$('#btn_reference_apply').click( function(e)
        {
            e.preventDefault();
            let data 			= JSON.parse($(REF_ROW).closest('tr').find('button').attr('data'));
    		data.remarks 		= $('#txt_reference_remark').val();
    		$(REF_ROW).closest('tr').find('button').attr('data',JSON.stringify(data));
    		$(REF_ROW).closest('td').find('span').html($('#txt_reference_remark').val().substring(0, 30));
            $('#reference_remark_modal').modal('hide');
        });
		
		$('#dd_epf_percentage').change(function(e)
		{
			if($('#txt_salary').val() != '')
			{
				$('#txt_epf').val(0);
				let ep_cost = parseFloat($('#txt_salary').val()) * parseFloat($('#dd_epf_percentage').val());
				if (!isNaN(ep_cost)) 
				{
					$('#txt_epf').val(ep_cost);
				}
			}
		});
		
		$("#chk_ep_tick").change(function() 
		{
			$('#txt_epcost').val(0);
			if(this.checked) 
		    {
				$.fn.set_default_value('EP','txt_epcost');
		    }
		});
		
		$("#dd_dp_qty").change(function() 
		{
			$("#dd_td_dependent").trigger('change');
		});
		
		$("#dd_td_dependent").change(function() 
		{
//			console.log($("option:selected", this).attr('data-value'));
			
			if($("option:selected", this).attr('data-value') != undefined)
			{
				let duration= parseInt($('#txt_duration').val());
				let data 	= JSON.parse($("option:selected", this).attr('data-value'));
				let cost	= 0;
				let cost_per_annum = parseFloat(data.field1 * 12);
				
				if(duration <= 6)
				{
					cost = parseFloat(data.field2 * duration * parseInt($('#dd_dp_qty').val()));
				}
				else if(duration < 12)
				{
					cost 	= (cost_per_annum * parseInt($('#dd_dp_qty').val()));
				}
				else
				{
					cost 	= (data.field1 * duration * parseInt($('#dd_dp_qty').val()));
				}
				
				$('#txt_td_dependent_amount').val(cost);
			}
		});
		
		$("#chk_om_tick").change(function() 
		{
			$('#txt_outpatient_medical_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('OM','txt_outpatient_medical_cost');
		    }
		});
		
		$("#chk_mi_tick").change(function() 
		{
			$('#txt_medical_insurance_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('MI','txt_medical_insurance_cost');
		    }
		});
		
		$("#chk_laptop_tick").change(function() 
		{
			$('#txt_laptop_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('LP','txt_laptop_cost');
		    }
		});
		
		
		$("#chk_socso_tick").change(function() 
		{
			$('#txt_socso').val(0);
		    if(this.checked) 
		    {
		        $('#txt_socso').val('70.00');
		    }
		});
		
		$("#chk_ta_tick").change(function() 
		{
			$('#txt_temp_accommodation_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('HA','txt_temp_accommodation_cost');
		    }
		});
		
		$("#chk_mobilization_tick").change(function() 
		{
			$('#txt_mobilization_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('TC','txt_mobilization_cost');   
		    }
		});
		
		$("#chk_ft_tick").change(function() 
		{
			$('#txt_flight_ticket_cost').val(0);
		    if(this.checked) 
		    {
		    	$.fn.set_default_value('FT','txt_flight_ticket_cost');
		    }
		});
		
		$("#chk_eis_tick").change(function() 
		{
			$('#txt_eis').val(0);
		    if(this.checked) 
		    {
		        $('#txt_eis').val('50.00');
		    }
		});
		
		$("#chk_hrdf_tick").change(function() 
		{
			$('#txt_hrdf').val(0);
		    if(this.checked) 
		    {
		        $('#txt_hrdf').val(parseFloat($('#txt_salary')) * 0.01);
		    }
		});
		
		$('#btn_revoke_approval').click( function(e)
        {
            e.preventDefault();
			btn_save_revoke_approval = Ladda.create(this);
			btn_save_revoke_approval.start(); 
			$.fn.revoke_approval(); 
			$('#approval_revoke_modal').modal('hide');
        });
		
		$("#chk_sales_head_approval,#chk_hr_approval,#chk_accounts_approval,#chk_ceo_approval,#chk_offer_letter_approval").on("click", function (e) 
		{
		    var checkbox = $(this);
		    if (checkbox.is(":checked")) 
		    {
		        e.preventDefault();
		        return false;
		    }
		});
		
		
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.set_default_value = function(name,txt)
{
	try
	{
		let duration 		= parseInt($('#txt_duration').val());
		let cost			= 0;
		let fixed_cost		= 0;
		let cost_per_month 	= 0;
		
		if(name == 'OM')
		{
			fixed_cost		= 300;
		}
		else if(name == 'MI')
		{
			fixed_cost		= 1200;
		}
		else if(name == 'LP')
		{
			fixed_cost		= 3600;
			
		}
		else if(name == 'EP')
		{
			fixed_cost		= 3600;
		}
		else if(name == 'FT')
		{
			fixed_cost		= 900;
		}
		else if(name == 'HA')
		{
			fixed_cost		= 1000;
		}
		else if(name == 'TC')
		{
			fixed_cost		= 480;
		}
		
		cost_per_month 		= parseFloat(fixed_cost / 12);
		
		if(duration <= 6)
		{
			cost_per_month 		= parseFloat(fixed_cost / 6);
		}

		cost 	= (cost_per_month * duration);
		
		$(`#${txt}`).val(cost);
		
	}
	catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
}


$.fn.init_upload_file = function() 
{

    $.fn.reset_upload_form();
    
	// Documents upload start
	var i = 0;
	$('.doc_upload').each(function(e)
	{  	
		var $docupload = $(this);	
		$docupload.fileupload
		({
			url					: CURRENT_PATH + upload_file_path,
			dataType			: 'json',
			autoUpload			: false,
			acceptFileTypes		: /(\.|\/)(pdf)$/i,
			maxFileSize			: undefined,
			disableImageResize	: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
			previewMaxWidth		: 80,
			previewMaxHeight	: 80,
			previewCrop			: true,
		});	
		
		$docupload.bind('fileuploadsubmit', function (e, data) 
		{	
			let item_data = $('#' + data.fileInput[0].id).parent().parent().find('input:hidden').attr('data');
			
			data.formData 	= 
			{
				upload_path : 'contracts/' + CONTRACT_ID + '/',
				item_data	: item_data
			};
		});
			
		$docupload.bind('fileuploadadd', function (e, data) 
		{				
			$.each(data.fileInput, function (index, input)
			{
				var arr 		= (input.id).split('_');
				var num 		= arr[2];
				var $document 	= $('#document_' + num);
				$.each(data.files, function (index, file) 
				{
					let upload_btn = '';
					
					if($.trim($('#lbl_upload_' + num).text().toLowerCase().replace(/ +/g, "")) == 'offerletter')
					{
						if(SESSIONS_DATA.access_level == 58)
						{
							upload_btn = '<a class="btn btn-primary" style="width:100px" href="#" onclick="$.fn.add_attachment_data()">Upload</a>';
						}
					}

					var $docName = $('<p/>').append($('<span/>').text(file.name));
					$document.html($docName).append(upload_btn);
					$document.data(data);
					$('#hidden_doc_' + num).val(file.name).attr('data',JSON.stringify({'contract_no' : CONTRACT_ID, 'filename' : file.name, 'id' : 0, 'doc_type_id' : num}));
					
				});
			});		
		});
		
		
		$docupload.bind('fileuploadstop', function (e, data) 
		{				
			console.log("all file uploaded");
			
		});
		
		
		i++;
		
	});	
	
	// Documents upload end
	
};

$.fn.reset_upload_form = function()
{	
	var i = 0;
	$('.doc_upload').each(function()
	{
		$('#document_' + i).html('');
		$('#document_link_' + i).html('');
		var $documentupload = $('#doc_upload_' + i);
		$documentupload.unbind('fileuploadadd');
		i++;
	});	
};

$.fn.upload_file  = function(callback)
{
	$('.document').each(function()
	{
		var document_data = $(this).data();

		if (document_data.submit) 
		{
			document_data.submit().success(function(response) 
			{
//				if (callback) callback(response.files[0].name);

				let item_data = JSON.parse(response.files[0].item_data);
				let doc_data =
                {
                    contract_no	: item_data.contract_no,
                    doc_type_id : item_data.doc_type_id,
                    filename 	: response.files[0].name,
                    emp_id		: SESSIONS_DATA.emp_id
                }

            	$.fn.write_data
                (
                    $.fn.generate_parameter('update_attachments_filename', doc_data),
                    function(return_data)
                    {
                        if(return_data.data)
                        {
                        	$.fn.fetch_data
                		    (
                		        $.fn.generate_parameter('get_contract_doc_list',{contract_no : CONTRACT_ID}),
                		        function(return_data)
                		        {
                		            if(return_data)
                		            {
                		            	$.fn.populate_attachments(return_data.data);        		
                		            }
                		        },false,false,false,true
                		    );
                        }
                    }, false,false,true
                );
			});
		}
	});
};


$.fn.fill_amount_with_gst = function(amount)
{
	if(amount != "")
	{
		let tax = $('#dd_gst_sst').val()
		var amount_with_gst = (amount * ((tax / 100) + 1)).toFixed(2);
		$('#txt_billing_amount_with_gst').val(amount_with_gst);	
	}
	else
	{
		$('#txt_billing_amount_with_gst').val(0);	
	}
};

// START of Document initialization
$(document).ready(function()
{
    $.fn.form_load();
});
// END of Document initialization
