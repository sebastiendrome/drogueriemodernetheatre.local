<?php
// embed media modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// path where new .emb file should be created. If 'edit' was clicked, the path is the path/to/file.emb
if(isset($_GET['path']) && !empty($_GET['path']) ){
    $path = urldecode($_GET['path']);
}else{
	exit;
}

if(isset($_GET['replace']) && !empty($_GET['replace']) ){
    $replace = urldecode($_GET['replace']);
}else{
	$replace = '';
}
if(isset($_GET['context']) && !empty($_GET['context']) ){
	$context = urldecode($_GET['context']);
}else{
	$context = '';
}

// context dependent vars
if($context == 'edit_text' || $context == 'gallery' || $context == 'home_bg_img'){
	$filter_file_types = 'resizable_types';
	if($context == 'gallery'){
		// used to trigger click on gallery once back in manage_contents if the $context is gallery
		$gal_added = '?gal_added';
	}else{
		$gal_added = '';
	}
}else{
	$filter_file_types = 'supported_types';
	$gal_added = '';
}

if($context == 'edit_text'){
	$loaded_as_modal = true;
	$top_padding = ' style="padding-top:70px;"'; // body padding-top is set to zero in edit_text.php
}else{
	$loaded_as_modal = false;
	$top_padding = '';
}

// if this is a modal window
if($loaded_as_modal){
	$back_a = '<a href="javascript:;" class="button specialCancel hideModal" style="font-weight:normal;">'.$ui['cancel'].'</a>';
	$load_styles_and_working_div = '';
	$back_link = '';

// else, this a a full html document, include doctype and all (also, adminFooter see end of page)
}else{
	$description = $title = $ui['myUploads'];
	require(ROOT.DEMO.'~code/inc/doctype.php');
	// back link will be used to redirect to previous page
	$back_link = '/'.DEMO.'~code/admin/manage_contents.php'.$gal_added.'#'.preg_replace('/[^A-Za-z0-9]/', '', basename($path) );
	$back_a = '<a href="'.$back_link.'" class="button specialCancel goBack" style="font-weight:normal;">'.$ui['cancel'].'</a>';
	$load_styles_and_working_div = '<link href="/'.DEMO.'~code/css/admincss.css?v=2" rel="stylesheet" type="text/css">
	<!-- load responsive design style sheets -->
	<link rel="stylesheet" media="(max-width: 720px)" href="/'.DEMO.'~code/css/admin-max-720px.css">
	<div id="working">working...</div>';
}

$files = scan_dir(ROOT.CONTENT.UPLOADS.'_XL', $filter_file_types);

echo $load_styles_and_working_div;
?>

<div class="modal uniBg" id="chooseFromUploadsModal">

<div class="adminHeader">
	<div>
	<!-- upload file start -->
	<h3 class="below"><?php echo $ui['choose']; ?>&nbsp;&nbsp;&nbsp;&nbsp; <?php echo $back_a; ?> <a href="javascript:;" class="button submit save insertUploads disabled" style="font-weight:normal;"><?php echo $ui['insertSelected']; ?></a></h3>
	</div>
</div>

	<div style="height:100%;">
		<div<?php echo $top_padding; ?>>
		<p id="chooseFromUploadsResult" style="display:none;"></p>
			<div id="uploadsContainer" style="clear:both; overflow:auto; height:90%;">
				<input type="hidden" name="path" value="<?php echo $path; ?>">
				<input type="hidden" name="replace" value="<?php echo $replace; ?>">
					<?php
					echo display_user_uploads($files);
					?>
			</div>
		</div>
	</div>
	<!-- upload file end -->
</div>


<script type="text/javascript">

var filesToInsert = new Array;
var path = $('input[name="path"]').val();
var replace = $('input[name="replace"]').val();
var context = '<?php echo $context; ?>';
var redirect = '<?php echo $back_link; ?>';

if( context == 'home_bg_img' ){ // if context is home_bg_img, redirect to preferences form=siteDesign
	redirect = '/'+demo+'~code/admin/preferences.php?form=siteDesign';
}

$('a.goBack').on('click', function(e){
	e.preventDefault();
	window.location.href = redirect;
});


$('.fileContainer').on('click', 'a.pad', function(e){
	e.preventDefault();
	var url = encodeURIComponent( $(this).attr('href') );
	if($(this).hasClass('selected')){
		$(this).removeClass('selected');
		var index = filesToInsert.indexOf(url);
		if (index > -1) {
			filesToInsert.splice(index, 1);
		}
		if(filesToInsert.length == 0){
			$('a.button.insertUploads').addClass('disabled');
		}
		
	}else{
		// if context is home_bg_img, allow only one selection
		if( context == 'home_bg_img'){
			filesToInsert.length = 0
			filesToInsert = [url];
			$('.fileContainer a.pad').removeClass('selected');
		}else{
			filesToInsert.push(url);
		}
		$(this).addClass('selected');
		$('a.button.insertUploads').removeClass('disabled');
	}
	//alert('url= '+url+'\npath= '+path+'\nreplace= '+replace);
});


$('a.insertUploads').on('click', function(e){
	e.preventDefault();
	var len = filesToInsert.length;
	var end = '';
	for(var i = 0; i < len; i++){
		if( i == (len-1) ){
			end = 'end';
		}
		addFileFromUploads(filesToInsert[i], path, replace, end, context);
	}
	$('#chooseFromUploadsResult').show();
})
</script>

<?php 
// include admin footer if not loaded as modal
if(!$loaded_as_modal){
	require(ROOT.DEMO.'~code/inc/adminFooter.php');
}
?>
