// JavaScript Document

$(document).ready(function() 
	{
		check_for_scroller();
	});
$(window).resize(function() 
	{
		check_for_scroller();
	});

function check_for_scroller()
{
	if ($("div.scroll_container").length) {
		$("div.scroll_container").each(function(index, element) {
			resize_scroller(this);
		});
	}
}

function resize_scroller(container)
{
	var available_height = $(document).height(true);
	var inside_element = $(container).find(".scroll_inside");
	
	elements_to_calc = $(container).parent().children();
	elements_to_calc.each(function(index, element) {
		if ($(element).attr("rel") != "scroller")
        	available_height -= $(element).outerHeight(true);
    });
	
	$(container).css("height", available_height);
	
	/*return;
	$('form.scroll_inside').css("max-height", available_height);*/
}