<?php
$demo = preg_replace('/~code\/.*$/', '', str_replace(realpath($_SERVER['DOCUMENT_ROOT']).'/', '', __FILE__));
require($_SERVER['DOCUMENT_ROOT'].'/'.$demo.'~code/inc/first_include.php');

$title = $ui['titleAdmin'].' : '.$ui['help'];
$description = '';
$page = 'admin';

require(ROOT.DEMO.'~code/inc/doctype.php');
?>

<link href="/<?php echo DEMO; ?>~code/css/admincss.css?v=2" rel="stylesheet" type="text/css">

<!-- load responsive design style sheets -->
<link rel="stylesheet" media="(max-width: 720px)" href="/<?php echo DEMO; ?>~code/css/admin-max-720px.css">

<style type="text/css">
#adminContainer{position:relative; padding:0; padding-top:70px;}
#admin a.button{margin-top:15px;}
.leftNav{position:fixed; z-index:5; width:300px; top:55px; left:0;}
.leftNav a{display:block; padding:5px 20px;}
.leftNav a p{color:#000;}
.leftNav a:hover{text-decoration:none; background-color:rgba(0, 0, 0, .05);}
.leftNav a:hover h2{text-decoration:underline;}
a.adminLess:hover{background-color:rgba(0, 0, 0, .1);}
a.adminLess h2:after{content: " \25B8\ ";}
a.adminLess h2{text-decoration:underline;}
a.adminLess{background-color:rgba(0, 0, 0, .1);}
</style>

<div id="working">working...</div>

<div class="adminHeader">
<div style="padding:0 20px;">
	
	<h2>
		<a href="/admin/"><?php echo $title; ?></a>
	
	<!--<a href="?logout" class="button remove discret right"><?php echo $ui['logout']; ?></a>
	<a href="mailto:<?php echo AUTHOR_EMAIL; ?>?subject=Request from <?php echo substr(SITE,0,-1); ?>" title="<?php echo $ui['helpTitle']; ?>" class="button discret help right"><?php echo $ui['help']; ?></a>-->
	<!--<a href="my_uploads.php" title="" class="button discret fichiers right"><?php echo $ui['myUploads']; ?></a>-->
	<a href="/admin/help.php" title="" class="button discret help selected right"><?php echo $ui['help']; ?></a>
	<a href="preferences.php" title="<?php echo $ui['prefTitle']; ?>" class="button discret fav right"><?php echo $ui['preferences']; ?></a>
	<a href="/admin/" title="" class="button discret structure right"><img src="/<?php echo DEMO; ?>~code/images/mobile-menu.svg" style="width:10px; margin-right:5px;"><?php echo $ui['siteStructure']; ?></a>
	</h2>

</div>
</div>

<div class="leftNav">
<a href="">Create section</a>
</div>

<!-- start container -->
<div id="adminContainer">



</div>


<div class="clearBoth"></div>
</div><!-- end container -->




<?php require(ROOT.DEMO.'~code/inc/adminFooter.php'); ?>
