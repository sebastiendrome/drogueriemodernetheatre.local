<?php
// edit Text (txt or html files) ---> http://wysihtml.com, https://github.com/Voog/wysihtml/wiki
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

// message returned from from process in save_text.php
$message = '';
if( isset($_GET['message']) ){
	$message = urldecode($_GET['message']);
	if( substr($message, 0, 2) == '1|'){
		$item = substr($message, 2);
		$_SESSION['editItem'] = $item;
		$message = '<p class="success" style="padding-right:15px;">'.$ui['fileSaved'].'</p>';
		$back_link = '/_code/admin/manage_contents.php'; // back_link should not use browser history in this case, because we want the page to *reload* and show the new content (edited text). 
	}else{
		$message = '<p class="error">'.substr($message, 2).'</p>';
	}
}

// back history, will be attached to the "back" button, using javascript:history.go(n)
$back_History = -1; // will be decremented each time we reload this page (if $item is not set but $_SESSION['editItem'] is)

// form submit from within newFile.php modal: submitted: path, and fileName (optional)
if( isset($_POST['createText']) ){
	if( isset($_POST['path']) && !empty($_POST['path']) ){
		$item = urldecode($_POST['path']);
		if( isset($_POST['fileName']) && !empty($_POST['fileName']) ){
			$file_name = filename( urldecode($_POST['fileName']), 'encode').'.html';
			$item .= '/_XL/'.$file_name;
			//echo $item;
		}
		$_SESSION['editItem'] = $item;
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
	$_SESSION['editItem'] = $item;
	
}elseif( isset($_SESSION['editItem']) ){
	$item = $_SESSION['editItem'];
	--$back_History; // we've reloaded this page, decrement back-history
}

if(!isset($item)){
	header("location: manage_structure.php");
	exit;	
}

//echo $item; //-> 'section1/section2'

$title = 'ADMIN : Edit Text :';
$description = filename(str_replace('_XL/', '', $item), 'decode');
if(!isset($back_link)){ // if back_link has not already been set (which happens if we've saved the edited text, see above, line 15)
	$back_link = 'javascript:history.go('.$back_History.')'; // use browser's history.
}

$ext = file_extension(basename($item));

if( file_exists(ROOT.CONTENT.$item) && preg_match($_POST['types']['text_types'], $ext) ){
	$content = file_get_contents(ROOT.CONTENT.$item);
	if($ext == '.txt'){
		$content = my_nl2br($content);
	}

}else{
	$content = '';
}

//echo $content;

require(ROOT.'_code/inc/doctype.php');

?>

<!-- ensure the css styles here are the default ones for narrow windows! -->
<link rel="stylesheet" media="(max-width: 980px)" href="/_code/css/nav-left/max-980px.css?v=<?php echo $version; ?>">
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/nav-left/max-720px.css?v=<?php echo $version; ?>">


<meta http-equiv="X-UA-Compatible" content="IE=Edge">

<link href="/_code/css/admincss.css" rel="stylesheet" type="text/css">
<link href="/_code/css/wysihtml5.css" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/admin-max-720px.css">

<script src="/_code/admin/wysihtml-0.5.5/dist/wysihtml-toolbar.min.js"></script>
<script src="/_code/admin/wysihtml-0.5.5/parser_rules/custom.js"></script>

<div id="working">working...</div>

<!--
<div class="adminHeader">
<div id="admin">
	<a href="?logout" class="button discret remove right">logout</a>
	<a href="mailto:sebdedie@gmail.com" title="ask questions or send requests via email" class="button discret help right">help</a>
	<a href="preferences.php" title="Set the site main parameters, languages, fonts and other design options" class="button discret fav right">preferences</a>
</div>
	<div style="padding:0 20px;">
<h2><a href="<?php echo $back_link; ?>">&larr; back</a> | <?php echo $description; ?></h2> 
		<a name="topView"></a><?php echo $message; ?>
	</div>
</div>
-->

<!-- start adminContainer -->
<div id="adminContainer" style="padding:0;">
<div id="editTextHeader">
	<h2><a href="<?php echo $back_link; ?>">&larr; <?php echo BACK; ?></a></h2>
	<?php echo $message; ?>
</div>


<!-- start content -->
<div id="content" style="border:1px solid #ccc; margin-top:0; background-color:#eee;">

	
<form name="textEditorForm" action="save_text.php" method="post" id="textEditorForm">

<!-- start toolbar -->
<div id="toolbar">

<a data-wysihtml5-command="bold" title="CTRL+B"><b><?php echo $ui['bold']; ?></b></a>
<a data-wysihtml5-command="italic" title="CTRL+i"><i><?php echo $ui['italic']; ?></i></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h1" title="Big Header text"><h1>H1</h1></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h2" title="Medium Header text"><h2>H2</h2></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="h3" title="Small Header text"><h3>H3</h3></a>
<a data-wysihtml5-command="formatBlock" data-wysihtml5-command-value="p" title="Paragraph">P</a>


<a data-wysihtml5-command="justifyLeft" title="Align left" unselectable="on" style="border-radius: 3px 0 0 3px;"><img src="/_code/admin/images/align-left.gif" style="width:13px; height:12px; vertical-align:middle;">
</a><a data-wysihtml5-command="justifyCenter" title="Align center" unselectable="on" style="border-radius:0; margin:0 -1px;"><img src="/_code/admin/images/align-center.gif" style="width:13px; height:12px;vertical-align:middle;">
</a><a data-wysihtml5-command="justifyRight" title="Align right" unselectable="on" style="border-radius: 0 3px 3px 0;"><img src="/_code/admin/images/align-right.gif" style="width:13px; height:12px;vertical-align:middle;">
</a>
<a data-wysihtml5-command="justifyFull" title="Justify" unselectable="on"><img src="/_code/admin/images/align-justify.gif" style="width:13px; height:12px;vertical-align:middle;">
</a>

<!-- User can define the image's src: -->
<!--<a data-wysihtml5-command="insertImage">image</a>-->
<a href="javascript:;" class="showModal" rel="uploadFileInsert?path=_uploads">image</a>

<a data-wysihtml5-command="createLink" href="javascript:;" unselectable="on" class="wysihtml5-command-dialog-opened" style="border-radius: 3px 0 0 3px;"><?php echo $ui['link']; ?></a><a data-wysihtml5-command="removeLink" href="javascript:;" unselectable="on" class="" style="border-radius: 0 3px 3px 0; margin-left:-1px;"><s><?php echo $ui['link']; ?></s></a>

<div id="workflow">
<a data-wysihtml5-command="undo" href="javascript:;" unselectable="on" title="Undo">undo</a><a data-wysihtml5-command="redo" href="javascript:;" unselectable="on" title="Redo">redo</a><a data-wysihtml5-action="change_view" title="<?php echo $ui['showCode']; ?>" class="" onclick="if(this.className == ''){this.className = 'wysihtml5-command-active'}else{this.className = ''}"><?php echo $ui['showCode']; ?> <span class="question" title="<?php echo $ui['showCodeDescription']; ?>">?</span></a>
</div>

	<div data-wysihtml5-dialog="createLink" style="display:none;" class="dialogDiv">
        <label>
          <?php echo ucwords($ui['link']); ?>:
          <input data-wysihtml5-dialog-field="href" id="link_url" value="" placeholder="http://" style="width:400px;">
        </label>
        <a data-wysihtml5-dialog-action="save" onclick="javascript:validateUrl('link_url', event);">OK</a>&nbsp;<a data-wysihtml5-dialog-action="cancel"><?php echo $ui['cancel']; ?></a>
      </div>

</div><!-- end toolbar -->
   
   <div class="textareaContainer">
	  <textarea id="textarea" name="content" placeholder="<?php echo $ui['textAreaPlaceholder']; ?>"><?php echo $content; ?></textarea>
  </div>
	  <input type="hidden" name="item" value="<?php echo $item; ?>">
	  <button type="reset" name="reset" class="left" id="resetTextEditor"><?php echo $ui['reset']; ?></button> <span class="question" title="<?php echo $ui['resetDescription']; ?>">?</span>
	  <button type="submit" name="saveTextEditor" id="saveTextEditor" class="save right"><?php echo $ui['saveChanges']; ?></button>
	</form>

<div style="display:none;">
	<h2>Events:</h2>
<div id="log"></div>
</div>

<div class="clearBoth"></div>

</div><!-- end content -->


</div><!-- end adminContainer -->

<?php require(ROOT.'_code/inc/adminFooter.php'); ?>


<script type="text/javascript">
var ss = new Array();
ss[0] = '/_code/css/common.css';
ss[1] = '/_code/css/<?php echo CSS; ?>/layout.css';
ss[2] = '/<?php echo CONTENT; ?>_custom_css.css';
var editor = new wysihtml5.Editor("textarea", {
	toolbar:		"toolbar",
	parserRules:	wysihtml5ParserRules,
	style: false,
	stylesheets:	ss
	//useLineBreaks:	false
});

var formmodified = 0;

editor
	/*.on("load", function() {
		//
	})
	.on("focus", function() {
		//
	})
	.on("blur", function() {
		//
	})*/
	.on("newword:composer", function() {
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
	.on("undo:composer", function() {
		formmodified = 1;
		warn();
	})
	.on("redo:composer", function() {
		formmodified = 1;
		warn();
	})/*
	.on("interaction", function() {
		formmodified = 1;
		warn();
	})*/;

function warn(){
	if( !$('#editTextHeader').find('p.note.warn').length ){
		$('#editTextHeader').append('<p class="note warn"><?php echo $ui['warnSave']; ?></p>');
	}
	if( $('#editTextHeader').find('p.success').length ){
		$('#editTextHeader p.success').remove();
	}
}

function unwarn(){
	if( $('#editTextHeader').find('p.note.warn').length ){
		$('#editTextHeader p.note.warn').remove();
	}
}


// set iframe and textarea height depending on window size
$("#textEditorForm iframe").css("height", wH-170+'px');
$("#textEditorForm textarea").css("height", wH-170+'px');
// but make sure it is never less than 100px high
if($("#textEditorForm iframe").height() < 100){
	$("#textEditorForm iframe").css("height", 100+'px');
}
if($("#textEditorForm textarea").height() < 100){
	$("#textEditorForm textarea").css("height", 100+'px');
}

// validate url (for inserted images) 
// and replace /_XL/ with /_L/ if found, to use Large image and not extra large.
function validateUrl(id, e){
	e = e || window.event;
	var v = document.getElementById(id).value;
	var m = v.match(/^(\/_content\/_uploads\/|https?:\/\/|mailto:)/);
	if(m == null){
		alert(id+' must start with "http://", "https://" or "mailto:"');
		return false;
	}
	var img_size = v.match(/\/_XL\//);
	if(img_size != null){
		var new_size = v.replace("/_XL/", "/_L/");
		document.getElementById(id).value = new_size;
	}
}

// prevent user from leaving the page without saving his changes
$(document).ready(function(){
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
	$("button#saveTextEditor, button#resetTextEditor").click(function() {
		formmodified = 0;
		unwarn();
    });
});

</script>

