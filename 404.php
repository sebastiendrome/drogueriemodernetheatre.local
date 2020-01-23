<?php
require('~code/inc/first_include.php');

header('HTTP/1.0 404 Not Found');
$title = $description = '404: Page not found';
$page = '404';

require(ROOT.'~code/inc/doctype.php');

require(ROOT.'~code/inc/nav.php');

?>





<!-- start content -->
<div id="content">
<div style="padding:30px; color:#fff; text-shadow:1px 1px 1px #444; text-align:center; font-size:100px;">404:<br>
page not found</div>
</div><!-- end content -->

<div class="clearBoth"></div>

<?php require(ROOT.'~code/inc/footer.php'); ?>

