<?php
/**
 *
 *
 * index.php
 *
 * @author: Sancheev
 * @version: 1.0 - created
 */
$current_path = '../../';
include $current_path . 'header.php';

$leave_type   = db_query('id,descr','cms_master_list',"category_id = 16");
$where = '';
if ($_SESSION['is_supervisor'] == 1 && $_SESSION['is_admin'] == 0)
{
	$where = "reporting_to_id = " . $_SESSION['emp_id'] . ' AND is_active = 1';
}
$emp        = db_query('id,name','cms_employees', $where);

?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">

        </div>

        <div class="container">
            <h2><i class="fa fa-bar-chart-o"></i> Leave  Report</h2>
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
                            <form class="form-horizontal" data-validate="parsley" id="search_form">

                                <div class="form-group">
                                    <label for="doc_date" class="col-sm-1 control-label">Employee</label>
                                    <div class="col-sm-4">

                                        <select id="dd_employee" style="width:100%" class="populate">
                                            <?php
                                            if ($_SESSION['is_admin'] == 1){
                                             ?>
        									    <option value="ALL">All</option>
            								<?php
                                            }
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
                                        <button class="btn btn-default" id="leave_date">
                                            <i class="fa fa-calendar-o"></i>
                                            <span class="hidden-xs hidden-sm"></span> <b class="caret"></b>
                                            <input type="hidden" id="from_date" value="">
                    						<input type="hidden" id="to_date" value="">
                                        </button>

        							</div>
                                </div>

                                <div class="form-group">
                                    <label for="log_date" class="col-sm-1 control-label">Leave Type</label>
                                    <div class="col-sm-4">
                                        <select id="dd_leave_type" style="width:100%" class="populate">
                                        	<option value="ALL">All</option>
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
                                            <input type="checkbox" id="chk_is_paid" name="chk_is_paid" checked="checked">Paid
                                        </label>
                                    </div>
                                     <div class="col-sm-2">
                                        <label class="checkbox-inline">
                                            <input type="checkbox" id="chk_is_unpaid" name="chk_is_unpaid" checked="checked">Unpaid
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

                <div id="div_report_view" class="col-sm-12" style="display:none;">
					<div class="panel panel-grape">
					  	<div class="panel-body">
                            <!--Start PDF icon-->
                            <div class="pull-right">
                                <div class="options">
                                    <div class="btn-toolbar">
                                        <div class="btn-group hidden-xs">
                                            <a href='#' class="btn btn-default dropdown-toggle" data-toggle='dropdown'><i class="fa fa-cloud-download"></i><span class="hidden-xs hidden-sm hidden-md"> Export as</span> <span class="caret"></span></a>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" onclick="exportTable2CSV('Leave Report','tbl_list')">Excel File (*.xlsx)</a></li>
                                                <li><a href="#" onclick="exportTable2PDF('Leave Report','tbl_list','l')">PDF File (*.pdf)</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <br />
                            <!--End PDF icon-->
						<div class="table-responsive">
							<table class="table table-bordered table-striped" id="tbl_list">
								<thead>
									<tr>
										<th width="20%">Name</th>
										<th width="13%">AL Date</th>
                                        <th width="13%">MC Date</th>
                                        <th width="13%">Unpaid Date</th>
                                        <th width="13%"></th>
                                        <th width="13%"></th>
                                        <th width="13%"></th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					  </div>
					</div>
				</div>

            </div>

        </div> <!-- container -->

    </div> <!--wrap -->
</div> <!-- page-content -->


    </div>



<?php include $current_path . "footer.php" ?>
