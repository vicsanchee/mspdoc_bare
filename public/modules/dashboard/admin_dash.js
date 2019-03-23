var SESSIONS_DATA	= '';
CURRENT_PATH	= '../../';

$.fn.reset_form = function(form)
{
	try
	{
		
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
		
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.get_appointment_list = function()
{
	try
	{
		var data	=
		{
			emp_id 			: SESSIONS_DATA.emp_id
	 	};

	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_appt_dashboard_list', data),
			function(return_data)
			{
				if(return_data)
				{
					$('#tbl_appt_dashboard > tbody').empty();
					let data 	= return_data.data;
					let row		= '';
					for(let i = 0; i < data.length; i++)
					{
						data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");
						//row += '&nbsp;<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_file(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="View File "><i class="fa fa-picture-o"/></a>';
						
						row += '<tr></td>' +
									'<td>' + data[i].name  	        + '</td>';
						
						if(data[i].appt_follow_up > 0)
						{
							data[i].appt_follow_up = '<span class="label label-primary">' + data[i].appt_follow_up + '</span>';
						}
						if(data[i].appt_took_place > 0)
						{
							data[i].appt_took_place = '<span class="label label-success">' + data[i].appt_took_place + '</span>';
						}
						if(data[i].appt_postponed > 0)
						{
							data[i].appt_postponed = '<span class="label label-warning">' + data[i].appt_postponed + '</span>';
						}
						if(data[i].appt_cancelled > 0)
						{
							data[i].appt_cancelled = '<span class="label label-danger">' + data[i].appt_cancelled + '</span>';
						}
									
						row +='<td>' + data[i].appt_total 			+ '</td>' +
									'<td>' + data[i].appt_open 		+ '</td>' +
									'<td>' + data[i].appt_follow_up	+ '</td>' +
									'<td>' + data[i].appt_took_place+ '</td>' +
									'<td>' + data[i].appt_postponed	+ '</td>' +
									'<td>' + data[i].appt_cancelled	+ '</td>' +
								'</tr>';
					}
					$('#tbl_appt_dashboard tbody').append(row);
				}

			},true
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.get_assets_list = function()
{
	try
	{
		var data	= {};

	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_assets_dashboard_list', data),
			function(return_data)
			{
				if(return_data)
				{
					$('#tbl_asset_dashboard > tbody').empty();
					let data 	= return_data.data.list;
					let row		= '';
					for(let i = 0; i < data.length; i++)
					{
						data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");
						//row += '&nbsp;<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_file(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="View File "><i class="fa fa-picture-o"/></a>';
						
						row += '<tr>' +
									'<td>' + data[i].descr			+ '</td>' +
									'<td>' + data[i].asset_count 	+ '</td>' +
									'<td>RM ' + data[i].asset_val 		+ '</td>' +
								'</tr>';
					}
					$('#tbl_asset_dashboard tbody').append(row);
					
					
					$('#tbl_asset_dashboard_detail > tbody').empty();
					let data1 	= return_data.data.detail;
					let row1		= '';
					for(let i = 0; i < data1.length; i++)
					{
						//data_val = escape(JSON.stringify(data1[i])); //.replace(/'/,"");
						//row += '&nbsp;<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.view_file(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="View File "><i class="fa fa-picture-o"/></a>';
						
						row1 += '<tr>' +
									'<td>' + data1[i].descr			+ '</td>' +
									'<td>' + data1[i].brand_name 	+ '</td>' +
									'<td>RM ' + data1[i].asset_val 	+ '</td>' +
									'<td>' + data1[i].expiry_date 	+ '</td>' +
									'<td>' + data1[i].name 			+ '</td>' +
								'</tr>';
					}
					$('#tbl_asset_dashboard_detail tbody').append(row1);
					
				}

			},true
		);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.get_leave_list = function()
{
	try
	{
		var data	=
		{
			emp_id 			: SESSIONS_DATA.emp_id
	 	};

	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_leave_dashboard_list', data),
			function(return_data)
			{
				if(return_data)
				{
					$('#tbl_leave_dashboard > tbody').empty();
					let data 	= return_data.data;
					let row		= '';
					for(let i = 0; i < data.length; i++)
					{
						data_val = escape(JSON.stringify(data[i])); //.replace(/'/,"");
						
						row += `<tr>
									<td>${data[i].name}</td>
									<td>${data[i].annual_leave}  BF: ( ${data[i].brought_forward} ) / ${data[i].annual_taken}</td>
									<td>${data[i].med_leave} /  ${data[i].med_taken}</td>
									<td>${data[i].emergency_taken}</td>
									<td>${(parseFloat(data[i].annual_taken) + parseFloat(data[i].emergency_taken))}</td>
									<td>${
											(    ( parseFloat(data[i].annual_leave) + parseFloat(data[i].brought_forward) ) - 
												 ( parseFloat(data[i].annual_taken) + parseFloat(data[i].emergency_taken) )
										     )
										 }</td>
									<td>${data[i].unpaid_taken}</td>
								</tr>`;
					}
					$('#tbl_leave_dashboard tbody').append(row);
				}

			},true
		);
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
		$('#daterangepicker').daterangepicker
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
	          startDate	: moment().subtract('days', 29),
	          endDate	: moment()
	        },
	        function(start, end) 
	        {
	            $('#daterangepicker span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
	        }
	    );
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
		
		$('#btn_appointment_more').click( function(e)
		{
			e.preventDefault();
			$('#div_asset_details').hide();
			$('#div_asset_details_expiry').hide();
			$('#div_appointment_details').show();
			
			$.fn.get_appointment_list();
		});
		$('#btn_asset_more').click( function(e)
		{
			e.preventDefault();
			$('#div_appointment_details').hide();
			$('#div_asset_details').show();
			$('#div_asset_details_expiry').show();
			$.fn.get_assets_list();
		});
		
		$('#btn_leave_more').click( function(e)
		{
			e.preventDefault();
			$('#div_appointment_details').hide();
			$('#div_asset_details').hide();
			$('#div_asset_details_expiry').hide();
			$('#div_leave_details').show();
			$.fn.get_leave_list();
		});
		
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

$(document).ready(function()
{

	$.fn.form_load();
});
