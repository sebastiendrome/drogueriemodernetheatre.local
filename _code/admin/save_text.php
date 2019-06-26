<?php
// upload file form POST process (from uploadFile.php, modal window)
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// save text description (of files in manage content)
if(isset($_POST['saveTextEditor'])){
	if( isset($_POST['item']) && !empty($_POST['item']) ){
		$file = urldecode($_POST['item']); // full path to file
	}
	if( isset($_POST['content']) && !empty($_POST['content']) ){
		$content = urldecode($_POST['content']);
	}
	if( isset($file) && isset($content) ){
		$message = save_text_editor($file, $content);
		$_SESSION['editItem'] = $file;
	}else{
		$message = '0|Missing data<br>You cannot save an empty file. To delete this file, go back and delete it...';
	}
	header("location: edit_text.php?message=".urlencode($message));
	exit;
}
