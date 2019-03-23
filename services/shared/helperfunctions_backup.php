<?php
/**
 * @author 		Jamal
 * @date 		17-Jun-2012
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */
use ZK\ZkLib;

function handle_exception(&$e)
{
	$err = $e->getTraceAsString();
	$err = $err . $e->getMessage();
	log_trans($err, ERROR_LOG_FILE);

	if(SHOW_ERROR_TO_USER)
		return return_error($err);
	else
		return return_error("An error has occured. Please contact the system administrator.","ERROR");
}

function handle_success_response($return_string = '',$return_value = '')
{
	$data = array
	(
		'code' 	=> '0',
		'msg' 	=> $return_string,
		'data' 	=> $return_value
	);
	return json_encode($data);
}

function handle_fail_response($return_string = '',$return_value = '')
{
	$data = array
	(
		'code' 	=> '1',
		'msg' 	=> $return_string,
		'data' 	=> $return_value
	);
	return json_encode($data);
}

function return_error($return_string = '',$return_value = '')
{
	$data = array
	(
		'code' 	=> '2',
		'msg' 	=> $return_string,
		'data' 	=> $return_value
	);
	return json_encode($data);
}

function log_trans($str_data, $fname)
{
	if($fname != '')
	{
		if(!is_dir(constant('LOG_DIR')))
		{
			mkdir(constant('LOG_DIR'),0755,TRUE);
		}

		$fh = fopen($fname, 'a+') or die("can't open file");
		fwrite($fh,"[" . date('Y-m-d H:i:s') . "]\r\n");
		fwrite($fh, $str_data);
		fwrite($fh, "\r\n\r\n\r\n");
		fclose($fh);
	}
}

function array_push_assoc($array, $key, $value)
{
	$array[$key] = $value;
 	return $array;
}

function add_timestamp_to_array($data, $emp_id, $new_edit)
{
	if($new_edit === 0)
	{
		$data = array_push_assoc($data,':created_by',$emp_id);
		$data = array_push_assoc($data,':created_date', get_current_date());
	}
	if($new_edit === 1)
	{
		$data = array_push_assoc($data,':updated_by',$emp_id);
		$data = array_push_assoc($data,':updated_date', get_current_date());
	}

	return $data;
}

function remove_invalid_char($params)
{
	for($i=0; $i < count($params); $i++)
		$params[$i] = rem_inval_chars($params[$i]);

	return $params;
}

function rem_inval_chars($parameter, $special_case='')
{
	$parameter = str_replace("'","",$parameter);
	$parameter = str_replace("\\","",$parameter);
	$parameter = str_replace("\0","",$parameter);
	$parameter = str_replace("\"","",$parameter);

	if(intval($special_case) === 1)
	{
		$parameter = str_replace(":","",$parameter);
		//$parameter = str_replace("/","",$parameter);
		$parameter = str_replace("!","",$parameter);
		$parameter = str_replace("<","",$parameter);
		$parameter = str_replace(">","",$parameter);
		$parameter = str_replace(".","",$parameter);
		$parameter = str_replace(",","",$parameter);
		$parameter = str_replace(";","",$parameter);
		$parameter = str_replace("�","",$parameter);
		$parameter = str_replace("�","",$parameter);
		$parameter = str_replace("@","",$parameter);
		$parameter = str_replace("#","",$parameter);
		$parameter = str_replace("^","",$parameter);
		$parameter = str_replace("*","",$parameter);
		$parameter = str_replace("(","",$parameter);
		$parameter = str_replace(")","",$parameter);
		$parameter = str_replace(" ","",$parameter);
		$parameter = str_replace("&","",$parameter);
		$parameter = str_replace("$","",$parameter);
		$parameter = str_replace("%","",$parameter);
		$parameter = str_replace("=","",$parameter);
	}

	return $parameter;
}

function base64_to_jpeg($base64_string, $filename)
{
	$filepath = constant('FILES_DIR');
	if(!file_exists($filepath))
	{
		mkdir($filepath, 0777, true);
	}
	file_put_contents($filepath . '/' . $filename, base64_decode($base64_string));

	return $filename;
}

function get_current_date()
{
	try
	{
		return date('Y-m-d H:i:s');
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_current_date_in_milliseconds()
{
	try
	{
		return floor(microtime(true) * 1000);
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function convert_to_date($date)
{
	try
	{
		if(is_valid_date($date))
		{
			$date = new DateTime($date);
			return $date->format('Y-m-d');
		}
		else
		{
			return NULL;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function convert_to_datetime($date,$set_current_time = false)
{
	try
	{
		if(is_valid_date($date))
		{
			$date = new DateTime($date);

			if($set_current_time)
			{
				return $date->format('Y-m-d') . ' ' . date('H:i:s');
			}
			return $date->format('Y-m-d H:i:s');
		}
		else
		{
			return NULL;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function convert_date_to_format($date)
{
    try
    {
        if(is_valid_date($date))
        {
            $date = new DateTime($date);
            return $date->format('d-M-Y');
        }
        else
        {
            return NULL;
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function is_valid_date($date)
{
    try
    {
    	$time 		= strtotime($date);

    	if($time)
    	{
    	   $d 			= DateTime::createFromFormat('Y-m-d', date('Y-m-d',$time));
    	   $valid_data = $d && $d->format('Y-m-d') == date('Y-m-d',$time);
    	   return $valid_data;
        }
        else
        {
            return false;
        }
	}
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function if_object_exist($object, $key, $return_val_if_empty = NULL)
{
	if(property_exists($object, $key))
		return empty($object->$key) ? $return_val_if_empty : $object->$key;
	else
		return $return_val_if_empty;
}

function if_property_exist($object, $key, $return_val_if_empty = NULL,$return_val_if_no_match = false)
{
	if(isset($object->$key))
	{
		if ($return_val_if_no_match !== false)
		{
			if(in_array($object->$key,$return_val_if_no_match))
			{
				return $return_val_if_no_match[0];
			}
		}
		
		return $object->$key;
	}
	else
	{
		return $return_val_if_empty;
	}
}

function log_it($function_name, $param, $bg = false)
{
	if(LOG_TRANS)
	{
		$filepath = constant('SERVICE_LOG');
		if($bg === true)
		{
			$filepath = constant('BG_LOG');
		}
		log_trans("Function Details: " . $function_name .
				" \r\n Param Details : \r\n" . json_encode($param) , $filepath);
	}
}

function unique_id($l = 4)
{
	return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

function get_unique_id()
{
	return md5(uniqid() . time());
}

function get_short_code()
{
	return strtoupper(strrev(base_convert((microtime(true)-1443621600),10,36)));
}

function random_numbers($digits)
{
	return rand(pow(10, $digits - 1) - 1, pow(10, $digits) - 1);
}

function push_it_to_ios($push_id_array, $params)
{
	
	log_it(__FUNCTION__, $params);
	log_it(__FUNCTION__, $push_id_array);
	$return_data    = array();
	$apns_host      = 'gateway.sandbox.push.apple.com';
	$apns_cert      = constant('LIB_DIR')  . '/pn_cert/apns_dev.pem';

	if(constant('API_ENV') === 'PROD')
	{
		$apns_host  = 'gateway.push.apple.com';
		$apns_cert  = constant('LIB_DIR')  . '/pn_cert/apns.pem';
	}
	$apns_port      = 2195;

	$stream_context = stream_context_create();
	stream_context_set_option($stream_context, 'ssl', 'local_cert', $apns_cert);
	stream_context_set_option($stream_context, 'ssl', 'passphrase', 'abc.123+');

	$apns           = stream_socket_client('ssl://' . $apns_host . ':' . $apns_port, $error, $errorString, 2, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $stream_context);
	$payload['aps'] = array('alert' => substr($params->data->msg, 0, 200), 'badge' => 1, 'sound' => 'default');
	
	if(isset($params->data->data))
	{
		$payload['aps']['data'] = $params->data->data;
	}
	if(isset($params->data->lat))
	{
		$payload['aps']['lat'] = $params->data->lat;
		$payload['aps']['lng'] = $params->data->lng;
	}

	$output         = json_encode($payload);

	for ($i = 0; $i < count($push_id_array); $i++)
	{
		if(trim($push_id_array[$i]['push_id']) != '')
		{
			$token          = pack('H*', $push_id_array[$i]['push_id']);
			$apns_message   = chr(0) . chr(0) . chr(32) . $token . chr(0) . chr(strlen($output)) . $output;
			fwrite($apns, $apns_message);
			$return_data[$i]['status'] =  'ok';
			sleep(1);
		}
	}

	@socket_close($apns);
	fclose($apns);

	return handle_success_response('Success',$return_data);
}

function walk($val, $key, &$new_array)
{
	$nums = explode(',',$val);
	$new_array[$nums[0]] = $nums[1];
}

function smtpmailer($to, $from, $from_name, $subject, $body, $cc = NULL, $attachment = NULL, $ical = NULL )
{
	try 
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
	        
		require_once constant('LIB_DIR')  . '/phpmailer/class.phpmailer.php';
		
		$mail = new PHPMailer(true);        // create a new object
		$mail->IsSMTP();                	// enable SMTP
		$mail->SMTPDebug    = 0;        	// debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth     = true;     	// authentication enabled
	
		if(constant('MAIL_HOST_GMAIL') == true)
		   $mail->SMTPSecure   = 'ssl';    // secure transfer enabled REQUIRED for GMail
	
		if(constant('API_ENV') !== 'PROD')
		{
			$subject .= " - " . constant('API_ENV');
		}
		   
		$mail->Host         = constant('MAIL_HOST');
		$mail->Port         = constant('MAIL_PORT');;
	    $mail->Username     = constant('MAIL_USERNAME');
	    $mail->Password     = constant('MAIL_PASSWORD');
	    $mail->SetFrom($from, $from_name);
	    $mail->Subject      = $subject;
	    $mail->Body         = $body;
	    $mail->isHTML(true);
	    
	    if($attachment)
	    {
	    	if(is_array($attachment))
	    	{
	    		for($i = 0; $i < count($attachment); $i++)
	    		{
	    			$mail->AddAttachment($attachment[$i]);
	    		}
	    	}
	    	else
	    	{
	    		$mail->AddAttachment($attachment);
	    	}
	    }
		
	    $to_tmp	= explode(';', $to);
	    for($i = 0; $i < count($to_tmp); $i++)
	    {
	    	if(constant('API_ENV') !== 'PROD')
	    	{
	    		$tmp = explode("@", strtolower(trim($to_tmp[$i])));
	    		if($tmp[1] != 'msphitect.com.my')
	    		{
	    			$to_tmp[$i] = constant('STAGING_EMAIL_NOTIFICATION');
	    		}
	    	}
	    	
	    	$mail->AddAddress($to_tmp[$i]);
	    }
	    
	    if($cc)
	    {
	        $cc_tmp	= explode(';', $cc);
	        for($i = 0; $i < count($cc_tmp); $i++)
	        {
	        	if(constant('API_ENV') !== 'PROD')
	        	{
	        		$tmp = explode("@", strtolower(trim($cc_tmp[$i])));
	        		if($tmp[1] != 'msphitect.com.my')
	        		{
	        			$cc_tmp[$i] = constant('STAGING_EMAIL_NOTIFICATION');
	        		}
	        	}
	        	
	    	    $mail->AddCC($cc_tmp[$i]);
	        }
	    }
	
	    if(!$mail->Send())
	    {
	    	log_it(__FUNCTION__, 'Email Error ' . $mail->ErrorInfo);
	    	return false;
	    }
	    else
	    {
	    	log_it(__FUNCTION__, 'Email Sent : ' . $to . " CC'ed : " . ($cc ? $cc: ""));
	    	return true;
	    }
	}
    catch(Exception $e)
    {
    	handle_exception($e);
    }
}

function smtpmailer_new($to, $from, $from_name, $subject, $body, $cc = NULL, $attachment = NULL, $ical = NULL )
{
	try
	{
		error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
		
		require_once constant('LIB_DIR')  . '/phpmailer/class.phpmailer.php';
		
		$mail = new PHPMailer(true);        // create a new object
		$mail->IsSMTP();                	// enable SMTP
		$mail->SMTPDebug    = 0;        	// debugging: 1 = errors and messages, 2 = messages only
		$mail->SMTPAuth     = true;     	// authentication enabled
		
		if(constant('MAIL_HOST_GMAIL') == true)
			$mail->SMTPSecure   = 'ssl';    // secure transfer enabled REQUIRED for GMail
			
			if(constant('API_ENV') !== 'PROD')
			{
				$subject .= " - " . constant('API_ENV');
			}
			
			$mail->Host         = constant('MAIL_HOST');
			$mail->Port         = constant('MAIL_PORT');;
			$mail->Username     = constant('MAIL_USERNAME');
			$mail->Password     = constant('MAIL_PASSWORD');
			$mail->SetFrom($from, $from_name);
			$mail->Subject      = $subject;
			$mail->Body         = $body;
			$mail->isHTML(true);
			
			if($attachment)
			{
				$mail->AddAttachment($attachment);
			}
			
			$to_tmp	= explode(';', $to);
			for($i = 0; $i < count($to_tmp); $i++)
			{
				if(constant('API_ENV') !== 'PROD')
				{
					$tmp = explode("@", strtolower(trim($to_tmp[$i])));
					if($tmp[1] != 'msphitect.com.my')
					{
						$to_tmp[$i] = constant('STAGING_EMAIL_NOTIFICATION');
					}
				}
				
				$mail->AddAddress($to_tmp[$i]);
			}
			
			if($cc)
			{
				$cc_tmp	= explode(';', $cc);
				for($i = 0; $i < count($cc_tmp); $i++)
				{
					if(constant('API_ENV') !== 'PROD')
					{
						$tmp = explode("@", strtolower(trim($cc_tmp[$i])));
						if($tmp[1] != 'msphitect.com.my')
						{
							$cc_tmp[$i] = constant('STAGING_EMAIL_NOTIFICATION');
						}
					}
					
					$mail->AddCC($cc_tmp[$i]);
				}
			}
			
			if(!$mail->Send())
			{
				$data['status'] = false;
				$data['msg']	= $mail->ErrorInfo;
				log_it(__FUNCTION__, 'Email Error ' . $mail->ErrorInfo);
				return $data;
			}
			else
			{
				$data['status'] = true;
				$data['msg']	= 'Email Sent';
				log_it(__FUNCTION__, 'Email Sent : ' . $to . " CC'ed : " . ($cc ? $cc: ""));
				return $data;
			}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function send_sms_to_super_admin($msg)
{
	try 
	{
		log_it(__FUNCTION__, $msg);
		
		$rs = db_query("group_concat(malaysia_phone SEPARATOR ';') as mobile", "cms_employees", 'super_admin = 1');
		
		if (count($rs) > 0)
		{
			send_sms($msg,$rs[0]['mobile']);
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function send_sms($msg, $mobile_number )
{
	try 
	{
		log_it(__FUNCTION__, $msg . ' ' . $mobile_number);
		
		if(constant('SMS_URL') != '')
		{
			if(constant('API_ENV') !== 'PROD')
			{
				$mobile_number = constant('STAGING_SMS_NOTIFICATION');
				$msg		   .= " -" . constant('API_ENV');
			}
			
			$url = constant('SMS_URL');
			$url.= "?un=" 			. constant('SMS_USERNAME');
			$url.= "&pwd=" 			. constant('SMS_PASSWORD');
			$url.= "&dstno=" 		. $mobile_number;
			$url.= "&msg=" 			. urlencode(html_entity_decode($msg, ENT_QUOTES, 'utf-8'));
			$url.= "&type=" 		. constant('SMS_LANG_TYPE');
			$url.= "&sendid=" 		. constant('SMS_SENDER_ID');
			$url.= constant('SMS_ADDITIONAL_PARAM');

			require_once constant('LIB_DIR') 	. '/httpful/vendor/autoload.php';
			
			$response = \Httpful\Request::GET($url)->send();
			if ($response)
			{
// 				log_it(__FUNCTION__, $response);
				if (trim($response) == "2000 = SUCCESS" || trim($response) == '')
				{
					return true;
				}
				else
				{
					return false;
				}
			}
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function encrypt_string($param)
{
	return base64_encode( mcrypt_encrypt( MCRYPT_RIJNDAEL_256, md5( SECURITY_SECRET ), $param, MCRYPT_MODE_CBC, md5( md5( SECURITY_SECRET ) ) ) );
}

function decrypt_string($param)
{
	return rtrim( mcrypt_decrypt( MCRYPT_RIJNDAEL_256, md5( SECURITY_SECRET ), base64_decode( $param ), MCRYPT_MODE_CBC, md5( md5( SECURITY_SECRET ) ) ), "\0");
}

function convert_date_to_specified_format($date, $format)
{
	if($date === '' or $date === NULL)
		return '';

	$date = strtotime($date);
	$date = date($format, $date);
	return $date;
}

function is_this_integer($input)
{
	return(ctype_digit(strval($input)));
}

function isValidMd5($md5)
{
	return !empty($md5) && preg_match('/^[a-f0-9]{32}$/', $md5);
}

function include_css_files($address)
{
	return "<link rel='stylesheet' type='text/css' href='$address?v=" . constant('VERSION') . "' /> \n";
}

function include_js_files($address)
{
	return "<script type='text/javascript' src='$address?v=" . constant('VERSION') . "'></script> \n";
}

function is_number($val)
{
	return ctype_digit($val) ? $val : '0';
}

function make_thumb($src, $dest, $desired_width)
{
	$source_image 	= imagecreatefromjpeg($src);
	$width 			= imagesx($source_image);
	$height 		= imagesy($source_image);
	$desired_height = floor($height * ($desired_width / $width));
	$virtual_image 	= imagecreatetruecolor($desired_width, $desired_height);
	imagecopyresampled($virtual_image, $source_image, 0, 0, 0, 0, $desired_width, $desired_height, $width, $height);
	imagejpeg($virtual_image, $dest);
}

function make_thumbnails($filepath,$thumbnail_path)
{
	$thumbnail_width 	= 80;
	$thumbnail_height 	= 80;
	$arr_image_details 	= getimagesize($filepath);
	$original_width 	= $arr_image_details[0];
	$original_height 	= $arr_image_details[1];
	if ($original_width > $original_height)
	{
		$new_width 	= $thumbnail_width;
		$new_height = intval($original_height * $new_width / $original_width);
	}
	else
	{
		$new_height = $thumbnail_height;
		$new_width 	= intval($original_width * $new_height / $original_height);
	}
	$dest_x = intval(($thumbnail_width - $new_width) / 2);
	$dest_y = intval(($thumbnail_height - $new_height) / 2);
	if ($arr_image_details[2] == 1)
	{
		$imgt 			= 'ImageGIF';
		$imgcreatefrom 	= 'ImageCreateFromGIF';
	}
	if ($arr_image_details[2] == 2)
	{
		$imgt 			= 'ImageJPEG';
		$imgcreatefrom 	= 'ImageCreateFromJPEG';
	}
	if ($arr_image_details[2] == 3)
	{
		$imgt 			= 'ImagePNG';
		$imgcreatefrom 	= 'ImageCreateFromPNG';
	}

	if ($imgt)
	{
		$old_image = $imgcreatefrom($filepath);
		$new_image = imagecreatetruecolor($thumbnail_width, $thumbnail_height);
		imagecopyresized($new_image, $old_image, $dest_x, $dest_y, 0, 0, $new_width, $new_height, $original_width, $original_height);
		$imgt($new_image, $thumbnail_path);
	}
}

function split_filename_and_ext($filepath)
{
	$ext 		= pathinfo($filepath, PATHINFO_EXTENSION);
	$file_info	= array(basename($filepath, '.' . $ext), $ext);

	return $file_info;
}

function is_data_exist($table_name, $column_name, $item_id)
{
	log_it(__FUNCTION__,$table_name . ' ' . $column_name . ' ' . $item_id);

	$where = $column_name . " = '" . $item_id . "'";

	if(is_this_integer($item_id))
	{
		$where = $column_name . " = " . $item_id;
	}


	$rs = db_query('count(*) as id_count', $table_name, $where);

	if(isset($rs) && $rs != NULL)
	{
		return $rs[0]['id_count'];
	}

	return false;
}

// print_r (get_time_to_seconds('13:15'));
function get_time_to_seconds($time)
{
	$time_seconds = 0;
	sscanf($time, "%d:%d:%d", $hours, $minutes, $seconds);


	if(isset($seconds))
	{
		$time_seconds = $seconds;
	}

	if(isset($minutes))
	{
		$time_seconds += ($minutes * 60);
	}

	if(isset($hours))
	{
		$time_seconds += ($hours * 3600);
	}

// 	$time_seconds = isset($seconds) ? $hours * 3600 + $minutes * 60 + $seconds : $hours * 60 + $minutes;

	return $time_seconds;
}

function executeAsyncShellCommand($comando = null)
{
    if(!$comando)
    {
        throw new Exception("No command given");
    }
    // If windows, else
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') 
    {
        system($comando." > NUL");
    }
    else
    {
        shell_exec("/usr/bin/nohup ".$comando." >/dev/null 2>&1 &");
    }
}

// echo strtotime(date('Y-m-d') . ' 20:00') . "\n";
// echo strtotime('2018-03-28 09:00');die;
// echo is_date_bigger_than_current_date('2018-03-28 09:00 AM');
function is_date_bigger_than_current_date($date)
{
    
    $current_date   = strtotime("now");
    $my_date        = strtotime($date);
    $is_bigger      = false;
    
    if($my_date > $current_date)
    {
        $is_bigger = true;
    }
    else 
    {
        if((int)date('H') < (int)constant('CUT_OFF_TIME_FOR_APPT')) //to over the is_bigger with cut of time
        {
            $is_bigger = true;
        }
    }
    
    return (int)$is_bigger;
}

function get_gmap_address($lat,$lng)
{
    try
    {
        log_it(__FUNCTION__, trim($lat). ',' . trim($lng));
        
        if($lat != "" && $lng != "")
        {
            $arrContextOptions=array
            (
            		"http" => array
            		(
            			"header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.102 Safari/537.36"
            		)
//                 "ssl"=>array
//                 (
//                     "verify_peer"=>false,
//                     "verify_peer_name"=>false,
//                 ),
            );
            
//             $url    = constant('MAP_URL') . 'latlng=' . trim($lat) . ',' . trim($lng) . '&key=' . constant('MAP_KEY');
            
            $url	= sprintf(constant('MAP_URL'),trim($lat),trim($lng));
            
//             $json   = file_get_contents($url,false, stream_context_create($arrContextOptions));
            
            $json   = call_api("", $url);
            
            $data   = json_decode($json, true);
            
            log_it(__FUNCTION__, $data);
            
            return $data['display_name'];
        }
        else 
        {
            return "";
        }
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function run_in_background($command, $priority = 0)
{
	if($priority)
	{
		$pid = shell_exec("nohup php nice -n $priority $command > /dev/null 2>&1 & echo $!");
	}
	else
	{
		log_it(__FUNCTION__, "nohup php $command > /dev/null 2>&1 & echo $!");
		$pid= shell_exec("nohup php $command > /dev/null 2>&1 & echo $!");
		log_it(__FUNCTION__, $pid);
	}
	return($pid);
}

function is_process_running($pid)
{
	exec("ps $pid", $process_state);
	return(count($process_state) >= 2);
}

function call_api($params,$url)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		if ( filter_var(urldecode(($url . $params)), FILTER_VALIDATE_URL, FILTER_FLAG_QUERY_REQUIRED) === false )
		{
			log_it(__FUNCTION__, "URL may contain malicious code: $params");
			return false;
		}
		
		$ch     = curl_init();
		
		curl_setopt($ch, CURLOPT_URL, ($url . $params));
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_USERAGENT, 'User-Agent: curl/7.39.0');
		
		$output     = curl_exec($ch);
		$curl_error =  curl_error($ch);
		curl_close($ch);
		
		if($curl_error)
		{
			log_it(__FUNCTION__, "cURL $curl_error");
			return false;
		}
		else
		{
			log_it(__FUNCTION__, $output);
			return $output;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_accessibility($module_id,$access)
{
	try 
	{
		$count_access 	= count($access);
		for($i = 0; $i < $count_access;$i++)
		{
			if((int)($access[$i]->module_id) == (int)($module_id))
			{
				return $access[$i];
			}
		}
		return false;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function is_file_exist($filepath,$return_file_path = false)
{
	try
	{
		if (!file_exists($filepath)) 
		{
			$return_file_path;
		}
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_controlpanel_connection()
{
	try
	{
		log_it(__FUNCTION__, "");
		
		$xmlapi = new xmlapi(constant('CPANEL_IP'));
		$xmlapi->password_auth(constant('CPANEL_USERNAME'),constant('CPANEL_PASSWORD'));
		$xmlapi->set_output('json');
		$xmlapi->set_port(constant('CPANEL_PORT'));
		
		return $xmlapi;
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_attendance_connection()
{
	try
	{
		log_it(__FUNCTION__, "");
		
		return new ZkLib(constant('ATTENDANCE_API_URL'));
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function route_to_admin($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$params->email 	= constant('STAGING_EMAIL_NOTIFICATION');
		$params->emp_id = 15;
		
		return $params;
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_working_days($params)
{
	try
	{
		log_it(__FUNCTION__, $params);
		
		$start_date = if_property_exist($params, 'start_date');
		$end_date 	= if_property_exist($params, 'end_date');
		$holidays	= if_property_exist($params, 'holidays', array());
		
		
		if($start_date === NULL || $start_date == '')
		{
			return handle_fail_response('Start Date is mandatory');
		}
		
		if($end_date === NULL || $end_date == '')
		{
			return handle_fail_response('End Date is mandatory');
		}
		
		$start 		= new DateTime($start_date);
		$end 		= new DateTime($end_date);
		$oneday 	= new DateInterval("P1D");
		$days 		= array();
		
		foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day)
		{
			$day_num = $day->format("N"); /* 'N' number days 1 (mon) to 7 (sun) */
			if($day_num < 6)  /* weekday */
			{
				$format = $day->format( 'Y-m-d' );
				if(in_array( $format, $holidays ) === false )
				{
					//Add the valid day to our days array
					//This could also just be a counter if that is all that is necessary
					$days[] = $day->format( 'Y-m-d' );
				}
				
			}
		}  
		
		return $days;
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_from_assoc_array($key,$value,$array)
{
	$data = array();
	if (empty($array))
	{
		return array();
	}
	else
	{
		foreach($array as $item)
		{
			if ($item[$key] == $value)
			{
				array_push($data, $item);
			}
		}
		return $data;
	}
}

function get_doc_primary_no($field,$table)
{
	try
	{
		log_it(__FUNCTION__, $field . " " . $table);
		
		$primary_rs = db_query("IFNULL( CONCAT( RIGHT(YEAR(NOW()),2),'-',  LPAD(  SUBSTRING_INDEX(MAX($field),'-',-1) + 1 ,7,'0000000')), CONCAT( RIGHT(YEAR(NOW()),2),'-','0000001')) AS $field "
								, $table,"YEAR(created_date) = YEAR(NOW())",'1','0',$field,'desc');
		
		if(count($primary_rs) > 0)
		{
			return $primary_rs[0];
		}
		else
		{
			return false;
		}
		
	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}

function get_mail_signature($email)
{
	try
	{
		log_it(__FUNCTION__, $email);
		
		if($email == '')
		{
			return array();
		}
		
		$rs = db_execute_custom_sql("SELECT cms_employees.name, cms_master_employer.mail_signature
									FROM cms_employees
									LEFT JOIN cms_master_employer ON cms_employees.employer_id = cms_master_employer.id
									WHERE cms_employees.office_email in('" . $email . "') AND cms_employees.is_active = 1");
		
		
		return isset($rs[0]) ? $rs[0] : array();
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

function get_verifier_approver($module_id,$type_id = 0)
{
	try
	{
		log_it(__FUNCTION__, $module_id);
		
		$verify_approve = "verify_it = 1 AND approve_it = 1";
		
		if($type_id == 1)
		{
			$verify_approve = "verify_it = 1";	
		}
		elseif($type_id == 2)
		{
			$verify_approve = "approve_it = 1";
		}
		
		
		$rs = db_execute_custom_sql("SELECT group_concat(office_email SEPARATOR ';') as email
									FROM cms_employees
									INNER JOIN cms_employees_access ON cms_employees.id =  cms_employees_access.emp_id
									WHERE cms_employees_access.module_id = " . $module_id . " AND " . $verify_approve . " AND cms_employees.is_active = 1");
		
		
		return isset($rs) ? $rs[0] : array();
	}
	catch (Exception $e)
	{
		handle_exception($e);
	}
}

?>
