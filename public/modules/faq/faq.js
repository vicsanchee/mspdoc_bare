var FORM_STATE 			= 0;
var RECORD_INDEX 		= 0;
var SESSIONS_DATA		= '';
var last_scroll_top 	= 0;

var btn_save; FAQ_ID	='';
START = 0; LIMIT = 30;
CURRENT_PATH			= '../../';

$.fn.data_table_features = function()
{
	try
	{
		if (!$.fn.dataTable.isDataTable( '#tbl_list' ) )
		{	
			table = $('#tbl_list').DataTable( {
				"searching": false,
				"paging": false,
				"info":     false,
				"order": [[ 1, "asc" ]]
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

$.fn.set_edit_form = function(data)
{
	FORM_STATE		= 1;
	$('#btn_save')			.html('<i class="fa fa-edit"></i> Edit');
};

$.fn.reset_form = function(form)
{
	try
	{
		FORM_STATE		= 0;
		FAQ_ID			= '';		
		if(form == 'form')
		{				
			$('#txt_question')			.val('');
			$('#txt_answer')			.val('');														
			$('.form-group').each(function () { $(this).removeClass('has-error'); });
			$('.help-block').each(function () { $(this).remove(); });
			$('#btn_save')			.html('<i class="fa fa-save"></i> Save');
		}	
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.show_hide_form = function(form_status)
{		
	if(form_status == 'NEW')
	{
		$('#new_div')					.show(400);
		$('#h4_primary_no')				.text('New FAQ');
	}
	else if(form_status == 'EDIT')
	{
		$('#new_div')					.show(400);
		$('#btn_save')			.html('<i class="fa fa-save"></i> EDIT');
	}
	else if(form_status == 'HIDE')
	{
		$('#new_div')					.hide(400);
	}	
};

$.fn.populate_faq_list_form = function(data)
{
	try
	{	
		if (data)
		{
			$('#tbl_view_list > tbody').empty();
			
			var row			= '';
			var data_val 	= '';
			for(var i = 0; i < data.length; i++)
			{
				data_val = escape(JSON.stringify(data[i]));
				row += 	'<tr>'+
							'<td width="1%"><b>Q' +	(i+1)	+ '.</b></td>' 	+ 
							'<td><b>' + data[i].question	+ '</b> <br />' + data[i].answer	+ '</td>'; 
							
				if(SESSIONS_DATA.is_admin == 1)
				{
					row += 	'<td width="1%"><a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.delete_form(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="Delete data "><i class="fa fa-trash-o"/></a>&nbsp;<a class="tooltips" href="javascript:void(0)" data-value=\'' + data_val + '\' onclick="$.fn.populate_detail_form(unescape($(this).attr(\'data-value\')))" data-trigger="hover" data-original-title="Edit data "><i class="fa fa-pencil"/></a></td>';
				}
				
				row += 	'</tr>';
			}
			$('#tbl_view_list > tbody').html(row);
			$('.back-to-top-badge').removeClass('back-to-top-badge-visible');
		}

	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};


$.fn.populate_detail_form = function(data)
{
	FORM_STATE		= 1;
	$.fn.show_hide_form('EDIT');
	
	try
	{
		var data 	= JSON.parse(data);
		
		var data	= 
		{
			id 					: data.id,
			start_index			: START,
			limit				: LIMIT,
			type_id				: data.type_id,
			question			: data.question,
			answer				: data.answer,	
			is_admin			: SESSIONS_DATA.is_admin,
			emp_id 				: SESSIONS_DATA.emp_id
		};
	 		
	 	$.fn.fetch_data
		(			
			$.fn.generate_parameter('get_faq_edit_details',data),	
			function(return_data)
			{
				if(return_data.data)
				{
					var data 					= return_data.data[0];
					FAQ_ID						= data.id;
					$('#txt_question')			.val((data.question).replace(/<br>/g,"\n"));
					$('#txt_answer')			.val((data.answer).replace(/<br>/g,"\n"));						
				}
			},true
		);

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
				
		var data	= 
		{
			id 					: data.id,
			start_index			: START,
			limit				: LIMIT,
			type_id				: data.type_id,
			question			: data.question,
			answer				: data.answer,	
			is_admin			: SESSIONS_DATA.is_admin,
			emp_id 				: SESSIONS_DATA.emp_id
		};
		
		bootbox.confirm({
			title: "Delete Confirmation",
			message: "Please confirm before you delete.",
			buttons: {
				cancel: {
					label: '<i class="fa fa-times"></i> Cancel'
				},
				confirm: {
					label: '<i class="fa fa-check"></i> Confirm'
				}
			},
			callback: function (result) {
				
				if(result == true)
				{			
					$.fn.write_data
					(
						$.fn.generate_parameter('delete_faq_details', data),	
						function(return_data)
						{
							if(return_data)
							{
								$.fn.show_right_success_noty('Data has been deleted successfully');
								$.fn.populate_faq_list_form(return_data.data.list);					
							}
							
						},false, btn_save
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


$.fn.get_faq_list = function()
{
	try
	{
		var data	= 
		{
			start_index		: START,
			limit			: LIMIT,			
			is_admin		: SESSIONS_DATA.is_admin,
			emp_id			: SESSIONS_DATA.emp_id
	 	};
	 												
	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('view_faq_list',data),	
			function(return_data)
			{
				if(return_data)
				{
					$.fn.populate_faq_list_form(return_data.data.list);
				}
			},true
		);
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
		
		var question = $('#txt_question').val().replace(/\r\n|\r|\n/g,"<br>");
		var answer	 = $('#txt_answer').val().replace(/\r\n|\r|\n/g,"<br>");
						
		var data	= 
		{
			id					: FAQ_ID,			
			start_index			: START,
			limit				: LIMIT,
			question			: question,
			answer				: answer,	
			is_admin			: SESSIONS_DATA.is_admin,
			emp_id 				: SESSIONS_DATA.emp_id,		
	 	}; 		
		
		$.fn.write_data
			(								
				$.fn.generate_parameter('add_edit_faq', data),	
				function(return_data)
				{
					if(return_data.data)
					{						
						$.fn.show_right_success_noty('Data has been recorded successfully');
						$.fn.populate_faq_list_form(return_data.data.list);						
					}
					
				},false, btn_save
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
		
		if(SESSIONS_DATA.is_admin == 1)
		{
			$('#new_btn_div')	.show(400);
		}
		else
		{
			$('#new_btn_div')	.hide(400);
		}

		$.fn.get_faq_list();
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
		$('#btn_hide').click( function(e)
		{
			e.preventDefault();
			$.fn.show_hide_form('HIDE');
		});
		
		$('#btn_new').click( function(e)
		{
			e.preventDefault();
			$.fn.reset_form('form');
			$.fn.show_hide_form('NEW');
		});
		
		$('#btn_reset').click( function(e)
		{
			e.preventDefault();
			$.fn.reset_form('form');
		});
			
		$('#btn_save').click( function(e)
		{
			e.preventDefault();
			btn_save = Ladda.create(this);
	 		btn_save.start();
			$.fn.save_edit_form();
			$.fn.reset_form('form');
			$('#new_div')	.hide(400);	
		});
		
		$(window).scroll(function() 
		{
			var st = $(this).scrollTop();
   			if (st < last_scroll_top)
   			{
   				$('.back-to-top-badge').removeClass('back-to-top-badge-visible');
   			}
   			last_scroll_top = st;
   			
			if($(window).scrollTop() == $(document).height() - $(window).height()) 
			{
				if($('#list_div').is(':visible'))
				{
					$('.back-to-top-badge').addClass('back-to-top-badge-visible');
				}
			}	
		});
		
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};



// START of Document initialization
$(document).ready(function() 
{	
	$.fn.form_load();
		
});
// END of Document initialization