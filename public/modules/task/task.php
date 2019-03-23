<?php
$current_path = '../../';

include $current_path . 'header.php';
?>

<div id="page-content">
    <div id="wrap">
        <div id="page-heading"></div>

        <div class="container-fluid">
            <h2>
                <i class="fa fa-tasks fa-fw"></i>Tasks
            </h2>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-grape">
                        <div class="panel-heading">
                            <h4 class="panel-title">New Task List</h4>
                            <div class="options">
                                <a href="#" class="btn-panel-opts" title="Add New Task" id="btn_new_task">
                                    <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">New Task</span>
                                </a>
                            </div>
                        </div>

                        <ul class="list-group" id="list_tasks_new"></ul>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-orange">
                        <div class="panel-heading">
                            <h4 class="panel-title">In Progress</h4>
                        </div>

                        <ul class="list-group" id="list_tasks_progress"></ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Task Modal -->
<!-- NB: z-index of modal must be overidden inline is using bootstrap-datepicker in order for date picker dropdown to appear -->
<div class="modal fade" id="modal_task" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="z-index: 5;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">Create New Task</h4>
            </div>

            <div class="modal-body">
                <form class="form-horizontal">
                    <!-- Task title -->
                    <div class="form-group">
                        <label class="control-label col-sm-2">Task</label>
                        <div class="col-sm-9">
                            <input type="text" id="txt_task" class="form-control">
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div class="form-group">
                        <label class="control-label col-sm-2">Description</label>
                        <div class="col-sm-9">
                            <textarea id="txtarea_descr" class="form-control" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2">Attachments</label>
                        <div class="col-sm-9">
                            <span class="btn btn-success fileinput-button">
                                <span>Browse File</span>
                                <input id="fileupload" type="file" name="files[]" multiple>
                            </span>
                        </div>
                        <div class="col-sm-9 col-sm-offset-2" id="files"></div>
                    </div>

                    <!-- Assign to -->
                    <div class="form-group">
                        <label class="control-label col-sm-2">Assign to</label>
                        <div class="col-sm-4">
                            <select id="dd_assign" multiple="multiple">
                                <option value=""></option>
                            </select>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div class="form-group">
                        <label for="dp_date" class="control-label col-sm-2">Due date</label>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input id="dp_date" type="text" class="form-control">
                                <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                            </div>
                        </div>
                    </div>

                    <!-- Status -->
                    <div class="modal-edit">
                        <div class="form-group">
                            <label for="" class="control-label col-sm-2">Status</label>
                            <div class="col-sm-4">
                                <div class="btn-group">
                                    <button type="button" id="btn_status" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" name="button">
                                        <span id="btn_status_text" class="text-left">Open</span>
                                        <span class="caret text-right"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-left" role=menu>
                                        <li><a class="status-btn" data-value='0'>Open</a></li>
                                        <li><a class="status-btn" data-value='1'>In Progress</a></li>
                                        <li><a class="status-btn" data-value='2'>Completed</a></li>
                                        <li><a class="status-btn" data-value='3'>Cancelled</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="btn_modal_cancel">Cancel</button>
                <button id="btn_modal_save" data-mode="add" type="button" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</div>
<!-- /Task Modal -->

<?php include $current_path . "footer.php" ?>
