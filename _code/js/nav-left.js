
$(window).on("load", function(){

	var fH = $('#footer').outerHeight();
	var $content = $('#content');

	/* #content dimensions */ 
	setContentDimensions(wH, fH, $content);

	// animate nav height
	$('#nav ul li.selected ul').css('height', 'auto');
	$('#nav ul li:not(.selected)').on('mouseenter', function(){
		var $ul = $(this).children('ul');
		$ul.css('height', 'auto');
		var autoHeight = $ul.height();
		$ul.height(0).stop().animate({height: autoHeight}, 300);
		limitNavHeight($('#nav'), wH); // added in case drop downs cause nav to exceed page height
	}).on('mouseleave', function(){
		var $ul = $(this).children('ul');
		$ul.stop().animate({height: 0}, 300);
	});
});

// set #content dimensions depending on window, nav and footer height
function setContentDimensions(wH, fH, $content){
	if($content.length){ // exception for home page
		var contentPad = $content.outerHeight()-$content.height();
		var margT = $content.css('margin-top').replace("px","");
		var	contentMinHeight = wH-fH-contentPad-margT;
		$content.css({'min-height': contentMinHeight+'px'});
	}
}