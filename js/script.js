$('.page-scroll').on('click', function(){
	var element = $(this).attr('href');
	var target = $(element);

	// $('body').scrollTop('400');
	$("html, body").animate({
		scrollTop: target.offset().top - 50
	}, 1250, "easeInOutExpo");
	// console.log(target.offset().top);
	// console.log($('body').scrollTop(target.offset().top-50));
	// e.preventDefault();
});