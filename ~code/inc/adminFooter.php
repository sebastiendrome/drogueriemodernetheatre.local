
<!-- set js vars from php for file upload max size -->
<script type="text/javascript">
/* vars imported from php, needed within js functions */
var types = new Array;
types['supported_types'] = new RegExp(<?php echo $_POST['types']['supported_types']; ?>);
types['resizable_types'] = new RegExp(<?php echo $_POST['types']['resizable_types']; ?>);
var max_upload_size = '<?php echo MAX_UPLOAD_SIZE; ?>';
var max_upload_bytes = <?php echo MAX_UPLOAD_BYTES; ?>;
//var content = '<?php echo CONTENT; ?>';
//var uploads = '<?php echo UPLOADS; ?>';
</script>
<!-- jQuery -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/jquery-3.3.1.min.js" charset="utf-8"></script>
<!-- common custom js -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/js.js?v=<?php echo $version; ?>" charset="utf-8"></script>
<!-- js for admin -->
<script type="text/javascript" src="/<?php echo DEMO; ?>~code/js/admin_js.js?v=<?php echo $version; ?>" charset="utf-8"></script>


</body>
</html>