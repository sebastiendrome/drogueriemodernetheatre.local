<?php
// delete section modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['parents']) && !empty($_GET['parents']) ){
	$parents = urldecode($_GET['parents']);
}else{
	$parents = '';
}
if(isset($_GET['deleteSection']) && !empty($_GET['deleteSection']) ){
	$deleteSection = urldecode($_GET['deleteSection']);
	if(BILINGUAL== 'yes'){
		$show_deleteSection = $deleteSection;
	}else{
		$show_deleteSection = preg_replace('/ ?,.*/', '', $deleteSection);
	}
}else{
	echo '<p class="error">ERROR: missing section data</p>';
	exit;
}
?>
<div class="modal" id="deleteSectionContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3 class="first"><?php echo $ui['sectionDelConfirm']; ?></h3>
	<p>
	<input type="text" style="border-left:7px solid #000; width:96%;" value="<?php echo $show_deleteSection; ?>">
	</p>
	<form name="deleteSectionForm" action="" method="post">
		<input type="hidden" name="parents" value="<?php echo $parents; ?>">
		<input type="hidden" name="deleteSection" value="<?php echo $deleteSection; ?>">
		<p class="warning note"><?php echo $ui['sectionDelWarning']; ?></p>
		<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" class="deleteSection cancel right" name="deleteSectionSubmit"><?php echo $ui['delete']; ?></button>
	</form>
</div>
