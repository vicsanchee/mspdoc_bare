var FORM_STATE 		        = 0;
var RECORD_INDEX 	        = 0;
var SESSIONS_DATA	        = '';
var btn_save,btn_save_remarks,btn_send_email,btn_verify_approve;
var total_selected_files    = 0
OUTBOUND_NO		            = '';
STATUS_DRAFT                = '197';
SEND_VERIFY_STATUS          = '198';
VERIFY_STATUS               = '199';
APPROVE_STATUS              = '200';
DOC_SENT_STATUS             = '201';
CURRENT_PATH	            = 	'../../';


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
                client_id 		: $('#dd_client_search').val(),
                created_by 		: $('#dd_created_by_search').val(),
                category_id		: $('#dd_category_search').val(),
                status_id	    : $('#dd_status_search').val(),
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
            $.fn.generate_parameter('get_outbound_list',data),
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

            //let access_level			= SESSIONS_DATA.access_level;

            for(var i = 0; i < data.length; i++)
            {
                data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");

                row += '</tr>';
                row += '<tr id="TR_ROW_' + i + '"  data-value=\'' + data_val + '\'>' +
                    '<td>' + data[i].outbound_no 	+ '</td>' +
                    '<td>' + data[i].outbound_date	+ '</td>' +
                    '<td>' + data[i].client_name 	+ '</td>' +
                    '<td>' + data[i].category_name 	+ '</td>' +
                    '<td>' + data[i].created_by 	+ '</td>' +
                    '<td>' + data[i].status_name 	+ '</td>';

                row += '<td width="10%">';
                row += '<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Comments" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_remark(unescape($(this).attr(\'data-value\')))"><i class="fa fa-external-link"></i></a>';
                if(MODULE_ACCESS.edit_it == 1)
                {
                    row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Details" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.populate_detail_form(unescape($(this).attr(\'data-value\')))"><i class="fa fa-sign-in"></i></a>';

                }
                if(MODULE_ACCESS.delete_it == 1)
                {
                    row += '&nbsp;&nbsp;<a class="tooltips" data-toggle="tooltip" data-placement="left" title="View Details" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_outbound(unescape($(this).attr(\'data-value\')))"><i class="fa fa-trash-o"/></a>';
                }

                var status = '';
                var verify = '';
                var approve = '';
                var json_approval 	= JSON.parse(data[i].approvals);

                if(json_approval)
                {
                    if(json_approval.verify.verified == 1)
                    {
                        status = '<i class="fa fa-pencil-square-o" aria-hidden="true">Verified</i><br/>';
                    }
                    else
                    {
                        if(MODULE_ACCESS.verify_it == 1 && data[i].status_id == SEND_VERIFY_STATUS)
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
                        if(data[i].status_id == SEND_VERIFY_STATUS)
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
        if($('#detail_form').parsley( 'validate' ) == false)
        {
            btn_save.stop();
            return;
        }

        var attachment = [];
        $('#document .file-upload.new').each(function(index) {
            attachment.push($(this)[0].innerText.trim());
        });

        var data	=
            {
                outbound_no					    : OUTBOUND_NO,
                outbound_date                   : $('#outbound_date').val(),
                client_id 	                    : $('#dd_client').val(),
                employer_id 	                : $('#dd_company').val(),
                attention_to 	                : $('#txt_attention_to').val(),
                status_id 	                    : $('#dd_status').val(),
                email				            : ($('#dd_email').val()) ? $('#dd_email').val() : '',
                category_id			            : $('#dd_category').val(),
                amount				            : $('#txt_amount').val(),
                remark				            : $('#txt_remark').val(),
                email_content	                : encodeURIComponent(CKEDITOR.instances.text_editor.getData()),
                emp_id 							: SESSIONS_DATA.emp_id,
                attachment                      : attachment
            };


        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_outbound', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    $.fn.set_edit_form();

                    OUTBOUND_NO = return_data.data.outbound_no;
                    if(total_selected_files > 0)
                    {
                       $.fn.upload_file(return_data.data);
                    }
                    else
                    {
                        if(return_data.data.status_id == SEND_VERIFY_STATUS)
                        {
                            $.fn.send_email_verifier_approver_outbound(return_data.data);
                        }
                    }

                    $('#h4_primary_no').text('Outbound Document Number : ' + return_data.data.outbound_no);
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
        $('#h4_primary_no')		.text('Outbound Document Number : ' + data.outbound_no);

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_outbound_details',{outbound_no : data.outbound_no}),
            function(return_data)
            {
                if(return_data.data.details)
                {
                    var data 					    = return_data.data.details;

                    OUTBOUND_NO					    = data.outbound_no;
                    $('#outbound_date')	            .val(data.outbound_date);
                    $('#dd_client')					.val(data.client_id).change();
                    $('#dd_company')			    .val(data.employer_id).change();
                    $('#txt_attention_to')	        .val(data.attention_to);
                    if(data.status_id == VERIFY_STATUS || data.status_id == APPROVE_STATUS || data.status_id == DOC_SENT_STATUS)
                    {
                        $('#dd_status')   .attr('disabled','disabled');
                        $('#dd_status')   .append('<option value="'+data.status_id+'">'+data.status_name+'</option>');
                    }
                    $('#dd_status')					.val(data.status_id).change();
                    $('#dd_email')	                .val(JSON.parse(data.email)).change();
                    $('#dd_category')               .val(data.ctg_id).change();
                    $('#txt_amount')                .val(data.amount);
                    $('#txt_remark')                .val(data.notes);

                    if(data.status_id == APPROVE_STATUS)
                    {
                        $('#btn_send_email').show();
                    }

                    $.fn.load_editor('text_editor');
                    CKEDITOR.instances.text_editor.setData(decodeURIComponent(data.email_content));

                    var attachment = JSON.parse(data.attachment);

                    for(var i = 0; i < attachment.length; i++)
                    {
                        $('#document').append('<div class="file-upload new"><span><a class="link-view-file" href="'+data.filepath+attachment[i]+'" target="_blank">'+attachment[i]+'</a></span></div>');
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

$.fn.delete_outbound = function(data)
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
                            outbound_no         : data.outbound_no,
                            emp_id              : SESSIONS_DATA.emp_id
                        };
                    $.fn.write_data
                    (
                        $.fn.generate_parameter('delete_outbound', data_delete),
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

$.fn.verify_approval = function(data,event,status)
{
    try
    {
       btn_verify_approve = Ladda.create(event);
       btn_verify_approve.start();

       var data 	= JSON.parse(data);

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
       var status_id = null;

        if(status == 1){
            approvals.verify.verified = 1;
            approvals.verify.verified_by = SESSIONS_DATA.emp_id;
            approvals.verify.verified_date = moment().format('YYYY-MM-DD HH:mm:ss');
            status_id = VERIFY_STATUS;
        }
        if(status == 2){
            approvals = JSON.parse(data.approvals);
            approvals.approve.approved = 1;
            approvals.approve.approved_by = SESSIONS_DATA.emp_id;
            approvals.approve.approved_date = moment().format('YYYY-MM-DD HH:mm:ss');
            status_id = APPROVE_STATUS;
        }

        var data_approvals	=
            {
                outbound_no         : data.outbound_no,
                approvals           : approvals,
                status_id           : status_id,
                module_id			: MODULE_ACCESS.module_id,
                emp_id 			    : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('outbound_verify_approval', data_approvals),
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

$.fn.view_remark = function(data)
{
    try
    {
        var data        = JSON.parse(data);
        var remarks     = JSON.parse(data.remarks);

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

        $('#outbound_document').attr('data-value',JSON.stringify(data));
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
        var data        = JSON.parse($('#outbound_document').attr('data-value'));
        var remarks     = JSON.parse(data.remarks);

        var remark_rec =
            {
                remarks         : ($('#outbound_remark').val()).trim(),
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

        var data	=
            {
                outbound_no    : data.outbound_no,
                remarks	        : remarks,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('outbound_add_edit_remark', data),
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


$.fn.add_remark_for_send_email = function(data)
{
    try
    {
        var remarks     = JSON.parse(data.remarks);

        var remark_rec =
            {
                remarks         : data.remarks_new,
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

        var data	=
            {
                outbound_no    : data.outbound_no,
                remarks	        : remarks,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('outbound_add_edit_remark', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    console.log(return_data.data);
                }

            },false
        );
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
        var remarks         = JSON.parse(data.remarks);
        var delete_data     = JSON.parse(data.delete_data);
        remarks             = remarks.filter(obj => obj.remarks != delete_data.remarks);


        var data	=
            {
                outbound_no     : data.outbound_no,
                remarks	        : remarks,
                emp_id          : SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('outbound_add_edit_remark', data),
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


$.fn.reset_form = function(form)
{
    try
    {
        FORM_STATE		= 0;

        if(form == 'list')
        {
            $('#dd_client_search')		.val('').change();
            $('#dd_created_by_search')	.val('').change();
            $('#dd_category_search')	.val('').change();
            $('#dd_status_search')	    .val('').change();
        }
        else if(form == 'form')
        {
            OUTBOUND_NO		    = '';
            $.fn.reset_upload_form();
            $('#outbound_date')	        .val('');
            $('#dd_client')			    .val('').change();
            $('#dd_company')		    .val('').change();
            $('#txt_attention_to')	    .val('');
            $('#dd_status')			    .val('').change();
            $('#dd_email')	            .val('').change();
            $('#dd_category')           .val('').change();
            $('#txt_amount')            .val('');
            $('#txt_remark')            .val('');
            $.fn.load_editor('text_editor');
            CKEDITOR.instances.text_editor.setData(atob($('#txt_template').val()));
            $('#detail_form').parsley().destroy();

            $('#dd_status option[value="' + VERIFY_STATUS + '"]')   .remove();
            $('#dd_status option[value="' + APPROVE_STATUS + '"]')  .remove();
            $('#dd_status option[value="' + DOC_SENT_STATUS + '"]') .remove();
            $('#dd_status').removeAttr('disabled');
        }
        else if(form == 'remark_list_modal')
        {
            $('#outbound_remark')      .val('');
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
        $('#btn_send_email').hide();
        $('#h4_primary_no')	.text('');
        $('#btn_save')		.html('<i class="fa fa-check"> </i> Save');
        $.fn.init_upload_file();
        $.fn.set_validation_form();
    }
    else if(form_status == 'EDIT')
    {
        $('#list_div')		.hide(400);
        $('#new_div')		.show(400);
        $('#btn_send_email').hide();
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

$.fn.load_editor = function(id)
{
    var editor = CKEDITOR.instances[id];
    if (editor) { editor.destroy(true); }

    CKEDITOR.config.contentsCss 	= CURRENT_PATH + 'assets/css/email.css';
    CKEDITOR.config.allowedContent 	= true;
    CKEDITOR.replace(id,
        {
            height		: 300
        });
};

$.fn.prepare_form = function()
{
    try
    {
        $('#outbound_date').datepicker({dateFormat: 'dd-mm-yy'});
        $('.populate').select2({tags: true,tokenSeparators: [",", " "]});
        $('.tooltips').tooltip();
        $.fn.load_editor('text_editor');

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

        $('#btn_send_email').click( function(e)
        {
            e.preventDefault();
            btn_send_email = Ladda.create(this);
            btn_send_email.start();
            $.fn.send_email_notification();
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
    $('#doc_upload').fileupload
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

    $('#doc_upload').bind('fileuploadsubmit', function (e, data)
    {
        let outbound_no = $('#document').data('outbound_no');

        data.formData =
            {
                upload_path : 'outbound_document/' + outbound_no + '/',
                file_name   : data.files[0].name
            }
    });

    $('#doc_upload').bind('fileuploadadd', function (e, data)
    {
        total_selected_files += 1;

        $('#document').append
        (
            $('<div></div>')
                .addClass('file-upload new')
                .data(data)
                .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span>' + data.files[0].name + '</span>')
        );

        $(".file-upload .cancel").unbind().on('click', function(event) {
            event.preventDefault();
            $(this).parent().remove();
            total_selected_files -= 1;
        });
    });

    $('#doc_upload').bind('fileuploadstop', function (e, data)
    {
        console.log("all file uploaded");
    });
    // Documents upload end
};

$.fn.reset_upload_form = function()
{
    $('#document').html('');
    var $documentupload = $('#doc_upload');
    $documentupload.unbind('fileuploadadd');
    total_selected_files = 0;
};

$.fn.upload_file  = function(param)
{
    try {
        let total_files     = total_selected_files;
        let total_completed = 0;
        let total_succeed   = 0;
        let failed_file     = [];

        $('#document').data('outbound_no',param.outbound_no);

        $('#document .file-upload.new').each(function(index) {

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
                            if(param.status_id == SEND_VERIFY_STATUS)
                            {
                                $.fn.send_email_verifier_approver_outbound(param);
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
    }
    catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
};


$.fn.send_email_verifier_approver_outbound = function(param)
{
    try
    {
        var data	=
            {
                outbound_no					    : param.outbound_no,
                module_id					    : MODULE_ACCESS.module_id,
                emp_id 							: SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('send_email_verifier_approver_outbound', data),
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


$.fn.send_email_notification  = function()
{
    try
    {
        var data	=
            {
                outbound_no					    : OUTBOUND_NO,
                status_id                       : DOC_SENT_STATUS,
                emp_id 							: SESSIONS_DATA.emp_id
            };

        $.fn.write_data
        (
            $.fn.generate_parameter('send_email_notification_outbound', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    $.fn.show_right_success_noty('Email has been sent successfully');
                    $.fn.add_remark_for_send_email(return_data.data);
                }

            },false,btn_send_email
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
