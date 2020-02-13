<?php
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

$title = 'ADMIN : '.$ui['preferences'];
$description = $message = $error = '';
$page = 'admin';
$back_link = 'manage_structure.php';


// Form submit validation
if(isset($_POST['submitStylesPrefs']) || isset($_POST['submitUserPrefs']) || isset($_POST['submitCredsPrefs']) ){
	
	if( isset($_POST['submitStylesPrefs']) ){
		$file = 'styles_custom.php';
	}else{
		$file = 'user_custom.php';
	}

	// get custom content
	$content = file_get_contents(ROOT.CONTENT.$file);
	
	// initialize new_vals (from form $_POST)
	$new_vals = array();
	foreach($_POST as $k => $v){
		if($k != 'types' && $k != 'sizes' && $k != 'submitStylesPrefs' && $k != 'submitUserPrefs' && $k != 'submitCredsPrefs'){
			//echo $k.' = '.$v.'<br>';
			$new_vals[$k] = addslashes($v);
		}
	}
	
	// debug
	//print_r($new_vals);
	//exit;

	/* password & username change validation */
	// unset username and password, to prevent saving empty values.
	if( isset($_POST['submitCredsPrefs']) ){ 
		unset($new_vals['username'], $new_vals['password']);

		$usr = trim( strip_tags( urldecode($_POST['username']) ) );
		$pwd = trim( strip_tags( urldecode($_POST['password']) ) );
		if( empty($usr) || empty($pwd) ){
			$error .= '<p class="error">empty username or password</p>'; 
		}

		// make sure blank user name and password are not saved
		if( isset($new_vals['admin_username']) && empty($new_vals['admin_username']) ){
			unset($new_vals['admin_username']);
			$error .= '<p class="error">empty new username</p>'; 
		}elseif( isset($new_vals['admin_username']) ){
			// validate username
			if(strlen($new_vals['admin_username'])<51 && strlen($new_vals['admin_username'])>2){
				$new_vals['admin_username'] = sha1($new_vals['admin_username']);
			}else{
				unset($new_vals['admin_username']);
				$error .= '<p class="error">'.$ui['changeUserError'].'</p>'; 
			}
		}
		if( isset($new_vals['admin_password']) && empty($new_vals['admin_password']) ){
			unset($new_vals['admin_password']);
			$error .= '<p class="error">empty new password</p>'; 
		}elseif( isset($new_vals['admin_password']) ){
			// validate password
			if(strlen($new_vals['admin_password'])<51 && strlen($new_vals['admin_password'])>2){
				if($new_vals['admin_password'] == $new_vals['admin_password_again']){
					$new_vals['admin_password'] = sha1($new_vals['admin_password']);
				}else{
					unset($new_vals['admin_password'], $new_vals['admin_password_again']);
					$error .= '<p class="error">'.$ui['pwdNoMatch'].'</p>';
				}
				
			}else{
				unset($new_vals['admin_password']);
				$error .= '<p class="error">'.$ui['changePwdError'].'</p>'; 
			}
		}
		// make sure to save both admin username AND password, OR NONE
		if(!isset($new_vals['admin_password']) && isset($new_vals['admin_username'])){
			unset($new_vals['admin_username']);
			$error .= '<p class="error">invalid new admin password</p>'; 
		}
		if(!isset($new_vals['admin_username']) && isset($new_vals['admin_password'])){
			unset($new_vals['admin_password']);
			$error .= '<p class="error">invalid new admin username</p>'; 
		}

		// if new password and username are still set, verify old ones
		if(isset($new_vals['admin_username']) && isset($new_vals['admin_password']) && empty($error) ){
			$usr = sha1($usr);
			$pwd = sha1($pwd);
			// if username or password are wrong, show login window witrh wrong login message
			if($usr !== $topinambourg || $pwd !== $krakapouf){
				unset($_SESSION['gribouilli'], $_SESSION['kftgrnpoiu']);
				header("location: /".DEMO."~code/admin/manage_structure.php?error");
				exit;
			}else{
			// if all right, update login sessions to new username and password
				$_SESSION['gribouilli'] = sha1($new_vals['admin_username']);
				$_SESSION['kftgrnpoiu'] = sha1($new_vals['admin_password']);
			}
		}
		if( empty($error) ){
			$new_vals['topinambourg'] = $new_vals['admin_username'];
			$new_vals['krakapouf'] = $new_vals['admin_password'];
			unset($new_vals['admin_username'], $new_vals['admin_password']);
		}
	}
	/* end password & username change validation */

	// format title (user_en & user_de), seo_description, seo_title
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

	// get full font css specification from basicc font value
	/*
	if(isset($new_vals['site_font'])){
		$new_vals['site_font'] = $custom_fonts[$new_vals['site_font']];
	}
	if(isset($new_vals['header_font'])){
		$new_vals['header_font'] = $header_fonts[$new_vals['header_font']];
	}
	*/

	//echo 'font: '.$new_vals['site_font'].'<br>'; exit;

	// change css_version to force refresh new css with query ?v=789
	if($file == 'styles_custom.php'){
		$rand = rand(1,999);
		$new_vals['css_version'] = $rand;
	}
	
	foreach($new_vals as $nk => $nv){
		if( preg_match('/\$'.$nk.' = \'.*\';/', $content, $match) ){
			//echo $match[0].'<br>';
			$content = str_replace($match[0], '$'.$nk.' = \''.$nv.'\';', $content);
			// change value of variable names corresponding tp $nk to the new value
			$$nk = $nv;
		}
	}

	if( empty($error) ){
		// write content user_custom or styles_custom file
		if( $fp = fopen(ROOT.CONTENT.$file, 'w') ){
			fwrite($fp, $content);
			fclose($fp);
		}else{
			$error .= '<p class="error">Could not open preferences file.</p>';
		}
	}

	// load new lang file if applicable
	if( isset($new_vals['first_lang']) && !empty($new_vals['first_lang']) ){
		//echo $new_vals['first_lang'].'<br>';
		$new_lang_file = ROOT.DEMO.'~code/admin/ui_lang/'.$new_vals['first_lang'].'.php';
		if( file_exists($new_lang_file) ){
			include($new_lang_file);
		}else{
			include(ROOT.DEMO.'~code/admin/ui_lang/english.php');
		}
	}

	if( empty($error) ){
		if( empty($message) ){
			$message .= '<p class="success">'.$ui['changeSaved'].'</p>';
		}
	}else{
		$message .= $error;
	}
	// debug
	//echo '<pre>'.str_replace('<', '&lt;', $content).'</pre>'; //exit;
}


// new language added via newLang modal and admin/new_lang
if(isset($_GET['new_lang']) && !empty($_GET['new_lang'])){
	$message .= '<p class="note">the new language <b>'.$_GET['new_lang'].'</b> has been added to the list of languages options.</p>';
}

if( isset($_GET['upload_result']) ){
	$upload_result = urldecode($_GET['upload_result']);
	$_GET['form'] = 'siteDesign';
}


// message GET (from delete_file.php for exemple)
if( isset($_GET['message']) ){
	$message = urldecode($_GET['message']);
}

require(ROOT.DEMO.'~code/inc/doctype.php');

// delete background image(s)
if( isset($_GET['deleteBgImage']) ){
	if( isset($home_image) ){
		unlink(ROOT.CONTENT.UPLOADS.'_XL/'.$home_image);
		unlink(ROOT.CONTENT.UPLOADS.'_L/'.$home_image);
		unlink(ROOT.CONTENT.UPLOADS.'_M/'.$home_image);
		unlink(ROOT.CONTENT.UPLOADS.'_S/'.$home_image);
		unset($home_image);
	}
}

$backups = scan_dir(ROOT.CONTENT.'~backup'); /**** !!!!! should sort by date, most recent */
//rsort($backups);
// debug
//print_r($backups);

// dynamic selected link color in left nav, depending on overlay bg defined in custom.css.php
if( substr($overlay_bg, 0, 8) == 'rgba(255'){
	$sel_link_color = '#000'; 
}else{
	$sel_link_color = '#fff'; 
}

?>
<script src="/<?php echo DEMO; ?>~code/js/jscolor.min.js"></script>

<link href="/<?php echo DEMO; ?>~code/css/admincss.css?v=<?php echo $version; ?>" rel="stylesheet" type="text/css">

<style type="text/css">
.adminHeader p{display:inline-block; padding-right:40px; margin-left:30px; overflow:visible;}
#adminContainer{position:relative; padding:0;}
#admin a.button{margin-top:15px;}
.leftNav{position:fixed; z-index:5; width:300px;/* top:55px;*/ left:0;}
.leftNav a{display:block; padding:5px 20px;}
.leftNav a:hover{text-decoration:none; background-color:#ddd;}
.leftNav a:hover h2{text-decoration:underline;}
/* dynamic selected link color */
a.adminLess, a.adminLess:hover{color:<?php echo $sel_link_color; ?>;}
a.adminLess h2:after{content: " \25B8\ ";}
a.adminLess h2{text-decoration:underline;}
#prefContainer{
	margin-left:300px; margin-top:1px; 
	max-height:100%; min-width:500px;
}
#prefContainer input, #prefContainer textarea, #prefContainer select{width:100%;}
#prefContainer form{padding:10px;}
form.inner{max-width:650px; min-width:380px;}
form.inner >div{ margin-top:20px; padding-bottom:100px;}
div.halfContainer{float:left; width:50%; min-width:400px;}
div.third, div.quart, div.twothird{float:left; padding:10px 1%;}
div.third{width:32%;clear:both;}
div.twothird{width:64%;}
div.quart{width:48%;}
div.third p.note, div.third p.error, div.third p.success{padding-right:35px; font-size:smaller;} 
a.homeBgOption{display:inline-block; text-align:center; width: calc(50% - 10px); padding-left:5px; border:1px solid #ccc; border-radius:3px; color:#444; background-color:#fff;}
a.homeBgOption span{visibility:hidden;}
a.homeBgOption.bgSelected{border-color:#000; color:#000; background-color:#eee;}
a.homeBgOption.bgSelected span{visibility:visible;}
</style>

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">


<div class="adminHeader">
<div style="padding: 5px 15px 5px 15px;">

<h1><a href="/<?php echo DEMO; ?>admin/"><span class="tip" data-tip="<?php echo $ui['adminBack']; ?>"><?php echo $ui['structure']; ?></span></a></h1>

<a href="javascript:;" class="save button submit" style="display:none; margin-left:20px;"><?php echo $ui['saveChanges']; ?></a>

<?php if(isset($message)){echo $message;} ?>

<h1 style="margin:0; display:inline-block; float:right; text-decoration:underline;"><?php echo $ui['preferences']; ?></h1>


	
	
</div>
</div>


	
<div class="leftNav">
	<a href="?form=mainInfo"<?php if(!isset($_GET['form']) || isset($_GET['form']) && $_GET['form'] == 'mainInfo'){echo ' class="adminLess"';}?> data-show="mainInfo">
	<h2><?php echo $ui['siteMainInfo']; ?></h2>
	<p><?php echo $ui['siteMainInfoDescription']; ?></p>
	</a>

	<a href="?form=siteDesign"<?php if(isset($_GET['form']) && $_GET['form'] == 'siteDesign'){echo ' class="adminLess"';}?> data-show="siteDesign">
	<h2><?php echo $ui['designOptions']; ?></h2>
	<p><?php echo $ui['designOptDescription']; ?></p>
	</a>

	<a href="?form=adminCreds"<?php if(isset($_GET['form']) && $_GET['form'] == 'adminCreds'){echo ' class="adminLess"';}?> data-show="adminCreds">
	<h2><?php echo $ui['adminAccess']; ?></h2>
	<p><?php echo $ui['adminAccDescription']; ?></p>
	</a>

</div>


<!-- start adminContainer -->
<div id="adminContainer">


	<div id="prefContainer">

	<?php 
	if(!isset($_GET['form']) || isset($_GET['form']) && $_GET['form'] == 'mainInfo'){
		?>

		<!-- site main info start -->
		<form action="/<?php echo DEMO; ?>~code/admin/preferences.php?form=mainInfo" id="mainInfo" name="mainInfoForm" method="post" class="inner">
		<div>

			
			<div class="third" style="text-align:right;"><span class="tip" data-tip="<?php echo $ui['bilingDesc']; ?>"><?php echo $ui['bilingual']; ?>:</span></div>
			<div class="twothird"><input type="radio" name="bilingual" value="yes" style="width:auto;" <?php if($bilingual == 'yes'){echo ' checked';}?> onclick="if($(this).prop('checked')==true){$('.l2').show();}else{$('.l2').hide();}"> <?php echo $ui['yes']; ?> &nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" name="bilingual" value="no" style="width:auto;" <?php if($bilingual == 'no'){echo ' checked';}?> onclick="if($(this).prop('checked')==true){$('.l2').hide();}else{$('.l2').show();}">  <?php echo $ui['no']; ?></div>
			

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

			<div class="third" style="text-align:right;"><span class="tip" data-tip="<?php echo $ui['siteNameDescription']; ?>"><?php echo $ui['siteName']; ?>:</span></div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteNameDescription']; ?>"><input type="text" maxlength="50" name="user_en" style="margin:5px 0;" value="<?php echo $user_en; ?>" placeholder="50 chars. max."></span><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteNameDescription']; ?>"><input type="text" maxlength="100" name="user_de" style="margin:5px 0;" value="<?php echo $user_de; ?>" placeholder="50 chars. max."></span>
			</div>
			</div>
			
			<div class="third" style="text-align:right;"><span class="tip" data-tip="<?php echo $ui['siteTitleDescription']; ?>"><?php echo $ui['siteTitle']; ?>:</span></div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteTitleDescription']; ?>"><input type="text" maxlength="100" name="seo_title_en" style="margin:5px 0;" value="<?php echo $seo_title_en; ?>" placeholder="100 chars. max."></span><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteTitleDescription']; ?>"><input type="text" maxlength="100" name="seo_title_de" style="margin:5px 0;" value="<?php echo $seo_title_de; ?>" placeholder="100 chars. max."></span>
			</div>
			</div>
			
			<div class="third" style="text-align:right;"><span class="tip" data-tip="<?php echo $ui['siteDescDescription']; ?>"><?php echo $ui['siteDescription']; ?>:</span></div>
			<div class="twothird">
			<span class="langFirst below l2"><?php echo $first_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteDescDescription']; ?>"><textarea maxlength="500" rows="5" name="seo_description_en" style="margin:5px 0;" placeholder="500 chars. max."><?php echo $seo_description_en; ?></textarea></span><br>
			<div class="l2">
			<span class="langSec below l2"><?php echo $second_lang; ?><br></span>
			<span class="tip" data-tip="<?php echo $ui['siteDescDescription']; ?>"><textarea maxlength="500" rows="5" name="seo_description_de"  style="margin:5px 0;" placeholder="500 chars. max."><?php echo $seo_description_de; ?></textarea></span>
			</div>
			</div>
			
			<div class="clearBoth" style="padding-top:20px;">
			<button type="submit" name="submitUserPrefs" class="save right savePrefs"> <?php echo $ui['saveChanges']; ?> </button> <button type="reset" name="reset" class="right"><?php echo $ui['reset']; ?></button>
			</div>

		</div>
		</form>
		<!-- site main info end -->
	
	<?php 
	}elseif( $_GET['form'] == 'siteDesign'){ 
	?>


		<!-- this div is outside the rest of the form#siteDesign (below it) but imitates the forms style, inline -->
		<div style="max-width: 650px; min-width: 380px; padding:10px;">

		<div class="third" style="text-align:right;">
			<?php 
			$sel_checkimage = $sel_checkslides = '';
			$slides_file = ROOT.CONTENT.'home-slides.txt';
			echo $ui['homeBg'].':';

			// get bg image dimensions
			if( isset($home_image) ){
				$xl_img = ROOT.CONTENT.UPLOADS.'_XL/'.$home_image;
				list($w, $h) = getimagesize($xl_img);
				$sel_checkimage = ' bgSelected';
				//echo '<!--<p><span class="tip" data-tip="'.$ui['viewSite'].'"><a href="/'.DEMO.'?v=678" class="openNew" target="_tab">&nbsp;</a></span></p>-->';
			}elseif( file_exists($slides_file) ){
				$sel_checkslides = ' bgSelected';
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

			if( isset($upload_result) ){
				echo str_replace(array('0|','1|','2|'), array('<p class="error">','<p class="success">','<p class="note">'), $upload_result).'</p>';

				// resize _XL image if upload success
				if( strstr($upload_result, ' class="success') || substr($upload_result, 0, 2)=='1|' ){
					$bg_max_size = 2000;
					if($w > $bg_max_size || $h > $bg_max_size){
						// rename to temp name
						$ext = file_extension( $home_image );
						$tmp_name = ROOT.CONTENT.UPLOADS.'_XL/___tmp___'.$ext;
						rename($xl_img, $tmp_name);
						$resize = resize($tmp_name, $xl_img, $w, $h, $bg_max_size, $bg_max_size);
						if( substr($resize, 0, 2) !== '1|' && !empty($resize) ){
							echo '<p class="error">'.$resize.'</p>';
						}else{
							unlink($tmp_name);
							list($w, $h) = getimagesize($xl_img);
							// debug
							//echo '<p class="note">The image has been resized</p>';
							// show warning message if bg img is too small
							if( ($w < 2000 && $h < 1000) || ($w < 1000 && $h < 2000) ){
								echo '<p class="error">'.$ui['bgDimWarning'].'</p>
								<p style="font-size:smaller">Dimensions:<br>
								'.$w.' &times; '.$h.'px<br>
								Recommended dimensions:<br>
								2000 &times; 1000px</p>';
							}
						}
					}
				}
			}
			?>
			</div>

			<div class="twothird">
			<?php
			echo '<!--<a href="javascript:;" class="homeBgOption'.$sel_checkimage.'"><span class="checkmark green">&nbsp;</span>&nbsp;&nbsp;&nbsp;Single image&nbsp;&nbsp;&nbsp;</a>
			<a href="javascript:;" class="homeBgOption'.$sel_checkslides.'"><span class="checkmark green">&nbsp;</span>&nbsp;&nbsp;&nbsp;Slide-show&nbsp;&nbsp;&nbsp;</a>
			<div class="clearBoth" style="margin-bottom:10px;"></div>-->';
			echo '<div id="bgContainer" style="position:relative;">';
			// home image is set, show it with 'change' button
			if( isset($home_image) ){
				echo '<div style="position:absolute; top:20px; right:20px;">
				<a href="?form=siteDesign&deleteBgImage" class="button remove">'.$ui['delete'].'</a> 
				<a href="javascript:;" class="button submit showModal" rel="newFile?path='.urlencode(UPLOADS.'_M/').'&replace='.urlencode(UPLOADS.'_M/'.$home_image).'&context=home_bg_img">'.$ui['change'].'</a>
				</div>
				<a href="/'.CONTENT.UPLOADS.'_XL/'.$home_image.'?v='.rand(1,999).'" target="_blank" title="open image in new window" style="display:block;"><img src="/'.CONTENT.UPLOADS.SIZE.'/'.$home_image.'?v='.rand(1,999).'" style="width:100%; cursor: zoom-in;">
				</a>';
			
			// else, show 'choose image' button
			}else{
				echo '<a href="javascript:;" class="button submit showModal left" rel="newFile?path=~uploads%2F_M%2F&replace='.BG.'.jpg&context=home_bg_img">'.$ui['uploadImage'].'</a>';
			}
			?>
			</div>
			</div>
		</div>
		
		<!-- siteDesign start -->
		<form action="/<?php echo DEMO; ?>~code/admin/preferences.php?form=siteDesign" id="siteDesign" name="siteDesignForm" method="post" class="inner">
		<div>
			
			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['siteNav']; ?>:
			<div class="lowkey" style="margin-top:22px; font-size:smaller;">click icons to preview &rarr;</div>
			</div>
			<div class="twothird" style="position:relative;">
			<select name="css">
				<option value="nav-left"<?php if($css == 'nav-left'){echo ' selected';} ?>><?php echo $ui['navOptLeft']; ?></option>
				<option value="nav-top"<?php if($css == 'nav-top'){echo ' selected';} ?>><?php echo $ui['navOptTop']; ?></option>
			</select>
			<div class="note" style="font-size:smaller; margin:5px 0; padding:7px;">
			<span class="nowrap"><?php echo $ui['navOptLeft']; ?>: <a href="/<?php echo DEMO; ?>?nav=left" target="_test" title="click to view"><img src="/<?php echo DEMO; ?>~code/admin/images/nav-left.gif" style="vertical-align:middle;"></a></span> &nbsp;&nbsp; <span class="nowrap"><?php echo $ui['navOptTop']; ?>: <a href="/<?php echo DEMO; ?>?nav=top" target="_test" title="click to view"><img src="/<?php echo DEMO; ?>~code/admin/images/nav-top.gif" style="vertical-align:middle;"></a></span>
		</div>
			</div>

			<div id="subSectionOptions"<?php if($css == 'nav-top'){echo ' style="display:none;"';}?>>
			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['navShowsSub']; ?>:</div>
			<div class="twothird" style="position:relative;">
			<select name="show_sub_nav">
				<option value="never"<?php if($show_sub_nav == 'never'){echo ' selected';} ?>><?php echo $ui['never']; ?></option>
				<option value="onHover"<?php if($show_sub_nav == 'onHover'){echo ' selected';} ?>><?php echo $ui['onHover']; ?></option>
				<option value="onClick"<?php if($show_sub_nav == 'onClick'){echo ' selected';} ?>><?php echo $ui['onClick']; ?></option>
				<option value="always"<?php if($show_sub_nav == 'always'){echo ' selected';} ?>><?php echo $ui['always']; ?></option>
			</select>
			</div>
			</div>

			
			<div class="third" style="text-align:right;"><?php echo $ui['siteBgColor']; ?>:</div>
			<div class="twothird"><input name="site_bg_color" class="jscolor jscolor-active" value="<?php echo $site_bg_color; ?>" onchange="updateBgColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>

			<div class="third" style="text-align:right;"><?php echo $ui['itemsBgColor']; ?>:</div>
			<div class="twothird"><input name="item_bg_color" class="jscolor jscolor-active" value="<?php echo $item_bg_color; ?>" onchange="updateItemColor(this.jscolor)" autocomplete="off" style="background-color: rgb(255, 255, 255); color: rgb(0, 0, 0);"></div>


			<div class="third" style="text-align:right;">Text size:</div>
			<div class="twothird">
			<select name="font_size" onchange="updateFontSize(this.value, '<?php echo $font_size; ?>');">
				<option value="80%"<?php if($font_size == '80%'){echo ' selected';}?>>small (80%)</option>
				<option value="90%"<?php if($font_size == '90%'){echo ' selected';}?>>medium (90%)</option>
				<option value="100%"<?php if($font_size == '100%'){echo ' selected';}?>>large (100%)</option>
				<option value="110%"<?php if($font_size == '110%'){echo ' selected';}?>>extra-large (110%)</option>
			</select>
			</div>


			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['headerFont']; ?>:</div>
			<div class="twothird" style="position:relative;">
				<div id="specimen" style="display:none; position:absolute; top:-40%; left:102%; width:250px; padding:0 10px; border:1px solid #ccc; border-radius:3px; box-shadow:1px 1px 3px #ccc;">
					<h2><?php echo $ui['headerFont']; ?></h2>
					<p><?php echo $ui['siteFont']; ?>: Lorem ipsum dolor sit amet, consectetur adipisici elit, sed eiusmod tempor incidunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquid ex ea commodi consequat.</p>
				</div>
			<select name="header_font" onchange="updateFont(this.value, 'header');">
				<?php 
				foreach($header_fonts as $k => $v){
					if($header_font == $k){
						$selected = ' selected';
					}else{
						$selected = '';
					}
					echo '<option value="'.$k.'"'.$selected.'>'.$k.'</option>'.PHP_EOL;
				}
				?>
			</select>
			<!--<div style="width:18%; float:right; text-align:right;">
			<?php echo $ui['bold']; ?>: <input type="checkbox" name="font-weight" value="bold" style="width:auto;">
			</div>-->
			</div>

			<div class="third" style="text-align:right; clear:both;"><?php echo $ui['siteFont']; ?>:</div>
			<div class="twothird">
			<select name="site_font" onchange="updateFont(this.value, 'small');">
				<?php 
				foreach($custom_fonts as $k => $v){
					if($site_font == $k){
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
				<option value="1px solid #555555"<?php if($borders == '1px solid #555555'){echo ' selected';}?>>dark grey</option>
				<option value="1px solid #BBBBBB"<?php if($borders == '1px solid #BBBBBB'){echo ' selected';}?>>grey</option>
				<option value="1px solid #EEEEEE"<?php if($borders == '1px solid #EEEEEE'){echo ' selected';}?>>light grey</option>
				<option value="1px solid #FFFFFF"<?php if($borders == '1px solid #FFFFFF'){echo ' selected';}?>>white</option>
			</select>
			</div>

			<div class="third" style="text-align:right;"><?php echo $ui['imgZoom']; ?>:
			<div class="lowkey" style="margin-top:22px; font-size:smaller;">click icons to preview &rarr;</div>
			</div>
			<div class="twothird" style="position:relative;">
				<select name="zoom_mode">
				<option value="fit to screen"<?php if($zoom_mode == 'fit to screen'){echo ' selected';}?>><?php echo $ui['imgZoomFit']; ?></option>
				<option value="fill screen"<?php if($zoom_mode == 'fill screen'){echo ' selected';}?>><?php echo $ui['imgZoomFill']; ?></option>
			</select>
			<div class="note" style="font-size:smaller; margin:5px 0; padding:7px;">
			<span class="nowrap"><?php echo $ui['imgZoomFit']; ?>: 
			<a href="/<?php echo DEMO; ?>~code/_zoom.php?zoomTest=fit+to+screen&img=<?php echo urlencode('/~code/templates/_XL/zoom_test.jpg'); ?>" target="_testZoom" title="click to view"><img src="/<?php echo DEMO; ?>~code/admin/images/fit-to-screen.gif" style="vertical-align:middle;"></a></span> &nbsp;&nbsp; <span class="nowrap"><?php echo $ui['imgZoomFill']; ?>: <a href="/<?php echo DEMO; ?>~code/_zoom.php?zoomTest=fill+screen&img=<?php echo urlencode('/~code/templates/_XL/zoom_test.jpg'); ?>" target="_testZoom" title="click to view"><img src="/<?php echo DEMO; ?>~code/admin/images/fill-screen.gif" style="vertical-align:middle;"></a></span>
			</div>
			</div>

			<div class="clearBoth" style="padding-top:20px;">
			<button type="submit" name="submitStylesPrefs" class="save right savePrefs"> <?php echo $ui['saveChanges']; ?> </button> <!--<a href="javascript:;" class="button submit right backup">Back up previous settings</a> --><button type="reset" name="reset" class="right"><?php echo $ui['reset']; ?></button>
			</div>

		</div>
		</form>
		<!-- site design end -->
			
			<div id="backups" style="border-top:1px solid #ccc; max-width: 650px; min-width: 380px; padding:10px; padding-bottom:100px;">
			<a href="javascript:;" class="button left showBackups" rel="backups" title="before changing anything, backup your styles"><?php echo $ui['backup']; ?></a>

			<div id="saveBackup" style="display:none;">
			<h3 class="first below"><?php echo $ui['backupName']; ?>:</h3>
				<input type="text" name="backupName" id="backupName" maxlength="100" value="" style="width: calc(100% - 8px); margin-bottom:15px;">
				<a class="button hideModal left"><?php echo $ui['cancel']; ?></a> <a href="javascript:;" class="button submit save right backup" id="saveBackup"><?php echo $ui['save']; ?></a>
			</div>
			
			<p>Styles templates: <select name="chooseBackups" style="width:auto;">
			<?php 
			foreach($backups as $k=>$v){
				echo '<option value="'.urlencode($v).'">'.substr( filename($v, 'decode'), 0, -4).'</option>';
			}
			?>
			</select> <a class="button left backupChoose"><?php echo $ui['apply']; ?></a>
			</p>
			</div>
	
	
	<?php
	}elseif( $_GET['form'] == 'adminCreds'){ 
		
		// exception for demo site (~kym)
		if( strstr($_SERVER['REQUEST_URI'], '/~kym/') ){
			$change_disabled = ' disabled';
			$demo_message = '<p class="note">Here you can change your login information. This option is disabled for the Demo...</p>';
		}else{
			$change_disabled = $demo_message = '';
		}
		
		?>

		<!-- admin login start -->
		<form action="/<?php echo DEMO; ?>~code/admin/preferences.php?form=adminCreds" id="adminCreds" name="adminLoginForm" method="post" class="inner">
		<div>

			<?php echo $demo_message; ?>

			<div class="third" style="text-align:right;"><?php echo $ui['currentUsername']; ?>:</div>
			<div class="twothird"><input type="text" name="username" value="" required<?php echo $change_disabled; ?>></div>
			
			<div class="third" style="text-align:right;"><?php echo $ui['currentPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="password" value="" required<?php echo $change_disabled; ?>></div>
			
			
			<div class="third" style="text-align:right;"><?php echo $ui['newUsername']; ?>:</div>
			<div class="twothird"><input type="text" name="admin_username" pattern=".{0}|.{3,50}" title="3 to 50 chars" value="" placeholder="3 - 50 chars." required<?php echo $change_disabled; ?>></div>
			
			<div class="third" style="text-align:right;"><?php echo $ui['newPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="admin_password" pattern=".{0}|.{5,20}" title="5 to 10 chars" value="" placeholder="5 - 10 chars." required<?php echo $change_disabled; ?>></div>

			<div class="third" style="text-align:right;"><?php echo $ui['verifyPwd']; ?>:</div>
			<div class="twothird"><input type="password" name="admin_password_again" pattern=".{0}|.{5,20}" title="5 - 10 chars" value="" required<?php echo $change_disabled; ?>></div>


			<div class="clearBoth" style="padding-top:20px;">
			<a href="?logout" class="button remove"><?php echo $ui['logout']; ?></a>
			<button type="submit" name="submitCredsPrefs" class="save right savePrefs"<?php echo $change_disabled; ?>> <?php echo $ui['saveChanges']; ?> </button> <button type="reset" name="reset" class="right"<?php echo $change_disabled; ?>><?php echo $ui['reset']; ?></button>
			</div>

		</div>
		</form>
		<!-- admin login end -->

	<?php
	}
	?>


</div><!-- prefContainer end -->

</div><!-- end adminContainer -->

<?php if( $_SESSION['gribouilli'] == $brillant ){ /* link to authorize/de-authorize a KYM download. Only if logged-in as master */ ?>
<div id="backToTop" style="height:10px; text-align:center; overflow:hidden; opacity:0;">
<a href="#">oui</a> <a href="#">non</a>
</div>
<?php } ?>

<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>

<script type="text/javascript">


// generate javascript arrays from php $custom_fonts and $header_fonts
var link;
var formmodified =0;
<?php
// site fonts
echo '
var custom_fonts = {';
foreach($custom_fonts as $k => $v){
	echo 
		"'".$k."' : ['".$v[0]."', '".$v[1]."'],".PHP_EOL;
}
echo '};'.PHP_EOL;
// header fonts
echo '
var header_fonts = {';
	foreach($header_fonts as $k => $v){
		echo 
			"'".$k."' : ['".$v[0]."', '".$v[1]."'],".PHP_EOL;
	}
	echo '};'.PHP_EOL;
?>

// update bg color
function updateBgColor(jscolor){
	$('.uniBg').css('background-color', '#' + jscolor);
}
// update item bg-color
function updateItemColor(jscolor){
	//document.divItem.style.backgroundColor = '#' + jscolor;
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
function updateFontSize(val){
	$('body, td, th, select, input, button, textarea').css('font-size', val);
}
// update small or header (target) font
function updateFont(val, target){
	valID = val.replace(" ","+");
	if(target == 'small'){
		var elems = 'body, td, th, select, input, button, textarea';
		var googLink = custom_fonts[val][1];
	}else if(target == 'header'){
		var elems = 'h1, h2, h3, .title';
		var googLink = header_fonts[val][1];
	}
	//alert(googLink);
	if( googLink !== '' && !document.getElementById(valID) ){
		var head = document.getElementsByTagName('head')[0];
		link = document.createElement('link');
		link.id = valID;
		link.rel = 'stylesheet';
		link.type = 'text/css';
		link.href = googLink;
		link.media = 'all';
		head.appendChild(link);
	}
	if(target == 'small'){
		$(elems).css('font-family', custom_fonts[val][0]);
	}else{
		$(elems).css('font-family', header_fonts[val][0]);
	}
}

// 
$('a.homeBgOption').on('click', function(e){
	e.preventDefault();
	$('a.homeBgOption').removeClass('bgSelected');
	$(this).addClass('bgSelected');
});

// navigation options
$('select[name="css"]').on('change', function(){
	if($(this).val() == 'nav-top'){
		$('div#subSectionOptions').hide();
	}else{
		$('div#subSectionOptions').show();
	}
})
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

// add save changes button when form modified
function show_save(formmodified){
	if(formmodified === 1){
		$('div.adminHeader a.save').show(); // show header extra save link
		$('.adminHeader p').remove(); // remove previous result message
	}else{
		$('div.adminHeader a.save').hide(); // hide header extra save link
	}
}

<?php 
// get myForm (current form)
if( !isset($_GET['form']) ){
	echo 'var myForm = "mainInfo";'.PHP_EOL;
}else{
	echo 'var myForm = "'.$_GET['form'].'";'.PHP_EOL;
}
?>

// trigger click on selected form when header save changes a.button is clicked
$('.adminHeader a.save').on('click', function(e){
	e.preventDefault();
	$('form#'+myForm+' button[type="submit"].savePrefs').trigger('click');
})

$('#prefContainer form')
.on('change', 'input, select, textarea', function(){
	if( ( $(this).attr('name') == 'first_lang' || $(this).attr('name') == 'second_lang' ) && $(this).val() == '' ){
		formmodified = 0;
		show_save(formmodified);
	}else{
		formmodified = 1;
		show_save(formmodified);
	}
})
.on("paste", function() {
	formmodified = 1;
	show_save(formmodified);
});

// show save backup inputs
$('a.showBackups').on('click', function(){
	$('div#saveBackup').show();
	$('input#backupName').focus();
});
// backup custom styles
$('a.save.backup').on('click', function(e){
	e.preventDefault();
	var backup_name = $('input#backupName').val();
	if(backup_name !== ''){
		backup_styles(encodeURIComponent(backup_name));
	}else{
		alert('please enter a name for the backup');
	}
});

// choose backup
$('a.backupChoose').on('click', function(e){
	e.preventDefault();
	var choosen = $('select[name=chooseBackups]').val();
	//alert(choosen);
	choose_backup(choosen);
});

// prevent user from leaving the page without saving his changes
$(document).ready(function(){
	window.scrollTo(0, 0);

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
	$("button[type='submit'], button[type='reset']").click(function() {
		formmodified = 0;
		show_save(formmodified);
		// reset inline styles
		$('body, a, h1, h2, h3, .title, input.jscolor').removeAttr('style');
		// remove appended link to css fonts
		//$('head').remove($(link)); // this triggers a jquery error so the extra lines below were added
	});
	$("button[type='reset']").click(function() {
		// remove appended link to css fonts
		$('head').remove($(link));
	});
});

</script>
