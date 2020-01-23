<?php
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

ini_set('upload_max_filesize', '30M');
ini_set('post_max_size', '31M');

$message = '';

// upload result (from admin/upload_file.php) AND embed result (from admin/embed_media.php)
if(isset($_GET['upload_result'])){
	$message = urldecode($_GET['upload_result']);
	// disable XXS protection so that iframes in embeded media that was just edited do load
	header("X-XSS-Protection: 0");
}


// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}

// item is the section content that should be shown in this page...
if(isset($_SESSION['item'])){
	unset($_SESSION['item']);
}

// echo $item; -> 'section1/section2'


$title = 'ADMIN : '.$ui['myUploads'].' :';
$description = $ui['myUploads'];
$page = $ui['myUploads'];

$crumble = '<a href="/'.DEMO.'~code/admin/manage_structure.php">Admin</a> : '.$page;

// set back_link:
// get referer without query string
/*
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = preg_replace('/\?.*/  /*', '', $_SERVER['HTTP_REFERER']);
	//echo $referer.'<br>';
	$back_link = str_replace(PROTOCOL.SITE.'~code/admin/', '', $referer);
	//$crumble .= '<a href="/'.DEMO.'admin/manage_structure.php">Site Structure</a>';
	//echo $back_link;
	if($back_link == 'manage_contents.php'){
		if(strstr($item, '/')){
			$back_link .= '?item='.urlencode( str_replace('/'.basename($item), '', $item) );
		}else{
			$back_link = 'manage_structure.php';
		}
	}elseif($back_link == 'edit_text.php'){
		$back_link = 'manage_structure.php';
	}
}else{
	$back_link = 'manage_structure.php';
}
*/

require(ROOT.DEMO.'~code/inc/doctype.php');
?>

<link href="/<?php echo DEMO; ?>~code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">

<div id="working">working...</div>

<div class="adminHeader">
<div id="admin">
	<!--<a href="?logout" class="button discret remove right"><?php echo $ui['logout']; ?></a>
	<a href="mailto:<?php echo AUTHOR_REF; ?>?subject=Request from <?php echo substr(SITE,0,-1); ?>" title="<?php echo $ui['helpTitle']; ?>" class="button discret help right"><?php echo $ui['help']; ?></a>-->
	<a href="preferences.php" title="<?php echo $ui['prefTitle']; ?>" class="button discret fav right"><?php echo $ui['preferences']; ?></a>
	<a href="my_uploads.php" title="" class="button discret fichiers right selected"><?php echo $ui['myUploads']; ?></a>
	<a href="/<?php echo DEMO; ?>admin/" title="" class="button discret structure right"><img src="/<?php echo DEMO; ?>~code/images/mobile-menu.svg" style="width:9px; margin-right:5px;"><?php echo $ui['siteStructure']; ?></a>
</div>
	<div style="padding:0 20px;">
<h2><?php echo $crumble; ?></h2>
	</div>
</div>

<div style="padding:10px 20px; padding-bottom:0;">
<?php if( isset($message) ){
	echo $message;
}
?>



</div>

<!-- start container -->
<div id="adminContainer">
	
	<div id="uploadsContainer">
		<div id="ajaxTarget">
			<div class="tools" style="margin-left:5px;"><a href="javascript:;"><img src="/<?php echo DEMO; ?>~code/admin/images/thumbs-size.gif"></a></div>
	<?php 
	$files = scan_dir(ROOT.CONTENT.UPLOADS.'_XL');
	$uploads = display_user_uploads($files);
	echo $uploads;
	?>
		</div>
	
		<div class="clearBoth"></div>
	</div>


</div><!-- end container -->




<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>
