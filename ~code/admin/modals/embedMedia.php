<?php
// embed media modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// path where new .emb file should be created. If 'edit' was clicked, the path is the path/to/file.emb
if(isset($_GET['path']) && !empty($_GET['path']) ){
    $path = urldecode($_GET['path']);
    $ext = file_extension($path);
    if($ext == '.emb'){
        $content = file_get_contents(ROOT.CONTENT.$path); // get file content if editing an already existing file.
    }
}else{
	exit;
}


?>
<div class="modal uniBg" id="embedMediaContainer">
<a class="closeBut">&times;</a>
	<!-- upload file start -->
	<div>
	<h3 class="first"><?php echo $ui['embedMedia']; ?></h3>
	<p><?php echo $ui['embedMediaInstructions']; ?></p>
    <!-- <p class="note warning">Only paste code from trusted sources: malicious code could break your site and/or make it dangerous to use !</p> -->
	<form name="embedMediaForm" id="embedMediaForm" action="/<?php echo DEMO; ?>~code/admin/embed_media.php" method="post">
		<input type="hidden" name="path" value="<?php echo $path; ?>">
		<textarea name="embedMedia" id="TeM" style="width:100%; height:200px;" placeholder="<?php echo $ui['embedMediaPlaceholder']; ?>"><?php if(isset($content)){echo $content;} ?></textarea>
		<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" name="embedMediaSubmit" class="save right" id="sEmB" disabled> <?php echo $ui['save']; ?> </button>
	</form>
	</div>
	<!-- upload file end -->


	

	
</div>

<script type="text/javascript">
$('textarea#TeM').on('input cut paste', function(){
	var len = $(this).val().length;
	if(len > 10){
		$('button#sEmB').prop('disabled', false);
	}else{
		$('button#sEmB').prop('disabled', true);
	}
});
</script>