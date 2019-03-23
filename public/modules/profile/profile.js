var SESSIONS_DATA	= '';
var last_scroll_top = 0;
var photo_editor;
CURRENT_PATH		= '../../';
EMP_ID 				= '';

function load_default_img()
{
	// $('#div_emp_photo').html('<img class="img-circle" src="../assets/img/profile_default.jpg" height="150" />');
//	$('#img_emp_photo').attr("src", "../assets/img/profile_default.jpg");
}

$.fn.populate_detail_form = function()
{
	try
	{

		var data	=
		{
			id			: SESSIONS_DATA.emp_id
	 	};

		$('.populate').select2();
	 	$('#h4_primary_no')		.text('Employee Id : ' + data.id);

		if(data.photoname === null)
		{
			data.photoname = '';
		}

	 	$.fn.fetch_data
		(
			$.fn.generate_parameter('get_employees_details',{id : data.id}),
			function(return_data)
			{
				if(return_data.data)
				{
					var data 		= return_data.data[0];
					var data_asset = return_data.data.asset_list;

					// var photo_content = '<img class="img-circle" src="../assets/img/profile_default.jpg" height="150" />';
					if(data.photoname !== null && data.photoname !== '')
					{
						// photo_content = '<img class="img-circle" src="../../services/files/photos/' + data.id + '/' + data.photoname +'" height="150" onerror="load_default_img()"/>';
//						$('#img_emp_photo').attr("src", "../../services/files/photos/" + data.id + "/" + data.photoname);
					}

					// $('#div_emp_photo')				.html(photo_content);
					EMP_ID							= data.id;
					$('#div_name')					.html(data.name);
					$('#div_employer')				.html(data.employer_name);
					$('#div_desg')					.html(data.designation);
					$('#div_phone')					.html(data.home_phone);
					$('#div_mobile')				.html(data.malaysia_phone);
					$('#div_email')					.html(data.email);
					$('#div_employee_no')			.html(data.employee_no);
					$('#div_dept')					.html(data.dept_name);
					$('#div_home_address')			.html(data.home_address);
					$('#div_current_address')		.html(data.local_address);
					$('#div_home_country')			.html(data.home_country);
					$('#div_nationality')			.html(data.nationality);
					$('#div_start_date')			.html(data.work_start_date);
					$('#div_end_date')				.html(data.work_end_date);
					$('#div_notice_period')			.html(data.notice_period);
					$('#div_ep_expiry_date')		.html(data.ep_valid_till);

					$('#div_access_level')			.html(data.access_level_name);

					$('#div_leaving_date')			.html(data.leaving_date);
					$('#div_reason')				.html(data.leaving_reason);

					data.is_active == 1 ? $('#div_active_status').html('<span class="text-success"><b>Active</b></span>') : $('#div_active_status').html('<span class="text-danger"><b>Inactive</b></span>');
					data.is_active == 1 ? $('#leaving_div').hide() : $('#leaving_div').show();

					data.allow_add == 1 ? $('#div_allow_add_edit').html('Allowed') : $('#div_allow_add_edit').html('Not Allowed');
					data.allow_verify == 1 ? $('#div_allow_verify').html('Allowed') : $('#div_allow_verify').html('Not Allowed');
					data.allow_approve == 1 ? $('#div_allow_approve').html('Allowed') : $('#div_allow_approve').html('Not Allowed');
					data.is_admin == 1 ? $('#div_admin_role').html('Yes') : $('#div_admin_role').html('No');

					var general_skills_name = '';
					if(data.general_skills_name){
						for(var i =0; i < data.general_skills_name.length; i++){
							if((i+1) == data.general_skills_name.length){
								general_skills_name += data.general_skills_name[i].skills_name
							}
							else{
								general_skills_name += data.general_skills_name[i].skills_name +', '
							}
						}
					}

					var specific_skills_name = '';
					if(data.specific_skills_name){
						for(var i =0; i < data.specific_skills_name.length; i++){
							if((i+1) == data.specific_skills_name.length){
								specific_skills_name += data.specific_skills_name[i].skills_name
							}
							else{
								specific_skills_name += data.specific_skills_name[i].skills_name +', '
							}
						}
					}
					$('#div_general_skills')		.html(general_skills_name);
					$('#div_general_skills')		.attr('data-id', data.general_skills);
					$('#div_specific_skills')		.html(specific_skills_name);
					$('#div_specific_skills')		.attr('data-id', data.specific_skills);
												
					var row			= '';
					for(var i = 0; i < data_asset.length; i++)
					{
						row += '<tr>'+
									'<td>' + data_asset[i].type_name 			+ '</td>';
							 data_asset[i].owner_name != null  ? row += '<td>'+ data_asset[i].owner_name + '</td>' : row += '<td>MSP</td>';
							 data_asset[i].brand_name != null && data_asset[i].brand_name != ''  ? row += '<td>'+ data_asset[i].brand_name + '</td>' : row += '<td>-</td>';
							 data_asset[i].taken_date != null	? row += '<td>'+ data_asset[i].taken_date + '</td>' : row += '<td>-</td>';
							 data_asset[i].return_date != null ? row += '<td>'+ data_asset[i].return_date + '</td>' : row += '<td>-</td>';
						row += '</tr>';
		
					}
					$('#tbl_asset tbody').html(row);
				}
			},true
		);

	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

$.fn.reset_upload_form = function()
{
	$('#files').html('');
    var $fileupload = $('#fileupload');
    $fileupload.unbind('fileuploadadd');
};

$.fn.init_upload_file = function()
{

    $.fn.reset_upload_form();

    var $fileupload 		= $('#fileupload');

    $fileupload.fileupload
    ({
        url					: CURRENT_PATH + upload_file_path,
        dataType			: 'json',
        autoUpload			: false,
        acceptFileTypes		: /(\.|\/)(pdf)$/i,
        maxFileSize			: undefined,
        disableImageResize	: /Android(?!.*Chrome)|Opera/.test(window.navigator.userAgent),
        previewMaxWidth		: 80,
        previewMaxHeight	: 80,
        previewCrop			: true,
    });


    $fileupload.bind('fileuploadsubmit', function (e, data)
    {
        data.formData 	=
        {
            upload_path: 'photos/' + EMP_ID + '/'
        };
    });


    $fileupload.bind('fileuploadadd', function (e, data)
    {
        var $files = $('#files');
        $.each(data.files, function (index, file)
        {
        	var reader = new FileReader();
	        reader.onload = function(e)
	        {
	            $('#img_emp_photo').attr('src', e.target.result);
	        };
	        reader.readAsDataURL(file);
        });
    });
};

$.fn.bind_command_events = function()
{
	try
	{
		$('#btn_edit_photo').click( function(e)
		{
			e.preventDefault();
			$('#btn_edit_done').show();
			$('#div_photo_container').removeClass('photo-container');
			$('#btn_edit_photo').hide();
			photo_editor = $('#img_emp_photo');
			photo_editor.croppie
			({
				viewport:
				{
					width: 150,
					height: 150,
					type: 'circle'
				},
				boundary:
				{
					width: 200,
					height: 200
				},
				// url: 'demo/demo-1.jpg',
				enforceBoundary: false
				// mouseWheelZoom: false
			});
		});

		$('.photo-container').hover(function()
		{
			$('#btn_edit_photo').css("display", "flex");
		},function()
		{
			$('#btn_edit_photo').css("display", "none");
		});

		$('#btn_edit_done').on('click', function (ev)
		{
            photo_editor.croppie('result',
            {
            	type: 'rawcanvas',
            	// size: { width: 300, height: 300 },
            	format: 'png'
            }).then(function (canvas)
            {
            	$.fn.fetch_data
				(
					$.fn.generate_parameter('edit_profile_pic',{image : canvas.toDataURL(),emp_id : SESSIONS_DATA.emp_id}),
					function(return_data)
					{
						console.log(return_data.data);

						photo_editor.croppie('destroy');
						$('#img_emp_photo').prependTo('#div_photo_container'); // NOTE: put it back where it belongs
						$('#div_photo_container div').remove(); 	// NOTE: and delete those pesky leftover divs

						$('#btn_edit_done').hide();
						$('#btn_edit_photo').show();
						$('#img_emp_photo').attr("src", return_data.data);
						$.fn.show_right_success_noty('Profile picture has been updated successfully');
					}
				);
			});
		});

		$('.btn-edit-details').on('click', function(ev)
		{
			console.log("button clicked - ", $(this).attr('id'));		//TODO: remove this later
			var id = $(this).attr('id');

			switch (id) {
				case 'btn_edit_email':
					$('#view_email').hide();
					$('#edit_email').show();

					var str = $('#div_email').text();
					$('#txt_email').val(str).focus();

					break;

				case 'btn_edit_phone':
					$('#view_phone').hide();
					$('#edit_phone').show();

					var str = $('#div_phone').text();
					var str2 = $('#div_mobile').text();

					$('#txt_phone').val(str);
					$('#txt_mobile').val(str2);
					break;

				case 'btn_edit_home_address':
					$('#view_home_address').hide();
					$('#edit_home_address').show();

					var str = $('#div_home_address').text();
					$('#txt_home_address').val(str).focus();

					break;
				case 'btn_edit_current_address':
					$('#view_current_address').hide();
					$('#edit_current_address').show();

					var str = $('#div_current_address').text();
					$('#txt_current_address').val(str).focus();

					break;
				case 'btn_edit_general_skills':
					$('#view_general_skills').hide();
					$('#edit_general_skills').show();

					var str = $('#div_general_skills').attr('data-id');
					str ? $('#dd_general_skills').val(str.split(',')).change() : $('#dd_general_skills').val('').change();

					break;
				case 'btn_edit_specific_skills':
					$('#view_specific_skills').hide();
					$('#edit_specific_skills').show();

					var str = $('#div_specific_skills').attr('data-id');
					str ? $('#dd_specific_skills').val(str.split(',')).change() : $('#dd_specific_skills').val('').change();

					break;
			}
		});

		$('.btn-cancel-edit').on('click', function(ev) {

			let id = $(this).attr('id');

			switch (id) {
				case 'btn_cancel_email':
					$('#view_email').show();
					$('#edit_email').hide();
					break;

				case 'btn_cancel_phone':
					$('#view_phone').show();
					$('#edit_phone').hide();
					break;

				case 'btn_cancel_home_address':
					$('#view_home_address').show();
					$('#edit_home_address').hide();
					break;

				case 'btn_cancel_current_address':
					$('#view_current_address').show();
					$('#edit_current_address').hide();
					break;

				case 'btn_cancel_general_skills':
					$('#view_general_skills').show();
					$('#edit_general_skills').hide();
					break;

				case 'btn_cancel_specific_skills':
					$('#view_specific_skills').show();
					$('#edit_specific_skills').hide();
					break;
			}

		});

		$('.btn-save-edit').on('click', function (ev) {
			let id = $(this).attr('id');

			switch (id) {
				case 'btn_save_email':
					var data = {
						id 		: EMP_ID,
						emp_id 	: SESSIONS_DATA.emp_id,
						email 	: $('#txt_email').val()
					};
					$.fn.update_field(
						data,
						function () {
							var str = $('#txt_email').val();
							$('#div_email').html(str);
							$('#view_email').show();
							$('#edit_email').hide();
						}
					);
					break;
				case 'btn_save_phone':
					var data = {
						id 		: EMP_ID,
						emp_id 	: SESSIONS_DATA.emp_id,
						phone 	: $('#txt_phone').val(),
						mobile 	: $('#txt_mobile').val()
					};
					$.fn.update_field(
						data,
						function () {
							var str  = $('#txt_phone').val(),
								str2 = $('#txt_mobile').val();
							$('#div_phone').html(str);
							$('#div_mobile').html(str2);
							$('#view_phone').show();
							$('#edit_phone').hide();
						}
					);
					break;
				case 'btn_save_home_address':
					var data = {
						id 			 : EMP_ID,
						emp_id 		 : SESSIONS_DATA.emp_id,
						home_address : $('#txt_home_address').val()
					};
					$.fn.update_field(
						data,
						function () {
							var str = $('#txt_home_address').val();
							$('#div_home_address').html(str.replace(/\r?\n/g,'<br/>'));

							$('#view_home_address').show();
							$('#edit_home_address').hide();
						}
					);
					break;
				case 'btn_save_current_address':
					var data = {
						id 			 	: EMP_ID,
						emp_id 		 	: SESSIONS_DATA.emp_id,
						current_address : $('#txt_current_address').val()
					};
					$.fn.update_field(
						data,
						function () {
							var str = $('#txt_current_address').val();
							$('#div_current_address').html(str.replace(/\r?\n/g,'<br/>'));

							$('#view_current_address').show();
							$('#edit_current_address').hide();
						}
					);

					break;
				case 'btn_save_general_skills':
					console.log($('#dd_general_skills').val().toString());
					var data = {
						id 			 	: EMP_ID,
						emp_id 		 	: SESSIONS_DATA.emp_id,
						general_skills  : $('#dd_general_skills').val().toString()
					};
					$.fn.update_field(
						data,
						function () {
							$('#div_general_skills').html($.fn.convert_skills_view('general', $('#dd_general_skills').val()));

							$('#view_general_skills').show();
							$('#edit_general_skills').hide();
						}
					);

					break;
				case 'btn_save_specific_skills':
					var data = {
						id 			 	: EMP_ID,
						emp_id 		 	: SESSIONS_DATA.emp_id,
						specific_skills : $('#dd_specific_skills').val().toString()
					};
					$.fn.update_field(
						data,
						function () {
							$('#div_specific_skills').html($.fn.convert_skills_view('specific', $('#dd_specific_skills').val()));

							$('#view_specific_skills').show();
							$('#edit_specific_skills').hide();
						}
					);

					break;
			}
		})
	}
	catch(err)
    {
        $.fn.log_error(arguments.callee.caller,err.message);
    }
};

$.fn.convert_skills_view = function(type, values) 
{
	try {
		let array = [], result = [];

		if (type == 'general') {
			array = JSON.parse($('#edit_general_skills').attr('data-obj'));

			values.forEach(function(item) {
				for (let i of array) {
					if (i.id == item) {
						result.push(i.skills_name);
						break;
					}
				}
			})
		}
		else if (type == 'specific') {
			array = JSON.parse($('#edit_specific_skills').attr('data-obj'));

			values.forEach(function(item) {
				for (let i of array) {
					if (i.id === item) {
						result.push(i.skills_name);
						break;
					}
				}
			})
		}

		return result.toString();
	}
	catch (err) {
		$.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.update_field = function(objData, call_back) {
	try {

		$.fn.write_data(
			$.fn.generate_parameter('update_profile', objData),
			function (return_data) {
				if (return_data.data) {
					console.log(return_data.data);
					call_back(return_data);
				}
			}
		);
	}
	catch (err) {
		console.log(err);
		// $.fn.log_error(arguments.callee.caller,err.message);
	}
}

$.fn.form_load = function()
{
	try
	{
//		SESSIONS_DATA = JSON.parse($('#session_data').val());
		$.fn.populate_detail_form();
		$.fn.bind_command_events();
		$.fn.init_upload_file();

	}
	catch(err)
	{
		$.fn.log_error(arguments.callee.caller,err.message);
	}
};

// START of Document initialization
$(document).ready(function()
{
	$.fn.form_load();

});
// END of Document initialization
