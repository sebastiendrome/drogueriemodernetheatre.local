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

$slides_dir = '_uploads/home-slides/';
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
//$slides_data_output = '["'.implode('","', $slides).'"]';

require(ROOT.'_code/inc/doctype.php');
?>

<script type="text/javascript">
var loadingFlags = [1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1,1];
function setLoadingFlags(i){loadingFlags[i]=0;}
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

<div id="slideContainer" data-slides='<?php echo $slides_data_output; ?>'>&nbsp;</div>


<div id="hidden">
<?php
$i = 0;
foreach($slides as $s){
	echo '<img src="'.$s.'" id="i'.$i.'" onload="setLoadingFlags('.$i.')">';
	$i++;
}
?>
</div>


<script type="text/javascript">

function loadSomeMore(){
	loadingFlags[0]=1;
	document.getElementById("i0").src="imgX.gif";
	loadingFlags[1]=1;
	document.getElementById("i1").src="imgY.gif";
	window.setTimeout(processImages,50);
}

function processImages(slideNum){
	if(loadingFlags[0] || loadingFlags[1]){
		// check often until loaded
		window.setTimeout(processImages,50);
	return;
	}
	// both found - do your work
	$("#slideContainer").css("background-image", 'url("'+slides[slideNum]+'")').show(0);
	slideNum++;
	loadSomeMore();
}

/* By Eharry.me (https://gist.github.com/Ema4rl/b8ef90be99205ddada5ef2cd6e632ebe) */
!function($){
	var $slideContainer=$("#slideContainer"),
		count=0,
		slides=$slideContainer.data("slides"),
		len=slides.length,
		n=function(){
			if(count>=len){count=0}
			$slideContainer.css("background-image", 'url("'+slides[count]+'")').show(0, function(){
				setTimeout(n, 5000);
			});
			count++;
		};
	n()
}(jQuery);
</script>

<?php require(ROOT.'_code/inc/footer.php'); ?>
