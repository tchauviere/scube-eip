<?php
set_time_limit(120);
?>

<h1>Step 3: Preparation of the environment</h1>

<?php
if (isset($_POST['path']))
{
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
		?>
        	<p class="error">Your binary is wrong. Please try another.</p>
        	<div class="button_line">
                <a class="button_reload" href="#" onclick="step_3();"><img src="images/reload.png" alt="Reload" /> Try another binary</a>
            </div>
        <?php
	}
	else
	{
		$assetic = shell_exec($path_php.' ../../app/console assetic:dump');
		$drop = shell_exec($path_php.' ../../app/console doctrine:schema:drop --force');
		$doc      = shell_exec($path_php.' ../../app/console doctrine:schema:update --force');
		
		print ("<p style='color:black;text-align:center;'>PHP -v : <br><br><textarea name='php' cols=70 rows=5 readonly>".$valueReturn."</textarea></p>");
		print ("<p style='color:black;text-align:center;'>Assetic:dump return : <br><br><textarea name='ass' cols=70 rows=5 readonly>".$assetic."</textarea></p>");
		print ("<p style='color:black;text-align:center;'>Doctrine:schema:drop return : <br><br><textarea name='drop' cols=70 rows=5 readonly>".$drop."</textarea></p>");
		print ("<p style='color:black;text-align:center;'>Doctrine:schema:update return : <br><br><textarea name='doc' cols=70 rows=5 readonly>".$doc."</textarea></p>");
		
		?>
        	<div class="button_line">
               <a class="button_nextstep" href="#" onclick="step_4();">Next step <img src="images/next.png" alt="Next" /></a>
            </div>
        <?php
	}
}
else
{
	?>
    <p style="text-align:center;"> Please enter the full PATH to your php.exe (server side): </p>
    <form method='post' action='#' >   
        
        <input id="path" type='text' name='path' accept='txt' value="php"><br>
        
    </form>
    
    <div class="button_line">
    	<a class="button_submit" href="#" onclick="step_3_submit();">Submit <img src="images/submit.png" alt="Submit" /></a>
    </div>
    
    <?php
}
?>