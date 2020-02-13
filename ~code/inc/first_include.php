<?php
/******** TO DO ********
 * bilingual articles
 * make directory agnostic = allow for Demo on sub-folder
 * upload multiple files
 * 	--> https://tutorialzine.com/2013/05/mini-ajax-file-upload-form
 * edit images: rotate, crop, apply css filters:
 * 	-_> save filters to canvas: https://stackoverflow.com/questions/39854891/how-to-save-an-image-with-css-filter-applied#39859386
 * 	--> css filters: https://www.sitepoint.com/build-simple-image-editor-with-css-filters-jquery/
 * Update menu: use line number systematically! (instead of complicated parents/child search)
 * all user actions results displayed in #done div via ajax
 */
session_start();
ini_set("auto_detect_line_endings", true);

// set version, to load fresh css and js
$version = 5;

// Protocol: http vs https
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
define("PROTOCOL", $protocol);

// initialize site 
define("SITE", $_SERVER['HTTP_HOST'].'/');

// document root (beware of inconsistent trailing slash depending on environment, hence the use of realpth)
define("ROOT", realpath($_SERVER['DOCUMENT_ROOT']).'/');

// directory agnostic DEMO var extracts whatever dir is between root and ~code
$demo = preg_replace('/~code\/.*$/', '', str_replace(ROOT, '', __FILE__));
define("DEMO", $demo);

// reference to site author...
define("AUTHOR_REF", '<a href="https://killyourmaster.net" target="_blank" title="build your website fast and easy.">site powered by KYM</a>');
define("AUTHOR_EMAIL", 'kym.killyourmaster@gmail.com');
// content directory (which contains all user files)
define("CONTENT", DEMO.'~content/');
// upload directory (which contains all user uploaded files)
define("UPLOADS", '~uploads/');

// php root and error reporting, local vs. remote
if( strstr(SITE,'.local') ){ 					// local server
	ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	define( 'DISPLAY_DEBUG', TRUE );
	define( 'LOG_ERRORS', TRUE );
}else{ 											// remote server
	ini_set('display_errors', 0);
	define("SEND_ERRORS_TO", AUTHOR_EMAIL);
	define( 'DISPLAY_DEBUG', FALSE );
	define( 'LOG_ERRORS', TRUE );
}

// create error log file if it does not exist. If it can't be created. redirect to setup.php for instructions
if( !file_exists(ROOT.CONTENT.'hGtDjkpPWSXk.php') ){
	if( !$fp = fopen(ROOT.CONTENT.'hGtDjkpPWSXk.php', 'w') ){
		header("Location: /".DEMO."~code/setup.php");
		exit();
	}
}

// error handler
require(ROOT.DEMO.'~code/errors.php');

// create uploads dir if it does not exist
if( !file_exists(ROOT.CONTENT.UPLOADS) ){
	mkdir( ROOT.CONTENT.UPLOADS );
	mkdir(ROOT.CONTENT.UPLOADS.'_S');
	mkdir(ROOT.CONTENT.UPLOADS.'_M');
	mkdir(ROOT.CONTENT.UPLOADS.'_L');
	mkdir(ROOT.CONTENT.UPLOADS.'_XL');
	mkdir(ROOT.CONTENT.UPLOADS.'de');
	mkdir(ROOT.CONTENT.UPLOADS.'en');
}

// CREATE BACKUP dir if it does not exists
if( !file_exists(ROOT.CONTENT.'~backup') ){
	mkdir( ROOT.CONTENT.'~backup' );
}

// include or create customizable elements
if( !file_exists(ROOT.CONTENT.'custom.php') ){
	if( file_exists(ROOT.CONTENT.'user_custom.php') ){
		$t = 123456789;
	}
	if( !$fp = fopen(ROOT.CONTENT.'custom.php', 'w') ){
		log_custom_error('Could not open '.ROOT.CONTENT.'custom.php', '');
	}else{
		if( !isset($t) ){
			$t = time();
		}
		fwrite($fp, '<?php $cust = "'.$t.'";');
		fclose($fp);
		log_custom_error('Created '.ROOT.CONTENT.'custom.php with time='.$t, '');
	}
}else{
	$get_custom = true;
}

// if user_custom.php does not exists, copy the specimen from root
if( !file_exists(ROOT.CONTENT.'user_custom.php') ){
	copy(ROOT.DEMO.'~code/templates/user_custom.php', ROOT.CONTENT.'user_custom.php');
}

// if styles_custom.php does not exists, copy the specimen from root
if( !file_exists(ROOT.CONTENT.'styles_custom.php') ){
	copy(ROOT.DEMO.'~code/templates/default_styles.php', ROOT.CONTENT.'styles_custom.php');
}

// menu file (used as site structure)
define("MENU_FILE", ROOT.CONTENT.'menu.txt');
// create menu file if it does not exist
if( !file_exists(MENU_FILE) ){
	if( !$fp = fopen(MENU_FILE, 'w') ){
		echo '<p style="color:red;">Error: Could not create menu file!</p>';
	}else{
		fclose($fp);
	}
}

// reference paths
define("CONTEXT_PATH", str_replace( ROOT.DEMO, '', getcwd() ) );
define("SECTION", basename(CONTEXT_PATH) );

// reserved names
define("SYSTEM_NAMES", '/^_?(~backup|~uploads)$/');

// default languages (more can be added by user, array will be extended in user_custom.php)
$languages = array();
$languages['english'] = array('seo'=>'en', 'more'=>'more', 'back'=>'back');
$languages['français'] = array('seo'=>'fr', 'more'=>'voir plus', 'back'=>'retour');
$languages['deutsch'] = array('seo'=>'de', 'more'=>'mehr', 'back'=>'zurück');
$languages['español'] = array('seo'=>'es', 'more'=>'más', 'back'=>'volver');

// custom fonts array, used by custom.css.php to generate custom css
require(ROOT.DEMO.'~code/inc/custom_fonts.php');
// require custom parameters set by user (first_lang and second_lang, css style, username, admin creds...)
require(ROOT.CONTENT.'user_custom.php');
// require custom styles set by user
require(ROOT.CONTENT.'styles_custom.php');

// nav layout
define("CSS", $css);

// $show_sub_nav, set in requiered file above, is used within functions...
define("SHOW_SUB_NAV", $show_sub_nav);

// set allowed tags for strip_tags function, used for validating user txt input
define("ALLOWED_TAGS", '<b><strong><br><u><i><a><h1><h2><h3><span><div><img>');

// language dependent constants (for 'more' and 'back' links)
define("BILINGUAL", $bilingual);
define("FIRST_LANG", $first_lang);
define("SECOND_LANG", $second_lang);
// language directory that will let the site know it should switch to second language.
// note: if this value is changed, it must also be changed in .htaccess!  
define("LANG_DIR", 'lang-2/');


if( strstr($_SERVER['REQUEST_URI'], '/'.LANG_DIR) || ( isset($_GET['lang']) && $_GET['lang']=='de') ){
	$lang = 'de'; // second language
	define("LANG_LINK", LANG_DIR);
}else{ // default (first language)
	$lang = "en"; 
	define("LANG_LINK", '');
}
define("LANG", $lang);

if(LANG == 'en'){ // en means $first_lang (not "english")
	$user = $user_en;
	if( !array_key_exists($first_lang, $languages) ){ // if first lang is not part of the lang array
		$default_lang = 'english';
	}else{
		$default_lang = $first_lang;
	}
	if( !array_key_exists($second_lang, $languages) ){ // if second lang is not part of the lang array
		$other_lang = 'deutsch';
	}else{
		$other_lang = $second_lang;
	}
	
}elseif(LANG == 'de'){ // de means $second_lang (not "Deutsch")
	$user = $user_de;
	if( !array_key_exists($second_lang, $languages) ){ // if first lang is not part of the lang array
		$default_lang = 'Deustch';
	}else{
		$default_lang = $second_lang;
	}
	if( !array_key_exists($first_lang, $languages) ){ // if second lang is not part of the lang array
		$other_lang = 'english';
	}else{
		$other_lang = $first_lang;
	}
}

define("USER", $user);
define("MORE", $languages[$default_lang]['more']);
define("BACK", $languages[$default_lang]['back']);
define("SEO_LANG", $languages[$default_lang]['seo']);
define("OTHER_LANG", $languages[$other_lang]['seo']);

// FILE TYPES
$types = array();
// ALL
$types['supported_types'] = '/^\.(jpe?g?|png|gif|s?html?|txt|mp3|m4a|oga?g?|wav|mp4|m4v|webm|ogv|pdf|svg|docx?|msword|odt|gal|emb)$/i';
// TEXT
$types['text_types'] = '/^\.(s?html?|txt)$/i';
// audio
$types['audio_types'] = '/^\.(mp3|m4a|oga?g?|wav)$/i';
// video
$types['video_types'] = '/^\.(mp4|m4v|webm|ogv)$/i';
// resizable
$types['resizable_types'] = '/^\.(jpe?g?|png|gif)$/i';
// only available for download
$types['download'] = '/^\.(pdf|docx?|msword|odt)$/i';
// register $types as a $_POST var, so it is accessible within functions scope (like a constant).
$_POST['types'] = $types;

// FILE SIZES:
$sizes = array();
$sizes['L'] = array("width"=>800, "height"=>667);
$sizes['M'] = array("width"=>650, "height"=>542);
$sizes['S'] = array("width"=>300, "height"=>250);
// register $sizes as a $_POST var, so it is accessible within functions scope.
$_POST['sizes'] = $sizes;

// image size 
$size = "_M";
if(CSS == 'nav-top'){
	$large_w_limit = 1200;
}else{
	$large_w_limit = 1370;
}
if(isset($_COOKIE['wW'])){
	if($_COOKIE['wW'] > $large_w_limit ){
		$size = "_L";
	}elseif($_COOKIE['wW'] < 340){
		$size = "_S";
	}
}
define("SIZE", $size);
define("L_W_LIMIT", $large_w_limit);

// define home page background image name
define("BG", 'home_bg_6Fg7M-do8');
// check if it exists in any format, if yes, set $home_image
if( file_exists( ROOT.CONTENT.UPLOADS.'_L/'.BG.'.jpg') ){
	$home_image = BG.'.jpg';
}elseif( file_exists( ROOT.CONTENT.UPLOADS.'_L/'.BG.'.gif') ){
	$home_image = BG.'.gif';
}elseif( file_exists( ROOT.CONTENT.UPLOADS.'_L/'.BG.'.png') ){
	$home_image = BG.'.png';
}

// require common functions
require(ROOT.DEMO.'~code/inc/functions.php');

if(isset($get_custom) && $get_custom == true){
	get_custom();
}


// max upload size must be after including functions above, for return_bytes()
$max_upload_size = ini_get('upload_max_filesize');
$max_upload_bytes = return_bytes($max_upload_size);
define("MAX_UPLOAD_SIZE", $max_upload_size);
define("MAX_UPLOAD_BYTES", $max_upload_bytes);

// require admin specific files
if( strstr($_SERVER['REQUEST_URI'], '/admin/') ){
	$lang_file = ROOT.DEMO.'~code/admin/ui_lang/'.$default_lang.'.php';
	if( file_exists($lang_file) ){
		require($lang_file);
	}else{
		require(ROOT.DEMO.'~code/admin/ui_lang/english.php');
	}
	require(ROOT.DEMO.'~code/admin/not_logged_in.php');
	require(ROOT.DEMO.'~code/admin/admin_functions.php');
	// and make a backup of default styles
	if( !file_exists(ROOT.CONTENT.'~backup/default_styles.php') ){
		$styles_setup_dir = ROOT.DEMO.'~code/templates/styles';
		$styles_backup_dir = ROOT.CONTENT.'~backup';
		$styles = scan_dir($styles_setup_dir);
		foreach($styles as $st){
			//echo $styles_setup_dir.'/'.$st.'<br>';
			copy($styles_setup_dir.'/'.$st, $styles_backup_dir.'/'.$st);
		}
	}
}


