<!-- jQuery -->
<script type="text/javascript" src="/_code/js/jquery-3.2.1.min.js" charset="utf-8"></script>
<!-- common custom js -->
<script type="text/javascript" src="/_code/js/js.js?v=<?php echo $version; ?>" charset="utf-8"></script>
<!-- throttle debounce pluggin -->
<script type="text/javascript" src="/_code/js/throttle-debounce.min.js" charset="utf-8"></script>

<?php
if(CSS == 'nav-top'){ 
?>
	<script type="text/javascript" src="/_code/js/nav-top.js"></script>
<?php
// animate sub-nav, only for nav-left
}elseif(SHOW_SUB_NAV == 'yes' && CSS == 'nav-left'){ 
?>
	<script type="text/javascript" src="/_code/js/nav-left.js"></script>
<?php 
} 
?>