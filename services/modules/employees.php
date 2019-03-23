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


function get_employees_details($params)
{
    try
    {
        log_it(__FUNCTION__, $params);

        $id	= if_property_exist($params,'id','');

        if($id === NULL || $id == '')
        {
            return handle_fail_response('Employee ID is mandatory');
        }

        $rs = db_query("
        id
        , employee_no
        , name
        , photoname
		, ic_passport
		, sex_id
		, age
		, date_format(dob,'" . constant('UI_DATE_FORMAT') .  "') as dob
		, nationality
		, birth_place
		, religion
		, race
		, designation
        , employer_id
		, home_phone
		, malaysia_phone
		, office_phone
		, local_address
        , home_address
        , home_country
		, email
		, office_email
		, username
        , pswd
		, is_admin
        , access_level
		, chat_id
		, dept_id
		, reporting_to_id
		, allow_add
        , allow_approve
        , allow_verify
        , allow_contract_approve
		, allow_contract_create
		, allow_contract_view_all
		, allow_contract_access
		, general_skills
        , specific_skills
        , date_format(work_start_date,'" . constant('UI_DATE_FORMAT') .  "') as work_start_date
        , date_format(work_end_date,'" . constant('UI_DATE_FORMAT') .  "') as work_end_date
        , salary
        , date_format(ep_valid_till,'" . constant('UI_DATE_FORMAT') .  "') as ep_valid_till
        , notice_period_id
		, spouse_name
		, spouse_occupation
		, date_format(marriage_date,'" . constant('UI_DATE_FORMAT') .  "') as marriage_date
		, spouse_ic
		, spouse_company
		, spouse_company_address
		, no_of_children
		, emergency_person
		, emergency_relation
		, emergency_address
		, emergency_phone
		, bank_acc_name
		, bank_acc_ic
		, bank_name
		, bank_account_no
		, accessible_ctg
		, super_admin
		, is_active
		, concat('" . constant('UPLOAD_DIR_URL') . "', 'photos/',id,'.jpeg') as profile_pic
		, date_format(leaving_date,'" . constant('UI_DATE_FORMAT') .  "') as leaving_date
		, leaving_reason
		, created_by
        , (select employer_name from cms_master_employer where cms_master_employer.id = cms_employees.employer_id) as employer_name
        , (select descr from cms_master_list where cms_master_list.id = cms_employees.dept_id) as dept_name
        , (select descr from cms_master_list where cms_master_list.id = cms_employees.notice_period_id) as notice_period
        , (select descr from cms_master_list where cms_master_list.id = cms_employees.access_level) as access_level_name
        ",
        "cms_employees",
        "id = ". $id);

        $general_skills_id = explode(",",$rs[0]['general_skills']);
        $specific_skills_id = explode(",",$rs[0]['specific_skills']);

        $where_general = "";
        $where_specific = "";

		for ($i = 0; $i < count($general_skills_id); $i++)
		{
            if(($i+1) == count($general_skills_id)){
				if($general_skills_id[$i] != "")
				{
					$where_general .=  "id=".$general_skills_id[$i];
				}
				else
				{
                	$where_general .=  "id=0";
				}
            }
            else{
                $where_general .=  "id=".$general_skills_id[$i]." OR ";
            }
        }

        for ($i = 0; $i < count($specific_skills_id); $i++)
        {
            if(($i+1) == count($specific_skills_id))
			{
				if($specific_skills_id[$i] != "")
				{
                	$where_specific .=  "id=".$specific_skills_id[$i];
				}
				else
				{
					$where_specific .=  "id=0";
				}
            }
            else{
                $where_specific .=  "id=".$specific_skills_id[$i]." OR ";
            }
        }


        $general_skills_name  = db_query('skills_name','cms_skills',$where_general);
        $specific_skills_name  = db_query('skills_name','cms_skills',$where_specific);

        $rs[0]['general_skills_name'] = $general_skills_name;
        $rs[0]['specific_skills_name'] = $specific_skills_name;

        $rs['work_list'] = [];
        $rs['leave_list'] = [];
        $rs['asset_list'] = [];
		 $rs_access  	= db_query('module_id,view_it ,view_it_all ,create_it ,edit_it ,delete_it ,print_it ,verify_it ,approve_it,revoke_verify,revoke_approval','cms_employees_access',"emp_id = " . $id);
		 $rs['access'] 	= $rs_access ? $rs_access : array();
		 

         return handle_success_response('Success', $rs);

	}
	catch(Exception $e)
	{
		handle_exception($e);
	}
}


?>
