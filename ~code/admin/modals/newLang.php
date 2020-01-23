<?php
// new language modal
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');
require(ROOT.DEMO.'~code/admin/not_logged_in.php');

if(isset($_GET['lang']) && $_GET['lang'] != 'first' || $_GET['lang'] != 'second'){
    $lang = $_GET['lang'];
    // $prev will be used to revert language select input option to previously selected one, if cancel is clicked
    if($lang == 'first'){
        $prev = $first_lang;
    }else{
        $prev = $second_lang;
    }
}else{
    exit;
}
?>
<div class="modal uniBg" id="newLangContainer">
	<a href="javascript:;" class="closeBut" onclick="$('#<?php echo $lang; ?>_lang option[value=\'<?php echo $prev; ?>\']').prop('selected', true);">&times;</a>
	
	<!-- new language form start -->
	<div>
	<h3 class="first">Add a Language:</h3>
	<form name="newLangForm" id="newLangForm" action="/<?php echo DEMO; ?>~code/admin/new_lang.php" method="post">
    <input type="hidden" name="lang_num" value="<?php echo $lang; ?>">
	<p><b>New language</b> (as it is written in that language, for exemple 汉语 for Chinese):<br>
    <input type="text" name="new_lang" maxlength="20" required></p>
    <p>
    <p>Language <b>browser code</b> (see online reference <a href="https://www.w3schools.com/tags/ref_language_codes.asp" target="_blank">here</a>):<br>
    <input type="text" name="seo" maxlength="7" required>
    </p>
    Translate "<b>more</b>" (meaning: see <i>more</i> about this section):<br>
    <input type="text" name="more" maxlength="20" required>
    </p>
    <p>
    Translate "<b>back</b>" (meaning: go <i>back</i> to the previous page):<br>
    <input type="text" name="back" maxlength="20" required>
    </p>
    <a class="button hideModal left" onclick="$('#<?php echo $lang; ?>_lang option[value=\'<?php echo $prev; ?>\']').prop('selected', true);">Cancel</a>
		<button type="submit" name="newLangSubmit" class="right">Add language</button>
	</form>
	</div>
	<!-- new language form end -->
</div>
