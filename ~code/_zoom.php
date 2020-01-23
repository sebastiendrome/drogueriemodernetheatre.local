<?php
require('inc/first_include.php');

$lang_file = ROOT.DEMO.'~code/admin/ui_lang/'.$default_lang.'.php';
if( file_exists($lang_file) ){
	require($lang_file);
}else{
	require(ROOT.DEMO.'~code/admin/ui_lang/english.php');
}

// make sure there is an image request, or else exit
if(isset($_GET['img']) && !empty($_GET['img'])){
	$img = urldecode($_GET['img']);
	// validate file extension, or else exit
	$ext = file_extension( basename($img) );
	if( !preg_match($types['resizable_types'], $ext) ) {
		exit;
	}
	// use different directory size depending on screen width
	if(isset($_COOKIE['wW']) && $_COOKIE['wW'] < 650){
		$dir_size = '_L';
	}else{
		$dir_size = '_XL';
	}
	// img url
	$img_url = preg_replace('/\/_(S|M|L)\//', '/'.$dir_size.'/', $img);
}else{
	exit;
}

$title = USER.' Artist Portfolio';
$description = USER.' image: '.basename($img);
$social_image = PROTOCOL.SITE.CONTENT.$img_url;

require(ROOT.'~code/inc/doctype.php');


if( !isset($_GET['zoomTest']) && file_exists(ROOT.CONTENT.$img_url) ){
	// get image file width and height
	list($orig_img_w, $orig_img_h) = getimagesize(ROOT.CONTENT.$img_url);
	$relative_url = '/'.CONTENT.$img_url;

// zoom test, from admin 
}elseif( file_exists(ROOT.$img_url) ){
	list($orig_img_w, $orig_img_h) = getimagesize(ROOT.$img_url);
	$relative_url = $img_url;
	$zoom_mode = urldecode($_GET['zoomTest']);
	$relative_url = $img_url;

// image not found, echo message and exit
}else{
	echo '<!-- start nav -->
	<div class="backTitle zoomPage uniBg">
		<ul>
			<li><a href="javascript:window.history.back();">&larr; '.BACK.'</a>
			<p>The file has been removed.</p></li>
		</ul>
	</div><!-- end nav -->
	</body>
	</html>';
	exit;
}

// bg img while large one is loading. This image is already cached, since it is the image that was clicked on a previous page, to be enlarged. ( $img = urldecode($_GET['img]) )
$bg_rel_url = '/'.CONTENT.$img;

?>

<!-- jQuery -->
<script type="text/javascript" src="/~code/js/jquery-3.3.1.min.js" charset="utf-8"></script>
<script type="text/javascript" src="/~code/js/js.js?v=4" charset="utf-8"></script>

<script type="text/javascript">
function rendered(){
	//Render complete
	//alert("image rendered");
	var zoomMore = '<?php echo $ui['zoomMore']; ?>';
	var backTitleWidth = $('div.backTitle').outerWidth();
	// image file actual width and height
	var orig_img_w = <?php echo $orig_img_w; ?>;
	var orig_img_h = <?php echo $orig_img_h; ?>;
	
	// image width and height as displayed
	var img_display_w = $('#inOut').width();
	var img_display_h = $('#inOut').height();
	//alert(img_display_w+' x '+img_display_h);

	// ratio between two image sizes (original and as displayed)
	var ratio = orig_img_w/img_display_w;
	ratio = ratio.toFixed(2);
	if(ratio > 1){
		$('body').append('<div id="mess" style="opacity:0; position:fixed; text-align:center; top:40px; left: '+backTitleWidth+'px; padding:15px; background-color:rgba(0,0,0,.5); color:#fff;"><ul><li style="margin:6px 0;">'+zoomMore+'</li></ul></div>');
		$('body div#mess').animate({'opacity': 1}, 1000);
		setTimeout(function(){
			$('body div#mess').animate({'opacity': 0}, 1000, function(){
				$(this).hide();
			});
		}, 2000);
	}else{
		$('img#inOut').css('cursor', 'auto').removeAttr('title');
	}
	
	// scroll to vertical middle of image (works only if image has loaded)
	$('html,body').animate({ scrollTop: -( wH - img_display_h ) / 2  }, 200);

	// When user clicks on image (to zoom in, or to zoom out), we want to:
	// 1. change the image style/size accordingly,
	// 2. scroll so that image point where user just clicked is centered in window
    $('body').on('click', 'img#inOut', function(e){

		// image width and height as displayed (repeat from above, but now hopefully the image has loaded!)
		img_display_w = $('#inOut').width();
		img_display_h = $('#inOut').height();
		//alert(img_display_w+' x '+img_display_h);
		
		// ratio between two image sizes (original and as displayed)
		var ratio = orig_img_w/img_display_w;
		ratio = ratio.toFixed(2);

		// get mouse coordinates relative to image
		var y = e.pageY - $(this).offset().top; // from top edge
		var x = e.pageX - $(this).offset().left; // from left edge

		// if user zooms-in, change image style to zoom-out, and vice-versa
        if( $(this).hasClass("isOut") ){
            $(this).removeClass("isOut").addClass("isIn"); // let's zoom in
			// image is now full size. Let's calculate new coordinates by multiplying old ones by ratio
			var new_y = y*ratio; // top
			var new_x = x*ratio; // left

		// if user zooms-out,...
		}else{
			$(this).removeClass("isIn").addClass("isOut"); // let's zoom out
			// image is now reduced. Let's calculate new coordinates by dividing old ones by ratio
			var new_y = y/ratio; // top
			var new_x = x/ratio; // left
		}

		// now that we have the new coordinates, let's calculate the distance relative to window width and height, where these coordinates will be centered in window:
		var fromTop = new_y-(wH/2);
		var fromLeft = new_x-(wW/2);
		// and finaly let's scroll there...
		$('html,body').scrollTop(fromTop).scrollLeft(fromLeft);
		
	});
}

function startRender(){
	//Rendering start
	requestAnimationFrame(rendered);
}

function loaded(){
	requestAnimationFrame(startRender);
}
</script>

<style type="text/css">
/* ensure the back link is positionned the same way regardless of layout choosen by user */
.backTitle{top:40px;}
/* orizontaly center the image, limit its display width to its actual width */
img#inOut{
	display:block; 
	margin:auto; 
	max-width:<?php echo $orig_img_w; ?>px;
	background-image: url(<?php echo $bg_rel_url; ?>);
	background-repeat:no-repeat;
	background-size:cover;
}
/* reduced size style */
img.isOut{cursor: zoom-in;}
<?php if($zoom_mode == 'fill screen'){
	echo 'img.isOut{width:100%;}'.PHP_EOL;
}elseif($zoom_mode == 'fit to screen'){
	if( isset($_COOKIE['wW']) ){
		$max_w = $_COOKIE['wW'].'px';
		$max_h = $_COOKIE['wH'].'px';
	}else{
		$max_w = '100%';
		$max_h = 'auto';
	}
	echo 'img.isOut{max-width:'.$max_w.'; max-height:'.$max_h.';}'.PHP_EOL;
}
?>
/* full size style */
img.isIn{width:auto; cursor:zoom-out;}
</style>

<!-- start nav -->
<div class="backTitle zoomPage uniBg">
	<ul>
		<li><a href="javascript:window.history.back();">&larr; <?php echo BACK; ?></a></li>
	</ul>
</div><!-- end nav -->


<img src="<?php echo $relative_url; ?>" id="inOut" class="isOut" title="<?php echo $ui['zoomMore']; ?>" onload="loaded();">



<!--
<script type="text/javascript">
/*
$(document).ready(function(){
	
	var zoomMore = '<?php echo $ui['zoomMore']; ?>';
	var backTitleWidth = $('div.backTitle').outerWidth();
	// image file actual width and height
	var orig_img_w = <?php echo $orig_img_w; ?>;
	var orig_img_h = <?php echo $orig_img_h; ?>;
	
	// image width and height as displayed
	var img_display_w = $('#inOut').width();
	var img_display_h = $('#inOut').height();
	//alert(img_display_w+' x '+img_display_h);

	// ratio between two image sizes (original and as displayed)
	var ratio = orig_img_w/img_display_w;
	ratio = ratio.toFixed(2);
	if(ratio > 1){
		$('body').append('<div id="mess" style="opacity:0; position:fixed; text-align:center; top:40px; left: '+backTitleWidth+'px; padding:15px; background-color:rgba(0,0,0,.5); color:#fff;"><ul><li style="margin:6px 0;">'+zoomMore+'</li></ul></div>');
		$('body div#mess').animate({'opacity': 1}, 1000);
		setTimeout(function(){
			$('body div#mess').animate({'opacity': 0}, 1000, function(){
				$(this).hide();
			});
		}, 2000);
	}else{
		$('img#inOut').css('cursor', 'auto').removeAttr('title');
	}
	
	// scroll to vertical middle of image (works only if image has loaded)
	$('html,body').animate({ scrollTop: -( wH - img_display_h ) / 2  }, 200);

	// When user clicks on image (to zoom in, or to zoom out), we want to:
	// 1. change the image style/size accordingly,
	// 2. scroll so that image point where user just clicked is centered in window
    $('body').on('click', 'img#inOut', function(e){

		// image width and height as displayed (repeat from above, but now hopefully the image has loaded!)
		img_display_w = $('#inOut').width();
		img_display_h = $('#inOut').height();
		//alert(img_display_w+' x '+img_display_h);
		
		// ratio between two image sizes (original and as displayed)
		var ratio = orig_img_w/img_display_w;
		ratio = ratio.toFixed(2);

		// get mouse coordinates relative to image
		var y = e.pageY - $(this).offset().top; // from top edge
		var x = e.pageX - $(this).offset().left; // from left edge

		// if user zooms-in, change image style to zoom-out, and vice-versa
        if( $(this).hasClass("isOut") ){
            $(this).removeClass("isOut").addClass("isIn"); // let's zoom in
			// image is now full size. Let's calculate new coordinates by multiplying old ones by ratio
			var new_y = y*ratio; // top
			var new_x = x*ratio; // left

		// if user zooms-out,...
		}else{
			$(this).removeClass("isIn").addClass("isOut"); // let's zoom out
			// image is now reduced. Let's calculate new coordinates by dividing old ones by ratio
			var new_y = y/ratio; // top
			var new_x = x/ratio; // left
		}

		// now that we have the new coordinates, let's calculate the distance relative to window width and height, where these coordinates will be centered in window:
		var fromTop = new_y-(wH/2);
		var fromLeft = new_x-(wW/2);
		// and finaly let's scroll there...
		$('html,body').scrollTop(fromTop).scrollLeft(fromLeft);
		
	});
});
*/
</script>
-->

</body>
</html>
