<?php
require('_code/inc/first_include.php');
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
    $description = USER;
}else{
    $description = $seo_description;
}

$page = 'home';

$slides_dir = '_content/_uploads/home-slides/';
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
$slides_data_output = '["'.implode('","', $slides).'"]';

require(ROOT.'_code/inc/doctype.php');
?>

<script type="text/javascript">
var imgLoaded = 0;
function setImgLoaded(){imgLoaded = 1;}
</script>

<style type="text/css">
html, body{height:100%;}

div#slideContainer {
	width:100%; height: 100%;
	/* Default image. */
	background-image: url(<?php echo $slides[0]; ?>);
	background-repeat: no-repeat;
	background-position: top left;
	background-size: cover;
	background-color:#000;
	-webkit-transition: all 0.7s ease;
	-moz-transition: all 0.7s ease;
	-ms-transition: all 0.7s ease;
	-o-transition: all 0.7s ease;
	transition: all 0.7s ease;
}
/* additional CSS for hidden image preload section */
div#hidden{ display: none; }

<?php
if(CSS == 'nav-left'){ 
	echo '
	#nav ul li ul{background-color: transparent;}
	#nav{background-color:rgba(255, 255, 255, .8);}
	';
}
?>
#footer{position:absolute; bottom:0;}
</style>

<?php require(ROOT.'_code/inc/nav.php'); ?>

<div id="slideContainer">&nbsp;</div>


<div id="hidden">
<?php
echo '<img src="'.$slides[1].'" id="loadingImg" onload="setImgLoaded();">';
?>
</div>


<script type="text/javascript">
var slides = <?php echo $slides_data_output; ?>;
var len = slides.length;
var slideNum = 1;
var timeOut = 3500;
var checkOut = 500;
var loadingImgId = 'loadingImg';
var allLoaded = 0; // gets updated to 1 when all images have loaded, but has no consequences below...
var changeTime;
var checkTime;

function checkAndLoad(){

	if(imgLoaded == 1){ // loaded - do your work
		clearTimeout(checkTime);
		if(timeOut <= 0){
			timeOut = 1;
		}
		imgLoaded = 0;
		//console.log(slideNum+' loaded: '+timeOut);
		changeImg();
	
	}else{ // not loaded, check again until loaded
		timeOut = timeOut - checkOut;
		//console.log(slideNum+' not loaded: '+timeOut);
		checkTime = setTimeout(checkAndLoad, checkOut);
	}
}

function doIt(){
	$("#slideContainer").css("background-image", 'url("'+slides[slideNum]+'")');
	clearTimeout(changeTime);
	if(slideNum < (len-1)){
		slideNum++;
	}else{
		slideNum = 0;
		allLoaded = 1;
	}
	document.getElementById(loadingImgId).src = slides[slideNum];
	timeOut = 3500;
	checkTime = setTimeout(checkAndLoad, checkOut);
}

function changeImg(){
	//console.log(slideNum+' showing. '+timeOut);
	changeTime = setTimeout(doIt, timeOut);
}

checkAndLoad();

</script>

<?php require(ROOT.'_code/inc/footer.php'); ?>
