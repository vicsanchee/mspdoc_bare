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

$curr_year 	=  date("Y");

$emp_leave_type		= db_query('cms_master_list.id as id,cms_master_list.descr as descr,cms_emp_leave.no_of_days','cms_emp_leave INNER JOIN cms_master_list ON
             cms_emp_leave.master_list_id = cms_master_list.id','cms_emp_leave.emp_id = '.$_SESSION['emp_id'].' AND cms_emp_leave.applicable_year = ' . $curr_year . ' AND cms_emp_leave.is_active = 1');

$expenses         	= db_query('id,descr','cms_master_list','id = 195 AND is_active = 1');


?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h2><i class="fa fa-plane fa-fw"></i>Leave Application</h2>
        </div>

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-primary">
                        <div class="panel-body">

                            <div id="div_leave_summary">

                            </div>

                        </div>
                    </div>
                </div>
            </div>

			<div class="row">

                <div class="col-md-12">
                    <div class="panel panel-grape">
    					<div class="panel-body">

                            <form class="form-horizontal" data-validate="parsley" id="leave_form">

                                <div class="form-group">
                                    <label for="dd_leave_type" class="col-sm-2 control-label">Leave Type :</label>
                                    <div class="col-sm-4">
                                        <select id="dd_leave_type" style="width:100%" class="populate" required="required" onchange="$.fn.view_file_status(this.value)">
                                            <option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($emp_leave_type); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($emp_leave_type[$i]['id']) ?>"><?php echo($emp_leave_type[$i]['descr']) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <label for="txt_reason" class="col-sm-2 control-label">Reason :</label>
                                    <div class="col-sm-4">
                                        <input type="text" class="form-control marginBottom10px" id="txt_reason" placeholder="Required Field"  required="required">
                                    </div>

                                </div>

                                <div>
                                    <div class="form-group">

                                        <label for="start_date" class="col-sm-2 control-label">Start Date :</label>
                                        <div class="col-sm-4">
                                            <div class="input-group">
                                                <input type="text" id="start_date" class="form-control" data-date-format="dd-mm-yyyy"  placeholder="e.g dd-mm-yyyy" required="required">
                                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                            </div>
                                        </div>
                                        <div id="leave_end_date">
                                            <label for="end_date" class="col-sm-2 control-label">End Date :</label>
                                            <div class="col-sm-4">
                                                <div class="input-group">
                                                    <input type="text" id="end_date" class="form-control" data-date-format="dd-mm-yyyy"  placeholder="e.g dd-mm-yyyy" required="required">
                                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="leave_time_off">
                                            <label for="dd_leave_time_off" class="col-sm-2 control-label">Leave Type :</label>
                                            <div class="col-sm-4">
                                                <select id="dd_leave_time_off" style="width:100%" class="populate">
                                                    <option value="1" selected>1 Hour</option>
                                                    <option value="2">2 Hours</option>
                                                    <option value="3">3 Hours</option>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-group">

                                        <div id="leave_days_check">
                                            <label for="chk_weekend" class="col-sm-2 control-label">Allow Days :</label>
                                            <div class="col-sm-4" style="padding-top:5px;">
                                                <div class="col-sm-1">
                                                    <input type="checkbox" id="chk_allow_weekend" name="chk_allow_weekend">
                                                </div>
                                                <div class="col-sm-5">
                                                    <b>Weekend Days.</b>
                                                </div>
                                                <div class="col-sm-1">
                                                    <input type="checkbox" id="chk_allow_holiday" name="chk_allow_holiday">
                                                </div>
                                                <div class="col-sm-5">
                                                    <b>Public Holidays.</b>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div id="leave_file" style="display:none">
                                            
                                            <label for="fileupload" class="col-sm-2 control-label">File :</label>
                                            <div class="col-sm-2">
                                                <span class="btn btn-success fileinput-button" id="btn_upload" style="width: 100%">
                                                    <span>Medical Bill</span>
                                                    <input id="fileupload" type="file" name="files[]">
                                                </span>
                                                <br/>
                                                <div id="files" class="files" style="margin-top: 8px"></div>
                                                <input type="hidden" id="hidden_filename">
                                            </div>
                                            <div class="col-sm-2">
                                                <span class="btn btn-success fileinput-button" id="btn_upload_cert" style="width: 100%">
                                                    <span>Medical Cert</span>
                                                    <input id="fileupload_cert" type="file" name="files[]">
                                                </span>
                                                <br/>
                                                <div id="files_cert" class="files" style="margin-top: 8px"></div>
                                                <input type="hidden" id="hidden_filename_cert">
                                            </div>
                                            
                                        </div>  
                                          
									</div>
									
									<div id="leave_file_cost" style="display:none">
										<div class="form-group">
	                                        <label class="col-sm-2 control-label">Cost</label>
	                                        <div class="col-sm-4">
	                                        	<div class="input-group">
		                                            <div class="input-group-addon">RM</div>
		                                            <input type="number" min="0.00" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_cost" required>
	                                        	</div>
	                                        </div>
	                                        <label class="col-sm-2 control-label">SST / GST</label>
	                                        <div class="col-sm-4">
	                                        	<div class="input-group">
		                                            <div class="input-group-addon">RM</div>
		                                            <input type="number" min="0.00" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_gst" required>
	                                        	</div>
	                                        </div>
										</div>
										
										<div class="form-group">
	                                        <label class="col-sm-2 control-label">Round Up</label>
	                                        <div class="col-sm-4">
	                                        	<div class="input-group">
		                                            <div class="input-group-addon">RM</div>
		                                            <input type="number" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_roundup" required>
	                                        	</div>
	                                        </div>
	                                        <label class="col-sm-2 control-label">Total</label>
	                                        <div class="col-sm-4">
	                                        	<div class="input-group">
		                                            <div class="input-group-addon">RM</div>
		                                            <input type="number" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_total" required readonly="readonly">
	                                        	</div>
	                                        </div>
		                               	</div>
	     
	
			                            <div class="form-group">  
	                                        <label class="col-sm-2 control-label">Nature of Expenses</label>
	                                        <div class="col-sm-4">
		                                        <div class="col-sm-12 input-group">
			                                        <select id="dd_expenses" style="width:100%" class="populate" >
			            								
			            								<?php
			                                            for($i = 0; $i < count($expenses); $i++)
			                                            {
			                                            ?>
			                                                <option value="<?php echo($expenses[$i]['id']) ?>" selected="selected"><?php echo($expenses[$i]['descr']) ?></option>
			                                            <?php
			                                            }
			                                            ?>
			            							</select>
			            						</div>   
	                                        </div>
		                                    <div class="col-sm-6">
		                                    </div>
				                        </div>            
			                        </div>
			                        
			                                
                                    <div class="form-group">

                                        <div class="pull-right">
                                            <div class="col-sm-12" style="text-align:right">
                                                <button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_view_days">View Leave Days</button>
                                                <button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_save_time_off"><i class="fa fa-save"></i> Save</button>
                                            </div>
                                        </div>

                                    </div>
                                    
                                    
                                </div>


                                <div id="leave_days_info" class="col-sm-12">
                                	<table width="100%" class="table">
                                     	<tbody>
                                        <tr>
                                            <td width="50%"><h2><span class="col-sm-12 label label-default">Available Leave : <span id="div_available_leave">n/a</span></span></h2></td>
                                            <td width="50%"><h2><span class="col-sm-12 label label-default">Balance Leave : <span id="div_balance_leave">n/a</span></span></h2></td>
                                        </tr>
                                        </tbody>
                                    </table>
									<div class="col-sm-9">
                                        <table id="tbl_days" width="100%" class="table table-hover table-condensed">
                                            <thead>
                                                <th><input type="checkbox" id="chk_all" name="chk_all" onchange="$.fn.change_balance_leave('all')"></th>
                                                <th>DATE</th>
                                                <th>DAY</th>
                                                <th>DURATION</th>
                                                <th>&nbsp;</th>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-sm-3">
                                    	<div class="alert alert-info">
                                    		<h3>Public Holidays</h3>
                                            <p id="div_holidays">

                                            </p>
                                            <a href="https://www.onestopmalaysia.com/holidays-<?php echo(date('Y')); ?>.html" target="_blank">Click here</a> to view all the public holidays.
                                       </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="form-group">
                                            <div class="pull-right">
                                                <div class="btn-toolbar" style="text-align:right">
                                                    <button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_add_leave"><i class="fa fa-save"></i> Save</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                           	</form>

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
										<th></th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Applied Date</th>
                                        <th>Type</th>
                                        <th>Days/Hours</th>
										<th>Reason</th>
                                        <th>Verification</th>
                                        <th>Leave Record</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					  </div>
					</div>
					<a href="#" class="back-to-top-badge" id="btn_load_more"><i class="fa fa-arrow-down"></i>Load More</a>
				</div>

            </div>

        </div> <!-- container -->

    </div> <!--wrap -->
</div> <!-- page-content -->


    <!-- Modal Leave Record -->
    <div class="modal fade" id="leaveRecordModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Leave Record</h4>
                </div>
                <div class="modal-body">
                	<table class="table" id="tbl_leave_record">
                        <thead>
                            <tr>
                            	<th>Date</th>
                                <th>Days/Hours</th>
                                <th>Action</th>
                                <th>Payment</th>
							</tr>
						</thead>
						<tbody>

						</tbody>
					</table>
                    <br>
                    <h5>Remarks :</h5>
                    <table class="table" id="tbl_remark_list">
						<tbody>

						</tbody>
					</table>
                </div>
                <div class="modal-footer">
                </div>
            </div>
        </div>
    </div>



<?php include $current_path . "footer.php" ?>
