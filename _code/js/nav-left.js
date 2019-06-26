
$(function(){

	//var wH = $(window).innerHeight();
	var fH = $('#footer').outerHeight();
	var $content = $('#content');
	/*var $backTitle = $('.backTitle');

	if($backTitle.length){
		var originTop = $backTitle.offset().top;
		// below uses debounce pluggin (js/throttle-debounce.min.js) to call behaviour only once when scroll starts
		$(window).scroll($.debounce(50, true, function(e) {
			animateTitle($backTitle, originTop);
		}));
	}
	*/

	/** #content dimensions */ 
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

// animate backTitle up and down
/*
function animateTitle($backTitle, originTop){
	var st = $('html').scrollTop() || $('body').scrollTop();
	if(st > 0){
		$backTitle.animate({'top': 0}, 100);
	}else{
		$backTitle.animate({'top': originTop}, 400);
	}
}
*/

// set #content dimensions depending on window, nav and footer height
function setContentDimensions(wH, fH, $content){
	if($content.length){ // exception for home page
		var contentPad = $content.outerHeight()-$content.height();
		var margT = parseInt( $content.css('margin-top').replace("px","") );
		//var	paddB = parseInt( $content.css('padding-bottom').replace("px","") );
		var	contentMinHeight = wH-fH-contentPad-margT-24;
		//alert(margT);
		$content.css({'min-height': contentMinHeight+'px'});
	}
}