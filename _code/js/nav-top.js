
$(window).on("load", function(){

	var $content = $('#content');

	/** #content dimensions */ 
	if($content.length){

		var nH = $('#nav').outerHeight();
		var fH = $('#footer').outerHeight();
		
		var $backTitle = $('.backTitle');

		if($backTitle.length){
			var bH = $backTitle.outerHeight();
			var bW = Math.ceil( $backTitle.outerWidth() );
			
			// below uses debounce pluggin (js/throttle-debounce.min.js) to call behaviour only once when scroll starts
			$(window).scroll($.debounce(50, true, function(e) {
				animateTitle($backTitle, bW);
			}));
			$(window).resize($.debounce(50, false, function(e) {
				animateTitle($backTitle, bW);
			}));
	
		}else{
			var bH = 0; // $backTitle height
		}

		setContentDimensions(wH, nH, fH, bH, $content);
	}

});

function animateTitle($backTitle, bW){
	var st = $('html').scrollTop() || $('body').scrollTop();
	var originLeft = $('#content>div.divItem').offset().left;
	if(st > 0){
		$backTitle.animate({'left': originLeft-bW});
	}else{
		$backTitle.animate({'left': originLeft});
	}
}

// set #content dimensions depending on window, nav and footer height
function setContentDimensions(wH, nH, fH, bH, $content){
	
	var paddT = parseInt( $content.css('padding-top').replace("px","") );
	var	paddB = parseInt( $content.css('padding-bottom').replace("px","") );
	var contentTopMargin = parseInt(bH+nH);
	var	contentMinHeight = wH-contentTopMargin-paddT-paddB-fH;
	//alert('contentTopMargin='+contentTopMargin+' paddT='+paddT+' paddB='+paddB+' fH='+fH);
	$content.css({'min-height': contentMinHeight+'px', 'margin': contentTopMargin+'px auto 0 auto'});
	$('.backTitle').css('top', (nH+paddT)+'px');
}
