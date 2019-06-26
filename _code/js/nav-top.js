
$(window).on("load", function(){

	//var wH = $(window).innerHeight();
	var nH = $('#nav').outerHeight();
	var fH = $('#footer').outerHeight();
	var $backTitle = $('.backTitle');
	var bH = 0;
	var $content = $('#content');

	if($backTitle.length){
		bH = $backTitle.outerHeight();
		var elWidth = $backTitle.outerWidth();
		
		// below uses debounce pluggin (js/throttle-debounce.min.js) to call behaviour only once when scroll starts
		$(window).scroll($.debounce(50, true, function(e) {
			animateTitle($backTitle, elWidth);
		}));
		$(window).resize($.debounce(50, false, function(e) {
			animateTitle($backTitle, elWidth);
		}));

		/** #content dimensions */ 
		setContentDimensions(wH, nH, fH, bH, $content);
	}

});

function animateTitle($backTitle, elWidth){
	var st = $('html').scrollTop() || $('body').scrollTop();
	var originLeft = $('#content>div.divItem').offset().left;
	if(st > 0){
		$backTitle.animate({'left': originLeft-elWidth});
	}else{
		$backTitle.animate({'left': originLeft});
	}
}

// set #content dimensions depending on window, nav and footer height
function setContentDimensions(wH, nH, fH, bH, $content){
	var paddT = parseInt( $content.css('padding-top').replace("px","") );
	var	paddB = parseInt( $content.css('padding-bottom').replace("px","") );
	var	contentMinHeight = wH-fH-nH-paddT-paddB-4;
	var backTitleTop = nH+12;
	var bodyTopPad = nH+bH;
	$content.css({'min-height': contentMinHeight+'px', 'margin': '0 auto'});
	$('body').css('padding-top', bodyTopPad+'px');
	$('.backTitle').css('top', backTitleTop+'px');
	//alert(nH);
}
