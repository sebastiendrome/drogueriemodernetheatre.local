<?php
//require(ROOT.DEMO.'~code/inc/custom_fonts.php');
/*
Generate the output to be written in a css style sheet (_custom_css.css),
create the css file,
output the html markup for linking the css file in a page
*/

// overlay (modals) background, dark or light depending on site bg color
function overlaybg($site_bg_color){ // aabbcc or 002fc1 etc.
	$color = array();
	$total = 0;
	$color['r1'] = substr($site_bg_color, 0, 1);
	$color['r2'] = substr($site_bg_color, 1, 1);
	$color['b1'] = substr($site_bg_color, 2, 1);
	$color['b2'] = substr($site_bg_color, 3, 1);
	$color['g1'] = substr($site_bg_color, 4, 1);
	$color['g2'] = substr($site_bg_color, 5, 1);
	foreach($color as $k => $v){
		if($v=='A'){$v = 10;}
		if($v=='B'){$v = 11;}
		if($v=='C'){$v = 12;}
		if($v=='D'){$v = 13;}
		if($v=='E'){$v = 14;}
		if($v=='F'){$v = 15;}
		
		$total += $v;
	}
	// ( total max: 90, min: 0, middle = 45)
	$opacity = '.6';
	if( $total > 45){		// dark overlay
		if( $total < 55 ){	// make dark overlay more opaque
			$opacity = '.8';
		}
		$overlay_bg = 'rgba(0, 0, 0, '.$opacity.')';
	}else{					// light overlay
		if( $total > 35 ){	// make light overlay more opaque
			$opacity = '.8';
		}
		$overlay_bg = 'rgba(255, 255, 255, '.$opacity.')';
	}
	return $overlay_bg;
}
$overlay_bg = overlaybg($site_bg_color);


/**** START OUTPUT ****/
$output = '@charset "UTF-8";'.PHP_EOL;

// import google fonts if necessary
if( !empty($custom_fonts[$site_font][1]) ){
	$output .= "@import url('".$custom_fonts[$site_font][1]."');".PHP_EOL;
}
if( !empty($header_fonts[$header_font][1]) ){
	$output .= "@import url('".$header_fonts[$header_font][1]."');".PHP_EOL;
}

/* output rgba colors dependent of site bg color */
$output .= '
.overlay, a.adminLess, a.adminLess:hover{background-color:'.$overlay_bg.' !important;}'.PHP_EOL;
$output .= 'div#welcome{color:'.$overlay_bg.';}'.PHP_EOL;

$output .= '

/***** user defined styles *****/
';

/* output user custom styles */
$output .= '

/* site_font */
body, td, th, select, input, button, textarea{
	font-family:'.$custom_fonts[$site_font][0].';
	font-size:'.$font_size.';
	color:#'.$font_color.';
	line-height:1.5; /* do not add pixels or ems! this is relative to font size */ 
}

/* header font */
h1, h2, h3{font-family:'.$header_fonts[$header_font][0].'; font-weight:normal;}


/* site bg_color */
.uniBg, #nav ul li ul{background-color: #'.$site_bg_color.';}

/* item_bg_color */
div.txt, div.html, .textareaContainer{background-color:#'.$item_bg_color.';}

/* links color */
a{color:#'.$link_color.';} 

/* within file linking to sub-section, text should look normal */
a.imgMore{color:#'.$font_color.';}

/* borders */
.divItem img, .divItem div.txt, .divItem div.html,/* .textareaContainer,*/ .wysihtml5-editor img{border:'.$borders.';}
';
if($borders != 'none'){
	if(strstr($borders, 'FFFFFF') && $site_bg_color !== 'FFFFFF'){
		$output .= '.divItem div.txt, divItem div.html{padding:20px;}'.PHP_EOL;
	}elseif( !strstr($borders, 'FFFFFF') ){
		$output .= '.divItem div.txt, divItem div.html{padding:20px;}'.PHP_EOL;
	}
}
if($item_bg_color !== $site_bg_color){
	$output .= '.divItem div.txt, divItem div.html{padding:20px;}'.PHP_EOL;
}

/* sub-nav, only applicable if choosen layout is 'nav-left' */
if( CSS == 'nav-left'){
	if($show_sub_nav == 'always'){
		$output .= 'div.placeHolderTitle, div.backTitle{
		display:none;
	}'.PHP_EOL;
	
	}elseif($show_sub_nav == 'onClick'){
		$output .= '
	#nav ul li:not(.selected) ul{
		display:none;
	}
	div.placeHolderTitle, div.backTitle{
		display:none;
	}'.PHP_EOL;
	
	}elseif($show_sub_nav == 'onHover'){
		$output .= '
	#nav ul li ul{
		height:0;
		overflow:hidden;
	}
	/* commented out because javascript (in nav-left.js) does a better job than the following css (using transition) */
	/*#nav ul li ul{
		max-height:0px; 
		overflow:hidden; 
		-webkit-transition: max-height 0.5s;
		-moz-transition: max-height 0.5s;
		-o-transition: max-height 0.5s;
		-ms-transition: max-height 0.5s;
		transition: max-height 0.5s;
		transition-delay: 0;
	}
	#nav ul li:hover ul{max-height:500px;
	}
	#nav ul li.selected ul{
		height:auto;
		max-height:none;
	}*/
	div.placeHolderTitle, div.backTitle span.back{
		display:none;
	}'.PHP_EOL;
	
	}elseif($show_sub_nav == 'never'){
		$output .= '
	#nav ul li ul{
		height:0;
		overflow:hidden;
	}
	div.backTitle{
		position:fixed;
		z-index:10;
		padding-top:5px !important; padding-bottom:5px !important; padding-right:10px;
		margin-top:5px;
		margin-left:-20px;	
	}
	div.backTitle span.back{
		display:inline-block;
	}
	div.placeHolderTitle{
		display:block;
	}'.PHP_EOL;
	}
}

/* bilingual? */
if($bilingual == 'no'){
	$output .= '.l2{display:none;}'.PHP_EOL;
}

/**** END OUTPUT ****/


if($fp = fopen(ROOT.CONTENT.'_custom_css.css', 'w')){
	fwrite($fp, $output);
	fclose($fp);
}

if( !isset($css_version) ){
	$css_version = $version;
}
echo '<link href="/'.CONTENT.'_custom_css.css?v='.$css_version.'" rel="stylesheet" type="text/css">'.PHP_EOL;

?>