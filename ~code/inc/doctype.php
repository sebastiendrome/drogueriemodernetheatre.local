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

if( isset($home_image) && !isset($social_image) ){
	$social_image = PROTOCOL.SITE.CONTENT.UPLOADS.SIZE.'/'.$home_image;
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

<meta property="og:author"        content="Sébastien Brault, https://killyourmaster.net">
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
?>
<title><?php echo $title; ?></title>

<!-- javascript -->
<?php require(ROOT.DEMO.'~code/inc/js.php'); ?>
<?php
// custom js
if( file_exists(ROOT.DEMO.'~custom/custom.js') ){
	echo '<!-- site custom js -->
	<script type="text/javascript" src="/'.DEMO.'~custom/custom.js?v='.$version.'"></script>'.PHP_EOL;
}
?>

<!-- user custom css -->
<?php require(ROOT.DEMO.'~code/custom.css.php'); ?>

<!-- generic css -->
<link href="/<?php echo DEMO; ?>~code/css/common.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">

<!-- layout css -->
<link href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/layout.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">

<style type="text/css">
/* limit container width depending on screen size and resulting SIZE var defined in first_include. */
#content{max-width:<?php echo $_POST['sizes'][substr(SIZE,1)]['width']; ?>px;}
</style>

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 980px)" href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/max-980px.css?v=<?php echo $version; ?>">
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/<?php echo $css_var; ?>/max-720px.css?v=<?php echo $version; ?>">

<!-- site custom css -->
<?php 
if( file_exists(ROOT.DEMO.'~custom/custom.css') ){
	echo '<link href="/'.DEMO.'~custom/custom.css?v='.$version.'" rel="stylesheet" type="text/css">'.PHP_EOL;
}
if( file_exists(ROOT.DEMO.'~custom/max-980px.css') ){
	echo '<link rel="stylesheet" media="(max-width: 980px)" href="/'.DEMO.'~custom/max-980px.css?v='.$version.'" type="text/css">'.PHP_EOL;
}
if( file_exists(ROOT.DEMO.'~custom/max-720px.css') ){
	echo '<link rel="stylesheet" media="(max-width: 720px)" href="/'.DEMO.'~custom/max-720px.css?v='.$version.'" type="text/css">'.PHP_EOL;
}
?>


</head>

<body class="uniBg">
