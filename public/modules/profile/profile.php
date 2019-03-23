<?php

$current_path = '../../';
include $current_path . 'header.php';


$created_by 	= db_query('id,name','cms_employees','');
$dept       	= db_query('id,descr','cms_master_list',"category_id = 1");
$doc_type   	= db_query('id,descr','cms_master_list',"category_id = 2");
$notice_period  = db_query('id,descr','cms_master_list',"category_id = 8");
$leave_type		= db_query('id,descr,no_of_days','cms_master_list',"category_id = 16");
$client     	= db_query('id,employer_name','cms_master_employer','');
// $contract     	= db_query('contract_no,employee_name','cms_contracts','employee_id is null');
$access_level 	= db_query('id,descr','cms_master_list',"category_id = 17");

$skills_general  	= db_query('id,skills_name','cms_skills',"type_id = 67");
$skills_specific 	= db_query('id,skills_name','cms_skills',"type_id = 68");

?>

<div id="page-content">
    <div id='wrap'>
        <div id="page-heading">

        </div>
		<div class="container">
            <h2><i class="fa fa-user"></i> Personal Details</h2>

			<!-- Aircraft div -->
			<div class="row">

				<div class="col-sm-12">

					<div class="panel panel-midnightblue">

					   <div class="panel-body">

					      <div class="row" style="display: flex; align-items: center;">
                                <div style="flex: 1;">
                                    <div class="table-responsive">
                                        <table class="table table-condensed">
                                            <h3><strong><span id="div_name"></span></strong></h3>
                                            <!-- <thead>
                                                <tr>
                                                    <th width="50%"></th>
                                                    <th width="50%"></th>
                                                </tr>
                                            </thead> -->
                                            <tbody class="personal-details">
                                                <tr>
                                                    <td>Designation</td>
                                                    <td id="div_desg"></td>
                                                </tr>
                                                <tr>
                                                    <td>Employer</td>
                                                    <td id="div_employer"></td>
                                                </tr>
                                                <tr>
                                                    <td>Department</td>
                                                    <td id="div_dept"></td>
                                                </tr>
                                                <!-- <tr>
                                                    <td>Status</td>
                                                    <td id="div_active_status"></td>
                                                </tr>
                                                <tr>
                                                    <td>Acct. Employee No.</td>
                                                    <td id="div_employee_no"></td>
                                                </tr> -->
                                                <tr>
                                                    <td>Social</td>
                                                    <td>
                                                        <a href="#" class="btn btn-xs"><i class="fa fa-linkedin"></i></a>
                                                        <a href="#" class="btn btn-xs"><i class="fa fa-facebook"></i></a>
                                                        <a href="#" class="btn btn-xs"><i class="fa fa-skype"></i></a>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td style="border-top: 3px solid grey;">Email</td>
                                                    <td style="border-top: 3px solid grey;">
                                                      <div id="view_email">
                                                        <a href="" id="div_email"></a>
                                                        <span id="btn_edit_email" class="btn-edit-details" title="Edit Email"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                      </div>
                                                      <div id="edit_email" class="edit-container" style="display:none;">
                                                        <input type="email" class="form-control" id="txt_email" data-parsley-type="email" required>
                                                        <a class="btn btn-success btn-save-edit" id="btn_save_email" style="margin: 0 5px;">Save</a>
                                                        <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_email">Cancel</a>
                                                      </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>Phone</td>
                                                    <td class="form-inline">
                                                      <div id="view_phone" class="view-container">
                                                        <span><i class="fa fa-phone fa-fw" aria-hidden="true"></i>: </span><span id="div_phone" style="margin-right: 15px;"></span>
                                                        <span><i class="fa fa-mobile fa-fw" aria-hidden="true"></i>: </span><span id="div_mobile"></span>
                                                        <span id="btn_edit_phone" class="btn-edit-details" title="Edit Phone Number"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                      </div>
                                                      <div id="edit_phone" class="edit-container form-group" style="display: none;">
                                                        <label for="txt_phone">Home: </label>
                                                        <input class="form-control" id="txt_phone" data-parsley-type="digits" required>
                                                        <label for="txt_mobile">Mobile(MY): </label>
                                                        <input class="form-control" id="txt_mobile" data-parsley-type="digits" required>
                                                        <a class="btn btn-success btn-save-edit" id="btn_save_phone" style="margin: 0 5px;">Save</a>
                                                        <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_phone">Cancel</a>
                                                      </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                  <td>Home Address</td>
                                                  <td>
                                                    <div id="view_home_address" class="view-container">
                                                      <span href="" id="div_home_address"></span>
                                                      <span id="btn_edit_home_address" class="btn-edit-details" title="Edit Home Address"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                    </div>
                                                    <div id="edit_home_address" class="edit-container" style="display:none;">
                                                      <textarea class="form-control" id="txt_home_address"></textarea>
                                                      <a class="btn btn-success btn-save-edit" id="btn_save_home_address" style="margin: 0 5px;">Save</a>
                                                      <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_home_address">Cancel</a>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td>Current Address</td>
                                                  <td>
                                                    <div id="view_current_address" class="view-container">
                                                      <span href="" id="div_current_address"></span>
                                                      <span id="btn_edit_current_address" class="btn-edit-details" title="Edit Home Address"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                    </div>
                                                    <div id="edit_current_address" class="edit-container" style="display:none;">
                                                      <textarea class="form-control" id="txt_current_address"></textarea>
                                                      <a class="btn btn-success btn-save-edit" id="btn_save_current_address" style="margin: 0 5px;">Save</a>
                                                      <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_current_address">Cancel</a>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td>General Skills</td>
                                                  <td>
                                                    <div id="view_general_skills" class="view-container">
                                                      <span href="" id="div_general_skills"></span>
                                                      <span id="btn_edit_general_skills" class="btn-edit-details" title="Edit General Skills"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                    </div>
                                                    <div id="edit_general_skills" class="edit-container" style="display:none;" data-obj='<?php echo json_encode($skills_general) ?>'>
                                                      <select id="dd_general_skills" style="width:100%" class="populate" multiple>
                                                          <?php
                                                          for($i = 0; $i < count($skills_general); $i++)
                                                          {
                                                          ?>
                                                              <option value="<?php echo($skills_general[$i]['id']) ?>"><?php echo($skills_general[$i]['skills_name']) ?></option>
                                                          <?php
                                                          }
                                                          ?>
                                                      </select>
                                                      <a class="btn btn-success btn-save-edit" id="btn_save_general_skills" style="margin: 0 5px;">Save</a>
                                                      <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_general_skills">Cancel</a>
                                                    </div>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td>Specific Skills</td>
                                                  <td>
                                                    <div id="view_specific_skills" class="view-container">
                                                      <span href="" id="div_specific_skills"></span>
                                                      <span id="btn_edit_specific_skills" class="btn-edit-details" title="Title Specific Skills"><i class="fa fa-pencil" aria-hidden="true"></i></span>
                                                    </div>
                                                    <div id="edit_specific_skills" class="edit-container" style="display:none;" data-obj='<?php echo json_encode($skills_specific) ?>'>
                                                      <select id="dd_specific_skills" style="width:100%" class="populate" multiple>
                                                          <?php
                                                          for($i = 0; $i < count($skills_specific); $i++)
                                                          {
                                                          ?>
                                                              <option value="<?php echo($skills_specific[$i]['id']) ?>"><?php echo($skills_specific[$i]['skills_name']) ?></option>
                                                          <?php
                                                          }
                                                          ?>
                                                      </select>
                                                      <a class="btn btn-success btn-save-edit" id="btn_save_specific_skills" style="margin: 0 5px;">Save</a>
                                                      <a class="btn btn-danger btn-cancel-edit" id="btn_cancel_specific_skills">Cancel</a>
                                                    </div>
                                                  </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <div id="div_photo_container" style="width: 200px; margin-right: 20px; margin-left: 30px;">
                                        <img id="img_emp_photo" src="<?php echo $_SESSION['profile_pic']; ?>" width="150" height="150" onerror="load_default_img()">
                                        <!--div id="btn_edit_photo" class="btn-edit-photo"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</div-->
                                    </div>
                                    <button type="button" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_edit_photo">Edit</button>
                                    <button type="button" class="btn btn-primary-alt ladda-button" style="display:none" data-style="expand-left" data-spinner-color="#000000" id="btn_edit_done">Apply</button>
                                    <span class="btn btn-success fileinput-button" id="btn_add_image">
                                      <span>Browse File</span>
                                      <input id="fileupload" type="file" name="files[]">
                                    </span>

                                </div>
                            </div>
					        <hr>
						  <form class="form-horizontal" data-validate="parsley" id="detail_form">

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Home Country</h4>
                                <h5 class="col-sm-6" id="div_home_country"></h5>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Nationality</h4>
                                <h5 class="col-sm-6" id="div_nationality"></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Start Date</h4>
                                <h5 class="col-sm-6" id="div_start_date"></h5>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="col-sm-6">End Date</h4>
                                <h5 class="col-sm-6" id="div_end_date"></h5>
                            </div>
                              
                            <div class="clearfix"></div>

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Notice Period</h4>
                                <h5 class="col-sm-6" id="div_notice_period"></h5>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="col-sm-6">EP Expiry Date</h4>
                                <h5 class="col-sm-6" id="div_ep_expiry_date"></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                            <div class="col-sm-6" id="leaving_div">
                                <h4 class="col-sm-6">Leaving Date/Reason</h4>
                                <h5 class="col-sm-6"><span id="div_leaving_date"></span>/<span id="div_reason"></span></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                              <?php
    						  if($_SESSION['is_admin'] == 1)
    						  {
    						  ?>

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Add/Edit Employee Info.</h4>
                                <h5 class="col-sm-6" id="div_allow_add_edit"></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Verify</h4>
                                <h5 class="col-sm-6" id="div_allow_verify"></h5>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Approve</h4>
                                <h5 class="col-sm-6" id="div_allow_approve"></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Admin Role?</h4>
                                <h5 class="col-sm-6" id="div_admin_role"></h5>
                            </div>
                            <div class="col-sm-6">
                                <h4 class="col-sm-6">Accessibility Level</h4>
                                <h5 class="col-sm-6" id="div_access_level"></h5>
                            </div>
                            
                            <div class="clearfix"></div>

                              <?php
							  }
							  ?>

    						  <?php
    						  if($_SESSION['super_admin'] == 1)
    						  {
    						  ?>
                                <div class="col-sm-6">
                                    <h4 class="col-sm-6">Special Role?</h4>
                                    <h5 class="col-sm-6" id="div_special_role"></h5>
                                </div>
                                <div class="col-sm-6">
                                    <h4 class="col-sm-6">Accesible Document Type</h4>
                                    <h5 class="col-sm-6" id="div_accessible_ctg"></h5>
                                </div>
                                
                                <div class="clearfix"></div>

    						  <?php
    						 }
    						  ?>
                             
                             <hr>
                              
                             <div class="clearfix"></div>
                               
                             <div class="col-sm-12">
                                    <h4 class="col-sm-6">Assets Details</h4>
                              </div>
                              <div class="col-sm-12">
                                <table class="table" id="tbl_asset">
                                    <thead>
                                        <tr>
                                            <th>Asset Type</th>
                                            <th>Owner</th>
                                            <th>Brand</th>
                                            <th>Taken Date</th>
                                            <th>Return Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                              </div>


						  </form>

						<br/><hr><br/>


					  </div>
					</div>

				</div>

			</div>



        </div> <!-- container -->
    </div> <!--wrap -->
</div> <!-- page-content -->

<?php include $current_path . "footer.php" ?>
