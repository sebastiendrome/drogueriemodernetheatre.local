<?php
/*********** 1: UTILITY FUNCTIONS (USED WITHIN OTHER FUNCTIONS) ***************/

/* COPY DIRECTORY AND ITS CONTENTS */
function copyr($source, $dest){
	if (is_file($source)) {// Simple copy for a file
		return copy($source, $dest);
	}
	if (!is_dir($dest)) {// Make destination directory
		mkdir($dest,0777);
	}
	$dir = dir($source);// Loop through the folder
	while (false !== $entry = $dir->read()) {
		if ($entry == '.' || $entry == '..') {// Skip pointers
			continue;
		}
		if ($dest !== "$source/$entry") {// Deep copy directories
			copyr("$source/$entry", "$dest/$entry");
		}
	}
	$dir->close();// Clean up
	return true;
}

/* FUNCTION TO REMOVE DIRECTORY AND ITS CONTENTS */
function rmdirr($dirname){
	if (!file_exists($dirname)){// Sanity check
		return false;
	}
	if (is_file($dirname)){// Simple delete for a file
		return unlink($dirname);
	}
	$dir = dir($dirname);// Loop through the folder
	while (false !== $entry = $dir->read()){
		if ($entry == '.' || $entry == '..'){// Skip pointers
			continue;
		}
		rmdirr("$dirname/$entry");// Recurse
	}
	$dir->close();// Clean up
	return rmdir($dirname);
}
/* validate new section name (format: "english, deutsch") */
function validate_section_name($newName){
	$en = $de = '';
	// remove dangerous characters
	$newName = strip_tags($newName);
	$newName = str_replace(array("\\", "\t", "\n", "\r", "(", ")", "/"), '', $newName);

	// cannot start with more than one underscore
	$newName = preg_replace('/^_+/', '_', $newName);

	// no comma, just add it to end of string, and duplicate name
	if(!strstr($newName, ',')){
		$newName .= ', '.$newName;

	}else{ // deal with at least one comma, maybe more?...
		$pieces = explode(',', $newName); // split string by comma
		$en = trim($pieces[0]);
		$de = trim($pieces[1]);
		
		// names reserved for system
		if( preg_match(SYSTEM_NAMES, $en) ){
			return false;
		}
		
		if(empty($de)){
			$newName = $en.', '.$en;
		}elseif(empty($en)){
			$newName = $de.', '.$de;
		}else{
			$newName = $en.', '.$de;
		}
	}
	return $newName;
}
/* sanitize user input */
function sanitize_text($input, $allowed_tags = ''){
	if( empty($allowed_tags) ){
		$allowed_tags = ALLOWED_TAGS;
	}
	$input = 
	preg_replace('/on(load|unload|click|dblclick|mouseover|mouseenter|mouseout|mouseleave|mousemove|mouseup|keydown|pageshow|pagehide|resize|scroll)[^"]*/i', '', $input);
	$input = addslashes( strip_tags($input, $allowed_tags) );
	return $input;
}
/* human file size */
function FileSizeConvert($bytes){
	$bytes = floatval($bytes);
		$arBytes = array(
			0 => array(
				"UNIT" => "TB",
				"VALUE" => pow(1024, 4)
			),
			1 => array(
				"UNIT" => "GB",
				"VALUE" => pow(1024, 3)
			),
			2 => array(
				"UNIT" => "MB",
				"VALUE" => pow(1024, 2)
			),
			3 => array(
				"UNIT" => "KB",
				"VALUE" => 1024
			),
			4 => array(
				"UNIT" => "B",
				"VALUE" => 1
			),
		);

	foreach($arBytes as $arItem){
		if($bytes >= $arItem["VALUE"]){
			$result = $bytes / $arItem["VALUE"];
			$result = str_replace(".", "," , strval(round($result, 2)))." ".$arItem["UNIT"];
			break;
		}
	}
	return $result;
}
/* echo random string */
function rand_string($length = 10){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $char_length = strlen($characters);
    $rand_string = '';
    for ($i = 0; $i < $length; $i++) {
        $rand_string .= $characters[rand(0, $char_length-1)];
    }
    return $rand_string;
}

/* generate menu file output from 3D array */
function array_to_menu_file($menu_array){
	$menu_file = '';
	foreach($menu_array as $key => $val){
		if(!empty($key)){ // don't generate empty lines
			$menu_file .= $key."\n";
			if(!empty($val)){ // don't generate empty lines
				foreach($val as $k => $v){
					$menu_file .= "\t".$k."\n";
					if(!empty($v)){
						foreach($v as $vk => $vv){
							$menu_file .= "\t\t".$vk."\n";
						}
					}
				}
			}
		}
	} 
	return $menu_file;
}

/* insert item in associative array at specific position */
function insert_at($array = [], $item = [], $position = 0) {
	$previous_items = array_slice($array, 0, $position, true);
	$next_items	 = array_slice($array, $position, NULL, true);
	return $previous_items + $item + $next_items;
}

/* change $parents(string) = 
* "parent_1, parent_1qQqparent_2, parent_2" 
* to array
* $parents[0]='parent_1'; $parents[1]='parent_2'; 
*/
function string_to_array($parents_string, $glue){
	if( !empty($parents_string) ){
		if( strstr($parents_string, $glue) ){
			$parents_array = explode($glue, $parents_string);
		}else{
			$parents_array = array($parents_string);
		}
	}else{
		$parents_array = '';
	}
	return $parents_array;// array or empty string
}
	

/* scan a directory, filter out system files, return array */
function scan_dir($dir, $types = ''){
	$files = array();
	if( $handle = opendir($dir) ){
		while( false !== ($entry = readdir($handle) ) ){
			// filter out system files, home page bg image, and '-de' versions (for articles)
			if(substr($entry, 0, 1) !== '.' && file_name_no_ext($entry) !== BG && substr(file_name_no_ext($entry), -3) !== '-de' ){
				if( empty($types) ){
					$files[] = $entry;
				}elseif( preg_match( $_POST['types'][$types], file_extension($entry) ) ){
					$files[] = $entry;
				}
			}
		}
		closedir($handle);
		// check if there are some files, or return false
		if( !empty($files) ){
			return $files;
		}else{
			return false;
		}
	}else{
		return false;
	}
}


/*********** 2: DISPLAY FUNCTIONS (FUNCTIONS THAT OUTPUT HTML MARKUP) ***************/

// display site structure from menu array
function site_structure($menu_array = array(), $parents = array(), $pos = 1, $context = ''){
	
	global $ui;

	// n will increment menu items position
	//$pos = 1;
	$count = count($menu_array);
	$site_structure = $path = $data_parents = $sub_class = '';

	$site_is_empty = true;
	
	// generate menu array from menu.txt file, if no array is provided
	if( empty($menu_array) ){
		$menu_array = menu_file_to_array();
	}
	
	/*echo '<pre>';
	print_r($menu_array);
	echo '</pre>';*/
	
	// open main ul container
	if( empty($parents) ){
		$data_parents .= '';
		$site_structure .= '<ul class="structure" data-parents="'.$data_parents.'">';
	// get path of current item relative to its parents
	}else{
		foreach($parents as $p){
			list($en, $de) = explode(',', $p);
			$path .= filename($en, 'encode').'/';
			$sub_class .= 'sub';
		}
		$data_parents = implode("qQq", $parents); //"a section, une sectionqQqanother one, une autre"
	}
	
	foreach($menu_array as $key => $var){
		if( !empty($key) ){ // ignore empty items
			
			$site_is_empty = false;

			/*------------- SECTIONS -------------*/
			if(strstr($key, ',')){

				$sub_class .= ' section';
			
				list($en, $de) = explode(',', $key);
				$item = $path.filename($en, 'encode');

				/* hidden vs. published sections */
				// hidden
				if(substr($key,0,1) == '_'){
					// remove _ (underscore) from name
					$display_name =  substr($key, 1);
					$status = ' hidden';
					$show_hide = $ui['publish'];
					$sh_class = 'show';
					$sh_title = $ui['publishTitle'];
					// published
				}else{
					$display_name = $key;
					$status = '';
					$show_hide = $ui['hide'];
					$sh_class = 'hide';
					$sh_title = $ui['hideTitle'];
				}

				// show bilingual (en, de) name only if bilingual
				if(BILINGUAL == 'yes'){
					$title_help = ' ('.FIRST_LANG.', '.SECOND_LANG.')';
				}else{
					$display_name = preg_replace('/ ?,.*/', '', $display_name);
					$title_help = '';
				}

				/* html output for a section */
				$site_structure .= '
				<li data-name="'.$key.'" data-oldposition="'.$pos.'" class="'.$sub_class.$status.'">
				<a href="javascript:;" class="up" title="'.$ui['moveUpTitle'].'"></a>
				<a href="javascript:;" class="down" title="'.$ui['moveDownTitle'].'"></a>';
				if( $context == 'manage_contents' ){
					$site_structure .= '<span class="fileTitle">'.$ui['subSec'].' <span class="tip" data-tip="'.$ui['viewSite'].'"><a href="/'.DEMO.$item.'/" target="_tab" class="openNew" style="font-weight:normal; font-size:smaller; margin-left:0;">
					<svg viewBox="0 0 15 13">
					<rect x="0.5" y="0.5" width="15" height="12"/>
					<path d="M-2.4,11.1c0.1-0.6,0.5-4.6,7.1-4.4"/>
					<line x1="5.7" y1="6.5" x2="2.8" y2="9.6"/>
					<line x1="2.5" y1="4.2" x2="5.6" y2="7.1"/>
					<line x1="1" y1="2.5" x2="14" y2="2.5"/>
					</svg>
					</a></span></span><br>';
				}
				$site_structure .= '<span class="nowrap">
				<span class="tip" data-tip="'.$ui['changePosTitle'].'"><input type="text" class="position" name="order'.$pos.'" value="'.$pos.'" maxlength="6"></span><span class="tip" data-tip="'.$ui['changeSecNameTitle'].$title_help.'"><input type="text" class="nameInput" name="'.$key.'" value="'.$display_name.'" maxlength="100"></span>
				</span>';
				$site_structure .= ' 
				<span class="nowrap">
				<a href="manage_contents.php?item='.urlencode($item).'" class="edit" title="'.$ui['editSecTitle'].'">'.$ui['edit'].'</a>';
				// !!!!!! allow only one sub-level of section!
				if( empty($parents) ){
					$site_structure .= ' <a href="javascript:;" class="newSub add showModal" rel="createSection?parents='.urlencode($key).'" title="'.$ui['newSubSecTitle'].'">'.$ui['newSubSection'].'</a>';
				}
				$site_structure .= ' <a href="javascript:;" class="delete remove deleteSection" title="'.$ui['deleteSecTitle'].'">'.$ui['delete'].'</a></span>';
				$site_structure .= '<a href="javascript:;" class="'.$sh_class.'" title="'.$sh_title.'">'.$show_hide.'</a> ';
				if( empty($var) ){
					$site_structure .= '<span class="empty">('.$ui['emptySection'].')</span>';
				}
				// let's not close the section <li> yet, so that its contents are offset

			/*------------- FILES -------------*/
			}else{

				// get file extension (including dot: ".jpg")
				$ext = file_extension($key);
				// various ways to display file depending on extension
				if( preg_match($_POST['types']['resizable_types'], $ext) ){
					// rest of path to file
					$path_to_file = UPLOADS.'_S/'.$key;
					// link to file
					$file_link = '/'.CONTENT.$path_to_file;
				}else{
					// rest of path to file
					$path_to_file = UPLOADS.'_XL/'.$key;
					// link to file
					$file_link = '/'.DEMO.'~code/images/'.substr($ext,1).'.png';
				}

				$txt_file_name = preg_replace('/'.preg_quote($ext).'$/', '.txt', $key);
				$txt_file = UPLOADS.'/en/'.$txt_file_name;
				if( file_exists(ROOT.CONTENT.$txt_file) ){
					$description = strip_tags( file_get_contents(ROOT.CONTENT.$txt_file) );
				}else{
					$description = '';
				}
				
				if( empty($description) ){
					$description = filename($key, 'decode');
					$description = substr($description, 0, -11).$ext;
				}else{
					$description = str_replace(array("\'", '\"'), array('&#39;','&quot;'), $description);
					$description = substr($description, 0, 35);
				}
				/* html output for a file */
				$site_structure .= '<li data-name="'.$key.'" data-oldposition="'.$pos.'">
				<a href="javascript:;" class="up" title="'.$ui['moveUpTitle'].'"></a>
				<a href="javascript:;" class="down" title="'.$ui['moveDownTitle'].'"></a>
				<span class="nowrap">
				<span class="tip" data-tip="'.$ui['changePosTitle'].'"><input type="text" class="position" name="order'.$pos.'" value="'.$pos.'" maxlength="6"></span><input type="text" class="imgInput" name="'.$key.'" style="background-image:url('.$file_link.');" value="'.$description.'" onclick="this.blur();window.location.href=\'manage_contents.php?item='.urlencode(substr($path, 0, -1)).'#'.preg_replace('/[^A-Za-z0-9]/', '', $key).'\';" title="'.$ui['editFileTitle'].'">
				</span>
				 
				<span class="nowrap">
				<a href="manage_contents.php?item='.urlencode(substr($path, 0, -1)).'#'.preg_replace('/[^A-Za-z0-9]/', '', $key).'" class="edit" title="'.$ui['editFileTitle'].'">'.$ui['edit'].'</a> 
				<a href="javascript:;" class="delete showModal remove" rel="deleteFile?parentsPath='.urlencode($path).'&file='.urlencode($path_to_file).'" title="'.$ui['deleteFileTitle'].'">'.$ui['delete'].'</a>
				</span>
				</li>'.PHP_EOL;
			}

			
			// section ($key) contains something ($var), reiterate the function call
			if( !empty($var) ){
				// add containing section ($key) to parents
				$parents[] = $key;

				//print_r($parents);
				
				// add to data-parents parents for the containing ul
				$data_parents = implode("qQq", $parents);
				//$site_structure .= '<p>'.$data_parents.'</p>';
				$site_structure .= '<ul data-parents="'.$data_parents.'">';
				$site_structure .= site_structure($var, $parents);
				
				// !!!!!!! works only because one level of sub-section allowed
				// remove last added key of $parents array
				foreach($parents as $k => $v){
					if($v == $key){
						unset($parents[$k]);
					}
				}
				
				$site_structure .= '</ul>';
			}

			// let's close the section opening <li>, now that we've embeded the section content in it
			$site_structure .= '<div class="clearBoth"></div>
			</li>'.PHP_EOL;

			$pos++;

		} // end if(!empty($key))

	} // end foreach($menu_array as $key) 

	// close main ul container
	if( empty($parents) ){
		$site_structure .= '</ul>';
	}

	// if it is still empty, the site is empty. Output a welcome message 
	if( $site_is_empty ){
		return '<div id="welcome">
		
		'.$ui['welcome'].'
		
		</div>';
	}else{
		return $site_structure;
	}
}


// display section or sub-section contents
function display_content_admin($path = '', $menu_array = ''){
	
	global $ui;
	
	$parents = array();
	$sub_class = '';

	// if no path provided, use SESSION[item] if possible.
	if( empty($path) ){
		if( isset($_SESSION['item']) && !empty($_SESSION['item']) ){
			$path = $_SESSION['item'];
		}else{ // if no session, then we can't know the path, so let's just display an error message.
			$display = '<p class="error">Oops, your session has expired. Please <a href="manage_structure.php" class="button">refresh</a></p>';
			return $display;
		}
	}

	// if no menu_array provided, generate menu array from menu.txt file
	if( empty($menu_array) ){
		$menu_array = menu_file_to_array();
		
		// get current directory (=section or sub-section)
		$dir = basename($path);
		// if current directory($dir) != path, we're dealing with a sub section, set $parent_dir
		if($dir !== $path){
			$parent_dir = str_replace('/'.$dir, '', $path);
		}
		
		// no parents dir, so attempt to match current directory (=section) to top level of menu_array (=menu_array[key])
		if( !isset($parent_dir) ){
			foreach($menu_array as $k => $v){
				//$display .=  $k.'<br>';
				if( preg_match('/^'.preg_quote(filename($dir, 'decode')).',/', $k) ){
					$parent = $k;
					$parents[] = $k;
					// and generate sub-array of items accordingly
					$depth_array = $menu_array[$k];
					break;
				}
			}
		// else, attempt to match current directory to sub level of menu_array(=menu_array[key][val])
		}else{
			$sub_class .= 'sub';
			foreach($menu_array as $k => $v){
				//$display .=  $k.'<br>';
				if( preg_match('/^'.preg_quote(filename($parent_dir, 'decode')).',/', $k) ){
					$parents[] = $k;
					foreach($v as $vk => $vv){
						if( preg_match('/^'.preg_quote(filename($dir, 'decode')).',/', $vk) ){
							$parent = $k.'/'.$vk;
							$parents[] = $vk;
							// and generate sub-sub array of items accordingly
							$depth_array = $menu_array[$k][$vk];
							break;
						}
					}
				}
			}
		}
	}

	$data_parents = implode('qQq', $parents);
	// debug
	// echo $data_parents;
	
	$n = 0;
	
	$display = '<ul class="content" data-parents="'.$data_parents.'">';
	
	// loop through the files if there are any
	if( !empty($depth_array) ){
		foreach($depth_array as $key => $val){

			// ignore empty array keys (empty line in menu.txt file)
			if( !empty($key) ){

				$n++;
				
				// SECTIONS
				if( strstr($key, ',') ){
					
					$sub_array[$key] = $val;
					// repeat the ul but with class = "structure" for css consistency
					$display .= '<ul class="structure" data-parents="'.$data_parents.'">';
					$display .= site_structure($sub_array, $parents, $n, 'manage_contents');
					$display .= '</ul>';
					unset($sub_array);
				
					
				// FILES
				}else{
					
					$anchor = preg_replace('/[^A-Za-z0-9]/', '', $key);
					$display .= '<li data-name="'.$key.'" data-oldposition="'.$n.'">
					<a name="'.$anchor.'"></a>
					<a href="javascript:;" class="up" title="'.$ui['moveUpTitle'].'"></a>
					<a href="javascript:;" class="down" title="'.$ui['moveDownTitle'].'"></a>';

					$ext = file_extension($key);
					$item = UPLOADS.'_XL/'.$key; // default
					
					$display_file = display_file_admin($key);

					// get text description english and deutsch versions
					$txt_filename = preg_replace('/'.preg_quote($ext).'/', '.txt', $key);
					$en_file = UPLOADS.'en/'.$txt_filename;
					$de_file = UPLOADS.'de/'.$txt_filename;
					
					// create txt files if they don't already exist
					if(!file_exists(ROOT.CONTENT.$en_file)){
						if(!$fp = fopen(ROOT.CONTENT.$en_file, "w")){
							echo '<p class="error">could not create EN text file</p>';
						}
					}
					if(!file_exists(ROOT.CONTENT.$de_file)){
						if(!$fp = fopen(ROOT.CONTENT.$de_file, "w")){
							echo '<p class="error">could not create DE text file</p>';
						}
					}
					// get content of text files
					$en = stripslashes( my_br2nl( file_get_contents(ROOT.CONTENT.$en_file) ) );
					$de = stripslashes( my_br2nl( file_get_contents(ROOT.CONTENT.$de_file) ) );
					
					// various ways to display file depending on extension
					// images
					if( preg_match($_POST['types']['resizable_types'], $ext) ){
						$item = UPLOADS.'_S/'.$key;
						$file_title = 'Image';
						$action_title = $action_file = '';
						// show rotate option for jpg images only (does not work on gif and png)
						if( file_extension($key) == '.jpg' ){
							$action_file .= '<span class="tip" data-tip="'.$ui['rotDesc'].'"><a class="button discret rotate" data-rotate="90">'.$ui['rotate'].'</a></span>
							<span class="tip" data-tip="'.$ui['cancel'].'"><a href="javascript:;" class="button left cancel"></a></span>
							<span class="tip" data-tip="'.$ui['save'].'"><a href="javascript:;" class="button submit save left" style="background-color:#25850d;"><span class="checkmark white">&nbsp;</span></a></span>';
						}
					
					// txt & html (articles)
					}elseif( preg_match($_POST['types']['text_types'], $ext) ){
						$file_title = 'Article';
						$action_title = ' title="'.$ui['editFileTitle'].'"';
						$action_file = '<a class="button edit discret" href="/'.DEMO.'~code/admin/edit_text.php?item='.urlencode($item).'" title="'.$ui['editFileTitle'].'">'.$ui['edit'].'</a>';

					}elseif( $ext == '.emb'){
						$file_title = 'Media';
						$action_title = ' title="'.$ui['editFileTitle'].'"';
						$action_file = '<a class="button edit showModal discret" href="javascript:;" rel="embedMedia?path='.urlencode($item).'" title="'.$ui['editFileTitle'].'">'.$ui['edit'].'</a>';
						
					}elseif( $ext == '.gal'){
						$file_title = $ui['gallery'];
						$action_title = ' title="'.$ui['editFileTitle'].'"';
						$action_file = '<a class="button edit showModal discret" href="javascript:;" rel="gallery?path='.urlencode($item).'" title="'.$ui['editFileTitle'].'">'.$ui['edit'].'</a>';
					
					}else{
						$file_title = substr($ext,1).' file';
						$action_title = '';
						$action_file = '';
					}
					
					
					// html output for a file
					$display .= '<div class="imgContainer"'.$action_title.'><p>
					<span class="tip" data-tip="'.$ui['changePosition'].'"><span class="tip" data-tip="'.$ui['changePosTitle'].'"><input type="text" class="position" name="order'.$n.'" value="'.$n.'" maxlength="6"></span></span>
					<span class="fileTitle">'.$file_title.'</span> <span class="tip" data-tip="'.$ui['viewSite'].'"><a class="openNew" href="/'.DEMO.$path.'/#'.$anchor.'" target="_tab">
					<svg viewBox="0 0 19 13">
					<rect x="3.49" y="0.5" width="15" height="12"/>
					<line x1="3.49" y1="3" x2="18.49" y2="3"/>
					<path d="M.5,11.37C.56,10.76,1,6.81,7.56,7"/>
					<line x1="8.57" y1="6.71" x2="5.71" y2="9.8"/>
					<line x1="5.37" y1="4.47" x2="8.45" y2="7.3"/>
					</svg>
					</a></span></p>';
					$display .= $display_file;
					$display .= '<p>';
					$display .= '<a href="javascript:;" class="button remove showModal discret left" rel="deleteFile?file='.urlencode($item).'&parentsPath='.$path.'" title="'.$ui['deleteFileTitle'].'">'.$ui['delete'].'</a>';

					$display .= $action_file;
					
					$display .= '</p>
					</div>';
					// texts
					$display .= '<div class="actions">
					<input type="hidden" class="file" value="'.$item.'">
					<p><span class="tip" data-tip="'.$ui['textDescDescription'].'">'.$ui['textDescription'].':</span> <span class="tags">'.$ui['formatTags'].': &lt;b>&lt;u>&lt;i>&lt;a> <span class="tagTip">&lt;b><b>'.$ui['bold'].'</b>&lt;/b> &lt;u><u>'.$ui['underline'].'</u>&lt;/u> &lt;i><i>'.$ui['italic'].'</i>&lt;/i> &lt;a&nbsp;href="http://yourlink.com">'.$ui['link'].'&lt;/a></span></span></p>';
					
					$display .= '<span class="below l2">'.FIRST_LANG.'<br></span>
					<input type="hidden" class="enMemory" value="'.str_replace('"', '&quot;', $en).'">
					<textarea class="en" name="en_txt" maxlength="1000">'.$en.'</textarea>
					<span class="below l2">'.SECOND_LANG.'<br></span>
					<input type="hidden" class="deMemory" value="'.str_replace('"', '&quot;', $de).'">
					<textarea class="de l2" name="de_txt" maxlength="1000">'.$de.'</textarea>
					<a href="javascript:;" class="button submit saveText disabled save right">'.$ui['saveChanges'].'</a>';
					$display .= '</div>';
				}

				$display .= '<div class="clearBoth"></div>';
				$display .= '</li>';
			}
		}
	}else{
		$display .= '<p style="opacity:.5;">'.$ui['emptySecNote'].'</p>';
	}
	
	$display .= '</ul>';
	
	return $display;
}


// display file
function display_file_admin($file_name, $raw = FALSE){

	global $ui; 
	$ext = file_extension($file_name);
	
	// various ways to display file depending on extension
	// 1. resizable types (jpg, png, gif)
	if( preg_match($_POST['types']['resizable_types'], $ext) ){
		$item = UPLOADS.'_S/'.$file_name;
		// url link to file
		$file_link = '/'.CONTENT.$item;
		$display_file = '<a href="'.preg_replace('/\/(_S|_M|_L)\//', '/_XL/', $file_link).'" title="'.$ui['viewLarge'].'" target="_blank" class="aImg" data-bgimg="'.$file_link.'" style="background-image:url('.$file_link.'?rand='.rand(111,999).');"></a>';
		
	}else{
		// if not an image, the file is in the _XL directory (no various sizes)
		$item = UPLOADS.'_XL/'.$file_name;
		// url link to file
		$file_link = '/'.CONTENT.$item;
		
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
			$display_file = PHP_EOL.'<audio controls style="width: calc(100% - 2px); border:1px solid #ccc;">
			<source src="/'.CONTENT.$item.'" type="audio/'.$media_type.'">
			Sorry, your browser doesn\'t support HTML5 audio.
			</audio>'.PHP_EOL;

		}elseif( preg_match($_POST['types']['video_types'], $ext) ){ // text video files
			if($ext == '.m4v'){
				$media_type = 'mp4';
			}elseif($ext == '.ogv'){
				$media_type = 'ogg';
			}else{
				$media_type = substr($ext, 1);
			}
			$display_file = PHP_EOL.'<video controls style="width: calc(100% - 2px); border:1px solid #ccc;">
			<source src="/'.CONTENT.$item.'" type="video/'.$media_type.'">
			Sorry, your browser doesn\'t support HTML5 video.
			</video>'.PHP_EOL;

		}elseif( preg_match($_POST['types']['text_types'], $ext) ){
			$inner_html = file_get_contents(ROOT.CONTENT.$item);
			// apply styles to entire text item, by parsing style comment
			if( preg_match('/<!-- qQqStyleqQq-.*? -->/', $inner_html, $matches) ){
				$item_style_string = str_replace(array('<!-- qQqStyleqQq-', ' -->'),'', $matches[0]);
				$inline_styling = ' style="'.$item_style_string.'"';
			}else{
				$inline_styling = '';
			}
			if($ext == '.txt'){ // txt
				$display_file = '<div class="txt admin"'.$inline_styling.'>'.my_nl2br( strip_tags( $inner_html , str_replace('<a>','',ALLOWED_TAGS) ) ).'</div>';
			
			}elseif( preg_match('/s?html?/', $ext) ){ // html
				
				$display_file = '<div class="html admin uniBg"'.$inline_styling.'>'.strip_tags( $inner_html , str_replace('<a>','',ALLOWED_TAGS) ).'</div>';
			}

		}elseif($ext == '.emb'){ // embeded media
			$display_file = '<div class="html admin uniBg">'.file_get_contents(ROOT.CONTENT.$item).'</div>';
		
		}elseif($ext == '.svg'){ // svg
			$file_link = '/'.CONTENT.$item;
			$display_file = '<div class="html admin"><a href="'.$file_link.'" title="'.$ui['viewLarge'].'" target="_blank">';
			$display_file .= file_get_contents(ROOT.CONTENT.$item);
			$display_file .= '</a></div>';
			
		}elseif($ext == '.gal'){ // gallery
			

			//$display_file = display_gallery_admin($item);
			
			
			$gal = '';
			$contents = file_get_contents(ROOT.CONTENT.$item);
			$img_array = explode(PHP_EOL, trim($contents));
			//$img_count = count($img_array);
			if( !empty($img_array[0]) ){
				$gal .= '<h3>'.$ui['gallery'].', '.count($img_array).' images</h3>';
				foreach($img_array as $img){
					$im = str_replace( array('/_XL/', '/_M/', '/_L/'), '/_S/', $img);
					$gal .= '<span style="background-image:url(/'.CONTENT.$im.');">&nbsp;</span>';
				}
			}else{
				$gal .= '<h3>'.$ui['gallery'].'</h3>'.PHP_EOL.'<span class="note">'.$ui['emptyGal'].'</span>';
			}
			
			$display_file = '<div class="html admin gal uniBg">'.$gal.'</div>';
			
		
		}else{
			if($raw){
				$a_start = $a_end = '';
			}else{
				$a_start = '<a href="'.str_replace('/_S/', '/_XL/', $file_link).'" title="view file in a new window" target="_blank">';
				$a_end = '</a>';
			}
			$display_file = $a_start.'<img src="/'.DEMO.'~code/images/'.substr($ext,1).'.png" id="'.$file_name.'">'.$a_end;
		}
	}
	if( !isset($display_file) || empty($display_file) ){
		$display_file = '<p class="error">Cannot display '.$file_name.'</p>';
	}
	return $display_file;
}


// display gallery
function display_gallery_admin($path){

	global $ui;

	$gallery = '';
	// if we're in the process of creating the gallery (via modals/gallery.php), the gallery file does not exist yet, so just return empty string
	if( !file_exists(ROOT.CONTENT.$path) ){
		return $gallery;
	}

	$i = 0;
	$contents = file_get_contents(ROOT.CONTENT.$path);
	$img_array = explode(PHP_EOL, trim($contents));

	if( !empty($img_array[0]) ){
		$img_count = count($img_array);
		$gallery .= '<h3 class="imgCount">'.$ui['gallery'].': '.$img_count.' images</h3>'.PHP_EOL;
		$gallery .= '<div class="adminGal">';
		$gallery .= '<form name="manageGall" method="POST" action="">';
		foreach($img_array as $img){
			$im = str_replace( array('/_XL/', '/_M/', '/_L/'), '/_S/', $img);
			$i++;
			$gallery .= '<div class="galImgRow" data-gallery="'.$path.'" data-file="'.$img.'"><span class="tip" data-tip="'.$ui['changePosTitle'].'"><input type="text" class="position" name="position" data-oldPosition="'.$i.'" value="'.$i.'"></span>
			<a href="/'.CONTENT.$img.'" class="aImg" data-bgimg="/'.CONTENT.$im.'" target="_blank" title="'.$ui['viewLarge'].'" style="background-image:url(/'.CONTENT.$im.');">&nbsp;</a>';
			if( file_extension( basename($img) ) == '.jpg' ){
				$gallery .= '<span>
				<span class="tip" data-tip="'.$ui['rotDesc'].'">
				<a class="button discret rotate" data-rotate="90">'.$ui['rotate'].'</a>
				</span>
				<span class="tip" data-tip="'.$ui['cancel'].'">
				<a href="javascript:;" class="button left cancel"></a>
				</span>
				<span class="tip" data-tip="'.$ui['save'].'">
				<a href="javascript:;" class="button submit save left"><span class="checkmark white">&nbsp;</span></a>
				</span>
				</span>';
			}
			
			$gallery .= '<a href="javascript:;" class="button remove right" title="'.$ui['delete'].'">'.$ui['delete'].'</a></div>';
		}
		$rand = rand(1,999);
		$gallery .= '</form>
		</div>';
		$gallery .= '<a href="/'.DEMO.'~code/admin/manage_contents.php?reload='.$rand.'#'.preg_replace('/[^A-Za-z0-9]/', '', basename($path)).'" class="button submit save right" style="margin:10px; margin-bottom:-7px;">'.$ui['save'].'</a>
		<div class="clearBoth"></div>';
	}else{
		$gallery .= '<p class="note">'.$ui['emptyGal'].'</p>';
	}
	return $gallery;
}

/* display all uploaded files */
function display_user_uploads( $files ){

	global $ui;

	$display = '';

	if( empty($files) ){
		$display .= '<p class="note">'.$ui['emptySection'].'</p>';
	}else{
		sort($files);
		
		foreach($files as $f){
			$large = '/'.CONTENT.UPLOADS.'_XL/'.$f;
			$ext = file_extension($f);
			$display .= '<div class="fileContainer">';
			if( preg_match($_POST['types']['resizable_types'], $ext ) ){
				$small = '/'.CONTENT.UPLOADS.'_S/'.$f;
				$display .= '<a class="pad" style="background-image:url('.$small.');" href="'.$large.'" target="_blank" title="'.$ui['select'].'"><div class="selection">
				<span class="checkmark green">&nbsp;</span>
				</div>';
				$display .= '</a>';
			}else{
				$display_file = display_file_admin($f, TRUE); // (true = ommit enclosing a tag)
				if( !preg_match( $_POST['types']['resizable_types'], $ext ) && !preg_match( $_POST['types']['text_types'], $ext ) && $ext !== '.gal' ){
					$display_file .= '<span class="fname">'.filename($f, 'decode').'</span>';
				}
				$display .= '<a class="pad" href="'.$large.'" target="_blank" title="'.$ui['select'].'">
				<div style="position:absolute; z-index:10; top:0; left:0; right:0; bottom:0;">';
				$display .= $display_file;
				$display .= '</div><div class="selection">
				<span class="checkmark green">&nbsp;</span>
				</div>
				</a>';
			}
			$display .= '</div>';
		}
	}
	return $display;
}

/* determine if a file is used */
function is_file_used($file_name){

	// prevent from deleting the home page background image!
	if(file_name_no_ext($file_name) == BG){
		return true;
	}
	
	$menu = file_get_contents(MENU_FILE);
	
	// match file_name and menu.txt
	if( strstr($menu, "\t".$file_name) ){
		return true;
	}else{
		// match all .gal files in menu
		preg_match_all('/\t.*?\.gal\n/', $menu, $matches);
		// loop through the matches and get each gallery content, to look for a match with file_name
		$match = $matches[0];
		if( !empty($match) ){
			foreach($match as $k => $v){
				$contents = file_get_contents( ROOT.CONTENT.UPLOADS.'_XL/'.trim($v) );
				if( strstr($contents, UPLOADS.'_XL/'.$file_name) ){
					return true;
				}
			}
		}

		// match all .html files in menu
		preg_match_all('/\t.*?\.(s?html?|txt)\n/', $menu, $matches);
		// loop through the matches and get each file content, to look for a match with file_name
		$match = $matches[0];
		if( !empty($match) ){
			foreach($match as $k => $v){
				$contents = file_get_contents( ROOT.CONTENT.UPLOADS.'_XL/'.trim($v) );
				if( strstr($contents, '/'.$file_name.'">') ){
					return true;
				}
			}
		}
	}
	// no match, the file is not used in the site
	return false;
}


/*********** 3: ACTIVE FUNCTIONS (FUNCTIONS THAT CHANGE THE CONTENT) ***************/

/* update menu content ($action can be 'add', 'delete')
used to add (upload, embed) or delete file from menu.txt
*/
function update_menu_file($action, $path, $file_name){
	
	$error = '';
	$menu = file_get_contents(MENU_FILE);

	// get pieces of path
	$path_pieces = explode('/', $path);

	// first, match the top level section in menu file (to avoid matching sub-sections of the same name in multiple top sections)
	if( preg_match('/(?<!\\t)'.preg_quote( filename($path_pieces[0], 'decode') ).',.*?(?=\n\S|\Z)/s', $menu, $top_match) ){
		//$update_menu .= '<pre>['.$top_match[0].']</pre>';
		
		$tabs = '';
		// for each match from path against menu title line, set $match and add a tab 
		foreach($path_pieces as $piece){
			if( !empty($piece) ){ // avoid empty lines/matches...
				if( strstr($top_match[0], filename($piece, 'decode').',') ){
					$match = filename($piece, 'decode');
					$tabs .= "\t";
				}
			}
		}
		// make sure to match the correct item in case a top and sub item have the same name!
		if($tabs == "\t\t"){ // this is for a file
			$match = "\t".$match; // this is for a sub-section
		}

		// add: for upload_file, embed_file, save_text_editor
		if($action == 'add'){
			// add new file name to top_match
			$new_insert = preg_replace('/(?<!\\t)('.preg_quote($match, '/').',.*)/', "$1"."\n".$tabs.$file_name, $top_match[0]);
		
		// delete: for delete_file
		}elseif($action == 'delete'){
			preg_match('/'.preg_quote($match, '/').',.*?(?=\n\S|\Z)/s', $top_match[0], $sub_match);
			$replace = str_replace("\n".$tabs.$file_name, '', $sub_match[0]);
			$new_insert = str_replace($sub_match[0], $replace, $top_match[0]);
		}



		//$update_menu .= '<pre>['.$new_insert.']</pre>';
		$old_content = file_get_contents(MENU_FILE);
		// now we can replace top_match with new_insert in menu
		$new_content = str_replace($top_match[0], $new_insert, $menu);

		if($old_content == $new_content){
			$error .= '<p class="error">Error: No match! no change...</p>';
		}
		
		if($fp = fopen(MENU_FILE, 'w') ){
			fwrite($fp, $new_content);
			fclose($fp);
		}else{
			$error .= '<p class="error">Could not open menu file.</p>';
		}
	}else{
		$error .= '<p class="error">Could not match top path to menu file. path='.$path.'</p>';
	}

	if(empty($error)){
		return 'success';
	}else{
		return $error;
	}
}

/* update gallery (and create it if it does not exist) */
function update_gallery_file($path, $action, $file, $position=''){
	$result = '';
	$update_menu = false;

	$gallery_filename = basename($path);
	$parents_dir = preg_replace('/\/?'.preg_quote($gallery_filename).'^/', '', $path);

	$gallery_full_path = ROOT.CONTENT.UPLOADS.'_XL/'.$gallery_filename;
	
	if( !file_exists($gallery_full_path) ){ // file must be created
		$gal_contents = '';
		$update_menu = true;
	}else{
		$gal_contents = file_get_contents($gallery_full_path);
	}

	// img aded to gallery should be '~uploads/img.jpg', not '/content/~uploads...'
	if( strstr($file, CONTENT) ){
		$file = str_replace(CONTENT, '', $file);
	}
	if( substr($file, 0,1) == '/' ){
		$file = substr($file, 1);
	}
	
	if($action == 'add'){
		$new_gal_contents = $file.PHP_EOL.$gal_contents;
	
	}elseif($action == 'remove'){
		$new_gal_contents = preg_replace('/'.preg_quote($file, '/').'\n?/', '', $gal_contents);
	
	}elseif($action == 'position' && $position !== ''){
		$img_array = explode(PHP_EOL, trim($gal_contents) );
		$count = count($img_array);
		// remove $file from array
		if(($key = array_search($file, $img_array)) !== false) {
			unset($img_array[$key]);
		}
		if($position > $count+1){
			$position = $count+1;
		}elseif($position < 1){
			$position = 1;
		}
		// insert $file in array at given position
		array_splice($img_array, $position-1, 0, $file);
		$new_gal_contents = implode(PHP_EOL, $img_array);
	}
	
	// create gallery file if it does not exist, write new content in it
	if( $fp = fopen($gallery_full_path, 'w') ){
		if( !empty($new_gal_contents) ){
			fwrite($fp, $new_gal_contents);
		}
		fclose($fp);
		// update menu file
		if($update_menu){
			$update = update_menu_file('add', $parents_dir, $gallery_filename);
			if($update !== 'success'){
				$result .= '<p class="error">Menu file could not be updated</p>';
			}
		}
		//$result .= '<p>Path of gal to display: '.$path.'</p>';
		$result .= display_gallery_admin(UPLOADS.'_XL/'.$gallery_filename);
	}else{
		//return false;
		$result .= '<p class="error">Could not create '.$gallery_full_path.'</p>';
	}
	return $result;
}

/* change section or sub-section name
updates menu via preg_match(preg_replace($parent.$oldname, $parent.$newname)
*/
function update_section_name($oldName, $newName, $parents, $adminPage){

	global $ui;
	
	$result = $menu_array = $output = $error = '';
	
	// generate array of parents from string
	$parents = string_to_array($parents, 'qQq');
	
	//echo print_r($parents);

	if(!empty($oldName) && !empty($newName)){
		
		$parents_dir = '';
		$menu = file_get_contents(MENU_FILE);
		
		// validate new name
		if( $newValidName = validate_section_name($newName) ){

			// get ready to rename section directory
			$old_dir = dir_from_section_name($oldName);
			$new_dir = dir_from_section_name($newValidName);
			
			if( !empty($parents) ){ // rename a sub-section! Risk of duplicates
				
				// for each parents, iterate $target_key array, and path to actual dir
				foreach($parents as $p){
					$dir = dir_from_section_name($p);
					$parents_dir .= $dir.'/'; 
				}
				
				$old_dir = $parents_dir.$old_dir;
				$new_dir = $parents_dir.$new_dir;

				// !!!!!! GETTING AWAY WITH THIS ONLY BECAUSE THERE CAN BE ONLY ONE PARENT
				// = ONLY ONE SUB-LEVEL OF SECTIONS
				$parent = $parents[0];
				

				$tabs = "\t"; // sub-section has one tab
				// match parent section and all its subsections (down to end of file or next top level section)
				if( preg_match('/(?<!\\t)'.preg_quote($parent).'.*?(?=\n\S|\Z)/s', $menu, $top_match) ){
					$new_insert = str_replace("\n".$tabs.$oldName, "\n".$tabs.$newValidName, $top_match[0]);
					
					$old = $top_match[0];
					$new = $new_insert;
				}else{
					$error .= '<p class="error">Could not match parent section to menu file.</p>';
				}
				
			}elseif( strstr($menu, $oldName."\n") ){ // rename a top level section, no risk of duplicates
				$old = $oldName."\n";
				$new = $newValidName."\n";
				$old_dir = dir_from_section_name($oldName);
				$new_dir = dir_from_section_name($newValidName);
			
			}else{
				$error .= '<p class="error">ERROR: No match!</p>';
			}
			
			if(isset($old) && isset($new) && $old !== $new){
				$new_contents = str_replace($old, $new, $menu);
			
				if($fp = fopen(MENU_FILE, "w")){
					fwrite($fp, $new_contents);
					fclose($fp);
					if( !rename(ROOT.CONTENT.$old_dir, ROOT.CONTENT.$new_dir) ){
						$error .= '<p class="error">ERROR: Could not rename '.ROOT.CONTENT.$old_dir.' to '.ROOT.CONTENT.$new_dir.'</p>';
					}
				}else{
					$error .= '<p class="error">ERROR: Could not open '.MENU_FILE.'</p>';
				}
			}else{
				$error .= '<p class="note">No change: '.$old.' = '.$new.'</p>';
			}
		}else{ // invalid name
			$error .= '<p class="error">'.str_replace('[%rep%]', $newName, $ui['systemName']).'</p>';
		}
	}else{
		$error .= '<p class="error">ERROR: Empty name!</p>';
	}

	// generate html output for manage structure admin page
	if($adminPage == 'manage_structure'){
		$output = site_structure();
	// generate html output for manage content admin page
	}elseif($adminPage == 'manage_contents'){
		$output = display_content_admin();
	}

	if(!empty($error)){
		$result .= $error.$output;
	}else{
		$result .= $output;
	}

	echo $result;
}

/* create new section or sub-section (if a sub-section, $parent will NOT be empty. If a main section, $parent WILL be empty)
updates menu via simple preg_replace($parent.$new_section)
*/
function create_section($parents, $createSection){

	global $ui;

	$result = $error = $parent_dir = '';

	// validate new name
	if($new_section = validate_section_name($createSection)){

		// add underscore to hide the new section, if it is not already there
		if( substr($new_section, 0, 1) !== '_' ){
			$new_section = '_'.$new_section;
		}

		// generate array of parents from string
		$parents = string_to_array($parents, 'qQq');

		$contents = file_get_contents(MENU_FILE);
		
		$new_dir = dir_from_section_name($new_section);
		
		if(!empty($parents) && $parents != 'undefined'){ // if $parent is not empty, we're creating a sub-section in this parent section

			// !!!!!! GETTING AWAY WITH THIS ONLY BECAUSE THERE CAN BE ONLY ONE PARENT
			// = ONLY ONE SUB-LEVEL OF SECTIONS
			$parent = $parents[0];

			preg_match('/'.preg_quote($parent).'\n/', $contents, $parent_match);

			//$error .= '<h2>Parent: '.$parent_match[0].'</h2>';

			$new_contents = str_replace($parent_match[0], $parent_match[0]."\t".$new_section."\n", $contents);
			$parent_dir = dir_from_section_name($parent).'/';

		}else{ // we're creating a main section
			$new_contents = $new_section."\n".$contents;
		}
		
		// make sure the section name does not already exist in this location
		if( is_dir(ROOT.CONTENT.$parent_dir.$new_dir) || is_dir( ROOT.CONTENT.$parent_dir.substr($new_dir,1) ) ){
			$result = '<p class="error">'.str_replace('[%rep%]', filename(substr($new_dir,1), 'decode'), $ui['sectionNameExists']).'</p>';
			return $result;
		}
	}else{ // invalid name
		$error .= '<p class="error">'.str_replace('[%rep%]', $new_section, $ui['systemName']).'</p>';
		return $error;
	}
	
	if($new_contents == $contents){
		$error .= '<p class="error">ERROR:<br><pre>'.$new_contents.PHP_EOL.' == '.PHP_EOL.$contents.'</pre></p>';
	}
	
	if($fp = fopen(MENU_FILE, "w")){
		fwrite($fp, $new_contents);
		fclose($fp);
		// create directory for section
		if( !mkdir(ROOT.CONTENT.$parent_dir.$new_dir) ){
			$error .= '<p class="error">ERROR: Could not create '.ROOT.CONTENT.$new_dir.'</p>';
		// copy template index.php to directory
		}else{
			if( !copy(ROOT.DEMO.'~code/templates/index.php', ROOT.CONTENT.$parent_dir.$new_dir.'/index.php') ){
				$error .= '<p class="error">ERROR: Could not copy '.ROOT.DEMO.'~code/templates/index.php to '.ROOT.CONTENT.$new_dir.'/index.php</p>';
			}
		}
	}else{
		$error .= '<p class="error">ERROR: Could not open '.MENU_FILE.'</p>';
	}
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">'.str_replace('[%rep%]', filename(substr($new_dir,1), 'decode'), $ui['sectionCreated']).'</p>';
	}
	return $result;
}

/* delete section (if a sub-section, $parents will NOT be empty. If a main section, $parents WILL be empty)
updates menu via menu_file_to_array > array_to_menu_file
*/
function delete_section($parents, $deleteSection){

	global $ui;

	$result = $error = '';

	// generate array of parents from string
	$parents = string_to_array($parents, 'qQq');

	// generate 3D array from menu file
	$menu_array = menu_file_to_array();
	
	// if a sub-section: remove sub array key
	if( !empty($parents) ){

		// !!!!!! GETTING AWAY WITH THIS ONLY BECAUSE THERE CAN BE ONLY ONE PARENT
		// = ONLY ONE SUB-LEVEL OF SECTIONS
		$parent = $parents[0];

		// unset item key in $inner_array
		unset($menu_array[$parent][$deleteSection]);
		$parent_dir = dir_from_section_name($parent);
		$dir_to_delete = $parent_dir.'/'.dir_from_section_name($deleteSection);
	
	// if a single section, remove array key
	}else{
		// unset this key from menu array
		unset($menu_array[$deleteSection]);
		$dir_to_delete = dir_from_section_name($deleteSection);
	}
	
	// generate new menu file from updated menu array
	$menu_file = array_to_menu_file($menu_array);
	// update menu file (write $menu_file into menu.txt)
	if($fp = fopen(MENU_FILE, "w")){
		fwrite($fp, $menu_file);
		fclose($fp);
		
		// delete directory
		if( !rmdirr(ROOT.CONTENT.$dir_to_delete) ){
			$error .= '<p class="error">ERROR: could not delete '.$dir_to_delete.': Directory does not exist...</p>';
		}
		
	}else{
		$error .= '<p class="error">ERROR: could not open '.MENU_FILE.'</p>';
	}
	
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">'.str_replace('[%rep%]', $deleteSection, $ui['sectionDeleted']).'</p>';
	}
	
	return $result;
}

/* change section or sub-section position (update menu.txt)
updates menu via menu_file_to_array > array_to_menu_file
*/
function update_position($item, $oldPosition, $newPosition, $parents, $adminPage){

	global $ui;

	$result = $output = $error = '';

	// generate array of parents from string
	$parents = string_to_array($parents, 'qQq');
	
	if( !empty($item) && !empty($oldPosition) && !empty($newPosition) && is_numeric($newPosition) ){
		
		// generate 3D array from menu file
		$menu_array = menu_file_to_array();
		$newPos = $newPosition-1; // arrays start with 0, not 1
		$oldPos = $oldPosition-1;
		
		// update $menu_array:
		// if a single section, remove array key and re-insert it to proper position
		if( empty($parents) ){
			
			// create array key item => values 
			$insert_array = array($item => $menu_array[$item]);
			// unset this key from menu array
			unset($menu_array[$item]);
			// insert it at new position
			$menu_array = insert_at($menu_array, $insert_array, $newPos);
		
		// if a sub-section: remove sub array key and re-insert it to proper position
		}else{
			
			// only one parents
			if( count($parents) == 1 ){
				// duplicate parents array key into new array $inner_array
				$inner_array = $menu_array[$parents[0]];
				// create array
				$insert_array[$item] = $menu_array[$parents[0]][$item];
				// unset item key in $inner_array
				unset($inner_array[$item]);
				// insert it in new position into $inner_array
				$inner_array = insert_at($inner_array, $insert_array, $newPos);
				
				// update parents array key 
				$menu_array[$parents[0]] = $inner_array;
			
			// 2 parents	
			}elseif( count($parents) == 2 ){
				// duplicate parents array key into new array $inner_array
				$inner_array = $menu_array[$parents[0]][$parents[1]];
				// create array
				$insert_array[$item] = $menu_array[$parents[0]][$parents[1]][$item];
				// unset item key in $inner_array
				unset($inner_array[$item]);
				// insert it in new position into $inner_array
				$inner_array = insert_at($inner_array, $insert_array, $newPos);
				
				// update parents array key 
				$menu_array[$parents[0]][$parents[1]] = $inner_array;
			}
		}
		
		// generate new content to write into menu file, from updated $menu_array
		$menu_new_content = array_to_menu_file($menu_array);
		
		// update menu file (write new content into menu.txt)
		if($fp = fopen(MENU_FILE, "w")){
			fwrite($fp, $menu_new_content);
			fclose($fp);
		}else{
			$error .= '<p class="error">ERROR: could not open '.MENU_FILE.'</p>';
		}
	}else{
		$result .= '<p class="note warning">New position must be a valid number.<a class="closeMessage">&times;</a></p>';
	}
	
	if( !empty($error) ){
		$result .= $error;
	}else{
		$currentItem = $item;
		// generate html output for manage structure admin page
		if($adminPage == 'manage_structure'){
			$output .= site_structure();
		
		// generate html output for manage content admin page
		}elseif($adminPage == 'manage_contents'){
			//$output .= '<h1>OK</h1>';
			$output .= display_content_admin();
		}
		
		$result .= $output;
	}
	
	echo $result;
}

/* delete file, all its size versions, and its corresponding txt description files (en and de versions) 
uses update_menu_file
*/
function delete_file($delete_file, $parentsPath){

	global $ui;

	$message = $error = '';
	$file_name = basename($delete_file);

	$ext = file_extension($file_name);

	// 1. UPDATE MENU
	$update_menu = update_menu_file('delete', $parentsPath, $file_name);
	if($update_menu != 'success'){
		$message .= $update_menu;
	}

	// 2. if file is used somewhere else, don't delete it (return message)
	if( is_file_used($file_name) ){
		return '<p class="note">'.$ui['itemRemoved'].'</p>';
	}
	
	// else, file is not used, deleted it.
	
	// 3. delete file (and related files: 'en' and 'de' descriptions, various sizes)
	if( file_exists(ROOT.CONTENT.$delete_file) ){
		$txt_file = preg_replace('/'.preg_quote($ext).'$/', '.txt', $file_name );
		if( preg_match($_POST['types']['resizable_types'], $ext) ){ // resizable (images) files
			// get description files for deletion
			$en_txt = preg_replace('/\/_S\/.*/', '/en/'.$txt_file, $delete_file );
			$de_txt = preg_replace('/\/_S\/.*/', '/de/'.$txt_file, $delete_file );
			// get all sizes for deletion
			$xl_file = str_replace('/_S/', '/_XL/', $delete_file);
			$m_file = str_replace('/_S/', '/_M/', $delete_file);
			$l_file = str_replace('/_S/', '/_L/', $delete_file);
			
			if( unlink(ROOT.CONTENT.$delete_file) ){
				$message .= '<p class="success">'.$ui['itemDeleted'].'</p>';
				// delete all sizes
				unlink(ROOT.CONTENT.$xl_file);
				unlink(ROOT.CONTENT.$m_file);
				unlink(ROOT.CONTENT.$l_file);
			}else{
				$message .= '<p class="error">ERROR: The file could not be deleted.</p>';
			}
			
		}else{ // not an image... no sizes.
			// get description files for deletion
			$en_txt = preg_replace('/\/_XL\/.*/', '/en/'.$txt_file, $delete_file );
			$de_txt = preg_replace('/\/_XL\/.*/', '/de/'.$txt_file, $delete_file );
			
			if( unlink(ROOT.CONTENT.$delete_file) ){
				$message .= '<p class="success">'.$ui['itemDeleted'].'</p>';
				
				// delete alternate labguage version for text files, if it exists
				if( preg_match($_POST['types']['text_types'], $ext) ){
					$de_file = str_replace($ext, '-de'.$ext, $delete_file);
					if( file_exists(ROOT.CONTENT.$de_file) ){
						unlink(ROOT.CONTENT.$de_file);
					}
				}
			}else{
				$message .= '<p class="error">ERROR: The file could not be deleted.</p>';
			}
		}
		
		// 3. delete description files
		if(!unlink(ROOT.CONTENT.$en_txt) || !unlink(ROOT.CONTENT.$de_txt)){
			$message .= '<p class="note warning">The text description corresponding to the file could not be deleted... </p>';
		}
	}else{
		$message .= '<p class="error">ERROR: File does not exist: '.$delete_file.'</p>';
	}

	return $message;
}

/* save text file created with edit_text.php
uses update_menu_file
*/
function save_text_editor($file, $content){
	$error = $result = '';

	/*
	 $file is either 
	 - a section directory, in which case the file needs to be created and we need to update the menu
	 or 
	 - a path to file name (~uploads/file_name.html) if file is to be edited, in which case we don't need to update the menu
	 */
	
	// check if creating a new file with no name, or with a name/ old one.
	$ext = file_extension( basename($file) );
	if(!$ext){ // no file extension, we'll create a new html file

		$add_to_menu = true;
		// add the _XL directory to file path
		$create_path = UPLOADS.'_XL/';
		$path = $file;
		
		// extract clean version of entered text
		if( preg_match('/<h\d.*<\/h\d>/is', $content, $matches) ){ // match header tag if there's one
			$clean = preg_replace( '/(\s|<br>)+/', ' ', $matches[0]);	
		}else{	// or just extract text content
			$clean = preg_replace( '/(\s|<br>)+/', ' ', $content);
		}
		$clean = substr( strip_tags( trim($clean) ), 0, 22);
		
		if( !empty($clean) ){ // we have a clean name
			$rand = rand_string(5);
			$new_file_name = filename($clean, 'encode').'-'.$rand.'.html';
		}elseif( !empty($content) ){ // nothing came out of cleaning, create rand name
			$rand = rand_string();
			$new_file_name = $rand.'.html';
		}
		$new_file = $create_path.$new_file_name;
		
	}else{ // file already exists, we're just editing it

		$add_to_menu = false;
		$new_file = $file;
		$new_file_name = basename($new_file);
		$path = preg_replace('/'.$new_file_name.'$/', '', $new_file);
	}
	
	// make sure we're not over-writting a duplicate file name
	if($add_to_menu && file_exists(ROOT.CONTENT.$new_file)){
		$error .= 'File already exists! => '.$file;
	
	}else{
		// write new content into new file (create it if it does not exist)
		if($fp = fopen(ROOT.CONTENT.$new_file, 'w')){
			fwrite($fp, $content);
			fclose($fp);

			// UPDATE MENU
			if($add_to_menu){
				$update_menu = update_menu_file('add', $path, $new_file_name);
				if($update_menu != 'success'){
					$error .= $update_menu;
				}
			}
			
		}else{
			$error .= 'Could not open '.$file;
		}
	}
	
	if(!empty($error)){
		$result .= '0|'.$error;
	}else{
		$result .= '1|'.$new_file;
	}
	return $result;
}

/* embed media
uses update_menu_file
*/
function embed_media($path, $embed_media){

	global $ui;

	$error = $result = '';

	// check if we're editing a pre-existing txt file, or creating a new one in this section
	$ext = file_extension(basename($path));
	if(!$ext){ // no file extension, we'll create a new .emb file
		// add the _XL directory to file path
		$create_path = UPLOADS.'_XL/';
		$path .= '/_XL/';
		$rand = rand_string();
		$new_file_name = $rand.'.emb';
		$new_file = $create_path.$new_file_name;
		
	}else{
		$new_file = $path;
		$new_file_name = basename($new_file);
		$path = preg_replace('/'.$new_file_name.'$/', '', $new_file);
	}

	
	// write new content into new file (create it if it does not exist)
	if($fp = fopen(ROOT.CONTENT.$new_file, 'w')){
		fwrite($fp, $embed_media);
		fclose($fp);
		
		// UPDATE MENU (if new file: $ext==false)
		if(!$ext){ // no file extension, we'll create a new .emb file
			
			$update_menu = update_menu_file('add', $path, $new_file_name);
			if($update_menu != 'success'){
				$error .= $update_menu;
			}
		}
		
	}else{
		$error .= '<p class="error">Could not open '.$new_file_name.'</p>';
	}
	
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">'.str_replace('[%rep%]', $new_file_name, $ui['fileCreated']).'</p>';
	}
	return $result;
}

/* save text description - no menu update necessary */
function save_text_description($file, $en_txt, $de_txt){
	
	$error = $result = '';
	
	// sanitize user input
	$en_txt = sanitize_text($en_txt, '<b><strong><br><u><i><a>');
	$de_txt = sanitize_text($de_txt, '<b><strong><br><u><i><a>');
	$en_txt = my_nl2br($en_txt);
	$de_txt = my_nl2br($de_txt);
	
	$file_name = basename($file);
	$ext = file_extension($file_name);
	$text_file_name = preg_replace('/'.preg_quote($ext).'/', '.txt', $file_name);
	
	// need both S and XL for non-images files saved in XL dir
	$txt_dir = str_replace(array('/_S','/_XL'), '', dirname($file));
	$en_file = $txt_dir.'/en/'.$text_file_name;
	$de_file = $txt_dir.'/de/'.$text_file_name;
	
	if($fp = fopen(ROOT.CONTENT.$en_file, 'w')){
		fwrite($fp, $en_txt);
		fclose($fp);
	}else{
		$error .= '<p class="error">Could not open '.$en_file.'</p>';
	}
	if($fp = fopen(ROOT.CONTENT.$de_file, 'w')){
		fwrite($fp, $de_txt);
		fclose($fp);
	}else{
		$error .= '<p class="error">Could not open '.$de_file.'</p>';
	}
	
	if(!empty($error)){
		$result .= $error;
	}else{
		$result .= '<p class="success">Text saved for file: '.filename(basename($file), 'decode').'</p>';
	}
	return $result;
}


/******************************* UPLOAD / RESIZE FILE *******************************************/

/* straight-up upload file function, used in later function. 
Requires a FORM-submitted file input named "file"
*/
function up_file($upload_dest){
	if( move_uploaded_file($_FILES['file']['tmp_name'], $upload_dest) ) {
		// if file is a jpg, fix orientation if possible
		$ext = file_extension($upload_dest);
		if($ext == '.jpg'){
			if( $orientation = get_image_orientation($upload_dest) ){
				$result = fix_image_orientation($upload_dest, $orientation);
				// $result could be empty (success) or string 'error message'. 
				// This is NOT returned by this function, which just returns true or false.
			}
		}
		return true;
	}else{
		return false;
	}
}


/* determine if image can be rotated to correct orientation (only for jpg)
*/
function get_image_orientation($path_to_jpg){
	ini_set('display_errors', 'Off');
	$exif = @exif_read_data($path_to_jpg);
	ini_set('display_errors', 'On');
	if ( !empty($exif['IFD0']['Orientation']) ) {
		$orientation = $exif['IFD0']['Orientation'];
	}elseif( !empty($exif['Orientation']) ){
		$orientation = $exif['Orientation'];
	}else{
		$orientation = false;
	}
	return $orientation;
}


/* fix image orientation (only for jpg)
*/
function fix_image_orientation($path_to_jpg, $image_orientation){

	$result = '';
	list($w, $h) = getimagesize($path_to_jpg);
	$new = imagecreatetruecolor($w, $h);

	if(!$new){
		$result .= '<p class="error">could not imagecreatetruecolor</p>';
	}else{

		ini_set('memory_limit','512M');
		$from = imagecreatefromjpeg($path_to_jpg);
		
		if(!$from){
			$result .= '<p class="error">could not imagecreatefromjpeg: '.$path_to_jpg.'</p>';
		}else{
			if( !imagecopyresampled($new, $from, 0, 0, 0, 0, $w, $h, $w, $h) ){
				$result .= '<p class="error">could not imagecopyresampled: '.$path_to_jpg.'</p>';
			}else{
				
				switch($image_orientation) {
					case 3:
						$new = imagerotate($new, 180, 0); // 90 js deg = 3
						break;
					case 6:
						$new = imagerotate($new, -90, 0); // 180 js deg = 6
						break;
					case 8:
						$new = imagerotate($new, 90, 0); // 270 js deg = 8
						break;
				}
				
				imagejpeg($new, $path_to_jpg, 90);
			}
		}
	}
	imagedestroy($new);

	if( empty($result) ){
		return true;
	}else{
		return $result;
	}

}


/* upload file (under manage content) - requires updating menu.txt
uses update_menu_file
*/
function upload_file($path, $replace=''){

	global $ui;
	
	// initialize upload results
	$upload_message = $resize_result = $menu_update_result = '';
	$types = $_POST['types'];

	$file_name = $_FILES['file']['name']; // 'file' must be the name of the file upload input in the sending html FORM!

	// get file extension
	$ext = file_extension($file_name);
	// re-format extension to standard, to avoid meaningless mismatch
	$ext = strtolower($ext);
	if($ext == '.jpeg' || $ext == '.jpe'){
		$ext = '.jpg';
	}
	if($ext == '.oga'){
		$ext = '.ogg';
	}
	// Mac .txt files can use the "plain" file type (for plain text)!...
	if($ext == '.plain'){
		$ext = '.txt';
	}
	// msword file type (can be generated by open office)... and docx can be .doc, to use the doc.png icon...
	if($ext == '.msword' || $ext == '.docx'){
		$ext = '.doc';
	}
	// wav files can have 'x-wav' type
	if($ext == '.x-wav'){
		$ext = '.wav';
	}
	
	// check against extension if file type is supported
	if ( !preg_match($types['supported_types'], $ext) ){
		$upload_message .= '<p class="error">'.str_replace('[%rep%]', $ext, $ui['fileTypeNotSupported']).'</p>';
	
	// UPLOAD FILE
	}else{
		
		// format/clean file name (without the extension)
		$file_name_no_ext = file_name_no_ext($file_name);
		$file_name_no_ext = filename($file_name_no_ext, 'encode');
		
		// is it an image? (if yes, it will be resized and uploaded in various sizes/directories)
		if( preg_match($types['resizable_types'], $ext) ){
			
			$resize = TRUE;
			ini_set('memory_limit','512M');

		}else{
			$resize = FALSE;
		}
		
		
		// if we're uploading a file to replace another one
		if( !empty($replace) ){

			// set the upload file name to replace name (but don't change extension, we'll compare them next)
			$new_file_name = file_name_no_ext( basename($replace) ).$ext;
			$replace_ext = file_extension($replace);
			
			// if the original file and its replacement don't have the same extension, delete the original
			if( $replace_ext !== $ext){
				if( !unlink( ROOT.CONTENT.UPLOADS.'_XL/'.basename($replace) ) ){
					$upload_message .= '<p class="note">Could not delete '.$replace.'</p>';
				}
				// delete other sizes if resizable_type
				if( $resize ){
					$small = ROOT.CONTENT.UPLOADS.'_S/'.basename($replace);
					$medium = ROOT.CONTENT.UPLOADS.'_M/'.basename($replace);
					$large = ROOT.CONTENT.UPLOADS.'_L/'.basename($replace);
					unlink($small); unlink($medium); unlink($large);
				}
			}
		// if we're uploading to add a new file
		}else{
			// let's make sure the file name is unique
			$rand = rand_string(5);
			$new_file_name = $file_name_no_ext.'-'.$rand.$ext;
		}

		// set upload destination (relative and root based)
		$upload_dest = UPLOADS.'_XL/'.$new_file_name;
		$root_upload_dest = ROOT.CONTENT.$upload_dest;
		
		// upload
		if( up_file($root_upload_dest) ){
			
			// RESIZE, if file is resizable (image)
			if($resize){
				
				// read exif data and fix image orientation now if necessary! (concerns only jpgs)
				if($ext == '.jpg'){

					// get image orientation from exif metadata, or return false
					$image_orientation = get_image_orientation($root_upload_dest);
					
					// could not read image orientation...
					if($image_orientation !== false){

						// fix image orientation (and return true) or return error message
						$fix_orientation = fix_image_orientation($root_upload_dest, $image_orientation);
						if( $fix_orientation !== true ){
							$upload_message .= $fix_orientation;
						}
					}
				}
				
				
				// update width and height now! Or else resizing will be off...


				$resize_result .= resize_all($root_upload_dest);
				if(substr($resize_result, 0, 1) === '0'){
					$upload_message .= '<p class="error">'.$resize_result.'</p>';
				}
			}

			$upload_message .= '<p class="success">'.$ui['fileUploaded'].': '.filename($new_file_name, 'decode').'</p>';
			
			// debug
			//$upload_message .= '<p class="note">Replace: '.$replace.'</p>';

			/* 
			UPLOAD DONE. 
			NOW, UPDATE MENU OR NOT? 
			*/
			// if $path is the generic upload destination, menu does not need updating.
			if( preg_match('/^'.str_replace('/', '\/', UPLOADS).'/', $path) ){
				$update_menu = false;
			
			// if $path is the parent section
			}else{
				// but replace = new file, no need to update
				if( basename($replace) == $new_file_name ){ 
					$update_menu = false;
				// else, menu needs to be updated
				}else{
					$update_menu = true;
				}
			}
			
			// UPDATE MENU.txt
			if($update_menu){

				$update_menu = update_menu_file('add', $path, $new_file_name);
				if($update_menu !== 'success'){
					log_custom_error($update_menu, "fatal");
				}
				
				// if we're replacing a file wich has a different extension (see conditional that precedes), we need to also delete the file from the menu
				if( !empty($replace) ){
					$update_menu = update_menu_file('delete', $path, basename($replace) );
					if($update_menu !== 'success'){
						log_custom_error($update_menu, "fatal");
					}
				}
			}
			
		}else{
			$upload_message .= '<p class="error">'.str_replace('[%rep%]', MAX_UPLOAD_SIZE, $ui['fileTooLarge']).'</p>';
		}
	}

	$upload_results = $upload_message.$menu_update_result;

	return $upload_results;
}


/* resize image to multiple sizes */
function resize_all($upload_dest){
	
	$resize_result = '';

	list($w, $h) = getimagesize($upload_dest);
	
	// resize image to various sizes as specified by $_POST['sizes'] array
	foreach($_POST['sizes'] as $key => $val){
		
		$width = $val['width'];
		$height = $val['height'];
		$resize_dest = str_replace('/_XL', '/_'.$key, $upload_dest);
		
		if($w > $width || $h > $height){
			$resize_result .= resize($upload_dest, $resize_dest, $w, $h, $width, $height);
				
		}else{
			if( !copy($upload_dest, $resize_dest) ){
				$resize_result .= '0|could not copy '.$upload_dest.' to '.$resize_dest.'<br>';
			}
		}
	}
	
	return $resize_result;
}


/* resize image */
function resize($src, $dest, $width_orig, $height_orig, $width, $height){

	$result = '';

	$ext = file_extension($src); //extract extension
	$ext = str_replace('jpeg', 'jpg', strtolower($ext) ); // format it for later macthing
	
	// make sure file is resizable (match against file extension)
	if ( preg_match($_POST['types']['resizable_types'], $ext) ){

		ini_set('memory_limit','512M');
		
		// if image is bigger than the target width or height, calculate new sizes and resize
		if($width_orig > $width || $height_orig > $height){
			
			$scale = min($width/$width_orig, $height/$height_orig);
			$width = round($width_orig*$scale);
			$height = round($height_orig*$scale);
			
			// create canvas for image with new sizes
			$new = imagecreatetruecolor($width, $height);
			if(!$new){
				return '0|could not imagecreatetruecolor<br>';
			}
			
			// we can resize jpg, gif and png files.
			if($ext == '.jpg'){ 
				$from = imagecreatefromjpeg($src);
			}elseif($ext == '.gif'){
				imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
				imagealphablending($new, false);
				imagesavealpha($new, true);
				$from = imagecreatefromgif($src); 
			}elseif($ext == '.png'){
				imagealphablending($new, false);
				imagesavealpha($new, true);
				$from = imagecreatefrompng($src);
			}
			
			if(!$from){
				return '0|could not imagecreatefrom: '.$src.'<br>';
			}
			
			if( !imagecopyresampled($new, $from, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig) ){
				return '0|could not imagecopyresampled<br>';
			}
				
			if($ext == '.jpg'){
				imagejpeg($new, $dest, 90);
			}elseif($ext == '.gif'){ 
				imagegif($new, $dest); 
			}elseif($ext == '.png'){
				imagepng($new, $dest);
			}
			imagedestroy($new);
			
		// no need to resize, the original image is too small
		}else{
			return '1|no need to resize.';
		}
	
	// file is not resizable
	}else{
		return '0|file is not resizable.';
	}
	
	return $result;
}
