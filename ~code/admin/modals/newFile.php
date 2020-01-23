<?php
// upload file modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

if(isset($_GET['path']) && !empty($_GET['path']) ){
	$path = urldecode($_GET['path']);
}else{
	exit;
}

// uploaded file should replace a previous one?
if(isset($_GET['replace']) && !empty($_GET['replace'])){
	$replace = urldecode($_GET['replace']);
	$replace_filename = basename($replace);
}else{
	$replace = $replace_filename = '';
}

// context
if( isset($_GET['context']) && !empty($_GET['context']) ){
	$context = $_GET['context'];
}else{
	$context = 'newFile';
}

if($context == 'home_bg_img' || $context == 'edit_text' || $context == 'gallery'){
	$allowed_types = 'resizable_types';
	$up_file = $ui['uploadImage'];
	$up_file_desc = $ui['uploadImDescription'].' '.$ui['uploadSupportedTypes'].': jpg, png, gif.';
}elseif($context == 'newFile'){
	$allowed_types = 'supported_types';
	$up_file = $ui['uploadFile'];
	$up_file_desc = $ui['uploadFileDescription'];
}






?>
<div class="modal uniBg" id="newFileContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	
	<!-- upload file start -->
	<div>
	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/<?php echo DEMO; ?>~code/admin/upload_file.php" method="post">
	<a class="button submit left upload" id="chooseFileLink"><?php echo $up_file; ?></a>
	<div class="progress">
    	<div class="bar"></div>
	</div>
		<input type="file" name="file" id="fileUpload" style="opacity:0;">
		<!--<input type="file" name="file" id="fileUpload" style="opacity:0;" multiple>-->
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="allowed_types" value="<?php echo $allowed_types; ?>">
		<input type="hidden" name="context" value="<?php echo $context; ?>">
		<button type="submit" name="uploadFileSubmit" id="uploadFileSubmit" style="opacity:0; height:1px; padding:0; margin:0; position:absolute; top:0; left:0;">Upload</button>
	</form>

	<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $up_file_desc; ?>
	</p>

	<?php
	// show chooseFromUploads options if some files have been uploaded
	if( $scan = scan_dir(ROOT.CONTENT.UPLOADS.'_XL', $allowed_types ) ){
		echo '<a href="/'.DEMO.'~code/admin/modals/chooseFromUploads.php?path='.urlencode($path).'&replace='.urlencode($replace).'&context='.$context.'" class="button submit left hideUp">'.$ui['chooseFromUploads'].'</a>';
	}
	?>

	</div>
	<!-- upload file end -->



<?php 
// only show the create file option if the modal is not opened from the replace button
if( empty($replace) ){
?>
	
	<h3 style="text-align:center; margin:20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>
	
	<!-- create file start -->
	<div id="createFileDiv" class="hideUp">
	<!--<form name="createTextForm" action="/<?php echo DEMO; ?>~code/admin/edit_text.php" method="post">
	<button type="submit" name="createText" class="left"><?php echo $ui['createFile']; ?></button>
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
	</form>-->
	<a href="/<?php echo DEMO; ?>~code/admin/edit_text.php?createText&path=<?php echo urlencode($path); ?>&replace=<?php echo urlencode($replace); ?>" class="button submit"><?php echo $ui['createFile']; ?></a>
	<p class="above clearBoth" style="margin-top:0;"><?php echo $ui['createFileDescription']; ?></p>
	</div>
	<!-- create file end -->


	<!-- create gallery start -->
	<h3 style="text-align:center; margin:20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>

	<a href="javascript:;" class="button showModal submit hideUp left" rel="gallery?path=<?php echo $path; ?>&replace=<?php echo $replace; ?>" onclick="$('div.modalContainer, div.overlay').hide();"><?php echo $ui['newGallery']; ?></a>
	<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['newGalDescription']; ?></p>
	<!-- create gallery end -->


	<!-- insert media start -->
	<h3 style="text-align:center; margin:20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>

	<a href="javascript:;" class="button showModal submit hideUp left" rel="embedMedia?path=<?php echo $path; ?>&replace=<?php echo $replace; ?>" onclick="$('div.modalContainer, div.overlay').hide();"><?php echo $ui['embedMedia']; ?></a>
	<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['embedMediaDescription']; ?></p>
	<!-- insert media end -->

<?php
}
?>
	
</div>

