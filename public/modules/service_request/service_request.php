<?php

$current_path = '../../';
include $current_path . 'header.php';
$emp = array();

$access = get_accessibility(18,$_SESSION['access']);
if($access->view_it_all == 1)
{
    $emp        	= db_query('id,name,office_email','cms_employees','is_active = 1');
}
else
{
    $emp        	= db_query('id,name,office_email','cms_employees',"id =" . $_SESSION['emp_id'] . " AND is_active = 1");
}

$category     		= db_query('id,descr','cms_master_list',"category_id = 39");

$employer    	    = db_query('id,employer_name','cms_master_employer',"is_active = 1");

$client     		= db_query('id,name','cms_clients',"is_active = 1");

$status             = db_query('id,descr','cms_master_list',"category_id = 40");

$payment_term       = db_query('id,descr','cms_master_list',"category_id = 5");

$asset_type        = db_query('id,descr','cms_master_list',"category_id = 20");


?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h2><i class="fa fa-cubes"></i> Service Request</h2>
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
                                    <label for="dd_category_search" class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-4">
                                        <select id="dd_category_search" style="width:100%" class="populate">
                                            <option value="">Please Select</option>
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
                                    <label for="dd_company_search" class="col-sm-2 control-label">Company</label>
                                    <div class="col-sm-4">
                                        <select id="dd_company_search" style="width:100%" class="populate">
                                            <option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($employer); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($employer[$i]['id']) ?>"><?php echo($employer[$i]['employer_name']) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <label for="dd_status_search" class="col-sm-2 control-label">Status</label>
                                    <div class="col-sm-4">
                                        <select id="dd_status_search" style="width:100%" class="populate">
                                            <option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($status); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($status[$i]['id']) ?>"><?php echo($status[$i]['descr']) ?></option>
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
                            <h4>Service Request List</h4>
                            <div class="options">
                                <a href="#" class="btn-panel-opts" title="Add New Appointment" id="btn_new">
                                    <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">New Service Request</span>
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
                                                <li><a href="#" onclick="exportTable2CSV('Servive Request','tbl_list')">Excel File (*.xlsx)</a></li>
                                                <li><a href="#" onclick="exportTable2PDF('Service Request','tbl_list','p')">PDF File (*.pdf)</a></li>
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
                                        <th>Service No.</th>
                                        <th>Category</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
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
                            <h4 id="h4_primary_no">Service Request Number : -</h4>
                            <div class="options">
                                <a href="#" id="btn_back" class="btn-panel-opts" title="Back to list">
                                    <i class="fa fa-arrow-circle-left fa-lg fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">Back to list</span>
                                </a>
                            </div>
                        </div>

                        <div class="panel-body">

                            <!--Service Request Start-->
                            <form class="form-horizontal" data-validate="parsley" id="detail_form">

                                <div class="form-group">
                                    <label for="dd_category" class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-4">
                                        <select id="dd_category" style="width:100%" class="populate" required="required">
                                            <option value="">Please Select</option>
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
                                    <label for="dd_company" class="col-sm-2 control-label">Company</label>
                                    <div class="col-sm-4">
                                        <select id="dd_company" style="width:100%" class="populate" required="required">
                                            <option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($employer); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($employer[$i]['id']) ?>"><?php echo($employer[$i]['employer_name']) ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                            </form>

                            <div id="div_invoice" style="display: none">
                                <form class="form-horizontal" data-validate="parsley" id="invoice_form">

                                    <div class="form-group">
                                        <label for="dd_invoice_client" class="col-sm-2 control-label">Client Name</label>
                                        <div class="col-sm-4">
                                            <select id="dd_invoice_client" style="width:100%" class="populate" required="required">
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
                                        <label class="control-label col-sm-2">Contact Person</label>
                                        <div class="col-sm-4">
                                            <input id="txt_invoice_contact_person" type="text" class="form-control marginBottom10px" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Descirption of service / Supply of Products</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_invoice_description" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                        <label class="control-label col-sm-2">Bank Account Details</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_invoice_bank_details" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Unit Price (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_invoice_unit_price" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                        <label class="control-label col-sm-2">Quantity</label>
                                        <div class="col-sm-4">
                                            <input id="txt_invoice_quantity" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_invoice_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required" disabled="disabled">
                                        </div>
                                        <label for="dd_invoice_gst_sst" class="control-label col-sm-2">GST/SST</label>
                                        <div class="col-sm-4">
                                            <select id="dd_invoice_gst_sst" style="width:100%" class="populate" required="required">
                                                <option value="0" selected="selected">0%</option>
                                                <option value="6">6%</option>
                                                <option value="10">10%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Total Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_invoice_total_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required" disabled="disabled">
                                        </div>
                                        <label for="dd_invoice_payment_terms" class="control-label col-sm-2">Payment Terms</label>
                                        <div class="col-sm-4">
                                            <select id="dd_invoice_payment_terms" style="width:100%" class="populate" required="required">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($payment_term); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($payment_term[$i]['id']) ?>"><?php echo($payment_term[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dd_invoice_status" class="control-label col-sm-2">Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_invoice_status" style="width:100%" class="populate" required="required">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($status); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($status[$i]['id']) ?>"><?php echo($status[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 control-label">Attachments</label>
                                        <div class="col-sm-4">
                                            <span class="btn btn-success fileinput-button">
                                                <span>Browse File</span>
                                                <input id="doc_invoice_upload" type="file" name="files[]">
                                            </span>
                                            <br/>
                                            <span id="document_invoice" style="margin-top: 8px"></span>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="div_payment" style="display: none">
                                <form class="form-horizontal" data-validate="parsley" id="payment_form">

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Purpose / Descirption of service / Purchase of Products</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_payment_description" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                        <label class="control-label col-sm-2">Bank Account Details</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_payment_bank_details" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Unit Price (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_payment_unit_price" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                        <label class="control-label col-sm-2">Quantity</label>
                                        <div class="col-sm-4">
                                            <input id="txt_payment_quantity" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_payment_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required" disabled="disabled">
                                        </div>
                                        <label for="dd_payment_gst_sst" class="control-label col-sm-2">GST/SST</label>
                                        <div class="col-sm-4">
                                            <select id="dd_payment_gst_sst" style="width:100%" class="populate" required="required">
                                                <option value="0" selected="selected">0%</option>
                                                <option value="6">6%</option>
                                                <option value="10">10%</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Total Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_payment_total_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required" disabled="disabled">
                                        </div>
                                        <label class="col-sm-2 control-label">Payable To</label>
                                        <div class="col-sm-4">
                                            <input id="txt_payment_payable_to" type="text" class="form-control marginBottom10px" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="dd_payment_status" class="control-label col-sm-2">Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_payment_status" style="width:100%" class="populate" required="required">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($status); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($status[$i]['id']) ?>"><?php echo($status[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label class="col-sm-2 control-label">Attachments</label>
                                        <div class="col-sm-4">
                                            <span class="btn btn-success fileinput-button">
                                                <span>Browse File</span>
                                                <input id="doc_payment_upload" type="file" name="files[]">
                                            </span>
                                            <br/>
                                            <span id="document_payment" style="margin-top: 8px"></span>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="div_loan" style="display: none">
                                <form class="form-horizontal" data-validate="parsley" id="loan_form">

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Purpose of Advance</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_loan_description" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                        <label class="control-label col-sm-2">Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_loan_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Number of Repayment</label>
                                        <div class="col-sm-4">
                                            <input id="txt_loan_repayment_number" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                        <label class="control-label col-sm-2">Each Repayment Amount (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_loan_repayment_amount" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Advance or Loan (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_loan_advance" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                        <label class="control-label col-sm-2">Current Balance Advance / Loan (RM)</label>
                                        <div class="col-sm-4">
                                            <input id="txt_loan_balance" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Date Advance / Loan required</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date">
                                                <input type="text" id="date_loan_required" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            </div>
                                        </div>
                                        <label for="dd_loan_status" class="control-label col-sm-2">Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_loan_status" style="width:100%" class="populate" required="required">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($status); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($status[$i]['id']) ?>"><?php echo($status[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Attachments</label>
                                        <div class="col-sm-4">
                                            <span class="btn btn-success fileinput-button">
                                                <span>Browse File</span>
                                                <input id="doc_loan_upload" type="file" name="files[]">
                                            </span>
                                            <br/>
                                            <span id="document_loan" style="margin-top: 8px"></span>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div id="div_asset" style="display: none">
                                <form class="form-horizontal" data-validate="parsley" id="asset_form">

                                    <div class="form-group">
                                        <label for="dd_asset_type" class="col-sm-2 control-label">Type of Assets</label>
                                        <div class="col-sm-4">
                                            <select id="dd_asset_type" style="width:100%" class="populate" multiple="multiple" required="required">
                                                <?php
                                                for($i = 0; $i < count($asset_type); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($asset_type[$i]['id']) ?>"  data-val="<?php echo(json_encode($asset_type[$i])); ?>"><?php echo($asset_type[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <label class="control-label col-sm-2">Duration</label>
                                        <div class="col-sm-4">
                                            <input id="txt_asset_duration" type="number" class="form-control marginBottom10px" max="1000000" min="0" step="2" required="required">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Purpose</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_asset_description" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                        <label class="control-label col-sm-2">Remarks</label>
                                        <div class="col-sm-4">
                                            <textarea id="txt_asset_remark" class="form-control marginBottom10px" required="required"></textarea>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-sm-2">Date Needed</label>
                                        <div class="col-sm-4">
                                            <div class="input-group date">
                                                <input type="text" id="date_asset_needed" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                <span class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </span>
                                            </div>
                                        </div>
                                        <label for="dd_asset_status" class="control-label col-sm-2">Status</label>
                                        <div class="col-sm-4">
                                            <select id="dd_asset_status" style="width:100%" class="populate" required="required">
                                                <option value="">Please Select</option>
                                                <?php
                                                for($i = 0; $i < count($status); $i++)
                                                {
                                                    ?>
                                                    <option value="<?php echo($status[$i]['id']) ?>"><?php echo($status[$i]['descr']) ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="col-sm-2 control-label">Attachments</label>
                                        <div class="col-sm-4">
                                            <span class="btn btn-success fileinput-button">
                                                <span>Browse File</span>
                                                <input id="doc_asset_upload" type="file" name="files[]">
                                            </span>
                                            <br/>
                                            <span id="document_asset" style="margin-top: 8px"></span>
                                        </div>
                                    </div>

                                </form>
                            </div>
                            <!--Service Request End-->

                            <p class="pull-right">
                            <div id="div_button" class="btn-toolbar" style="text-align:right; display: none;">
                                <a href="#" class="btn btn-default" id="btn_cancel"><i class="fa fa-times"></i> Cancel</a>
                                <a href="#" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_save"><i class="fa fa-check"></i> Save</a>
                            </div>
                            </p>

                        </div>
                    </div>

                </div>

            </div>


        </div> <!-- container -->
    </div> <!--wrap -->
</div> <!-- page-content -->



<!-- Modal Remark List Start-->
<div class="modal fade" id="remarkListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" data-validate="parsley" id="remark_form">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Remark List</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="service_request" id="service_request">
                    <div class="row">
                        <div id="remarkList" class="col-sm-12">
                            <table class="table" id="tbl_remark_list">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Remark</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <div class="col-sm-12"><br></div>

                        <div class="col-sm-12">
                            Remark:
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <textarea name="service_remark" id="service_remark" class="form-control marginBottom10px" required="required"></textarea>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_add_remark">Save</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modal Remark List End-->


<?php include $current_path . "footer.php" ?>
