<?php
/**
 * @author 		Sancheev
 * @date 		16-Nov-2018
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */

//require_once(dirname(__FILE__)          . '/../server.php');
require_once(dirname(__FILE__) . '/../config/config-dev.inc.php');
require_once(constant('SHARED_DIR') . '../dbfunctions.php');


send_notification_archiving();
function send_notification_archiving()
{
    try
    {
        log_it(__FUNCTION__, array());


        $rs = db_query
        (
            "
            cms_document_archiving.doc_no
            ,cms_master_list.descr as doc_type
            ,doc_status.descr as status_name
            ,cms_document_archiving.notify_email as notify_email
            ,date_format(cms_document_archiving.from_date,'" . constant('DISPLAY_DATETIME_FORMAT') .  "') as from_date
            ,date_format(cms_document_archiving.to_date,'" . constant('DISPLAY_DATETIME_FORMAT') .  "') as to_date
            ,cms_document_archiving.attachment
            ,cms_employees.name as emp_name
            ,cms_employees.office_email
            ",
            "
            cms_document_archiving
	            INNER JOIN cms_employees on cms_document_archiving.created_by = cms_employees.id
	            LEFT JOIN cms_master_list on cms_document_archiving.doc_type = cms_master_list.id
	            LEFT JOIN cms_master_list doc_status on cms_document_archiving.status_id = doc_status.id
	            ",
            "cms_document_archiving.notify_by = CURDATE() 
                AND cms_employees.is_active = 1
            ",
            '','','cms_employees.name','ASC, cms_document_archiving.notify_by ASC'
        );


        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            $rec_count 		= count($rs);
            $template 		= file_get_contents(constant('TEMPLATE_DIR') . '/document_archiving_notification.html');
            $replace 		= array('{DOC_NO}','{TYPE_NAME}','{STATUS_NAME}','{FROM_DATE}','{TO_DATE}','{MAIL_SIGNATURE}','{APP_TITLE}');

            for($i = 0; $i < $rec_count;$i++)
            {
                $files = json_decode($rs[$i]['attachment']);
                for($j = 0; $j < count($files); $j++)
                {
                    $filename 		= $files[$j];
                    $files_arr[$j] 	= constant('DOC_FOLDER') . "doc_archiving/" . $rs[$i]['doc_no'] . "/" . $filename;
                }

                $rs_sign = get_mail_signature($rs[$i]['office_email']);
                if(count($rs_sign) > 0)
                {
                    $with 		= array($rs[$i]['doc_no'],$rs[$i]['doc_type'],$rs[$i]['status_name'],$rs[$i]['from_date'],$rs[$i]['to_date'] ,$rs_sign['mail_signature'],constant('APPLICATION_TITLE'));
                    $body		= str_replace($replace, $with, $template);

                    $to_email = implode(";",json_decode($rs[$i]['notify_email']));

                    smtpmailer
                    (
                        $to_email,
                        constant('MAIL_USERNAME'),
                        constant('MAIL_FROMNAME'),
                        "Document Archiving Notification",
                        $body,
                        $rs[$i]['office_email'],
                        $files_arr
                    );

                }

            }

        }

    }
    catch (Exception $e)
    {
        handle_exception($e);
    }
}



?>