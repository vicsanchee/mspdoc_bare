<?php
/**
 *
 *
 * index.php
 *
 * @author: Jamal
 * @version: 1.0 - created
 */
$current_path = '../../';
include $current_path . 'header.php';

$leave_type   = db_query('id,descr','cms_master_list',"category_id = 16");

if($_SESSION['is_admin'])
{
	$emp          = db_query('id,name','cms_employees','is_active = 1');
}
elseif( $_SESSION['is_supervisor'] == 1)
{
	$emp          = db_query('id,name','cms_employees','reporting_to_id = ' . $_SESSION['emp_id'] . ' AND is_active = 1');
}

?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">

        </div>

        <div class="container">
            <h2><i class="fa fa-check-circle-o"></i> Leave  Approval</h2>
			<div class="row" id="list_div">
                <div class="col-md-12">
                    <div class="panel panel-grape">
					    <div class="panel-heading">
						  <h4>Search</h4>
						  <div class="options">
							 <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
						  </div>
					    </div>
    					<div class="panel-body">
                            <form class="form-horizontal" data-validate="parsley" id="detail_form">

                                <div class="form-group">
                                    <label for="doc_date" class="col-sm-1 control-label">Employee</label>
                                    <div class="col-sm-4">

                                    <select id="dd_employee" style="width:100%" class="populate" data-placeholder="Please Select">
                                        <option value="">Please Select</option>
                                        <?php
                                        for($i = 0; $i < count($emp); $i++)
                                        {
                                        ?>
                                            <option value="<?php echo($emp[$i]['id']) ?>"><?php echo($emp[$i]['name']) ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>

                                    </div>
                                    <label for="dd_category" class="col-sm-1 control-label">Date</label>
                                    <div class="col-sm-4">
                                        <button class="btn btn-default" id="doc_date">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="hidden-xs hidden-sm"><?php echo date("F j, Y", strtotime('-30 day')); ?> - <?php echo date("F j, Y"); ?></span> <b class="caret"></b>
                                            <input type="hidden" id="from_date" value="<?php echo date("Y-m-d", strtotime('-30 day')); ?>">
                    						<input type="hidden" id="to_date" value="<?php echo date("Y-m-d"); ?>">
                                        </button>

        							</div>
                                </div>

                                <div class="form-group">
                                    <label for="log_date" class="col-sm-1 control-label">Leave Type</label>
                                    <div class="col-sm-4">
                                        <select id="dd_leave_type" style="width:100%" class="populate" required="required">
            								<option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($leave_type); $i++)
                                            {
                                            ?>
                                                <option value="<?php echo($leave_type[$i]['id']) ?>"><?php echo($leave_type[$i]['descr']) ?></option>
                                            <?php
                                            }
                                            ?>
            							</select>
                                    </div>

                                    <label for="log_date" class="col-sm-1 control-label"></label>
                                    <div class="col-sm-2">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="chk_is_verified" name="chk_is_verified">Verified Leave
                                        </label>
                                    </div>
                                     <div class="col-sm-2">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="chk_is_approved" name="chk_is_approved">Approved Leave
                                        </label>
                                    </div>

                                </div>

                            </form>
                            <p class="pull-right">
                                <div class="btn-toolbar" style="text-align:right">
                                    <a href="#" class="btn btn-default" id="btn_reset"><i class="fa fa-times"></i> Reset</a>
                                    <button class="btn-primary-alt btn" id="btn_search" name="btn_search"><i class="fa fa-search"></i> Search</button>
                                </div>
                            </p>
                        </div>
					</div>
                </div>

                <div class="col-sm-12">
					<div class="panel panel-grape">
						<div class="panel-heading">
							<h4>Leave List</h4>
							<div class="options">
								<a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
							</div>
					  </div>
					  <div class="panel-body">
                            <!--Start PDF icon-->
                            <div class="pull-right">
                                <div class="options">
                                    <div class="btn-toolbar">
                                        <div class="btn-group hidden-xs">
                                            <a href='#' class="btn btn-default dropdown-toggle" data-toggle='dropdown'><i class="fa fa-cloud-download"></i><span class="hidden-xs hidden-sm hidden-md"> Export as</span> <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="exportTable2CSV('Leave','tbl_list')">Excel File (*.xlsx)</a></li>
                                                <li><a href="#" onclick="exportTable2PDF('Leave','tbl_list','p')">PDF File (*.pdf)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <br />
                            <!--End PDF icon-->
						<div class="table-responsive">
							<table class="table" id="tbl_list">
								<thead>
									<tr>
										<th>Name</th>
                                        <th>Start Date</th>
										<th>End Date</th>
                                        <th>Type</th>
                                        <th>Days/Hours</th>
										<th>Reason</th>
                                        <th>Status</th>
                                        <th>Action</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>

                            <div class="load-more" style="display: none;">
                                <a class="btn btn-info btn-xs" id="btn_load_more"><span class="fa fa-arrow-down fa-fw"></span>Load More<span class="fa fa-arrow-down fa-fw"></span></a>
                            </div>
						</div>
					  </div>
					</div>
				</div>

            </div>



               <!--Leave Record Start-->
               <div class="row" id="info_div" style="display: none">

                   <div class="col-sm-12 text-left">
                       <div class="form-group">
                        <a href="#" class="btn btn-default" id="btn_info_back"><i class="fa fa-arrow-left"></i> Back</a>
                       </div>
                   </div>

                   <div class="col-sm-12">
                        <div class="panel panel-grape">
                            <div class="panel-heading">
                                <h4 class="col-sm-6" id="employee_name"></h4>
                                <h4 class="col-sm-5 text-right" id="leave_type"></h4>
                                <div class="options">
                                    <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">

                            	<div class="col-sm-12">
                                    <div class="panel panel-grape">
                                        <div class="panel-body">
                                            <div id="div_leave_summary">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br />

                                <div class="col-sm-12">
                                    <div class="panel panel-grape">
                                        <div class="panel-body">
                                         <form class="form-horizontal" id="leave_form">
                                            <h3 class="text-center">Leave Application Details</h3>
                                            <div class="col-sm-12">
                                            <table  class="table table-bordered table-hover"  id="tbl_leave_record">
                                                <thead>
                                                <tr>
                                                	<th class="table-checkbox">
														<input type="checkbox" class="group-checkable" id="group_checkbox" />
													</th>
                                                    <th>Date</th>
                                                    <th>No. of Days/Hours</th>
                                                    <th>Action</th>
                                					<th>Payment</th>
                                                </tr>
                                                </thead>
                                                <tbody>

                                                </tbody>
                                            </table>
                                            <p class="pull-left">
                                                <div class="btn-toolbar">
                                                    <button class="btn-primary-alt btn" id="btn_apply" name="btn_apply"><i class="fa fa-save"></i> Apply</button>
                                                </div>
                                            </p>
                                        </div>
                                        </form>

                                    </div>
                                    </div>
                                </div>

                        </div>
                   		</div>

                	</div>




                </div>
				<!--Leave Record End-->


        </div> <!-- container -->

    </div> <!--wrap -->
</div> <!-- page-content -->

    <!-- Modal Remark-->
    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            Please confirm the selection and add the remark below.
                        </div>
                        <div class="col-sm-12"><br><br></div>
                        <div class="col-sm-4">
                            Remark:
                        </div>
                        <div class="col-sm-8">
                            <textarea name="leave_remark" id="leave_remark" cols="40" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_verify">Confirm</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Remark List -->
    <div class="modal fade" id="remarkListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Remark List</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div id="remarkList" class="col-sm-12">
                            <table class="table" id="tbl_remark_list">
								<thead>
									<tr>
										<th></th>
										<th>Remark</th>
										<th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Remark Action</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
                        </div>
                        <div class="col-sm-12"><br></div>
                        <div class="col-sm-4">
                            Remark:
                        </div>
                        <div class="col-sm-8">
                            <textarea name="le_remark" id="le_remark" cols="40" rows="3"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_add_remark">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Leave Record -->
    <div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Approval Option</h4>
                </div>
                <div class="modal-body">
                	<div class="row">
                        <div class="col-sm-12">
                            <div class="col-sm-4 text-center">
                                <label class="radio-inline">
                                    <input type="radio" id="option_paid" name="option_approve" value="PAID"> PAID
                                </label>
                            </div>
                            <div class="col-sm-4 text-center">
                                <label class="radio-inline">
                                    <input type="radio" id="option_unpaid" name="option_approve" value="UNPAID"> UNPAID
                                </label>
                            </div>
                            <div class="col-sm-4 text-center">
                                <label class="radio-inline">
                                    <input type="radio" id="option_reject" name="option_approve" value="REJECT"> REJECT
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                	<button class="btn-primary-alt btn" id="btn_approve" name="btn_approve"><i class="fa fa-save"></i> Save </button>
                </div>
            </div>
        </div>
    </div>

<?php include $current_path . "footer.php" ?>
