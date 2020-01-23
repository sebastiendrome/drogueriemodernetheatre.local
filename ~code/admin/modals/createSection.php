<?php
// create section modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

// for creating sub-sections, we need the parent section:
if(isset($_GET['parents']) && !empty($_GET['parents']) ){
	$parents = urldecode($_GET['parents']);
	if(FIRST_LANG == 'français'){
		$sub = 'sous-';
	}else{
		$sub = 'sub-';
	}
}else{
	$parents = $sub = '';
}
?>
<div class="modal uniBg" id="createSectionContainer">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3 class="first below"><?php echo str_replace('[%rep%]', $sub, $ui['sectionName']); ?>:</h3>
	<form name="createSectionForm" action="" method="post" onsubmit="if($(this).find('input#createSection').val()==''){return false;}">
		<span class="l2"><?php echo $ui['sectionNameDescription']; ?></span>
		<input type="hidden" name="parents" value="<?php echo $parents; ?>">
		<input type="text" name="createSection" id="createSection" maxlength="100" value="" style="width:97%; border-left:7px solid #000; margin-bottom:15px;" placeholder="<?php if(BILINGUAL == 'yes'){echo FIRST_LANG.', '.SECOND_LANG;} ?>">
		<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" name="createSectionSubmit" class="right"><?php echo $ui['create']; ?></button>
	</form>
</div>
<!--
	removed because it interferes with checkmodalheight() (focus makes page natively scroll to modal...)
<script type="text/javascript">
document.forms[0].createSection.focus();
</script>
-->