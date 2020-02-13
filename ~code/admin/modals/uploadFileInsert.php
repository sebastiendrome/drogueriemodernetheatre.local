<?php
// upload file modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// for creating sub-sections, we need the parent section:
if( isset($_GET['path']) ){
	$path = urldecode($_GET['path']);
}else{
	exit;
}

if( isset($_GET['allowed_types']) ){
	$allowed_types = $_GET['allowed_types'];
}else{
	$allowed_types = 'resizable_types';
}
?>


<div class="modal uniBg" id="uploadFileInsertContainer">

	<a href="javascript:;" class="closeBut">&times;</a>
	

	<!-- upload file start -->
	<div>
    
	<p id="f1_upload_process"></p>
    <p id="result"></p>

	<div id="uploadFileDiv">

	<form enctype="multipart/form-data" name="uploadFileForm" id="uploadFileForm" action="/<?php echo DEMO; ?>~code/admin/upload_file_to_insert.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo MAX_UPLOAD_BYTES; ?>">
		<input type="hidden" name="allowed_types" value="<?php echo $allowed_types; ?>">
		<input type="hidden" name="context" value="edit_text">
		<a class="button submit left upload" id="chooseFileLink"><?php echo $ui['uploadImage']; ?></a> <span class="lowkey"><?php echo $ui['uploadSupportedTypes']; ?>: jpg, gif, png. <!-- <?php echo $ui['uploadMaxSize']; ?> <?php echo MAX_UPLOAD_SIZE; ?>--></span>
		<p class="above clearBoth hideUp" style="margin-top:0;"><?php echo $ui['uploadImDescription']; ?></p>
		<?php
		if($scan = scan_dir(ROOT.CONTENT.UPLOADS.'_XL', 'resizable_types') ){
			echo '<a href="javascript:;" rel="chooseFromUploads?path='.urlencode($path).'&replace=&context=edit_text" class="showModal button submit left hideUp">'.$ui['chooseFromUploads'].'</a>';
		}
		?>
		<input type="file" name="file" id="fileUpload" style="opacity:0;">
		<button type="submit" class="right" id="uploadFileSubmit" name="uploadFileSubmit" style="opacity:0;">Upload</button>
		<div class="progress">
    		<div class="bar"></div>
		</div>
		
	</form>

	</div>

	
	<h3 style="text-align:center; margin:0 0 20px 0;" class="hideUp"> —— <?php echo $ui['or']; ?> —— </h3>

	
	<div id="imgUrlDiv" class="hideUp">
        <span class="tip" data-tip="<?php echo $ui['insertURLInstructions']; ?>"><h3><?php echo $ui['insertURLTitle']; ?></h3></span>
		<input id="img_url" value="" placeholder="http://" style="width:calc(100% - 120px);">
		<a class="button submit insertImage right"><?php echo $ui['insertURL']; ?></a>
		<p class="above" style="margin-top:0;"><?php echo $ui['insertURLDescription']; ?></p>
	</div>
	<p class="clearBoth hideUp">&nbsp;</p>

	</div>
	<!-- upload file end -->


</div>


<script type="text/javascript">

var css = '<?php echo CSS; ?>';

// validate url before inserting... (function validateUrl is declared in edit_text.php)
$('a.insertImage').on('click', function(e){
	var img_url = $('input#img_url').val();
	if(img_url.length){
		var ok = validateUrl('img_url', event);
		if( ok != false ){
			insertImg(img_url);
			hideModal($('#uploadFileInsertContainer'));
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
	if( $(this).val().length ){
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
	warn();
	var internal = false;
	// remove http(s):// from url if internal url
	var replace = "^.*\/"+content.replace("/","\/")+"~uploads\/_(S|M|L|XL)\/";
	var re = new RegExp(replace);
	var match = img_url.match(re);
	if(match !== null){
		internal = true;
		s_img = img_url.replace(match[0], "/"+content+"~uploads/_S/");
		m_img = img_url.replace(match[0], "/"+content+"~uploads/_M/");
		l_img = img_url.replace(match[0], "/"+content+"~uploads/_L/");
		if(css == 'nav-top'){
			img_url = l_img;
		}else{
			img_url = m_img;
		}
		// 800, 650, 300 img sizes (width)
		// screen sizes: more than 1370 use L, less than 340 use S, else use M 
		//var imgSrc = img_url+'" srcset="'+l_img_url+' 800w, '+img_url+' 650w, '+s_img_url+' 300w" sizes="(max-width: 1370px) 650px, (max-width: 340px) 300px, 800px"';

		var set = l_img+' 800w, '+m_img+' 650w, '+s_img+' 300w';
		var siz = '(max-width: '+img_w_limit+'px) 650px, (max-width: 340px) 300px, 800px';
		var imAlt = '';

		editor.composer.commands.exec("insertImage", {srcset:set, src:img_url, sizes:siz, alt:imAlt});
	
		// if not, just insert the image url
	}else{
		editor.composer.commands.exec("insertImage", {src:img_url, alt:''});
	}
	//editor.composer.commands.exec("insertHTML", imgString);
}

</script>
