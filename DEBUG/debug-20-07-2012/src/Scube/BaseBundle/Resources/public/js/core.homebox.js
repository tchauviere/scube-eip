function	start_homebox_system(url)
{
	$("#home_corner").click(function() {
		if (!$("#home_box").length){
			$("#header").append("<div id='home_box'></div>");
			
			/* Get the content from server */
			$.ajax({
			  url: url,
			  success: function(data) {
				$('#home_box').html(data);
				$("#home_box").css({
									position:"absolute",
									top:0,
									left:0,
									"max-width":400
									
									});
				$("#home_box").fadeIn();
			  }
			});
			
		}
		else
			$("#home_box").fadeToggle();
		
		
	});
}