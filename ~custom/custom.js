
// move '.aMore' link when mouse over '.imgMore' (for sub-sections)
$(window).on("load", function(){
	$('div.divItem').on('mouseenter', 'a.imgMore', function(){
		$(this).closest('.divItem').find('.aMore').css('text-decoration','none').animate({paddingLeft: 20}, 200);
	});
	// move-back '.aMore' link on mouse leave '.imgMore' (for sub-sections)
	$('div.divItem').on('mouseleave', 'a.imgMore', function(){
		$(this).closest('.divItem').find('.aMore').animate({paddingLeft: 0}, 200);
	});
});