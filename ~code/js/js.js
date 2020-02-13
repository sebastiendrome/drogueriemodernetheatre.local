
// get window width and height
var wW = $(window).innerWidth();
var wH = $(window).innerHeight();

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

// show/hide navigation for small screens if viewport width is less than 720px
function adjustNav($nav, nH){
	// must re-evaluate window width (can't use wW)
	if ($(window).innerWidth() < 720) {
		
		$nav.on('click', function(){

			if($(this).hasClass('collapsible')){
				$(this).removeClass('collapsible').removeAttr("style");
			}else if($(this).height() == wH){ // collaspible class has been removed by limitNavHeight function, so just look for nav_height = window_height
				$(this).css({'height':nH+'px', 'overflow':'hidden'});
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
}

// set cookies of window width and height for later use
setCookie('wW', wW, 2);
setCookie('wH', wH, 2);



$(window).on("load", function(){

	var $nav = $('#nav');
	var nH = $nav.outerHeight();

	// make articles images zoomable (div.divItem: only in site, not in admin) and if not nested in <a> tag
	$('div.divItem div.txt img, div.divItem div.html img').on('click', function(){
		var inA = $(this).parent('a'); // look for <a> parent tag
		if(inA.length === 0){ // image is not nested in an <a> tag
			var lm = $(this).attr('src');
			if(lm.indexOf('/'+content) != -1){
				lm = lm.replace('/'+content, '');
				window.location.href = '/'+demo+'~code/_zoom.php?img='+encodeURIComponent(lm)+'&lang='+lang;
			}else{
				window.open(lm, '_blank');
			}
		}
	});

	// limit nav height (and update nH var)
	limitNavHeight($nav, wH);

	// move '.aMore' link when mouse over '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseenter', 'a.imgMore', function(){
		$(this).closest('.divItem').find('div.title a').css('text-decoration','underline');
		//$(this).closest('.divItem').find('.more').animate({paddingLeft: 20}, 200);
	});
	// move-back '.aMore' link on mouse leave '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseleave', 'a.imgMore', function(){
		$(this).closest('.divItem').find('div.title a').css('text-decoration','none');
		//$(this).closest('.divItem').find('.more').animate({paddingLeft: 0}, 200);
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
		nH = $nav.outerHeight();
		adjustNav($nav, nH);
	}).resize();

	// zoom in gallery images
	$('div.galContainer span.galZoom').on('click', function(){
		var galId = $(this).parents('div.galContainer').attr('id'),
			$theImg = $('div#'+galId+' div.gallery img'),
			imgurl = $theImg.attr("src");
		window.location.href = '/'+demo+'~code/_zoom.php?img='+ encodeURIComponent( imgurl.replace('/'+content,'') ) +'&lang='+lang;

	});

	/** gallery function: 
	 * navigation with both dots and prev/next arrows, new image load */
	$('div.galContainer').on('click', 'a', function(e){
		e.preventDefault();
		var $this = $(this),
			galId = $this.parents('div.galContainer').attr('id'),
			$theImg = $('div#'+galId+' div.gallery img'),
			$imgContainer = $('div#'+galId+' div.gallery'),
			galHeight = $imgContainer.outerHeight(),
			$dots = $('div#'+galId+' div.gallNav a'), // get array of dot links
			total = $dots.length,
			images = new Array(),
			nextImg,
			next,
			prev;
		
		// loop through dots links, to create image array from their data-img, and style/unstyle new selected dot
		$dots.each(function( index ){
			var $dot = $(this);
			// make image array
			images[index] = $dot.data('img');

			// remove selected dot
			if( $dot.hasClass('selected') ){
				$dot.removeClass('selected');
				next = index+1;
				prev = index-1;
			}

			// if a dot was clicked ($this), the next image index is this dot's index
			if( $this.is($dot) ){
				nextImg = index;
				$dot.addClass('selected');
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

		if($theImg.attr("src") !== images[nextImg]){

			// fix the container height, to avoid jumping page when new image loads
			$imgContainer.css('height', galHeight+'px');
			// start fading out the current image
			$theImg.animate({'opacity': .3}, 500);

			// create new image with next img src
			var newImage = new Image();
			
			// inload function, must be declared before the new image source is set
			newImage.onload = function(){
				// stop the opacity animation, quickly fade in and change the src
				$theImg.attr("src", images[nextImg]).stop().animate({'opacity': 1}, 200);
				// container must adapt to new image height
				$imgContainer.css('height', 'auto');
				// select appropriate dot
				$dots.eq( nextImg ).addClass('selected');
			}

			// set the new source (will trigger onload function above)
			newImage.src = images[nextImg];
		}

	});
	/** end gallery function */


});
