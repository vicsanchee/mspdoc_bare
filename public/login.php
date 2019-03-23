<?php
/**
 *
 *
 * login.php
 *
 * @author: Jamal
 * @version: 1.0 - created
 */

$current_path = './';
session_start();
ob_start();
require_once(dirname(__FILE__) . '/../services/config/config-dev.inc.php');
require_once(constant('SHARED_DIR')     . '/dbfunctions.php');
include '../services/modules/login.php';
include 'header-focused.php';

$redirect       = 'modules/dashboard/admin_dash.php';
$username       = (isset($_REQUEST['txt_username'])) ? trim($_REQUEST['txt_username'])  : null;
$password       = (isset($_REQUEST['txt_password'])) ? trim($_REQUEST['txt_password'])  : null;
$message        = '';

if($username != null && $password != null)
{
    $param = json_encode($data = array
    (
        'username' => $username,
        'password' => md5($password)
    ));

    $result = json_decode(check_login(json_decode($param)));

    if($result->code == 0)
    {
        $rs = db_query('id','cms_employees',"reporting_to_id = " . $result->data->emp_id);

        if (count($rs) > 0)
        {
            $_SESSION['is_supervisor'] = 1;
        }
        else 
        {
            $_SESSION['is_supervisor'] = 0;
        }

        $_SESSION['token']              	= $result->data->token;
        $_SESSION['emp_id']             	= $result->data->emp_id;
        $_SESSION['name']               	= $result->data->name;
        $_SESSION['email']               	= $result->data->email;
        $_SESSION['office_email']           = $result->data->office_email;
        $_SESSION['username']               = $result->data->username;
        $_SESSION['is_admin']           	= $result->data->is_admin;
        $_SESSION['super_admin']        	= $result->data->super_admin;
        $_SESSION['profile_pic']        	= $result->data->profile_pic;
        $_SESSION['access']					= $result->data->access;
        $_SESSION['modules']				= $result->data->modules;
        
        $_SESSION['date']               	= time();
        $_SESSION['hash']               	= md5($_SESSION['token']);
        ob_end_clean();

        if($result->data->is_admin != 1)
        {
            header('location: ' . $current_path . 'modules/profile/profile.php');
            exit();
        }
        header('location: ' . $redirect);
        exit();
    }
    else
    {
        $message = $result->msg;
    }

}

?>

<div class="verticalcenter">
    <a href="index2.php"></a>
	<div class="panel panel-primary">
		<div class="panel-body">
			<h4 class="text-center" style="margin-bottom: 25px;">Log in to get started <br/><?php echo(constant('STAGING'));?></h4>
			    <?php
			    if($message !='')
			    {
			    ?>
			    <div class="alert alert-dismissable alert-danger">
                    <strong>Oh snap!</strong>
                    <?php echo $message;?>
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                </div>
			    <?php
                }
                ?>
				<form id='login_form' action="login.php" method="post" class="form-horizontal" style="margin-bottom: 0px !important;">
						<div class="form-group">
							<div class="col-sm-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-user"></i></span>
									<input type="text" class="form-control" id="txt_username" name="txt_username" placeholder="Username">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-sm-12">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-lock"></i></span>
									<input type="password" class="form-control" id="txt_password" name="txt_password" placeholder="Password">
								</div>
							</div>
						</div>
						<div class="clearfix">
							<div class="pull-right"><label><input type="checkbox" style="margin-bottom: 20px" checked=""> Remember Me</label></div>
						</div>
					</form>

		</div>
		<div class="panel-footer">
			<a href="./modules/reset_password/reset.php" class="pull-left btn btn-link" style="padding-left:0">Forgot password?</a>

			<div class="pull-right">
				<a href="#" class="btn btn-primary" onclick="document.getElementById('login_form').submit()">Log In</a>
			</div>
		</div>
		<div class="pull-right"><?php echo (constant('POWERED_BY'));?>&nbsp;&nbsp;<span class="badge">Version <?php echo (constant('VERSION'));?></span></div>
	</div>
 </div>

 <?php

 	echo include_js_files('assets/js/jquery-1.10.2.min.js');
 	echo include_js_files('assets/js/bootstrap.min.js');

 ?>

</body>
</html>
