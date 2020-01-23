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

// unset tlang session
if( isset($_SESSION['tlang']) ){
	unset($_SESSION['tlang']);
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

// publish
/*
if( isset($_GET['publish']) ){
	// get parent_dir and old_name from $item
	$oldName = basename($item);
	if($oldName !== $item){ // parent exists
		$parents = str_replace('/'.$oldName, '', $item);
		$dir_sep = '/';
	}else{
		$parents = $dir_sep = '';
	}
	$newName = preg_replace('/^_/', '', $oldName);

	// debug
	echo 'oldName '.$oldName.'<br>';
	echo 'newName '.$newName.'<br>';
	echo 'parents '.$parents.'<br>';
	exit;
	
	$result = update_section_name($oldName, $newName, $parents, 'manage_contents');
	if( !strstr($result, '<p class="error"') ){
		// reload the page with renamed item
		header( "Location: ?item=".urlencode($parents.$dir_sep.$new_name) );
	}
}
*/

// echo $item; -> 'section1/section2'


$title = 'ADMIN : '.$ui['titleContent'].' :';
$description = filename($item, 'decode');

$crumble = '';
$c_link = '?item=';
if( strstr($description, '/') ){
	$path_explode = explode('/', $description);
	$i=0;
	foreach($path_explode as $p){
		$i++;
		if( $i<count($path_explode) ){
			$c_link .= filename($p, 'encode').'%2F';
			$a_start = '<a href="'.substr($c_link,0,-3).'">';
			$a_end = '</a>';
		}else{
			$a_start = $a_end = '';
		}
		if( substr($p, 0, 1) == '_' ){
			$p = substr($p, 1);
		}
		$crumble .= '&nbsp;&nbsp;>&nbsp;&nbsp;'.$a_start.$p.$a_end;
	}
}else{
	if( substr($item, 0, 1) == '_' ){
		$description = substr($description, 1);
	}
	$crumble .= '&nbsp;&nbsp;>&nbsp;&nbsp;'.$description;
}

// set back_link:
// get referer without query string
if(isset($_SERVER['HTTP_REFERER'])){
	$referer = preg_replace('/\?.*/', '', $_SERVER['HTTP_REFERER']);
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

require(ROOT.DEMO.'~code/inc/doctype.php');
?>

<link href="/<?php echo DEMO; ?>~code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">

<div id="working">working...</div>

<div class="adminHeader">
	<div style="padding: 5px 15px 5px 15px;">

<h1><a href="/<?php echo DEMO; ?>admin/" style="text-decoration:underline;"><?php echo $ui['structure']; ?></a></h1>
<h2><?php echo $crumble; ?></h2>

<span class="tip" data-tip="<?php echo $ui['viewSite']; ?>"><a href="/<?php echo DEMO.$item; ?>/" class="openNew" target="_tab">
<svg viewBox="0 0 15 13">
<rect x="0.5" y="0.5" width="15" height="12"/>
<path d="M-2.4,11.1c0.1-0.6,0.5-4.6,7.1-4.4"/>
<line x1="5.7" y1="6.5" x2="2.8" y2="9.6"/>
<line x1="2.5" y1="4.2" x2="5.6" y2="7.1"/>
<line x1="1" y1="2.5" x2="14" y2="2.5"/>
</svg>
</a></span>

<h1 style="margin:0; display:inline-block; float:right;"><span class="tip" data-tip="<?php echo $ui['prefTitle']; ?>"><a href="preferences.php"><?php echo $ui['preferences']; ?></a></span></h1>

<a href="javascript:;" class="button add showModal submit big left" style="margin-left:20px;" rel="newFile?path=<?php echo urlencode($item); ?>"><span class="tip" data-tip="<?php echo $ui['newItTitle']; ?>"><?php echo $ui['newItem']; ?></span></a>

	</div>
</div>



<!-- start container -->
<div id="adminContainer">

	
<?php if( isset($message) ){
	echo $message;
}
?>

	<div id="contentContainer">
	<?php
	/*
	if(substr( basename($item), 0, 1) == '_'){
		echo '<a href="?publish" id="publish" data-item="'.urlencode($item).'">'.$ui['publish'].'</a>';
	}
	*/
	?>
	
		<div id="ajaxTarget">
	<?php 
	$display = display_content_admin($item);
	echo $display;
	?>
		</div>
	</div>


<div class="clearBoth"></div>
</div><!-- end container -->



<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>

<script type="text/javascript">
// added to highlight (like on :hover) the selected item to edit
var myhash = window.location.hash;
if(myhash.length){
	myhash = myhash.replace("#","");
	//alert(myhash);
	$anchor = $('a[name='+myhash+']');
	//alert($anchor);
	$parentLi = $anchor.parent('li');
	//alert($parentLi.data('name'));
	$parentLi.addClass('selected');
	$parentLi.on('mouseout, mouseenter', function(){
		$(this).removeClass('selected');
	});
	$('ul.content').on('mouseenter', 'li', function(){
		//alert('hover!');
		$parentLi.removeClass('selected');
	});
	// trigger click on gallery if we're coming back from chooseFromUploads
	<?php if( isset($_GET['gal_added']) ){ ?>
	$parentLi.find('div.gal').trigger('click');
	/*checkModalHeight('#gallery');*/
	<?php } ?>
}
</script>