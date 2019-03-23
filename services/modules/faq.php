<?php
/**
 * @author 		Jamal
 * @date 		16-Nov-2015
 * @modify
 * @Note = Please follow the indentation
 *         Please follow the naming convention
 */
//require_once(dirname(__FILE__) . '/../config/config.inc.php');
//require_once(constant('SHARED_DIR') . '/dbfunctions.php');

function view_faq_list($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $start_index	= if_property_exist($params, 'start_index',	0);
        $limit	        = if_property_exist($params, 'limit', 0);
        $is_admin       = if_property_exist($params, 'is_admin', 0);
        $emp_id	        = if_property_exist($params, 'emp_id');


        $where	=	" cms_faq.is_active = 1 ";
               
        $rs = db_query_list
              (
                "cms_faq.id
				, cms_faq.question
        	    , cms_faq.answer
                , (select name from cms_employees emp where emp.id = cms_faq.created_by
) as created_by
                ",
                "
                cms_faq
                ",
                $where, $start_index, $limit, "id", 'ASC');

        if(count($rs) < 1 || !isset($rs))
        {
            return handle_fail_response('No record found');
        }
        else
        {
            return handle_success_response('Success', $rs);
        }

    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function add_edit_faq($params)
{
	    try
    {
        log_it(__FUNCTION__, $params);

        $id		    		= if_property_exist($params,'id');
		$question			= if_property_exist($params,'question');
        $answer				= if_property_exist($params,'answer');
		$emp_id 	        = if_property_exist($params,'emp_id');
		
        $data = array
        (
		
			':question'	     		=>  $question,
            ':answer'				=> 	$answer,
			':created_by'			=>  $emp_id		
        );
    
        if(is_data_exist('cms_faq', 'id', $id))
        {            
            $data[':id']    = $id;
            $data 			= add_timestamp_to_array($data,$emp_id,1);
            db_update($data, 'cms_faq', 'id');
        }
        else
        {
            $data 		= add_timestamp_to_array($data,$emp_id,0);
            db_add($data, 'cms_faq');
        } 
		
		$rs = json_decode(view_faq_list($params));
        
		return handle_success_response('Success', $rs->data);      
    }
    catch(Exception $e)
    {
            handle_exception($e);
    }
}

function get_faq_edit_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id			= if_property_exist($params,'id','');
		$question	= if_property_exist($params, 'question','');     
        $answer		= if_property_exist($params, 'answer','');

        if($id === NULL || $id == '')
        {
            return handle_fail_response('ID is mandatory');
        }

        $rs = db_query("
						  cms_faq.id
						, cms_faq.question
        	    		, cms_faq.answer
					",
            "cms_faq",
            "id = ". $id);
		
        
        return handle_success_response('Success', $rs);

    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}

function delete_faq_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id  		= if_property_exist($params,'id');
        $emp_id		= if_property_exist($params, 'emp_id');
        
        if($id === NULL || $id == '')
        {
            return handle_fail_response('ID is mandatory');
        }

        $data = array
        (
            ':id'	  		=> $id,
            ':is_active'  	=> 0
        );
        
        $data 		= add_timestamp_to_array($data,$emp_id, 1);
        $result 	= db_update($data, 'cms_faq','id');     
		$rs 		= json_decode(view_faq_list($params));
        
		return handle_success_response('Success', $rs->data); 
    }
    catch(Exception $e)
    {
        handle_exception($e);
    }
}



?>