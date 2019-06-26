<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

ini_set('upload_max_filesize', '30M');
ini_set('post_max_size', '31M');

$message = '';

// upload result (from admin/upload_file.php) AND embed result (from admin/embed_media.php)
if(isset($_GET['upload_result'])){
	$message = urldecode($_GET['upload_result']);
	// disable XXS protection so that iframes in embeded media that was just edited do load
	header("X-XSS-Protection: 0");
}

// DELETE section
if(isset($_POST['deleteSectionSubmit'])){
	if(!empty($_POST['parents'])){
		$parents = urldecode($_POST['parents']);
	}else{
		$parents = '';
	}
	if(!empty($_POST['deleteSection'])){
		$deleteSection = urldecode($_POST['deleteSection']);
		$message = delete_section($parents, $deleteSection);
	}
}


// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}

// item is the section content that should be shown in this page...
if(isset($_GET['item'])){
	$item = trim(urldecode($_GET['item']));
	if(empty($item)){
		header("location: manage_structure.php");
		exit;
	}
	$_SESSION['item'] = $item;
	
}elseif(isset($_SESSION['item'])){
	$item = $_SESSION['item'];
}

// if still no item, go back to admin manage_structure page
if(!isset($item)){
	header("location: manage_structure.php");
	exit;	
// security check, or if user deleted a section that is still in memory session
}elseif( !is_dir(ROOT.CONTENT.$item) ){
	if( isset($_SESSION['item']) ){
		unset($_SESSION['item']);
	}
	header("location: manage_structure.php");
	exit;
}

// echo $item; -> 'section1/section2'


$title = 'ADMIN : '.$ui['titleContent'].' :';
$description = filename($item, 'decode');

$crumble = '<a href="/_code/admin/manage_structure.php">'.$ui['titleContent'].'</a>';
$c_link = '?item=';
if( strstr($description, '/') ){
	$path_explode = explode('/', $description);
	foreach($path_explode as $p){
		$c_link .= filename($p, 'encode').'%2F';
		$crumble .= ' <span style="font-weight:normal;">></span> <a href="'.substr($c_link,0,-3).'">'.$p.'</a>';
	}
}else{
	$c_link = '?item='.$item;
	$crumble .= ' <span style="font-weight:normal;">></span> <a href="'.$c_link.'">'.$description.'</a>';
}

// set back_link:
// get referer without query string
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER']);
	//echo $referer.'<br>';
	$back_link = str_replace(PROTOCOL.SITE.'_code/admin/', '', $referer);
	//$crumble .= '<a href="/admin/manage_structure.php">Site Structure</a>';
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

require(ROOT.'_code/inc/doctype.php');
?>

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/admin-max-720px.css">

<div id="working">working...</div>

<div class="adminHeader">
<div id="admin">
	<a href="?logout" class="button discret remove right">logout</a>
	<a href="mailto:<?php echo AUTHOR_REF; ?>?subject=Request from <?php echo substr(SITE,0,-1); ?>" title="<?php echo $ui['helpTitle']; ?>" class="button discret help right"><?php echo $ui['help']; ?></a>
	<a href="preferences.php" title="<?php echo $ui['prefTitle']; ?>" class="button discret fav right"><?php echo $ui['preferences']; ?></a>
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

<a href="javascript:;" class="button add showModal left big" rel="newFile?path=<?php echo urlencode($item); ?>" title="<?php echo $ui['newItTitle']; ?>"><?php echo $ui['newItem']; ?></a>

</div>

<!-- start container -->
<div id="adminContainer">
	
	<div id="contentContainer">
		<div id="ajaxTarget">
	<?php 
	$display = display_content_admin($item);
	echo $display;
	?>
		</div>
	</div>


<div class="clearBoth"></div>
</div><!-- end container -->




<?php require(ROOT.'_code/inc/adminFooter.php'); ?>
