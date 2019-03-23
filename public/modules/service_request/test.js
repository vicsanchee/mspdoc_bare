var FORM_STATE 		= 0;
var RECORD_INDEX 	= 0;
var SESSIONS_DATA	= '';
var btn_save,btn_save_remarks,btn_verify_approve;
var total_selected_files    = 0
var SERVICE_NO		        = '';
var SERVICE_DATA            = '';
var CURRENT_PATH	        = 	'../../';

//START - Category of Request for Service
RAISE_INVOICE               = '218';
PAYMENT                     = '219';
LOAN                        = '220';
ASSET                       = '221';
//END - Category of Request for Service

//START - Status of Request for Service
STATUS_DRAFT                = '222';
SEND_VERIFY_STATUS          = '223';
COMPLETE_STATUS             = '224';
//END - Status of Request for Service



$.fn.data_table_features = function()
{
    try
    {
        if (!$.fn.dataTable.isDataTable( '#tbl_list' ))
        {
            table = $('#tbl_list').DataTable( {
                "searching": false,
                "paging": false,
                "info":     false,
                "order": [[ 0, "desc" ]]
            } );
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


$.fn.get_list = function(is_scroll)
{
    try
    {
        var data	=
            {
                category_id         : $('#dd_category_search').val(),
                created_by 		    : $('#dd_created_by_search').val(),
                company_id		    : $('#dd_company_search').val(),
                status_id	        : $('#dd_status_search').val(),
                complete_status_id  : COMPLETE_STATUS,
                view_all		    : MODULE_ACCESS.view_it_all,
                start_index		    : RECORD_INDEX,
                limit			    : LIST_PAGE_LIMIT,
                is_admin		    : SESSIONS_DATA.is_admin,
                emp_id			    : SESSIONS_DATA.emp_id
            };

        if(is_scroll)
        {
            data.start_index =  RECORD_INDEX;
        }

        $.fn.fetch_data_for_table
        (
            $.fn.generate_parameter('get_service_request_list',data),
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
            if(data.rec_index)
            {
                RECORD_INDEX = data.rec_index;
            }
            data = data.list;

            for(var i = 0; i < data.length; i++)
            {
                data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");

                row += '</tr>';
                row += '<tr id="TR_ROW_' + i + '"  data-value=\'' + data_val + '\'>' +
                    '<td>' + data[i].service_no	+ '</td>' +
                    '<td>' + data[i].category_name 	+ '</td>' +
                    '<td>' + data[i].created_by 	+ '</td>' +
                    '<td>' + data[i].created_date 	+ '</td>' +
                    '<td>' + data[i].status_name 	+ '</td>';

                row += '<td width="10%">';
                row += '<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Comments" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_remark(unescape($(this).attr(\'data-value\')))"><i class="fa fa-external-link"></i></a>';
                if(MODULE_ACCESS.edit_it == 1)
                {
                    row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Details" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.populate_detail_form(unescape($(this).attr(\'data-value\')))"><i class="fa fa-sign-in"></i></a>';

                }
                if(MODULE_ACCESS.delete_it == 1)
                {
                    row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Details" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_service_request(unescape($(this).attr(\'data-value\')))"><i class="fa fa-trash-o"/></a>';
                }

                var status = '';
                var verify = '';
                var approve = '';
                var json_approval 	= JSON.parse(data[i].sr_data).approval_verify;

                if(json_approval)
                {
                    if(json_approval.verify.verified == 1)
                    {
                        status = '<i class="fa fa-pencil-square-o" aria-hidden="true">Verified</i><br/>';
                    }
                    else
                    {
                        if(MODULE_ACCESS.verify_it == 1 && JSON.parse(data[i].sr_data).status_id == SEND_VERIFY_STATUS)
                        {
                            verify = '<button type="button" class="btn btn-info-alt btn-sm btn-label ladda-button tooltips" data-toggle="tooltip" data-placement="left" data-style="expand-left" data-spinner-color="#000000" title="Verify" onclick="$.fn.verify_approval(unescape( $(this).closest(\'tr\').data(\'value\')),this,1)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="hidden-xs">Verify</span></button>';
                        }
                    }
                    if(json_approval.approve.approved == 1)
                    {
                        status += '<i class="fa fa-check-square-o" aria-hidden="true">Approved</i>';
                    }
                    else
                    {
                        if(MODULE_ACCESS.approve_it == 1)
                        {
                            approve = '<button type="button" class="btn btn-success-alt btn-sm btn-label ladda-button tooltips" data-toggle="tooltip" data-placement="left" data-style="expand-left" data-spinner-color="#000000" title="Approve" onclick="$.fn.verify_approval(unescape( $(this).closest(\'tr\').data(\'value\')),this,2)"><i class="fa fa-check-square-o" aria-hidden="true"></i><span class="hidden-xs">Approve</span></button>';
                        }
                    }
                }
                else
                {
                    if(MODULE_ACCESS.verify_it == 1)
                    {
                        if(JSON.parse(data[i].sr_data).status_id == SEND_VERIFY_STATUS)
                        {
                            verify = '<button type="button" class="btn btn-info-alt btn-sm btn-label ladda-button tooltips" data-toggle="tooltip" data-placement="left" data-style="expand-left" data-spinner-color="#000000" title="Verify" onclick="$.fn.verify_approval(unescape( $(this).closest(\'tr\').data(\'value\')),this,1)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i><span class="hidden-xs">Verify</span></button>';
                        }
                    }
                    else if(MODULE_ACCESS.approve_it == 1)
                    {
                        approve = '<button type="button" class="btn btn-success-alt btn-sm btn-label ladda-button tooltips" data-toggle="tooltip" data-placement="left" data-style="expand-left" data-spinner-color="#000000" title="Approve" onclick="$.fn.verify_approval(unescape( $(this).closest(\'tr\').data(\'value\')),this,2)"><i class="fa fa-check-square-o" aria-hidden="true"></i><span class="hidden-xs">Approve</span></button>';
                    }
                }

                row += '<br>'+status + verify + approve;
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


$.fn.save_edit_form = function()
{
    try
    {
        if(SERVICE_NO == '') {
            SERVICE_DATA = {
                category_id: "",
                employer_id: "",
                description: "",
                unit_price: "",
                qty: "",
                amount: "",
                sst: "",
                total_amount: "",
                bank_account_details: "",
                date_required: "",
                status_id: "",
                raise_invoice: {
                    client_id: "",
                    contact_person: "",
                    payment_terms: ""
                },
                payment: {
                    payable_to: ""
                },
                advance_loan: {
                    number_of_repayment: "",
                    each_repayment_amount: "",
                    advance_or_loan: "",
                    current_balance_advance_or_loan: ""
                },
                assets: {
                    type_of_assets: "",
                    duration: "",
                    remarks: ""
                },
                attachment: null,
                approval_verify: null,
                remark_list: null
            };
        }

        var attachment = [];

        SERVICE_DATA.category_id = $('#dd_category').val();
        SERVICE_DATA.employer_id = $('#dd_company').val();

        if(SERVICE_DATA.category_id == RAISE_INVOICE) {
            if($('#invoice_form').parsley( 'validate' ) == false)
            {
                btn_save.stop();
                return;
            }

            $('#document_invoice .file-upload.new').each(function(index) {
                attachment.push($(this)[0].innerText.trim());
            });

            SERVICE_DATA.description                    = $('#txt_invoice_description')	    .val();
            SERVICE_DATA.unit_price                     = $('#txt_invoice_unit_price')	    .val();
            SERVICE_DATA.qty                            = $('#txt_invoice_quantity')	    .val();
            SERVICE_DATA.amount                         = $('#txt_invoice_amount')	        .val();
            SERVICE_DATA.sst                            = $('#dd_invoice_gst_sst')		    .val();
            SERVICE_DATA.total_amount                   = $('#txt_invoice_total_amount')	.val();
            SERVICE_DATA.bank_account_details           = $('#txt_invoice_bank_details')	.val();
            SERVICE_DATA.status_id                      = $('#dd_invoice_status')			.val();
            SERVICE_DATA.raise_invoice.client_id        = $('#dd_invoice_client')           .val();
            SERVICE_DATA.raise_invoice.contact_person   = $('#txt_invoice_contact_person')  .val();
            SERVICE_DATA.raise_invoice.payment_terms    = $('#dd_invoice_payment_terms')    .val();
            SERVICE_DATA.attachment                     = attachment;
        }
        else if(SERVICE_DATA.category_id == PAYMENT) {
            if($('#payment_form').parsley( 'validate' ) == false)
            {
                btn_save.stop();
                return;
            }

            $('#document_payment .file-upload.new').each(function(index) {
                attachment.push($(this)[0].innerText.trim());
            });

            SERVICE_DATA.description                    = $('#txt_payment_description')	    .val();
            SERVICE_DATA.unit_price                     = $('#txt_payment_unit_price')	    .val();
            SERVICE_DATA.qty                            = $('#txt_payment_quantity')	    .val();
            SERVICE_DATA.amount                         = $('#txt_payment_amount')	        .val();
            SERVICE_DATA.sst                            = $('#dd_payment_gst_sst')		    .val();
            SERVICE_DATA.total_amount                   = $('#txt_payment_total_amount')	.val();
            SERVICE_DATA.bank_account_details           = $('#txt_payment_bank_details')	.val();
            SERVICE_DATA.status_id                      = $('#dd_payment_status')			.val();
            SERVICE_DATA.payment.payable_to             = $('#txt_payment_payable_to')      .val();
            SERVICE_DATA.attachment                     = attachment;;
        }
        else if(SERVICE_DATA.category_id == LOAN) {
            if($('#loan_form').parsley( 'validate' ) == false)
            {
                btn_save.stop();
                return;
            }

            $('#document_loan .file-upload.new').each(function(index) {
                attachment.push($(this)[0].innerText.trim());
            });

            SERVICE_DATA.description                    = $('#txt_loan_description')	    .val();
            SERVICE_DATA.amount                         = $('#txt_loan_amount')	            .val();
            SERVICE_DATA.date_required                  = $('#date_loan_required')			.val();
            SERVICE_DATA.status_id                      = $('#dd_loan_status')			    .val();
            SERVICE_DATA.advance_loan.number_of_repayment               = $('#txt_loan_repayment_number')   .val();
            SERVICE_DATA.advance_loan.each_repayment_amount             = $('#txt_loan_repayment_amount')   .val();
            SERVICE_DATA.advance_loan.advance_or_loan                   = $('#txt_loan_advance')            .val();
            SERVICE_DATA.advance_loan.current_balance_advance_or_loan   = $('#txt_loan_balance')            .val();
            SERVICE_DATA.attachment                     = attachment;
        }
        else if(SERVICE_DATA.category_id == ASSET) {
            if($('#asset_form').parsley( 'validate' ) == false)
            {
                btn_save.stop();
                return;
            }

            $('#document_asset .file-upload.new').each(function(index) {
                attachment.push($(this)[0].innerText.trim());
            });

            SERVICE_DATA.description                    = $('#txt_asset_description')	    .val();
            SERVICE_DATA.date_required                  = $('#date_asset_needed')			.val();
            SERVICE_DATA.status_id                      = $('#dd_asset_status')			    .val();
            SERVICE_DATA.assets.type_of_assets          = $('#dd_asset_type')               .val();
            SERVICE_DATA.assets.duration                = $('#txt_asset_duration')          .val();
            SERVICE_DATA.assets.remarks                 = $('#txt_asset_remarks')           .val();
            SERVICE_DATA.attachment                     = attachment;
        }


        var data	=
            {
                service_no		        : SERVICE_NO,
                service_data            : SERVICE_DATA,
                emp_id 				    : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_service_request', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    $.fn.set_edit_form();

                    SERVICE_NO = return_data.data.service_no;
                    if(total_selected_files > 0)
                    {
                        $.fn.upload_file(return_data.data);
                    }
                    else
                    {
                        if(return_data.data.service_data.status_id == SEND_VERIFY_STATUS)
                        {
                            $.fn.send_email_verifier_approver_service_request(return_data.data,0);
                        }
                    }

                    $('#h4_primary_no').text('Service Number : ' + return_data.data.service_no);
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

$.fn.set_edit_form = function(data)
{
    FORM_STATE		= 1;
    $('#btn_save')			.html('<i class="fa fa-edit"></i> Update');
};


$.fn.populate_detail_form = function(data)
{
    try
    {
        var data 	= JSON.parse(data);
        $.fn.show_hide_form	('EDIT');
        $('#h4_primary_no')		.text('Document Number : ' + data.service_no);

        /*SERVICE_NO		        = data.service_no;
        SERVICE_DATA                = JSON.parse(data.sr_data);

        $('#dd_category')       .val(SERVICE_DATA.category_id).change();
        $('#dd_company')        .val(SERVICE_DATA.employer_id).change();

        if(SERVICE_DATA.category_id == RAISE_INVOICE) {
            $('#txt_invoice_description')	    .val(SERVICE_DATA.description);
            $('#txt_invoice_unit_price')	    .val(SERVICE_DATA.unit_price);
            $('#txt_invoice_quantity')	        .val(SERVICE_DATA.qty);
            $('#txt_invoice_amount')	        .val(SERVICE_DATA.amount);
            $('#dd_invoice_gst_sst')		    .val(SERVICE_DATA.sst).change();
            $('#txt_invoice_total_amount')	    .val(SERVICE_DATA.total_amount);
            $('#txt_invoice_bank_details')	    .val(SERVICE_DATA.bank_account_details);
            $('#dd_invoice_client')             .val(SERVICE_DATA.raise_invoice.client_id).change();
            $('#txt_invoice_contact_person')    .val(SERVICE_DATA.raise_invoice.contact_person);
            $('#dd_invoice_payment_terms')      .val(SERVICE_DATA.raise_invoice.payment_terms).change();
            if(data.status_id == COMPLETE_STATUS)
            {
                $('#dd_invoice_status')         .attr('disabled','disabled');
                $('#dd_invoice_status')         .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
            }
            $('#dd_invoice_status')			    .val(SERVICE_DATA.status_id).change();
        }
        else if(SERVICE_DATA.category_id == PAYMENT) {
            $('#txt_payment_description')	    .val(SERVICE_DATA.description);
            $('#txt_payment_unit_price')	    .val(SERVICE_DATA.unit_price);
            $('#txt_payment_quantity')	        .val(SERVICE_DATA.qty);
            $('#txt_payment_amount')	        .val(SERVICE_DATA.amount);
            $('#dd_payment_gst_sst')		    .val(SERVICE_DATA.sst).change();
            $('#txt_payment_total_amount')	    .val(SERVICE_DATA.total_amount);
            $('#txt_payment_bank_details')	    .val(SERVICE_DATA.bank_account_details);
            $('#txt_payment_payable_to')        .val(SERVICE_DATA.payment.payable_to);
            if(data.status_id == COMPLETE_STATUS)
            {
                $('#dd_payment_status')         .attr('disabled','disabled');
                $('#dd_payment_status')         .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
            }
            $('#dd_payment_status')			    .val(SERVICE_DATA.status_id).change();
        }
        else if(SERVICE_DATA.category_id == LOAN) {
            $('#txt_loan_description')	        .val(SERVICE_DATA.description);
            $('#txt_loan_amount')	            .val(SERVICE_DATA.amount);
            $('#date_loan_required')			.val(SERVICE_DATA.date_required);
            $('#txt_loan_repayment_number')     .val(SERVICE_DATA.advance_loan.number_of_repayment );
            $('#txt_loan_repayment_amount')     .val(SERVICE_DATA.advance_loan.each_repayment_amount);
            $('#txt_loan_advance')              .val(SERVICE_DATA.advance_loan.advance_or_loan);
            $('#txt_loan_balance')              .val(SERVICE_DATA.advance_loan.current_balance_advance_or_loan);
            if(data.status_id == COMPLETE_STATUS)
            {
                $('#dd_loan_status')            .attr('disabled','disabled');
                $('#dd_loan_status')            .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
            }
            $('#dd_loan_status')			    .val(SERVICE_DATA.status_id).change();
        }
        else if(SERVICE_DATA.category_id == ASSET) {
            $('#txt_asset_description')	        .val(SERVICE_DATA.description);
            $('#date_asset_needed')			    .val(SERVICE_DATA.date_required);
            $('#dd_asset_type')                 .val(SERVICE_DATA.assets.type_of_assets).change();
            $('#txt_asset_duration')            .val(SERVICE_DATA.assets.duration  );
            $('#txt_asset_remarks')             .val(SERVICE_DATA.assets.remarks );
            if(data.status_id == COMPLETE_STATUS)
            {
                $('#dd_asset_status')           .attr('disabled','disabled');
                $('#dd_asset_status')           .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
            }
            $('#dd_asset_status')			    .val(SERVICE_DATA.status_id).change();
        }*/

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_service_request_details',{service_no : data.service_no}),
            function(return_data)
            {
                if(return_data.data.details)
                {
                    var data 					    = return_data.data.details;

                    SERVICE_NO		        = data.service_no;
                    SERVICE_DATA            = JSON.parse(data.sr_data);

                    $('#dd_category')       .val(SERVICE_DATA.category_id).change();
                    $('#dd_company')        .val(SERVICE_DATA.employer_id).change();

                    if(SERVICE_DATA.category_id == RAISE_INVOICE) {
                        $('#txt_invoice_description')	    .val(SERVICE_DATA.description);
                        $('#txt_invoice_unit_price')	    .val(SERVICE_DATA.unit_price);
                        $('#txt_invoice_quantity')	        .val(SERVICE_DATA.qty);
                        $('#txt_invoice_amount')	        .val(SERVICE_DATA.amount);
                        $('#dd_invoice_gst_sst')		    .val(SERVICE_DATA.sst).change();
                        $('#txt_invoice_total_amount')	    .val(SERVICE_DATA.total_amount);
                        $('#txt_invoice_bank_details')	    .val(SERVICE_DATA.bank_account_details);
                        $('#dd_invoice_client')             .val(SERVICE_DATA.raise_invoice.client_id).change();
                        $('#txt_invoice_contact_person')    .val(SERVICE_DATA.raise_invoice.contact_person);
                        $('#dd_invoice_payment_terms')      .val(SERVICE_DATA.raise_invoice.payment_terms).change();
                        if(SERVICE_DATA.status_id == COMPLETE_STATUS)
                        {
                            $('#dd_invoice_status')         .attr('disabled','disabled');
                            $('#dd_invoice_status')         .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
                        }
                        $('#dd_invoice_status')			    .val(SERVICE_DATA.status_id).change();

                        var attachment = SERVICE_DATA.attachment;
                        for(var i = 0; i < attachment.length; i++)
                        {
                            $('#document_invoice').append('<div class="file-upload new"><span class=""><a href="'+data.filepath+attachment[i]+'" target="_blank">'+attachment[i]+'</a></span></div>');
                        }
                    }
                    else if(SERVICE_DATA.category_id == PAYMENT) {
                        $('#txt_payment_description')	    .val(SERVICE_DATA.description);
                        $('#txt_payment_unit_price')	    .val(SERVICE_DATA.unit_price);
                        $('#txt_payment_quantity')	        .val(SERVICE_DATA.qty);
                        $('#txt_payment_amount')	        .val(SERVICE_DATA.amount);
                        $('#dd_payment_gst_sst')		    .val(SERVICE_DATA.sst).change();
                        $('#txt_payment_total_amount')	    .val(SERVICE_DATA.total_amount);
                        $('#txt_payment_bank_details')	    .val(SERVICE_DATA.bank_account_details);
                        $('#txt_payment_payable_to')        .val(SERVICE_DATA.payment.payable_to);
                        if(SERVICE_DATA.status_id == COMPLETE_STATUS)
                        {
                            $('#dd_payment_status')         .attr('disabled','disabled');
                            $('#dd_payment_status')         .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
                        }
                        $('#dd_payment_status')			    .val(SERVICE_DATA.status_id).change();

                        var attachment = SERVICE_DATA.attachment;
                        for(var i = 0; i < attachment.length; i++)
                        {
                            $('#document_payment').append('<div class="file-upload new"><span class=""><a href="'+data.filepath+attachment[i]+'" target="_blank">'+attachment[i]+'</a></span></div>');
                        }
                    }
                    else if(SERVICE_DATA.category_id == LOAN) {
                        $('#txt_loan_description')	        .val(SERVICE_DATA.description);
                        $('#txt_loan_amount')	            .val(SERVICE_DATA.amount);
                        $('#date_loan_required')			.val(SERVICE_DATA.date_required);
                        $('#txt_loan_repayment_number')     .val(SERVICE_DATA.advance_loan.number_of_repayment );
                        $('#txt_loan_repayment_amount')     .val(SERVICE_DATA.advance_loan.each_repayment_amount);
                        $('#txt_loan_advance')              .val(SERVICE_DATA.advance_loan.advance_or_loan);
                        $('#txt_loan_balance')              .val(SERVICE_DATA.advance_loan.current_balance_advance_or_loan);
                        if(SERVICE_DATA.status_id == COMPLETE_STATUS)
                        {
                            $('#dd_loan_status')            .attr('disabled','disabled');
                            $('#dd_loan_status')            .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
                        }
                        $('#dd_loan_status')			    .val(SERVICE_DATA.status_id).change();

                        var attachment = SERVICE_DATA.attachment;
                        for(var i = 0; i < attachment.length; i++)
                        {
                            $('#document_loan').append('<div class="file-upload new"><span class=""><a href="'+data.filepath+attachment[i]+'" target="_blank">'+attachment[i]+'</a></span></div>');
                        }
                    }
                    else if(SERVICE_DATA.category_id == ASSET) {
                        $('#txt_asset_description')	        .val(SERVICE_DATA.description);
                        $('#date_asset_needed')			    .val(SERVICE_DATA.date_required);
                        $('#dd_asset_type')                 .val(SERVICE_DATA.assets.type_of_assets).change();
                        $('#txt_asset_duration')            .val(SERVICE_DATA.assets.duration  );
                        $('#txt_asset_remarks')             .val(SERVICE_DATA.assets.remarks );
                        if(SERVICE_DATA.status_id == COMPLETE_STATUS)
                        {
                            $('#dd_asset_status')           .attr('disabled','disabled');
                            $('#dd_asset_status')           .append('<option value="'+SERVICE_DATA.status_id+'">'+data.status_name+'</option>');
                        }
                        $('#dd_asset_status')			    .val(SERVICE_DATA.status_id).change();

                        var attachment = SERVICE_DATA.attachment;
                        for(var i = 0; i < attachment.length; i++)
                        {
                            $('#document_asset').append('<div class="file-upload new"><span class=""><a href="'+data.filepath+attachment[i]+'" target="_blank">'+attachment[i]+'</a></span></div>');
                        }
                    }
                }
            },true
        );
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.delete_service_request = function(data)
{
    try
    {
        var data 	= JSON.parse(data);
        bootbox.confirm
        ({
            title: "Delete Confirmation",
            message: "Please confirm before you delete.",
            buttons:
                {
                    cancel:
                        {
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                    confirm:
                        {
                            label: '<i class="fa fa-check"></i> Confirm'
                        }
                },
            callback: function (result)
            {
                if (result == true)
                {
                    var data_delete	=
                        {
                            service_no          : data.service_no,
                            emp_id              : SESSIONS_DATA.emp_id
                        };
                    $.fn.write_data
                    (
                        $.fn.generate_parameter('delete_service_request', data_delete),
                        function(return_data)
                        {
                            if(return_data)
                            {
                                RECORD_INDEX = 0;
                                $.fn.get_list(false);
                                $.fn.show_right_success_noty('Data has been deleted successfully');
                            }

                        },false
                    );
                }
            }
        });
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
        var data 	    = JSON.parse(data);
        var sr_data     = JSON.parse(data.sr_data);
        var remarks     = sr_data.remark_list;

        if (remarks) // check if there is any data, precaution
        {
            var row			    = '';
            var data_val 	    = '';
            $('#tbl_remark_list tbody').html('');

            for(var i = 0; i < remarks.length; i++)
            {
                data.delete_data = [];
                data.delete_data.push(JSON.stringify(remarks[i]));
                data_val        = escape(JSON.stringify(data));

                row += '<tr>'+
                    '<td><a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_remark(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="Delete data "><i class="fa fa-trash-o"/></a></td>' +
                    '<td>' + remarks[i].remarks  	    + '</td>' +
                    '<td>' + remarks[i].created_by		+ '</td>' +
                    '<td>' + remarks[i].created_date	+ '</td>';
                row += '</tr>';

            }
            $('#tbl_remark_list tbody').html(row);
            $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
        }
        else
        {
            $('#tbl_remark_list > tbody').empty();
        }

        $('#service_request').attr('data-value',JSON.stringify(data));
        $('#remarkListModal')   .modal();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.add_edit_remark = function()
{
    try
    {
        if($('#remark_form').parsley( 'validate' ) == false)
        {
            btn_save_remarks.stop();
            return;
        }
        var data        = JSON.parse($('#service_request').attr('data-value'));
        var sr_data     = JSON.parse(data.sr_data);
        var remarks     = sr_data.remark_list;

        var remark_rec =
            {
                remarks         : ($('#service_remark').val()).trim(),
                created_by      : SESSIONS_DATA.name,
                created_date    : moment().format('YYYY-MM-DD HH:mm:ss')
            };

        if(remarks)
        {
            remarks.push(remark_rec);
        }
        else
        {
            remarks = [];
            remarks.push(remark_rec);
        }

        sr_data.remark_list = remarks;

        var data_service	=
            {
                service_no      : data.service_no,
                service_data	: sr_data,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_service_request', data_service),
            function(return_data)
            {
                if(return_data.data)
                {
                    RECORD_INDEX = 0;
                    $.fn.get_list(false);
                    $.fn.reset_form('remark_list_modal');
                    $.fn.show_right_success_noty('Data has been recorded successfully');
                }

            },false, btn_save_remarks
        );

        $('#remarkListModal').modal('hide');
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.delete_remark = function(data)
{
    try
    {
        var data            = JSON.parse(data);
        var sr_data         = JSON.parse(data.sr_data);
        var delete_data     = JSON.parse(data.delete_data);

        var remarks         = sr_data.remark_list;
        sr_data.remark_list = remarks.filter(obj => obj.remarks != delete_data.remarks);

        var data_service	=
            {
                service_no      : data.service_no,
                service_data	: sr_data,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_service_request', data_service),
            function(return_data)
            {
                if(return_data.data)
                {
                    RECORD_INDEX = 0;
                    $.fn.get_list(false);
                    $.fn.reset_form('remark_list_modal');
                    $.fn.show_right_success_noty('Data has been deleted successfully');
                }

            },false
        );

        $('#remarkListModal').modal('hide');
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$.fn.verify_approval = function(data,event,status)
{
    try
    {
        btn_verify_approve = Ladda.create(event);
        btn_verify_approve.start();

        var data 	    = JSON.parse(data);
        var sr_data     = JSON.parse(data.sr_data);

        var approvals =
            {
                verify  : {
                    verified        : null,
                    verified_by     : null,
                    verified_date   : null
                },
                approve : {
                    approved        : null,
                    approved_by     : null,
                    approved_date   : null
                }
            };


        if(status == 1){
            approvals.verify.verified = 1;
            approvals.verify.verified_by = SESSIONS_DATA.emp_id;
            approvals.verify.verified_date = moment().format('YYYY-MM-DD HH:mm:ss');
            $.fn.send_email_verifier_approver_service_request(data,1);
        }
        if(status == 2){
            approvals = sr_data.approval_verify;
            approvals.approve.approved = 1;
            approvals.approve.approved_by = SESSIONS_DATA.emp_id;
            approvals.approve.approved_date = moment().format('YYYY-MM-DD HH:mm:ss');
            sr_data.status_id = COMPLETE_STATUS;

            var remarks     = sr_data.remark_list;
            var remark_rec =
                {
                    remarks         : data.complete_status_name,
                    created_by      : SESSIONS_DATA.name,
                    created_date    : moment().format('YYYY-MM-DD HH:mm:ss')
                };
            if(remarks)
            {
                remarks.push(remark_rec);
            }
            else
            {
                remarks = [];
                remarks.push(remark_rec);
            }
            sr_data.remark_list = remarks;

            $.fn.send_email_verifier_approver_service_request(data,2);
        }

        sr_data.approval_verify = approvals;

        var data_service	=
            {
                service_no      : data.service_no,
                service_data	: sr_data,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_service_request', data_service),
            function(return_data)
            {
                if(return_data.data)
                {
                    RECORD_INDEX = 0;
                    $.fn.get_list(false);
                }
            },false,btn_verify_approve
        );

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
            $('#dd_category_search')		.val('').change();
            $('#dd_created_by_search')	    .val('').change();
            $('#dd_company_search')	        .val('').change();
            $('#dd_status_search')	        .val('').change();
        }
        else if(form == 'form')
        {
            SERVICE_NO		                = '';
            SERVICE_DATA                    = '';
            $('#dd_category')			    .val('').change();
            $('#dd_company')		        .val('').change();

            $('#dd_invoice_client')			.val('').change();
            $('#txt_invoice_contact_person').val('');
            $('#txt_invoice_description')	.val('');
            $('#txt_invoice_bank_details')	.val('');
            $('#txt_invoice_unit_price')	.val('');
            $('#txt_invoice_quantity')	    .val('');
            $('#txt_invoice_amount')	    .val('');
            $('#dd_invoice_gst_sst')		.val('0').change();
            $('#txt_invoice_total_amount')	.val('');
            $('#dd_invoice_payment_terms')	.val('').change();
            $('#dd_invoice_status')			.val(STATUS_DRAFT).change();
            $('#dd_invoice_status option[value="' + COMPLETE_STATUS + '"]')   .remove();
            $('#dd_invoice_status')         .removeAttr('disabled');

            $('#txt_payment_description')	.val('');
            $('#txt_payment_bank_details')	.val('');
            $('#txt_payment_unit_price')	.val('');
            $('#txt_payment_quantity')	    .val('');
            $('#txt_payment_amount')	    .val('');
            $('#dd_payment_gst_sst')		.val('0').change();
            $('#txt_payment_total_amount')	.val('');
            $('#txt_payment_payable_to')	.val('');
            $('#dd_payment_status')			.val(STATUS_DRAFT).change();
            $('#dd_payment_status option[value="' + COMPLETE_STATUS + '"]')   .remove();
            $('#dd_payment_status')         .removeAttr('disabled');

            $('#txt_loan_description')	    .val('');
            $('#txt_loan_amount')	        .val('');
            $('#txt_loan_repayment_number')	.val('');
            $('#txt_loan_repayment_amount')	.val('');
            $('#txt_loan_advance')	        .val('');
            $('#txt_loan_balance')	        .val('');
            $('#date_loan_required')	    .val('');
            $('#dd_loan_status')			.val(STATUS_DRAFT).change();
            $('#dd_loan_status option[value="' + COMPLETE_STATUS + '"]')   .remove();
            $('#dd_loan_status')            .removeAttr('disabled');

            $('#dd_asset_type')			    .val('').change();
            $('#txt_asset_duration')	    .val('');
            $('#txt_asset_description')	    .val('');
            $('#txt_asset_remarks')	        .val('');
            $('#date_asset_needed')	        .val('');
            $('#dd_asset_status')			.val(STATUS_DRAFT).change();
            $('#dd_asset_status option[value="' + COMPLETE_STATUS + '"]')   .remove();
            $('#dd_asset_status')           .removeAttr('disabled');

            $('#invoice_form')  .parsley().destroy();
            $('#payment_form')  .parsley().destroy();
            $('#loan_form')     .parsley().destroy();
            $('#asset_form')    .parsley().destroy();
        }
        else if(form == 'remark_list_modal')
        {
            $('#service_remark')  .val('');
            $('#remark_form').parsley().destroy();
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
        $('#h4_primary_no')	.text('');
        $('#btn_save')		.html('<i class="fa fa-check"> </i> Save');
        $.fn.set_validation_form();
        $.fn.init_upload_file();
    }
    else if(form_status == 'EDIT')
    {
        $('#list_div')		.hide(400);
        $('#new_div')		.show(400);
        $.fn.set_validation_form();
        $.fn.set_edit_form();
        $.fn.init_upload_file();
    }
    else if(form_status == 'BACK')
    {
        $('#list_div')		.show(400);
        $('#new_div')		.hide(400);
    }

    if(MODULE_ACCESS.create_it == 0)
    {
        $('#btn_new')		.hide();
        $('#btn_save')		.hide();
        $('#btn_cancel')	.hide();
    }
    else
    {
        $('#btn_new')		.show();
        $('#btn_save')		.show();
        $('#btn_cancel')	.show();
    }
};

$.fn.show_hide_component = function()
{
    var category = $('#dd_category')   .val();
    var company  = $('#dd_company')    .val();

    $('#div_invoice')   .hide();
    $('#div_payment')   .hide();
    $('#div_loan')      .hide();
    $('#div_asset')     .hide();
    $('#div_button')    .hide();

    if(category == RAISE_INVOICE && company != '') {
        $('#div_invoice')   .show();
        $('#div_button')    .show();
    }
    else if(category == PAYMENT && company != '') {
        $('#div_payment')   .show();
        $('#div_button')    .show();
    }
    else if(category == LOAN && company != '') {
        $('#div_loan')      .show();
        $('#div_button')    .show();
    }
    else if(category == ASSET && company != '') {
        $('#div_asset')     .show();
        $('#div_button')    .show();
    }
};

$.fn.calculate_value = function(option) {
    if(option == 'AMOUNT_INVOICE') {
        var unit_price = $('#txt_invoice_unit_price')   .val();
        var quantity   = $('#txt_invoice_quantity')     .val();
        var amount     = parseFloat(unit_price) * parseFloat(quantity);

        $('#txt_invoice_amount')                        .val(amount.toFixed(2));
    }
    if(option == 'TOTAL_INVOICE') {
        var amount      = $('#txt_invoice_amount')       .val();
        var gst         = $('#dd_invoice_gst_sst')       .val();
        var total_amont = parseFloat(amount) + ((parseFloat(amount) * parseFloat(gst)) / 100);

        $('#txt_invoice_total_amount')                    .val(total_amont.toFixed(2));
    }

    if(option == 'AMOUNT_PAYMENT') {
        var unit_price = $('#txt_payment_unit_price')   .val();
        var quantity   = $('#txt_payment_quantity')     .val();
        var amount     = parseFloat(unit_price) * parseFloat(quantity);

        $('#txt_payment_amount')                        .val(amount.toFixed(2));
    }
    if(option == 'TOTAL_PAYMENT') {
        var amount      = $('#txt_payment_amount')       .val();
        var gst         = $('#dd_payment_gst_sst')       .val();
        var total_amont = parseFloat(amount) + ((parseFloat(amount) * parseFloat(gst)) / 100);

        $('#txt_payment_total_amount')                    .val(total_amont.toFixed(2));
    }
};

$.fn.prepare_form = function()
{
    try
    {
        $('#date_loan_required,#date_asset_needed').datepicker({dateFormat: 'dd-mm-yy'});
        $('.populate').select2({tags: true,tokenSeparators: [",", " "]});
        $('.tooltips').tooltip();

        $.fn.set_validation_form();

        if(MODULE_ACCESS.create_it == 0)
        {
            $('#btn_new')		.hide();
            $('#btn_save')		.hide();
            $('#btn_cancel')	.hide();
        }

        $.fn.get_list();
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.set_validation_form = function()
{
    $('#invoice_form').parsley
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

    $('#payment_form').parsley
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

    $('#loan_form').parsley
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

    $('#asset_form').parsley
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

    $('#remark_form').parsley
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
        $('#dd_category, #dd_company').change( function(e)
        {
            $.fn.show_hide_component();
        });

        $('#btn_reset').click( function(e)
        {
            e.preventDefault();
            $.fn.reset_form('list');
            RECORD_INDEX = 0;
            $.fn.get_list(false);
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

        $('#btn_save').click( function(e)
        {
            e.preventDefault();
            btn_save = Ladda.create(this);
            btn_save.start();
            $.fn.save_edit_form();

        });

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

        $('#btn_add_remark').click( function(e)
        {
            e.preventDefault();
            btn_save_remarks = Ladda.create(this);
            btn_save_remarks.start();
            $.fn.add_edit_remark();
        });


        $('#txt_invoice_unit_price, #txt_invoice_quantity').keyup( function(e)
        {
            $.fn.calculate_value('AMOUNT_INVOICE');
            $.fn.calculate_value('TOTAL_INVOICE');
        });

        $('#dd_invoice_gst_sst').change( function(e)
        {
            $.fn.calculate_value('TOTAL_INVOICE');
        });

        $('#txt_payment_unit_price, #txt_payment_quantity').keyup( function(e)
        {
            $.fn.calculate_value('AMOUNT_PAYMENT');
            $.fn.calculate_value('TOTAL_PAYMENT');
        });

        $('#dd_payment_gst_sst').change( function(e)
        {
            $.fn.calculate_value('TOTAL_PAYMENT');
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
    $('#doc_invoice_upload').fileupload
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
    $('#doc_payment_upload').fileupload
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
    $('#doc_loan_upload').fileupload
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
    $('#doc_asset_upload').fileupload
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

    $('#doc_invoice_upload').bind('fileuploadsubmit', function (e, data)
    {
        let service_no  = $('#document_invoice').data('service_no');

        data.formData =
            {
                upload_path : 'service_request/' + service_no + '/',
                file_name   : data.files[0].name
            }
    });
    $('#doc_payment_upload').bind('fileuploadsubmit', function (e, data)
    {
        let service_no  = $('#document_payment').data('service_no');

        data.formData =
            {
                upload_path : 'service_request/' + service_no + '/',
                file_name   : data.files[0].name
            }
    });
    $('#doc_loan_upload').bind('fileuploadsubmit', function (e, data)
    {
        let service_no  = $('#document_loan').data('service_no');

        data.formData =
            {
                upload_path : 'service_request/' + service_no + '/',
                file_name   : data.files[0].name
            }
    });
    $('#doc_asset_upload').bind('fileuploadsubmit', function (e, data)
    {
        let service_no  = $('#document_asset').data('service_no');

        data.formData =
            {
                upload_path : 'service_request/' + service_no + '/',
                file_name   : data.files[0].name
            }
    });


    $('#doc_invoice_upload').bind('fileuploadadd', function (e, data)
    {
        total_selected_files += 1;

        $('#document_invoice').append
        (
            $('<div></div>')
                .addClass('file-upload new')
                .data(data)
                .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );

        $(".file-upload .cancel").unbind().on('click', function(event) {
            event.preventDefault();
            $(this).parent().remove();
            total_selected_files -= 1;
        });
    });
    $('#doc_payment_upload').bind('fileuploadadd', function (e, data)
    {
        total_selected_files += 1;

        $('#document_payment').append
        (
            $('<div></div>')
                .addClass('file-upload new')
                .data(data)
                .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );

        $(".file-upload .cancel").unbind().on('click', function(event) {
            event.preventDefault();
            $(this).parent().remove();
            total_selected_files -= 1;
        });
    });
    $('#doc_loan_upload').bind('fileuploadadd', function (e, data)
    {
        total_selected_files += 1;

        $('#document_loan').append
        (
            $('<div></div>')
                .addClass('file-upload new')
                .data(data)
                .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );

        $(".file-upload .cancel").unbind().on('click', function(event) {
            event.preventDefault();
            $(this).parent().remove();
            total_selected_files -= 1;
        });
    });
    $('#doc_asset_upload').bind('fileuploadadd', function (e, data)
    {
        total_selected_files += 1;

        $('#document_asset').append
        (
            $('<div></div>')
                .addClass('file-upload new')
                .data(data)
                .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );

        $(".file-upload .cancel").unbind().on('click', function(event) {
            event.preventDefault();
            $(this).parent().remove();
            total_selected_files -= 1;
        });
    });

    $('#doc_invoice_upload').bind('fileuploadstop', function (e, data)
    {
        console.log("all file uploaded");
    });
    $('#doc_payment_upload').bind('fileuploadstop', function (e, data)
    {
        console.log("all file uploaded");
    });
    $('#doc_loan_upload').bind('fileuploadstop', function (e, data)
    {
        console.log("all file uploaded");
    });
    $('#doc_asset_upload').bind('fileuploadstop', function (e, data)
    {
        console.log("all file uploaded");
    });
    // Documents upload end
};

$.fn.reset_upload_form = function()
{
    $('#document_invoice')      .html('');
    $('#document_payment')      .html('');
    $('#document_loan')         .html('');
    $('#document_asset')        .html('');
    var $documentupload_invoice = $('#doc_invoice_upload');
    var $documentupload_payment = $('#doc_payment_upload');
    var $documentupload_loan    = $('#doc_loan_upload');
    var $documentupload_asset   = $('#doc_asset_upload');
    $documentupload_invoice     .unbind('fileuploadadd');
    $documentupload_payment     .unbind('fileuploadadd');
    $documentupload_loan        .unbind('fileuploadadd');
    $documentupload_asset       .unbind('fileuploadadd');
    total_selected_files        = 0;
};

$.fn.upload_file  = function(param)
{
    try {
        let total_files     = total_selected_files;
        let total_completed = 0;
        let total_succeed   = 0;
        let failed_file     = [];

        var id_doc = '';
        if(param.service_data.category_id == RAISE_INVOICE) {
            id_doc = 'document_invoice';
        }
        if(param.service_data.category_id == PAYMENT) {
            id_doc = 'document_payment';
        }
        if(param.service_data.category_id == LOAN) {
            id_doc = 'document_loan';
        }
        if(param.service_data.category_id == ASSET) {
            id_doc = 'document_asset';
        }

        $('#'+id_doc).data('service_no',param.service_no);

        $('#'+id_doc+' .file-upload.new').each(function(index) {

            let data = $(this).data();

            if (data.submit)
            {
                data.submit()
                    .success(function(result, textStatus)
                    {
                        total_succeed += 1;
                        console.log(result);
                        // if (callback) callback(result.files[0].name);
                    })
                    .complete(function(result, textStatus)
                    {
                        total_completed += 1;
                        if(total_files == total_completed){
                            if(param.service_data.status_id == SEND_VERIFY_STATUS)
                            {
                                $.fn.send_email_verifier_approver_service_request(param,0);
                            }
                        }
                        console.log(result);
                    })
                    .error(function(result, textStatus)
                    {
                        failed_file.push(result);
                    });
            }
        });

    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
};

$.fn.send_email_verifier_approver_service_request = function(param,type)
{
    try
    {
        var data	=
            {
                service_no					    : param.service_no,
                module_id					    : MODULE_ACCESS.module_id,
                emp_id 							: SESSIONS_DATA.emp_id,
                email_type                      : type,
                attachment                      : []
            };

        if(param.sr_data)
        {
            param.service_data = JSON.parse(param.sr_data);
        }
        data.attachment = param.service_data.attachment;

        $.fn.write_data
        (
            $.fn.generate_parameter('send_email_verifier_approver_service_request', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    $.fn.show_right_success_noty('Email has been sent successfully');
                }

            },false
        );
    }
    catch (e)
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
};


// START of Document initialization
$(document).ready(function()
{
    $.fn.form_load();
});
// END of Document initialization
