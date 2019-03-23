<?php

$current_path = '../../';
include $current_path . 'header.php';
$emp = array();

$access = get_accessibility(16,$_SESSION['access']);
if($access->view_it_all == 1)
{
    $emp        	    = db_query('id,name,office_email','cms_employees','is_active = 1');
}
else
{
    $emp        	    = db_query('id,name,office_email','cms_employees',"id =" . $_SESSION['emp_id'] . " AND is_active = 1");
}

$client     		    = db_query('id,name','cms_clients',"is_active = 1");

$employer    		    = db_query('id,employer_name','cms_master_employer',"is_active = 1");

$sql 		            = "SELECT cms_appt_pic.id,cms_appt_pic.name,cms_appt_pic.email, cms_clients.name as company_name
                           FROM cms_appt_pic INNER JOIN cms_clients ON cms_appt_pic.client = cms_clients.id
                           WHERE cms_appt_pic.created_by = " . $_SESSION['emp_id'] . " 
                           UNION ALL
                           SELECT cms_employees.id, cms_employees.name, cms_employees.office_email as email,
                           cms_master_employer.employer_name  as company_name
                           FROM
                           cms_employees INNER JOIN cms_master_employer ON cms_employees.employer_id = cms_master_employer.id
                           WHERE cms_employees.is_active = 1";

$to   		            = db_execute_custom_sql($sql);

$template	            = db_query('template_content','cms_master_template',"id = 26");

$outbound_status	    = db_query('id,descr','cms_master_list',"category_id = 34");

$outbound_category      = db_query('id,descr','cms_master_list',"category_id = 35");


?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h2><i class="fa fa-exchange"></i> Outbound Document</h2>
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
                                    <label for="dd_category_search" class="col-sm-2 control-label">Category</label>
                                    <div class="col-sm-4">
                                        <select id="dd_category_search" style="width:100%" class="populate">
                                            <option value="">Please Select</option>
                                            <?php
                                            for($i = 0; $i < count($outbound_category); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($outbound_category[$i]['id']) ?>"><?php echo($outbound_category[$i]['descr']) ?></option>
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
                                            for($i = 0; $i < count($outbound_status); $i++)
                                            {
                                                ?>
                                                <option value="<?php echo($outbound_status[$i]['id']) ?>"><?php echo($outbound_status[$i]['descr']) ?></option>
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
                            <h4>Outbound List</h4>
                            <div class="options">
                                <a href="#" class="btn-panel-opts" title="Add New Appointment" id="btn_new">
                                    <i class="fa fa-plus fa-fw" aria-hidden="true"></i>
                                    <span class="hidden-xs">New Document</span>
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
                                                <li><a href="#" onclick="exportTable2CSV('Outbound Document','tbl_list')">Excel File (*.xlsx)</a></li>
                                                <li><a href="#" onclick="exportTable2PDF('Outbound Document','tbl_list','p')">PDF File (*.pdf)</a></li>
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
                                        <th>Document No.</th>
                                        <th>Date</th>
                                        <th>Client</th>
                                        <th>Category</th>
                                        <th>Created By</th>
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
                            <h4 id="h4_primary_no">Outbound Number : -</h4>
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
                                            <a data-toggle="tab" href="#tab-one">Details</a>
                                        </li>
                                        <li><a data-toggle="tab" href="#tab-two">Email Content</a></li>
                                    </ul>

                                    <div class="tab-content">

                                        <!--Outbound Start-->
                                        <div id="tab-one" class="tab-pane active">
                                            <p>

                                            <div class="form-group">
                                                <label for="contract_date" class="col-sm-2 control-label">Date</label>
                                                <div class="col-sm-4">
                                                    <div class="input-group date">
                                                        <input type="text" id="outbound_date" class="form-control" data-date-format="dd-mm-yyyy" data-format="dd-mm-yyyy" placeholder="e.g dd-mm-yyyy" required="required">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-calendar"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <label for="dd_client" class="col-sm-2 control-label">Client Name</label>
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
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Category</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_category" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($outbound_category); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($outbound_category[$i]['id']) ?>"><?php echo($outbound_category[$i]['descr']) ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <label class="col-sm-2 control-label">Attention To</label>
                                                <div class="col-sm-4">
                                                    <input type="text" class="form-control marginBottom10px" id="txt_attention_to">
                                                </div>
                                            </div>


                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Status</label>
                                                <div class="col-sm-4">
                                                    <select id="dd_status" style="width:100%" class="populate" required="required">
                                                        <option value="">Please Select</option>
                                                        <?php
                                                        for($i = 0; $i < count($outbound_status); $i++)
                                                        {
                                                            ?>
                                                            <option value="<?php echo($outbound_status[$i]['id']) ?>"><?php echo($outbound_status[$i]['descr']) ?></option>
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

                                            <div class="form-group">
                                                <label class="col-sm-2 control-label">Amount</label>
                                                <div class="col-sm-4">
                                                    <input type="number" class="form-control marginBottom10px" id="txt_amount">
                                                </div>
                                                <label class="control-label col-sm-2">Remark</label>
                                                <div class="col-sm-4">
                                                    <textarea name="txt_remark" id="txt_remark" class="form-control marginBottom10px"></textarea>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label class="control-label col-sm-2">Email</label>
                                                <div class="col-sm-4 error-container">
                                                    <select id="dd_email" class="populate" multiple="multiple">
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
                                                <label id="lbl_upload" class="col-sm-2 control-label">Attachments</label>
                                                <div class="col-sm-4">
                                                    <span class="btn btn-success fileinput-button" id="btn_add_image">
                                                        <span>Browse File</span>
                                                        <input id="doc_upload" type="file" name="files[]">
                                                    </span>
                                                    <br/>
                                                    <span id="document" style="margin-top: 8px"></span>
                                                </div>
                                            </div>

                                            </p>
                                        </div>
                                        <!--Outbound End-->

                                        <!--Email Content Start-->
                                        <div id="tab-two" class="tab-pane">

                                            <p>

                                            <div class="form-group">
                                                <div class="col-md-12" contenteditable="true" id="text_editor"></div>
                                                <input type="hidden" id="txt_template" value="<?php echo(base64_encode($template[0]['template_content'])); ?>">
                                            </div>

                                            </p>
                                        </div>
                                        <!--Email Content End-->

                                    </div>

                                </div>

                            </form>

                            <p class="pull-right">
                            <div class="btn-toolbar" style="text-align:right">
                                <a href="#" class="btn btn-default" id="btn_cancel"><i class="fa fa-times"></i> Cancel</a>
                                <a href="#" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_send_email"><i class="fa fa-envelope"></i> Send Email</a>
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
                <input type="hidden" name="outbound_document" id="outbound_document">
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
                            <textarea name="outbound_remark" id="outbound_remark" class="form-control marginBottom10px" required="required"></textarea>
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
