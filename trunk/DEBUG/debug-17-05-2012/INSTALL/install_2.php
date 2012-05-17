<?php

if (!isset($_SERVER['HTTP_HOST'])) {
    exit('This script cannot be run from the CLI. Run it from a browser.');
}

$error = 0;
$win = 0;

if (isset($_POST['distributionbundle_doctrine_step']))
{
	if (!empty($_POST['distributionbundle_doctrine_step']['driver']) &&
	    !empty($_POST['distributionbundle_doctrine_step']['host']) &&
	    !empty($_POST['distributionbundle_doctrine_step']['name']) &&
	    !empty($_POST['distributionbundle_doctrine_step']['user']))
	    {

$new_parameters = "
; These parameters can be imported into other config files
; by enclosing the key with % (like %database_user%)
; Comments start with ';', as in php.ini
[parameters]
database_driver=\"".$_POST['distributionbundle_doctrine_step']['driver']."\"
database_host=\"".$_POST['distributionbundle_doctrine_step']['host']."\"
database_port=\"".$_POST['distributionbundle_doctrine_step']['port']."\"
database_name=\"".$_POST['distributionbundle_doctrine_step']['name']."\"
database_user=\"".$_POST['distributionbundle_doctrine_step']['user']."\"
database_password=\"".$_POST['distributionbundle_doctrine_step']['password']['password']."\"
mailer_transport=\"smtp\"
mailer_host=\"localhost\"
mailer_user=\"\"
mailer_password=\"\"
locale=\"en\"
secret=\"01e324a59f7ff5232919a83d4ade681c2024d876\"\n";
	    
	    $result = file_put_contents("../app/config/parameters.ini", $new_parameters);
	    if ($result == FALSE)
	       $error = 1;
	    
	    }
	    else
		$win = 1;
}




?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <link href="css/install.css" rel="stylesheet" media="all" />
        <title>Scube Installation step 2</title>
    </head>
    <body>
        <div id="symfony-wrapper">
            <div id="symfony-content">
                <div class="symfony-blocks-install">
                <div class="symfony-block-logo">
                    <img src="images/logo_scube.png" alt="sf_symfony" />
                </div>

                <div class="symfony-block-content">
                    <h1>Database set up</h1>

<?php
if ($win)
   echo "<p>The configuration step is now finished !<br />You can now use the Scube admin.</p>";
else if (!$error)
{
?>
<p>Please complete the following form</p>
<form action="install_2.php" method="POST">
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

            <div class="symfony-form-errors"></div><div class="symfony-form-row"><label for="distributionbundle_doctrine_step_password_password">
        Password
            </label><div class="symfony-form-field"><input type="password" id="distributionbundle_doctrine_step_password_password" name="distributionbundle_doctrine_step[password][password]" /><div class="symfony-form-errors"></div></div></div><div class="symfony-form-row"><label for="distributionbundle_doctrine_step_password_password_again">
        Password again
            </label><div class="symfony-form-field"><input type="password" id="distributionbundle_doctrine_step_password_password_again" name="distributionbundle_doctrine_step[password][password_again]" /><div class="symfony-form-errors"></div></div></div>
        </div>

        <input type="hidden" id="distributionbundle_doctrine_step__token" name="distributionbundle_doctrine_step[_token]" value="21a8c238bc232c0fab11533ae482087c1ce2654a" /><div class="symfony-form-row"><label for="distributionbundle_doctrine_step_port">
        Port
            </label><div class="symfony-form-field"><input type="text" id="distributionbundle_doctrine_step_port" name="distributionbundle_doctrine_step[port]" /><div class="symfony-form-errors"></div></div></div>

        <div class="symfony-form-footer">
            <p><input type="submit" value="Next Step" class="symfony-button-grey" /></p>
            <p>* mandatory fields</p>
        </div>
    </form>
<?php
}
else
{
?>

	<p>Unable to open the SQL configuration file, you need to replace the file ./app/config/parameters.ini with the following:</p>

<?php
	echo "<textarea class='symfony-configuration'>".$new_parameters."</textarea>";
}
?>


                </div>
            </div>
        </div>
    </body>
</html>
