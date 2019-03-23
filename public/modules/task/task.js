/**
 * Script for appointments.php
 * @author: Darus
 */

CURRENT_PATH = '../../';

$.fn.get_new_task_list = function ()
{
    try {
        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_tasks_list', {emp_id : SESSIONS_DATA.emp_id, status : "0"}),
            function (return_data)
            {
                if (return_data.code == 0)
                {
                    $('#list_tasks_new').empty();
                    for (const task of return_data.data)
                    {
                        let due_date = moment(task.due_date);

                        $('#list_tasks_new').append
                        (`
                            <li class='list-group-item task-item open-modal' data-value='${task.id}'>
                                <span class="task-title">${task.title}</span>
                                <div class="task-assign">
                                    <i class="fa fa-user fa-fw" aria-hidden="true"></i>
                                    Assigned to <strong><i>${task.assigned_to_name}</i></strong>
                                </div>
                                <div class="task-date">
                                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                                    ${due_date.format('Do MMMM YYYY')}
                                </div>
                            </li>
                        `);
                    }

                    $('.open-modal').unbind().on('click', function(event) {
                        event.preventDefault();

                        let id = $(this).data('value');
                        $.fn.get_task_by_id(id);
                    });
                }
                else if (return_data.code == 1)
                {
                    // NOTE: Place placeholder in both list
                    $.fn.place_placeholder();
                }
            }, false, '', false, true
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.get_inprogress_task_list = function ()
{
    try {
        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_tasks_list', {emp_id : SESSIONS_DATA.emp_id, status : 1}),
            function (return_data)
            {
                if (return_data.code == 0)
                {
                    $('#list_tasks_progress').empty();
                    for (const task of return_data.data)
                    {
                        let due_date = moment(task.due_date);

                        $('#list_tasks_progress').append
                        (`
                            <li class='list-group-item task-item open-modal-2' data-value='${task.id}'>
                                <span class="task-title">${task.title}</span>
                                <div class="task-assign">
                                    <i class="fa fa-user fa-fw" aria-hidden="true"></i>
                                    Assigned to <strong><i>${task.assigned_to_name}</i></strong>
                                </div>
                                <div class="task-date">
                                    <i class="fa fa-clock-o fa-fw" aria-hidden="true"></i>
                                    ${due_date.format('Do MMMM YYYY')}
                                </div>
                            </li>
                        `);
                    }

                    $('.open-modal-2').unbind().on('click', function(event) {
                        event.preventDefault();

                        let id = $(this).data('value');
                        $.fn.get_task_by_id(id);
                    });
                }
                else if (return_data.code == 1)
                {
                    // NOTE: Place placeholder in both list
                    $.fn.place_placeholder(false);
                }
            }, false, '', false, true
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.get_task_by_id = function(task_id)
{
    try {
        let data = { id: task_id };
        $.fn.fetch_data
        (
            $.fn.generate_parameter('get_tasks_by_id', data),
            function(return_data)
            {
                $.fn.set_modal_edit(return_data.data, function()
                {
                    $('#modal_task').modal('show');     // NOTE: hide modal
                });
            }, false, '', false, true
        );
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
                if (return_data)
                {
                    $('#dd_assign').empty().append('<option value=""></option>')

                    for (let emp of return_data.data)
                    {
                        if (emp.id == SESSIONS_DATA.emp_id)
                        {
                            $('#dd_assign').append(`<option value="${emp.id}">Myself</option>`);
                        }
                        else
                        {
                            $('#dd_assign').append(`<option value="${emp.id}">${emp.name}</option>`);
                        }
                    }
                }
            }, true
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.place_placeholder = function (is_new_task_list = true)
{
    try {
        if (is_new_task_list)
        {
            $('#list_tasks_new').empty().append
            (
                `<a class="list-group-item">
                    <div class="list-placeholder">No New Tasks</div>
                </a>`
            );
        }
        else
        {
            $('#list_tasks_progress').empty().append
            (
                `<a class="list-group-item">
                    <div class="list-placeholder">No Task In Progress</div>
                </a>`
            );
        }
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.add_edit_task = function ()
{
    try
    {
        let date = moment($('#dp_date').val(), 'D-MMM-YYYY');
        let data =
        {
            emp_id      : SESSIONS_DATA.emp_id,
            title       : $('#txt_task').val(),
            descr       : $('#txtarea_descr').val().replace(/"/g,'\\"'),
            assigned_to : $('#dd_assign').val(),
            due_date    : date.format('YYYY-MM-D')
        }
        // NB: .replace(/"/g,'\\"') is used to escape double quotes in JSON String. Did not use JSON.stringify
        // because $.fn.generate_parameter will stringify the whole data but will not stringify the string with
        // double quotes. So need to manually escape the double quotes instead of stringifying the string itself.
        // Stringifying a stringified string will not work.

        let mode = $('#btn_modal_save').data('mode');

        if (mode === 'edit')
        {
            data.id         = $('#btn_modal_save').data('id');
            data.status     = $('#btn_status')    .data('value');
        }

        $.fn.write_data
        (
            $.fn.generate_parameter('add_edit_tasks', data),
            function(return_data)
            {
                if (return_data.code == 0)  // NOTE: Success
                {
                    if ($('#files .file-upload.new').length > 0)
                    {
                        if (mode == 'edit') { $.fn.upload_file(data.id); }
                        else if (mode == 'add') { $.fn.upload_file(return_data.data); }
                    }
                    else
                    {
                        $.fn.get_new_task_list();
                        $.fn.get_inprogress_task_list();
                        $('#modal_task').modal('hide');     // NOTE: hide modal
                    }
                }
                else if (return_data.code == 1)     // NOTE: Failed
                {
                    // TODO: Handle failed ajax
                }
            }
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.set_modal_add = function (callback)
{
    try {
        $('#myModalLabel').html('Create New Task');
        $('#btn_modal_save').html('Create').data('mode', 'add');
        $('.modal-edit').hide();

        $("#files")         .empty();
        $('#txt_task')      .val('');
        $('#txtarea_descr') .val('');
        $('#dd_assign')     .val(SESSIONS_DATA.emp_id).change();
        $('#dp_date')       .val('');

        callback();
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.set_modal_edit = function (data, callback_modal)
{
    try {
        let duedate = moment(data.due_date);

        $("#files")         .empty();
        $('#txt_task')      .val(data.title);
        $('#txtarea_descr') .val(data.descr);
        $('#dd_assign')     .val(data.assigned_to.split(',')).trigger('change');
        $('#dp_date')       .val(duedate.format('D-MMM-YYYY'));
        $.fn.change_status_btn(data.status);

        if (data.files)
        {
            for (const file of data.files)
            {
                $("#files").append
                (
                    $('<div></div>')
                        .addClass('file-upload')
                        .append('<a href class="delete" data-id="' + file.id + '"><i class="fa fa-trash-o fa-fw" aria-hidden="true" title="Delete file"></i></a><a href class="link-view-file" data-path="' + file.filepath + '">' + file.filename + '</a>')
                );
            }

            $('.file-upload .delete').unbind().on('click', function(event)
            {
                event.preventDefault();

                let this_file = $(this);
                let id = this_file.data('id');

                bootbox.confirm
                ({
                    title: "Delete Confirmation",
                    message: "Are you sure to delete this attachment?.",
                    buttons:
                    {
                        cancel:
                        {
                            label: '<i class="fa fa-times"></i> Cancel'
                        },
                        confirm:
                        {
                            label: '<i class="fa fa-check"></i> Yes'
                        }
                    },
                    callback: function (result)
                    {
                        if (result == true)
                        {
                            $.fn.delete_file(id, function()
                            {
                                console.log("[DEBUG] | in callback...");
                                this_file.parent().remove();
                            });
                        }
                    }
                });

            });

            $('.link-view-file').unbind().on('click', function(event)
            {
                event.preventDefault();

                let path = $(this).data('path');

                $.fn.view_file(path);
            });
        }

        // IF not owner
        if (SESSIONS_DATA.emp_id != Number(data.created_by))
        {
            $('#txt_task')      .prop('disabled', 'disabled');
            $('#txtarea_descr') .prop('disabled', 'disabled');
            // $('#dd_assign')     .select2({allowClear: false, placeholder: "Please choose a person"});
            $('#dp_date')       .prop('disabled', 'disabled');
            $('#dd_assign')     .on('select2-removing', function(e)
            {
                e.preventDefault();
            });
        }

        // Finally open the modal
        $('#myModalLabel').html('Update Task');
        $('#btn_modal_save').html('Update').data({'mode' : 'edit', 'id' : data.id});
        $('.modal-edit').show();
        callback_modal();
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.change_status_btn = function (value)
{
    try {
        value = parseInt(value);
        switch (value) {
            case 0:
                $('#btn_status').removeClass('btn-success btn-warning btn-danger').addClass('btn-primary').data('value', 0);
                $('#btn_status_text').html('Open');
                break;
            case 1:
                $('#btn_status').removeClass('btn-primary btn-success btn-danger').addClass('btn-warning').data('value', 1);
                $('#btn_status_text').html('In Progress');
                break;
            case 2:
                $('#btn_status').removeClass('btn-warning btn-primary btn-danger').addClass('btn-success').data('value', 2);
                $('#btn_status_text').html('Completed');
                break;
            case 3:
                $('#btn_status').removeClass('btn-warning btn-success btn-primary').addClass('btn-danger').data('value', 3);
                $('#btn_status_text').html('Cancelled');
                break;
        };
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.upload_file = function (task_id)
{
    try {
        let total_files     = $('#files .file-upload.new').length;
        let total_completed = 0;
        let total_succeed   = 0;
        let failed_file     = [];

        $('#files').data('taskid',task_id);

        $('#files .file-upload.new').each(function(index) {

            let data = $(this).data('data');

            if (data.submit)
            {
                data.submit()
                    .success(function(result, textStatus)
                    {
                        // If the files are successfully uploaded to the server, then save the filename in database
                        let doc_data =
                        {
                            filename : result.files[0].name,
                            emp_id   : SESSIONS_DATA.emp_id,
                            task_id  : task_id
                        }

                        $.fn.write_data
                        (
                            $.fn.generate_parameter('upload_tasks_doc', doc_data),
                            function(return_data)
                            {
                                if(return_data.code == 0)
                                {
                                    total_succeed += 1;
                                }
                            }, false
                        );
                        // if (callback) callback(result.files[0].name);
                    })
                    .complete(function(result, textStatus)
                    {
                        total_completed = 1;
                    })
                    .error(function(result, textStatus)
                    {
                        failed_file.push(result.files[0].name);
                    });
            }
        });

        if (total_completed == total_files && total_succeed != total_files)
        {
            let msg = "Failed to upload some file ...";
            console.error("Upload Error - ", msg);
        }
        else if (total_completed == total_files && total_succeed == 0)
        {
            let msg = "Failed to upload all file";
            console.error("Upload Error - ", msg);
        }

        $.fn.get_new_task_list();
        $.fn.get_inprogress_task_list();
        $('#modal_task').modal('hide');     // NOTE: hide modal
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
};

$.fn.view_file = function (path)
{
    try {
        if (!path)
        {
            $.fn.show_right_error_noty('Document path cannot be empty');
			return;
        }

        window.open(path);
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}

$.fn.delete_file = function (tasks_id, callback)
{
    try {
        let data =
        {
            id : tasks_id
        }

        $.fn.write_data
        (
            $.fn.generate_parameter('delete_tasks_doc', data),
            function(return_data)
            {
                if (return_data.code == 0)
                {
                    callback();
                }
            }, true
        );
    } catch (e) {
        $.fn.log_error(arguments.callee.caller, e.message);
    }
}
//------------------------------------------------------------------------------

$.fn.prepare_form = function ()
{
    try
    {
        // Datepicker
        $('#dp_date').datepicker
        ({
            autoclose: true,
            format   : 'd-M-yyyy'
        });

        // Select2 Dropdown
        $('#dd_assign').select2
        ({
            placeholder : 'Please Select'
        });

        // Fileupload
        $('#fileupload').fileupload
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

        $.fn.get_new_task_list();
        $.fn.get_inprogress_task_list();

        $.fn.get_employee_list();
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
        $('#btn_new_task').on('click', function(event) {
            event.preventDefault();

            $.fn.set_modal_add(function()
            {
                $('#modal_task').modal('show');     // NOTE: hide modal
            });
        });

        // Modal Save Button
        $('#btn_modal_save').on('click', function(e) {
            e.preventDefault();

            $.fn.add_edit_task();
        });

        $('.status-btn').on('click', function(e) {
            e.preventDefault();

            let value = $(this).data('value');
            $.fn.change_status_btn(value);
        });

        // When files are added
        $('#fileupload').bind('fileuploadadd', function(event, data)
        {
            $("#files").append
            (
                $('<div></div>')
                    .addClass('file-upload new')
                    .data({'data': data})
                    .append('<a href class="cancel"><i class="fa fa-times fa-fw" aria-hidden="true"></i></a><span class="">' + data.files[0].name + '</span>')
            );

            $(".file-upload .cancel").unbind().on('click', function(event) {
                event.preventDefault();

                $(this).parent().remove();
            });
        });

        // When files are going to be uploaded
        $('#fileupload').bind('fileuploadsubmit', function(event, data)
        {
            // let date    = moment($('#dp_date').val(), 'D-MMM-YYYY');
            let task_id = $('#files').data('taskid');

            data.formData =
            {
                upload_path : 'tasks_doc/' + task_id + '/',
                file_name   : data.files[0].name
            }
        });

        $('#btn_modal_cancel').click(function(event) {
            event.preventDefault();

            bootbox.confirm
            ({
                title: "Cancel Confirmation",
                message: "Are you sure you want to cancel?",
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
                        $('#modal_task').modal('hide');
                    }
                }
            });
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
