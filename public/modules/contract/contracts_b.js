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

    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.set_edit_form = function(data)
{
	FORM_STATE		= 1;
	$('#btn_save')			.html('<i class="fa fa-edit"></i> Update');
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

$.fn.perform_upload = function()
{
	$.fn.upload_file(function() 
	{
	});
}

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
		
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


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

// START of Document initialization
$(document).ready(function()
{
    $.fn.form_load();
});
// END of Document initialization
