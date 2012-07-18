<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href="css/install.css" rel="stylesheet" media="all" />
        <title>Scube Installation step 3</title>		
    </head>
	<body>
        <div id="symfony-wrapper">
            <div id="symfony-content">
                <div class="symfony-blocks-install">
                <div class="symfony-block-logo">
                    <img src="images/logo_scube.png" alt="sf_symfony" />
                </div>

                <div class="symfony-block-content">
                <h1>Assetic Dump & Doctrine Schema Update</h1>
		<body>
	</html>
<?php
$path_php = $_POST['path'];
$path_php = str_replace('.exe', '', $path_php);
$test_php = $path_php.' -v';

print("<p style='color:black'>PHP Path : <span style='color:blue;'>".$path_php."</span></p>");
print("<p style='color:black'>Test PHP : <span style='color:blue;'>".$test_php."</span></p>");

$check1 = strstr($path_php, 'php');

while ($check1 != 'php' && strlen($check1) > 2)
{
	$size = strlen($check1);
	
	$size = $size - 1;
	$size = $size * -1;
	$check1 = substr($check1,  $size);
	$check1 = strstr($check1, 'php');
}
$valueReturn = '';
if ($check1 != "php"){}
else
	$valueReturn = shell_exec($test_php);
if (!$valueReturn)
{
	//print ("<br><p style='color:red'>Please go <strong>BACK</strong> select the right binary.</p>");
	print("<center><a href='./install_2.php'>  <span style='color:red;'> Please go Back and select the right binary </span></a></p></center>");
}
else
{
	print ($valueReturn."<br>");
    echo "<br>";
    
    $assetic = shell_exec($path_php.' ../app/console assetic:dump');
    $drop = shell_exec($path_php.' ../app/console doctrine:schema:drop --force');
    $doc      = shell_exec($path_php.' ../app/console doctrine:schema:update --force');

    print ("<p style='color:black'>Assetic:dump return : <br><br><textarea name='ass' cols=70 rows=20 readonly>".$assetic."</textarea></p>");
	print ("<p style='color:black'>Doctrine:schema:drop return : <br><br><textarea name='drop' cols=70 rows=5 readonly>".$drop."</textarea></p>");
    print ("<p style='color:black'>Doctrine:schema:update return : <br><br><textarea name='doc' cols=70 rows=5 readonly>".$doc."</textarea></p>");
    
    print("<center>And then : <a href='../web/app_dev.php/install'>Now you can use it</a></p></center>");
}
?>