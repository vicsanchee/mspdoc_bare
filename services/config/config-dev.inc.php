<?php
/**
 * @author 		Jamal
 * @date 		11-Nov-2015
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */

error_reporting(E_ALL);
ini_set('display_errors', 	1);
ini_set('memory_limit', 	'-1');
date_default_timezone_set('Asia/Kuala_Lumpur');


//START DB CONSTANT
define("DB_USERNAME",			'root');
define("DB_PASSWORD",			'');
define("DB_HOSTNAME",			'localhost');
define("DB_PORT",				'3306');
define("DB_NAME",				'msphitec_hrms');
define('DBDRIVER',              'mysql');
define("SLAVE_DB",              '[
                                      {
                                        "host"    : "127.0.0.1",
                                        "username": "root",
                                        "password": "",
                                        "port"    : "3306",
                                        "dbname"  : "msphitec_hrms"
                                      },
                                      {
                                        "host"    : "127.0.0.1",
                                        "username": "root",
                                        "password": "",
                                        "port"    : "3306",
                                        "dbname"  : "msphitec_hrms"
                                      }
                                    ]'
);
//END DB CONSTANT


//START MAIL CONSTANT
define('MAIL_HOST_GMAIL', 		false);
define('MAIL_HOST', 		    'vanilla.sfdns.net');
define('MAIL_PORT', 		    587);
define('MAIL_USERNAME', 		'notify@msphitect.com.my');	// email user
define('MAIL_PASSWORD', 		'Tonify#2018');			// email password
define('MAIL_FROMNAME', 		'MSP HITECT');					// lif
define('MAIL_SIGNATURE', 		'MSP Communication Department');
//END MAIL CONSTANT


//START SMS CONSTANT
define("SMS_URL",				'https://www.isms.com.my/isms_send.php');
define("SMS_USERNAME",			urlencode("jamalmsp"));
define("SMS_PASSWORD",			urlencode("Jamal2018"));
define("SMS_SENDER_ID",			urlencode("66300"));
define("SMS_LANG_TYPE",			(int)1);
define("SMS_ADDITIONAL_PARAM",	"&agreedterm=YES");
define("SMS_SUPER_ADMIN",		true);
//END SMS CONSTANT


//START PUSH NOTIFICATION CONSTANT
define("PN_HOST",			"gateway.push.apple.com");
define("PN_PORT",			2195);
define("PN_PASS_PHARSE",	"abc.123");
//END PUSH NOTIFICATION CONSTANT


//START UI CONSTANT
define('SECRET_STATIC',         'Msp@#iT3ct');         // secret key
define('MAX_NO_OF_RECORDS',		'10');
define('UI_DATE_FORMAT',		'%d-%m-%Y');
define('DISPLAY_DATE_FORMAT',	'%d-%b-%Y');
define('DISPLAY_DATETIME_FORMAT','%d-%b-%Y %h:%i %p');
define('CUT_OFF_TIME_FOR_APPT',  20);   // this is used as cut of time for appointment, used 24 time
//END UI CONSTANT



//START APP CONFIG CONSTANT
define('APPLICATION_TITLE', 		'Next Generation HRMS');
define('POWERED_BY', 				'MSP Hitect (M) Sdn Bhd &copy; 2016');
define("VERSION",					'4.6.3');
define("STAGING",					'STAGING - TESTING SERVER');
define("STAGING_EMAIL_NOTIFICATION",'sancheev@gmail.com');
define("STAGING_SMS_NOTIFICATION",	'60122721963');
//END APP CONFIG CONSTANT

//START LOGGING CONSTANT
define("LOG_TRANS",				true);
define("SHOW_ERROR_TO_USER",	false);
//END LOGGINGCONSTANT

//START LDAP CONSTANT
define('LDAP_SERVICE',			'');
define('LDAP_PORT',			    '');
define('LDAP_DOMAIN_PREFIX',	'');
define('RESTRICT_DUPLICATE_LOGIN', false);
//END LDAP CONSTANT

//START PATH CONSTANT
define('APPLICATION_ROOT_DIR', 	dirname(__FILE__) . '/..');
define('CONFIG_DIR', 			dirname(__FILE__));
define('ROOT_DIR', 				constant('CONFIG_DIR')		. '/..');
define('PUBLIC_DIR', 			strtolower(constant('ROOT_DIR')) 		. '/../public');
define('LIB_DIR', 				constant('ROOT_DIR') 		. '/lib');
define('SHARED_DIR',           	constant('ROOT_DIR')       	. '/shared');
define('FILES_DIR',            	constant('ROOT_DIR')       	. '/files');
define('MODULES_DIR',           constant('ROOT_DIR')       	. '/modules');
define('TEMPLATE_DIR',          constant('ROOT_DIR')       	. '/email_template');
define('JOB_DIR',           	constant('ROOT_DIR')       	. '/job');
define('LOG_DIR',           	constant('ROOT_DIR')       	. '/log');

define('ERROR_LOG_FILE',		constant('LOG_DIR') 		. '/error_log_' . date('Y-m-d') . '.txt');
define('SERVICE_LOG',			constant('LOG_DIR') 		. '/service_log_' . date('Y-m-d') . '.txt');
define('BG_LOG',				constant('LOG_DIR') 		. '/background_log_' . date('Y-m-d') . '.txt');
define('PN_CERT_DIR',           constant('LIB_DIR')       	. '/pn_cert');

define('SERVER_IP',             "http://" . (!isset($_SERVER['HTTP_HOST']) ?  'localhost' :  $_SERVER['HTTP_HOST']));
define('RESET_PASS_URL', 		constant('SERVER_IP') . '/mspdoc/public/modules/reset_password/reset_password_email_verification.php');
define('APP_URL', 			    constant('SERVER_IP') . '/mspdoc/public/login.php');
define('DOWNLOAD_URL', 			constant('SERVER_IP') . '/mspdoc/public/download.php?key=');
define('DOC_FOLDER', 			'C:/Program Files (x86)/EasyPHP-17/eds-www/mspdoc_bare/services/files/');
define('UPLOAD_DIR_URL',        constant('SERVER_IP') . '/mspdoc_bare/services/files/');
//END PATH CONSTANT

define('API_ENV',      'DEV');

?>
