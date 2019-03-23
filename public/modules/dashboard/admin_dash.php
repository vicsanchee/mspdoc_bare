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

$app_count      = db_query('count(*) as count','cms_appt inner join cms_employees on cms_employees.id = cms_appt.created_by','status in(0) and cms_employees.is_active=1');
$doc_count      = db_query('count(*) as count','cms_documents','viewed in(0)');
$asset_count    = db_query('count(*) as count','cms_assets','');
$leave_count    = db_query('count(*) as count','cms_leave_by_day','approved in(0)');

?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">
            <h2><i class="fa fa-home"></i> Dashboard</h2>
            
<!--             <div class="options"> -->
<!--                 <div class="btn-toolbar"> -->
<!--                     <button class="btn btn-default" id="daterangepicker"> -->
<!--                         <i class="fa fa-calendar-o"></i>  -->
<!--                         <span class="hidden-xs hidden-sm"><?php //echo date("F j, Y", strtotime('-30 day')); ?> - <?php //echo date("F j, Y"); ?></span> <b class="caret"></b>
<!--                     </button> -->
<!--                 </div> -->
<!--             </div> -->
            
        </div>


        <div class="container-fluid">
			<div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <a class="info-tiles tiles-toyo" href="<?php echo $current_path ?>modules/appointments/appointmentReport.php">
                                <div class="tiles-heading">Total Open Appointments</div>
                                <div class="tiles-body-alt">
                                    <i class="fa fa-calendar"></i>
                                    <div class="text-center"><span class="text-top"></span><?php echo ($app_count[0]['count']); ?><span class="text-smallcaps"></span></div>
                                    <small></small>
                                </div>
                                <div class="tiles-footer" id="btn_appointment_more">more info</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <a class="info-tiles tiles-success" href="<?php echo $current_path ?>modules/claims/documents.php">
                                <div class="tiles-heading">Total Document (Not Viewed)</div>
                                <div class="tiles-body-alt">
                                    <i class="fa fa-cloud-upload"></i>
                                    <div class="text-center"><span class="text-top"></span><?php echo ($doc_count[0]['count']); ?><span class="text-smallcaps"></span></div>
                                    <small></small>
                                </div>
                                <div class="tiles-footer">go to documents</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <a class="info-tiles tiles-orange" href="<?php echo $current_path ?>modules/employees/asset.php">
                                <div class="tiles-heading">Total Assets</div>
                                <div class="tiles-body-alt">
                                    <i class="fa fa-laptop"></i>
                                    <div class="text-center"><?php echo ($asset_count[0]['count']); ?></div>
                                    <small></small>
                                </div>
                                <div class="tiles-footer" id="btn_asset_more">more info</div>
                            </a>
                        </div>
                        <div class="col-md-3 col-xs-12 col-sm-6">
                            <a class="info-tiles tiles-alizarin" href="<?php echo $current_path ?>modules/leave/leaveInfo.php">
                                <div class="tiles-heading">Leave Pending Approvals</div>
                                <div class="tiles-body-alt">
                                    <i class="fa fa-plane"></i>
                                    <div class="text-center"><?php echo ($leave_count[0]['count']); ?></div>
                                    <small></small>
                                </div>
                                <div class="tiles-footer" id="btn_leave_more">more info</div>
                            </a>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-12" id="div_appointment_details" style="display: none">
                    <div class="panel panel-indigo">
                        <div class="panel-heading">
                            <h4>Employees</h4>
                            <div class="options">
							     <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
						    </div>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" >
                                <table class="table" style="margin-bottom: 0px;" id="tbl_appt_dashboard">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-9 col-sm-3">User</th>
                                            <th class="col-xs-2 col-sm-2">Total</th>
                                            <th class="col-xs-2 col-sm-2">Open</th>
                                            <th class="col-xs-2 col-sm-2">Follow Up</th>
                                            <th class="col-xs-2 col-sm-2">Took Place</th>
                                            <th class="col-xs-2 col-sm-2">Postponed</th>
                                            <th class="col-xs-2 col-sm-2">Cancelled</th>
                                        </tr>
                                    </thead>
                                    <tbody class="selects">
                                        
                                        
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12" id="div_asset_details" style="display: none">
                    <div class="panel panel-indigo">
                        <div class="panel-heading">
                            <h4>Assets Summary</h4>
                            <div class="options">
							     <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
						    </div> 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive" >
                                <table class="table" style="margin-bottom: 0px;" id="tbl_asset_dashboard">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-2 col-sm-3">Type</th>
                                            <th class="col-xs-2 col-sm-2">Count</th>
                                            <th class="col-xs-2 col-sm-2">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody class="selects">
                                        
                                        
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12" id="div_asset_details_expiry" style="display: none">
                    <div class="panel panel-indigo">
                        <div class="panel-heading">
                            <h4>Assets Details</h4>
                            <div class="options">
							     <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
						    </div> 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table" style="margin-bottom: 0px;" id="tbl_asset_dashboard_detail">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-2 col-sm-3">Type</th>
                                            <th class="col-xs-2 col-sm-2">Brand Name</th>
                                            <th class="col-xs-2 col-sm-2">Value</th>
                                            <th class="col-xs-2 col-sm-2">Expiry Date</th>
                                            <th class="col-xs-2 col-sm-2">PIC</th>
                                        </tr>
                                    </thead>
                                    <tbody class="selects">
                                        
                                        
                                    </tbody>
                                   
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                
                
                <div class="col-md-12" id="div_leave_details" style="display: none">
                    <div class="panel panel-indigo">
                        <div class="panel-heading">
                            <h4>Leave Details</h4>
                            <div class="options">
							     <a href="javascript:;" class="panel-collapse"><i class="fa fa-chevron-down"></i></a>
						    </div> 
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table" style="margin-bottom: 0px;" id="tbl_leave_dashboard">
                                    <thead>
                                        <tr>
                                            <th class="col-xs-2 col-sm-3">User</th>
                                            <th class="col-xs-2 col-sm-2">Annual Leave / Taken</th>
                                            <th class="col-xs-2 col-sm-2">Medical Leave / Taken</th>
                                            <th class="col-xs-2 col-sm-2">Emergency Leave</th>
                                            <th class="col-xs-2 col-sm-2">Total Leave Taken</th>
                                            <th class="col-xs-2 col-sm-1">Balance</th>
                                            <th class="col-xs-2 col-sm-2">UnPaid Leave</th>
                                        </tr>
                                    </thead>
                                    <tbody class="selects">
                                        
                                        
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

   
<?php include $current_path . "footer.php" ?>
