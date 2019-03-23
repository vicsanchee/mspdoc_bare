<?php

// require_once '../../../services/server.php';
require_once(dirname(__FILE__) . '/../services/config/config-dev.inc.php');
require_once(constant('SHARED_DIR')     . '/dbfunctions.php');
session_start();

if(!isset($_SESSION) or $_SESSION == null)                                                          // if no session kick user out
{
	header('location:' . $current_path . 'login.php');
}
else if(!isset($_SESSION['hash']) or $_SESSION['hash'] == null or $_SESSION['hash'] == '')          // if no hash kick user out
{
	header('location:' . $current_path . 'login.php');
}
else
{
	$hash  = md5
	(
			$_SESSION['token']
	);

	if($hash != $_SESSION['hash']) // if hash does not match
	{
		header('location:' . $current_path . 'login.php');
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title><?php echo(constant('APPLICATION_TITLE')); ?></title>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport"              content="width=device-width, initial-scale=1.0">
	<meta name="description"           content="<?php echo(constant('APPLICATION_TITLE')); ?>">
	<meta name="author"                content="<?php echo(constant('POWERED_BY')); ?>">

    <link rel='stylesheet' type='text/css' href="<?php echo $current_path ?>assets/css/styles.css?=2">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600' rel='stylesheet' type='text/css'>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries. Placeholdr.js enables the placeholder attribute -->
	<!--[if lt IE 9]>
        <link rel="stylesheet" href="assets/css/ie8.css">
		<script type="text/javascript" src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.1.0/respond.min.js"></script>
        <script type="text/javascript" src="assets/plugins/charts-flot/excanvas.min.js"></script>
	<![endif]-->

	<!-- The following CSS are included as plugins and can be removed if unused-->

<?php

$page_name = basename($_SERVER['PHP_SELF']);
$module_id = 0;

echo include_css_files($current_path . 'assets/plugins/form-daterangepicker/daterangepicker-bs3.css'); 		//<!-- DateRangePicker -->
echo include_css_files($current_path . 'assets/plugins/datatables/dataTables.css'); 						// <!-- Google Code Date Tables -->
echo include_css_files($current_path . 'assets/plugins/jquery-fileupload/css/jquery.fileupload-ui.css'); 	// <!--File Upload-->
echo include_css_files($current_path . 'assets/plugins/form-markdown/css/bootstrap-markdown.min.css');
echo include_css_files($current_path . 'assets/plugins/pines-notify/jquery.pnotify.default.css');
echo include_css_files($current_path . 'assets/plugins/form-select2/select2.css');
echo include_css_files($current_path . 'assets/plugins/form-datetimepicker/bootstrap-datetimepicker.less');
echo include_css_files($current_path . 'assets/plugins/jqueryui-timepicker/jquery.ui.timepicker.css');
echo include_css_files($current_path . 'assets/js/jqueryui.css');
echo include_css_files($current_path . 'assets/plugins/ladda/dist/ladda-themeless.min.css');
echo include_css_files($current_path . 'assets/plugins/form-croppie/croppie.css');
echo include_css_files($current_path . 'assets/plugins/bootstrap-toggle/css/bootstrap-toggle.min.css');
echo include_css_files($current_path . 'assets/plugins/form-timepicker/jquery.timepicker.css');
// echo include_css_files($current_path . 'assets/plugins/form-toggle/toggles.css');

if($page_name == 'faq.php')
{
	$module_id = 9;
}
else if ($page_name == 'clients.php')
{
	echo include_css_files($current_path . 'assets/css/clients.css');
}
elseif($page_name == 'appointments.php' || $page_name == 'appointmentReport.php' || $page_name == 'app_client_summary.php')
{
	echo include_css_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/css/select2.min.css');
	echo include_css_files($current_path . 'assets/plugins/form-datepicker/css/datepicker3.css');
	echo include_css_files($current_path . 'assets/css/appointments.css');
	$module_id = 4;
}
elseif($page_name == 'contracts.php' || $page_name == 'contracts.php')
{
	echo include_css_files($current_path . 'assets/css/appointments.css');
	$module_id = 7;
}
elseif($page_name == 'outbound_document.php')
{
    echo include_css_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/css/select2.min.css');
    echo include_css_files($current_path . 'assets/css/appointments.css');
    $module_id = 16;
}
elseif($page_name == 'document_archiving.php')
{
    echo include_css_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/css/select2.min.css');
    echo include_css_files($current_path . 'assets/css/appointments.css');
    $module_id = 17;
}
elseif($page_name == 'service_request.php')
{
    echo include_css_files($current_path . 'assets/plugins/select2-4.0.6-rc.1/dist/css/select2.min.css');
    echo include_css_files($current_path . 'assets/css/appointments.css');
    $module_id = 18;
}
$profile_default 	= $current_path.'assets/img/profile_default.jpg';
$profile_image 		= $_SESSION['profile_pic'];
// $module_access		= get_accessibility($module_id,$_SESSION['access']);

?>

<!-- <script type="text/javascript" src="assets/js/less.js"></script> -->
</head>

<body class="<?php if (isset($_COOKIE["fixed-header"])) echo ' static-header'; ?>">
	<input type="hidden" id="session_data" value='<?php echo(json_encode($_SESSION));?>'>

    <header class="navbar navbar-inverse <?php if (isset($_COOKIE["fixed-header"])) {echo 'navbar-static-top';} else {echo 'navbar-fixed-top';} ?>" role="banner">
        <a id="leftmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="right" title="Toggle Sidebar"></a>
        <!-- <a id="rightmenu-trigger" class="tooltips" data-toggle="tooltip" data-placement="left" title="Toggle Infobar"></a> -->

        <div class="navbar-header pull-left">
            <div class="navbar-brand"></div>
        </div>

        <ul class="nav navbar-nav pull-right toolbar">

        	<li class="dropdown">
        		<a href="#" class="dropdown-toggle username" data-toggle="dropdown"><span class="hidden-xs"><?php echo($_SESSION['name']) ?> <i class="fa fa-caret-down"></i></span>
                <img src="<?php echo $profile_image; ?>" onerror="this.src='<?php echo($current_path);?>assets/img/profile_default.jpg'" />
                </a>
        		<ul class="dropdown-menu userinfo arrow">
        			<li class="username">
                        <a href="#">
        				    <div class="pull-left"><img src="<?php echo $profile_image; ?>" onerror="this.src='<?php echo($current_path);?>assets/img/profile_default.jpg'"/></div>
        				    <div class="pull-right"><h5><?php echo($_SESSION['name']) ?></h5><small></span></small></div>
                        </a>
        			</li>
        			<li class="userlinks">
        				<ul class="dropdown-menu">
        					<!-- <li><a href="#">Edit Profile <i class="pull-right fa fa-pencil"></i></a></li>
        					<li><a href="#">Account <i class="pull-right fa fa-cog"></i></a></li> -->
                            <li><a href="<?php echo $current_path ?>modules/profile/profile.php">Personal Details</a></li>
        					<li class="divider"></li>
                            <li>
                                <a  href="#"
                                    data-toggle="modal"
                                    data-target="#change_pwd_modal"
                                    data-initial=false
                                    data-backdrop='static'
                                    data-keyboard=false>Change Password
                                </a>
                            </li>
        					<li class="divider"></li>
        					<li><a href="<?php echo $current_path ?>modules/help/help.php">Help <i class="pull-right fa fa-question-circle"></i></a></li>
        					<li class="divider"></li>
        					<li><a href="<?php echo $current_path ?>logout.php" class="text-right">Sign Out</a></li>
        				</ul>
        			</li>
        		</ul>
        	</li>

		</ul>
    </header>

    <div id="page-container">
        <!-- BEGIN SIDEBAR -->
        <nav id="page-leftbar" role="navigation">
            <!-- BEGIN SIDEBAR MENU -->
            <ul class="acc-menu" id="sidebar">
            
                <?php
				if($_SESSION['is_admin'] == 1)
				{
				?>
                <li><a href="<?php echo $current_path ?>modules/dashboard/admin_dash.php"><i class="fa fa-home"></i> <span>Dashboard</span>
            	<?php
				}
				?>
				<li><a href="<?php echo $current_path ?>modules/faq/faq.php" id="menu_faq" data-val="9"><i class="fa fa-globe"></i> <span>FAQ</span></a></li>
				
				<li><a href="javascript:;" id="menu_appointments" data-val="4"><i class="fa fa-calendar"></i> <span>Appointments</span>
                </a>
                    <ul class="acc-menu">
                        <li><a href="<?php echo $current_path ?>modules/appointments/appointments.php"><span>List of Appointments</span></a></li>
                    </ul>
                </li>
				<li><a href="javascript:;" id="menu_contracts" data-val="7"><i class="fa fa-cubes"></i> <span>Contracts</span></a>
                	<ul class="acc-menu">
                    	<li><a href="<?php echo $current_path ?>modules/contract/contracts.php"><span>OnBoarding</span></a></li>
                  	</ul>
              	</li>
                <li><a href="javascript:;" id="menu_others" data-val="7"><i class="fa fa-cubes"></i> <span>Others</span></a>
                    <ul class="acc-menu">
                        <li><a href="<?php echo $current_path ?>modules/outbound_document/outbound_document.php"><span>Outbound Document</span></a></li>
                        <li><a href="<?php echo $current_path ?>modules/document_archiving/document_archiving.php"><span>Document Archiving</span></a></li>
                        <li><a href="<?php echo $current_path ?>modules/service_request/service_request.php"><span>Service Request</span></a></li>
                    </ul>
                </li>
				
            </ul>
            <!-- END SIDEBAR MENU -->
        </nav>
