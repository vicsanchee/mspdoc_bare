<?php
/**
 * appointments.php
 *
 * @author: Darus
 * @version: 2.4 - created
 */
$current_path = '../../';
include $current_path . 'header.php';

$category   = db_query('id,descr','cms_master_list',"category_id = 24");
$appt_status= db_query('id,descr','cms_master_list',"category_id = 28");

$sql 		= "SELECT cms_appt_pic.id,cms_appt_pic.name,cms_appt_pic.email, cms_clients.name as company_name
				FROM cms_appt_pic INNER JOIN cms_clients ON cms_appt_pic.client = cms_clients.id
				WHERE cms_appt_pic.created_by = " . $_SESSION['emp_id'] . " 
				 UNION ALL
				SELECT cms_employees.id, cms_employees.name, cms_employees.office_email as email,
				cms_master_employer.employer_name  as company_name
				FROM
				cms_employees INNER JOIN cms_master_employer ON cms_employees.employer_id = cms_master_employer.id
				WHERE cms_employees.is_active = 1";

$to   		= db_execute_custom_sql($sql);
$template	= db_query('template_content','cms_master_template',"id = 1");

?>

<div id="page-content">
    <div id="wrap">
        <div id="page-heading">
            <h2><i class="fa fa-calendar fa-fw"></i>Appointments</h2>

            <div class="options">
                <div class="btn-toolbar">
                    
                </div>
            </div>
        </div>

        <div class="container-fluid">
            <div class="row view-1" id="div_list">
                <div class="col-sm-12">
                    <div class="panel panel-grape">
                        <div class="panel-heading">
                            <h4 class="panel-title">Active Appointments</h4>
                            <div class="options">
                                <a href="#" class="btn-panel-opts" title="Add New Appointment" id="btn_new_appt">
                                    <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">New Appointment</span>
                                </a>
                            </div>
                        </div>

                        <div class="list-group" id="list_inc_appt">
                            <div class="list-group-item">
                                <div class="list-loader">
                                    <div class="spinner"></div>
                                    <span>Fetching Data...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row view-1" id="div_list1">
                <div class="col-sm-12">
                    <div class="panel panel-grape" style="background-color: #fff;">
                        <div class="panel-heading">
                            <h4>Past Appointments</h4>
                            <div class="options">
								<a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-up"></i></a>
							</div>
                        </div>

                        <div class="table-responsive" id='tbl_appointment'>
                            <table class="table table-hover">
                                <thead>
                                    <th>#</th>
                                    <th>Date/Time</th>
                                    <th>Subject</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                </thead>
                                <tbody id="list_past_appt">
                                    <tr>
                                        <td colspan="5">
                                            <div class="list-loader">
                                                <div class="spinner"></div>
                                                <span>Fetching Data...</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row view-2" style="display: none;" id="div_detail">
                <div class="col-sm-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading">
                            <h4>Create New Appointment</h4>
                            <div class="options">
                                <a href="#" id="btn_back_1" class="btn-panel-opts" title="Back to list">
                                    <i class="fa fa-arrow-circle-left fa-lg fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">Back to list</span>
                                </a>
                            </div>
                            
                        </div>

                        <div class="panel-body">
                            
                            <form class="form-horizontal" id="form_appt">
                            
                            <div class="tab-container tab-grape">
                           		<ul class="nav nav-tabs">
                               		<li class="active"><a data-toggle="tab" href="#tab-one">Appointments<i class="fa faa-flash animated" aria-hidden="true"></i></a></li>
                                   	<li><a data-toggle="tab" href="#tab-two">Email Content</a></li>
                                   	<li><a data-toggle="tab" href="#tab-three">Claims</a></li>
                              	</ul>
                            
                                
                                <div class="tab-content">
                                
	                                <!--Appointment Details-->
	                                <div id="tab-one" class="tab-pane active">
		                                <div class="form-group">
		                                    <label class="control-label col-sm-1">To</label>
		                                    <div class="col-sm-11 error-container">
		                                        <select id="dd_to" class="populate" multiple="multiple">
		                                            <?php
		                                            for($i = 0; $i < count($to); $i++)
		                                            {
		                                            ?>
		                                                <option value="<?php echo($to[$i]['email']) ?>" data-val="<?php echo(json_encode($to[$i])); ?>"><?php echo($to[$i]['name'] . ' - ' . $to[$i]['company_name']) ?></option>
		                                            <?php
		                                            }
		                                            ?>
		                                     	</select>
		                                    </div>
		                                </div>
                                
		                                <div class="form-group">
		                                    <label class="control-label col-sm-1">Subject</label>
		                                    <div class="col-sm-3 error-container">
		                                        <input type="text" class="form-control marginBottom10px" id="txt_subject" placeholder="Subject" required="required">	
		                                    	<input type="hidden" id="txt_template" value="<?php echo(base64_encode($template[0]['template_content'])); ?>">
		                                    </div>
		                                    
		                                    <label class="control-label col-sm-1">Category</label>
		                                    <div class="col-sm-3 error-container">
		                                        <div class="select2-hide">
		                                            <select id="dd_category" class="populate">
		                                            <?php
		                                            for($i = 0; $i < count($category); $i++)
		                                            {
		                                            ?>
		                                                <option value="<?php echo($category[$i]['id']) ?>"><?php echo($category[$i]['descr']) ?></option>
		                                            <?php
		                                            }
		                                            ?>
		                                            </select>
		                                        </div>
		                                    </div>
		                                    
		                                    <label class="control-label col-sm-1">Location</label>
		                                    <div class="col-sm-3 error-container">
		                                        <input type="text" class="form-control marginBottom10px" id="txt_location" placeholder="Location (Optional)">	
		                                    </div>
		                                    
		                                </div>

		                                <div class="form-group">
		                                    <label for="dp_date" class="control-label col-sm-1">Start</label>
		                                    <div class="col-sm-3 error-container">
		                                        <div class="input-group">
		                                            <input id="dp_date_start" type="text" class="form-control" data-date-format="dd-M-yyyy" required>
		                                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
		                                        </div>
		                                    </div>
		                                    
		                                    <div class="col-sm-2 error-container">
		                                   		<div class="input-group">
		                                         	<input id="tp_time_start" type="text" class="form-control" style="box-sizing: border-box !important;" required>
		                                        	<span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
		                                     	</div>
		                                	 </div>
		                                </div>
	                                
		                                <div class="form-group">
		                                    <label for="dp_date" class="control-label col-sm-1">End</label>
		                                    <div class="col-sm-3 error-container">
		                                        <div class="input-group">
		                                            <input id="dp_date_end" type="text" class="form-control" data-date-format="dd-M-yyyy" required>
		                                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
		                                        </div>
		                                    </div>
		                                	<div class="col-sm-2 error-container"> 	
		                                     	<div class="input-group">
		                                   			<input id="tp_time_end" type="text" class="form-control" style="box-sizing: border-box !important;">
		                                        	<span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
		                                   		</div>
		                                	 </div>
		                                </div>
                                
		                                <div class="form-group row-status">
		                                    <label class="control-label col-sm-1">Status</label>
		                                    <div class="col-sm-3 error-container">
		                                        <div class="select2-hide">
		                                            <select id="dd_status" class="populate">
		                                            <?php
		                                            for($i = 0; $i < count($appt_status); $i++)
		                                            {
		                                            ?>
		                                                <option value="<?php echo($appt_status[$i]['id']) ?>"><?php echo($appt_status[$i]['descr']) ?></option>
		                                            <?php
		                                            }
		                                            ?>
		                                            </select>
		                                        </div>
		                                    </div>
		                                    
		                                </div>
                                
		                                <div id="div_postpone" style="display: none;">
			                                <div class="form-group">
			                                    <label for="dp_date" class="control-label col-sm-1">Start</label>
			                                    <div class="col-sm-3 error-container">
			                                        <div class="input-group">
			                                            <input id="dp_pp_date_start" type="text" class="form-control" data-date-format="dd-M-yyyy">
			                                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			                                        </div>
			                                    </div>
			                                    
			                                    <div class="col-sm-2 error-container">
			                                   		<div class="input-group">
			                                         	<input id="tp_pp_time_start" type="text" class="form-control" style="box-sizing: border-box !important;">
			                                        	<span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
			                                     	</div>
			                                	 </div>
			                                </div>
			                                <div class="form-group">
			                                    <label for="dp_date" class="control-label col-sm-1">End</label>
			                                    <div class="col-sm-3 error-container">
			                                        <div class="input-group">
			                                            <input id="dp_pp_date_end" type="text" class="form-control" data-date-format="dd-M-yyyy">
			                                            <span class="input-group-addon"><i class="fa fa-calendar" aria-hidden="true"></i></span>
			                                        </div>
			                                    </div>
			                                	<div class="col-sm-2 error-container"> 	
			                                     	<div class="input-group">
			                                   			<input id="tp_pp_time_end" type="text" class="form-control" style="box-sizing: border-box !important;">
			                                        	<span class="input-group-addon"><i class="fa fa-clock-o" aria-hidden="true"></i></span>
			                                   		</div>
			                                	 </div>
			                                </div>
	                                	</div>
                                	
	                                	<div class="form-group" id="row_outcome" style="display: none;">
		                                    <label class="control-label col-sm-1">Meeting OutCome</label>
		                                    <div class="col-sm-11 error-container">
		                                        <textarea id="txt_outcome" class="form-control" rows="3"></textarea>
		                                    </div>
	                                	</div>
                                	
	                                </div>
	                                <!--End tab-one-->
                                
	                                <!--Email Content Details-->
	                                <div id="tab-two" class="tab-pane">
	                               		<div class="form-group">
	                                		<div class="col-md-12" contenteditable="true" id="text_editor"></div>	
	                                	</div>
	                                </div>
	                                <!--End tab-two-->
                                
	                                <!--Claim Details-->
	                                <div id="tab-three" class="tab-pane">
	                           			
	                           			<div class="form-group" id="row_receipt" style="display:none">
	                                    	
		                           			<label class="control-label col-sm-1">Receipts</label>
		                       				<div class="col-sm-5">
		                                		<div class="file-container" style="width: 90%">
		                                       		<div class="btn btn-success btn-sm fileinput-button pull-left" id="btn_add_image">
		                                        		<span>Attach New</span>
		                                            	<input id="fileupload" type="file" name="files[]">
		                                     		</div>
		                                        	<div id="files" class="files pull-left" style="margin-left: 10px;"></div>
		                                          	<input type="hidden" id="hidden_filename">
		                                          	
		                                    	</div>
		                                  		<button type="button" class="btn btn-danger" id="btn_delete_attc" style="display: none;"><span class="fa fa-times"></span></button>
		                             		</div>
	                                        
		                              		<div class="col-sm-2">
		                                 		<label class="col-sm-6 control-label">Cost</label>
		                                     	<div class="col-sm-6 input-group">
		                                       		<input type="number" min="0.00" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_cost" required>
		                                    	</div>
		                              		</div>
	                                        	
		                                 	<div class="col-sm-2">
		                                    	<label class="col-sm-6 control-label">GST/SST</label>
			                                   	<div class="col-sm-6 input-group">
			                                    	<input type="number" min="0.00" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_gst" required>
			                                   	</div>
		                           			</div>
	                                        	
		                                  	<div class="col-sm-2">
		                                  		<label class="col-sm-6 control-label">Round Up</label>
		                                   		<div class="col-sm-6 input-group">
		                                       		<input type="number" min="0.00" step="0.01" value="0.00" class="form-control marginBottom10px" id="txt_roundup" required>
		                                    	</div>
		                             		</div>
	                                        	
	                                	</div>
	                                
		                                <div class="form-group" id="row_receipt" style="display:none">
		                                	<div class="col-sm-1"></div>
		                                	<div class="col-sm-11">
			                                	<div class="table-responsive">
			                                    	<table class="table table-striped" id="tbl_receipt">
			                                        	<thead>
			                                            	<tr>
			                                                	<th>File</th>
			                                                   	<th>Amount</th>
			                                                   	<th>Action</th>
			                                               	</tr>
			                                                </thead>
			                                                <tbody>
																<tr id="base_row">
																	<td width="50%">
																		<div class="col-sm-12"></div>
																	</td>
																	<td width="30%"></td>
																	<td width="20%"></td>
																</tr>
			                                        	</tbody>
			                                    	</table>
			                                  	</div>
		                                	</div>
		                                </div>
                                	
                                	</div><!--End tab-three-->

                           		</div><!-- tab-content -->

							</div><!-- end tab-container tab-grape -->
                            
                            
                            
                            </form>
                        </div>

						
                        <div class="panel-footer" style="background-color: white;">
                            <div class="btn-toolbar pull-right">
                                <!-- <button id="btn_modal_close" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button> -->
                                <button id="btn_save" type="button" class="btn btn-primary ladda-button" data-style="expand-right">
                                    <span class="ladda-label">Create Appointment</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include $current_path . "footer.php" ?>
