<?php
if( !empty(SECTION) ){
	$title = USER.': '.filename(SECTION, 'decode').'.';
}else{
	$title = USER.' Artist Portfolio.';
}
if( !empty(CONTEXT_PATH) ){
	if(CONTEXT_PATH !== SECTION){
		$description = filename(str_replace(array(CONTENT, '/'), array('', ' '), CONTEXT_PATH), 'decode');
	}else{
		$description = filename(SECTION, 'decode');
	}
}else{
	$description = '';
}

require(ROOT.DEMO.'~code/inc/doctype.php');

require(ROOT.DEMO.'~code/inc/nav.php');
//echo getcwd().'<br>';
$display = display_content_array( str_replace( ROOT.CONTENT, '', getcwd() ) ); 
?>


<!-- start content -->
<div id="content">
<?php
echo $display;
?>
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.DEMO.'~code/inc/footer.php'); ?>

