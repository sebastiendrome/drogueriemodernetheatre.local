<?php
if( file_exists('../../~code/inc/first_include.php') ){
	require('../../~code/inc/first_include.php');
}elseif('../../../~code/inc/first_include.php'){
	require('../../../~code/inc/first_include.php');
}
require(ROOT.DEMO.'~code/inc/index.php');