<?php
// delete file modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['file']) && !empty($_GET['file']) ){
	$file = urldecode($_GET['file']);
	$ext = file_extension($file);
	// get file_name and path ready for function display_file_admin
	$file_name = basename($file);
	$path = preg_replace('/\/(_XL|_S|_M|_L)\/'.preg_quote($file_name).'$/', '', $file);
	$display_file = display_file_admin($file_name);
	
}else{
	exit;
}

if( isset($_GET['parentsPath']) ){
	$parentsPath = $_GET['parentsPath'];
}else{
	$parentsPath = '';
}

?>
<div class="modal uniBg" id="deleteFileContainer">
	<a href="javascript:;" class="closeBut closeModal">&times;</a>
	<h3 class="first"><?php echo $ui['fileDelConfirm']; ?></h3>
	<div class="imgContainer">
	<?php echo $display_file; ?>
	</div>
	
	<form name="deleteFileForm" action="/<?php echo DEMO; ?>~code/admin/delete_file.php" method="post">
		<input type="hidden" name="deleteFile" value="<?php echo urlencode($file); ?>">
		<input type="hidden" name="parentsPath" value="<?php echo urlencode($parentsPath); ?>">
		<div style="clearBoth">&nbsp;</div>
	<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" name="deleteFileSubmit" class="cancel right"><?php echo $ui['delete']; ?></button>
	</form>
	
</div>
