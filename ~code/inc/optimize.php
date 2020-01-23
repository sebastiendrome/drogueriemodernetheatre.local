<?php 
// recommended size and quality depend on context (set by recommended_size)...
if( !isset($_GET['recommended_size']) ){
    $recommended_size = '3000';
    $quality = '80 (Very High)';
}else{
    $recommended_size = $_GET['recommended_size']; 
    $quality = '60 (High)';
}
?>
<ol>
	<li>Open your image in Photoshop</li>
	<li>Under menu, select: Image > Image Size...</li>
    <li>Adjust the Width and Height, in pixels, so that neither exceeds <?php echo $recommended_size; ?>px</li>
    <li>Check "Constrain Proportions" and "Resample Image"</li>
    <li>Click OK.</li>
    <li>Under menu, select: File > Save for Web & Devices...</li>
    <li>Select JPEG format, set Quality to <?php echo $quality; ?>, check Optimized</li>
    <li>Click Save.</li>
</ol>