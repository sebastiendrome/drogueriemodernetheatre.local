<?php
// create section modal
require($_SERVER['DOCUMENT_ROOT'].'/_code/inc/first_include.php');

if(isset($_GET['path']) && !empty($_GET['path'])){
    $path = urldecode($_GET['path']);
    if(substr($path, 0, 1) == '/'){
        $path = substr($path, 1);
    }
    if(substr($path, -1) == '/'){
        $path = substr($path, 0, -1);
    }
    $output = display_content_array($path);
}else{
    $error = '<p class="error">No path!...</p>';
    $output = $error;
}

echo $output;