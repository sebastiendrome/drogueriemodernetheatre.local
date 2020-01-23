<?php
// embed media form POST process (from embedMedia.php, modal window)
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// upload file form process
if(isset($_POST['embedMediaSubmit'])){
    $path = urldecode($_POST['path']);
    $embed_media = urldecode($_POST['embedMedia']);

    // validate embed_media
    if(!strstr($embed_media, '<iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms"')){
        $embed_media = preg_replace('/<iframe /', '<iframe sandbox="allow-same-origin allow-scripts allow-popups allow-forms" ', $embed_media);
    }
	
	$upload_result = embed_media($path, $embed_media);
	header("location: manage_contents.php?upload_result=".urlencode($upload_result));
	exit;
}
