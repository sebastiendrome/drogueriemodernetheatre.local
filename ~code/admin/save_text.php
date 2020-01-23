<?php
// Save article (calls function: save_text_editor())
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// save article content
if( isset($_POST['saveTextEditor']) ){
	if( isset($_POST['item']) && !empty($_POST['item']) ){
		// full path to file, or to section (if file still needs to be created)
		$file = urldecode($_POST['item']); // -> "section/file_name.html" OR "section/"
	}
	// article (editor) content
	if( isset($_POST['content']) && !empty($_POST['content']) ){
		$content = urldecode($_POST['content']);
		// attempt to match comment styles in content
		if( preg_match('/<!-- qQqStyleqQq-.*? -->/', $content, $matches) ){
			$content = str_replace($matches[0], '', $content);
		}
	}
	// articles styles set in html comment, passed via input commentStyles
	if( isset($_POST['commentStyles']) ){
		$comment_styles = urldecode($_POST['commentStyles']);
	}else{
		$comment_styles = '';
	}
	// required data is set, save article content (create the file if necessary)
	if( isset($file) && isset($content) ){
		$message = save_text_editor($file, $comment_styles.$content);
		$_SESSION['editItem'] = $file;
	}else{
		$message = '0|Missing data<br>You cannot save an empty file. To delete this file, go back and delete it...';
	}
	header("location: edit_text.php?message=".urlencode($message));
	exit;
}
