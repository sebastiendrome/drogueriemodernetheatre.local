<?php
/* initial setup for .htaccess file, which has been re-written to adapt to the sub-directory location of the site */
// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// document root (beware of inconsistent trailing slash depending on environment, hence the use of realpth)
define("ROOT", realpath($_SERVER['DOCUMENT_ROOT']).'/');

// directory agnostic DEMO var extracts whatever dir is between root and ~code
$demo = preg_replace('/~code\/.*$/', '', str_replace(ROOT, '', __FILE__));
define("DEMO", $demo);

$message = '';
$create_file_test = ROOT.DEMO.'~content/tmp.txt';
$htaccess_file = ROOT.DEMO.'.htaccess';
$htaccess_to_move = ROOT.DEMO.'~content/.htaccess';
$htaccess_template = ROOT.DEMO.'~code/templates/htaccess.txt';
$ht_content = file_get_contents($htaccess_template);
$new_ht_content = str_replace('[%rep%]', DEMO, $ht_content);


/* messages output: */

$success_message = '<div class="cont success"><h3>Congratulations, you\'re all set!</h3>
<p>The two links below will open two windows (or tabs). It\'s best to have these two windows open when you work on your site.</p>
<a href="../~code/admin/" target="_admin" style="display:inline-block; float:left; width:48%; border:1px solid #ccc; padding:20px 0; text-align:center; background-color:#fff;">Open your admin area</a>
<a href="../" target="_site" style="display:inline-block; float:right; width:48%; border:1px solid #ccc; padding:20px 0; text-align:center; background-color:#fff;">Open your site</a>
</p>
<div style="clear:both;">&nbsp;</div>
<div style="float:left; width:48%;">↑ You\'ll need to login.<br>
Your username is your email, your password figures in the email your received with your download link.</div><div style="float:right; width:48%;">↑ This will open your site home page.</div>
<div style="clear:both;">&nbsp;</div>
</div>';

$change_permissions_message = '<div class="cont">
<h3>Ok, a few adjustments are needed for KYM to work on this server:</h3>
<p>You need to access your site server with and FTP program, and set the permissions for this folder:</p>
<b>'.SITE.DEMO.'~content</b>
<p>Permissions need to be set to: 766</p>
<p>When this is done, please reload this page (or <a href="'.$_SERVER['REQUEST_URI'].'">click here</a>).</p>
<p style="color:grey; margin-top:40px;">If you have trouble connecting to your server, contact <a href="mailto:kym.killyourmaster@gmail.com">kym.killyourmaster@gmail.com</a> for assistance. Make sure to state your name, email address, and site name in your email.</p>
</div>';

$htaccess_move_message = '<div class="cont">
<h3>Good, we\'re almost set!</h3>
1. You need to access your site with an FTP program, and<br>
<b>delete this file:</b>
<p style="color:red;">'.SITE.DEMO.'.htaccess</p>
2. Then <b>locate this file</b>:
<p style="color:red;">'.SITE.DEMO.'~content/.htaccess</p>
<b>and move it to the location of the file you just deleted</b>:
<p style="color:red;">'.SITE.DEMO.'.htaccess</p>
Basically, grab the file and drag it into its parent folder, <br>out of the folder "~content" and into its containing folder.
<p style="font-weight:bold;">3. When that\'s done, reload this page (or <a href="'.$_SERVER['REQUEST_URI'].'">click here</a>), and you should be good to go!</p>
<p style="color:grey; margin-top:40px;">If the above instructions don\'t make sense to you, please contact <a href="mailto:kym.killyourmaster@gmail.com">kym.killyourmaster@gmail.com</a> for assistance. Make sure to state your name, email address, and site name in your email.</p>
</div>';

$htaccess_error_message = '<div class="cont error"><p>ERROR. Could not create '.$htaccess_to_move.'</p>
<p>Please contact <a href="mailto:kym.killyourmaster@gmail.com">kym.killyourmaster@gmail.com</a> for assistance. Make sure to state your full name, email address, and site name in your email.</p></div>';

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- portfolio version 3 -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- generic css -->
<link href="/~code/css/common.css" rel="stylesheet" type="text/css">

<style type="text/css">
div.cont{
	/*width:400px; */
	max-width:600px;
	min-width:300px;
	padding:20px;
	margin:50px auto; 
	background-color:#f5f5f5;
	border:1px solid #aaa;
	border-radius:3px;
}
div.cont.success{background-color:#cce9b2;}
div.cont.error{background-color: #e9b8b2;}
</style>

</head>

<body style="font-family:Arial, Helvetica, sans-serif;">

<?php

/* test directory permissions for ~content */
if( $fp = @fopen($create_file_test, 'w') ){
	unlink($create_file_test);
	fclose($fp);

	$message = $success_message;

}else{

	$message = $change_permissions_message;
}

// if .htaccess is missing, create it from templates/htaccess.txt
if( !file_exists($htaccess_file) ){
	if( $fp = @fopen($htaccess_file, 'w') ){
		fwrite($fp, $new_ht_content);
		fclose($fp);
	}else{
		if( $fp = @fopen($htaccess_to_move, 'w') ){
			fwrite($fp, $new_ht_content);
			fclose($fp);

			$message = $htaccess_move_message;

		}else{
			$message = $htaccess_error_message;
		}
	}
}

// if site is not in root directory, remove .htaccess file and replace it with custom 
if( !empty(DEMO) ){
	@unlink($htaccess_file);
	if( $fp = @fopen($htaccess_file, 'w') ){
		fwrite($fp, $new_ht_content);
		fclose($fp);

		$message = $success_message;

	}else{
		if( $fp = @fopen($htaccess_to_move, 'w') ){
			fwrite($fp, $new_ht_content);
			fclose($fp);

			$message = $htaccess_move_message;

		}else{
			
			$message = $htaccess_error_message;
		}
	}
}elseif( empty($message) ){

	$message = $success_message;
}

echo $message;
?>

</body>
</html>
