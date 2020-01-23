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
?>



<!-- droguerie custom - slides specific start -->

<?php
/* ALTERNATIVELY:
 get array from txt file: /~content/home-slides.txt and prepend to each: '~content/~uploads/_L/'
 */
// Create array of slides from directory content
$slides_dir = '~content/~uploads/home-slides/';
$slides = array();
if( $handle = opendir(ROOT.$slides_dir) ){
	while(false !== ($file = readdir($handle) ) ) {
	if(substr($file, 0, 1) != "."){
			$slides[] = '/'.$slides_dir.$file;
		}
	}
	closedir($handle);
}
shuffle($slides);
?>

<style type="text/css">
body{
	background-image: url(<?php echo $slides[0]; ?>); /* Default image. */
	background-repeat: no-repeat;
	background-position: 50%;
	background-size: cover;
	background-color:#000;
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
body{background-position: top center;}
<?php } ?>

</style>

<script type="text/javascript">
// create js array of slides from php array
var slides = new Array;
<?php
$i = 0;
foreach($slides as $s){
	echo 'slides['.$i.'] = "'.$s.'";'.PHP_EOL;
	$i++;
}
?>
var len = slides.length;
var n = 0;
var waitTime = 4000; // 4 seconds
var tOut;
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
		var timeTaken = tDone-tStart;
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
	newImage.src = slides[n];
}
// when 1 slide has loaded, assign image to body bg
loadSlide();

</script>

<!-- droguerie custom - slides specific end -->



<!-- start content -->
<div id="content">
&nbsp;
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.'~code/inc/footer.php'); ?>
