<?php
// create section modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');
?>
<div class="modal uniBg" id="saveBackup">
	<a href="javascript:;" class="closeBut">&times;</a>
	<h3 class="first below"><?php echo $ui['backupName']; ?>:</h3>
	<form name="saveBackupForm" action="" method="post" onsubmit="if($(this).find('input#backupName').val()==''){return false;}">
		<input type="text" name="backupName" id="backupName" maxlength="100" value="backup <?php echo date('F j, Y g:i'); ?>" style="width: calc(100% - 8px); margin-bottom:15px;">
		<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <button type="submit" name="saveBackup" class="save right backup"><?php echo $ui['save']; ?></button>
	</form>
</div>
