function	start_checkbox_system()
{
	$('input:checkbox[name=grid_lock]').change(function () {
		if ($(this).attr("checked")) {
			$('input:checkbox[name=grid_admin]').attr("disabled", false);
			switch_edit_mode(false);
		}
		else {
			$('input:checkbox[name=grid_admin]').attr("checked", false);
			$('input:checkbox[name=grid_admin]').attr("disabled", true);
			switch_edit_mode(true);
		}
	});
	$('input:checkbox[name=grid_admin]').change(function () {
		if ($(this).attr("checked")) {
			$('input:checkbox[name=grid_lock]').attr("checked", true);
			$('input:checkbox[name=grid_lock]').attr("disabled", true);
			edit_mode = false;
			clean_grid();
			switch_admin_mode(true);
		}
		else {
			$('input:checkbox[name=grid_lock]').attr("disabled", false);
			switch_admin_mode(false);
			edit_mode = false;
			load_Grid();
		}
	});
}