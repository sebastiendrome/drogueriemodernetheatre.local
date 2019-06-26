<?php
// upload file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// path is the gallery file, i.e. [section]/_XL/gal-12345.gal
if( isset($_GET['path']) ){
	$path = urldecode($_GET['path']);
	if( is_dir(ROOT.CONTENT.$path) ){
		$rand = rand_string();
		$path .= '/_XL/'.$rand.'.gal';
	}
	
}else{
	exit;
}

?>
<div class="modal" id="galleryContainer">

<a href="javascript:;" class="closeBut">&times;</a>

	<!-- upload file start -->
	<div>

	<div id="uploadFileDiv">

	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="context" value="gallery">
		<a class="button submit left" id="chooseFileLink"><?php echo $ui['uploadImage']; ?></a>
		<input type="file" name="file" id="fileUpload" style="opacity:0;">
		<button type="submit" class="right" id="uploadFileSubmit" name="uploadFileSubmit" style="opacity:0;">Upload</button>
		<div class="progress">
    		<div class="bar"></div>
		</div>
	</form>
	<p class="above clearBoth hideUp lowkey" style="margin-top:0;"><?php echo $ui['uploadImDescription']; ?> 
	<?php echo $ui['uploadSupportedTypes']; ?>: jpg, gif, png. <!--<?php echo $ui['uploadMaxSize']; ?> <?php echo MAX_UPLOAD_SIZE; ?>--></p>

	</div>
	<!-- upload file end -->

	<div id="adminGalContainer">
	<?php
	$gallery = display_gallery_admin($path);
	echo $gallery;
	?>
	</div>
	
</div>

<script type="text/javascript">
// this is necessary or else, old modal container contains also an #uploadFile and takes precedence!!! 
$('body div.modal#newFileContainer').remove();
</script>

