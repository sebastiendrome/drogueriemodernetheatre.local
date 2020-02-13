<?php
require('~code/inc/first_include.php');
if(LANG == 'de'){
    $seo_title = $seo_title_de;
    $seo_description = $seo_description_de;
}else{
    $seo_title = $seo_title_en;
    $seo_description = $seo_description_en;
}
if(empty($seo_title)){
    $title = USER.' Artist Portfolio';
}else{
    $title = $seo_title;
}
if(empty($seo_description)){
    $description = $title;
}else{
    $description = $seo_description;
}

$page = 'home';

require(ROOT.'~code/inc/doctype.php');

require(ROOT.'~code/inc/nav.php');


/*** BACKGROUND IMG OR SLIDES START ***/
// get array of slides from txt file: /~content/home-slides.txt
// try opening the file for reading
$slides_file = ROOT.CONTENT.'home-slides.txt';
if( file_exists($slides_file) ){
	$handle = @fopen($slides_file, 'r');
	if($handle){
		// on success, set $slides array
		$home_slides = array();
		while( ($line = fgets($handle, 400) ) !== false){
			// process the line read.
			$home_slides[] = trim($line); // trim line in case line-break is included
		}
		fclose($handle);
	}
}

/* start if home_slides */
if( isset($home_slides) && !empty($home_slides) ){
	shuffle($home_slides);
	// next, output css and javascript to process the slide show (update body background-image via css transition triggered by javascript)
?>

<style type="text/css">
body{
	background-image: url(/~content/~uploads/_M/<?php echo $home_slides[0]; ?>); /* Default image. */
	background-repeat: no-repeat;
	background-position: 50%;
	background-size: cover;
	/*background-color:#000;*/
	-webkit-transition: background-image 1s ease;
	-moz-transition: background-image 1s ease;
	-ms-transition: background-image 1s ease;
	-o-transition: background-image 1s ease;
	transition: background-image 1s ease;
}

<?php if(CSS == 'nav-left'){ ?>
/* translucent nav */
#nav ul li ul{background-color: transparent;}
#nav{background-color:rgba(255, 255, 255, .8);}
<?php }else{ ?>
/* position bg image to top if nav-top */
body{background-position: top center;}
<?php } ?>

</style>

<script type="text/javascript">
// create js array of slides from php array
var slides = new Array;
<?php
$i = 0;
foreach($home_slides as $s){
	echo 'slides['.$i.'] = "'.$s.'";'.PHP_EOL;
	$i++;
}
?>
var len = slides.length;
var n = 0;
var waitTime = 4000; // 4 seconds
var tOut;
var path = '/~content/~uploads/';
var img_size = '_L/';
var slidesDiv = document.body;
// load slide[n] in DOM
function loadSlide(){
	// create new image in DOM
	var newImage = new Image();
			
	// onload function, must be declared before the new image source is set
	newImage.onload = function(){
		slidesDiv.style.backgroundImage = 'url(' + newImage.src + ')';
		if(n < len-1){
			n++;
		}else{
			n = 0;
		}
		var tDone = performance.now();
		// image load time
		var timeTaken = tDone-tStart;
		// switch image size depending on image load time
		if(timeTaken < 3000){
			img_size = '_XL/';
		}else if(timeTaken > 5000){
			img_size = '_L/';
		}
		if(timeTaken > waitTime){
			tOut = 1;
		}else{
			tOut = waitTime-timeTaken;
		}
		//alert(tOut);
		setTimeout(loadSlide, tOut);
	}

	var tStart = performance.now();
	// set the new source (will trigger onload function above)
	newImage.src = path+img_size+slides[n];
}
// when 1 slide has loaded, assign image to body css bg
loadSlide();
</script>

<?php 
// end if home_slides; if home_image, set it as body background-image
}elseif( isset($home_image) ){
	// background image position account for header if nav-top
	if( CSS == 'nav-top'){
		$bg_position = 'background-position:50% 35%; ';
	}else{
		$bg_position = 'background-position:50% 50%; ';
	}
	// various sizes
	$m_bg = CONTENT.UPLOADS.'_M/'.$home_image;
	$l_bg = CONTENT.UPLOADS.'_L/'.$home_image;
	$xl_bg = CONTENT.UPLOADS.'_XL/'.$home_image;
	
	echo '
	<style type="text/css">
	/* home page background */
	body{
		background-repeat:no-repeat;
		'.$bg_position.'
		background-size:cover;
		background-image:url(/'.$m_bg.');
	}
	@media only screen and (min-width: 320px) {
		body{
			background-image:url(/'.$l_bg.');
		}
	}
	@media only screen and (min-width: 650px) {
		body{
			background-image:url(/'.$xl_bg.');
		}
	}
	</style>';

} // end NO slides
/*** BACKGROUND IMG OR SLIDES END ***/

?>



<!-- start content -->
<div id="content">
&nbsp;
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.'~code/inc/footer.php'); ?>
