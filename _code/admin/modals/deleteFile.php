<?php
// delete file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['file']) && !empty($_GET['file']) ){
	$file = urldecode($_GET['file']);
	$ext = file_extension($file);
	// get file_name and path ready for function display_file_admin
	$file_name = basename($file);
	$path = preg_replace('/\/(_XL|_S|_M|_L)\/'.preg_quote($file_name).'$/', '', $file);
	$display_file = display_file_admin($path, $file_name);
	
}else{
	exit;
}

?>
<div class="modal" id="deleteFileContainer">
	<a href="javascript:;" class="closeBut closeModal">&times;</a>
	<h3 class="first"><?php echo $ui['fileDelConfirm']; ?></h3>
	<?php echo $display_file; ?>
	<p><?php echo filename($path, 'decode').'/'.filename($file_name, 'decode'); ?></p>
	<form name="deleteFileForm" action="/_code/admin/delete_file.php" method="post">
		<input type="hidden" name="deleteFile" value="<?php echo urlencode($file); ?>">
	<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" name="deleteFileSubmit" class="cancel right"><?php echo $ui['delete']; ?></button>
</form>	
</div>
