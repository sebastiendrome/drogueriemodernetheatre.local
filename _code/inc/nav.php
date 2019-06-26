<?php

// generate navigation links depending on menu array of items from menu txt file
function make_nav($menu_array){
	$nav = '';
    foreach($menu_array as $key => $val){
		$sub_nav = '';
		// ignore empty items and hidden items (whose name starts with underscore)
		if(!empty($key) && substr(basename($key),0,1) !== '_'){
			$class = $sub_ul_class = '';
			$split = explode(',', $key);
			if(LANG == 'en'){
				$name = $split[0];
			}elseif(LANG == 'de'){
				$name = $split[1];
			}
			$dir = filename($split[0], 'encode');
			$link = '/'.LANG_LINK.$dir.'/';
			$split_path = explode('/', CONTEXT_PATH);	// [0]=>'_content', [1]=>'dir', [2]=>'subdir'
	        if($dir == $split_path[1] ){
	            $class = ' class="selected"';
			}
			// if SHOW_SUB_NAV == yes, get sub-sections
			if(SHOW_SUB_NAV == 'yes' && is_array($val) && !empty($val)){
				foreach($val as $k => $v){
					if( strstr($k, ', ') && substr($k, 0, 1) !== '_' ){
						$sub_class = '';
						$sub_split = explode(',', $k);
						if(LANG == 'en'){
							$sub_name = $sub_split[0];
						}elseif(LANG == 'de'){
							$sub_name = $sub_split[1];
						}
						$sub_dir = filename($sub_split[0], 'encode');
						$sub_link = $link.$sub_dir.'/';
						
						if( count($split_path) > 2 && $class == ' class="selected"' && $sub_dir == $split_path[2] ){
							$sub_class = ' class="selected"';
							$show_ul = $split_path[1];
						}
						$sub_nav .= '<li><a href="'.$sub_link.'"'.$sub_class.'>'.$sub_name.'</a></li>'.PHP_EOL;
					}
				}
			}
			// if SHOW_SUB_NAV == yes, $sub_nav was set above...
			if( !empty($sub_nav) ){
				// show sub nav (ul) item if we're within its top directory
				if(isset($show_ul) && $show_ul == $dir){
					$sub_ul_class = ' class="selected"';
				}
				$sub_nav = '<ul'.$sub_ul_class.'>'.$sub_nav.'</ul>'.PHP_EOL;
			}
	        $nav .= '<li'.$class.'><a href="'.$link.'"'.$class.'>'.$name.'</a>'.$sub_nav.'</li>'.PHP_EOL;
		}
    }
    return $nav;
}

$menu_array = menu_file_to_array(); 
$nav = make_nav($menu_array);

// show bilingual links
if($bilingual == 'yes'){
	if( empty(LANG_LINK) ){
		$en_link = $_SERVER['REQUEST_URI'];
		if(substr($_SERVER['REQUEST_URI'],0,1) == '/'){ // remove first slash from uri, just in case...
			$de_link = '/'.LANG_DIR.substr($_SERVER['REQUEST_URI'],1);
		}else{
			$de_link = '/'.LANG_DIR.$_SERVER['REQUEST_URI'];
		}
		$de_selected = ' lang="'.OTHER_LANG.'"';
		$en_selected = ' class="selected"';
	}else{
		$en_link = str_replace('/'.LANG_LINK, '/', $_SERVER['REQUEST_URI']);
		$de_link = $_SERVER['REQUEST_URI'];
		$de_selected = ' class="selected"';
		$en_selected = ' lang="'.OTHER_LANG.'"';
	}
}else{
	$de_selected = $en_selected = '';
}

// main link to home page
if(empty(LANG_LINK)){
	$home_link = '/';
}else{
	$home_link = '/'.LANG_LINK;
}
?>
<!-- start nav -->
<div id="nav">
    <ul>
        <li><h1><a href="<?php echo $home_link; ?>" title="<?php echo USER; ?>"><?php echo USER; ?></a></h1></li>
		<?php 
		echo $nav;
		?>
		<li class="lastLi l2"><a href="<?php echo $en_link; ?>"<?php echo $en_selected; ?>><?php echo $first_lang; ?></a> | <a href="<?php echo $de_link; ?>"<?php echo $de_selected; ?>><?php echo $second_lang; ?></a></li>
	</ul>
	<a id="mobileMenu" href="javascript:;"><img src="/_code/images/mobile-menu.svg" style="width:23px;" onerror="this.onerror=null; this.src='/_code/images/mob-nav.png'"></a>
</div><!-- end nav -->
