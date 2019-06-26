<?php
// validate and store new language (form POST sent from newLang.php modal)
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');
require(ROOT.'_code/admin/not_logged_in.php');

require(ROOT.'_code/admin/admin_functions.php');

unset($_POST['sizes'], $_POST['types']);

if(!isset($_POST) || empty($_POST)){
    exit;
}else{
    foreach($_POST as $k => $v){
        $post[$k] = sanitize_text($v);
    }
    unset($_POST);
}

$error = '';

if(!isset($post['lang_num']) || empty($post['lang_num'])){
    $error .= 'missing lang_num<br>';
}
if(!isset($post['new_lang']) || empty($post['new_lang'])){
    $error .= 'missing new_lang<br>';
}
if(!isset($post['seo']) || empty($post['seo'])){
    $error .= 'missing seo<br>';
}
if(!isset($post['more']) || empty($post['more'])){
    $error .= 'missing more<br>';
}
if(!isset($post['back']) || empty($post['back'])){
    $error .= 'missing back<br>';
}


if(!empty($error)){
    echo '<p style="color:red;">Error: '.$error.'</p>';
    exit;
}else{
    $user_custom = ROOT.CONTENT.'user_custom.php';
    $content = file_get_contents($user_custom);
    
    // if new language already exists, update it
    if(array_key_exists($post['new_lang'], $languages)){
        $new_content = preg_replace('/\n\$languages\[\''.$post['new_lang'].'\'] = .*/', "\n".'$languages[\''.$post['new_lang'].'\'] = array(\'seo\'=>\''.$post['new_lang'].'\', \'more\'=>\''.$post['more'].'\', \'back\'=>\''.$post['back'].'\');', $content);

    // add the array entry for the new language
    }else{
        $new_content = str_replace("\n// user choosen first and second languages\n", '$languages[\''.$post['new_lang'].'\'] = array(\'seo\'=>\''.$post['seo'].'\', \'more\'=>\''.$post['more'].'\', \'back\'=>\''.$post['back'].'\');'."\n\n".'// user choosen first and second languages'."\n", $content);
    }
    
    // update value of first or second language variable (commented out, saving it is done in preferences)
    //$new_content = preg_replace('/\$'.$post['lang_num'].'_lang = .*/', '$'.$post['lang_num'].'_lang = \''.$post['new_lang'].'\';', $new_content);
    
    // open and write new content 
    if($fp = fopen($user_custom, 'w')){
        fwrite($fp, $new_content);
        fclose($fp);
    }else{
        echo '<p style="color:red;">Could not open '.$user_custom.'</p>';
        exit;
    }

    // send me the new language data
    mail(AUTHOR_REF, 'new language artist portfolio', 'language = '.$post['new_lang'].', seo = '.$post['seo'].', more = '.$post['more'].' back = '.$post['back']);

    // redirect to admin/preferences
    header("Location: preferences.php?new_lang=".$post['new_lang'].'&lang_num='.$post['lang_num']);
    exit;
}
