<?php
/**
 * required to be defined on top of each page individualy:
 * $title, $description
 * 
 * optional: 
 * $social_url, $social_image
 */

if( !isset($social_url) || empty($social_url) ){
	$social_url = PROTOCOL.SITE.substr($_SERVER['REQUEST_URI'],1); // http(s)://example.com/path/to/dir/
}
// social image (for meta property="og:image") is the background image in home page...
$bg_path = ROOT.CONTENT.UPLOADS.SIZE.'/';

if( isset($home_image) && !isset($social_image) ){
	$social_image = PROTOCOL.SITE.CONTENT.UPLOADS.SIZE.$home_image;
}

if( isset($page) && $page == 'home' && isset($home_image) ){
	$body_bg = true;
	$s_bg = CONTENT.UPLOADS.'_S/'.$home_image;
	$m_bg = CONTENT.UPLOADS.'_M/'.$home_image;
	$l_bg = CONTENT.UPLOADS.'_L/'.$home_image;
	$xl_bg = CONTENT.UPLOADS.'_XL/'.$home_image;
}
?>
<!DOCTYPE HTML>
<html lang="<?php echo SEO_LANG;?>">
<head>
<!-- portfolio version <?php echo $version; ?> -->
<?php 
// include google analytics js code if it exists in root directory
$gtag = ROOT.DEMO.'gtag.js';
if( file_exists($gtag) ){
	include($gtag);
}
?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="description" content="<?php echo $description; ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="author" content="Sébastien Brault">

<meta property="og:author"        content="Sébastien Brault">
<meta property="og:url"           content="<?php echo $social_url; ?>">
<meta property="og:type"          content="website">
<meta property="og:title"         content="<?php echo $title; ?>">
<meta property="og:description"   content="<?php echo $description; ?>">
<?php 
if( isset($social_image) && !empty($social_image) ){ ?>
<meta property="og:image"         content="<?php echo $social_image; ?>">
<?php 
}
// allows for testing nav-left or nav-right from admin preferences ($css_var and NOT $css, not to interfere with preferences.php nav option change which loads doctype after setting new $css value)
$css_var = CSS;
if( isset($_GET['nav']) ){
	if($_GET['nav'] == 'left'){
		$css_var = 'nav-left';
	}elseif($_GET['nav'] == 'top'){
		$css_var = 'nav-top';
	}
	//$_SESSION['css'] = $css_var;
}
/*
if( isset($_SESSION['css']) && !empty($_SESSION['css']) ){
	$css_var = $_SESSION['css'];
}
*/
// background image position account for header if nav-top
if( $css_var == 'nav-top'){
	$bg_position = 'background-position:50% 35%; ';
}else{
	$bg_position = 'background-position:50% 50%; ';
}
?>
<title><?php echo $title; ?></title>

<!-- javascript -->
<?php require(ROOT.DEMO.'~code/inc/js.php'); ?>

<!-- user custom css -->
<?php require(ROOT.DEMO.'~code/custom.css.php'); ?>

<!-- generic css -->
<link href="/<?php echo DEMO; ?>~code/css/common.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">

<!-- layout css -->
<link href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/layout.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">

<style type="text/css">
/* limit container width depending on screen size and resulting SIZE var defined in first_include. */
#content{max-width:<?php echo $_POST['sizes'][substr(SIZE,1)]['width']; ?>px;}
<?php if( isset($body_bg) ){ ?>

body{background-repeat:no-repeat; <?php echo $bg_position; ?>background-size:cover;}

/* For width smaller than 320px: */
body{background-image:url(/<?php echo $l_bg; ?>);}

@media only screen and (min-width: 320px) {
	body{background-image:url(/<?php echo $xl_bg; ?>);}
}

<?php } ?>
</style>

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 980px)" href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/max-980px.css?v=<?php echo $version; ?>">
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/max-720px.css?v=<?php echo $version; ?>">


</head>

<body class="uniBg">
