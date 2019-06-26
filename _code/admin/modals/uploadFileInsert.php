<?php
// upload file modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');


// for creating sub-sections, we need the parent section:
if( isset($_GET['path']) ){
	$path = urldecode($_GET['path']);
}else{
	exit;
}

?>
<style>

</style>

<div class="modal" id="uploadFileInsertContainer">

    <a href="javascript:;" class="closeBut">&times;</a>

	<!-- upload file start -->
	<div>
    
	<p id="f1_upload_process"></p>
    <p id="result"></p>

	<div id="uploadFileDiv">

	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/_code/admin/upload_file_to_insert.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="context" value="fileInsert">
		<a class="button submit left" id="chooseFileLink"><?php echo $ui['uploadImage']; ?></a> <span class="lowkey"><?php echo $ui['uploadSupportedTypes']; ?>: jpg, gif, png. <!-- <?php echo $ui['uploadMaxSize']; ?> <?php echo MAX_UPLOAD_SIZE; ?>--></span>
		<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['uploadImDescription']; ?></p>
		<input type="file" name="file" id="fileUpload" style="opacity:0;">
		<button type="submit" class="right" id="uploadFileSubmit" name="uploadFileSubmit" style="opacity:0;">Upload</button>
		<div class="progress">
    		<div class="bar"></div>
		</div>
	</form>

	</div>

	
	<h3 style="text-align:center; margin:0 0 20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>

	
	<div id="imgUrlDiv" class="hideUp">
        <h3><?php echo $ui['insertURLTitle']; ?> <span class="question" title="<?php echo $ui['insertURLInstructions']; ?>">?</span></h3>
		<input id="img_url" value="" placeholder="http://" style="width:calc(100% - 120px);">
		<a class="button submit hideModal insertImage right"><?php echo $ui['insertURL']; ?></a>
		<p class="above" style="margin-top:0;"><?php echo $ui['insertURLDescription']; ?></p>
	</div>
	<p class="clearBoth hideUp">&nbsp;</p>

	</div>
	<!-- upload file end -->


	<!-- tips start -->
	<!--
	<div style="margin-top:20px; border-top:1px solid #ccc;" class="hideUp">
	<p>Tips:</p>
		<div class="tip">
			<a href="javascript:;" class="tipTitle">How to best optimize Images for the web, using Photoshop</a>
			<ol>
			<li>Open your file in Photoshop</li>
			<li>Under menu, select: Image > Image Size...</li>
			<li>Adjust the Width and Height, in pixels, so that neither exceeds 3000px</li>
			<li>Check "Constrain Proportions" and "Resample Image"</li>
			<li>Click OK.</li>
			<li>Under menu, select: File > Save for Web & Devices...</li>
			<li>Select JPEG format, Very High quality, check Optimized</li>
			<li>Click Save.</li>
			</ol>
		</div>

		<div class="tip">
			<a href="javascript:;" class="tipTitle">How to get the URL of an image from another web site.</a>
			<ol>
			<li>Browse to the image in the other web site.</li>
			<li>Control-click on the image (press the "ctrl" key and click at the same time).</li>
			<li>You'll see a list of options, click on the one that says "copy image address", or "copy image location" or something equivalent (the wording differs depending on the navigator).</li>
			<li>Come back here and paste it!</li>
			</ol>
		</div>
		
	</div>
	-->
	<!-- tips end -->
	<!--
	<a class="button hideModal left hideUp">Cancel</a>
	-->

</div>


<script type="text/javascript">
// validate url before inserting... (function validateUrl is declared in edit_text.php)
$('a.insertImage').on('click', function(e){
	var img_url = $('input#img_url').val();
	if(img_url.length){
		var ok = validateUrl('img_url', event);
		if( ok != false ){
			insertImg(img_url);
		}else{
			$('input#img_url').focus();
			return false;
			e.preventDefault();
		}
	}else{
		alert('url is empty...');
		return false;
		e.preventDefault();
    }
});

// make sure only one of the input options (url and upload) has a value, disable the other on change
$('input#img_url').on('change, keypress, keyup', function(e){
	var thisVal = $(this).val();
	if( thisVal.length ){
		enable_url();
	}else{
		disable_url();
	}
});

$('input#img_url').bind('paste', function(e) { // text pasted
	enable_url();
});


// initialize url option submit button, so it's disabled until something is entered
disable_url();

function disable_url(){
	$('a.button.insertImage').addClass('disabled');
	$('input#img_url').val('');
}
function enable_url(){
	$('a.button.insertImage').removeClass('disabled');
}

// insert image url in text editor (using their API command)
function insertImg(img_url){
	// check for '_XL' directory in image path, and replace it with _M dir, so that inserted images are not huge
	var match = img_url.match("/_XL/");
	if(match != null){
		img_url = img_url.replace("/_XL/", "/_M/");
	}
	// remove http(s):// from url if internal url
	var match2 = img_url.match(/^.*\/_content\/_uploads\//);
	if(match2 != null){
		img_url = img_url.replace(match2, "/_content/_uploads/");
	}
    editor.composer.commands.exec("insertImage", {src:img_url, alt:''});
}

</script>
