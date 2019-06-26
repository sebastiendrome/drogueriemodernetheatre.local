<?php
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');
require(ROOT.'_code/admin/admin_functions.php');

$title = 'ADMIN : '.$ui['preferences'];
$description = $message = $error = '';
$page = 'admin';
$back_link = 'manage_structure.php';

// arrays of custom fonts (custom_fonts for small font and header_fonts for headers)
/* see also _code/custom.css.php, where conditionals load google fonts if any of the following are used */
$custom_fonts = array(
	'Courier New'=>'14px "Courier New", Courier, monospace',
	'Lucida Console'=>'12px "Lucida Console", "Lucida Sans Typewriter", Monaco, monospace',
	/* google font */
	'Space Mono'=>'13px "Space Mono", monospace', 
	'Lucida Sans Unicode'=>'13px "Lucida Sans Unicode", "Lucida Grande", sans-serif',
	/* google font */
	'Open Sans'=>'13px "Open Sans", sans-serif',
	/* google font */
	'PT Sans'=>'14px "PT Sans", sans-serif',
	'Tahoma'=>'13px Tahoma, Geneva, sans-serif',
	'Trebuchet MS'=>'13px "Trebuchet MS", Helvetica, sans-serif',
	'Verdana'=>'12px Verdana, Geneva, sans-serif',
	'Arial'=>'14px Arial, Helvetica, sans-serif',
	'Arial Narrow'=>'15px "Arial Narrow", Arial, sans-serif',
	/* google font */
	'Ubuntu'=>'14px "Ubuntu", Palatino, sans-serif',
	/* google font */
	'Ubuntu Condensed'=>'16px "Ubuntu Condensed", "Arial Narrow", sans-serif',
	/* google font */
	'Ubuntu Mono'=>'14px "Ubuntu Mono", monospace',
	'Helvetica Neue'=>'14px "Helvetica Neue", Helvetica, Arial, sans-serif',
	'Times New Roman'=>'14px "Times New Roman", Times, serif',
	'Georgia'=>'14px Georgia, serif',
	'Palatino Linotype'=>'14px "Palatino Linotype", "Book Antiqua", Palatino, serif',
	/* google font */
	'EB Garamond'=>'17px "EB Garamond", serif',
	/* google font */
	'Old Standard TT'=>'16px "Old Standard TT", serif'
);

$header_fonts = array(
	/* google font */
	'Cormorant Garamond'=>'"Cormorant Garamond", serif',
	/* google font */
	'Trirong'=>'Trirong, serif',
	/* google font */
	'Slabo 27px'=>'"Slabo 27px", serif',
	/* google font */
	'Open Sans'=>'"Open Sans", sans-serif',
	/* google font */
	'Archivo Narrow'=>'"Archivo Narrow", "Arial Narrow", sans-serif',
	/* google font */
	'Ubuntu Condensed'=>'"Ubuntu Condensed", "Arial Narrow", sans-serif',
	/* google font */
	'Cutive'=>'Cutive, serif',
	/* google font */
	'Space Mono'=>'"Space Mono", monospace', 
	'Courier New'=>'"Courier New", Courier, monospace',
	'Arial'=>'Arial, Helvetica, sans-serif',
	'Arial Narrow'=>'"Arial Narrow", Arial, sans-serif',
	'Helvetica Neue'=>'"Helvetica Neue", Helvetica, Arial, sans-serif',
	'Times New Roman'=>'"Times New Roman", Times, serif',
	'Georgia'=>'Georgia, serif',
	'Palatino Linotype'=>'"Palatino Linotype", "Book Antiqua", Palatino, serif',
	/* google font */
	'EB Garamond'=>'"EB Garamond", serif',
	/* google font */
	'Old Standard TT'=>'"Old Standard TT", serif',
	/* google font */
	'Vollkorn'=>'Vollkorn, serif',
	/* google font */
	'Arvo'=>'Arvo, serif'
);

// arrays of custom fonts (custom_fonts for small font and header_fonts for headers)
/* see also _code/custom.css.php, where conditionals load google fonts if any of the following are used */
/*
$new_custom_fonts = array(
	'small' => array(
		//'Courier New'=>'14px "Courier New", Courier, monospace',
		'Courier New'=> array(
			'alt' => 'Courier, monospace',
			'size' => '14px'
		), 
		//'Lucida Console'=>'12px "Lucida Console", "Lucida Sans Typewriter", Monaco, monospace',
		'Lucida Console'=> array(
			'alt' => '"Lucida Sans Typewriter", Monaco, monospace',
			'size' => '12px'
		), 
		// font-family: 'Space Mono', monospace;
		'Space Mono'=> array(
			'alt' => 'monospace',
			'size' => '13px'
		), 
		//'Lucida Sans Unicode'=>'13px "Lucida Sans Unicode", "Lucida Grande", sans-serif',
		'Lucida Sans Unicode'=> array(
			'alt' => '"Lucida Grande", sans-serif',
			'size' => '13px'
		), 
		//'Open Sans'=>'"Open Sans", sans-serif',
		'Open Sans' =>  array(
			'alt' => 'sans-serif',
			'size' => '13px',
			'load' => '400,400i,700'
		), 
		//'PT Sans'=>'14px "PT Sans", sans-serif',
		'PT Sans' => array(
			'alt' => 'sans-serif',
			'size' => '14px',
			'load' => '400,400i,700'
		), 
		//'Arial'=>'14px Arial, Helvetica, sans-serif',
		'Arial' => array(
			'alt' => 'Helvetica, sans-serif',
			'size' => '14px'
		), 
		//'Tahoma'=>'13px Tahoma, Geneva, sans-serif',
		'Tahoma' => array(
			'alt' => 'Geneva, sans-serif',
			'size' => '13px'
		), 
		//'Trebuchet MS'=>'13px "Trebuchet MS", Helvetica, sans-serif',
		'Trebuchet MS' => array(
			'alt' => 'Helvetica, sans-serif',
			'size' => '13px'
		), 
		//'Verdana'=>'12px Verdana, Geneva, sans-serif',
		'Verdana' => array(
			'alt' => 'Geneva, sans-serif',
			'size' => '12px'
		), 
		//'Arial Narrow'=>'15px "Arial Narrow", Arial, sans-serif',
		'Arial Narrow' => array(
			'alt' => 'Arial, sans-serif',
			'size' => '15px'
		), 
		//'Helvetica Neue'=>'14px "Helvetica Neue", Helvetica, Arial, sans-serif',
		'Helvetica Neue' => array(
			'alt' => 'Helvetica, Arial, sans-serif',
			'size' => '14px'
		), 
		//'Times New Roman'=>'14px "Times New Roman", Times, serif',
		'Times New Roman' => array(
			'alt' => 'Times, serif',
			'size' => '14px'
		), 
		//'Georgia'=>'14px Georgia, serif',
		'Georgia' => array(
			'alt' => 'serif',
			'size' => '14px'
		), 
		//'Palatino Linotype'=>'14px "Palatino Linotype", "Book Antiqua", Palatino, serif',
		'Palatino Linotype' => array(
			'alt' => '"Book Antiqua", Palatino, serif',
			'size' => '14px'
		), 
		//'EB Garamond'=>'17px "EB Garamond", serif',
		'EB Garamond' => array(
			'alt' => 'serif',
			'size' => '17px',
			'load' => '700'
		), 
		//'Old Standard TT'=>'16px "Old Standard TT", serif'
		'Old Standard TT' => array(
			'alt' => 'serif',
			'size' => '16px',
			'load' => '400,400i,700'
		)
	),
	
	'header' => array(
		//'Courier New'=>'"Courier New", Courier, monospace', HEADER FONT
		'Courier New'=> array(
			'alt' => 'Courier, monospace'
		), 
		// font-family: 'Space Mono', monospace;
		'Space Mono'=> array(
			'alt' => 'monospace',
		), 
		//'Open Sans'=>'"Open Sans", sans-serif', HEADER FONT
		'Open Sans' =>  array(
			'alt' => 'sans-serif',
			'load' => '700'
		), 
		// 'Cormorant Garamond'=>'"Cormorant Garamond", serif' HEADER FONT
		'Cormorant Garamond' => array(
			'alt' => 'serif',
			'load' => '700'
		),
		//'Trirong'=>'Trirong, serif', HEADER FONT
		'Trirong' => array(
			'alt' => 'serif',
			'load' => '500'
		),
		//'Cutive'=>'Cutive, serif', HEADER FONT
		'Cutive' => array(
			'alt' => 'serif',
			'load' => '400'
		), 
		//'Slabo 27px'=>'"Slabo 27px", serif', HEADER FONT
		'Slabo 27px' => array(
			'alt' => 'serif',
			'load' => '400'
		), 
		//'Archivo Narrow'=>'"Archivo Narrow", "Arial Narrow", sans-serif', HEADER FONT
		'Archivo Narrow' => array(
			'alt' => '"Arial Narrow", sans-serif',
			'load' => '700'
		), 
		//'Arial'=>'Arial, Helvetica, sans-serif', HEADER FONT
		'Arial' => array(
			'alt' => 'Helvetica, sans-serif'
		), 
		//'Arial Narrow'=>'"Arial Narrow", Arial, sans-serif', HEADER FONT
		'Arial Narrow' => array(
			'alt' => 'Arial, sans-serif'
		), 
		// 'Helvetica Neue'=>'"Helvetica Neue", Helvetica, Arial, sans-serif', HEADER FONT
		'Helvetica Neue' => array(
			'alt' => 'Helvetica, Arial, sans-serif'
		), 
		//'Times New Roman'=>'"Times New Roman", Times, serif', HEADER FONT
		'Times New Roman' => array(
			'alt' => 'Times, serif'
		), 
		//'Georgia'=>'Georgia, serif', HEADER FONT
		'Georgia' => array(
			'alt' => 'serif'
		), 
		//'Palatino Linotype'=>'"Palatino Linotype", "Book Antiqua", Palatino, serif', HEADER FONT
		'Palatino Linotype' => array(
			'alt' => '"Book Antiqua", Palatino, serif'
		), 
		//'EB Garamond'=>'"EB Garamond", serif', HEADER FONT
		'EB Garamond' => array(
			'alt' => 'serif',
			'load' => '700'
		), 
		//'Old Standard TT'=>'"Old Standard TT", serif', HEADER FONT
		'Old Standard TT' => array(
			'alt' => 'serif',
			'load' => '700'
		), 
		//'Vollkorn'=>'Vollkorn, serif', HEADER FONT
		'Vollkorn' => array(
			'alt' => 'serif',
			'load' => '700'
		), 
		//'Arvo'=>'Arvo, serif' HEADER FONT
		'Arvo' => array(
			'alt' => 'serif',
			'load' => '700'
		)
	)
);
*/

// email verification (check that verification code, new email and old email match)
/*
if(isset($_GET['verify'])){
	if( $_GET['verify'] == $email_verification_code.base64_encode($tmp_email).base64_encode($email) ){
		$content = file_get_contents(ROOT.CONTENT.'user_custom.php');
		if( preg_match('/\$email = \''.$email.'\';/', $content, $match) ){
			// set new email to write in user_custom.php content
			$content = str_replace($match[0], '$email = \''.$tmp_email.'\';', $content);
			// set new email_verification_code
			$rand = rand_string();
			$new_verification_code = $rand.rand(111,9999);
			$content = str_replace('$email_verification_code = \''.$email_verification_code.'\';', '$email_verification_code = \''.$new_verification_code.'\';', $content);

			// write content to file
			if( $fp = fopen(ROOT.CONTENT.'user_custom.php', 'w') ){
				fwrite($fp, $content);
				fclose($fp);
				$message .= '<p class="success">Thank you, your new email '.$tmp_email.' has been verified and saved.</p>';
			}else{
				$message .= '<p class="error">Could not open preferences file.</p>';
			}
			//reload the page without the verify code query
			header("Location: ?message=".urlencode($message));
			exit;
		
		}else{
			header("Location: ".PROTOCOL.SITE);
			exit;
		}
	
	}else{
		header("Location: /_code/admin/preferences.php");
		exit;
	}
}
*/




// Form submit validation
if(isset($_POST['submitSitePrefs']) || isset($_POST['submitUserPrefs'])){
	
	// get content of user_custom.php
	$content = file_get_contents(ROOT.CONTENT.'user_custom.php');
	// initialize new_vals (from form $_POST)
	$new_vals = array();
	foreach($_POST as $k => $v){
		if($k != 'types' && $k != 'sizes' && $k != 'submitSitePrefs' && $k != 'submitUserPrefs'){
			//echo $k.' = '.$v.'<br>';
			$new_vals[$k] = addslashes($v);
		}
	}
	
	//print_r($new_vals);
	//exit;
	// unset username and password, to prevent saving empty values. 
	unset($new_vals['username'], $new_vals['password']);

	// make sure blank user name and password are not saved
	if( isset($new_vals['admin_username']) && empty($new_vals['admin_username']) ){
		unset($new_vals['admin_username']);
	}elseif( isset($new_vals['admin_username']) ){
		// validate username
		if(strlen($new_vals['admin_username'])<51 && strlen($new_vals['admin_username'])>2){
			$new_vals['admin_username'] = sha1($new_vals['admin_username']);
		}else{
			unset($new_vals['admin_username']);
			$error .= '<p class="error">username must have 3 to 20 characters.<br>
			The new username/password have <u>not</u> been saved.</p>'; 
		}
	}
	if( isset($new_vals['admin_password']) && empty($new_vals['admin_password']) ){
		unset($new_vals['admin_password']);
	}elseif( isset($new_vals['admin_password']) ){
		// validate password
		if(strlen($new_vals['admin_password'])<51 && strlen($new_vals['admin_password'])>2){
			if($new_vals['admin_password'] == $new_vals['admin_password_again']){
				$new_vals['admin_password'] = sha1($new_vals['admin_password']);
			}else{
				unset($new_vals['admin_password'], $new_vals['admin_password_again']);
				$error .= '<p class="error">The two admin passwords do not match!<br>
				The new username/password have <u>not</u> been saved.</p>';
			}
			
		}else{
			unset($new_vals['admin_password']);
			$error .= '<p class="error">admin password must have 5 to 20 characters.<br>
			The new username/password have <u>not</u> been saved.</p>'; 
		}
	}
	// make sure to save both admin username AND password, OR NONE
	if(!isset($new_vals['admin_password']) && isset($new_vals['admin_username'])){
		unset($new_vals['admin_username']);
	}
	if(!isset($new_vals['admin_username']) && isset($new_vals['admin_password'])){
		unset($new_vals['admin_password']);
	}

	// if new password and username are set, verify old ones
	if(isset($new_vals['admin_username']) && isset($new_vals['admin_password'])){
		$usr = trim( strip_tags( urldecode($_POST['username']) ) );
		$pwd = trim( strip_tags( urldecode($_POST['password']) ) );
		$usr = sha1($usr);
		$pwd = sha1($pwd);
		// if username or password are wrong, show login window witrh wrong login message
		if($usr !== $admin_username || $pwd !== $admin_password){
			$_SESSION['userName'] = 'blip';
			$_SESSION['kftgrnpoiu'] = 'bug';
			header("location: /_code/admin/preferences.php");
			exit;
		}else{
		// if all right, update login sessions to new username and password
			$_SESSION['userName'] = sha1($new_vals['admin_username']);
			$_SESSION['kftgrnpoiu'] = sha1($new_vals['admin_password']);
		}
	}


	// format title, seo_description, seo_title
	if(isset($new_vals['user_en'])){
		$new_vals['user_en'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['user_en']) ) );
	}
	if(isset($new_vals['user_de'])){
		$new_vals['user_de'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['user_de']) ) );
	}
	if(isset($new_vals['seo_description_en'])){
		$new_vals['seo_description_en'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['seo_description_en']) ) );
	}
	if(isset($new_vals['seo_title_en'])){
		$new_vals['seo_title_en'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['seo_title_en']) ) );
	}
	if(isset($new_vals['seo_description_de'])){
		$new_vals['seo_description_de'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['seo_description_de']) ) );
	}
	if(isset($new_vals['seo_title_de'])){
		$new_vals['seo_title_de'] = trim( strip_tags( str_replace( array("\'", '\"',"\n", "\r"), array('&#39;', '&quot;', ' ', ' '), $new_vals['seo_title_de']) ) );
	}

	// get full font css specification from baisc font value
	if(isset($new_vals['site_font'])){
		$new_vals['site_font'] = $custom_fonts[$new_vals['site_font']];
	}
	if(isset($new_vals['header_font'])){
		$new_vals['header_font'] = $header_fonts[$new_vals['header_font']];
	}

	//echo 'font: '.$new_vals['site_font'].'<br>'; exit;
	
	foreach($new_vals as $nk => $nv){
		if( preg_match('/\$'.$nk.' = \'.*\';/', $content, $match) ){
			//echo $match[0].'<br>';
			$content = str_replace($match[0], '$'.$nk.' = \''.$nv.'\';', $content);
			// change value of variable names corresponding tp $nk to the new value
			$$nk = $nv;
		}
	}

	if( empty($error) ){
		// write content to file
		if( $fp = fopen(ROOT.CONTENT.'user_custom.php', 'w') ){
			fwrite($fp, $content);
			fclose($fp);
		}else{
			$error .= '<p class="error">Could not open preferences file.</p>';
		}
	}

	if( empty($error) ){
		if( empty($message) ){
			$message .= '<p class="success">'.$ui['changeSaved'].'</p>';
		}
	}else{
		$message .= $error;
	}

	//echo '<pre>'.str_replace('<', '&lt;', $content).'</pre>'; //exit;
}

$main_info_class = $site_design_class = $admin_info_class = 'adminMore';
$main_info_inner_display = $site_design_inner_display = $admin_info_inner_display = 'none';

// new language added via newLang modal and admin/new_lang
if(isset($_GET['new_lang']) && !empty($_GET['new_lang'])){
	$message .= '<p class="note">the new language <b>'.$_GET['new_lang'].'</b> has been added to the list of languages options.</p>';
	$main_info_h2_class = 'adminLess';
	$main_info_inner_display = 'block';

}

if( isset($_GET['upload_result']) ){
	$upload_result = urldecode($_GET['upload_result']);
	$site_design_h2_class = 'adminLess';
	$site_design_inner_display = 'block';
}


// message GET (from delete_file.php for exemple)
if(isset($_GET['message'])){
	$message = urldecode($_GET['message']);
}

require(ROOT.'_code/inc/doctype.php');










?>
<script src="/_code/js/jscolor.min.js"></script>

<link href="/_code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/_code/css/admin-max-720px.css">

<style>
/*.inner{display:none;}*/
h2 a.adminMore:before{content: "\25B8\ ";}
h2 a.adminLess:before{content: "\25BE\ ";}
h2 a.adminLess{text-decoration:underline;}
</style>

<div id="working">working...</div>

<div class="adminHeader">
<div id="admin">
	<a href="?logout" class="button discret remove right">logout</a>
	<a href="mailto:<?php echo AUTHOR_REF; ?>?subject=Request from <?php echo substr(SITE,0,-1); ?>" title="<?php echo $ui['helpTitle']; ?>" class="button discret help right"><?php echo $ui['help']; ?></a>
</div>
	<div style="padding:0 20px;">
	<h2><a href="<?php echo $back_link; ?>">&larr; <?php echo BACK; ?></a> - <?php echo $ui['preferences']; ?></h2>
	</div>
</div>

<div style="padding:0 20px;">
<?php 
if( isset($message) ){
	echo $message;
}
?>
</div>

<!-- start adminContainer -->
<div id="adminContainer">
	
	<div id="prefContainer" style="max-width:650px;">
		<div id="ajaxTarget">
		<?php if( isset($result) && !empty($result) ){
			echo $result;
		}
		?>
		
		<form action="/_code/admin/preferences.php" name="userPreferences" method="post">
		
		<a name="mainInfo">&nbsp;</a>

		
		
		
		<!-- site main info start -->
		<div class="outer mainInfo">

			<h2><a href="#mainInfo" class="<?php echo $main_info_class; ?>"><?php echo $ui['siteMainInfo']; ?></a></h2>

			<p><?php echo $ui['siteMainInfoDescription']; ?></p>
			
			<!-- inner main info start -->
			<div class="inner innerMainInfo" style="display:<?php echo $main_info_inner_display; ?>;">

			
			<div class="third" style="text-align:right;"><?php echo $ui['bilingual']; ?>:</div>
			<div class="twothird"><input type="radio" name="bilingual" value="yes" style="width:auto;" <?php if($bilingual == 'yes'){echo ' checked';}?> onclick="if($(this).prop('checked')==true){$('.l2').show();}else{$('.l2').hide();}"> yes &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bilingual" value="no" style="width:auto;" <?php if($bilingual == 'no'){echo ' checked';}?> onclick="if($(this).prop('checked')==true){$('.l2').hide();}else{$('.l2').show();}"> no</div>
			

			<div class="third" style="text-align:right;"><?php echo $ui['firstLang']; ?>:</div>
			<div class="twothird">

			<select name="first_lang" id="first_lang" onchange="if($(this).val()==''){showModal('newLang?lang=first');}">
			<?php 
			foreach($languages as $k => $v){
				if($first_lang == $k){
					$selected = ' selected';
				}else{
					$selected = '';
				}
				echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
			}
			?>
			<option value="">other...</option>
			</select>
			</div>
			<div class="third l2" style="text-align:right;"><?php echo $ui['secondLang']; ?>:</div>
			<div class="twothird l2">
			<select name="second_lang" id="second_lang" onchange="if($(this).val()==''){showModal('newLang?lang=second');}">
			<?php 
			foreach($languages as $k => $v){
				if($second_lang == $k){
					$selected = ' selected';
				}else{
					$selected = '';
				}
				echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
			}
			?>
			<option value="">other...</option>
			</select>
			</div>

			<div class="third" style="text-align:right;"><span class="question" title="<?php echo $ui['siteNameDescription']; ?>">?</span> <?php echo $ui['siteName']; ?>:</div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<input type="text" maxlength="50" name="user_en" style="margin:5px 0;" value="<?php echo $user_en; ?>" placeholder="50 chars. max."><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<input type="text" maxlength="100" name="user_de" style="margin:5px 0;" value="<?php echo $user_de; ?>" placeholder="50 chars. max.">
			</div>
			</div>
			
			<div class="third" style="text-align:right;"><span class="question" title="<?php echo $ui['siteTitleDescription']; ?>">?</span> <?php echo $ui['siteTitle']; ?>:</div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<input type="text" maxlength="100" name="seo_title_en" style="margin:5px 0;" value="<?php echo $seo_title_en; ?>" placeholder="100 chars. max."><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<input type="text" maxlength="100" name="seo_title_de" style="margin:5px 0;" value="<?php echo $seo_title_de; ?>" placeholder="100 chars. max.">
			</div>
			</div>
			
			<div class="third" style="text-align:right;"><span class="question" title="<?php echo $ui['siteDescDescription']; ?>">?</span> <?php echo $ui['siteDescription']; ?>:</div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<textarea maxlength="500" rows="5" name="seo_description_en" style="margin:5px 0;" placeholder="500 chars. max."><?php echo $seo_description_en; ?></textarea><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<textarea maxlength="500" rows="5" name="seo_description_de"  style="margin:5px 0;" placeholder="500 chars. max."><?php echo $seo_description_de; ?></textarea>
			</div>
			</div>
			
			<div class="clearBoth" style="padding-top:20px;">
			<button type="submit" name="submitSitePrefs" class="save right"> <?php echo $ui['saveChanges']; ?> </button> <button type="reset" name="reset" class="right"><?php echo $ui['reset']; ?></button>
			</div>

			</div>
			<!-- main info innerdiv end -->
		</div>
		<!-- site main info end -->
		



		
		<!-- siteDesign start -->
		<div class="outer siteDesign">

			<a name="design"></a>
			<p>&nbsp;</p>
			<h2><a href="#design" class="<?php echo $site_design_class; ?>"><?php echo $ui['designOptions']; ?></a></h2>

			<p><?php echo $ui['designOptDescription']; ?></p>
			
			<!-- inner site design start -->
			<div class="inner innerSiteDesign" style="display:<?php echo $site_design_inner_display; ?>;">
			
			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['siteNav']; ?>:</div>
			<div class="twothird" style="position:relative;">
				<div id="layoutSpecimen" style="display:none; position:absolute; top:-100px; left:100%; width:auto; padding:0; border:1px solid #ccc; border-radius:3px; box-shadow:1px 1px 3px #ccc;">
					<img src="/_code/admin/images/layout.jpg" style="display:block;">
				</div>
			<select name="css">
				<option value="nav-left"<?php if($css == 'nav-left'){echo ' selected';} ?>><?php echo $ui['navOptLeft']; ?></option>
				<option value="nav-top"<?php if($css == 'nav-top'){echo ' selected';} ?>><?php echo $ui['navOptTop']; ?></option>
			</select>
			</div>

			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['navShowsSub']; ?>:</div>
			<div class="twothird" style="position:relative;">
			<select name="show_sub_nav">
				<option value="no"<?php if($show_sub_nav == 'no'){echo ' selected';} ?>><?php echo $ui['no']; ?></option>
				<option value="yes"<?php if($show_sub_nav == 'yes'){echo ' selected';} ?>><?php echo $ui['yes']; ?></option>
			</select>
			</div>


			<div class="third" style="text-align:right;"><?php echo $ui['homeBg']; ?>:
			<?php
			if( isset($upload_result) ){
				if($upload_result == 'file uploaded'){
					$class = 'success';
				}else{
					$class = 'error';
				}
				echo '<p class="'.$class.'" style="text-align:left;">'.$upload_result.'</p>';
			}
			if( isset($home_image) ){
				list($w, $h) = getimagesize(ROOT.CONTENT.$home_image);
				$bytes = filesize(ROOT.CONTENT.$home_image);
				if($w<1300 || $h<900){
					$d_class = 'error';
					$dim_warning = '<p class="note warning" style="text-align:left; padding-right:30px;">'.$ui['bgDimWarning'].' (1300 &times; 900 pixels)</p>';
				}else{
					$dim_warning = $d_class = '';
				}
				echo '<p>pixel dimensions: ';
				echo '<span class="'.$d_class.'">'.$w.' &times; '.$h.'</span></p>';
				echo $dim_warning;
				if($bytes>=1000000){
					$s_class = 'error';
					$size_warning = '<p class="note warning" style="text-align:left; padding-right:30px;">The image size will considerably impact the loading time of your home page. <a href="/_code/inc/optimize.php?recommended_size=2000" target="_blank">See here how to optimize it better</a>.</p>';
				}else{
					$size_warning = $s_class = '';
				}
				echo '<p>'.$ui['imgSize'].': ';
				echo '<span class="'.$s_class.'">'.FileSizeConvert($bytes).'</span>';
				echo '</p>';
				echo $size_warning;
				echo '<a href="javascript:;" class="button showModal right" rel="uploadFile?path=&replace='.urlencode($home_image).'">'.$ui['change'].'</a>';
				echo '</div>
				<div class="twothird"><a href="/'.CONTENT.$home_image.'?v='.rand(1,999).'" target="_blank" title="open image in new window"><img src="/'.CONTENT.$home_image.'?v='.rand(1,999).'" style="width:100%;"></a>
				</div>';
			}else{
				echo '<a href="javascript:;" class="button showModal right" rel="uploadFile?path=&replace=bg.jpg">'.$ui['upload'].'</a>';
				echo '</div>
				<div class="twothird"><img src="/'.CONTENT.'bg.jpg" style="width:100%;">
				</div>';
			}
			?>
			
			
			<div class="third" style="text-align:right;"><?php echo $ui['siteBgColor']; ?>:</div>
			<div class="twothird"><input name="site_bg_color" class="jscolor jscolor-active" value="<?php echo $site_bg_color; ?>" onchange="updateBgColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>

			<div class="third" style="text-align:right;"><?php echo $ui['itemsBgColor']; ?>:</div>
			<div class="twothird"><input name="item_bg_color" class="jscolor jscolor-active" value="<?php echo $item_bg_color; ?>" onchange="updateItemColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>
			
			<!--
			<div class="third" style="text-align:right;">Text size:</div>
			<div class="twothird">
				<select name="font_size" onchange="updateFontSize(this.value, '<?php echo $font_size; ?>');">
				<option value="80%"<?php if($font_size == '80%'){echo ' selected';}?>>small</option>
				<option value="90%"<?php if($font_size == '90%'){echo ' selected';}?>>medium</option>
				<option value="100%"<?php if($font_size == '100%'){echo ' selected';}?>>large</option>
			</select>
			</div>
			-->


			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['headerFont']; ?>:</div>
			<div class="twothird" style="position:relative;">
				<div id="specimen" style="display:none; position:absolute; top:-40%; left:100%; width:450px; padding:0 15px; border:1px solid #ccc; border-radius:3px; box-shadow:1px 1px 3px #ccc;">
					<h2>Header text example</h2>
					<p>Paragraph example: Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat. Quis aute iure reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint obcaecat cupiditat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
				</div>
			<select name="header_font" onchange="updateFont(this.value, 'header');">
				<?php 
				foreach($header_fonts as $k=>$v){
					if($header_font == $v){
						$selected = ' selected';
					}else{
						$selected = '';
					}
					echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
				}
				?>
			</select>
			</div>

			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['siteFont']; ?>:</div>
			<div class="twothird">
			<select name="site_font" onchange="updateFont(this.value, 'small');">
				<?php 
				foreach($custom_fonts as $k=>$v){
					if($site_font == $v){
						$selected = ' selected';
					}else{
						$selected = '';
					}
					echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
				}
				?>
			</select>
			</div>

			<div class="third" style="text-align:right;"><?php echo $ui['fontColor']; ?>:</div>
			<div class="twothird"><input name="font_color" class="jscolor jscolor-active" value="<?php echo $font_color; ?>" onchange="updateFontColor(this.jscolor);" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>

			<div class="third" style="text-align:right;"><?php echo $ui['linksColor']; ?>:</div>
			<div class="twothird"><input name="link_color" class="jscolor jscolor-active" value="<?php echo $link_color; ?>" onchange="updateLinkColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>

			<div class="third" style="text-align:right;"><?php echo $ui['itemsBorder']; ?>:</div>
			<div class="twothird">
				<select name="borders">
				<option value="none"<?php if($borders == 'none'){echo ' selected';}?>>none</option>
				<option value="1px solid #000000"<?php if($borders == '1px solid #000000'){echo ' selected';}?>>black</option>
				<option value="1px solid #AAAAAA"<?php if($borders == '1px solid #AAAAAA'){echo ' selected';}?>>grey</option>
				<option value="1px solid #EEEEEE"<?php if($borders == '1px solid #EEEEEE'){echo ' selected';}?>>light grey</option>
				<option value="1px solid #FFFFFF"<?php if($borders == '1px solid #FFFFFF'){echo ' selected';}?>>white</option>
			</select>
			</div>

			<div class="third" style="text-align:right;"><?php echo $ui['imgZoom']; ?>:</div>
			<div class="twothird" style="position:relative;">
				<div id="zoomMode" style="display:none; position:absolute; bottom:-220px; left:100%; width:393px; padding:10px; border:1px solid #ccc; border-radius:3px; box-shadow:1px 1px 3px #ccc;">
					<img src="/_code/admin/images/zoom-mode.jpg">
				</div>
				<select name="zoom_mode">
				<option value="fit to screen"<?php if($zoom_mode == 'fit to screen'){echo ' selected';}?>><?php echo $ui['imgZoomFit']; ?></option>
				<option value="fill screen"<?php if($zoom_mode == 'fill screen'){echo ' selected';}?>><?php echo $ui['imgZoomFill']; ?></option>
			</select>
			</div>

			<div class="clearBoth" style="padding-top:20px;">
			<button type="submit" name="submitSitePrefs" class="save right"> <?php echo $ui['saveChanges']; ?> </button> <button type="reset" name="reset" class="right"><?php echo $ui['reset']; ?></button>
			</div>

			</div>
			<!-- site design innerdiv end -->
		</div>
		<!-- site design end -->





		<!-- admin login start -->
		<div class="outer adminLogin">

			<a name="adminCreds"></a>
			<p>&nbsp;</p>
			<h2><a href="#adminCreds" class="<?php echo $admin_info_class; ?>"><?php echo $ui['adminAccess']; ?></a></h2>

			<p><?php echo $ui['adminAccDescription']; ?></p>
			
			<!-- inner admin login start -->
			<div class="inner innerAdminLogin" style="display:<?php echo $admin_info_inner_display; ?>;">

			<div class="third" style="text-align:right;"><?php echo $ui['currentUsername']; ?>:</div>
			<div class="twothird"><input type="text" name="username" value=""></div>
			
			<div class="third" style="text-align:right;"><?php echo $ui['currentPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="password" value=""></div>
			
			
			<div class="third" style="text-align:right;"><?php echo $ui['newUsername']; ?>:</div>
			<div class="twothird"><input type="text" name="admin_username" pattern=".{0}|.{3,50}" title="3 to 50 chars" value="" placeholder="3 - 50 chars."></div>
			
			<div class="third" style="text-align:right;"><?php echo $ui['newPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="admin_password" pattern=".{0}|.{5,20}" title="5 to 10 chars" value="" placeholder="5 - 10 chars."></div>

			<div class="third" style="text-align:right;"><?php echo $ui['verifyPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="admin_password_again" pattern=".{0}|.{5,20}" title="5 - 10 chars" value=""></div>


			<div class="clearBoth" style="padding-top:20px;">
			<button type="submit" name="submitSitePrefs" class="save right"> <?php echo $ui['saveChanges']; ?> </button> <button type="reset" name="reset" class="right"><?php echo $ui['reset']; ?></button>
			</div>

			</div>
			<!-- admin login innerdiv end -->
		</div>
		<!-- admin login end -->





		</form>
	

</div><!-- ajaxTarget end -->
</div><!-- prefContainer end -->


<div class="clearBoth" style="padding:50px;">&nbsp;</div>
</div><!-- end adminContainer -->


<?php require(ROOT.'_code/inc/adminFooter.php'); ?>

<script type="text/javascript">
// generate javascript arrays from php $custom_fonts and $header_fonts
var custom_fonts = new Array;
var header_fonts = new Array;
<?php
// site fonts
foreach($custom_fonts as $k => $v){
	echo 'custom_fonts[\''.$k.'\'] = \''.$v.'\';'.PHP_EOL;
}
// header fonts
foreach($header_fonts as $k => $v){
	echo 'header_fonts[\''.$k.'\'] = \''.$v.'\';'.PHP_EOL;
}
?>
// update bg color
function updateBgColor(jscolor){
    document.body.style.backgroundColor = '#' + jscolor;
}
// update item bg-color
function updateItemColor(jscolor){
	document.divItem.style.backgroundColor = '#' + jscolor;
}
// update font color
function updateFontColor(jscolor){
    document.body.style.color = '#' + jscolor;
}
// update links color
function updateLinkColor(jscolor){
    $('a').css('color', '#' + jscolor);
}
// update font size (small:80% medium:90% large:100%)
function updateFontSize(val, orig){
	valNum = parseInt(val.replace("%",""));
	if(orig == '80%'){
		val = (valNum+15)+'%';
	}else if(orig == '100%'){
		val = (valNum-15)+'%';
	}
	document.body.style.fontSize = val;
}
// update small or header (target) font
function updateFont(val, target){
	valID = val.replace(" ","+");
	if (!document.getElementById(valID)) {
		var head = document.getElementsByTagName('head')[0];
		var link = document.createElement('link');
		link.id = valID;
		link.rel = 'stylesheet';
		link.type = 'text/css';
		link.href = 'https://fonts.googleapis.com/css?family='+valID+':400,700';
		link.media = 'all';
		head.appendChild(link);
	}
	if(target == 'small'){
		$('body, td, th, select, input, button, textarea').css('font', custom_fonts[val]);
	}else if(target == 'header'){
		$('h1, h2, h3, .title').css('font-family', header_fonts[val]);
	}
}

// show/hide inner divs for each preference section
$('div.outer').on('click', 'h2 a', function(){
	var theInnerDiv = $(this).parents('div.outer').find('div.inner');
	if(theInnerDiv.css('display') == 'none'){
		$('div.inner').hide();
		theInnerDiv.show();
		$('h2 a.adminLess').removeClass("adminLess").addClass("adminMore");
		$(this).removeClass("adminMore").addClass("adminLess");
	}else{
		$('div.inner').hide();
		theInnerDiv.hide();
		$(this).removeClass("adminLess").addClass("adminMore");
	}
});

// update first/second language name on selection
$('select[name="first_lang"]').on('change', function(){
	var l = $(this).val();
	$("span.langFirst").html(l);
});
$('select[name="second_lang"]').on('change', function(){
	var l = $(this).val();
	$("span.langSec").html(l);
});

// show/hide sample on font selection/blur
$('select[name="header_font"], select[name="site_font"]').on('focus', function(){
	$("#specimen").show();
}).on('blur', function(){
	$("#specimen").hide();
});

// show/hide illustration for image zoom mode
$('select[name="zoom_mode"]').on('focus', function(){
	$("#zoomMode").show();
}).on('blur', function(){
	$("#zoomMode").hide();
});

// show/hide illustration for image zoom mode
$('select[name="css"]').on('focus', function(){
	$("#layoutSpecimen").show();
}).on('blur', function(){
	$("#layoutSpecimen").hide();
});

var formmodified =0;

$('input, select, textarea')
	.on("change", function() {
		formmodified = 1;
	})
	.on("paste", function() {
		formmodified = 1;
	})

// prevent user from leaving the page without saving his changes
$(document).ready(function(){
	window.onbeforeunload = function(e){
		var warning = "Your changes have not been saved! Are you sure you want to leave this page?";
		if (formmodified == 1) {
			var e = e || window.event;
			// For IE and Firefox
			if (e){
				e.returnValue = warning;
			}
			// For Safari
			return warning;
		}
	}
	$("button[type=submit]").click(function() {
        formmodified = 0;
    });
});

</script>
