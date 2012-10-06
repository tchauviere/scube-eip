// JavaScript Document

/*

Types = success / error

*/


function add_notification(type, message) {
	
	if (type == "success") {
		var css = {'position':'absolute',
				   'top':'0px'
				   };
	}
	
	
	
	$(".Application").append("<p class='notification "+type+"'>"+message+"</p>");
	setTimeout('delete_notification()', 5000);
}

function delete_notification() {
	$('.notification').remove();
}