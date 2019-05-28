/**
 * @updated_by : Darus
 * @revised_by : Jamal
 */

CURRENT_PATH = '../../';


// --- Old Code ----------------------------------------------------------------
var FORM_STATE = 0;
var RECORD_INDEX = 0;
var SESSIONS_DATA = '';
var last_scroll_top = 0;
var btn_view_days, btn_add_leave;
var leave_data, leave_days;
var holidays = [];
var holiday_description = [];
LEAVE_ID = '';
NOOFDAYS = '';
BALANCE_LEAVE = 0;

AN_LEAVE_ID = '47';
MC_LEAVE_ID = '48';
EM_LEAVE_ID = '55';
TIME_OFF_ID = '227';


$.fn.data_table_features = function ()
{
    try
    {
        if (!$.fn.dataTable.isDataTable('#tbl_list'))
        {
            table = $('#tbl_list').DataTable
            ({
                "searching": false,
                "paging": false,
                "info": false,
                "order": [[1, "desc"]]
            });
        }
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.data_table_destroy = function ()
{
    try
    {
        if ($.fn.dataTable.isDataTable('#tbl_list'))
        {
            table.destroy();
        }
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.reset_form = function (form)
{
    try
    {
        FORM_STATE = 0;
        LEAVE_ID = '';

        if (form == 'form')
        {
            NOOFDAYS = '';
			BALANCE_LEAVE = 0;

            $('#start_date').val('');
            $('#end_date').val('');
			$('#dd_leave_type').val('').change();
            $('#txt_reason').val('');
			$('#chk_allow_weekend')		.prop('checked',false);

            $('.form-group').each(function ()
            {
                $(this).removeClass('has-error');
            });

            $('.help-block').each(function ()
            {
                $(this).remove();
            });

			$('#leave_file,#leave_file_cost').hide();
			$('#leave_days_info').hide();
			leave_data = [];
			leave_days = 0.0;

			$('#txt_cost')	    .val(0);
			$('#txt_gst')	    .val(0);
			$('#txt_roundup')	.val(0);
			$('#txt_total')		.val(0);
			$('#dd_expenses')	.val('195').change();
			$("#btn_upload").show();
			
			$.fn.reset_upload_form();
        }
		if (form == 'leave_days')
        {
			leave_data = [];
			$('#chk_all')		.prop('checked',false);
		}
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.get_leave_details = function ()
{
    try
    {

        var data =
            {
                emp_id: SESSIONS_DATA.emp_id
            };

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_leave_details', data),
            function (return_data)
            {
                if (return_data)
                {
                    $.fn.populate_leave_details(return_data.data);
                }
            }, true
        );
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.populate_leave_details = function (data)
{
    try
    {
        var row = '';

        for(var i = 0; i < data.length; i++) {

            var entitle_leave = data[i].entitle_leave ? data[i].entitle_leave : 0;
            var brought_forward = data[i].brought_forward ? data[i].brought_forward : 0;
            if(data[i].leave_id == TIME_OFF_ID) {
                entitle_leave = parseFloat(entitle_leave) * 8;
                brought_forward = parseFloat(brought_forward) * 8;
            }
            var paid_leave_taken = data[i].paid_leave_taken ? data[i].paid_leave_taken : 0;
            var unpaid_leave_taken = data[i].unpaid_leave_taken ? data[i].unpaid_leave_taken : 0;
            var available_leave = (parseFloat(entitle_leave) + parseFloat(brought_forward)) - (parseFloat(paid_leave_taken));

            row += '<div class="row">';

            row += '<div class="col-md-12 clearfix">' +
                '<h4 class="pull-left" style="margin: 0px;">' + data[i].leave_type + ' <small>(' + (data[i].leave_id == TIME_OFF_ID ? 'Hours' : 'Days') + ')</small></h4>' +
                '</div>';

            row += '<div class="col-xs-6 col-md-3">' +
                '<h3 class="text-center text-primary margin-bottom-0">' + parseFloat(entitle_leave).toFixed(1) + '</h3>' +
                '<div class="text-center text-info">Entitled for this year</div>' +
                '</div>';

            row += '<div class="col-xs-6 col-md-2">' +
                '<h3 class="text-center text-primary margin-bottom-0">' + parseFloat(brought_forward).toFixed(1) + '</h3>' +
                '<div class="text-center text-info">Brought Forward</div>' +
                '</div>';

            row += '<div class="col-xs-6 col-md-2">' +
                '<h3 class="text-center text-primary margin-bottom-0">' + parseFloat(paid_leave_taken).toFixed(1) + '</h3>' +
                '<div class="text-center text-info">Taken this year (Paid)</div>' +
                '</div>';

            row += '<div class="col-xs-6 col-md-2">' +
                '<h3 class="text-center text-primary margin-bottom-0">' + parseFloat(unpaid_leave_taken).toFixed(1) + '</h3>' +
                '<div class="text-center text-info">Taken this year (Unpaid)</div>' +
                '</div>';

            row += '<div class="col-xs-6 col-md-3">' +
                '<h3 class="text-center text-primary margin-bottom-0">' + parseFloat(available_leave).toFixed(1) + '</h3>' +
                '<div class="text-center text-info">Available Leave</div>' +
                '</div>';

            row += '</div>';

            if(data.length > (i+1)) {
                row += '<hr>';
            }

        }

        $('#div_leave_summary').html(row);
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.get_holidays = function ()
{
    try
    {
        var data = {};

        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_holidays', data),
            function (return_data)
            {
                if (return_data)
                {
					var data= return_data.data.list;
					for (var i = 0; i < data.length; i++){
						holidays.push(data[i].holiday);
						holiday_description.push(data[i].holiday_desc);
					}
                }
            }, true
        );
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.save_edit_form = function ()
{
    try
    {
        var leave_type_id 	= $('#dd_leave_type').val();

        if(leave_type_id == TIME_OFF_ID)
        {
            leave_data = [];
            var leave_param = {};
            var start_part		        = $('#start_date').val().split('-');
            leave_param.leave_date		= start_part[2] +'-'+ start_part[1] +'-'+ start_part[0];
            leave_param.no_of_days 	    = $('#dd_leave_time_off').val();
            leave_param.half_day_opt    = 0;
            leave_data.push(leave_param);
            leave_days                  = $('#dd_leave_time_off').val();
        }

		if(leave_data.length == 0)
		{
			$.fn.show_right_error_noty('Please select at least one leave');
			btn_add_leave.stop();
		}
		else
		{
			var reason 			= $('#txt_reason').val();
         	var filename 		= $('#hidden_filename').val();

            var data =
            {
                id			: LEAVE_ID,
                leave_data	: leave_data,
                no_of_days	: leave_days,
                type_id		: leave_type_id,
                type_name	: $('#dd_leave_type option:selected').text(),
                reason		: reason,
                filename	: $.fn.get_file_name('bill'),
                filename_cert: $.fn.get_file_name('cert'),
                emp_id		: SESSIONS_DATA.emp_id,
                emp_name	: SESSIONS_DATA.name
            };
            
            
            if ($('#dd_leave_type').val() == MC_LEAVE_ID)
            {
                data.cost     	= $('#txt_cost').val();
                data.gst      	= $('#txt_gst').val();
                data.roundup  	= $('#txt_roundup').val();
                data.total    	= $('#txt_total').val();
                data.noe      	= $('#dd_expenses').val();
            }

            $.fn.write_data
            (
                $.fn.generate_parameter('add_edit_leave', data),
                function (return_data)
                {
                    if (return_data.data)
                    {
                        $.fn.show_right_success_noty('Data has been recorded successfully');
                        $.fn.upload_file(function (){},'bill',return_data.data.id, return_data.data.doc_no);
                        $.fn.upload_file(function (){},'cert',return_data.data.id, return_data.data.doc_no);
                        $.fn.reset_form('form');
                        
                        if (return_data.data.listing.rec_index)
                        {
                            RECORD_INDEX = return_data.data.listing.rec_index;
                        }
                        $.fn.data_table_destroy();
                        $.fn.populate_list_form(return_data.data.listing.list, false);
                        $.fn.data_table_features();
                    }

                }, false, btn_add_leave
            );

            return true;
        }
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.populate_list_form = function (data, is_scroll)
{
    try
    {

        if (data) // check if there is any data, precaution
        {
            if (is_scroll == false)
            {
                $('#tbl_list > tbody').empty();
            }

            var row = '';
            var data_val = '';
            for (var i = 0; i < data.length; i++)
            {
                data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");
                let start_date   = moment(data[i].start_date);
                let end_date     = moment(data[i].end_date);
                let applied_date = moment(data[i].created_date);

                var half_day_opt = '';
                if(data[i].no_of_days == '0.5') {
                    if(data[i].half_day_opt == '1') {
                        half_day_opt = '(First Half)';
                    }
                    if(data[i].half_day_opt == '2') {
                        half_day_opt = '(Second Half)';
                    }
                }


                row += '<tr><td>';
                if (data[i].verified == 0)
                {
                    row += '<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_form(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="Delete data "><i class="fa fa-trash-o"/></a>';
                }

                if (data[i].type_id == MC_LEAVE_ID)
                {
                    row += '&nbsp;<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_file(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="View File "><i class="fa fa-picture-o"/></a>';
                }
                row += '</td><td width="10%">' + start_date.format('D-MMM-YYYY') + '</td>' +
                    '<td width="10%">' + end_date.format('D-MMM-YYYY') + '</td>' +
                    '<td width="10%">' + applied_date.format('D-MMM-YYYY') + '</td>' +
					'<td>' + data[i].type + half_day_opt + '</td>' +
                    '<td>' + data[i].no_of_days + (data[i].type_id == TIME_OFF_ID ? ' Hour(s)': '') + '</td>' +
                    '<td>' + data[i].reason + '</td>';

                if (data[i].verified == 0)
                {
                    row += '<td><span class="text-info"><b>Pending Verification</b></span></td>';
                }
                else
                {
                    row += '<td><span class="text-success"><b>Verified</b></span>';
					if (data[i].sum_approved > 0)
					{
						row += '<br /><i class="fa fa-check-circle text-success"> ' + data[i].sum_approved + ' Approved</i>';
					}
					else if (data[i].sum_rejected > 0)
					{
						row += '<br /><i class="fa fa-minus-circle text-danger"> ' + data[i].sum_rejected + ' Rejected</i>';
					}
					else
					{
						row += '<br /><span class="text-info"><b>Pending Approval</b></span>';
					}
					row += '</td>';
                }

                row += '<td><button type="button" class="btn btn-default" id="btn_leave_record" data-value=\'' + data_val + '\' onclick="$.fn.view_leave_by_day(unescape($(this).attr(\'data-value\')))">View</button></td>';

                row += '</tr>';

            }
            $('#tbl_list tbody').append(row);

            $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
        }
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.view_file = function(data)
{
	try
	{
		data = JSON.parse(data);
		if(data.mc == '')
		{
			$.fn.show_right_error_noty('Document path cannot be empty');
			return;
		}

		window.open(data.mc);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.view_leave_by_day = function (data)
{
    try
    {
        data = JSON.parse(data);
        $('#tbl_leave_record > tbody').empty();
        $('#tbl_remark_list  > tbody').empty();

        var data =
        {
            leave_id: data.id
        };

        $.fn.fetch_data(
            $.fn.generate_parameter('get_leave_by_day', data),
            function (return_data)
            {
                if (return_data)
                {
                    $.fn.populate_list_leave_by_day(return_data.data.list);
                    $.fn.populate_remark_list(return_data.data.leave_remarks);
                }
            }, true,false,false,true
        );

        $('#leaveRecordModal').modal();
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.populate_list_leave_by_day = function (data)
{
    try
    {
        if (data) // check if there is any data, precaution
        {
            var row = '';

            for (var i = 0; i < data.length; i++)
            {
                row += '<tr>';
                row += '<td>' + data[i].leave_date + '</td>' +
                       '<td>' + data[i].leave_no_of_days + (data[i].type_id == TIME_OFF_ID ? ' Hour(s)': '') + '</td>';


                if (data[i].approved == 1)
                {
                    row += '<td><span class="text-success"><b>APPROVED</b></span></td>';
                    if (data[i].paid == 1)
                    {
                        row += '<td><span class="text-success"><b>PAID</b></span></td>';
                    }
                    else
                    {
                        row += '<td><span class="text-danger"><b>UNPAID</b></span></td>';
                    }
            	}
                else  if (data[i].rejected == 1)
                {
                    row += '<td><span class="text-danger"><b>REJECT</b></span></td>';
                    row += '<td>UNPAID</td>';
                }
                else
                {
                    row += '<td><span class="text-info"><b>PENDING</b></span></td>';
                    row += '<td>-</td>';
                }
                row += '</tr>';

            }
            $('#tbl_leave_record tbody').html(row);

        }
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.populate_remark_list = function (data)
{
    try
    {
        if (data)
        {
            var row = '';
            var data_val = '';

            for (var i = 0; i < data.length; i++)
            {
                data_val = escape(JSON.stringify(data[i]));

                row += '<tr>' +
                    '<td>' + data[i].created_date + '(' + data[i].created_by + ') - ' + data[i].leave_remarks + '</td>';

                row += '</tr>';

            }
            $('#tbl_remark_list tbody').html(row);
        }

    } catch (err) {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.delete_form = function (data)
{
    try
    {
        data = JSON.parse(data);
        if (data.id == '')
        {
            $.fn.show_right_error_noty('ID cannot be empty');
            return;
        }

        var data =
        {
            id			: data.id,
			start_date	: data.start_date,
			end_date	: data.end_date,
			no_of_days	: data.no_of_days,
			reason		: data.reason,
            emp_id		: SESSIONS_DATA.emp_id,
            doc_no		: data.doc_no
        };

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
                    $.fn.write_data
                    (
                        $.fn.generate_parameter('delete_leave_details', data),
                        function (return_data)
                        {
                            if (return_data)
                            {
                                RECORD_INDEX = 0;
                                $('#tbl_list > tbody').empty();
                                $.fn.populate_list_form(return_data.data.list, false);
                                $.fn.show_right_success_noty('Data has been deleted successfully');
                            }

                        }, false
                    );
                }
            }
        });

    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.view_file_status = function (type_id)
{
	if (type_id == MC_LEAVE_ID)
	{
        $('#leave_file,#leave_file_cost').show();
        $('#leave_time_off,#btn_save_time_off').hide();
        $('#leave_end_date,#btn_view_days,#leave_days_check').show();
	}
	else if (type_id == TIME_OFF_ID)
    {
        $('#leave_time_off,#btn_save_time_off').show();
        $('#leave_end_date,#btn_view_days,#leave_days_check').hide();
    }
	else
	{
		$('#leave_file,#leave_file_cost').hide();
        $('#leave_time_off,#btn_save_time_off').hide();
        $('#leave_end_date,#btn_view_days,#leave_days_check').show();
	}
};

$.fn.change_balance_leave = function (action)
{
	var day_option = [];
	var all_leave_days = 0.0;
    var count = 0;
	$('#leave_form input[name="chk_full"]').each(function() {
		if($(this).is(':checked'))
		{
			day_option.push(1.0);
			all_leave_days = parseFloat(all_leave_days) + 1.0;
            $("#half_option_"+count+"").hide();
		}
		if(!$(this).is(':checked'))
		{
			day_option.push(0.5);
			all_leave_days = parseFloat(all_leave_days) + 0.5;
            $("#half_option_"+count+"").show();
		}
        count++;
	});

    var half_day_opt = [];
    $('#leave_form input[name="chk_half_opt"]').each(function() {
        if($(this).is(':checked'))
        {
            half_day_opt.push(1);
        }
        if(!$(this).is(':checked'))
        {
            half_day_opt.push(2);
        }
    });

	var count = 0;
	leave_days = 0.0;
	leave_data = [];
	$('#leave_form input[id="chk_day"]').each(function() {
		var leave_param = {};
		if(action == 'one')
		{
			if($(this).is(':checked'))
			{
				leave_days = parseFloat(leave_days) + parseFloat(day_option[count]);
				leave_param.leave_date 		= $(this).val();
				leave_param.no_of_days 	= day_option[count];
                leave_param.half_day_opt = leave_param.no_of_days == '0.5' ? half_day_opt[count] : 0;
				leave_data.push(leave_param);
			}
		}
		if(action == 'all')
		{
			if($('#chk_all').is(':checked'))
			{
				leave_days = parseFloat(leave_days) + parseFloat(day_option[count]);
				leave_param.leave_date 		= $(this).val();
				leave_param.no_of_days 	= day_option[count];
                leave_param.half_day_opt = leave_param.no_of_days == '0.5' ? half_day_opt[count] : 0;
				leave_data.push(leave_param);
			}
		}
		count++;
	});

	var balance_leave = parseFloat(BALANCE_LEAVE) - parseFloat(leave_days);
	$('#div_balance_leave').html(balance_leave.toFixed(1));
};

$.fn.view_days = function ()
{
	try
    {
		$.fn.reset_form('leave_days');
		$('#tbl_days > tbody').empty();

		if($('#leave_form').parsley( 'validate' ) == false)
		{
			btn_view_days.stop();
			return;
		}
		else
		{
			var start_part		= $('#start_date').val().split('-');
			var end_part		= $('#end_date').val().split('-');
			var start_date		= start_part[2] +'-'+ start_part[1] +'-'+ start_part[0];
			var end_date		= end_part[2] +'-'+ end_part[1] +'-'+ end_part[0];

			var start 			= new Date(start_date);
			var end	  			= new Date(end_date);
			var allow_weekend 	= $('#chk_allow_weekend').is(':checked');
			var allow_holiday 	= $('#chk_allow_holiday').is(':checked');

			var row	= '';

			if(end >= start)
			{
				var no_of_days 	= ((end - start) + 86400000)/86400000;
				var curr_date = new Date(start_date);
				var count = 0;
				var holiday_str = '';
				for(var i = 0; no_of_days > i; i++){

					var temp_date = moment(curr_date).format('YYYY-MM-DD');
					var holiday_status = false;
					if(holidays.indexOf(temp_date) != -1){
						holiday_status = true;
                        holiday_str += '<h5><span class="text-info"><b>'+moment(curr_date).format('DD-MM-YYYY')+' - '+holiday_description[holidays.indexOf(temp_date)]+'</b></span></h5>';
					}

					var day_name = moment(curr_date).format('dddd');
					var view_row = true;
					if(!allow_weekend && (day_name.trim() == 'Saturday' || day_name.trim() == 'Sunday'))
					{
						view_row = false;
					}
					else if(!allow_holiday && holiday_status)
					{
						view_row = false;
					}
					else
					{
						view_row = true;

						var txt_class = '';
						if(day_name.trim() == 'Saturday' || day_name.trim() == 'Sunday')
						{
							txt_class = 'text-primary';
						}
						if(holiday_status)
						{
							txt_class = 'text-danger';
						}
					}

					if(view_row)
					{
						row 	+= '<tr class="'+txt_class+'">';
						row 	+= '<td width="10%"><input type="checkbox" id="chk_day" name="chk_day" value="'+moment(curr_date).format('YYYY-MM-DD')+'" onchange="$.fn.change_balance_leave(\'one\')"></td>';
						row 	+= '<td width="25%">' +moment(curr_date).format('DD-MM-YYYY')+ '</td>';
						row 	+= '<td width="25%">' +day_name+ '</td>';
						row 	+= '<td><input type="checkbox" id="chk_full_'+i+'" name="chk_full" data-toggle="toggle" checked onchange="$.fn.change_balance_leave(\'one\')"></td>';
                        row 	+= '<td><div id="half_option_'+i+'" style="display: none;"><input type="checkbox" id="chk_half_opt_'+i+'" name="chk_half_opt" data-toggle="toggle" checked onchange="$.fn.change_balance_leave(\'one\')"></div></td>';
                        //row 	+= '<td><div class="control-label"><div class="toggle"></div></div><input type="checkbox" id="chk_full" name="chk_full" checked></td>';
						row 	+= '</tr>';
					}
					curr_date = moment(curr_date).add(1, 'days');
					count++;
				}

				$('#tbl_days tbody').append(row);
				if(holiday_str == '')
				{
					$('#div_holidays').html('<h5><span class="text-info"><b>No public holidays in selected date range.</b></span></h5>');
				}
				else
				{
					$('#div_holidays').html(holiday_str);
				}

				for(var j = 0; count > j; j++){
					$('#chk_full_'+j+'').bootstrapToggle({
						on: 'FULL',
						off: 'HALF',
						size: 'small'
					});
                    $('#chk_half_opt_'+j+'').bootstrapToggle({
                        on: 'FIRST',
                        off: 'SECOND',
                        size: 'small'
                    });
				}

				$.fn.avaliable_leave_info();
			}
			else
			{
				$.fn.show_right_error_noty('Start date is more than or equal with end date');
			}

			btn_view_days.stop();
		}
	}
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.avaliable_leave_info = function ()
{
	try
    {
		var type_id		= $('#dd_leave_type').val();

 		var alt_type_id = '';
        if (type_id == AN_LEAVE_ID || type_id == EM_LEAVE_ID)
        {
            type_id = AN_LEAVE_ID;
            alt_type_id = EM_LEAVE_ID;
        }

        if (type_id != '')
        {
            var data =
            {
                type_id: type_id,
                alt_type_id: alt_type_id,
                emp_id: SESSIONS_DATA.emp_id
            };

            $.fn.fetch_data
            (
                $.fn.generate_parameter('get_balance_leave', data),
                function (return_data)
                {
                    if (return_data)
                    {
                        var available_no_of_days = 0;
                        var available_brought_forward = 0;
                        var already_applied_days = 0;
                        var current_applied_days = 0;

                        (return_data.data.leave_entitle.no_of_days) ? available_no_of_days = return_data.data.leave_entitle.no_of_days: available_no_of_days = available_no_of_days;
                        (return_data.data.leave_entitle.brought_forward) ? available_brought_forward = return_data.data.leave_entitle.brought_forward: available_brought_forward = available_brought_forward;
                        (return_data.data.leave_applied.applied_days) ? already_applied_days = return_data.data.leave_applied.applied_days: already_applied_days = already_applied_days;
                        (NOOFDAYS != '') ? current_applied_days = NOOFDAYS: current_applied_days = current_applied_days;

                        var balance_leave = (parseFloat(available_no_of_days) + parseFloat(available_brought_forward)) - (parseFloat(already_applied_days) + parseFloat(current_applied_days));

                        $('#div_available_leave').html(balance_leave.toFixed(1));
						$('#div_balance_leave').html(balance_leave.toFixed(1));

						BALANCE_LEAVE = balance_leave;

						$('#leave_days_info').show();
                    }
                }, true
            );
        }
        else
        {
			$('#div_available_leave').html('n/a');
            $('#div_balance_leave').html('n/a');
        }

	}
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.get_list = function (is_scroll)
{
    try
    {
        var data =
        {
            start_index: RECORD_INDEX,
            limit: LIST_PAGE_LIMIT,
            emp_id: SESSIONS_DATA.emp_id
        };

        if (is_scroll)
        {
            data.start_index = RECORD_INDEX;
        }

        $.fn.fetch_data(
            $.fn.generate_parameter('get_leave_list', data),
            function (return_data)
            {
                if (return_data)
                {
                    if (return_data.data.rec_index)
                    {
                        RECORD_INDEX = return_data.data.rec_index;
                    }

                    $.fn.data_table_destroy();
                    $.fn.populate_list_form(return_data.data.list, is_scroll);
                    $.fn.data_table_features();
                }
            }, true
        );
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.select_all_checkbox = function(status)
{
	try
	{
		$('#leave_form input[id="chk_day"]').each(function() {
			if(status == true)
			{
				this.checked	= true;
			}
			else
			{
				this.checked	= false;
			}
		});
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.init_upload_file = function ()
{

    $.fn.reset_upload_form();

    var $fileupload = $('#fileupload');

    $fileupload.fileupload
    ({
        url: CURRENT_PATH + upload_file_path,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(pdf)$/i,
        maxFileSize: undefined,
        disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        previewMaxWidth: 80,
        previewMaxHeight: 80,
        previewCrop: true,
    });

    $fileupload.bind('fileuploadsubmit', function (e, data)
    {
    	let doc_date 	= moment($('#start_date').val(), 'DD-MM-YYYY');		
		let filename	= $.fn.get_file_name('bill');
		let path		= 'documents/' + doc_date.format('MM') + '_' + doc_date.format('YYYY') + '/' + SESSIONS_DATA.emp_id + '/';
        data.formData =
        {
            upload_path	: path,
            file_name   : filename
        };
    });

    $fileupload.bind('fileuploadadd', function (e, data)
    {
    	$("#files").append
        (
            $('<div></div>')
            .addClass('file-upload new')
            .append('<a href="javascript:void(0)" class="cancel" onclick="$(\'#btn_upload\').show(200);$(this).parent().remove()"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );
    	$('#files').data(data);
    	$("#btn_upload").hide(200);
    	$('#hidden_filename').val(data.files[0].name);
    });
    

    var $fileupload_cert = $('#fileupload_cert');

    $fileupload_cert.fileupload
    ({
        url: CURRENT_PATH + upload_file_path,
        dataType: 'json',
        autoUpload: false,
        acceptFileTypes: /(\.|\/)(pdf)$/i,
        maxFileSize: undefined,
        disableImageResize: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        previewMaxWidth: 80,
        previewMaxHeight: 80,
        previewCrop: true,
    });

    $fileupload_cert.bind('fileuploadsubmit', function (e, data)
    {
		let filename	= $.fn.get_file_name('cert');
		let path		= 'leave_doc/'  + SESSIONS_DATA.emp_id + '/';
        data.formData =
        {
            upload_path	: path,
            file_name   : filename
        };
    });

    $fileupload_cert.bind('fileuploadadd', function (e, data)
    {
    	$("#files_cert").append
        (
            $('<div></div>')
            .addClass('file-upload new')
            .append('<a href="javascript:void(0)" class="cancel" onclick="$(\'#btn_upload_cert\').show(200);$(this).parent().remove()"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
        );
    	$('#files_cert').data(data);
    	$("#btn_upload_cert").hide(200);
    	$('#hidden_filename_cert').val(data.files[0].name);
    });
    
    

};

$.fn.get_file_name = function (type)
{
	var filename = '';
	if(type == 'bill')
	{
		let doc_date 	= moment($('#start_date').val(), 'DD-MM-YYYY');
		let doc_ext		= $('#hidden_filename').val().split('.').pop();
	    let name 		= SESSIONS_DATA.username.trim();
		filename 		= name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();
		filename 		+= '_Medical_Bills_' + doc_date.format('MMMM_YY') + '.' + doc_ext;
	}
	else if(type == 'cert')
	{
		let doc_date 	= moment($('#start_date').val(), 'DD-MM-YYYY');
		let doc_ext		= $('#hidden_filename_cert').val().split('.').pop();
	    let name 		= SESSIONS_DATA.username.trim();
		filename 		= name.charAt(0).toUpperCase() + name.slice(1).toLowerCase();
		filename 		+= '_Medical_Certs_' + doc_date.format('MMMM_YY') + '.' + doc_ext;
	}
	return filename;
};

$.fn.reset_upload_form = function ()
{
    $('#files,#files_cert')						.html('');
    $('#hidden_filename,#hidden_filename_cert')	.val('');
};

$.fn.upload_file = function (callback,type,leave_id, doc_id)
{
	var data;
	if(type == 'bill')
	{
		data = $('#files').data();
	}
	else if(type == 'cert')
	{
		data = $('#files_cert').data();
	}
	if (data.submit)
    {
        data.submit().success(function (result)
        {
    		let doc_data =
            {
    			new_file 	: result.files[0].name.trim(),
                emp_id   	: SESSIONS_DATA.emp_id,
                leave_id  	: leave_id,
                doc_id		: doc_id,
                type 		: type
            }

            $.fn.write_data
            (
                $.fn.generate_parameter('update_leave_attachment_filename', doc_data),
                function(return_data)
                {
                    if(return_data.data)
                    {
                    	if (return_data.data.rec_index)
                        {
                            RECORD_INDEX = return_data.data.rec_index;
                        }
                        $.fn.data_table_destroy();
                        $.fn.populate_list_form(return_data.data.list, false);
                        $.fn.data_table_features();
                    }
                }, false
            );
        });
    }
};

// --- 4 core functions --------------------------------------------------------
$.fn.prepare_form = function ()
{
    try
    {
        $('.populate').select2();
        $.fn.reset_form('form');
        $('#leave_form').parsley
        ({
            successClass: 'has-success',
            errorClass: 'has-error',
            errors:
            {
                classHandler: function (el)
                {
                    return $(el).closest('.form-group');
                },
                errorsWrapper: '<ul class=\"help-block list-unstyled\"></ul>',
                errorElem: '<li></li>'
            }
        });

		$('#start_date').datepicker({dateFormat: 'dd-mm-yy'});
		$('#end_date').datepicker({dateFormat: 'dd-mm-yy'});

		
        $.fn.get_leave_details();
        $.fn.get_list(false);
		$.fn.get_holidays();
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.bind_command_events = function ()
{
    try
    {
        $('#btn_add_leave,#btn_save_time_off').click(function (e)
        {
            e.preventDefault();
            RECORD_INDEX 	= 0;
            btn_add_leave 	= Ladda.create(this);
            btn_add_leave.start();
            $.fn.save_edit_form();
        });

		$('#btn_view_days').click(function (e)
        {
            e.preventDefault();
			btn_view_days = Ladda.create(this);
	 		btn_view_days.start();
            $.fn.view_days();
        });

		$('#chk_all').change( function(e)
		{
			e.preventDefault();
			$.fn.select_all_checkbox($(this).is(':checked'));
		});

        $('#btn_load_more').click(function (e)
        {
            e.preventDefault();
            $.fn.get_list(true);
        });

        $(window).scroll(function ()
        {
            var st = $(this).scrollTop();
            if (st < last_scroll_top)
            {
                $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
            }
            last_scroll_top = st;

            if ($(window).scrollTop() == $(document).height() - $(window).height())
            {
                $('.back-to-top-badge').addClass('back-to-top-badge-visible');
            }
        });
        
        $('#txt_cost, #txt_gst, #txt_roundup').on('change', function(e) 
        {
            e.preventDefault();
            $('#txt_total').val(parseFloat($('#txt_cost').val()) + parseFloat($('#txt_gst').val()) + parseFloat($('#txt_roundup').val()));
        });

        $.fn.init_upload_file();

    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$.fn.form_load = function ()
{
    try
    {
        $.fn.prepare_form();
        $.fn.bind_command_events();
    }
    catch (err)
    {
        $.fn.log_error(arguments.callee.caller, err.message);
    }
};

$(document).ready(function ()
{
    $.fn.form_load();
});
