var RECORD_INDEX 	= 0;
var LIST_PAGE_LIMIT = 1000;
var SESSIONS_DATA	= '';
var last_scroll_top = 0;
var btn_search;


CURRENT_PATH	= '../../';

$.fn.data_table_features = function()
{
	try
	{
		if (!$.fn.dataTable.isDataTable( '#tbl_list' ) )
		{
			table = $('#tbl_list').DataTable
			({
				"searching"	: false,
				"paging"	: false,
				"info"		: false,
				"order"		: [[ 1, "desc" ]]
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


$.fn.get_list = function()
{
	try
	{
		var data	=
		{
			employee_id		: $('#dd_employee').val(),
			type_id			: $('#dd_leave_type').val(),
			from_date		: $('#from_date').val(),
			to_date			: $('#to_date').val(),
			paid			: $('#chk_is_paid').is(':checked') 	? 1 : 0,
			unpaid			: $('#chk_is_unpaid').is(':checked') 	? 1 : 0,
			emp_id			: SESSIONS_DATA.emp_id
	 	};

	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_leave_report',data),
			function(return_data)
			{
				if(return_data)
				{
					$('#tbl_list > tbody').empty();
					if(return_data.code == 0)
					{
						$('#div_report_view').show();
					}
					else
					{
						$('#div_report_view').hide();
					}
					$.fn.pouplate_list(return_data.data);
				}
			},true, btn_search
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.pouplate_list = function(data)
{
	try
	{
		if (data)
		{
			var row			= '';
			var data_val 	= '';
			var employee	= 0;
			var count		= 1;
			var current_date = new Date();

			for(var i = 0; i < data.length; i++)
			{
                let date = moment(data[i].leave_date, 'DD-MM-YYYY');

				data_val = escape(JSON.stringify(data[i]));
				
				if(data[i].reason == null)
				{
					data[i].reason = '-';
				}
				if(data[i].annual_leave_taken == null)
				{
					data[i].annual_leave_taken = 0;
				}
				if(data[i].medical_leave_taken == null)
				{
					data[i].medical_leave_taken = 0;
				}
				if(data[i].unpaid_leave_taken == null)
				{
					data[i].unpaid_leave_taken = 0;
				}

				var day_info = '';
				if(data[i].leave_no_of_days == 0.5)
				{
					day_info = '<br /><b>(1/2 day)</b>';
					if(data[i].type_id == 55)
					{
						day_info += '<b> (EL)</b>';
					}
				}
				else
				{
					day_info = '';
					if(data[i].type_id == 55)
					{
						day_info = '<br /><b>(EL)</b>';
					}
				}

				row += '<tr>';
				if(employee	!= data[i].employee_id)
				{
					count = 1;
					row += '<td>' + data[i].name	+ '</td>';
				}
				else
				{
					row += '<td></td>';
				}
				if(data[i].paid != 0)
				{
					if(data[i].type_id != 48)
					{
						row += '<td class="text-center">' + date.format('D-MMM-YYYY') + '' + day_info	+ '<br/>PAID</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
					}
					else
					{
						row += '<td>&nbsp;</td>';
						row += '<td class="text-center">' + date.format('D-MMM-YYYY') + '' + day_info	+ '</td>';
						row += '<td>&nbsp;</td>';
					}
				}
				else
				{
					row += '<td>&nbsp;</td>';
					row += '<td>&nbsp;</td>';
					row += '<td class="text-center">' + date.format('D-MMM-YYYY') + '' + day_info	+ '<br/>UNPAID</td>';
				}
				/*if(employee	!= data[i].employee_id)
				{
					row += '<td class="text-center">' + parseFloat(data[i].annual_leave_taken).toFixed(1)	+ '</td>';
					row += '<td class="text-center">' + ((parseFloat(data[i].annual_leave_entitle)+parseFloat(data[i].brought_forward))-parseFloat(data[i].annual_leave_taken)).toFixed(1)	+ '</td>';
					row += '<td class="text-center">' + parseFloat(data[i].medical_leave_taken).toFixed(1)	+ '</td>';
					row += '<td class="text-center">' + (parseFloat(data[i].medical_leave_entitle)-parseFloat(data[i].medical_leave_taken)).toFixed(1)	+ '</td>';
					row += '<td class="text-center">' + parseFloat(data[i].unpaid_leave_taken).toFixed(1)	+ '</td>';
				}
				else
				{
					row += '<td></td>';
					row += '<td></td>';
					row += '<td></td>';
					row += '<td></td>';
					row += '<td></td>';
				}*/
				row += '<td>&nbsp;</td>';
				row += '<td>&nbsp;</td>';
				row += '<td>&nbsp;</td>';
				row += '</tr>';

				if(count == data[i].day_count)
				{
					row += '<tr>';
					row += '<td class="text-center"><b>Total Summary(' + current_date.getFullYear()	+ ')</b></td>';
					row += '<td class="text-center">Entitle: ' + parseFloat(data[i].annual_leave_entitle).toFixed(1) + ' B/F: ' + data[i].brought_forward +  '</td>';
					row += '<td class="text-center">AL Taken: ' + parseFloat(data[i].annual_leave_taken).toFixed(1)	+ '</td>';
					row += '<td class="text-center">AL Balance: ' + ((parseFloat(data[i].annual_leave_entitle)+parseFloat(data[i].brought_forward))-parseFloat(data[i].annual_leave_taken)).toFixed(1)	+ '</td>';
					row += '<td class="text-center">MC Taken: ' + parseFloat(data[i].medical_leave_taken).toFixed(1)	+ '</td>';
					row += '<td class="text-center">MC Balance: ' + (parseFloat(data[i].medical_leave_entitle)-parseFloat(data[i].medical_leave_taken)).toFixed(1)	+ '</td>';
					row += '<td class="text-center">Unpaid Taken: ' + parseFloat(data[i].unpaid_leave_taken).toFixed(1)	+ '</td>';
					row += '</tr>';

					if(data.length != (i+1))
					{
						row += '<tr>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '<td>&nbsp;</td>';
						row += '</tr>';

						row += '<tr>';
						row += '<th>Name</th>';
						row += '<th>AL Date</th>';
                        row += '<th>MC Date</th>';
                        row += '<th>Unpaid Date</th>';
                        row += '<th></th>';
                        row += '<th></th>';
                        row += '<th></th>';
						row += '</tr>';
					}
				}

				employee = data[i].employee_id;
				count++;

			}
			$('#tbl_list tbody').append(row);
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}

};





$.fn.prepare_form = function()
{
	try
	{
		$('.populate').select2();
		$('#search_form').parsley
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

		$('#leave_date span').html(moment().startOf('year').format('MMMM D, YYYY') + ' - ' + moment().endOf('year').format('MMMM D, YYYY'));
		$('#from_date').val(moment().startOf('year').format('YYYY-MM-DD'));
		$('#to_date').val(moment().endOf('year').format('YYYY-MM-DD'));

		$('#leave_date').daterangepicker
	    (
	        {
	          ranges:
	          {
	             'Today'		: [moment(), moment()],
	             'Yesterday'	: [moment().subtract('days', 1), moment().subtract('days', 1)],
	             'Last 7 Days'	: [moment().subtract('days', 6), moment()],
	             'Last 30 Days'	: [moment().subtract('days', 29), moment()],
	             'This Month'	: [moment().startOf('month'), moment().endOf('month')],
	             'Last Month'	: [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
	          },
	          opens		: 'left',
	          startDate	: moment().startOf('year'),
	          endDate	: moment().endOf('year')
	        },
	        function(start, end)
	        {
				RECORD_INDEX = 0;
	            $('#leave_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	            $('#from_date').val(start.format('YYYY-MM-DD'));
				$('#to_date').val(end.format('YYYY-MM-DD'));
				//$.fn.get_list(false);
	        }
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
	    SESSIONS_DATA = JSON.parse($('#session_data').val());
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
		$('#btn_search').click( function(e)
        {
            e.preventDefault();
			RECORD_INDEX = 0;
			btn_search = Ladda.create(this);
	 		btn_search.start();
            $.fn.get_list();
        });
    }
    catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};


$(document).ready(function()
{
	$.fn.form_load();
});
