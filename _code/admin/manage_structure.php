<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

$title = $ui['titleAdmin'];
$description = '';
$page = 'admin';

// create new sub-section
if(isset($_POST['createSectionSubmit'])){
	if(!empty($_POST['parents'])){
		$parents = urldecode($_POST['parents']);
	}else{
		$parents = '';
	}
	if(!empty($_POST['createSection'])){
		$createSection = urldecode($_POST['createSection']);
		$createSection = validate_section_name($createSection);
	}
		
	$message = create_section($parents, $createSection);
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


$menu_array = menu_file_to_array();
$site_structure = site_structure($menu_array);

require(ROOT.'_code/inc/doctype.php');
?>

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/admin-max-720px.css">

<div id="working">working...</div>

<div class="adminHeader">
<div id="admin">
	<a href="?logout" class="button remove discret right"><?php echo $ui['logout']; ?></a>
	<a href="mailto:<?php echo AUTHOR_REF; ?>?subject=Request from <?php echo substr(SITE,0,-1); ?>" title="<?php echo $ui['helpTitle']; ?>" class="button discret help right"><?php echo $ui['help']; ?></a>
	<a href="preferences.php" title="<?php echo $ui['prefTitle']; ?>" class="button discret fav right"><?php echo $ui['preferences']; ?></a>
</div>
	<div style="padding:0 20px;">
	<h2><?php echo $title; ?></h2>
	</div>
</div>

<div style="padding:10px 20px; padding-bottom:0;">
<?php 
if( isset($message) ){
	echo $message;
}
?>

<a href="javascript:;" class="button add showModal left big" rel="createSection" title="<?php echo $ui['newSecTitle']; ?>"><?php echo $ui['newSection']; ?></a>

</div>

<!-- start container -->
<div id="adminContainer">
	
	<div id="structureContainer">
		<div id="ajaxTarget">
		<?php if(isset($result) && !empty($result)){
			echo $result;
		}
		?>
	<?php 
	//print_r($menu_array);
	echo $site_structure; 
	?>
		</div>
	</div>


<div class="clearBoth"></div>
</div><!-- end container -->




<?php require(ROOT.'_code/inc/adminFooter.php'); ?>
