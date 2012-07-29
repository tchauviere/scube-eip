var	application_started = false;
var	applications_suspended = new Array();

function	start_application_loader()
{
	$('.AppBox').live('click', function(event) {
		
		start_application(this);
		
		event.preventDefault();
		event.stopPropagation();
	});
}

function	start_application(element)
{
	var rnd = Math.floor(Math.random()*1001);
	var id = "Application_"+rnd
	var tpl = "<div id='"+id+"' class='ApplicationFrame'></div>";
	var tpl_iframe = "<iframe src='"+$(element).attr("href")+"'></iframe>";
	var tpl_close = "<a class='ApplicationClose' href='#' onclick='close_application(\""+id+"\");'>"+$("#Application_closer").html()+"</div>";
	var tpl_suspend = "<a class='ApplicationSuspend' href='#' onclick='suspend_application(\""+id+"\");'>"+$("#Application_suspender").html()+"</div>";
	
	if (application_started)
		close_application(application_started);
	/*
	if (applications_suspended[id] == true) {
		unsuspend_application(id);
		return;
	}
	*/
	
	application_started = id;
	
	$("body").append(tpl);
	$("#"+id).css({
				  'width':($(window).width() * 70)/100,
				  /*'height':($(window).height() - $("#header").height()),*/
				  'top':$("#header").height(),
				  'left':($(window).width() / 2) - ((($(window).width() * 70)/100) / 2),
				  });
	
	
	$("#"+id).html( $("#Application_loader").html() );
	$("#"+id).slideDown();
	$("#"+id).append(tpl_iframe);
	$("#"+id+" iframe").load(function() {
		$("#"+id).animate({
				  'height':($(window).height() - $("#header").height())
				  }, 800, function() {
					  	$("#"+id+" img").remove();
   						$("#"+id+" iframe").fadeIn();
						$("#"+id).prepend(tpl_close + tpl_suspend);
					  });
    	
    });
	
}

function	close_application(id_application)
{
	$("#"+id_application).slideUp(800, function() {
		$("#"+id_application).remove();
		application_started = false;
	});
}

function	suspend_application(id_application)
{
	$("#"+id_application).slideUp('fast', function() {
		applications_suspended[id_application] = true;
		application_started = false;
	});
}

/* Not working */
function	unsuspend_application(id_application)
{
	$("#"+id_application).slideDown();
	applications_suspended[id_application] = false;
	application_started = id_application;
}