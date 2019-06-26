<?php
// upload file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

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

$supported_types = str_replace(array("/^\.(", ")$/i", 's?', 'e?', 'a?', '?'), '', $_POST['types']['supported_types']);
$file_types = str_replace('|', ', ', $supported_types);


?>
<div class="modal" id="newFileContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	
	<!-- upload file start -->
	<div>
	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/_code/admin/upload_file.php" method="post">
	<a class="button submit left" id="chooseFileLink"><?php echo $ui['uploadFile']; ?></a><!-- <span class="hideUp lowkey">(<?php echo $ui['uploadMaxSize']; ?> <?php echo MAX_UPLOAD_SIZE; ?>)</span> -->
	<div class="progress">
    	<div class="bar"></div>
	</div>
		<input type="file" name="file" id="fileUpload" style="opacity:0;">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="context" value="newFile">
		<button type="submit" name="uploadFileSubmit" id="uploadFileSubmit" class="right"  style="opacity:0;">Upload</button>
	</form>
	<?php 
// only show the create file option if the modal is not opened from the replace button
if(empty($replace)){
?>
	<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['uploadFileDescription']; ?>
	</p>
<?php } ?>
	</div>
	<!-- upload file end -->

	<!--

	<h3 style="text-align:center; margin:20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>

	-->
	<!-- select file from _uploads start -->

	<!--
	<a class="button submit left showModal" rel="chooseFromUploads?path=<?php echo $path; ?>"><?php echo $ui['fileFromUploads']; ?></a>
	<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['fileFromUpDescription']; ?>
	</p>
-->

	<!-- select file from _uploads end -->
	
<?php 
// only show the create file option if the modal is not opened from the replace button
if(empty($replace)){
?>
	
	<h3 style="text-align:center; margin:20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>
	
	<!-- create file start -->
	<div id="createFileDiv" class="hideUp">
	<form name="createTextForm" action="/_code/admin/edit_text.php" method="post">
	<button type="submit" name="createText" class="left"><?php echo $ui['createFile']; ?></button>
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="replace" value="<?php echo $replace; ?>">
		<!--File name:
		<input type="text" name="fileName" value="" style="width:55%; padding:5px 0;" placeholder="&nbsp;(optional)" maxlength="50">
		<button type="submit" name="createText">Create</button> -->
	</form>
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
	
	<!-- tips start -->
	<!--
	<div style="border-top:1px solid #ccc; margin-top:20px;" class="hideUp">
	<p>Tips:</p>
		<div class="tip">
			<a href="javascript:;" class="tipTitle">Supported File Types for upload</a>
			<ol><?php echo $file_types; ?>.<br>
			<i>Note: pdf, docx, msword and odt files won't be displayed in the pages but will be accessible via a download link.</i></ol>
		</div>
		<div class="tip">
			<a href="javascript:;" class="tipTitle">How to best optimize Images for the web, using Photoshop</a>
			<?php include(ROOT.'_code/inc/optimize.php'); ?>
		</div>
		
	</div>
	-->
	<!-- tips end -->
	<!--
	<a class="button hideModal left hideUp"><?php echo $ui['cancel']; ?></a>
	-->
	
</div>
