var controller_URL 		= '../services/services.php';
var upload_file_path	= '../services/upload/index.php';
var CURRENT_PATH		= '';
var loading_image       = "<img src='" + CURRENT_PATH + "./assets/js/images/busy.gif'/>";
var BLOCKUI_CSS			= {border: 'none', padding: '15px', backgroundColor: '#000','-webkit-border-radius': '10px', '-moz-border-radius': '10px',opacity: .5, color: '#fff'};
var TOKEN				= '';
var SESSIONS_DATA		= '';
var MODULE_ACCESS		= '';
var LIST_PAGE_LIMIT		= 10;
var SEARCH_PAGE_LIMIT	= 100;
var TRANS_TIME			= 400;
var UI_DATE_FORMAT		= 'DD-MMM-YYYY';
var resizeTimeout;

$.fn.generate_parameter = function(method,data,additional_param)
{
	try
	{
		if($('#session_data').length > 0 && $('#session_data').val() != '')
		{
			TOKEN = JSON.parse($('#session_data').val()).token;
		}

		var param =
		{
			token		: TOKEN,
			method  	: method,
			data		: data
		};

		if(additional_param)
		{
	        var jsonStrAr = JSON.stringify(additional_param).replace('{','').replace('}','').split('","');
	        for(var v = 0; v < jsonStrAr.length; v++)
	        {
	           var m = jsonStrAr[v].split(':');
	           param[m[0].replace(/"/g,'')] = m[1].replace(/"/g,'');
	        }
	    }

		return JSON.stringify(param);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.show_noty = function(title, text, type, hide)
{
	$.pnotify
	({
		title 	: title,
		text	: text,
		type	: type,
		hide	: hide
	});
};

$.fn.show_right_success_noty = function(text)
{
	$.pnotify
	({
		title 	: 'SUCCESS',
		text	: text,
		type	: 'success',
		hide	: true
	});
};

$.fn.show_right_error_noty = function(text)
{
	$.pnotify
	({
		title 	: 'Opsss',
		text	: text,
		type	: 'error',
		hide	: true
	});
};

$.fn.get_accessibility = function(module_id)
{
	let S_DATA = JSON.parse($('#session_data').val());
	let access = S_DATA.access;
	
	for(i = 0; i < access.length;i++)
	{
		if(parseInt(access[i].module_id) == parseInt(module_id))
		{
			return access[i];
		}
	}
	
	if(module_id == -1)
	{
		return -1;
	}
	return 0;
};

$.fn.get_page_name = function()
{
	let page 	= window.location.pathname.split("/").pop();
	let module 	= window.location.pathname.split("/").slice(-2)[0].trim();

	switch(module.toLowerCase())
	{
	
		case 'contract':
			return 7;
		case 'outbound_document':
			return 16;
		case 'document_archiving':
			return 17;
        case 'service_request':
            return 18;
    	break;
		case 'appointments':
    	case 'profile':
    	case 'faq':
    	case 'dashboard':
    	case 'help':
    		return -1;
    	default:
        	return 0;
   
	}
};

// Start All shared ajax calls
$.fn.handle_return_error_msg = function(data)
{
	try
	{
		var error_msg = 'ERROR RECEIVED NULL';
		if(data.msg != null)
			error_msg = data.msg;
		else if(data.msg)
			error_msg = data.msg;

		$.fn.show_right_error_noty(error_msg);
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

/**
 * Main method of fetching data from backend
 * @method
 * @param  {object}     param         Contains data parameter for fetching
 * @param  {function}   call_back     Callback function to handle the success/failure response
 * @param  {boolean}    show_block_ui Whether to pop up the notification telling the user the fetching process is started
 * @param  {string}     btn_ladda     The ID of ladda button to stop ladda loading
 * @param  {boolean}    call_type     Sync or Async call flag
 * @param  {boolean}    silent_mode   Whether to pop up the notification when the fetch failed
 * @return {object}
 */
$.fn.fetch_data = function(param,call_back,show_block_ui,btn_ladda,call_type,silent_mode)
{
	 try
	 {
	 	var sync_type = true;

	 	if(call_type == false)
	 		sync_type = call_type;

		silent_mode = !!silent_mode;

		 $.ajax
		 ({
	            type		: "POST",
	            dataType	: 'json',
	            contentType : 'application/json',
	            url			: CURRENT_PATH + controller_URL,
	            data		: param,
	            async		: sync_type,
	            success		: function(data)
	            {
	      			if($.fn.is_success(data.code) == false && data.msg !== '')
					{
						console.log(silent_mode);
						if(silent_mode == false)
						{
							$.fn.handle_return_error_msg(data);
						}
					}
					call_back(data);
	            },
	            error 		: function()
	            {
	            	Ladda.stopAll();
	            	$.unblockUI();
	            	alert('Resource is not available. Please try again later. One or more of the services on which we depend is unavailable. Please try again later after the service has had a chance to recover.');
	            },
	            beforeSend	: function()
	            {
	            	if(show_block_ui == true)
	            	{
	            		$.blockUI({message: "<div class='circle'/><div class='circle1'/>  Just a moment...", css : BLOCKUI_CSS});
	            	}
			    },
			    complete	: function()
			    {
			    	if(btn_ladda)
			    		btn_ladda.stop();

			    	$.unblockUI();
   				}
	     });
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.write_data = function(param,call_back,show_block_ui,btn_ladda,call_type)
{
	 try
	 {
	 	 var sync_type = true;

	 	 if(call_type == false)
	 		sync_type = call_type;

		 $.ajax
		 ({
	            type		: "POST",
	            dataType	: 'json',
	            contentType : 'application/json',
	            url			: CURRENT_PATH + controller_URL,
	            data		: param,
	            async		: sync_type,
	            success		: function(data)
	            {
	            	if($.fn.is_success(data.code) == false)
	            	{
	      				$.fn.handle_return_error_msg(data);
	      				call_back(data);
	      			}
	      			else
						call_back(data);
	            },
	            error 		: function()
	            {
	            	Ladda.stopAll();
	            	$.unblockUI();
	            	alert('Resource is not available. Please try again later. One or more of the services on which we depend is unavailable. Please try again later after the service has had a chance to recover.');
	            },
	            beforeSend	: function()
	            {
	            	if(show_block_ui == true)
	            	{
	            		$.blockUI({message: "<div class='circle'/><div class='circle1'/>   Just a moment...", css : BLOCKUI_CSS});
	            	}
			    },
			    complete	: function()
			    {
			    	if(btn_ladda)
			    		btn_ladda.stop();

			    	$.unblockUI();
   				}
	     });
	 }
	 catch(err)
	 {
		$.fn.log_error(arguments.callee.caller,err.message);
	 }
};

$.fn.fetch_data_for_table = function(param,call_back,is_scroll,table_id,show_block_ui,btn_ladda,call_type,silent_mode)
{
	 try
	 {
	 	var sync_type = true;

	 	if(call_type == false)
	 		sync_type = call_type;

		silent_mode = !!silent_mode;

		 $.ajax
		 ({
	            type		: "POST",
	            dataType	: 'json',
	            contentType : 'application/json',
	            url			: CURRENT_PATH + controller_URL,
	            data		: param,
	            async		: sync_type,
	            success		: function(data)
	            {
	      			if($.fn.is_success(data.code) == false)
					{
						if(silent_mode == false)
						{
							$.fn.handle_return_error_msg(data);
						}
						$('#' + table_id + ' #table_list-loader').remove();
						if (!is_scroll)
                        {
                            $('#div_load_more').hide();
                            $('#' + table_id + ' > tbody').empty();
                            let no_record = '<tr><td colspan="' + $("#" + table_id).find('tr')[0].cells.length + '"><div class="list-placeholder">No Records Found</div></td></tr>';
                            $('#' + table_id + ' tbody').append(no_record);
                        }
                        else if (is_scroll)
                        {
                        	
                            $('#div_load_more').hide();
                            let no_record = '<tr><td colspan="' + $("#" + table_id).find('tr')[0].cells.length + '"><div class="list-placeholder">No More Records To Be Loaded</div></td></tr>';
                            $('#' + table_id + ' tbody').append(no_record);
//                            console.log(no_record);
                        }
					}
					else
					{
						if(data.data.list.length !== 0)
						{
							$('#' + table_id + ' #table_list-loader').remove();
    						call_back(data.data, is_scroll);
    						if ($.isFunction($.fn.data_table_features)) $.fn.data_table_features();
	                    }
	                    else
	                    {
		                    if (!is_scroll)
	                        {
	                            $('#div_load_more').hide();
	                            $('#' + table_id + '  tbody').empty();
	                            let no_record = '<tr><td colspan="' + $("#" + table_id).find('tr')[0].cells.length + '"><div class="list-placeholder">No Records Found</div></td></tr>';
	                            $('#' + table_id + ' tbody').append(no_record);
	                        }
	                        else if (is_scroll)
	                        {
	                        	
	                            $('#div_load_more').hide();
	                            $('#' + table_id + ' #table_list-loader').remove();
	                            let no_record = '<tr><td colspan="' + $("#" + table_id).find('tr')[0].cells.length + '"><div class="list-placeholder">No More Records To Be Loaded</div></td></tr>';
	                            $('#' + table_id + ' tbody').append(no_record);
//	                            console.log('here');
	                        }
	                  	}
					}
	            },
	            error 		: function()
	            {
	            	Ladda.stopAll();
	            	$.unblockUI();
	            	alert('Resource is not available. Please try again later. One or more of the services on which we depend is unavailable. Please try again later after the service has had a chance to recover.');
	            },
	            beforeSend	: function()
	            {
	            	if(show_block_ui == true)
	            	{
	            		$.blockUI({message: "<div class='circle'/><div class='circle1'/>  Just a moment...", css : BLOCKUI_CSS});
	            	}
	            	let progress = '<tr id="table_list-loader"><td colspan="' + $("#" + table_id).find('tr')[0].cells.length + '"><div class="list-loader"><div class="spinner"></div><span>Fetching Data...</span></div></td></tr>';
	            	$('#' + table_id + ' > tbody').append(progress);
	            	
			    },
			    complete	: function()
			    {
			    	if(btn_ladda)
			    		btn_ladda.stop();

			    	$.unblockUI();
   				}
	     });
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

// End All shared ajax calls

// START Shared function
$.fn.log_error = function(routine_name,error_msg)
{
	Ladda.stopAll();
	$.unblockUI();
	alert('Error Occur at : ' + routine_name + ' with error msg : ' + error_msg, 'Complaint Management');
};

$.fn.is_success = function(code)
{
	if(code == '0')
		return true;
	else
		return false;
};

$.fn.core_data_table_features = function()
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

$.fn.remove_table_row = function(table_row_id)
{
	try
	{
		$('#' + table_row_id).hide('slow', function() { $('#' + table_row_id).remove(); });
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.get_json_string = function(data) 
{
    try 
    {
    	var json_data = false;
        json_data = JSON.parse(data);
    } 
    catch (e) 
    {
        return false;
    }
    return json_data;
};

// END Shared function

$(document).ready(function()
{
	try
	{
		if($('#session_data').length > 0)
		{
			SESSIONS_DATA = JSON.parse($('#session_data').val());
			MODULE_ACCESS = $.fn.get_accessibility($.fn.get_page_name());
			if(MODULE_ACCESS == 0 || MODULE_ACCESS.view_it == 0)
			{
				window.location.href = CURRENT_PATH  + 'login.php';
			}
		}
	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
});
