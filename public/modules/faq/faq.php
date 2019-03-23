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

?>

<div id="page-content">
  <div id='wrap'>
    <div id="page-heading">

    </div>


    <div class="container">

      <h2><i class="fa fa-globe"></i> FAQ</h2>

      <!-- New div -->
      <div class="row" id="new_div" style="display: none">

        <div class="col-sm-12 text-right">
          <div class="form-group">
            <a href="#" class="btn btn-default" id="btn_hide"><i class="fa fa-arrow-left"></i>Hide</a>
          </div>
        </div>

        <form class="form-horizontal" data-validate="parsley" id="detail_form">

          <div class="col-md-12">
            <div class="panel panel-grape">
              <div class="panel-body">

              <label for="txt_question" class="col-sm-2 control-label">Question</label>
              <div class="col-sm-10"> 
                  <div class="form-group">
                    <textarea class="form-control marginBottom10px" id="txt_question" required="required"></textarea>
                  </div>
              </div>
              
              <div class="clearfix"></div>
              
              <label for="txt_answer" class="col-sm-2 control-label">Answer</label>
              <div class="col-sm-10">
                  <div class="form-group">
                    <textarea class="form-control marginBottom10px" id="txt_answer"></textarea>
                  </div>
              </div>

                <p class="pull-right">
                  <div class="btn-toolbar" style="text-align:right">
                    <a href="#" class="btn btn-default" id="btn_reset"><i class="fa fa-times"></i> Reset</a>
                    <a href="#" class="btn btn-primary-alt ladda-button" data-style="expand-left" data-spinner-color="#000000" id="btn_save"><i class="fa  fa-save"></i> Save</a>
                  </div>
                </p>

              </div>
            </div>
          </div>

        </form>

      </div>
      <!-- New div -->

      <div class="row" id="list_div">

        <div class="col-sm-12 text-right" id="new_btn_div" style="display: none;">
          <div class="form-group">
            <a href="#" class="btn btn-primary" id="btn_new"><i class="fa fa-plus"></i> New FAQ</a>
          </div>
        </div>

        <div class="col-sm-12">
          <div class="panel panel-grape">
            <div class="panel-body">
              <table class="table" id="tbl_view_list">
                <tbody>
                  <!--									<tr>
                  <td width="1%"><b>Q1.</b></td>
                  <td><b>Test Ques...?</b> <br /> Test Ans....</td>
                </tr>-->
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div>


  </div> <!-- container -->

</div> <!--wrap -->
</div> <!-- page-content -->


<?php include $current_path . "footer.php" ?>
