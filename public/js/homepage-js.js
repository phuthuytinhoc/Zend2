// JavaScript Document
var $ = jQuery.noConflict();
$(document).ready(function(e) {
	
	/*********************************
	/*** Show name in avatar block
	/********************************/
    $('.newsInfo .friendNews-block .friendNews-block-avatar').mouseenter(function(e) {
		e.preventDefault();
        $(this).children('span').slideDown(300);
    });
	
	$('.newsInfo .friendNews-block .friendNews-block-avatar').mouseleave(function(e) {
		$(this).children('span').stop();
        $(this).children('span').slideUp(300);
    });
	
	$('.btnShow').click(function(){
		$('.btnShow').removeClass('select');
		$(this).addClass('select');
	});
	
	$('.btnShow.friend').click(function(e) {
		$('.news').stop();
        $('.news.placeNews').hide();
		$('.news.friendNews').fadeIn('slow');
        var newinfo_height = $('.newsInfo').outerHeight();
        $('.suggestion').css('height',newinfo_height);
    });
	$('.btnShow.place').click(function(e) {
		$('.news').stop();
        $('.news.friendNews').hide();
		$('.news.placeNews').fadeIn('slow');
        var newinfo_height = $('.newsInfo').outerHeight();
        $('.suggestion').css('height',newinfo_height);
    });
	
	$('.btnShow.friend').addClass('select');
	$('.placeNews').hide();
	/*********************************
	/*** END Show name in avatar block
	/********************************/
	
	/*********************************
	/*** BEGIN FLOAT LEFT SECTION
	/********************************/
	;(function($){

	$.fn.advScroll = function(option) {
		$.fn.advScroll.option = {
			marginTop:-8,
			easing:'',
			timer:0,
		};

		option = $.extend({}, $.fn.advScroll.option, option);

		return this.each(function(){
			var el = $(this);
			$(window).scroll(function(){
				t = parseInt($(window).scrollTop()) + option.marginTop;
				el.stop().animate({marginTop:t},option.timer,option.easing);
				})
			});
		};

	})(jQuery)
	
	$('.userInfo').advScroll();
	/*********************************
	/*** END FLOAT LEFT SECTION
	/********************************/
	
	/**********************************
	/*** BEGIN MAKE SUGGESTION SECTION HAVE SAME WIDTH HEIGHT NEWSINFO SECTION
	**********************************/
	var newinfo_height = $('.newsInfo').outerHeight();
    $('.suggestion').css('height',newinfo_height);
	/**********************************
	/*** END MAKE SUGGESTION SECTION HAVE SAME WIDTH HEIGHT NEWSINFO SECTION
	**********************************/
});
