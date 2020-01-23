<?php
/* initial setup for .htaccess file, which has been re-written to adapt to the sub-directory location of the site */
// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// document root (beware of inconsistent trailing slash depending on environment, hence the use of realpth)
define("ROOT", realpath($_SERVER['DOCUMENT_ROOT']).'/');

// directory agnostic DEMO var extracts whatever dir is between root and ~code
$demo = preg_replace('/~code\/.*$/', '', str_replace(ROOT, '', __FILE__));
define("DEMO", $demo);

$success_message = '<div class="cont"><b>Congratulations, you\'re all set!</b></p>
<p>The two links below will open two windows (or tabs). It\'s best to have these two windows open when you work on your site.</p>
<a href="../~code/admin/preferences.php" target="_admin" style="display:inline-block; float:left; width:48%; border:1px solid #ccc; padding:20px 0; text-align:center;">Open your admin area</a>
<a href="../" target="_site" style="display:inline-block; float:right; width:48%; border:1px solid #ccc; padding:20px 0; text-align:center;">Open your site</a>
</p>
<div style="clear:both;">&nbsp;</div>
<div style="float:left; width:48%;">↑ you\'ll need to login, with<br>
username: admin<br>
password: password<br>
You can change your login info in the admin area.</div><div style="float:right; width:48%;">↑ this will open your site home page.</div>
<div style="clear:both;">&nbsp;</div>
</div>';

?>

<!DOCTYPE HTML>
<html lang="en">
<head>
<!-- portfolio version 3 -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<!-- generic css -->
<link href="/~code/css/common.css" rel="stylesheet" type="text/css">

<style>
div.cont{
	width:400px; 
	padding:20px;
	margin:50px auto; 
	background-color:#fff; 
	border:5px solid #ccc; 
	border-radius:3px;
}
</style>

</head>

<body style="font-family:Arial, Helvetica, sans-serif;">

<?php

/* test directory permissions for ~content */
$parent_dir = ROOT.DEMO.'~content';
$file = $parent_dir.'/tmp.txt';
if($fp = @fopen($file, 'w')){
	unlink($file);
	fclose($fp);
}else{

	echo '<div class="cont">
	<h3>Ok, a few adjustments are needed for KYM to work on this server:</h3>
	<p>You need to access your site with and FTP program, and set the permissions for this folder:</p>
	<b>'.SITE.DEMO.'~content</b>
	<p>Permissions need to be set to: 777</p>
	<p>When this is done, please reload this page.</p>
	</div>
	
	</body>
	</html>';

	exit();
}


if( !empty(DEMO) ){
	$htaccess_file = ROOT.DEMO.'.htaccess';
	@unlink($htaccess_file);
	$htaccess_template = ROOT.DEMO.'~code/templates/htaccess.txt';
	$ht_content = file_get_contents($htaccess_template);
	$new_content = str_replace('[%rep%]', DEMO, $ht_content);
	if( $fp = @fopen($htaccess_file, 'w') ){
		fwrite($fp, $new_content);
		fclose($fp);
		echo $success_message;
	}else{
		$tmp_ht_file = ROOT.DEMO.'~content/.htaccess';
		if( $fp = @fopen($tmp_ht_file, 'w') ){
			fwrite($fp, $new_content);
			fclose($fp);
		}else{
			echo '<div>ERROR. Could not create '.$tmp_ht_file.'</div>';
		}

		echo '<div class="cont">
		<h3>Good, we\'re almost set!</h3>
		1. You need to access your site with an FTP program, and<br>
		<b>delete this file:</b>
		<p style="color:red;">'.SITE.DEMO.'.htaccess</p>
		2. Then <b>locate this file</b>:
		<p style="color:red;">'.SITE.str_replace(ROOT, '', $tmp_ht_file).'</p>
		<b>and move it to the location of the file you just deleted</b>:
		<p style="color:red;">'.SITE.DEMO.'.htaccess</p>
		Basically, grab the file and drag it into its parent folder, <br>out of the folder "~content" and into the folder "'.basename(DEMO).'".
		<p style="font-weight:bold;">3. When that\'s done, reload this page, and you should be good to go!</p>
		</div>';
	}
}else{
	echo $success_message;
}
?>

</body>
</html>
