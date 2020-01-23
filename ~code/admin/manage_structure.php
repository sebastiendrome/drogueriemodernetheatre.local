<?php
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

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

require(ROOT.DEMO.'~code/inc/doctype.php');
?>

<link href="/<?php echo DEMO; ?>~code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">

<div id="working">working...</div>

<div class="adminHeader">
<div style="padding: 5px 15px 5px 15px;">

<h1 style="text-decoration:underline;"><?php echo $ui['structure']; ?></h1>

<h1 style="margin:0; display:inline-block; float:right;"><span class="tip" data-tip="<?php echo $ui['prefTitle']; ?>"><a href="preferences.php"><?php echo $ui['preferences']; ?></a></span></h1>


<a href="javascript:;" style="margin-left:20px;" class="button add showModal submit big left" rel="createSection"><span class="tip" data-tip="<?php echo $ui['newSecTitle']; ?>"><?php echo $ui['newSection']; ?></span></a>

</div>
</div>

<!-- start container -->
<div id="adminContainer">

<?php 
if( isset($message) ){
	echo $message;
}
?>
	
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




<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>
