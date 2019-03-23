<?php
/**
 * @author  Darus
 *
 */
 $current_path = '../../';
include $current_path . 'header.php';
?>

<div id="page-content">
    <div id="wrap">
        <div id="page-heading"></div>

        <div class="container-fluid">
            <h2><i class="fa fa-tasks fa-fw" aria-hidden="true"></i>Tasks report</h2>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-grape">
                        <div class="panel-heading">
                            <h4 class="panel-title"><i class="fa fa-search fa-fw" aria-hidden="true"></i>Search Filter</h4>
                            <div class="options">
                                <a href="" class="panel-collapse"><i class="fa fa-chevron-up"></i></a>
  						    </div>
                        </div>

                        <div class="panel-body">
                            <form class="form-horizontal" id="form_search_tasks" data-validate="parsley">
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <!-- Created by dropdown -->
                                        <label for="dd_created_by" class="col-sm-4 control-label">Created by</label>
                                        <div class="col-sm-8 sm-margin-btm-20 xs-margin-btm-10">
                                            <select id="dd_created_by">
                                            </select>
                                        </div>

                                        <!-- Assigned to dropdown -->
                                        <label for="dd_assigned_to" class="col-sm-4 control-label">Assigned to</label>
                                        <div class="col-sm-8 sm-margin-btm-20 xs-margin-btm-10">
                                            <select id="dd_assigned_to" data-placeholder="All">
                                            </select>
                                        </div>

                                        <!-- Status dropdown -->
                                        <label for="dd_status" class="col-sm-4 control-label">Status</label>
                                        <div class="col-sm-8 sm-margin-btm-20 xs-margin-btm-10">
                                            <select id="dd_status">
                                                <option value="all">All</option>
                                                <option value="0">Open</option>
                                                <option value="1">In Progress</option>
                                                <option value="2">Completed</option>
                                                <option value="3">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <label for="dp_date" class="col-sm-2 control-label xs-margin-top-10">Due date</label>
                                        <div class="col-sm-4">
                                            <button class="btn btn-default" id="dp_date">
                                                <i class="fa fa-calendar-o fa-fw"></i>
                                                <span class="hidden-xs hidden-sm"></span>
                                                <b class="caret"></b>
                                                <input type="hidden" id="from_date" value="">
                                                <input type="hidden" id="to_date" value="">
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <div class="btn-toolbar text-right">
                                    <button type="button" class="btn btn-default" id="btn_reset"><i class="fa fa-times"></i> Reset</button>
                                    <button type="button" class="btn btn-primary-alt" id="btn_search"><i class="fa fa-search fa-fw"></i>Search</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <a href="#" class="back-to-top-badge" id="btn_load_more"><i class="fa fa-arrow-down"></i>Load More</a>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4 class="panel-title">Tasks List</h4>
                        </div>

                        <div class="table-responsive white-bg" id="table_tasks_report">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>Due Date</th>
                                        <th>Task</th>
                                        <th>Assigned to</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody_tasks_list">
                                    <tr>
                                        <td colspan="5">
                                            <div class="list-placeholder">
                                                There's nothing here yet...
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $current_path . "footer.php" ?>
