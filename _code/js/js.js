
/* cookie function */
function setCookie(c_name,value,exdays){
	var exdate=new Date();exdate.setDate(exdate.getDate()+exdays);
	var c_value=escape(value)+((exdays==null) ? "" : "; expires="+exdate.toUTCString()+"; path=/");
	document.cookie=c_name+"="+c_value;
}

// make sure nav height never desappears below page bottom (it is positioned fixed...)
function limitNavHeight($nav, wH){
	nH = $nav.outerHeight(); // recalculate nav height
	//alert(nH);
	if(nH > wH){
		nH = wH;
		//alert('too high!');
		if($nav.hasClass('collapsible')){
			$nav.removeClass('collapsible');
		}
		$nav.css({'height':nH+'px', 'overflow':'auto'});
		$('#nav ul').css('margin-right', 0);
	}
}

// get window width and height
var wW = $(window).innerWidth();
var wH = $(window).innerHeight();

// set cookies of window width and height for later use
setCookie('wW', wW, 2);
setCookie('wH', wH, 2);


$(window).on("load", function(){

	var $nav = $('#nav');
	var navH = $nav.outerHeight;
	// get footer height
	var fH = $('#footer').outerHeight();

	// limit nav height (and update nH var)
	limitNavHeight($nav, wH);

	// this var will detremine where the footer stands, when content container is empty
	var contentMinHeight = wH-fH-87;

	// if viewport width is less than 980px, 
	if (document.documentElement.clientWidth < 980) {
		contentMinHeight = wH-fH-60;
	}

	// show/hide navigation for small screens
	// if viewport width is less than 720px, 
	if (document.documentElement.clientWidth < 720) {

		contentMinHeight = wH-fH-100;
		
		$nav.on('click', function(){

			if($(this).hasClass('collapsible')){
				$(this).removeClass('collapsible').removeAttr("style");
			}else if($(this).height() == wH){ // collaspible class has been removed by limitNavHeight function, so just look for nav_height = window_height
				$(this).css({'height':navH+'px', 'overflow':'hidden'});
				$('#nav ul').css('margin-right', '10px');
			}else{
				$(this).addClass('collapsible').removeAttr("style");
				limitNavHeight($nav, wH);
			}
		
			// avoid propagation of nav click if click on site title (#nav h1 a)
			$('#nav h1 a').click(function(e){
				e.stopPropagation();
			});
		});
	}

	// position footer at bottom of page even if no content
	$('#content').css('min-height', contentMinHeight+'px');

	// move '.aMore' link when mouse over '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseenter', 'a.imgMore', function(){
		$(this).closest('.divItem').find('.aMore').animate({paddingLeft: 20}, 200);
	});
	// move-back '.aMore' link on mouse leave '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseleave', 'a.imgMore', function(){
		$(this).closest('.divItem').find('.aMore').animate({paddingLeft: 0}, 200);
	});


	/** videos and embed codes */
	var $allVideos = $("iframe[src*='//player.vimeo.com'], iframe[src*='//www.youtube.com'], object, embed");
	$allVideos.each(function() {
		// jQuery .data does not work on object/embed elements
		$(this).attr('data-aspectRatio', this.height/this.width).removeAttr('height').removeAttr('width');
	});
	$(window).resize(function(){
		$allVideos.each(function(){
			var $el = $(this);
			var newWidth = $el.parents("div.divItem").width();
			$el.width(newWidth).height(newWidth * $el.attr('data-aspectRatio'));
		});
	}).resize();


	/** gallery function: 
	 * navigation with both dots and prev/next arrows */
	$('div.galContainer').on('click', 'a', function(e){
		e.preventDefault();
		var $this = $(this),
			$imgContainer = $this.parent('.gallery'),
			galHeight = $imgContainer.outerHeight(),
			galId = $this.parents('div.galContainer').attr('id'),
			$theImg = $('div#'+galId+' div.gallery img'),
			$links = $('div#'+galId+' div.gallNav a'), // get array of dot links
			total = $links.length,
			images = new Array(),
			nextImg,
			next,
			prev;
		
		// loop through dots links, to create image array from their data-img, and style/unstyle new selected dot
		$links.each(function( index ){
			var $dot = $(this);
			// if a dot was clicked ($this), the next image index is this dot's index
			if( $this.is($dot) ){
				nextImg = index;
			}
			// make image array
			images[index] = $dot.data('img');
			// remove selected dot
			if($dot.hasClass('selected')){
				$dot.removeClass('selected');
				next = index+1;
				prev = index-1;
			}
		});

		// make sure next and prev don't exceed image array or go negative (loop through sequence of images)
		if(next > (total-1)){next = 0;}
		if(prev < 0){prev = total-1;}
		
		// next or previous, or dot?
		if( $this.hasClass('next') ){
			nextImg = next;
		}else if( $this.hasClass('prev') ){
			nextImg = prev;
		}

		// fix the container height, to avoid jumping page when new image loads
		$imgContainer.css('height', galHeight+'px');

		// assign function to img onload; fade in, and un-fix the container height
		$theImg.on('load', function(){
			$(this).fadeIn(300);
			$imgContainer.css('height', 'auto');
		});
		
		// fade out current image, start loading new image, and select appropriate dot link
		$theImg.fadeOut( 200, function(){
			//alert(galHeight);
			$(this).attr("src", images[nextImg]);
			$links.eq( nextImg ).addClass('selected');
		});
		
		//alert('current:'+current+' next:'+next+' prev:'+prev);
	});
	/** end gallery function */


});
