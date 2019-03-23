/**
 * Script for taskReport.php
 * @author: Darus
 */

CURRENT_PATH	= '../../';
let LAST_SCROLL_TOP = 0;

$.fn.fetch_tasks_list = function (is_scroll = false)
{
    try {
        let data =
        {
            start_index : is_scroll ? $('#tbody_tasks_list tr').length : 0,
            limit       : LIST_PAGE_LIMIT,
            emp_id      : SESSIONS_DATA.emp_id,
            date_from   : moment().subtract('days', 29),
            date_to     : moment()
        }

        data = $.fn.apply_filter(data);

        $.fn.fetch_data
        (
            $.fn.generate_parameter('fetch_task_report_list', data),
            function(return_data)
            {
                if (return_data.code == 0)
                {
                    if (!is_scroll)
                    {
                        $('#tbody_tasks_list').empty();
                    }

                    let status =
                    [
                        "<span class='label label-primary'>Open</span>",
                        "<span class='label label-warning'>In Progress</span>",
                        "<span class='label label-success'>Completed</span>",
                        "<span class='label label-danger'>Cancelled</span>"
                    ];

                    for (const row of return_data.data.list)
                    {
                        let due_date = moment(row.due_date);

                        $('#tbody_tasks_list').append
                        (`
                            <tr>
                                <td></td>
                                <td>${due_date.format('D MMMM YYYY')}</td>
                                <td>${row.title}</td>
                                <td>${row.assigned_to_name}</td>
                                <td>${status[row.status]}</td>
                            </tr>
                        `);
                    }

                    $('#table_tasks_report').slideDown();
                }
                else if (return_data.code == 1 && is_scroll == false)
                {
                    $('#tbody_tasks_list').empty().append
                    (`
                        <tr>
                            <td colspan="5">
                                <div class="list-placeholder">No records found!</div>
                            </td>
                        </tr>
                    `);
                }
            }, true
        );

    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

/**
 * Function to apply search filters. Add new search filter here
 * @param  {Object} data - original data for 'fetch_task_report_list' without additional filter
 * @return {Object} data - data that has been added filters to be used by 'fetch_task_report_list'
 */
$.fn.apply_filter = function(data)
{
    try {
        if ($('#dd_created_by').val() !== 'all')
        {
            data.created_by = $('#dd_created_by').val();
        }
        if ($('#dd_assigned_to').val() !== 'all')
        {
            data.assigned_to    = $('#dd_assigned_to').val();
        }
        if ($('#dd_status').val() !== 'all')
        {
            data.status      = $('#dd_status').val();
        }
        if ($('#from_date').val())
        {
            data.date_from    = $('#from_date').val();
        }
        if ($('#to_date').val())
        {
            data.date_to      = $('#to_date').val();
        }
        // TODO: Add new search filters here if there's any

        return data;
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.get_employee_list = function ()
{
    try {
        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_emp_list_for_dropdown', {}),
            function (return_data)
            {
                if (return_data.code == 0)
                {
                    $('#dd_created_by, #dd_assigned_to').empty().append('<option value="all">All</option>');
                    $('#dd_created_by, #dd_assigned_to, #dd_status').val("all").change();

                    for (let emp of return_data.data)
                    {
                        $('#dd_created_by, #dd_assigned_to').append(`<option value="${emp.id}">${emp.name}</option>`);
                    }
                }
            }, true
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

// -----------------------------------------------------------------------------

$.fn.prepare_form = function ()
{
    try {
        // NOTE: Initialize 3 select2 dropdown
        $('#dd_created_by, #dd_assigned_to, #dd_status').select2({placeholder: "All"});

        // NOTE: Initialize datepicker for 'Due date'
        $('#dp_date').daterangepicker
	    ({
            ranges:
            {
                'Today'		    : [moment(), moment()],
                'Yesterday'	    : [moment().subtract('days', 1), moment().subtract('days', 1)],
                'Last 7 Days'	: [moment().subtract('days', 6), moment()],
                'Last 30 Days'	: [moment().subtract('days', 29), moment()],
                'This Month'	: [moment().startOf('month'), moment().endOf('month')],
                'Last Month'	: [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
            },
            locale:
            {
                cancelLabel : "Reset"
            },
            startDate            : moment().subtract('days', 29),
            endDate              : moment(),
            showCustomRangeLabel : false,
            autoUpdateInput      : false,
            opens		         : 'left'
	    },
        function(start, end)
        {
			RECORD_INDEX = 0;
            $('#dp_date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            $('#from_date').val(start.format('YYYY-MM-DD'));
			$('#to_date').val(end.format('YYYY-MM-DD'));
	    });
        $('#dp_date span').html(moment().subtract('days', 29).format('MMMM D, YYYY') + ' - ' + moment().format('MMMM D, YYYY'));

        $.fn.get_employee_list();
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.bind_command_events = function ()
{
    try {
        $('#btn_search').on('click', function(e) {
            e.preventDefault();

            $.fn.fetch_tasks_list();
        });

        $('#btn_reset').on('click', function(e) {
            e.preventDefault();

            $('#dd_created_by, #dd_assigned_to, #dd_status').val("all").change();
        });

        $('#btn_load_more').on('click', function(e) {
            e.preventDefault();

            $.fn.fetch_tasks_list(true);
        });

        $(window).scroll(function()
        {
            if($(window).scrollTop() == $(document).height() - $(window).height())
            {
            	$('.back-to-top-badge').addClass('back-to-top-badge-visible');
            }
            else
            {
                $('.back-to-top-badge').removeClass('back-to-top-badge-visible');
            }
        });
    } catch (e) {
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
