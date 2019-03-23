var BTN_LADDA 	= [];
CURRENT_PATH	= '../../';
var APPT_ID, btn_save;

$.fn.reset_form = function (form)
{
    try
    {
    	$.fn.delete_attachment();
    	if(form == 'list')
		{
			
		}
    	else if(form == 'form')
    	{
	        $('#dd_to')						.val('').change();
	        $('#txt_subject')     			.val('');
	        $('#tp_time_to')     			.val('');
	        $('#dd_category')      			.val(123).change();
	        $('#txt_location')      		.val('');
	        $('#dp_date_start')     		.val('');
	        $('#dp_date_end')				.val('');
	        $('#tp_time_start')				.val('').change();
	        $('#tp_time_end')       		.val('').change();
	        $('#dd_status')        			.val(149).change();
	        $('#btn_save')					.html('Create Appointment');
	        $('#row_receipt,#row_outcome')	.hide();
	        
	        $.fn.load_editor('text_editor');
			CKEDITOR.instances.text_editor.setData(atob($('#txt_template').val()));
			
			$('#dp_date_start,#dp_date_end, #dp_followup_date').datepicker
	        ({
	            autoclose		: true,
	            todayHighlight	: true
	        }).datepicker("setDate", new Date());
			$.fn.delete_attachment();
    	}
    	
    }
    catch (e)
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.load_inc_appt_list = function ()
{
    try 
    {
    	$.fn.fetch_data
        (
            $.fn.generate_parameter('get_appt_list_new', {past_appt:0, emp_id:SESSIONS_DATA.emp_id, office_email: SESSIONS_DATA.office_email}),
            function (return_data)
            {
                if (return_data.code == 0)
                {
                    $('#list_inc_appt').empty();
                    for (const item of return_data.data)
                    {
                        let datetime   	= moment(item.date_time);
                        let datetimeto 	= moment(item.date_time_to);
                        let des 		= '';
//                        let btn_label	= (item.check_type == 0 ? 'Check In' : 'Check Out');
//                        let btn_css		= (item.check_type == 0 ? 'btn-primary-alt' : 'btn-danger-alt');
//                        let btn_check	= '';
                        
                        
                        if (item.category_name)
                        {
                            des = `( <i class='list-designation'>${item.category_name}</i> )`;
                        }
                     
                        
                        if(datetime == null && datetimeto == null)
                        {
                        	datetime 	= moment("1970-01-01 00:00");
                        	datetimeto 	= moment("1970-01-01 00:00");
                        }
                        
                        
                        $('#list_inc_appt').append
                        (
                            `<a class='list-group-item appt_item' data-value='${escape(JSON.stringify(item))}'>
                                <div class='row'>
                                    <div class='apt-date col-sm-2 col-lg-2'>
                                        ${datetime.format('MMM D, H:mm A')} - ${datetimeto.format('H:mm A')}
                                    </div>

                                    <div class='col-sm-6 col-lg-4'>
                                        <p class='apt-title1'>${item.subject}</p>
                                        <p class='apt-title2'>${item.pic_email} ${des}</p>
                                    </div>
                                    
                                </div>
                            </a>`
                        );
                        
                    }

                    $('.appt_item').on('click', function(event,) 
                    {
                        //event.preventDefault();
                        event.stopPropagation();
                        $.fn.set_form_status(1);
                        let data = JSON.parse(unescape($(this).data('value')));
                        $.fn.populate_detail_form(data);
                    });
                }
                else if (return_data.code == 1)
                {
                    $('#list_inc_appt').empty().append
                    (
                        `<a class="list-group-item">
                            <div class="list-placeholder">No Upcoming Appointments</div>
                        </a>`
                    );
                }
            }, false, '', false, true
        );
    } 
    catch (e) 
    {
//    	console.log(e.message);
//        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.populate_detail_form = function (data)
{
    try
    {
//    	data 					= JSON.parse(data);

    	
    	APPT_ID					= data.id;
        let datetime   			= moment(data.date_time);
        let datetimeto 			= moment(data.date_time_to);
        
        if(datetime == null && datetimeto == null)
        {
        	datetime 	= moment("1970-01-01 00:00");
        	datetimeto 	= moment("1970-01-01 00:00");
        }
        
        $('#dd_to')				.val(data.pic_email.split(',')).change();
        $('#txt_subject')     	.val(data.subject);
        $('#dd_category')      	.val(data.category_id).change();
        $('#txt_location')      .val(data.location);
        $('#dp_date_start')     .val(datetime.format('DD-MMM-YYYY'));
        $('#dp_date_end')		.val(datetimeto.format('DD-MMM-YYYY'));
        $('#tp_time_start')		.val(datetime.format('h:mm A')).change();
        $('#tp_time_end')       .val(datetimeto.format('h:mm A')).change();
        $('#dd_status')        	.val(data.status_id).change();
        $('#btn_save')			.html('Update Appointment');
        if(data.created_by != SESSIONS_DATA.emp_id)
        {
        	$('#btn_save').hide();
        }
        
        $('#row_receipt,#row_outcome').show();
//        $.fn.load_editor('text_editor');
//		CKEDITOR.instances.text_editor.setData(atob(data.remarks));
        
        $.fn.get_receipt_list(data.id);
    }
    catch (e)
    {
        console.error("[DEBUG] | ", e.message);
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.get_receipt_list = function(appt_id)
{
    try 
    {
        var data =
        {
            appt_id : appt_id
        }

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_doc_list_by_appt', data),
            function (return_data)
            {
                if (return_data.code == 0 && return_data.data.length > 0)
                {
                	$.fn.populate_receipt_list(return_data.data);
                }
            }, false, '', true, true
        );
    } 
    catch (e) 
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.populate_receipt_list = function(data)
{
    try 
    {
    	$('#tbl_receipt > tbody').empty();
        let row_data = '';
        for (const row of data)
        {
        	row_data += '<tr>' +
						'<td width="50%">' +
							'<div class="col-sm-12">' +
							'<span class="receipt">' +
                            '<a href class="btn_view_file" data-path="' + row.filepath + '">' + row.filename + '</a>' +
                            '</span>' +
							'</div>' +
						'</td>' +
						'<td width="30%">Cost : ' + row.cost + '<br/> GST : ' + row.gst + '<br/>Total : ' + row.roundup + '</td>' +
						'<td width="20%"></td>' +
					'</tr>';
        }
        $('#tbl_receipt > tbody').append(row_data);
        $('.btn_view_file').unbind().on('click', function(event) 
        {
            event.preventDefault();
            let path = $(this).data('path');
            $.fn.view_file(path);
        });

    } 
    catch (e) 
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}


$.fn.load_past_appt_list = function ()
{
    try 
    {
        const tags = 
        [
            "<span class='label label-success'>Took Place</span>",
            "<span class='label label-warning'>Postponed</span>",
            "<span class='label label-danger'>Cancelled</span>"
        ]

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_appt_list_new', {past_appt:1, emp_id:SESSIONS_DATA.emp_id, office_email: SESSIONS_DATA.office_email}),
            function (return_data)
            {
                if (return_data.code == 0)
                {
                    $('#list_past_appt').empty();
                    for (const [index,item] of return_data.data.entries())
                    {
                        let datetime   = moment(item.date_time);
                        let datetimeto = moment(item.date_time_to);
                        let status     = item.status - 1;

                        $('#list_past_appt').append
                        (
                            `<tr>
                                <td>${index+1}</td>
                                <td>${datetime.format('MMM D, H:mm A')} - ${datetimeto.format('H:mm A')}</td>
                                <td>${item.subject}</td>
                                <td>${item.pic_email}</td>
                                <td>${item.status}</td>
                            </tr>`
                        )
                    }
                }
                else if (return_data.code == 1)
                {
                    $('#list_past_appt').empty().append
                    (
                        `<tr>
                            <td colspan="5">
                                <div class="list-placeholder">No records found!</div>
                            </td>
                        </tr>`
                    );
                }
            }, false, '', false, true
        );
    } 
    catch (e) 
    {
    	console.log(e.message);
//        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.save_edit_form = function()
{
	try
	{
		if($('#form_appt').parsley( 'validate' ) == false)
		{
			btn_save.stop();
			return;
		}
	    
		var datetime    	= $.fn.process_datetime();
	    var datetimeto  	= $.fn.process_datetime(true);
	    let time_start		= moment($('#tp_time_start').val(), 'h:mm A');
	    let time_end  		= moment($('#tp_time_end').val(), 'h:mm A');
	    let date_start 		= moment($('#dp_date_start').val(), 'DD-MMM-YYYY');
	    let date_end 		= moment($('#dp_date_end').val(), 'DD-MMM-YYYY');
	    
	    
        var data =
        {
        	id     			: APPT_ID,
            to				: $('#dd_to').val(),
            subject			: $('#txt_subject').val(),
            category	 	: $('#dd_category').val(),
            location		: $('#txt_location').val(),
        	date_time_start	: date_start.format('YYYY-MM-DD ') + ' ' + time_start.format('HH:mm'),
            date_time_end 	: date_end.format('YYYY-MM-DD ') + ' ' + time_end.format('HH:mm'),
            
            date_pp_time_start	: '',
            date_pp_time_end 	: '',
            
            descr			: encodeURIComponent(CKEDITOR.instances.text_editor.getData()),
            outcome			: $('#txt_outcome').val(),
            status			: $('#dd_status').val(),
            emp_id       	: SESSIONS_DATA.emp_id,
            emp_name     	: SESSIONS_DATA.name,
            emp_email    	: SESSIONS_DATA.office_email
        }
        
        
        if($('#dd_status').val() == 151 || $('#dd_status').val() == 152)
	    {
	    	let pp_time_start	= moment($('#tp_pp_time_start').val(), 'h:mm A');
		    let pp_time_end  	= moment($('#tp_pp_time_end').val(), 'h:mm A');
		    let pp_date_start 	= moment($('#dp_pp_date_start').val(), 'DD-MMM-YYYY');
		    let pp_date_end 	= moment($('#dp_pp_date_end').val(), 'DD-MMM-YYYY');
		    
		    data.date_pp_time_start = pp_date_start.format('YYYY-MM-DD ') + ' ' + pp_time_start.format('HH:mm');
		    data.date_pp_time_end = pp_date_end.format('YYYY-MM-DD ') + ' ' + pp_time_end.format('HH:mm');
	    }
        
        $.fn.write_data
        (
        	$.fn.generate_parameter('add_edit_appt_new', data),
        	function(return_data)
        	{
        		if(return_data.data)
        		{
        			APPT_ID = return_data.data;
        			$.fn.load_past_appt_list();
    				$.fn.load_inc_appt_list();
        			if($('#dd_status').val() == 150)
    				{
        				$('#btn_save').hide();
    				}
        			else
        			{
        				$('#row_receipt,#row_outcome').show();
        				$('#btn_save').html('Update Appointment');
        			}
        		}
        	}, false, btn_save
        );

        if ($('#files').data())
        {
            $.fn.save_receipt(APPT_ID);
        }
        
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	} 
}

$.fn.process_datetime = function (time_to = false)
{
    if (time_to)
    {
        var time_parts = $('#tp_time_end').val().split(/[\s:]+/);
    }
    else
    {
        var time_parts = $('#tp_time_start').val().split(/[\s:]+/);
    }
    var time_parts = $('#tp_time_start').val().split(/[\s:]+/);
    var date       = $('#dp_date_start').val().split('-').reverse().join('-');
    var hour       = Number(time_parts[0]);

    if ((time_parts[2] == 'PM' || time_parts[2] == 'pm') && hour != 12)
    {
        time_parts[0] = hour + 12;
    }

    var time = `${time_parts[0]}:${time_parts[1]}`;

    return `${date} ${time}`;
}

$.fn.save_receipt = function(appt_id)
{
    var data	=
    {
        doc_date			: moment($('#dp_date_start').val(), 'DD-MMM-YYYY'),
        category_id			: 7,
        cost                : $('#txt_cost').val(),
        gst                 : $('#txt_gst').val(),
        roundup             : $('#txt_roundup').val(),
        appt_id				: appt_id,
        emp_id 				: SESSIONS_DATA.emp_id
    };

    $.fn.upload_file(function(filename)
    {
        data.filename  = filename;
        $.fn.write_data
        (
            $.fn.generate_parameter('add_document', data),
            function(return_data)
            {
                if(return_data.data)
                {
                    $.fn.show_right_success_noty('Data has been recorded successfully');
                    $.fn.populate_receipt_list(return_data.data.list);
                }

            },false
        );
    });
}

$.fn.init_upload_file = function()
{
    $('#files')				.html('').removeData();
	$('#hidden_filename')	.val('');

    var $fileupload 		= $('#fileupload');

    $fileupload.fileupload
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
    
    let name 	= SESSIONS_DATA.username.trim();
    name 		= name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();
    
    $fileupload.bind('fileuploadsubmit', function (e, data)
    {
        let date 		= moment($('#dp_date_start').val(), 'DD-MMM-YYYY');
        let doc_ext		= $('#hidden_filename').val().split('.').pop();
        data.formData 	=
        {
            upload_path : 'documents/' + date.format('MM') + '_' + date.format('YYYY') + '/' + SESSIONS_DATA.emp_id + '/',
            file_name   : name + "_Claims_" + date.format('MMMMYY') + '.' + doc_ext
        };
    });

    $fileupload.bind('fileuploadadd', function (e, data)
    {
        var $files = $('#files');
        $.each(data.files, function (index, file)
        {
            // var $fileName = $('<p/>').append($('<span/>').text(file.name));
            $("#btn_delete_attc").show();
            $files.append(`<span>${file.name}</span>`);
            $files.data(data);

            $('#hidden_filename').val(file.name);
        });


    });
};

$.fn.upload_file  = function(callback)
{
    var data = $('#files').data();
    if (data.submit)
    {
        data.submit().success(function(response)
        {
           	if (callback) callback(response.files[0].name);
        });
    }
};

$.fn.delete_attachment = function()
{
    $('#files').html('').removeData();
    $('#btn_delete_attc').hide();
}


$.fn.view_file = function (path)
{
    try 
    {
        if (!path)
        {
            $.fn.show_right_error_noty('Document path cannot be empty');
			return;
        }
        window.open(path);
    } 
    catch (e) 
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.set_form_status = function(mode = 0, data = {})
{
    try 
    {
        
        switch (mode)
        {
            case 0:
            	$.fn.reset_form('list');
                $('#div_list')    .show(200);
                $('#div_list1')   .show(200);
                $('#div_detail')  .hide(200);
                break;
            case 1:
            	$.fn.reset_form('form');
                $('#div_list')    .hide(200);
                $('#div_list1')   .hide(200);
                $('#div_detail')  .show(200);
                break;
            case 2:
//                $('#dd_client, #txt_client, #dd_pic, #txt_pic, #txt_category').prop('required', false);
//                $('#txt_cost')       .val(0);
//                $('#txt_gst')        .val(0);
//                $('#txt_roundup')    .val(0);
//                $.fn.set_modal_view(true);
//                if (data) {$.fn.set_modal_data(data);}
//                $('#row_receipt').show();
//                $('.view-1')    .hide(200);
//                $('.view-2')    .show(200);
                break;
        }
    } 
    catch (e) 
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

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

$.fn.prepare_form = function ()
{
    try
    {
        $('#dp_date_start,#dp_date_end, #dp_pp_date_start,#dp_pp_date_end, #dp_followup_date').datepicker
        ({
            autoclose		: true,
            todayHighlight	: true
        }).datepicker("setDate", new Date());
        
        $('#tp_time_start, #tp_time_end, #tp_pp_time_start, #tp_pp_time_end, #tp_followup_time, #tp_followup_time_to').timepicker
        ({
            timeFormat	: 'g:i A',
            minTime		:  '07:00am',
            maxTime		: '10:00pm'
        });
        
        $.fn.load_editor('text_editor');
        
        $('.populate').select2({tags: true,tokenSeparators: [",", " "]});
//        $.fn.initialize_parsley('form_appt');
        $.fn.init_upload_file();
        $.fn.load_inc_appt_list();
        $.fn.load_past_appt_list();
    
    }
    catch (e)
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.bind_command_events = function ()
{
    try
    {
        $('#btn_new_appt').on('click', function(event) 
        {
            event.preventDefault();
            $.fn.set_form_status(1);
        });
        
        $('#btn_save').on('click', function(event) 
        {
            event.preventDefault();
            btn_save = Ladda.create(this);
	 		btn_save.start();
	 		$.fn.save_edit_form();
	 		
        });

        $('#btn_delete_attc').on('click', function(event) 
        {
            event.preventDefault();
            $.fn.delete_attachment();
        });

        $('#btn_back_1').on('click', function(event)
        {
            event.preventDefault();
            bootbox.confirm
            ({
                title: "Cancel Confirmation",
                message: "Are you sure you want to go back without saving changes?",
                buttons:
                {
                    cancel:
                    {
                        label: '<i class="fa fa-times"></i> NO'
                    },
                    confirm:
                    {
                        label: '<i class="fa fa-check"></i> YES'
                    }
                },
                callback: function (result)
                {
                    if (result == true)     // Users click 'YES'
                    {
                    	$.fn.set_form_status(0);
                    	$('.bootbox.modal').modal('hide');
                        $('#form_appt').parsley('reset');
                    }
                }
            });

        });

        $('#dd_status').on('change', function(e)
        {
            e.preventDefault();
            if($(this).val() == 151 || $(this).val() == 152)
        	{
            	$('#div_postpone').show();
        	}
            else
        	{
            	$('#div_postpone').hide();
        	}
           
        });
        
        $('#tp_time_start').on('change', function(e)
        {
            e.preventDefault();

            let time_parts = $(this).val().split(/[\s:]+/);
            let time;

            if (time_parts[1] == '00')
            {
                time = `${time_parts[0]}:30${time_parts[2].toLowerCase()}`;
            }
            else if (time_parts[1] == '30')
            {
                time = `${Number(time_parts[0]) + 1}:00${time_parts[2].toLowerCase()}`;
            }

            $('#tp_time_end').timepicker('option', 'disableTimeRanges', [['7:00am', time]])
        });

        $('#tp_pp_time_start').on('change', function(e)
        {
            e.preventDefault();

            let time_parts = $(this).val().split(/[\s:]+/);
            let time;

            if (time_parts[1] == '00')
            {
                time = `${time_parts[0]}:30${time_parts[2].toLowerCase()}`;
            }
            else if (time_parts[1] == '30')
            {
                time = `${Number(time_parts[0]) + 1}:00${time_parts[2].toLowerCase()}`;
            }

            $('#tp_pp_time_end').timepicker('option', 'disableTimeRanges', [['7:00am', time]])
        });
    }
    catch (e)
    {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.form_load = function ()
{
    try
    {
        SESSIONS_DATA = JSON.parse($('#session_data').val());
        $.fn.prepare_form();
        $.fn.bind_command_events();
    }
    catch (e)
    {
      $.fn.log_error(arguments.callee.caller, e.message);
    }
};

$(document).ready(function ()
{
    $.fn.form_load();
});
