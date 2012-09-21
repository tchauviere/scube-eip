<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    exit('This script cannot be run from the CLI. Run it from a browser.');
}

$error = 0;
$win = 0;

if (isset($_POST))
{
	if (!empty($_POST['distributionbundle_doctrine_step_driver']) &&
	    !empty($_POST['distributionbundle_doctrine_step_host']) &&
	    !empty($_POST['distributionbundle_doctrine_step_name']) &&
	    !empty($_POST['distributionbundle_doctrine_step_user']))
	    {

$new_parameters = "
; These parameters can be imported into other config files
; by enclosing the key with % (like %database_user%)
; Comments start with ';', as in php.ini
[parameters]
database_driver=\"".$_POST['distributionbundle_doctrine_step_driver']."\"
database_host=\"".$_POST['distributionbundle_doctrine_step_host']."\"
database_port=\"".$_POST['distributionbundle_doctrine_step_port']."\"
database_name=\"".$_POST['distributionbundle_doctrine_step_name']."\"
database_user=\"".$_POST['distributionbundle_doctrine_step_user']."\"
database_password=\"".$_POST['distributionbundle_doctrine_step_password']."\"
mailer_transport=\"smtp\"
mailer_host=\"localhost\"
mailer_user=\"\"
mailer_password=\"\"
locale=\"en\"
secret=\"01e324a59f7ff5232919a83d4ade681c2024d876\"\n";
	    
		$filename = "../../app/config/parameters.ini";
		var_dump($_POST);
		if (is_writable($filename))
		{
			file_put_contents($filename, $new_parameters);
			$win = 1;
		}
	    else
		{
			$error = 1;
		}
	}
}




?>

<h1>Step 2: Database setup</h1>

				
<?php
if ($win)
  {
	  ?>
      
      <p><strong>The database setup has been finished successfully</strong> ! You can now go to step 3.</p>
      <div class="button_line">
            <a class="button_nextstep" href="#" onclick="step_3();">Next step <img src="images/next.png" alt="Next" /></a>
        </div>
      
      <?php
	  
		}
else if (!$error)
{
?>


<p style="text-align:center;">Please complete the following form (* mandatory fields)</p>
<form action="#" method="POST">
        <div class="symfony-form-column">
                <div class="symfony-form-row">
            <label for="distributionbundle_doctrine_step_driver">
        Driver
                    <span class="symfony-form-required" title="This field is required">*</span>
            </label>

        <div class="symfony-form-field">
            <select id="distributionbundle_doctrine_step_driver" name="distributionbundle_doctrine_step[driver]" required="required"><option value="pdo_mysql" selected="selected">MySQL (PDO)</option><option value="pdo_sqlite">SQLite (PDO)</option><option value="pdo_pgsql">PosgreSQL (PDO)</option><option value="oci8">Oracle (native)</option><option value="ibm_db2">IBM DB2 (native)</option><option value="pdo_oci">Oracle (PDO)</option><option value="pdo_ibm">IBM DB2 (PDO)</option><option value="pdo_sqlsrv">SQLServer (PDO)</option></select>
            <div class="symfony-form-errors">
                
            </div>
        </div>
    </div>

                <div class="symfony-form-row">
            <label for="distributionbundle_doctrine_step_host">
        Host
                    <span class="symfony-form-required" title="This field is required">*</span>
            </label>

        <div class="symfony-form-field">
            <input type="text" id="distributionbundle_doctrine_step_host" name="distributionbundle_doctrine_step[host]" required="required" value="localhost" />
            <div class="symfony-form-errors">
                
            </div>
        </div>
    </div>

                <div class="symfony-form-row">
            <label for="distributionbundle_doctrine_step_name">
        Name
                    <span class="symfony-form-required" title="This field is required">*</span>
            </label>

        <div class="symfony-form-field">
            <input type="text" id="distributionbundle_doctrine_step_name" name="distributionbundle_doctrine_step[name]" required="required" value="scube" />
            <div class="symfony-form-errors">
                
            </div>
        </div>
    </div>

        </div>
        <div class="symfony-form-column">
                <div class="symfony-form-row">
            <label for="distributionbundle_doctrine_step_user">
        User
                    <span class="symfony-form-required" title="This field is required">*</span>
            </label>

        <div class="symfony-form-field">
            <input type="text" id="distributionbundle_doctrine_step_user" name="distributionbundle_doctrine_step[user]" required="required" value="root" />
            <div class="symfony-form-errors">
                
            </div>
        </div>
    </div>

            <div class="symfony-form-errors"></div><div class="symfony-form-row"><label for="distributionbundle_doctrine_step_password">
        Password
            </label><div class="symfony-form-field"><input type="text" id="distributionbundle_doctrine_step_password" name="distributionbundle_doctrine_step[password]" /><div class="symfony-form-errors"></div></div></div>
            </div>
        </div>

        Port
            </label><div class="symfony-form-field"><input type="text" id="distributionbundle_doctrine_step_port" name="distributionbundle_doctrine_step[port]" /><div class="symfony-form-errors"></div></div></div>
		
        
    </form>
    
    <div class="button_line">
        <a class="button_submit" href="#" onclick="step_2_submit();">Submit <img src="images/submit.png" alt="Submit" /></a>
        </div>
<?php
}
else
{
?>

	<p class="error">Unable to open the SQL configuration file, you need to replace the file ./app/config/parameters.ini with the following:</p>

<?php
	echo "<textarea style='width:80%;height:250px;display:block;margin:0 auto;' class='symfony-configuration'>".$new_parameters."</textarea>";
?>
	<div class="button_line">
            <a class="button_nextstep" href="#" onclick="step_3();">Next step <img src="images/next.png" alt="Next" /></a>
        </div>
<?php
}
?>
