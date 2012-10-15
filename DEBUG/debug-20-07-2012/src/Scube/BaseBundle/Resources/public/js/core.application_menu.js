// JavaScript Document

$(document).ready(function() 
	{
		check_for_menu_active_item();
	});

function check_for_menu_active_item()
{
	basepath_current_url = get_basepath($(location).attr('href'));
	
	if ($("ul.sidebar_navigation").length) {
		$("ul.sidebar_navigation").each(function(index, element) {
			li_navigation = $(element).children();
			li_navigation.each(function(index, element) {
				element_a = $(element).find("a");
				
				if (element_a.length) {
					basepath_a = get_basepath(element_a.attr("href"));
					if (basepath_a == basepath_current_url)
						element_a.addClass("active");
				}	
			});
		});
	}
}

function get_basepath(str)
{
	str_split = str.split("/");
	nb = str_split.length;
	basepath = "";
	if (nb && str_split[nb - 1])
		basepath = str_split[nb - 1];
	
	return basepath;
}