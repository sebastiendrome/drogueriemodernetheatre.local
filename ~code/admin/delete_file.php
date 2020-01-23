<?php
// delete file form POST process (from deleteFile.php, modal window)
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

$referer = preg_replace('/\?.*$/', '', $_SERVER['HTTP_REFERER'] );
$page = basename($referer);
// only authorize coming from these 2 admin pages
if($page !== 'manage_contents.php' && $page !== 'manage_structure.php'){
	echo $page.' not authorized';
	exit;
}

// DELETE FILE form process
if(isset($_POST['deleteFile']) && !empty($_POST['deleteFile'])){
	if(isset($_POST['parentsPath'])){
		$parentsPath = urldecode($_POST['parentsPath']);
	}
	$message = delete_file( urldecode($_POST['deleteFile']), $parentsPath );
	header('location: '.$referer.'?message='.urlencode($message));
	exit;
}
