<?php
/*
Generate the output to be written in a css style sheet (_custom_css.css),
create the css file,
output the html markup for linking the css file in a page
*/
/*
* missing in the following: 
* identify google fonts vs web safe fonts
* know which font variants to load (:400,400i,700 or :700 or...)
* custom_fonts array should be like:
* $custom_fonts = array('Lucida Sans' => array(alt => '"Lucida Grande", sans-serif', size => '13px', load => '400,400i,700')
* where if isset($custom_fonts['Lucida Sans'][load]) identifies the font as a google font
* and if isset($custom_fonts['Lucida Sans'][size]) identifies the font as a site font (not header font)
*/
/*
$used = array();
foreach($custom_fonts as $k=>$v){
	if( strstr($site_font, $k) ){
		echo '<link href="https://fonts.googleapis.com/css?family='.urlencode($k).':400,400i,700" rel="stylesheet">';
		$used[$k] = true;
	}

}
foreach($header_fonts as $k=>$v){
	if( strstr($header_font, $k) && !isset($used[$k]) ){
		echo '<link href="https://fonts.googleapis.com/css?family='.urlencode($k).':400,400i,700" rel="stylesheet">';
	}
}
*/
// import google font if necessary
/*
preg_match('//', $site_font, $small_font_match);
if(isset()){

}
*/

$output = '@charset "UTF-8";'.PHP_EOL;

if( strstr($site_font, '"Open Sans"') || strstr($header_font, '"Open Sans"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700');".PHP_EOL;
}
if( strstr($site_font, '"Space Mono"') || strstr($header_font, '"Space Mono"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Space+Mono:400,400i,700');".PHP_EOL;
}
if( strstr($site_font, '"PT Sans"') || strstr($header_font, '"PT Sans"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700');".PHP_EOL;
}
if( strstr($site_font, 'Arvo') || strstr($header_font, 'Arvo') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=PT+Sans:400,400i,700');".PHP_EOL;
}
if( strstr($site_font, 'Vollkorn') || strstr($header_font, 'Vollkorn') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Vollkorn:700');".PHP_EOL;
}
if( strstr($site_font, '"Archivo Narrow"') || strstr($header_font, '"Archivo Narrow"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Archivo+Narrow:700');".PHP_EOL;
}
if( strstr($site_font, '"Old Standard TT"') || strstr($header_font, '"Old Standard TT"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Old+Standard+TT:400,400i,700');".PHP_EOL;
}
if( strstr($site_font, '"EB Garamond"') || strstr($header_font, '"EB Garamond"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=EB+Garamond:700');".PHP_EOL;
}
if( strstr($site_font, '"Cormorant Garamond"') || strstr($header_font, '"Cormorant Garamond"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Cormorant+Garamond:700');".PHP_EOL;
}
if( strstr($site_font, 'Trirong') || strstr($header_font, 'Trirong') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Trirong:500');".PHP_EOL;
}
if( strstr($site_font, 'Cutive') || strstr($header_font, 'Cutive') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Cutive');".PHP_EOL;
}
if( strstr($site_font, '"Slabo 27px"') || strstr($header_font, '"Slabo 27px"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Slabo+27px');".PHP_EOL;
}
if( strstr($site_font, '"Ubuntu Mono"') || strstr($header_font, '"Ubuntu Mono"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Ubuntu+Mono');".PHP_EOL;
}
if( strstr($site_font, '"Ubuntu Condensed"')  || strstr($header_font, '"Ubuntu Condensed"') ){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Ubuntu+Condensed');".PHP_EOL;
}
if( strstr($site_font, '"Ubuntu"')  || strstr($header_font, '"Ubuntu"')){
	$output .= "@import url('https://fonts.googleapis.com/css?family=Ubuntu:400,700');".PHP_EOL;
}


$output .= '
/***** user defined styles *****/

/* site bg_color */
body, #nav, #nav ul li ul, .backTitle, div.txt, div.html, .modal, .textareaContainer{background-color: #'.$site_bg_color.';}

/* item_bg_color */
div.txt, div.html, .textareaContainer, .wysihtml5-editor{background-color:#'.$item_bg_color.';}

/* site_font */
body, td, th, select, input, button, textarea{
	font:'.$site_font.';
	color:#'.$font_color.';
	line-height:1.4; /* do not add pixels or ems! this is relative to font size */ 
}

/* font_size */
/*body, html{font-size:'.$font_size.';}*/

/* header font */
h1, h2, h3{font-family:'.$header_font.';}

/* links color */
a{color:#'.$link_color.';} 

/* within file linking to sub-section, text should look normal */
a.imgMore{color:#'.$font_color.';}

/* borders */
.divItem img, .divItem div.txt, .divItem div.html,/* .textareaContainer,*/ .wysihtml5-editor img{border:'.$borders.';}
';
if($borders != 'none'){
	if(strstr($borders, 'FFFFFF') && $site_bg_color != 'FFFFFF'){
		$output .= '.divItem div.txt, divItem div.html{padding:20px;}'.PHP_EOL;
	}elseif( !strstr($borders, 'FFFFFF') ){
		$output .= '.divItem div.txt, divItem div.html{padding:20px;}'.PHP_EOL;
	}
}/*else{
	$output .= '.divItem div.txt, .divItem div.html{border-bottom:1px solid #ddd;}'.PHP_EOL;
}*/

/* sub-nav */
if($show_sub_nav == 'yes'){
	$output .= '#nav ul li:hover ul{display:block;}'.PHP_EOL;
}

/* bilingual? */
if($bilingual == 'no'){
	$output .= '.l2{display:none;}'.PHP_EOL;
}

if($fp = fopen(ROOT.CONTENT.'_custom_css.css', 'w')){
	fwrite($fp, $output);
	fclose($fp);
}

echo '<link href="/'.CONTENT.'_custom_css.css" rel="stylesheet" type="text/css">'.PHP_EOL;

?>