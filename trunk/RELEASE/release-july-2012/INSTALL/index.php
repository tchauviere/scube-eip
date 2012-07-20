<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="js/jquery.1.7.1.js"></script>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Scube - install</title>

<style>

body {
	background-color:#FFF;
	font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
}
h1 {
	margin:0px;
	margin-bottom:20px;
	color:#1a2732;
	font-size:20px;
}
h2 {
	margin:20px;
	margin-bottom:20px;
	color:#1a2732;
	font-size:18px;
}
.error {
	color:#94211A;
}
.warning {
	color:#B7AA10;
}
p {
	color:#51626f;
	font-size:14px;
}
li {
	color:#51626f;
	font-size:14px;
}

form {
	margin:0 auto;
	text-align:center;
}

#header {
	text-align:center;
	margin:10px auto;
}

#steps {
	width:800px;
	height:60px;
	margin:10px auto;
	background-color:#EFEFEF;
	-moz-box-shadow: 0px 0px 5px 0px #8996a0;
	-webkit-box-shadow: 0px 0px 5px 0px #8996a0;
	-o-box-shadow: 0px 0px 5px 0px #8996a0;
	box-shadow: 0px 0px 5px 0px #8996a0;
	filter:progid:DXImageTransform.Microsoft.Shadow(color=#8996a0, Direction=NaN, Strength=5);
	-moz-border-radius: 25px;
	-webkit-border-radius: 25px;
	border-radius: 25px;
}

.step {
	width:25%;
	height:60px;
	float:left;
	vertical-align:middle;
	background:transparent url(images/step.png) right center no-repeat;
	
	color:#a5aeb6;
	font-size:18px;
	text-shadow: 0px 1px 1px white;
	line-height:60px;
	text-align:center;
}
.step img {
	height:26px;
	vertical-align:middle;
}
.active {
	color:#1a2732;
}

#step_1 {
	-webkit-border-top-left-radius: 25px;
	-webkit-border-bottom-left-radius: 25px;
	-moz-border-radius-topleft: 25px;
	-moz-border-radius-bottomleft: 25px;
	border-top-left-radius: 25px;
	border-bottom-left-radius: 25px;
}

#step_2 {
}

#step_3 {
}

#step_4 {
	background:none;
	-webkit-border-top-right-radius: 25px;
	-webkit-border-bottom-right-radius: 25px;
	-moz-border-radius-topright: 25px;
	-moz-border-radius-bottomright: 25px;
	border-top-right-radius: 25px;
	border-bottom-right-radius: 25px;
}

#current_step {
	display:none;
	width:760px;
	height:auto;
	margin:30px auto;
	padding:20px;
	background-color:#FFF;
	-moz-box-shadow: 0px 0px 5px 0px #8996a0;
	-webkit-box-shadow: 0px 0px 5px 0px #8996a0;
	-o-box-shadow: 0px 0px 5px 0px #8996a0;
	box-shadow: 0px 0px 5px 0px #8996a0;
	filter:progid:DXImageTransform.Microsoft.Shadow(color=#8996a0, Direction=NaN, Strength=5);
	-moz-border-radius: 25px;
	-webkit-border-radius: 25px;
	border-radius: 25px;
}

#loading {
	text-align:center;
	margin:30px;
}


.button_line {
	height:60px;
	margin:20px;
}

.button_reload {
	height:60px;
	width:300px;
	float:left;
	vertical-align:middle;
	background-color:#EFEFEF;
	color:#1a2732;
	font-size:18px;
	text-shadow: 0px 1px 1px white;
	line-height:60px;
	text-align:center;
	text-decoration:none;
	display:block;
	-moz-box-shadow: 0px 0px 5px 0px #8996a0;
	-webkit-box-shadow: 0px 0px 5px 0px #8996a0;
	-o-box-shadow: 0px 0px 5px 0px #8996a0;
	box-shadow: 0px 0px 5px 0px #8996a0;
	filter:progid:DXImageTransform.Microsoft.Shadow(color=#8996a0, Direction=NaN, Strength=5);
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
}
.button_reload img {
	height:26px;
	vertical-align:middle;
}

.button_nextstep {
	height:60px;
	width:300px;
	float:right;
	vertical-align:middle;
	background-color:#EFEFEF;
	color:#1a2732;
	font-size:18px;
	text-shadow: 0px 1px 1px white;
	line-height:60px;
	text-align:center;
	text-decoration:none;
	display:block;
	-moz-box-shadow: 0px 0px 5px 0px #8996a0;
	-webkit-box-shadow: 0px 0px 5px 0px #8996a0;
	-o-box-shadow: 0px 0px 5px 0px #8996a0;
	box-shadow: 0px 0px 5px 0px #8996a0;
	filter:progid:DXImageTransform.Microsoft.Shadow(color=#8996a0, Direction=NaN, Strength=5);
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
}
.button_nextstep img {
	height:26px;
	vertical-align:middle;
}

.button_submit {
	height:60px;
	width:300px;
	margin:0 auto;
	vertical-align:middle;
	background-color:#EFEFEF;
	color:#1a2732;
	font-size:18px;
	text-shadow: 0px 1px 1px white;
	line-height:60px;
	text-align:center;
	text-decoration:none;
	display:block;
	-moz-box-shadow: 0px 0px 5px 0px #8996a0;
	-webkit-box-shadow: 0px 0px 5px 0px #8996a0;
	-o-box-shadow: 0px 0px 5px 0px #8996a0;
	box-shadow: 0px 0px 5px 0px #8996a0;
	filter:progid:DXImageTransform.Microsoft.Shadow(color=#8996a0, Direction=NaN, Strength=5);
	-moz-border-radius: 10px;
	-webkit-border-radius: 10px;
	border-radius: 10px;
}
.button_submit img {
	height:26px;
	vertical-align:middle;
}

</style>

<script>

$(document).ready(function() {
	
	step_1();

});



function	step_1()
{
	$.ajax({
	  url: 'php_steps/s1.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_1();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

function	step_2()
{
	$("#step_1").removeClass("active");
	$("#step_2").addClass("active");
	$.ajax({
	  url: 'php_steps/s2.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_2();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

function	step_2_submit()
{
	submitdata = "distributionbundle_doctrine_step_driver="+$("#distributionbundle_doctrine_step_driver").val();
	submitdata += "&distributionbundle_doctrine_step_host="+$("#distributionbundle_doctrine_step_host").val();
	submitdata += "&distributionbundle_doctrine_step_name="+$("#distributionbundle_doctrine_step_name").val();
	submitdata += "&distributionbundle_doctrine_step_user="+$("#distributionbundle_doctrine_step_user").val();
	
	if ($("#distributionbundle_doctrine_step_port").val())
		submitdata += "&distributionbundle_doctrine_step_port="+$("#distributionbundle_doctrine_step_port").val();
	else
		submitdata += "&distributionbundle_doctrine_step_port=";
	
	if ($("#distributionbundle_doctrine_step_password").val())
		submitdata += "&distributionbundle_doctrine_step_password="+$("#distributionbundle_doctrine_step_password").val();
	else
		submitdata += "&distributionbundle_doctrine_step_password=";
		
	$.ajax({
	  type:"POST",
	  data:submitdata,
	  url: 'php_steps/s2.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_2();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

function	step_3()
{
	$("#step_2").removeClass("active");
	$("#step_3").addClass("active");
	$.ajax({
	  url: 'php_steps/s3.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_3();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

function	step_3_submit()
{
	submitdata = "path="+$("#path").val();
	
	$.ajax({
	  type:"POST",
	  data:submitdata,
	  url: 'php_steps/s3.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_3();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

function	step_4()
{
	$("#step_3").removeClass("active");
	$("#step_4").addClass("active");
	$.ajax({
	  url: 'php_steps/s4.php',
	  timeout:120000000,
	  error: function() {
		$('#current_step').html("<h2 class='error'>An error occured, <a href='#' onclick='step_4();'>Please reload this step</a></h2>");
		$('#loading').hide();
		$('#current_step').fadeIn();
	  },
	  beforeSend: function() {
		$('#current_step').hide();
		$('#loading').show();
	  },
	  success: function(data) {
		$('#current_step').html(data);
		$('#loading').hide();
		$('#current_step').fadeIn();
	  }
	});
}

</script>

</head>

<body>

<div id="header">
	<img src="images/logo.png" alt="Scube" />
</div>

<div id="steps">
	<div class="step active" id="step_1">
    <img src="images/step_1.png" alt="1." /> Requirements
    </div>
    
    <div class="step" id="step_2">
    <img src="images/step_2.png" alt="2." /> Database
    </div>
    
    <div class="step" id="step_3">
    <img src="images/step_3.png" alt="3." /> Preparation
    </div>
    
    <div class="step" id="step_4">
    <img src="images/step_4.png" alt="4." /> Account
    </div>
</div>

<div id="current_step"></div>

<div id="loading"><img src="images/loading.gif" alt="Now loading.." /></div>

</body>
</html>