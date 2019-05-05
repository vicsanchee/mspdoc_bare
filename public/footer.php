
	<footer role="contentinfo">
        <div class="clearfix">
            <ul class="list-unstyled list-inline pull-left">
                <li><?php echo constant('POWERED_BY');?></li>
            </ul>
            <button class="pull-right btn btn-inverse-alt btn-xs hidden-print" id="back-to-top"><i class="fa fa-arrow-up"></i></button>
        </div>
    </footer>

</div> <!-- page-container -->

<style media="screen">
    .reveal-btn {
        position: absolute;
        top: 8px;
        right: 15px;
        color: #a9a9a9;
        text-decoration: none;
    }
    .reveal-btn:hover, .reveal-btn:focus {
        color: #a9a9a9;
        text-decoration: none;
    }
    .reveal-btn:active {
        color: #808080;
        text-decoration: none;
    }
</style>


<?php

//The following plugins are used for the functionality of the theme


echo include_js_files($current_path . 'assets/js/jquery-1.10.2.min.js');
echo include_js_files($current_path . 'assets/js/jqueryui-1.10.3.min.js');
echo include_js_files($current_path . 'assets/js/bootstrap.min.js');
echo include_js_files($current_path . 'assets/js/enquire.js');										// media query
echo include_js_files($current_path . 'assets/js/jquery.cookie.js');
echo include_js_files($current_path . 'assets/js/jquery.nicescroll.min.js');
echo include_js_files($current_path . 'assets/plugins/pines-notify/jquery.pnotify.min.js');			// alert noty
echo include_js_files($current_path . 'assets/js/custom_js/core.js');
echo include_js_files($current_path . 'assets/js/custom_js/jquery.blockUI.js');

echo include_js_files($current_path . 'assets/plugins/ladda/dist/spin.min.js');
echo include_js_files($current_path . 'assets/plugins/ladda/dist/ladda.min.js');


echo include_js_files($current_path . 'assets/plugins/form-parsley/parsley.min.js');

echo include_js_files($current_path . 'assets/js/numeric-validation.js');


$page_name 				= basename($_SERVER['PHP_SELF']);
$page_name_without_ext 	= pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME);

if($page_name == 'profile.php')
{
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.iframe-transport.js');   // file upload
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
	echo include_js_files($current_path . 'assets/plugins/form-croppie/croppie.min.js');
	echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
}
else if($page_name == 'appointments.php')
{
	echo include_js_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/js/select2.min.js');
	echo include_js_files($current_path . 'assets/plugins/form-ckeditor/ckeditor.js');
	//     echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
	echo include_js_files($current_path . 'assets/plugins/form-datepicker/js/bootstrap-datepicker.js');
	echo include_js_files($current_path . 'assets/plugins/form-timepicker/jquery.timepicker.min.js');
	echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.iframe-transport.js');   // file upload
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
	echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt
}
else if($page_name == 'faq.php')
{
	echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
	echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');         	   // data table
	echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt
}
else if($page_name == 'contracts.php')
{
	echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.iframe-transport.js');   // file upload
	echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
	echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');         	   // data table
	echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt
	
	echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.debug.js');
	echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.min.js');
	echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.plugin.autotable.js');
	echo include_js_files($current_path . 'assets/js/jspdf/plugins/html2canvas.js');
	echo include_js_files($current_path . 'assets/js/jspdf/exportpdf.js');
	
}
else if($page_name == 'admin_dash.php')
{
	echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
	echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/daterangepicker.min.js');
}
else if($page_name == 'inout_documents.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/js/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
    echo include_js_files($current_path . 'assets/plugins/form-ckeditor/ckeditor.js');
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');               // data table
    echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt

    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.debug.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.min.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.plugin.autotable.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/html2canvas.js');
    echo include_js_files($current_path . 'assets/js/jspdf/exportpdf.js');
}
else if($page_name == 'document_archiving.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/js/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
    echo include_js_files($current_path . 'assets/plugins/form-ckeditor/ckeditor.js');
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');               // data table
    echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt

    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.debug.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.min.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.plugin.autotable.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/html2canvas.js');
    echo include_js_files($current_path . 'assets/js/jspdf/exportpdf.js');
}
else if($page_name == 'service_request.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/js/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
    echo include_js_files($current_path . 'assets/plugins/form-ckeditor/ckeditor.js');
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');               // data table
    echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt

    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.debug.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.min.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/jspdf.plugin.autotable.js');
    echo include_js_files($current_path . 'assets/js/jspdf/plugins/html2canvas.js');
    echo include_js_files($current_path . 'assets/js/jspdf/exportpdf.js');
}
else if($page_name == 'leave.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/daterangepicker.min.js');
    echo include_js_files($current_path . 'assets/plugins/bootstrap-toggle/js/bootstrap-toggle.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-parsley/parsley.min.js');					   // form validations
    echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.iframe-transport.js');   // file upload
    echo include_js_files($current_path . 'assets/plugins/jquery-fileupload/js/jquery.fileupload.js');         // file upload
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');         	   // data table
    echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');
}
else if($page_name == 'leaveInfo.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/daterangepicker.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-parsley/parsley.min.js');					   // form validations
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');         	   // data table
    echo include_js_files($current_path . 'assets/plugins/bootbox/bootbox.min.js');         	   			   // modal prompt
}
else if($page_name == 'leaveReport.php')
{
    echo include_js_files($current_path . 'assets/plugins/form-select2/select2.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/moment.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-daterangepicker/daterangepicker.min.js');
    echo include_js_files($current_path . 'assets/plugins/form-parsley/parsley.min.js');					   // form validations
    echo include_js_files($current_path . 'assets/plugins/datatables/jquery.dataTables.min.js');         	   // data table
}
if (file_exists($page_name_without_ext . '.js'))
{
	echo include_js_files($page_name_without_ext . '.js');				// this is js for the particular page
}

echo include_js_files($current_path . 'assets/js/placeholdr.js');  //IE8 Placeholders
echo include_js_files($current_path . 'assets/js/application.js');
echo include_js_files($current_path . 'footer.js');

?>


</body>
</html>
