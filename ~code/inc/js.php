<!-- jQuery -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/jquery-3.3.1.min.js" charset="utf-8"></script>
<!-- common custom js -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/js.js?v=<?php echo $version; ?>" charset="utf-8"></script>
<!-- throttle debounce pluggin -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/throttle-debounce.min.js" charset="utf-8"></script>

<script type="text/javascript">
var lang = '<?php echo LANG; ?>';
var demo = '<?php echo DEMO; ?>';
var content = '<?php echo CONTENT; ?>';
var nav = '<?php if( isset($_GET['nav']) ){echo $_GET['nav'];}else{echo CSS;} ?>';
var show_sub_nav = '<?php echo $show_sub_nav; ?>';
var img_w_limit = <?php echo L_W_LIMIT; ?>;
</script>

<?php
if(CSS == 'nav-top' && ( !isset($_GET['nav']) || $_GET['nav'] !== 'left') ){ 
?>
	<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/nav-top.js?v=<?php echo $version; ?>"></script>
<?php
}elseif(CSS == 'nav-left' || ( isset($_GET['nav']) && $_GET['nav']=='left') ){ 
?>
	<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/nav-left.js?v=<?php echo $version; ?>"></script>
<?php 
} 
?>
