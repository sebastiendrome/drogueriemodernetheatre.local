<?php
// upload file modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// $path is path to the parent section
if( isset($_GET['path']) ){
	$path = urldecode($_GET['path']);
	if( is_dir(ROOT.CONTENT.$path) ){ // file does not exist yet, we'll need to create it!
		$rand = rand_string();
		$gallery_file = $rand.'.gal';
	}else{
		$gallery_file = basename($path);
		$path = preg_replace('/\/?'.preg_quote($gallery_file).'^/', '', $path);
	}
	
}else{
	exit;
}
if( isset($_GET['context']) ){
	$context = $_GET['context'];
}else{
	$context = 'gallery';
}
if($context == 'gallery' || $context == 'edit_text' || $context == 'home_bg_img'){
	$allowed_types = 'resizable_types';
}
?>
<div class="modal uniBg" id="galleryContainer">

<a href="javascript:;" class="closeBut">&times;</a>

	<!-- upload file start -->
	<div>
	<?php
	if( file_extension($path) !== '.gal' ){ ?>
		<h3 class="first"><?php echo $ui['newGallery']; ?></h3>
	<?php } ?>
	<div id="uploadFileDiv">

	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="gallery_file" value="<?php echo $gallery_file; ?>">
		<input type="hidden" name="replace" value="">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="allowed_types" value="<?php echo $allowed_types; ?>">
		<input type="hidden" name="context" value="<?php echo $context; ?>">
		
		<a class="button submit left upload" id="chooseFileLink"><?php echo $ui['uploadImage']; ?></a><input type="file" name="file" id="fileUpload" style="opacity:0;">
		<p class="above clearBoth hideUp lowkey" style="margin-top:0;"><?php echo $ui['uploadImDescription']; ?>
		<?php echo $ui['uploadSupportedTypes']; ?>: jpg, gif, png. <!--<?php echo $ui['uploadMaxSize']; ?> <?php echo MAX_UPLOAD_SIZE; ?>--></p>
		<div class="progress">
    		<div class="bar"></div>
		</div>
		
		<button type="submit" class="right" id="uploadFileSubmit" name="uploadFileSubmit" style="opacity:0;">Upload</button>
	
	</form>
	

	<?php
	// if gallery has to be created, generate its name
	if( file_extension($path) !== '.gal' ){
		$path .= '/'.$gallery_file;
	}
	?>

	<?php
	if($scan = scan_dir(ROOT.CONTENT.UPLOADS.'_XL', 'resizable_types') ){
		echo '<a href="/'.DEMO.'~code/admin/modals/chooseFromUploads.php?path='.urlencode($path).'&replace=&context='.$context.'" class="button submit left" style="margin-top:20px;">'.$ui['chooseFromUploads'].'</a>';
	}
	?>

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

