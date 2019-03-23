<?php

$current_path = '../../';
include $current_path . 'header.php';

$emp = array();

$access = get_accessibility(7,$_SESSION['access']);
if($access->view_it_all == 1)
{
	$emp        	= db_query('id,name,office_email','cms_employees','is_active = 1');
}
else
{
	$emp        	= db_query('id,name,office_email','cms_employees',"id =" . $_SESSION['emp_id'] . " AND is_active = 1");
}

$client     		= db_query('id,name','cms_clients',"created_by = " . $_SESSION['emp_id'] . " AND is_active = 1");
$notification_to	= db_query('id,name,office_email','cms_employees','is_active = 1');

$sent_to		  	= db_query('cms_employees.id,cms_employees.name',
							   'cms_employees INNER JOIN cms_employees_access ON cms_employees.id = cms_employees_access.emp_id',
							   "cms_employees_access.module_id = 7 AND cms_employees_access.view_it = 1 AND cms_employees.is_active = 1");


$allowance  		= db_query('id,concat("Allowance - ", descr) as descr','cms_master_list',"category_id in(7) AND is_active = 1");
$billing  			= db_query('id,concat("Billing - ",descr) as descr','cms_master_list',"category_id in(25) AND is_active = 1");
$allow_bill_type	= array_merge($allowance,$billing);

$contract_type  	= db_query('id,descr','cms_master_list',"category_id = 9 AND is_active = 1");
$marital_status		= db_query('id,descr','cms_master_list',"category_id = 10 AND is_active = 1");
$sex				= db_query('id,descr','cms_master_list',"category_id = 11 AND is_active = 1");
$emp_type			= db_query('id,descr','cms_master_list',"category_id = 12 AND is_active = 1");
$billing_cycle		= db_query('id,descr','cms_master_list',"category_id = 13 AND is_active = 1");
$working_days		= db_query('id,descr','cms_master_list',"category_id = 14 AND is_active = 1");
$notice_period		= db_query('id,descr','cms_master_list',"category_id = 8 AND is_active = 1");
$contract_status	= db_query('id,descr','cms_master_list',"category_id = 33 AND is_active = 1");


$employers			= db_query('id,employer_name','cms_master_employer',"is_active = 1");

$upload   			= db_query('id,name,descr','cms_upload_doc','is_active = 1');
$relationship		= db_query('id,descr','cms_master_list',"category_id = 26 AND is_active = 1");
$sources			= db_query('id,descr','cms_master_list',"category_id = 27 AND is_active = 1");
$dependent			= db_query('id,descr,field1,field2','cms_master_list',"category_id = 32 AND is_active = 1");

$ext_recruiter		= db_query('id,ref_name,percentage,amount','cms_master_referral',"ref_type = 0 and is_active = 1");
$ext_sales			= db_query('id,ref_name,percentage,amount','cms_master_referral',"ref_type = 1 and is_active = 1");

$requestor		  	= db_query('id,name','cms_employees',"access_level in (57,58,64,65) AND is_active = 1");
$recruiter		  	= db_query('id,name','cms_employees',"access_level in (64,65,58) AND is_active = 1");
$countries			= db_query('id,name','cms_country');

?>

    <div id="page-content">
        <div id='wrap'>
            <div id="page-heading">
				<h2><i class="fa fa-cubes"></i> Onboarding</h2>
				
				<div class="options">

				</div>
            </div>

            <div class="container-fluid">                
                <div class="row" id="list_div">

                    <div class="col-sm-12">
                        <div class="panel panel-grape">
                            <div class="panel-heading">
                                <h4>Search</h4>
                                <div class="options">
                                    <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-up"></i></a>
                                </div>
                            </div>
                            <div class="panel-body" style="display: none">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="txt_name_search" class="col-sm-2 control-label">Name</label>
                                        <div class="col-sm-4">
                                        	<input type="text" class="form-control marginBottom10px" id="txt_name_search" placeholder="Candidate Name">    
                                        </div>
                                        <label for="dd_status_search" class="col-sm-2 control-label">Active Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_status_search" style="width:100%" class="populate">
                                                <option value="">Please Select</option>
                                                <option value="1">Active</option>
                                                <option value="0">In-Active</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="dd_client_search" class="col-sm-2 control-label">Client</label>
                                        <div class="col-sm-4">
                                            <select id="dd_client_search" style="width:100%" class="populate">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($client); $i++)
                                                {
                                                ?>
                                                <option value="<?php echo($client[$i]['id']) ?>"><?php echo($client[$i]['name']) ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <label for="dd_created_by_search" class="col-sm-2 control-label">Created By</label>
                                        <div class="col-sm-4">
                                            <select id="dd_created_by_search" style="width:100%" class="populate">
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
                                    </div>


                                    <div class="form-group">
                                        <label for="txt_model" class="col-sm-2 control-label">PO No.</label>
                                        <div class="col-sm-4">
                                             <input type="text" class="form-control marginBottom10px" id="txt_pono_search" placeholder="Optional" required="required">
                                        </div>
                                        <label for="dd_client_search" class="col-sm-2 control-label">Contract Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_stage_status_search" style="width:100%" class="populate" multiple>
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($contract_status); $i++)
                                                {
                                                ?>
                                                <option value="<?php echo($contract_status[$i]['id']) ?>"><?php echo($contract_status[$i]['descr']) ?></option>
                                                <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        
                                        
                                   </div>

                                    <p class="pull-right">
                                    <div class="btn-toolbar" style="text-align:right">
                                        <button class="btn-default btn ladda-button" data-style="expand-left" id="btn_reset" name="btn_reset"><i class="fa fa-refresh"></i> Reset</button>
                                        <button class="btn-primary-alt btn" id="btn_search" name="btn_search"><i class="fa fa-search"></i> Search</button>
                                    </div>
                                    </p>

                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="panel panel-grape">
                            <div class="panel-heading">
                                <h4>Onboarding List</h4>
                                <div class="options">
                                    <a href="#" class="btn-panel-opts" title="Add New Appointment" id="btn_new">
                                    	<i class="fa fa-plus fa-fw" aria-hidden="true"></i>
	                                    <span class="hidden-xs">New Onboarding</span>
	                              	</a>
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
                                                <li><a href="#" onclick="exportTable2CSV('Contract','tbl_list')">Excel File (*.xlsx)</a></li>
                                                <li><a href="#" onclick="exportTable2PDF('Contract','tbl_list','p')">PDF File (*.pdf)</a></li>
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
                                            <th>Candidate Name</th>
                                            <th>Client</th>
                                            <th>Created By</th>
                                            <th>Status</th>                                            
                                            <th>Approval Stages</th>
                                        	<th>Status Actions</th>
                                        	<th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>

                                        </tbody>
                                    </table>
                                    <div id="div_load_more" class="load-more" style="display: none;">
				                    	<a class="btn btn-info btn-xs" id="btn_load_more"><span class="fa fa-arrow-down fa-fw"></span>Load More<span class="fa fa-arrow-down fa-fw"></span></a>
				                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>

                <!-- New div -->
                <div class="row" id="new_div" style="display: none">

                    <div class="col-sm-12">

                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h4 id="h4_primary_no">Contract Number : -</h4>
                                <div class="options">
                                    <a href="#" id="btn_back" class="btn-panel-opts" title="Back to list">
	                                    <i class="fa fa-arrow-circle-left fa-lg fa-fw" aria-hidden="true"></i>
	                                    <span class="hidden-xs">Back to list</span>
                                	</a>
                                </div>
                            </div>

                            <div class="panel-body">
                                <form class="form-horizontal" data-validate="parsley" id="detail_form">

                                <div class="tab-container tab-grape">
                                    <ul class="nav nav-tabs">
                                        <li class="active">
                                        	<a data-toggle="tab" href="#tab-one">Contract</a>
                                        </li>
                                        <li><a data-toggle="tab" href="#tab-two">Candidate</a></li>
                                        <li><a data-toggle="tab" href="#tab-three">References</a></li>
                                        <li><a data-toggle="tab" href="#tab-four">Attachments</a></li>
                                    </ul>

                                    <div class="tab-content">

                                    	<!--Contract Details Start-->
                                    	<div id="tab-one" class="tab-pane active">
                                        	<p>

                                            <div class="form-group">
                                                <label for="dd_contract_type" class="col-sm-2 control-label">Contract Type</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_contract_type" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                            <?php
                                                            for($i = 0; $i < count($contract_type); $i++)
                                                            {
                                                                ?>
                                                                <option value="<?php echo($contract_type[$i]['id']) ?>"><?php echo($contract_type[$i]['descr']) ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                                <label for="contract_date" class="col-sm-2 control-label">Tentative Start Date</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input type="text" id="contract_date" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label for="dd_contract_type" class="col-sm-2 control-label">Resume Source</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_resume_source" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                            <?php
                                                            for($i = 0; $i < count($sources); $i++)
                                                            {
                                                                ?>
                                                                <option value="<?php echo($sources[$i]['id']) ?>" ><?php echo($sources[$i]['descr']) ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                                <label for="contract_date" class="col-sm-2 control-label">Referral</label>
                                                <div class="col-sm-2">
                                                	<input type="text" class="form-control marginBottom10px" id="txt_referral_name" placeholder="Name">    
                                             	</div>
                                             	<div class="col-sm-2">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_referral_amount" placeholder="Amount" max="5000" min="0" onchange="$.fn.check_max_min(this)">
                                              	</div> 
                                            </div>


											<div class="form-group">
                                                <label class="col-sm-2 control-label">External Recruiter</label>
                                                <div class="col-sm-2">
                                                    <select id="dd_ext_rec" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                            <?php
                                                            for($i = 0; $i < count($ext_recruiter); $i++)
                                                            {
                                                                ?>
                                                                <option value="<?php echo($ext_recruiter[$i]['id']) ?>" data='<?php echo(json_encode($ext_recruiter[$i])); ?>'><?php echo($ext_recruiter[$i]['ref_name']) ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_ext_rec_amount" placeholder="Amount" max="5000" min="0" onchange="$.fn.check_max_min(this)">
                                              	</div>
                                                <label class="col-sm-2 control-label">External Sales</label>
                                             	<div class="col-sm-2">
                                                    <select id="dd_ext_sales" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                            <?php
                                                            for($i = 0; $i < count($ext_sales); $i++)
                                                            {
                                                                ?>
                                                                <option value="<?php echo($ext_sales[$i]['id']) ?>" data='<?php echo(json_encode($ext_sales[$i])); ?>'><?php echo($ext_sales[$i]['ref_name']) ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                    </select>
                                                </div>
                                             	<div class="col-sm-2">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_ext_sales_amount" placeholder="Amount" max="5000" min="0" onchange="$.fn.check_max_min(this)">
                                              	</div> 
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Requestor Name</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_requestor_name" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                                <?php
                                                                for($i = 0; $i < count($requestor); $i++)
                                                                {
                                                                ?>
                                                                    <option value="<?php echo($requestor[$i]['id']) ?>"><?php echo($requestor[$i]['name']) ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                                <label for="request_date" class="col-sm-2 control-label">Request Date</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input type="text" id="request_date" class="form-control" data-date-format="DD-MM-YYYY"  data-format="dd-MM-yyyy" placeholder="e.g dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                    		<i class="fa fa-calendar"></i>
                                                		</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Recruiter Name</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_recruiter_name" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                                <?php
                                                                for($i = 0; $i < count($recruiter); $i++)
                                                                {
                                                                ?>
                                                                    <option value="<?php echo($recruiter[$i]['id']) ?>"><?php echo($recruiter[$i]['name']) ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Employer</label>
                                                 <div class="col-sm-4">
                                                     <select id="dd_employer" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                                <?php
                                                                for($i = 0; $i < count($employers); $i++)
                                                                {
                                                                ?>
                                                                    <option value="<?php echo($employers[$i]['id']) ?>"><?php echo($employers[$i]['employer_name']) ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                    </select>
                                                </div>
                                             </div>

                                            

                                            <div class="form-group">
                                                <label for="dd_status" class="col-sm-2 control-label">Client Name</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_client" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                                <?php
                                                                for($i = 0; $i < count($client); $i++)
                                                                {
                                                                ?>
                                                                    <option value="<?php echo($client[$i]['id']) ?>"><?php echo($client[$i]['name']) ?></option>
                                                                <?php
                                                                }
                                                                ?>
                                                    </select>
                                                    <label id="lbl_client_name" class="control-label"></label>
                                                </div>
                                                <label class="col-sm-2 control-label">Hiring Manager Name</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_hiring_manager_name">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Hiring Manager Tel.No.</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_hiring_manager_telno">
                                                </div>
                                                <label class="col-sm-2 control-label">Hiring Manager Email</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_hiring_manager_email">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Client Invoice Contact Person</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_client_invoice_contact_person">
                                                </div>
                                                <label for="txt_home_address" class="col-sm-2 control-label">Client Address</label>
                                                <div class="col-sm-4">
                                                    <textarea class="form-control marginBottom10px" id="txt_client_invoice_address_to"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Employment Type</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_employment_type" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($emp_type); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($emp_type[$i]['id']) ?>"><?php echo($emp_type[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Client Location</label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_client_location">
                                                </div>
                                           </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Contract Start Date</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input type="text" id="start_date" class="form-control" data-date-format="DD-MM-YYYY"  data-format="dd-MM-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                        <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                    </div>
                                                </div>
                                                <label for="dd_status" class="col-sm-2 control-label">Contract End Date</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input type="text" id="end_date" class="form-control" data-date-format="DD-MM-YYYY"  data-format="dd-MM-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                        <span class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </span>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Duration of Contract</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_duration" placeholder="Required Field" required="required" maxlength="5" onkeypress="return validateNumber(event)" readonly="readonly">
                                                </div>
                                                <label class="col-sm-2 control-label">Notice Period</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_notice_period" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($notice_period); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($notice_period[$i]['id']) ?>"><?php echo($notice_period[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                 </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Billing Amount </label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_billing_amount" onkeyup="$.fn.fill_amount_with_gst(this.value)" maxlength="10" onkeypress="return validateNumber(event)">
                                                </div>
                                                <label class="col-sm-2 control-label">GST/SST </label>
                                                <div class="col-sm-2">
                                                     <select id="dd_gst_sst" style="width:100%" class="populate" required="required">
                                                        <option value="0" selected="selected">0%</option>
                                                        <option value="6">6%</option>
                                                        <option value="10">10%</option>
                                                    </select>
                                                </div>
                                                <div class="col-sm-2">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_billing_amount_with_gst" readonly="readonly">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">One Time Fees </label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_one_time_fees" maxlength="10" onkeypress="return validateNumber(event)">
                                                </div>
                                                <label for="txt_home_address" class="col-sm-2 control-label">Fee Descr.</label>
                                                <div class="col-sm-4">
                                                    <textarea class="form-control marginBottom10px" id="txt_fee_descr" maxlength="999"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">No. of Month</label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_billing_of_month" maxlength="10" onkeypress="return validateNumber(event)" readonly="readonly">
                                                </div>
                                                <label class="col-sm-2 control-label">Billing Cycle</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_billing_cycle" style="width:100%" class="populate" >
                                                    	<option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($billing_cycle); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($billing_cycle[$i]['id']) ?>"><?php echo($billing_cycle[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>      
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Billing Type</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_billing_type" style="width:100%" class="populate" >
                                                        <option value="0">Monthly</option>
                                                        <option value="1">Daily</option>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">PO No.</label>
                                              	<div class="col-sm-2">
                                                    <select id="dd_pono_status" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                        <option value="yes">Available</option>
                                                        <option value="no">Pending</option>
                                                    </select>
                                             	</div>
                                             	<div class="col-sm-2" id="div_pono">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_pono" placeholder="Optional">
                                              	</div> 
                                            </div>

                                            <div class="form-group">
                                              <label class="col-sm-2 control-label">Working Days</label>
                                              <div class="col-sm-4">
                                                    <select id="dd_working_days" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($working_days); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($working_days[$i]['id']) ?>"><?php echo($working_days[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                              </div>
                                              <label class="col-sm-2 control-label">Overtime Rate </label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_overtime_rate" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                           </div>

                                            
                                            <div class="form-group">
                                              <label class="col-sm-2 control-label">Annual Leave By Client</label>
                                              <div class="col-sm-4">
                                                   <input type="number" class="form-control marginBottom10px" id="txt_annual_leave_by_client"  max="30" min="0" onchange="$.fn.check_max_min(this)">
                                              </div>
                                              <label for="dd_status" class="col-sm-2 control-label">Medical Leave By Client</label>
                                              <div class="col-sm-4">
                                                    <input type="number" class="form-control marginBottom10px" id="txt_medical_leave_by_client"  max="30" min="0" onchange="$.fn.check_max_min(this)">
                                              </div>
                                           </div>

                                           <div class="form-group">
                                              <label class="col-sm-2 control-label">Notification Email To</label>
                                              <div class="col-sm-4">
                                              		<select id="dd_notification_email_to" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
		                                                <?php
		                                                for($i = 0; $i < count($notification_to); $i++)
		                                                {
		                                                ?>
		                                                    <option value="<?php echo($notification_to[$i]['office_email']) ?>"><?php echo($notification_to[$i]['name']) ?></option>
		                                                <?php
		                                                }
		                                                ?>
                                                    </select>     
                                              </div>
                                              <label for="dd_status" class="col-sm-2 control-label">Notification Month</label>
                                              <div class="col-sm-4">
                                                <input type="number" class="form-control marginBottom10px" id="notification_month" max="5" min="1" onchange="$.fn.check_max_min(this)">
                                              </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Sales Approval</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_sales_approval" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                       	<?php
                                                       	for($i = 0; $i < count($notification_to); $i++)
                                                            {
                                                         ?>
                                                            	<option value="<?php echo($notification_to[$i]['id']) ?>"><?php echo($notification_to[$i]['name']) ?></option>
                                                        <?php
                                                            }
                                                         ?>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Remarks</label>
                                                <div class="col-sm-4">
                                                	<textarea class="form-control marginBottom10px" id="txt_remarks" rows="2" maxlength="1000"></textarea>     
                                                </div>
                                             </div>
                                             
                                             <div class="form-group">
                                             	<label class="col-sm-2 control-label">Total contract value</label>
                                              	<div class="col-sm-4">
                                              		<input type="text" class="form-control marginBottom10px" id="txt_total_contract_value"  maxlength="10" onkeypress="return validateNumber(event)" readonly="readonly">		    
                                              	</div>
                                              	<label class="col-sm-2 control-label">Status</label>
                                              	<div class="col-sm-4">
                                                	 <select id="dd_status" style="width:100%" class="populate" required="required" disabled="true">
                                                        <option value="">Please Select</option>
                                                       	<?php
                                                       	for($i = 0; $i < count($contract_status); $i++)
                                                      	{
                                                        ?>
                                                        	<option value="<?php echo($contract_status[$i]['id']) ?>"><?php echo($contract_status[$i]['descr']) ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>  
                                              	</div>
                                           	 </div>
                                             

                                             <div class="form-group" id="div_onboard_date">
                                                <label class="col-sm-2 control-label">Onboard Date</label>
                                                <div class="col-sm-4">
                                                	<div class="input-group date">
                                                        <input type="text" id="onboard_date" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>    
                                                </div>
                                                <div class="col-sm-2">
                                                	<a href="#" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_onboard_save"><i class="fa fa-check"></i> Save</a>		     
                                                </div>
                                                <div class="col-sm-4" id="div_emp_record">
                                                	     
                                                </div>
                                             </div>
                                             
                                            
                                            
                                            

                                        </p>
                                        </div>
                                        <!--Contract Details End-->





                                        <div id="tab-two" class="tab-pane">
                                        
                                        <p>
										   <h4>Person Information</h4>
										   <div class="form-group">
                                                <label class="col-sm-2 control-label">Name</label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_employee_name" placeholder="Required Field" required="required">
                                                </div>
                                                <label class="col-sm-2 control-label">NRIC/Passport</label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_nric" placeholder="Required Field" required="required">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Designation</label>
                                                <div class="col-sm-4">
                                                     <input type="text" class="form-control marginBottom10px" id="txt_designation" placeholder="Required Field" required="required">
                                                </div>
                                                
                                                <label class="col-sm-2 control-label">Sex</label>
                                                <div class="col-sm-4">
                                                	<select id="dd_sex" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($sex); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($sex[$i]['id']) ?>"><?php echo($sex[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>     
                                                </div>
                                            </div>
                                            
											<div class="form-group">
                                                <label class="col-sm-2 control-label">Nationality</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_nationality" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($countries); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($countries[$i]['id']) ?>"><?php echo($countries[$i]['name']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Email</label>
                                                <div class="col-sm-4">
                                                	<input type="text" class="form-control marginBottom10px" id="txt_email" required="required">    
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Marital Status</label>
                                                <div class="col-sm-4">
                                                	<select id="dd_marital_status" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($marital_status); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($marital_status[$i]['id']) ?>"><?php echo($marital_status[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>    
                                                </div>
                                                
                                                 <label class="col-sm-2 control-label">Contact No.</label>
                                                <div class="col-sm-4">
                                                	<input type="text" class="form-control marginBottom10px" id="txt_contact_no" required="required">	    
                                                </div>
                                               
                                             </div>
                                             
                                             <div class="form-group">
                                                <label class="col-sm-2 control-label">Home Add.</label>
                                                <div class="col-sm-4">
                                                  	<textarea name="txt_home_address" style="width:100%" id="txt_home_address" cols="45" rows="3" required="required" maxlength="900"></textarea>  
                                                </div>
                                                <div class="col-sm-6"></div>
                                             </div>
                                             
                                             


											<br/>
											<h4>Current Status</h4>
                                        	<div class="form-group">
                                                <label class="col-sm-2 control-label">Current Company Name</label>
                                                <div class="col-sm-4">
                                                	<input type="text" class="form-control marginBottom10px" id="txt_current_company" maxlength="150">
                                                </div>
                                                <label class="col-sm-2 control-label">Current EP Expiry Date</label>
                                                <div class="col-sm-4">
                                                	<div class="input-group date">
                                                        <input type="text" id="txt_current_ep_expiry_date" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <div class="col-sm-6">
                                                	
                                                </div>
                                                <label class="col-sm-2 control-label">Apply EP On</label>
                                                <div class="col-sm-4">
                                                	<div class="input-group date">
                                                        <input type="text" id="txt_apply_ep_on_date" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            
                                            <div class="form-group">
	                                        	<label class="col-sm-2 control-label">Able to obtain NOC</label>
	                                       		<div class="col-sm-4">
	                                            	<label class="checkbox-inline">
	                                              		<input type="checkbox" id="chk_noc" name="chk_noc"> &nbsp;
	                                                </label>
	                                           	</div>
	                                                
	                                            <label class="col-sm-2 control-label">Require to exit the country</label>
	                                       		<div class="col-sm-4">
	                                            	<label class="checkbox-inline">
	                                              		<input type="checkbox" id="chk_leave_country_required" name="chk_leave_country_required">&nbsp;
	                                                </label>
	                                           	</div>
	                                      	</div>
	                                      	
	                                      	<div class="form-group">
	                                        	<label class="col-sm-2 control-label">EP Required</label>
	                                       		<div class="col-sm-4">
	                                            	<label class="checkbox-inline">
	                                              	<input type="checkbox" id="chk_ep_required" name="chk_ep_required">&nbsp;
	                                                </label>
	                                           	</div>
	                                                
	                                            <label class="col-sm-2 control-label">Reference Check</label>
                                                <div class="col-sm-4">
                                                    <label class="checkbox-inline">
                                                      <input type="checkbox" id="chk_reference_check" name="chk_reference_check">&nbsp;
                                                    </label>
                                                </div>
	                                      	</div>
	                                      	
	                                      	
											
											
											<br/>
											<h4>Salary & Other Cost (RM)</h4>
                                        	<div class="form-group">
                                                <label class="col-sm-2 control-label">Basic Salary</label>
                                                <div class="col-sm-4">
                                                       <input type="text" class="form-control marginBottom10px" id="txt_salary" maxlength="10" onkeypress="return validateNumber(event)">
                                                </div>
                                                <label class="col-sm-2 control-label">Daily Salary</label>
                                                <div class="col-sm-4">
                                                       <input type="text" class="form-control marginBottom10px" id="txt_daily_salary" maxlength="10" onkeypress="return validateNumber(event)">
                                                </div>
                                            </div>

                                            <div class="form-group">
                                               <label class="col-sm-2 control-label">EPF</label>
                                              <div class="col-sm-2">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_epf" maxlength="10" onkeypress="return validateNumber(event)">
                            					  
                                              </div>
                                              <div class="col-sm-2">
                                              		<select id="dd_epf_percentage" style="width:100%" class="populate">
                                                        <option value="">Please Select</option>
                                                        <option value="0.13">13 %</option>
                                                        <option value="0.12">12 %</option>
                                                        <option value="0.11">11 %</option>
                                                    </select>     
                                              </div>
                                              	
                                              <label class="col-sm-2 control-label">SOCSO</label>
                                              <div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_socso" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_socso_tick" name="chk_socso_tick">
	                                                </label>   
                                              </div>
                                           </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">EP Cost </label>
                                                <div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_epcost" maxlength="10" onkeypress="return validateNumber(event)">
                                                </div>
                                                <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_ep_tick" name="chk_ep_tick">
	                                                </label>   
                                              	</div>
                                                <label class="col-sm-2 control-label">Recruiter Commission </label>
	                                            <div class="col-sm-4">
	                                            	<input type="text" class="form-control marginBottom10px" id="txt_recruiter_commission"  maxlength="10" onkeypress="return validateNumber(event)">
	                                            </div>
                                                
                                            </div>

											<div class="form-group">
                                              	<label class="col-sm-2 control-label">Outpatient Medical</label>
                                              	<div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_outpatient_medical_cost"  maxlength="10" onkeypress="return validateNumber(event)">
                                              	</div>
                                              	<div class="col-sm-1" style="text-align:right">
                                             		<label>
	                                              		<input type="checkbox" id="chk_om_tick" name="chk_om_tick">
	                                                </label>   
                                              	</div>
                                              	<label class="col-sm-2 control-label">Medical Insurance</label>
                                              	<div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_medical_insurance_cost"  maxlength="10" onkeypress="return validateNumber(event)">
                                              	</div>
                                              	<div class="col-sm-1" style="text-align:right">
                                             		<label>
	                                              		<input type="checkbox" id="chk_mi_tick" name="chk_mi_tick">
	                                                </label>   
                                              	</div>
                                           </div>

                                            <div class="form-group">
                                              <label class="col-sm-2 control-label">Overseas Visa</label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_overseas_visa_cost" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <label class="col-sm-2 control-label">Laptop</label>
                                              <div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_laptop_cost" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                           	  <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_laptop_tick" name="chk_laptop_tick">
	                                                </label>   
                                              </div>
                                           </div>

                                            <div class="form-group">
                                              <label class="col-sm-2 control-label">Temp. Accommodation</label>
                                              <div class="col-sm-3">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_temp_accommodation_cost"  maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_ta_tick" name="chk_ta_tick">
	                                                </label>   
                                              </div>
                                              <label class="col-sm-2 control-label">Mobilization</label>
                                              <div class="col-sm-3">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_mobilization_cost"  maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_mobilization_tick" name="chk_mobilization_tick">
	                                                </label>   
                                              </div>
                                           </div>

                                           <div class="form-group">
                                              <label class="col-sm-2 control-label">Flight Ticket</label>
                                              <div class="col-sm-3">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_flight_ticket_cost"  maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_ft_tick" name="chk_ft_tick">
	                                                </label>   
                                              </div>
                                              <label class="col-sm-2 control-label">Notice Period Buyout </label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_notice_period_buyout"  maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                           </div>

                                           <div class="form-group">
                                              <label class="col-sm-2 control-label">Bonus </label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_bonus" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <label class="col-sm-2 control-label">Sales Commission</label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_sales_commission" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                           </div>
                                           
                                           <div class="form-group">
                                              <label class="col-sm-2 control-label">EIS</label>
                                              <div class="col-sm-3">
                                                   <input type="number" class="form-control marginBottom10px" id="txt_eis" max="100" min="0" onchange="$.fn.check_max_min(this)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_eis_tick" name="chk_eis_tick">
	                                                </label>   
                                              </div>
                                              <label for="dd_status" class="col-sm-2 control-label">HRDF</label>
                                              <div class="col-sm-3">
                                                <input type="number" class="form-control marginBottom10px" id="txt_hrdf" max="10000" min="0" onchange="$.fn.check_max_min(this)">
                                              </div>
                                              <div class="col-sm-1" style="text-align:right">
                                              		<label>
	                                              		<input type="checkbox" id="chk_hrdf_tick" name="chk_hrdf_tick">
	                                                </label>   
                                              </div>
                                           </div>
                                           
                                           <div class="form-group">
                                              <label class="col-sm-2 control-label">Other Cost </label>
                                              <div class="col-sm-4">
                                                   <input type="text" class="form-control marginBottom10px" id="txt_other_cost" maxlength="10" onkeypress="return validateNumber(event)">
                                              </div>
                                              <label class="col-sm-2 control-label">Other Cost Remarks </label>
                                              <div class="col-sm-4">
                                                   <textarea class="form-control marginBottom10px" id="txt_other_cost_remarks" rows="2" maxlength="1000"></textarea>  
                                              </div>

                                           </div>

										   
										    <br/>
                                            <h4>Dependants Details</h4>
                                            
                                            <div class="table-responsive">
	                                            <table class="table table-striped" id="tbl_dependent">
	                                                <thead>
	                                                    <tr>
	                                                        <th>Type</th>
	                                                        <th>Quantity</th>
	                                                        <th>Amount</th>
	                                                        <th>Action</th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody>
														<tr id="base_row_dependent">
															<td width="30%">
															<select id="dd_td_dependent" style="width:100%" class="populate">
		                                                        <option value="0" data-value="{}" selected="selected">Please Select</option>
		                                                        <?php
		                                                        for($i = 0; $i < count($dependent); $i++)
		                                                        {
		                                                        ?>
		                                                            <option value="<?php echo($dependent[$i]['id']) ?>" data-value='<?php echo json_encode($dependent[$i]); ?>'><?php echo($dependent[$i]['descr']) ?></option>
		                                                        <?php
		                                                        }
		                                                        ?>
		                                                    </select>
															</td>
															<td width="20%">
																<select id="dd_dp_qty" style="width:100%" class="populate">
			                                                        <option value="0">Please Select</option>
			                                                        <option value="1">1</option>
			                                                        <option value="2">2</option>
			                                                        <option value="3">3</option>
			                                                        <option value="4">4</option>
			                                                        <option value="5">5</option>
			                                                        <option value="6">6</option>
			                                                        <option value="7">7</option>
			                                                        <option value="8">8</option>
			                                                        <option value="9">9</option>
			                                                        <option value="10">10</option>
			                                                    </select>	
															</td>
															<td width="30%"><input type="text" class="form-control marginBottom10px" id="txt_td_dependent_amount" placeholder="Required Field"  maxlength="10" onkeypress="return validateNumber(event)"></td>
															<td width="20%">
															<button id="btn_add_dependent" type="button" class="btn btn-primary" data-mode="0">
					                                            <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
					                                        </button>
															</td>
														</tr>
	                                                </tbody>
	                                            </table>
	                                        </div>


                                            <br/>
                                            <h4>Additional Allowance/Billing Details</h4>
                                            
                                            <div class="table-responsive">
	                                            <table class="table table-striped" id="tbl_allowance">
	                                                <thead>
	                                                    <tr>
	                                                        <th>Type</th>
	                                                        <th>Amount</th>
	                                                        <th>Action</th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody>
														<tr id="base_row">
															<td width="50%">
															<select id="dd_td_allowance" style="width:100%" class="populate">
		                                                        <option value="">Please Select</option>
		                                                        <?php
		                                                        for($i = 0; $i < count($allow_bill_type); $i++)
		                                                        {
		                                                        ?>
		                                                            <option value="<?php echo($allow_bill_type[$i]['id']) ?>"><?php echo($allow_bill_type[$i]['descr']) ?></option>
		                                                        <?php
		                                                        }
		                                                        ?>
		                                                    </select>
															</td>
															<td width="30%"><input type="text" class="form-control marginBottom10px" id="txt_td_amount" placeholder="Required Field"  maxlength="10" onkeypress="return validateNumber(event)"></td>
															<td width="20%">
															<button id="btn_add_allowance" type="button" class="btn btn-primary" data-mode="0">
					                                            <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
					                                        </button>
															</td>
														</tr>
	                                                </tbody>
	                                            </table>
	                                        </div>
                                            

                     
										
											<br/>
											<h4>Additional Benefits</h4>
											
											<div class="form-group">
	                                              <label class="col-sm-2 control-label">Annual Leave</label>
	                                              <div class="col-sm-4">
	                                                   <input type="number" class="form-control marginBottom10px" id="txt_annual_leave" max="30" min="0" onchange="$.fn.check_max_min(this)">
	                                              </div>
	                                              <label for="dd_status" class="col-sm-2 control-label">Medical Leave</label>
	                                              <div class="col-sm-4">
	                                                    <input type="number" class="form-control marginBottom10px" id="txt_medical_leave" max="30" min="0" onchange="$.fn.check_max_min(this)">
	                                              </div>
	                                        </div>
											
	                                       	<div class="form-group">
	                                        	<label class="col-sm-2 control-label">Overtime Applicable</label>
	                                           	<div class="col-sm-4">
	                                            	<label class="checkbox-inline">
	                                               		<input type="checkbox" id="chk_overtime_applicable" name="chk_overtime_applicable">&nbsp;
	                                               	</label>
	                                          	</div>
	                                           	<label class="col-sm-2 control-label">Client to Hire</label>
	                                           	<div class="col-sm-4">
	                                           		<label class="checkbox-inline">
	                                                      <input type="checkbox" id="chk_client_to_hire_allow" name="chk_client_to_hire_allow">&nbsp;
	                                               	</label>
	                                           	</div>
	                                      	</div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Replacement Leave</label>
                                                <div class="col-sm-4">
                                                     <label class="checkbox-inline">
                                                      <input type="checkbox" id="chk_replacement_leave_applicable" name="chk_replacement_leave_applicable">&nbsp;
                                                    </label>
                                                </div>
                                                <label class="col-sm-2 control-label">Annual Leave En-Cash</label>
                                                <div class="col-sm-4">
                                                     <label class="checkbox-inline">
                                                      <input type="checkbox" id="chk_annual_leave_encash_allow" name="chk_annual_leave_encash_allow">&nbsp;
                                                    </label>
                                                </div>
                                             </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Active</label>
                                                <div class="col-sm-4">
                                                    <label class="checkbox-inline">
                                                      <input type="checkbox" id="chk_is_active" name="chk_is_active" checked="checked">&nbsp;
                                                    </label>
                                                </div>
                                                <label class="col-sm-2 control-label">Claim Applicable</label>
                                              	<div class="col-sm-4">
                                                	<label class="checkbox-inline">
                                                      	<input type="checkbox" id="chk_travelling_claim" name="chk_travelling_claim"> Travelling
                                                  	</label>
                                                  	<label class="checkbox-inline">
                                                   		<input type="checkbox" id="chk_medical_claim" name="chk_medical_claim"> Medical
                                                  	</label>
                                              	</div>
                                            </div>
										
										
                                        </p>
                                        </div>
                                        <!--Cost End-->

                                        <!--Other Details Start-->
                                        <div id="tab-three" class="tab-pane">
                                        <p>
										
											<div class="table-responsive">
	                                            <table class="table table-striped" id="tbl_reference">
	                                                <thead>
	                                                    <tr>
	                                                        <th>Name</th>
	                                                        <th>Contact No.</th>
	                                                        <th>Email</th>
	                                                        <th>Company Name</th>
	                                                        <th>Designation</th>
	                                                        <th>Relationship</th>
	                                                        <th>Remarks</th>
	                                                        <th>Action</th>
	                                                    </tr>
	                                                </thead>
	                                                <tbody>
														<tr id="base_row_reference">
															<td width="15%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_name" placeholder="Required Field" maxlength="150"></td>
															<td width="15%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_contact" placeholder="Required Field" maxlength="20"></td>
															<td width="15%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_email" placeholder="Required Field" maxlength="100"></td>
															<td width="15%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_comp_name" placeholder="Required Field" maxlength="150"></td>
															<td width="10%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_desg" placeholder="Required Field" maxlength="100"></td>
															
															<td width="20%">
															<select id="dd_td_ref_relation" style="width:100%" class="populate">
		                                                        <option value="">Please Select</option>
		                                                        <?php
		                                                        for($i = 0; $i < count($relationship); $i++)
		                                                        {
		                                                        ?>
		                                                            <option value="<?php echo($relationship[$i]['id']) ?>"><?php echo($relationship[$i]['descr']) ?></option>
		                                                        <?php
		                                                        }
		                                                        ?>
		                                                    </select>
															</td>
															<td width="10%"><input type="text" class="form-control marginBottom10px" id="txt_td_ref_remarks" placeholder="Remarks" maxlength="100"></td>
															<td width="5%">
															<button id="btn_add_reference" type="button" class="btn btn-primary" data-mode="0">
					                                            <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
					                                        </button>
															</td>
														</tr>
	                                                </tbody>
	                                            </table>
	                                        </div>	

                                        </p>
                                        </div>
                                        <!--Other Details End-->

                                        <!--Files Upload Start-->
                                        <div id="tab-four" class="tab-pane">
                                        <p>
                                        
										  <?php
                                            for($i = 0; $i < count($upload); $i += 2)
                                            {
                                          ?>
                                           <div class="form-group">
                                                
                                                <label id="lbl_upload_<?php echo($upload[$i]['id']) ?>" class="col-sm-2 control-label"><?php echo($upload[$i]['descr']) ?></label>
                                                <div class="col-sm-4">
                                                    <span class="btn btn-success fileinput-button" id="btn_add_image">
                                                        <span>Browse File</span>
                                                        <input id="doc_upload_<?php echo($upload[$i]['id']) ?>" type="file" name="files[]" class="doc_upload">
                                                    </span>
                                                    <span class="doc_link" id="document_link_<?php echo($upload[$i]['id']) ?>"></span>
                                                    <br/>
                                                    <span id="document_<?php echo($upload[$i]['id']) ?>" class="document" style="margin-top: 8px"></span>
                                                    <input type="hidden" id="hidden_doc_<?php echo($upload[$i]['id']) ?>" class="attachment_doc">
                                                </div>
                                                
                                                <?php 
                                                if(isset($upload[$i+1]))
                                                {
                                                ?>	
	                                                <label id="lbl_upload_<?php echo($upload[$i + 1]['id']) ?>" class="col-sm-2 control-label"><?php echo($upload[$i+1]['descr']) ?></label>
	                                                <div class="col-sm-4">
	                                                    <span class="btn btn-success fileinput-button" id="btn_add_image">
	                                                        <span>Browse File</span>
	                                                        <input id="doc_upload_<?php echo($upload[$i + 1]['id']) ?>" type="file" name="files[]" class="doc_upload">
	                                                    </span>
	                                                    <span id="document_link_<?php echo($upload[$i+1]['id']) ?>"></span>
	                                                    <br/>
	                                                    <span id="document_<?php echo($upload[$i + 1]['id']) ?>" class="document" style="margin-top: 8px"></span>
	                                                    <input type="hidden" id="hidden_doc_<?php echo($upload[$i+1]['id']) ?>" class="attachment_doc">
	                                                </div>
                                                
                                                <?php } ?>
                                                
                                            </div>
                                          <?php
                                            }
                                          ?>

                                        </p>
                                        
                                        
                                        </div>
                                        <!--Files Upload End-->
										
                                    </div>

                            	</div>

                                </form>

                                <p class="pull-right">
                                <div class="btn-toolbar" style="text-align:right">
                                    <a href="#" class="btn btn-default" id="btn_cancel"><i class="fa fa-times"></i> Cancel</a>
                                    <a href="#" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_save"><i class="fa fa-check"></i> Save</a>
                                </div>
                                </p>

                            </div>
                        </div>

                    </div>

                </div>


               <!--Contract Information Start-->
               <div class="row" id="info_div" style="display: none">

                   <div class="col-sm-12 text-left">
                       <div class="form-group">
                        <a href="#" class="btn btn-default" id="btn_info_back"><i class="fa fa-arrow-left"></i> Back</a>
                       </div>
                   </div>

                   <div class="col-sm-12">
                        <div class="panel panel-grape">
                            <div class="panel-heading">
                                <h4 id="h4_contract_no"></h4>
                                <div class="options">
                                    <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
                                </div>
                            </div>
                            <div class="panel-body">
                            	<div class="col-sm-12">
                                    <h4 class="col-sm-2 text-center">
                                    	<div>Sales Head</div>
                                        <div id="sales_head_status"></div>
                                    </h4>
                                    <h4 class="col-sm-2 text-center">
                                    	<div>HR</div>
                                        <div id="hr_status"></div>
                                    </h4>
                                    <h4 class="col-sm-2 text-center">
                                    	<div>Finance</div>
                                        <div id="finance_status"></div>
                                    </h4>
                                    <h4 class="col-sm-2 text-center">
                                    	<div>CEO</div>
                                        <div id="ceo_status"></div>
                                    </h4>
                                    <h4 class="col-sm-2 text-center">
                                    	<div>HR(Offer Letter)</div>
                                        <div id="offer_letter_status"></div>
                                    </h4>
                                </div>
                                <br />
                            	<table width="100%" class="table-striped table-condensed">
                                	<tbody>
                                	<tr>
                                    	<td width="30%">Contract Type</td>
                                        <td width="2%">:</td>
                                        <td id="div_contract_type"></td>
                                    </tr>
                                    <tr>
                                    	<td>Date</td>
                                        <td>:</td>
                                        <td id="div_contract_date"></td>
                                    </tr>
                                    <tr>
                                    	<td>Requestor Name</td>
                                        <td>:</td>
                                        <td id="div_requestor_name"></td>
                                    </tr>
                                    <tr>
                                    	<td>Employer</td>
                                        <td>:</td>
                                        <td id="div_employer"></td>
                                    </tr>
                                    <tr>
                                    	<td>Employee Name</td>
                                        <td>:</td>
                                        <td id="div_employee_name"></td>
                                    </tr>
                                    <tr>
                                    	<td>Designation</td>
                                        <td>:</td>
                                        <td id="div_designation"></td>
                                    </tr>
                                    <tr>
                                    	<td>Employee Nationality</td>
                                        <td>:</td>
                                        <td id="div_nationality"></td>
                                    </tr>
                                    <tr>
                                    	<td>Employee Email</td>
                                        <td>:</td>
                                        <td id="div_email"></td>
                                    </tr>
                                     <tr>
                                    	<td>Client Name</td>
                                        <td>:</td>
                                        <td id="div_client"></td>
                                    </tr>
                                    <tr>
                                    	<td>Client Hiring Manager</td>
                                        <td>:</td>
                                        <td id="div_hiring_manager_name"></td>
                                    </tr>
                                     <tr>
                                    	<td>Hiring Manager Tel.No.</td>
                                        <td>:</td>
                                        <td id="div_hiring_manager_telno"></td>
                                    </tr>
                                    <tr>
                                    	<td>Hiring Manager Email</td>
                                        <td>:</td>
                                        <td id="div_hiring_manager_email"></td>
                                    </tr>
                                     <tr>
                                    	<td>Client Invoice Contact Person</td>
                                        <td>:</td>
                                        <td id="div_client_invoice_contact_person"></td>
                                    </tr>
                                    <tr>
                                    	<td>Client Invoice Address To</td>
                                        <td>:</td>
                                        <td id="div_client_invoice_address_to"></td>
                                    </tr>
                                     <tr>
                                    	<td>Start Date</td>
                                        <td>:</td>
                                        <td id="div_start_date"></td>
                                    </tr>
                                    <tr>
                                    	<td>End Date</td>
                                        <td>:</td>
                                        <td id="div_end_date"></td>
                                    </tr>
                                     <tr>
                                    	<td>Duration of Contract</td>
                                        <td>:</td>
                                        <td id="div_duration"></td>
                                    </tr>
                                    <tr>
                                    	<td>Notice Period</td>
                                        <td>:</td>
                                        <td id="div_notice_period"></td>
                                    </tr>
                                    <tr>
                                    	<td>Billing Amount </td>
                                        <td>:</td>
                                        <td id="div_billing_amount"></td>
                                    </tr>
                                     <tr>
                                    	<td>Billing Amount with GST/SST </td>
                                        <td>:</td>
                                        <td id="div_billing_amount_with_gst"></td>
                                    </tr>
                                    <tr>
                                    	<td>Basic Salary </td>
                                        <td>:</td>
                                        <td id="div_salary"></td>
                                    </tr>
                                    <tr id="div_daily_salary1">
                                    	<td>Daily Salary </td>
                                        <td>:</td>
                                        <td id="div_daily_salary"></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Total Salary </b></td>
                                        <td>:</td>
                                        <td id="div_total_salary"></td>
                                    </tr>
                                    <tr>
                                    	<td>EPF </td>
                                        <td>:</td>
                                        <td id="div_epf"></td>
                                    </tr>
                                    <tr>
                                    	<td>SOCSO </td>
                                        <td>:</td>
                                        <td id="div_socso"></td>
                                    </tr>
                                    <tr>
                                    	<td>EIS </td>
                                        <td>:</td>
                                        <td id="div_eis"></td>
                                    </tr>
                                    <tr>
                                    	<td>HRDF </td>
                                        <td>:</td>
                                        <td id="div_hrdf"></td>
                                    </tr>
                                    <tr>
                                    	<td>Referral </td>
                                        <td>:</td>
                                        <td id="div_referral"></td>
                                    </tr>
                                    <tr>
                                    	<td>External Recruiter </td>
                                        <td>:</td>
                                        <td id="div_ext_recruiter"></td>
                                    </tr>
                                    <tr>
                                    	<td>External Sales </td>
                                        <td>:</td>
                                        <td id="div_ext_sales"></td>
                                    </tr>
                                    </tbody>
                                </table>

                                <table width="100%" class="table-bordered table-condensed">
                                	<thead>
                                	<tr>
                                    	<th width="30%">ITEM </th>
                                        <th>COST			 </th>
                                        <th>QUANTITY 	 	 </th>
                                        <th>MONTHLY COST 	 </th>
                                    </tr>
                                    </thead>
                                    <tbody id="tbl_summary_cost_body">
                                    <tr>
                                    	<td>Flight Ticket</td>
                                        <td id="div_flight_ticket_cost"></td>
                                        <td id="div_flight_ticket_cost_duration"></td>
                                        <td id="div_monthly_flight_ticket_cost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Temporary Accommodation</td>
                                        <td id="div_temp_accommodation_cost"></td>
                                        <td id="div_temp_accommodation_cost_duration"></td>
                                        <td id="div_monthly_temp_accommodation_cost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Laptop Cost</td>
                                        <td id="div_laptop_cost"></td>
                                        <td id="div_laptop_cost_duration"></td>
                                        <td id="div_monthly_laptop_cost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Notice Period Buyout</td>
                                        <td id="div_notice_period_buyout"></td>
                                        <td id="div_notice_period_buyout_duration"></td>
                                        <td id="div_monthly_notice_period_buyout"></td>
                                    </tr>
                                    <tr>
                                    	<td>EP Cost</td>
                                        <td id="div_epcost"></td>
                                        <td id="div_epcost_duration"></td>
                                        <td id="div_monthly_epcost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Overseas Visa Cost</td>
                                        <td id="div_overseas_visa_cost"></td>
                                        <td id="div_overseas_visa_cost_duration"></td>
                                        <td id="div_monthly_overseas_visa_cost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Out Patient Medical Cost</td>
                                        <td id="div_outpatient_medical_cost"></td>
                                        <td id="div_outpatient_medical_cost_duration"></td>
                                        <td id="div_monthly_outpatient_medical_cost"></td>
                                    </tr>
                                    <tr>
                                    	<td>Medical Insurance</td>
                                        <td id="div_medical_insurance_cost"></td>
                                        <td id="div_medical_insurance_cost_duration"></td>
                                        <td id="div_monthly_medical_insurance_cost"></td>
                                    </tr>
                                    </tbody>
                                </table>
                                
                                
                                <table  width="100%" class="table-striped table-condensed">
                                	<tbody>
                                	<tr>
                                    	<td width="30%"><b>Total Cost to Company</b></td>
                                        <td width="2%">:</td>
                                        <td id="div_total_cost_to_company"></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Gross Profit Margin Per Month</b></td>
                                        <td>:</td>
                                        <td id="div_monthly_gross_profit_margin"></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Total Gross Profit</b></td>
                                        <td>:</td>
                                        <td id="div_total_gross_profit"></td>
                                    </tr>
                                    <tr>
                                    	<td><b>Gross Profit(%)</b></td>
                                        <td>:</td>
                                        <td id="div_gross_profit_percentage"></td>
                                    </tr>
                                    <tr>
                                    	<td>Travelling Claims Applicable</td>
                                        <td>:</td>
                                        <td id="div_travelling_claim_applicable"></td>
                                    </tr>
                                    <tr>
                                    	<td>Medical Claim Applicable</td>
                                        <td>:</td>
                                        <td id="div_medical_claim_applicable"></td>
                                    </tr>
                                    <tr>
                                    	<td>Overtime Applicable</td>
                                        <td>:</td>
                                        <td id="div_overtime_claim_applicable"></td>
                                    </tr>
                                    <tr>
                                    	<td>No. of Medical Leave Given By Client</td>
                                        <td>:</td>
                                        <td id="div_medical_leave_day_by_client"></td>
                                    </tr>
                                    <tr>
                                    	<td>No. of Annual Leave Given By Client</td>
                                        <td>:</td>
                                        <td id="div_annual_leave_day_by_client"></td>
                                    </tr>
                                    <tr>
                                    	<td>Replacement Leave Applicable</td>
                                        <td>:</td>
                                        <td id="div_replacement_leave_applicable"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                   </div>

                </div>
				<!--Contract Information End-->


            </div> <!-- container -->
        </div> <!--wrap -->
    </div> <!-- page-content -->




    <!-- Modal Remark Start-->
    <div class="modal fade" id="remarkModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Confirmation</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ct_no" id="ct_no">
                    <input type="hidden" name="chk_level" id="chk_level">
                    <div class="row">
                        <div class="col-sm-12">
                            Please confirm the selection and add the remark below.
                        </div>
                        <div class="col-sm-12"><br><br></div>
                        
                        <div class="col-sm-4">
                            Remark:
                        </div>
                        <div class="col-sm-8">
                            <textarea name="ct_remark" id="ct_remark" cols="50" rows="3" required="required"></textarea>
                        </div>
  
                        <div id="offer_letter">
                        
                        </div>
                        <div class="col-sm-12" id="div_overwrite_ref"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_approve">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Remark End-->

    <!-- Modal Remark List Start-->
    <div class="modal fade" id="remarkListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Remark List</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="contract_no" id="contract_no">
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
                        

                        <div class="col-sm-4">Send To</div>
                        <div class="col-sm-8">
                        	<select id="dd_send_to" style="width:100%" class="populate" required="required">
                            	<option value="">Please Select</option>
                                <?php
                                for($i = 0; $i < count($sent_to); $i++)
                               	{
                                ?>
                            		<option value="<?php echo($sent_to[$i]['id']) ?>"><?php echo($sent_to[$i]['name']) ?></option>
                                <?php
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-sm-12"><br></div>
                        <div class="col-sm-4">
                            Remark:
                        </div>
                        <div class="col-sm-8">
                            <textarea name="contract_remark" id="contract_remark" cols="50" rows="3" required="required"></textarea>
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
   <!-- Modal Remark List End-->

	
	<!-- Modal Remark for Reference Start-->
    <div class="modal fade" id="reference_remark_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Reference Remarks</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="reference_row_id" id="reference_row_id">
                    <div class="row">
                        <div class="col-sm-12">
                            Please enter the reference and click apply the remark below.
                        </div>
                        <div class="col-sm-12">
                            <textarea style="width: 100%" name="txt_reference_remark" id="txt_reference_remark" cols="50" rows="20" required="required"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_reference_apply">Apply</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Remark for Reference End-->
    
    
    <!-- Modal for approval revoke Start-->
    <div class="modal fade" id="approval_revoke_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="div_revoke_approval_title">Revoke Approval</h4>
                </div>
                <div class="modal-body">
					<input type="hidden" name="revoke_contract_no" id="revoke_contract_no">
                	<ul class="panel-tasks">
                    	<li class="item-danger">
                      		<label>
                            		<input type="checkbox" id="chk_sales_head_approval"> 
                             		<span class="task-description">Sales Head</span>
                  			</label>
                		</li>
                     	<li class="item-primary">
      						<label>
                            		<input type="checkbox" id="chk_hr_approval"> 
                              		<span class="task-description">Human Resource</span>
                    		</label>
						</li>
                     	<li class="item-info">
                       			<label>
                           			<input type="checkbox" id="chk_accounts_approval"> 
                                  	<span class="task-description">Accounts</span>
                               	</label>
                       	</li>
                      	<li class="item-success">
                        	<label>
                                <input type="checkbox" id="chk_ceo_approval"> 
                               	<span class="task-description">CEO</span>
                            </label>
                       	</li>
                       	<li>
                        	<label>
                                <input type="checkbox" id="chk_offer_letter_approval">
                               	<span class="task-description">Human Resource (Offer Letter Issued)</span>
                            </label>
                       	</li>
                 	</ul>
                 	
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="btn_revoke_approval">Apply</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Remark approval revoke End-->
    
	
	


<?php include $current_path . "footer.php" ?>
