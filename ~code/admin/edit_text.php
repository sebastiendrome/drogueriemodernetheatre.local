<?php
/* Rich Text Editor (txt or html files) 
---> http://wysihtml.com, https://github.com/Voog/wysihtml/wiki
*/
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

$back_link = '/'.DEMO.'~code/admin/manage_contents.php'; // back_link should not use browser history in this case, because we want the page to *reload* and show the new saved content. 

// message returned from form process in save_text.php
$message = '';
if( isset($_GET['message']) ){
	$message = urldecode($_GET['message']);
	if( substr($message, 0, 2) == '1|'){
		// check for lang version
		if( substr( file_name_no_ext($message), -3 ) == '-de' ){
			$item_de = substr($message, 2);
			$item = str_replace('-de'.file_extension( basename($item_de) ), file_extension( basename($item_de) ), $item_de);
		}else{
			$item = substr($message, 2);
			$item_de = str_replace(file_extension( basename($item) ), '-de'.file_extension( basename($item) ), $item);
			// if saved item is not '-de' BUT $_SESSION['tlang'] == SECOND_LANG, create '-de' item
			if( isset($_SESSION['tlang']) && $_SESSION['tlang'] ==  SECOND_LANG){
				if( !file_exists(ROOT.CONTENT.$item_de) ){
					if( $fp = fopen(ROOT.CONTENT.$item_de, 'w') ){
						fwrite( $fp, file_get_contents(ROOT.CONTENT.$item) );
						fclose($fp);
					}else{
						$message .= '<p class="error">Could not create '.SECOND_LANG.' version.</p>';
					}
				}
			}
		}
		$_SESSION['editItem'] = $item;
		$_SESSION['editItemDe'] = $item_de;
		$message = '<p class="success">'.$ui['fileSaved'].'</p>';
	}else{
		$message = '<p class="error">'.substr($message, 2).'</p>';
	}
}

// form submit from within newFile.php modal: submitted: path, and fileName (optional)
if( isset($_GET['createText']) ){
	if( isset($_GET['path']) && !empty($_GET['path']) ){
		$item = $item_de = urldecode($_GET['path']);
		/*if( isset($_GET['fileName']) && !empty($_GET['fileName']) ){
			$file_name = filename( urldecode($_GET['fileName']), 'encode').'.html';
			$item .= '/_XL/'.$file_name;
			$item_de .= '/_XL/'.str_replace(file_extension($file_name), '-de'.file_extension($file_name), $file_name);
		}*/
		$_SESSION['editItem'] = $item;
		$_SESSION['editItemDe'] = $item_de;
	}else{
		exit();
	}
}

// from link to edit existing file. item is the text file, or section in which a new text file should be created...
if( isset($_GET['item']) ){
	$item = trim( urldecode($_GET['item']) );
	if( empty($item) ){
		header("location: manage_structure.php");
		exit;
	}
	$item_de = str_replace(file_extension($item), '-de'.file_extension($item), $item);
	$_SESSION['editItem'] = $item;
	$_SESSION['editItemDe'] = $item_de;
	
}elseif( isset($_SESSION['editItem']) ){
	$item = $_SESSION['editItem'];
	$item_de = $_SESSION['editItemDe'];
}

// exit if no item is set
if( !isset($item) ){
	header("location: manage_structure.php");
	exit;
}

// alternative language version of item
if( isset($_GET['tlang']) ){
	$tlang = urldecode($_GET['tlang']);
	if($tlang == FIRST_LANG){
		$en_selected = ' class="wysihtml5-command-active"';
		$de_selected = '';
		if( isset($_SESSION['tlang']) ){
			unset($_SESSION['tlang']);
		}
		$item_version = $item;
		
	}elseif($tlang == SECOND_LANG){
		$de_selected = ' class="wysihtml5-command-active"';
		$en_selected = '';
		// debug
		//echo '<h1>'.ROOT.CONTENT.$item_de.'</h1>';
		if( !file_exists(ROOT.CONTENT.$item_de) ){
			//echo 'DE DOES NOT EXIST!';
			if( $fp = fopen(ROOT.CONTENT.$item_de, 'w') ){
				fwrite( $fp, file_get_contents(ROOT.CONTENT.$item) );
				fclose($fp);
			}
		}
		$_SESSION['tlang'] = $tlang;
		$item_version = $item_de;
	}

}elseif( isset($_SESSION['tlang']) ){
	$tlang = $_SESSION['tlang'];
	if($tlang == FIRST_LANG){
		unset($_SESSION['tlang']);
		$en_selected = ' class="wysihtml5-command-active"';
		$de_selected = '';
		$item_version = $item;
	}elseif($tlang == SECOND_LANG){
		$de_selected = ' class="wysihtml5-command-active"';
		$en_selected = '';
		$item_version = $item_de;
	}

}else{ // default
	$tlang = FIRST_LANG;
	$en_selected = ' class="wysihtml5-command-active"';
	$de_selected = '';
	$item_version = $item;
}

//echo $item; //-> 'section1/section2'

$title = 'ADMIN : Edit Text : '.$item;
$description = filename(str_replace('_XL/', '', $item), 'decode');

$ext = file_extension( basename($item) );
$js_styling = $comment_styles = '';

// store original item_bg_color and font_color to use in javascript removeArticleStyle() function
$orig_item_bg_color = $item_bg_color;
$orig_font_color = $font_color;

// get $item content, attempt to match article styles comment and parse it to reset $item_bg_color and $font_color accordingly
if( file_exists(ROOT.CONTENT.$item_version) && preg_match($_POST['types']['text_types'], $ext) ){
	$content = file_get_contents(ROOT.CONTENT.$item_version);
	if($ext == '.txt'){
		$content = my_nl2br($content);
	}
	// check for comment to apply article styles to editor container
	if( preg_match('/<!-- qQqStyleqQq-.*? -->/', $content, $matches) ){
		$comment_styles = $matches[0];
		// parse comment to extract style names and attributes
		$style_string = str_replace(array('<!-- qQqStyleqQq-', ' -->'),'', $comment_styles);
		$item_styles = explode(';', $style_string);
		// construct js to apply styles to textareaContainer
		$js_styling = '$("div.textareaContainer").css({';
		$c = 2;
		$s_count = count($item_styles);
		foreach($item_styles as $style){
			if($c < $s_count){
				$coma = ',';
			}else{
				$coma = '';
			}
			$st_parts = explode(':', $style);
			if( isset($st_parts[0]) && isset($st_parts[1]) ){
				$js_styling .= '"'.trim($st_parts[0]).'":"'.trim($st_parts[1]).'"'.$coma;
				if(trim($st_parts[0]) == 'background-color'){
					$item_bg_color = str_replace('#', '', $st_parts[1]);
				}elseif(trim($st_parts[0]) == 'color'){
					$font_color = str_replace('#', '', $st_parts[1]);
				}
			}
			$c++;
		}
		$js_styling .= '});'.PHP_EOL;
	}

}else{
	$content = '';
}

// google translate
$goog_content = $content;
// translate from and to, depending on current version content
if($tlang == FIRST_LANG){
	$g_from = SEO_LANG;
	$g_to = OTHER_LANG;
}else{
	$g_from = OTHER_LANG;
	$g_to = SEO_LANG;
}

// format content for google translate url query string
if( !empty($goog_content) ){
	
	// remove starting or trailing empty space
	$goog_content = trim($goog_content);
	// replace opening and closing block elements tags with hexadecimal new line
	$goog_content = preg_replace('#</?(h1|h2|h3|p|div)>#', '%0A', $goog_content);
	// strip tags and replace open/close tags with hexadecimal single space
	$goog_content = preg_replace('#<[^>]+>#', '%20', $goog_content);
	// replace html encoded space, tab and new line, with hexadecimal encoded space and new line
	$goog_content = str_replace(array('&nbsp;', "\t", "\n"), array('%20', '%20', '%0A'), $goog_content);
	// replace dot followed by letter with dot followed by new line
	$goog_content = preg_replace('/\.([a-zA-Z])/', '.%20$1', $goog_content);
	// replace multiple spaces with single space
	$goog_content = preg_replace('/(%20)+/', '%20', $goog_content);
	// replace multiple new lines with single new line
	$goog_content = preg_replace('/(%0A)+/', '%0A', $goog_content);
	// reduce to 5000 chars to avoid browser crash
	if(strlen($goog_content) > 5000){
		$goog_content = substr($goog_content, 0, 5000);
	}
}

require(ROOT.DEMO.'~code/inc/doctype.php');

// adjust div#content width to account for editor padding(10px) and border (1px)
$content_width_adjusted = $_POST['sizes'][substr(SIZE,1)]['width']+22;

?>

<!-- ensure the css styles here are the nav-left ones for narrow windows! -->
<link rel="stylesheet" media="(max-width: 980px)" href="/<?php echo DEMO; ?>~code/css/nav-left/max-980px.css?v=<?php echo $version; ?>">
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/nav-left/max-720px.css?v=<?php echo $version; ?>">


<meta http-equiv="X-UA-Compatible" content="IE=Edge">

<link href="/<?php echo DEMO; ?>~code/css/admincss.css" rel="stylesheet" type="text/css">
<link href="/<?php echo DEMO; ?>~code/css/wysihtml5.css" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">

<style type="text/css">
body{padding-top:0;}
#content{max-width:<?php echo $content_width_adjusted; ?>px;}
</style>

<script src="/<?php echo DEMO; ?>~code/admin/wysihtml-0.5.5/dist/wysihtml-toolbar.min.js"></script>
<script src="/<?php echo DEMO; ?>~code/admin/wysihtml-0.5.5/parser_rules/custom.js"></script>

<script src="/<?php echo DEMO; ?>~code/js/jscolor.min.js"></script>

<div id="working">working...</div>


<!-- start adminContainer -->
<div id="adminContainer" style="padding:0; background-color:rgba(190, 190, 190, .3)">


<!-- start content -->
<div id="content" style="margin-top:0; padding:0; padding-bottom:10px;">

	
<form name="textEditorForm" action="save_text.php" method="post" id="textEditorForm">

<div id="editTextHeader">
	<h2><a href="<?php echo $back_link; ?>">&larr; <?php echo BACK; ?></a></h2>
	
	<div id="saveResetButs">
	<span class="tip" data-tip="<?php echo $ui['resetDescription']; ?>">
	<a href="" class="button reset left" id="reset"><?php echo $ui['reset']; ?></a></span> 
	<button type="submit" name="saveTextEditor" id="saveTextEditor" class="save"><?php echo $ui['saveChanges']; ?></button>
	</div>
	
	<?php echo $message; ?>
	<div class="clearBoth"></div>
</div>

	<!-- start toolbar -->
	<div id="toolbar">

	<!-- popup window version...
		<a href="javascript:;" onClick="MyWindow=window.open('https://translate.google.com/#view=home&op=translate&sl=<?php echo $g_from; ?>&tl=<?php echo $g_to; ?>&text=<?php echo $goog_content; ?>','googleTranslate',width=600,height=300); return false;">Google translate</a>
	-->
	<div id="lang" class="l2"><div id="prevent"></div><a href="https://translate.google.com/#view=home&op=translate&sl=<?php echo $g_from; ?>&tl=<?php echo $g_to; ?>&text=<?php echo $goog_content; ?>" target="_tab" style="border-right:none;"><span style="color:#4285F4;">G</span><span style="color:#EA4335">o</span>o<span style="color:#4285F4;">g</span><span style="color:#34A853">l</span><span style="color:#EA4335">e</span> <span style="color:#888; font-style:italic;">translate</span></a><a href="?tlang=<?php echo FIRST_LANG; ?>"<?php echo $en_selected; ?> style="border-right:none;"><?php echo FIRST_LANG; ?></a><a href="?tlang=<?php echo SECOND_LANG; ?>"<?php echo $de_selected; ?> style="border-left:none;"><?php echo SECOND_LANG; ?></a></div>
	

		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="<?php echo $ui['h1']; ?>"><h1>H1</h1></a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="<?php echo $ui['h2']; ?>"><h2>H2</h2></a>
		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" title="<?php echo $ui['h3']; ?>"><h3>H3</h3></a>

		<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" title="<?php echo $ui['p']; ?>">¶</a>

		<a data-wysihtml5-command="bold" title="<?php echo $ui['bold']; ?> (CTRL + b)l" style="border-radius: 3px 0 0 3px;"><b>B</b></a><a data-wysihtml5-command="italic" title="<?php echo $ui['italic']; ?>" style="border-radius:0;"><i>i</i></a><a data-wysihtml5-command="underline" title="<?php echo $ui['underline']; ?>" style="border-radius: 0 3px 3px 0;"><u>U</u></a>

		<a data-wysihtml5-command="foreColorStyle" id="tCListen" title="<?php echo $ui['selTxtCol']; ?>">
		<span id="t">A</span>
		<span id="b">A</span>
		</a>


		<a data-wysihtml5-command="justifyLeft" title="<?php echo $ui['alignLeft']; ?>" unselectable="on" style="border-radius: 3px 0 0 3px;"><img src="/<?php echo DEMO; ?>~code/admin/images/align-left.gif" style="width:13px; height:12px; vertical-align:middle;">
		</a><a data-wysihtml5-command="justifyCenter" title="<?php echo $ui['alignCenter']; ?>" unselectable="on" style="border-radius:0; margin:0 -1px;"><img src="/<?php echo DEMO; ?>~code/admin/images/align-center.gif" style="width:13px; height:12px;vertical-align:middle;">
		</a><a data-wysihtml5-command="justifyRight" title="<?php echo $ui['alignRight']; ?>" unselectable="on" style="border-radius: 0 3px 3px 0;"><img src="/<?php echo DEMO; ?>~code/admin/images/align-right.gif" style="width:13px; height:12px;vertical-align:middle;">
		</a>
		<a data-wysihtml5-command="justifyFull" title="<?php echo $ui['justify']; ?>" unselectable="on"><img src="/<?php echo DEMO; ?>~code/admin/images/align-justify.gif" style="width:13px; height:12px;vertical-align:middle;">
		</a>

		<a data-wysihtml5-command="insertUnorderedList" title="Insert bulleted list" unselectable="on">
		<img src="/<?php echo DEMO; ?>~code/admin/images/ul.gif" style="width:13px; height:12px;vertical-align:middle;">
		</a>

		<a data-wysihtml5-command="createLink" href="javascript:;" unselectable="on" class="wysihtml5-command-dialog-opened" style="border-radius: 3px 0 0 3px;" title="<?php echo $ui['link']; ?>"><?php echo $ui['link']; ?></a><a data-wysihtml5-command="removeLink" href="javascript:;" unselectable="on" class="" style="border-radius: 0 3px 3px 0; margin-left:-1px;  margin-right:7px;" title="<?php echo $ui['linkRemove']; ?>"><s><?php echo $ui['link']; ?></s></a>

		<a href="javascript:;" class="showModal" rel="uploadFileInsert?path=~uploads" title="<?php echo $ui['insertImg']; ?>" style="width:15px; margin-right:7px; background-image:url(/<?php echo DEMO; ?>~code/admin/images/insert-image.gif);">&nbsp;</a>

		<a id="articleColors" style="background-color:#<?php echo $item_bg_color; ?>; color:#<?php echo $font_color; ?>;"><?php echo $ui['artCols']; ?></a>



		<div id="workflow">
			<a data-wysihtml5-command="undo" href="javascript:;" unselectable="on" title="Undo">undo</a><a data-wysihtml5-command="redo" href="javascript:;" unselectable="on" title="Redo">redo</a><a data-wysihtml5-action="change_view" title="<?php echo $ui['showCode']; ?>" class="" onclick="if(this.className == ''){this.className = 'wysihtml5-command-active'}else{this.className = ''}">&lt; / ></a>
		</div>


		
		<!-- dialog divs -->

		<div class="dialogDiv" id="floatImage" style="display:none; text-align:center; left:40%;">
		<a class="closeBut">&times;</a>
		<p><span class="below" style="color:#000;">Image position</span><br>
		<a data-wysihtml5-command="floatLeft" title="<?php echo $ui['alignLeft']; ?>" unselectable="on" style="padding-top:5px;">
		<span style="float:left; margin-right:2px; display: inline-block; background-color:#fff; border:1px solid #000; width:10px; height:12px; background-image:url(/~code/admin/images/insert-image.gif);"></span>
		<img src="/<?php echo DEMO; ?>~code/admin/images/align-left.gif" style="width:13px; height:12px; vertical-align:top;"></a> 
		<a data-wysihtml5-command="floatRight" title="<?php echo $ui['alignRight']; ?>" unselectable="on" style="padding-top:5px;">
		<span style="float:right; margin-left:2px; display: inline-block; background-color:#fff; border:1px solid #000; width:10px; height:12px; background-image:url(/~code/admin/images/insert-image.gif);"></span>
		<img src="/<?php echo DEMO; ?>~code/admin/images/align-left.gif" style="width:13px; height:12px; vertical-align:top;"></a></p>
		<!--<p><a data-wysihtml5-command="widthHalfClass">width 50%</a></p>-->
		</div>

		<div data-wysihtml5-dialog="foreColorStyle" class="dialogDiv" id="tColorModal" style="display:none;">
			<a class="closeBut">&times;</a>
			<input type="hidden" id="tC" data-wysihtml5-dialog-field="color" value="">
			<input name="selected_txt_color" id="jsC" class="jscolor" value="<?php echo $font_color; ?>" onchange="update(this.jscolor)">
			<a data-wysihtml5-dialog-action="cancel"><?php echo $ui['cancel']; ?></a>
			&nbsp;<a data-wysihtml5-dialog-action="save">OK</a>
		</div>

		<div data-wysihtml5-dialog="createLink" id="createLink" class="dialogDiv" style="display:none;">
			<label>
			<span style="color:#000;"><?php echo ucwords($ui['link']); ?>:</span>
				<input data-wysihtml5-dialog-field="href" id="link_url" value="" placeholder="http://" style="width:400px;">
			</label>
			<a data-wysihtml5-dialog-action="save" onclick="javascript:validateUrl('link_url', event);">&nbsp;OK&nbsp;</a><a class="closeBut">&times;</a>
		</div>

		<div class="dialogDiv" id="itemColors" style="display:none;">
		<a class="closeBut">&times;</a>
		<?php
		// if styles comment string was matched in item content
		if( isset($matches[0]) && !empty($matches[0]) ){
			$rem_style = '';
		}else{
			$rem_style = ' style="display:none;"';
		}
		?>
		<div id="removeArticleStyle"<?php echo $rem_style; ?>>
			<a href="javascript:;" class="remove"><?php echo $ui['delete'].' '.$ui['artCols']; ?></a>
			</div>
			<div style="display:table;">
			<div style="display:table-cell; padding-right:5px;">
			<span class="below" style="color:#000;"><?php echo $ui['bgColor']; ?></span><br>
				<input name="item_bg_color" class="jscolor jscolor-active" value="<?php echo $item_bg_color; ?>" onchange="updateBgColor(this.jscolor)" autocomplete="off" style="width:150px;">
			</div>
			<div style="display:table-cell; padding-right:5px;">
				<span class="below" style="color:#000;"><?php echo $ui['txtColor']; ?></span><br>
				<input name="font_color" class="jscolor jscolor-active" value="<?php echo $font_color; ?>" onchange="updateTextColor(this.jscolor)" autocomplete="off" style="width:150px;">
			</div>
			<div style="display:table-cell; vertical-align:bottom; padding-bottom:4px;">
				<a href="javascript:;" id="cancelItemColors">&nbsp;<?php echo $ui['cancel']; ?>&nbsp;</a> 
				<a href="javascript:;" id="insertItemColors">&nbsp;OK&nbsp;</a>
			</div>
			</div>
		</div>

	</div><!-- end toolbar -->

	<div class="textareaContainer uniBg">
		<div id="editor" data-placeholder="<?php echo $ui['textAreaPlaceholder']; ?>">
		<?php echo $content; ?>
		</div>
	</div>
	
	<input type="hidden" name="item" value="<?php echo $item_version; ?>">
	<input type="hidden" name="commentStyles" value="<?php echo $comment_styles; ?>">

</form>


<div class="clearBoth"></div>

</div><!-- end content -->


</div><!-- end adminContainer -->

<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>


<script type="text/javascript">

var formmodified = 0;

var font_color = '<?php echo $font_color; ?>';
var item_bg_color = '<?php echo $item_bg_color; ?>';
var orig_font_color = '<?php echo $orig_font_color; ?>';
var orig_item_bg_color = '<?php echo $orig_item_bg_color; ?>';

// set editor and textarea height depending on window size
var tAreaOffset = $(".textareaContainer").offset();
// #content padding: 10x2 + textareaContainer margin-top:10 + textareaContainer border 1 x 2 = 32
var tAreaHeight = wH-tAreaOffset.top-32;
// but make sure it is never less than 100px high
if(tAreaHeight < 100){
	tAreaHeight = 100;
}
$(".textareaContainer").css("height", tAreaHeight+'px');

var commentStyles = '<?php echo $comment_styles; ?>';

var ss = new Array();
ss[0] = '/'+demo+'~code/css/common.css';
ss[1] = '/'+demo+'~code/css/<?php echo CSS; ?>/layout.css';
ss[2] = '/<?php echo CONTENT; ?>_custom_css.css';
var editor = new wysihtml5.Editor("editor", {
	toolbar:		"toolbar",
	parserRules:	wysihtml5ParserRules,
	style: false,
	stylesheets:	ss
	//useLineBreaks:	false
});

editor
/*.on("load", function() {
	$(this).focus();
})
.on("load", function() {
	$('.wysihtml5-sandbox').contents().find('body')
	.on('mouseenter', 'img', function(){
		$(this).wrap('<span style="position:relative;"></span>');
		$(this).parent('span').prepend('<span class="editImg" style="position:absolute; top:0; left:0; background-color:#fff; border:1px solid #000; padding:5px;">Hello</span>');

	})
	.on('mouseleave', 'img', function(){
		$(this).unwrap("span");
		$('.wysihtml5-sandbox').contents().find('span.editImg').remove();
	});
		
})*/
/*.on("focus", function() {
	//
})
.on("blur", function() {
	//
})*/
.on("newword:composer", function() {
	formmodified = 1;
	warn();
})
.on("undo:composer", function() {
	formmodified = 1;
	warn();
})
.on("redo:composer", function() {
	formmodified = 1;
	warn();
})
.on("save:dialog", function(){
	formmodified = 1;
	warn();
})
.on("change", function() {
	formmodified = 1;
	warn();
})
.on("paste", function() {
	formmodified = 1;
	warn();
})
/*
.on("interaction", function(e) {
	console.log(e);
})*/
;

// set formmodified when action buttons are clicked
$('a[data-wysihtml5-command]').on('click', function(){
	//alert('clicked!');
	formmodified = 1;
	warn();
})

// update wysihtml5 input from jscolor input value (when jscolor is changed)
function update(jscolor){
	var input = document.getElementById('tC');
	input.value = jscolor.toRGBString();
	input.style.backgroundColor = '#'+jscolor;
}

// update jscolor input from wysihtml5 input value (when text is clicked)
function updatePickerColor(){
	setTimeout(function(){
		var updateFrom = document.getElementById('tC').value;
		//alert(updateFrom);
		var updateTo = document.getElementById('jsC');
		if(updateFrom !== ''){
			//alert(updateFrom);
			//alert(updateTo.jscolor);
			updateTo.value = updateFrom;
			updateTo.jscolor.importColor(updateFrom);
			//updateTo.value = updateFrom.toHEXString;
		}else{
			updateTo.value = orig_font_color;
		}
	}, 200);
}

$(".textareaContainer").on('click', function(){
	updatePickerColor();
});

function warn(){
	if( $('#editTextHeader').find('p.success').length ){
		$('#editTextHeader p.success').remove();
	}
	$('#editTextHeader h2').hide();
	$('#saveResetButs').show();
	$('div#lang div#prevent').show();
}

function unwarn(){
	$('#editTextHeader h2').show();
	$('#saveResetButs').hide();
	$('div#lang div#prevent').hide();
}

$('div#lang div#prevent').on('click', function(){
	alert('Save your changes first!\n(or click "reset" to discard your changes)');
});

// update bg color
function updateBgColor(jscolor){
	$('div.textareaContainer').css('background-color', '#' + jscolor);
}
function updateTextColor(jscolor){
	$('div.textareaContainer').css('color', '#' + jscolor);
}
function initArticleStyle(item_bg_color, font_color){
	$('div.textareaContainer').css({'background-color':'#'+item_bg_color,'color':'#'+font_color});
	$('input[name="font_color"]').val(font_color).css('background-color','#'+font_color);
	$('input[name="item_bg_color"]').val(item_bg_color).css('background-color','#'+item_bg_color);
	$('#itemColors').hide();
	$('a#articleColors').removeClass('wysihtml5-command-active');
	$('a#articleColors').css({'background-color':'#'+item_bg_color, 'color':'#'+font_color});
}

// validate url (for inserted images) 
// and replace /_XL/ with /_M/ if found, to use medium image and not extra large.
function validateUrl(id, e){
	e = e || window.event;
	var replace = "^(\/"+content.replace("/","\/")+"~uploads\/|https?:\/\/|mailto:)";
	var re = new RegExp(replace);
	var v = document.getElementById(id).value;
	var m = v.match(re);
	if(m == null){
		alert('Attention:\nThe '+id+' does not start with "http://" \nIf you know what you\'re doing, no problem. \nIf not, double-check to make sure it is a valid link.');
		return false;
	}
	var img_size = v.match(/\/_XL\//);
	if(img_size != null){
		var new_size = v.replace("/_XL/", "/_M/");
		document.getElementById(id).value = new_size;
	}
}

// insert html comment in editor content, with styles to be applied to article
function insertItemColors(){
	var bg_val = $('input[name="item_bg_color"]').val();
	var color_val = $('input[name="font_color"]').val();
	if(bg_val == orig_item_bg_color && color_val == orig_font_color){
		removeArticleStyle();
		return false;
	}
	var myInsert = "<!-- qQqStyleqQq-background-color:#"+bg_val+";color:#"+color_val+"; -->";
	
	// check that the new value is not the same as the old one
	if(myInsert !== commentStyles){
		// update value of commentStyles input
		$('input[name="commentStyles"]').val(myInsert);
	}else{
		$('#itemColors').hide();
		return false;
	}
	
	// lets update commentStyles to match the new inserted comment
	commentStyles = myInsert;
	$('div#removeArticleStyle').show();
	initArticleStyle(bg_val, color_val);
	// let's update the style vars to match the new ones
	item_bg_color = bg_val;
	font_color = color_val;
	warn();
}

// remove article styles
function removeArticleStyle(){
	// empty the value of commentStyles input
	$('input[name="commentStyles"]').val('');
	$('div#removeArticleStyle').hide();
	initArticleStyle(orig_item_bg_color, orig_font_color);
	// reset commentStyles to false (= no comment is matched in article content)
	commentStyles = '';
	warn();
}


// prevent user from leaving the page without saving his changes
$(document).ready(function(){

	// apply eventual styles (background-color and text color) to textarea container
	<?php echo $js_styling; ?>

	// warn before leaving the page, if form was modified
	window.onbeforeunload = function(e){
		var warning = "Your changes have not been saved! Are you sure you want to leave this page?";
		if (formmodified == 1) {
			var e = e || window.event;
			// For IE and Firefox
			if (e){
				e.returnValue = warning;
			}
			// For Safari
			return warning;
		}
	}

	// set formmodified to 1 when changing the editor or code textarea content
	$('body').on('keyup', 'div#editor, textarea.wysihtml5-source-view', function(){
		formmodified = 1;
		warn();
	});

	// show image float left-right-none dialog when image is clicked, if image is less wide than content
	$('body').on('click', 'div#editor img', function(){
		var imgW = $(this).width();
		var editorW = $('div#editor').width();
		//alert(imgW+' - '+editorW);
		if(editorW > imgW){
			$('div#floatImage').show();
		}
	});
	// hide the image float dialog if anything else than an image is clicked within editor
	$('div#editor').not('img').click(function() {
		$('div#floatImage').hide();
	});

	$('a#articleColors').on('click', function(){
		var $dialog = $('div#itemColors');
		if($dialog.css('display') == 'none'){
			$(this).removeAttr('style').addClass('wysihtml5-command-active');
			$dialog.show();
		}else{
			$dialog.hide();
			$(this).removeClass('wysihtml5-command-active').css({'background-color':'#'+item_bg_color, 'color':'#'+font_color});
		}
	});

	// focus on editor when actually clicking on textarea container padding
	$('div.textareaContainer').on('click', function(e){
		var textareaExists = $('body textarea.wysihtml5-source-view');
		if( !textareaExists.length ){
			editor.focus();
		}
	});

	// save editor content (append textarea with its content to the submitted form)
	$("button#saveTextEditor").click(function(){
		var content = $('#editor').html();
		$('form').append('<textarea name="content">'+content+'</textarea>');
	});

	// save and reset buttons remove save warning and reset formmodified to 0
	$("button#saveTextEditor, a#reset").click(function() {
		formmodified = 0;
		unwarn();
	});

	$('a#insertItemColors').on('click', function(){
		insertItemColors();
	});
	$('a#cancelItemColors').on('click', function(){
		initArticleStyle(item_bg_color, font_color);
	});
	$('div#removeArticleStyle a.remove').on('click', function(){
		removeArticleStyle();
	});
});

</script>

