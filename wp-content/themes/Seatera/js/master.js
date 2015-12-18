// On window load. This waits until images have loaded which is essential
/*global jQuery:false, my_ajax:false, on_resize:false */
/*jshint unused:false */
jQuery(window).load(function() {
	"use strict";

	jQuery('.wpb_thumbnails-fluid').isotope();

	if ( jQuery('embed').length != 0 ) {
		jQuery('embed').ready(function() {
			var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
			setTimeout( function() {
				jQuery('.overlay-hide').hide();
				loader.hide();
				jQuery('.pageload-overlay').hide();
			}, 1000 );
		});
	} else {
		var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
		setTimeout( function() {
			jQuery('.overlay-hide').hide();
			loader.hide();
			jQuery('.pageload-overlay').hide();
		}, 1000 );
	}

	function full_height_sidebar() {
		var main = 0;
		var sidebar_height = 0;
		var sidebar = '';
		if ( jQuery('.page-wrapper').hasClass('page-sidebar-right') ) {
			sidebar_height = jQuery('.sidebar-right').height();
			sidebar = jQuery('.sidebar-right');
			main = jQuery('.sidebar-right-pull').height();
		} else {
			sidebar_height = jQuery('.sidebar-left').height();
			sidebar = jQuery('.sidebar-left');
			main = jQuery('.sidebar-left-pull').height();
		};

		if ( jQuery(window).width() > 750 && main > sidebar.height() ) {
			sidebar.height(main);
		} else {
			sidebar.height('auto');
		}
	}
	full_height_sidebar();

});

/*
Plugin: jQuery Parallax
Version 1.1.3
Author: Ian Lunn
Twitter: @IanLunn
Author URL: http://www.ianlunn.co.uk/
Plugin URL: http://www.ianlunn.co.uk/plugins/jquery-parallax/

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

jQuery( document ).ajaxStop(function() {
	if ( jQuery('.vh_row_loading_effect').length == 1 ) {
		jQuery('li.notbooked').click(function() {
			jQuery('.vh_row_loading_effect').show();
		});
	}

	if ( jQuery('#movies_list_content').length == 1 ) {
		var noOfImages = jQuery(".movie_list_image img").length;
		var notLoaded = 0;

		if ( noOfImages > 0 ) {
			jQuery(".movie_list_image img").each(function(){
				if ( jQuery(this)[0].complete ) {
					notLoaded++;
					if( noOfImages === notLoaded ) {
						jQuery('#movies_list_content').css('opacity', '1');
						jQuery('.vh_loading_effect.movies_list').hide();
					}
				} else {
					jQuery(this).on('load', function(){
						notLoaded++;
						if( noOfImages === notLoaded ) {
							jQuery('#movies_list_content').css('opacity', '1');
							jQuery('.vh_loading_effect.movies_list').hide();
						}
					});
				}
			});
		} else {
			jQuery('.vh_loading_effect.movies_list').hide();
		}
	};

	if ( jQuery('#movie_post_content').length == 1 ) {
		var noOfImages = jQuery(".movie_image img").length;
		var notLoaded = 0;

		if ( noOfImages > 0 ) {
			jQuery(".movie_image img").each(function(){
				if ( jQuery(this)[0].complete ) {
					notLoaded++;
					if( noOfImages === notLoaded ) {
						jQuery('#movie_post_content').css('opacity', '1');
						jQuery('.vh_loading_effect').hide();
					}
				} else {
					jQuery(this).on('load', function(){
						notLoaded++;
						if( noOfImages === notLoaded ) {
							jQuery('#movie_post_content').css('opacity', '1');
							jQuery('.vh_loading_effect').hide();
						}
					});
				}
			});
		} else {
			jQuery('.vh_loading_effect').hide();
		}
	}

	function vh_hover_start_right(div) {
		var target = div;
		if ( div.is('input') ) {
			jQuery(div).parent().find('.hover_effect_right').css('left', '-3000px');
			jQuery(div).parent().find('.hover_effect_right').animate({
				opacity: "0.2",
			}, 1200, 'easeOutCirc', false);
			jQuery(div).parent().find('.hover_effect_right').animate({
				left: "0px",
			}, {duration: 30000, easing: 'linear', queue: false, complete: function(){ vh_hover_start_right(target); }});
		} else if ( div.hasClass('ui-state-default') || div.hasClass('ui-accordion-header') ) {
			jQuery(div).find('.hover_effect_right').css('left', '-3000px');
			jQuery(div).find('.hover_effect_right').animate({
				opacity: "0.7",
			}, 1200, 'easeOutCirc', false);
			jQuery(div).find('.hover_effect_right').animate({
				left: "0px",
			}, {duration: 30000, easing: 'linear', queue: false, complete: function(){ vh_hover_start_right(target); }});
		} else {
			jQuery(div).find('.hover_effect_right').css('left', '-3000px');
			jQuery(div).find('.hover_effect_right').animate({
				opacity: "0.2",
			}, 1200, 'easeOutCirc', false);
			jQuery(div).find('.hover_effect_right').animate({
				left: "0px",
			}, {duration: 30000, easing: 'linear', queue: false, complete: function(){ vh_hover_start_right(target); }});
		}
	}

	function vh_hover_start_left(div) {
		var target = div;
		jQuery(div).children('.hover_effect_left').css('right', '-3000px');
		jQuery(div).children('.hover_effect_left').animate({
			opacity: "0.2",
		}, 1200, 'easeOutCirc', false);
		jQuery(div).children('.hover_effect_left').animate({
			right: "0px",
		}, {duration: 30000, easing: 'linear', queue: false, complete: function(){ vh_hover_start_left(target); }});
	}

	function vh_hover_start_up(div) {
		var target = div;
		jQuery(div).children('.hover_effect_up').css('bottom', '0px');
		jQuery(div).children('.hover_effect_up').animate({
			opacity: "0.2",
		}, 1200, 'easeOutCirc', false);
		jQuery(div).children('.hover_effect_up').animate({
			bottom: "1500px",
		}, {duration: 25000, easing: 'linear', queue: false, complete: function(){ vh_hover_start_up(target); }});
	}


	jQuery(".hover_right").live( "mouseenter", function() {
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'hidden')
		} else {
			jQuery(this).css('overflow', 'hidden');
		}

		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'hidden')
			if ( jQuery(this).find('.hover_effect_right').length == 0 ) {
				jQuery(this).append('<span class="hover_effect_right"></span>');
			};
		} else if ( jQuery(this).is('input') ) {
			jQuery(this).parent().css('position','relative');
			jQuery(this).parent().addClass('input_parent');
			if ( jQuery(this).parent().find('.input_hover').length == 0 ) {
				jQuery(this).parent().append('<div class="input_hover" style="height: '+jQuery(this).height()+'px; width: '+jQuery(this).outerWidth()+'px; position: absolute; top: 0; overflow: hidden; cursor: pointer; z-index: 9999;"><span class="hover_effect_right" style="height: 150%; top: -12px;"></span></div>');
			};
		} else {
			if ( jQuery(this).children().is('a') ) {
				if ( jQuery(this).find('.hover_effect_right').length == 0 ) {
					jQuery(this).children().append('<span class="hover_effect_right"></span>');
				};
			} else {
				if ( jQuery(this).find('.hover_effect_right').length == 0 ) {
					jQuery(this).append('<span class="hover_effect_right"></span>');
				};
			}
			jQuery(this).css('overflow', 'hidden');
		}
		vh_hover_start_right(jQuery(this));
	});

	jQuery(".hover_right").live( "mouseleave", function() {
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'visible')
		} else {
			jQuery(this).css('overflow', 'visible');
		}
		jQuery(this).find('.hover_effect_right').remove();
		jQuery(this).parent().find('.input_hover').remove();
	});


	jQuery(".hover_left").live( "mouseenter", function() {
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'hidden')
		} else {
			jQuery(this).css('overflow', 'hidden');
		}
		if ( jQuery(this).find('.hover_effect_left').length == 0 ) {
			jQuery(this).append('<span class="hover_effect_left"></span>');
		};
		vh_hover_start_left(jQuery(this));
	});

	jQuery(".hover_left").live( "mouseleave", function() {
		jQuery(this).find('.hover_effect_left').remove();
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'visible')
		} else {
			jQuery(this).css('overflow', 'visible');
		}
	});
	
	jQuery(".hover_up").live( "mouseenter", function() {
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'hidden')
		} else {
			jQuery(this).css('overflow', 'hidden');
		}
		if ( jQuery(this).find('.hover_effect_up').length == 0 ) {
			jQuery(this).append('<span class="hover_effect_up"></span>');
		}
		vh_hover_start_up(jQuery(this));
	});

	jQuery(".hover_up").live( "mouseleave", function() {
		jQuery(this).children('.hover_effect_up').remove();
		if ( jQuery(this).is('a') ) {
			jQuery(this).parent().css('overflow', 'visible')
		} else {
			jQuery(this).css('overflow', 'visible');
		}
	});
});

jQuery(document).ready(function($) {
	"use strict";

	if ( jQuery('.vh_loading_effect').length == 1 ) {
		jQuery('.wrapper-dropdown-1 li, .wrapper-dropdown-2 li, .wrapper-dropdown-3 li, .wrapper-dropdown-4 li').click(function() {
			jQuery('.vh_loading_effect').show();
			jQuery('#movie_post_content').css('opacity', '0');
		});
	};

	if ( jQuery('.vh_loading_effect.movies_list').length == 1 ) {
		jQuery('.wrapper-dropdown-1 li, .wrapper-dropdown-2 li, .wrapper-dropdown-4 li').click(function() {
			jQuery('.vh_loading_effect.movies_list').show();
			jQuery('#movies_list_content').css('opacity', '0');
		});
	};

	var loader = new SVGLoader( document.getElementById( 'loader' ), { speedIn : 400, easingIn : mina.easeinout } );
	loader.show();

	jQuery('.row_seats_signup_buttons a').click(function(e) {
		e.preventDefault();
	});

	// Perform AJAX login on form submit
	jQuery('form#login').on('submit', function(e) {
		jQuery('form#login p.status').show().text(ajax_login_object.loadingmessage);
		jQuery.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
				'username': jQuery('form#login #username').val(), 
				'password': jQuery('form#login #password').val(), 
				'security': jQuery('form#login #security').val()
			},
			success: function(data) {
				jQuery('form#login p.status').text(data.message);
				if (data.loggedin == true) {
					document.location.href = ajax_login_object.redirecturl;
				}
			}
		});
		e.preventDefault();
	});

	var $isotope_container = jQuery(".blog .wpb_thumbnails");

	$isotope_container.isotope({ straightAcross : true });

	if (typeof smartresize !== 'undefined' && $.isFunction(smartresize)) {
		// update columnWidth on window resize
		jQuery(window).smartresize(function(){
			$isotope_container.isotope({

				// update columnWidth to a percentage of container width
				masonry: { columnWidth: $isotope_container.width() / 2 }
			});

			if ( jQuery(window).width() <= 767 ) {
				jQuery(".video-module-title").each(function(i, val) { console.log(jQuery(this).val());
					if (jQuery(this).val() == '&nbsp;') {
						jQuery(this).hide();
					}
				});
			}
			
		});
	}

	jQuery(".scroll-to-top").click(function() {
		jQuery("html, body").animate({ scrollTop: 0 }, "slow");
		return false;
	});


	jQuery('.package_button').click(function(e) {
		e.preventDefault();
		jQuery(this).parent().parent().find('input:radio').prop('checked', true);
		jQuery('#job_package_selection').submit();
	});


	var baseurl;
	baseurl = '<?php echo $site_url; ?>';

	function currentVideo(vid,videoids){
	for(var i = 0; i < videoids.length; i++){
		if(videoids[i]!=vid){
			var prev_fragment = document.getElementById('nav-fragment-'+videoids[i])
			prev_fragment.className = "ui-tabs-nav-item" ;
		}
	}
		var fragment = document.getElementById('nav-fragment-'+vid)
		fragment.className += " ui-tabs-selected" ;
	}

	function full_height_sidebar() {
		var main = 0;
		var sidebar_height = 0;
		var sidebar = '';
		if ( jQuery('.page-wrapper').hasClass('page-sidebar-right') ) {
			sidebar_height = jQuery('.sidebar-right').height();
			sidebar = jQuery('.sidebar-right');
			main = jQuery('.sidebar-right-pull').height();
		} else {
			sidebar_height = jQuery('.sidebar-left').height();
			sidebar = jQuery('.sidebar-left');
			main = jQuery('.sidebar-left-pull').height();
		};

		if ( jQuery(window).width() > 750 && main > sidebar.height() || main == sidebar.height() ) {
			sidebar.height(main);
		} else {
			sidebar.height('auto');
		}
	}

	jQuery(window).resize(function() {
		full_height_sidebar();
	});

	// header_size();

	jQuery('#movies_list_content .event_container').isotope({
			itemSelector: '.event_list',
			layoutMode: 'fitRows',
			transformsEnabled: true,
			
			sortAscending: false,
			animationOptions: {
				duration: 250,
				easing: 'swing',
				queue: true
			},
			animationEngine : "jquery"
		});

	jQuery('.front_page_post_grid ul.wpb_thumbnails li.isotope-item .post-grid-item-wrapper .entry-content, .teaser_grid_container_carousel .post_carousel_item .entry-content').dotdotdot({
		height: 110
	});

	jQuery('.video_container .play').click(function(e) {
		e.preventDefault();
		jQuery('.vh_wrapper').addClass('blur');
		var bg = jQuery('body').css('background-image');
		var bg_pos = jQuery('body').css('background-position');
		jQuery('.vh_wrapper').css({
			'background-image': bg,
			'background-position': bg_pos,
			'background-repeat': 'no-repeat'
		});
		jQuery('.image_gallery_container').parent().append('<div id="event_dialog" title="Video"></div>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').append(jQuery(this).parent().find('.wp-video'));
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').dialog({ 
			modal: true, 
			width: 640,
			resizable: false,
			dialogClass: "spotlight",
			position: { my: "center center", at: "center center" },
			close: function() {
				jQuery('.video_container').append(jQuery(this).find('.wp-video'));
				jQuery(this).dialog('destroy');
				jQuery('.vh_wrapper').removeClass('blur');
				jQuery('#event_dialog').remove();
			} 
		});
	});

	if ( date_format.date_format == "dd.mm.yy" ) {
		var date = jQuery('#todays_date').val();
		cat_ajax_get('','',date,jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val(),jQuery('#event_limit').val(),jQuery('#theatres_all_events').val(),'true',my_ajax.ajaxurl);
	} else {
		cat_ajax_get('','',jQuery('#todays_date').val(),jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val(),jQuery('#event_limit').val(),jQuery('#theatres_all_events').val(),'true',my_ajax.ajaxurl);
	}

	jQuery('.wrapper-dropdown-1 .dropdown li').click(function(e) {
		var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
		if ( category == null ) { category = '' };
		var date = jQuery('#movie-datepicker').val();
		if ( date == null ) { date = '' };
		var time = jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val();
		if ( time == null ) { time = '' };
		if ( jQuery('#theatres_all_events').val() != '1' && jQuery('#movies_list_events').val() != '1' ) {
			if ( date_format.date_format == 'mm.dd.yy' ) {
				date = jQuery('#movie-datepicker').val();
			} else if ( date_format.date_format == 'dd.mm.yy' ) {
				var split_date = date.split(date_format.date_separator);
				date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
			};
			if ( date_format.date_separator == '.' ) {
				date = date.replace(/\./g, '/');
			} else {
				date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
			}
		}
		cat_ajax_get(category, jQuery(this).find('input[type=hidden]').val(), $.datepicker.formatDate('mm.dd.yy', new Date(date)), time, jQuery('#event_limit').val(), jQuery('#theatres_all_events').val(), 'false', my_ajax.ajaxurl);
	});

	jQuery('#movie-datepicker').click(function() {
		jQuery(this).parent().addClass('active');
	});

	jQuery("#movie-datepicker").datepicker({
		dateFormat: date_format.date_format.replace(/\./g, date_format.date_separator),
		onSelect: function(dateText, inst) {
			if ( jQuery('#movie_post_content').length != 0 ) {
				var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
				if ( category == null ) { category = '' };
				var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
				if ( theatre == null ) { theatre = '' };
				var time = jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val();
				if ( time == null ) { time = '' };
				var date = jQuery('#movie-datepicker').val();
				if ( date_format.date_format == 'mm.dd.yy' ) {
					date = jQuery('#movie-datepicker').val();
				} else if ( date_format.date_format == 'dd.mm.yy' ) {
					var split_date = date.split(date_format.date_separator);
					date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
				};
				if ( date_format.date_separator == '.' ) {
					date = date.replace(/\./g, '/');
				} else {
					date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
				}
				cat_ajax_get(category, theatre, $.datepicker.formatDate('mm.dd.yy', new Date(date)), time, jQuery('#event_limit').val(), jQuery('#theatres_all_events').val(), 'false', my_ajax.ajaxurl);
				jQuery('#next_date').val($.datepicker.formatDate('mm.dd.yy', new Date(date)));
				var time_iteration = 0;
				var times_changed = 0;
				var upcomming_time = date_format.upcomming_time;
				var upcomming_time_start_split = upcomming_time.split(':');
				jQuery('#dd3 .dropdown li').each(function(){
					if ( upcomming_time == '' ) {
						if ( date_format.time_format == '24h' ) {
							if ( time_iteration < 10 ) {
								jQuery(this).find('span').html('0'+time_iteration+':00');
								jQuery(this).find('input[type=hidden]').val('0'+time_iteration+':00');
							} else {
								jQuery(this).find('span').html(time_iteration+':00');
								jQuery(this).find('input[type=hidden]').val(time_iteration+':00');
							}
						} else {
							if ( time_iteration < 10 ) {
								jQuery(this).find('span').html('0'+time_iteration+':00 am');
								jQuery(this).find('input[type=hidden]').val('0'+time_iteration+':00');
							} else {
								if ( time_iteration < 12 ) {
									jQuery(this).find('span').html(time_iteration+':00 am');
									jQuery(this).find('input[type=hidden]').val(time_iteration+':00');
								} else {
									jQuery(this).find('span').html(time_iteration+':00');
									jQuery(this).find('input[type=hidden]').val(time_iteration+':00');
								}
							}
						}
						times_changed++;
						time_iteration++;
					} else {
						if ( date_format.time_format == '24h' ) {
							if ( upcomming_time_start_split[0] < 10 ) {
								jQuery(this).find('span').html('0'+upcomming_time_start_split[0]+':00');
								jQuery(this).find('input[type=hidden]').val('0'+upcomming_time_start_split[0]+':00');
								times_changed++;
							} else {
								if ( upcomming_time_start_split[0] < 24 ) {
									jQuery(this).find('span').html(upcomming_time_start_split[0]+':00');
									jQuery(this).find('input[type=hidden]').val(upcomming_time_start_split[0]+':00');
									times_changed++;
								};
							}
						} else {
							if ( upcomming_time_start_split[0] < 10 ) {
								jQuery(this).find('span').html('0'+upcomming_time_start_split[0]+':00 am');
								jQuery(this).find('input[type=hidden]').val('0'+upcomming_time_start_split[0]+':00');
								times_changed++;
							} else {
								if ( upcomming_time_start_split[0] < 12 ) {
									if ( upcomming_time_start_split[0] < 24 ) {
										jQuery(this).find('span').html(upcomming_time_start_split[0]+':00 am');
										jQuery(this).find('input[type=hidden]').val(upcomming_time_start_split[0]+':00');
										times_changed++;
									};
								} else {
									if ( upcomming_time_start_split[0] == 13 ) {
										upcomming_time_start_split[0] = 1;
										var time_changed = true;
									};
									if ( upcomming_time_start_split[0] < 24 ) {
										jQuery(this).find('span').html(upcomming_time_start_split[0]+':00 pm');
										if ( time_changed != true ) {
											jQuery(this).find('input[type=hidden]').val(upcomming_time_start_split[0]+':00');
										} else {
											jQuery(this).find('input[type=hidden]').val(upcomming_time_start_split[0]+12+':00');
										}
										
										times_changed++;
									};
								}

							}
						}
						upcomming_time_start_split[0]++;
					}
				});
				var removable_item = 0;
				jQuery('#dd3 .dropdown li').each(function(){
					if ( removable_item > times_changed || removable_item == times_changed ) {
						jQuery(this).remove();
					};
					removable_item++;
				});
				jQuery('.vh_loading_effect').show();
				jQuery('#movie_post_content').css('opacity', '0');
			} else if ( jQuery('#movies_list_content').length != 0 ) {
				var date = jQuery('#movie-datepicker').val();
				if ( date_format.date_format == 'mm.dd.yy' ) {
					date = jQuery('#movie-datepicker').val();
				} else if ( date_format.date_format == 'dd.mm.yy' ) {
					var split_date = date.split(date_format.date_separator);
					date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
				};
				if ( date == null ) { date = '' };
				if ( date_format.date_separator == '.' ) {
					date = date.replace(/\./g, '/');
				} else {
					date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
				}
				var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
				if ( theatre == null ) { theatre = '' };
				var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
				if ( category == null ) { category = '' };
				var sort = jQuery('.wrapper-dropdown-6 .dropdown li.active').find('input[type=hidden]').val();
				if ( sort == null ) { sort = '' };
				vh_movies_list_get($.datepicker.formatDate('mm.dd.yy', new Date(date)),theatre,category,sort,jQuery('#movies_list_events').val(),my_ajax.ajaxurl);
				jQuery('.vh_loading_effect.movies_list').show();
				jQuery('#movies_list_content').css('opacity', '0');
			} else if ( jQuery('#event_ticket_content').length != 0 ) {
				var date = jQuery('#movie-datepicker').val();
				if ( date_format.date_format == 'mm.dd.yy' ) {
					date = jQuery('#movie-datepicker').val();
				} else if ( date_format.date_format == 'dd.mm.yy' ) {
					var split_date = date.split(date_format.date_separator);
					date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
				};
				if ( date_format.date_separator == '.' ) {
					date = date.replace(/\./g, '/');
				} else {
					date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
				}
				vh_event_ticket_get($.datepicker.formatDate('mm.dd.yy', new Date(date)), jQuery('#post_id').val(), jQuery('#show_all_tickets').val(), my_ajax.ajaxurl);
			};
		},
		onClose: function(dateText, inst) {
			jQuery('#movie-datepicker').parent().removeClass('active');
		},
		beforeShow: function (textbox, instance) {
            instance.dpDiv.css({
                    marginTop: (-textbox.offsetHeight+31) + 'px'
            });
    	}
	});

	// jQuery('.ui-datepicker-calendar td').live( "click", function(e) {
	// 	console.log(jQuery('#movie-datepicker').val());
	// 	var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( category == null ) { category = '' };
	// 	var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( theatre == null ) { theatre = '' };
	// 	var time = jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( time == null ) { time = '' };
	// 	cat_ajax_get(category, theatre, jQuery('#movie-datepicker').val(), time, jQuery('#event_limit').val(), my_ajax.ajaxurl);
	// });

	jQuery('.wrapper-dropdown-3 .dropdown li').click(function(e) {
		var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
		if ( category == null ) { category = '' };
		var date = jQuery('#movie-datepicker').val();
		if ( date == null ) { date = '' };
		if ( jQuery('#theatres_all_events').val() != '1' ) {
			if ( date_format.date_format == 'mm.dd.yy' ) {
				date = jQuery('#movie-datepicker').val();
			} else if ( date_format.date_format == 'dd.mm.yy' ) {
				var split_date = date.split(date_format.date_separator);
				date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
			};
			if ( date_format.date_separator == '.' ) {
				date = date.replace(/\./g, '/');
			} else {
				date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
			}
		}
		var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
		if ( theatre == null ) { theatre = '' };
		cat_ajax_get(category, theatre, $.datepicker.formatDate('mm.dd.yy', new Date(date)), jQuery(this).find('input[type=hidden]').val(), jQuery('#event_limit').val(), jQuery('#theatres_all_events').val(), 'false', my_ajax.ajaxurl);
	});

	jQuery('.wrapper-dropdown-4 .dropdown li').click(function(e) {
		var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
		if ( theatre == null ) { theatre = '' };
		var date = jQuery('#movie-datepicker').val();
		if ( date == null ) { date = '' };
		if ( jQuery('#theatres_all_events').val() != '1' && jQuery('#movies_list_events').val() != '1' ) {
			if ( date_format.date_format == 'mm.dd.yy' ) {
				date = jQuery('#movie-datepicker').val();
			} else if ( date_format.date_format == 'dd.mm.yy' ) {
				var split_date = date.split(date_format.date_separator);
				date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
			};
			if ( date_format.date_separator == '.' ) {
				date = date.replace(/\./g, '/');
			} else {
				date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
			}
		}
		var time = jQuery('.wrapper-dropdown-3 .dropdown li.active').find('input[type=hidden]').val();
		if ( time == null ) { time = '' };
		cat_ajax_get(jQuery(this).find('input[type=hidden]').val(), theatre, $.datepicker.formatDate('mm.dd.yy', new Date(date)), time, jQuery('#event_limit').val(), jQuery('#theatres_all_events').val(), 'false', my_ajax.ajaxurl);
	});

	function cat_ajax_get(catID,theatre,dates,times,limit,all_events,initial,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "load-filter", cat: catID, theatres: theatre, date: dates, time: times, event_limit: limit, show_all_events: all_events, initial_load: initial},
			success: function(response) {
				jQuery("#movie_post_content").html(response);
				return false;
			}
		});
	}

	vh_event_ticket_get($.datepicker.formatDate('mm.dd.yy', new Date()), jQuery('#post_id').val(), jQuery('#show_all_tickets').val(), my_ajax.ajaxurl);

	function vh_event_ticket_get(dates,id,tickets,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "event-filter", date: dates, post_id: id, all_tickets: tickets},
			success: function(response) {
				jQuery("#event_ticket_content").html(response);
				return false;
			}
		});
	}

	// jQuery('.wrapper-dropdown-2.movies_list .dropdown li').click(function(e) {
	// 	var date = jQuery('#movie-datepicker').val();
	// 	if ( date == null ) { date = '' };
	// 	var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( theatre == null ) { theatre = '' };
	// 	var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( category == null ) { category = '' };
	// 	var sort = jQuery('.wrapper-dropdown-6 .dropdown li.active').find('input[type=hidden]').val();
	// 	if ( sort == null ) { sort = '' };
	// 	vh_movies_list_get(date,theatre,category,sort,my_ajax.ajaxurl);
	// });

	jQuery('.wrapper-dropdown-4 .dropdown li').click(function(e) {
		var date = jQuery('#movie-datepicker').val();
		if ( date == null ) { date = '' };
		var theatre = jQuery('.wrapper-dropdown-1 .dropdown li.active').find('input[type=hidden]').val();
		if ( theatre == null ) { theatre = '' };
		var category = jQuery(this).find('input[type=hidden]').val();
		if ( category == null ) { category = '' };
		var sort = jQuery('.wrapper-dropdown-6 .dropdown li.active').find('input[type=hidden]').val();
		if ( sort == null ) { sort = '' };
		if ( jQuery('#theatres_all_events').val() != '1' && jQuery('#movies_list_events').val() != '1' ) {
			if ( date_format.date_format == 'mm.dd.yy' ) {
				date = jQuery('#movie-datepicker').val();
			} else if ( date_format.date_format == 'dd.mm.yy' ) {
				var split_date = date.split(date_format.date_separator);
				date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
			};
			if ( date_format.date_separator == '.' ) {
				date = date.replace(/\./g, '/');
			} else {
				date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
			}
		}
		vh_movies_list_get($.datepicker.formatDate('mm.dd.yy', new Date(date)),theatre,category,sort,jQuery('#movies_list_events').val(),my_ajax.ajaxurl);
	});

	jQuery('.wrapper-dropdown-1 .dropdown li').click(function(e) {
		var date = jQuery('#movie-datepicker').val();
		if ( date == null ) { date = '' };
		var theatre = jQuery(this).find('input[type=hidden]').val();
		if ( theatre == null ) { theatre = '' };
		var category = jQuery('.wrapper-dropdown-4 .dropdown li.active').find('input[type=hidden]').val();
		if ( category == null ) { category = '' };
		var sort = jQuery('.wrapper-dropdown-6 .dropdown li.active').find('input[type=hidden]').val();
		if ( sort == null ) { sort = '' };
		if ( jQuery('#theatres_all_events').val() != '1' && jQuery('#movies_list_events').val() != '1' ) {
			if ( date_format.date_format == 'mm.dd.yy' ) {
				date = jQuery('#movie-datepicker').val();
			} else if ( date_format.date_format == 'dd.mm.yy' ) {
				var split_date = date.split(date_format.date_separator);
				date = split_date[1]+date_format.date_separator+split_date[0]+date_format.date_separator+split_date[2];
			};
			if ( date_format.date_separator == '.' ) {
				date = date.replace(/\./g, '/');
			} else {
				date = date.replace(new RegExp(date_format.date_separator,"igm"), '/');
			}
		}
		vh_movies_list_get($.datepicker.formatDate('mm.dd.yy', new Date(date)),theatre,category,sort,jQuery('#movies_list_events').val(),my_ajax.ajaxurl);
	});

	vh_movies_list_get($.datepicker.formatDate('mm.dd.yy', new Date()),'','',jQuery('.wrapper-dropdown-6 .dropdown li.active').find('input[type=hidden]').val(),jQuery('#movies_list_events').val(),my_ajax.ajaxurl);


	function vh_movies_list_get(dates,theatre,category,sort,all_list,ajaxurl) {
		jQuery.ajax({
			type: 'POST',
			url: ajaxurl,
			data: {"action": "movies-filter", date: dates, theatres: theatre, cat: category, sorting: sort, list_all_events: all_list},
			success: function(response) {
				jQuery("#movies_list_content").html(response);

				return false;
			}
		});
	}

	// jQuery('.wrapper-dropdown-5 .dropdown li').click(function(e) {
	// 	var date = jQuery('#movie-datepicker').val();
	// 	if ( date == null ) { date = '' };
	// 	vh_event_ticket_get(date, jQuery('#post_id').val(), my_ajax.ajaxurl);
	// });

	jQuery( ".spotlight_cont .spotlight_container" ).mouseenter(function() {
		jQuery(this).children('.spotlight_controls').show().animate({
			bottom: "5",
		}, 300, function() {
			// Animation complete.
		});
		jQuery(this).children('.line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
		jQuery(this).children('.movie_title').show().animate({
			top: "0",
		}, 300, function() {
			// Animation complete.
		});
		jQuery('.spotlight_controls a').mouseenter(function() {
			var border_color = jQuery('.secondary-body-color').css('background-color');
			if (jQuery(this).hasClass('icon-ticket')) {
				border_color = jQuery('.main-body-color').css('background-color');
			} else if (jQuery(this).hasClass('icon-info')) {
				border_color = '#000000';
			}

			jQuery(this).parent().parent().parent().children('.line').css('borderBottomColor', border_color);
			jQuery(this).parent().parent().parent().children('.line').fadeIn(50);
		}).mouseleave(function () {
			var isHovered = !!jQuery(this).filter(function() { return jQuery(this).is(":hover"); }).length;

			if (isHovered) {
				jQuery(this).parent().parent().parent().children('.line').hide();
			}
			jQuery(this).parent().parent().parent().children('.line').css('border-bottom-color', '');
		});
	});

	jQuery( ".spotlight_cont .spotlight_container" ).mouseleave(function() {
		jQuery(this).children('.spotlight_controls').show().animate({
			bottom: "-60",
		}, 300, function() {
			// Animation complete.
		});
		jQuery(this).children('.line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
		jQuery(this).children('.movie_title').show().animate({
			top: "-100",
		}, 300, function() {
			// Animation complete.
		});
	});

	jQuery( ".movie_image" ).live( "mouseenter", function(e) {
		jQuery(this).children('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".movie_image" ).live( "mouseleave", function(e) {
		jQuery(this).children('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".post-thumb-img-wrapper" ).mouseenter(function() {
		jQuery(this).children('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".post-thumb-img-wrapper" ).mouseleave(function() {
		jQuery(this).children('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".seatera-recentpostsplus.widget .news_image" ).mouseenter(function() {
		jQuery(this).children('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".seatera-recentpostsplus.widget .news_image" ).mouseleave(function() {
		jQuery(this).children('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".image_gallery_container .image_container, .image_gallery_container .video_container" ).mouseenter(function() {
		jQuery(this).find('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".image_gallery_container .image_container, .image_gallery_container .video_container" ).mouseleave(function() {
		jQuery(this).find('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".post-thumb-img-wrapper" ).mouseenter(function() {
		jQuery(this).find('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery( ".post-thumb-img-wrapper" ).mouseleave(function() {
		jQuery(this).find('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			// Animation complete.
		 });
	});

	jQuery('.header_search .sb-icon-search').click(function() {
		jQuery('.header_search .sb-icon-search').css({'right': '20px', 'left': 'auto'});
		if ( !jQuery('.header_search').hasClass('active') ) {
			jQuery('.header_search').css('width', '0%');
			jQuery('.header_search').animate({
				width: "18.6%",
			}, 300, function() {
				jQuery('.header_search').addClass('active');
				jQuery('.header_search .sb-icon-search').addClass('active');
			});
			jQuery('.header_search .footer_search_input').val('');
			jQuery('.header_search .footer_search_input').focus();
		};
	});

	var times_changed = 0;
	jQuery('.header_search .sb-icon-search').click(function() {
		jQuery('.header_search .footer_search_input').change(function() {
			times_changed = times_changed + 1;
		});

		if ( times_changed != 0 ) {
			jQuery(this).parent().submit();
			jQuery.cookie('vh_search_open', '1', { path: '/' });
		} else {
			if ( jQuery('.header_search').hasClass('active') ) {
				jQuery('.header_search').animate({
					width: "0%",
				}, 300, function() {
					jQuery('.header_search').removeClass('active');
					jQuery('.header_search .sb-icon-search').css({'left': '10px', 'right': 'auto'});
				});
			};
			jQuery.cookie('vh_search_open', '0', { path: '/' });
		}
	});

	if ( jQuery.cookie('vh_search_open') == null ) {
		jQuery.cookie('vh_search_open', '0', { path: '/' });
	};

	if ( jQuery.cookie('vh_search_open') == '1' ) {
		jQuery('.header_search').addClass('active');
		jQuery('.header_search').css('width', '18.6%');
		jQuery('.header_search .sb-icon-search').css({
			'right': '20px',
			'left': 'auto'
		});
	};

	jQuery('body').click(function() {
		if ( jQuery('.header_search').hasClass('active') ) {
			if ( jQuery('.header_search .footer_search_input').val() == '' ) {
				jQuery('.header_search').animate({
					width: "0%",
				}, 300, function() {
					jQuery('.header_search').removeClass('active');
					jQuery('.header_search .sb-icon-search').css({'left': '10px', 'right': 'auto'});
				});
			};
		}
	});

	jQuery('.wpb_button').each(function(){
		jQuery(this).addClass('hover_right');
	});

	if ( jQuery('.wpb_map_wraper').parent().height() != null ) {
		jQuery('.wpb_gmaps_widget').parent().parent().parent().css('margin-bottom', '0');
		jQuery('.wpb_map_wraper').parent().css('padding', '0');
	};

	if ( jQuery('div').hasClass('wpb_map_wraper') ) {
		jQuery('.sidebar-no-pull').css('padding', '30px 30px 0px 30px');
	};

	function DropDown(el) {
				this.dd = el;
				this.placeholder = this.dd.children('span');
				this.opts = this.dd.find('ul.dropdown > li');
				this.val = '';
				this.index = -1;
				this.initEvents();
	}
	DropDown.prototype = {
		initEvents : function() {
			var obj = this;

			obj.dd.on('click', function(event){
				jQuery(this).toggleClass('active');
				if ( jQuery(this).hasClass('active') ) {
					jQuery('.dropdown_list').removeClass('active');
					jQuery('ul.dropdown').slideUp(150);
					jQuery(this).find('ul.dropdown').slideDown(300);
					jQuery(this).addClass('active');
				} else {
					jQuery('ul.dropdown').slideUp(150);
				}
				
				return false;
			});

			obj.opts.on('click',function(){
				var opt = jQuery(this);
				obj.val = opt.text();
				obj.index = opt.index();
				obj.placeholder.text(obj.val);
				jQuery(this).parent().find('li').removeClass('active');
				jQuery('ul.dropdown').slideUp(150);
				jQuery(this).toggleClass('active');
			});
		},
		getValue : function() {
			return this.val;
		},
		getIndex : function() {
			return this.index;
		}
	}

	jQuery(function() {
		var dd = new DropDown( jQuery('#dd') );
		var dd2 = new DropDown( jQuery('#dd2') );
		var dd3 = new DropDown( jQuery('#dd3') );
		var dd4 = new DropDown( jQuery('#dd4') );
		var dd5 = new DropDown( jQuery('#dd5') );
		var dd6 = new DropDown( jQuery('#dd6') );

		jQuery(document).click(function() {
			// all dropdowns
			jQuery('.wrapper-dropdown-1').removeClass('active');
			jQuery('.wrapper-dropdown-2').removeClass('active');
			jQuery('.wrapper-dropdown-3').removeClass('active');
			jQuery('.wrapper-dropdown-4').removeClass('active');
			jQuery('.wrapper-dropdown-5').removeClass('active');
			jQuery('.wrapper-dropdown-6').removeClass('active');
			jQuery('.wrapper-dropdown-1').find('ul.dropdown').slideUp(150);
			jQuery('.wrapper-dropdown-2').find('ul.dropdown').slideUp(150);
			jQuery('.wrapper-dropdown-3').find('ul.dropdown').slideUp(150);
			jQuery('.wrapper-dropdown-4').find('ul.dropdown').slideUp(150);
			jQuery('.wrapper-dropdown-5').find('ul.dropdown').slideUp(150);
			jQuery('.wrapper-dropdown-6').find('ul.dropdown').slideUp(150);
		});

	});

	jQuery('.tp-caption.Slide-title').addClass('hover_right');
	jQuery('.tp-leftarrow.tparrows').addClass('hover_left');
	jQuery('.tp-rightarrow.tparrows').addClass('hover_right');
	jQuery('.header-menu li ul').prepend('<span class="shadows"></span>');
	jQuery('.header-menu li ul').append('<span class="dropdown_spike"></span>');
	jQuery('.wpb_tabs_nav li').each(function(){
		if ( !jQuery(this).hasClass('ui-state-active') ) {
			jQuery(this).addClass('hover_right');
		};
	});
	jQuery('.wpb_accordion_header').each(function(){
		if ( !jQuery(this).hasClass('ui-state-active') ) {
			jQuery(this).addClass('hover_right');
		};
	});

	// Dropdown for movies module
	jQuery('.dropdown_list ul.dropdown').prepend('<span class="shadows"></span>');
	jQuery('.dropdown_list ul.dropdown').prepend('<span class="dropdown_spike"></span>');


	// jQuery('.newsletter-submit').addClass('hover_right');
	jQuery('.newsletter-submit').parent().addClass('input_parent');

	jQuery(".spotlight_trailer .icon-play-1").click(function(e) {
		e.preventDefault();
		jQuery('.vh_wrapper').addClass('blur');
		var bg = jQuery('body').css('background-image');
		var bg_pos = jQuery('body').css('background-position');
		jQuery('.vh_wrapper').css({
			'background-image': bg,
			'background-position': bg_pos,
			'background-repeat': 'no-repeat'
		});
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().append('<div id="event_dialog" title="'+jQuery(this).parent().parent().parent().find('.movie_title').html()+'"></div>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').append('<iframe width="640" height="360" src="//www.youtube.com/embed/'+jQuery(this).parent().find('input[type=hidden]').val()+'?rel=0" frameborder="0" allowfullscreen style="display: block"></iframe>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').dialog({ 
			modal: true, 
			width: 640,
			resizable: false,
			dialogClass: "spotlight",
			position: { my: "center center", at: "center center" },
			close: function() {
				jQuery(this).dialog('destroy');
				jQuery('.vh_wrapper').removeClass('blur');
				jQuery('#event_dialog').remove();
			} 
		});
	});

	jQuery(".eventrating.widget .icon-play-1").each(function(){
		if ( jQuery(this).children().val() == '' ) {
			jQuery(this).hide();
		};
	});

	jQuery(".prettyphoto").click(function() {
		jQuery('.vh_wrapper').addClass('blur');
		var bg = jQuery('body').css('background-image');
		var bg_pos = jQuery('body').css('background-position');
		jQuery('.vh_wrapper').css({
			'background-image': bg,
			'background-position': bg_pos,
			'background-repeat': 'no-repeat'
		});
		jQuery('body').addClass('prettyphotos');
	});

	jQuery("body.prettyphotos").live('click', function() {
		jQuery('body').removeClass('prettyphotos');
		setTimeout( function() {
			jQuery('.vh_wrapper').removeClass('blur');
		}, 100 );
	});

	jQuery(".eventrating.widget li .icon-play-1").click(function(e) {
		e.preventDefault();
		jQuery('.vh_wrapper').addClass('blur');
		var bg = jQuery('body').css('background-image');
		var bg_pos = jQuery('body').css('background-position');
		jQuery('.vh_wrapper').css({
			'background-image': bg,
			'background-position': bg_pos,
			'background-repeat': 'no-repeat'
		});
		jQuery('.eventrating.widget').parent().append('<div id="event_dialog" title="'+jQuery(this).attr('title')+'"></div>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').append('<iframe width="640" height="360" src="//www.youtube.com/embed/'+jQuery(this).parent().find('input[type=hidden]').val()+'?rel=0" frameborder="0" allowfullscreen style="display: block"></iframe>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').dialog({ 
			modal: true, 
			width: 640,
			resizable: false,
			dialogClass: "spotlight",
			position: { my: "center center", at: "center center" },
			close: function() {
				jQuery(this).dialog('destroy');
				jQuery('.vh_wrapper').removeClass('blur');
				jQuery('#event_dialog').remove();
			} 
		});
	});

	jQuery(".event_buttons .icon-play-1").live( "click", function(e) {
		e.preventDefault();
		var title = '';
		jQuery('.vh_wrapper').addClass('blur');
		var bg = jQuery('body').css('background-image');
		var bg_pos = jQuery('body').css('background-position');
		jQuery('.vh_wrapper').css({
			'background-image': bg,
			'background-position': bg_pos,
			'background-repeat': 'no-repeat'
		});
		if ( jQuery(this).parent().parent().parent().find('.page_title').html() == null ) {
			title = jQuery(this).parent().parent().parent().find('.movie_title a').html();
		} else {
			title = jQuery(this).parent().parent().parent().find('.page_title').html();
		}

		jQuery('.event_buttons').parent().append('<div id="event_dialog" title="'+title+'"></div>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').append('<iframe width="640" height="360" src="//www.youtube.com/embed/'+jQuery(this).parent().find('input[type=hidden]').val()+'?rel=0" frameborder="0" allowfullscreen style="display: block"></iframe>');
		jQuery(this).parent().parent().parent().parent().parent().parent().parent().find('#event_dialog').dialog({ 
			modal: true, 
			width: 640,
			resizable: false,
			dialogClass: "spotlight",
			position: { my: "center center", at: "center center" },
			close: function() {
				jQuery(this).dialog('destroy');
				jQuery('.vh_wrapper').removeClass('blur');
				jQuery('#event_dialog').remove();
			} 
		});
	});

	jQuery(".contact7_submit").click(function() {
		jQuery('form.wpcf7-form').submit();
	});

	jQuery('.top-menu-container .header-menu li.menu-item-has-children').mouseenter(function() {
		jQuery(this).find('ul.sub-menu').stop().slideToggle(300);
		jQuery(this).append('<div class="bottom_line"></div>');
		jQuery(this).children('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		});
		
	}).mouseleave(function() {
		jQuery(this).find('ul.sub-menu').stop().slideToggle(150);
		jQuery(this).children('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			jQuery(this).remove();
			jQuery(this).find('ul.sub-menu').finish();
		 });
	});

	jQuery('.top-menu-container .header-menu > li:not(.menu-item-has-children)').mouseenter(function() {
		jQuery(this).append('<div class="bottom_line"></div>');
		jQuery(this).children('.bottom_line').show().animate({
			width: "100%",
			left: "0%", 
		}, 300, function() {
			// Animation complete.
		 });
		
	}).mouseleave(function() {
		jQuery(this).children('.bottom_line').show().animate({
			width: "0%",
			left: "50%", 
		}, 300, function() {
			jQuery(this).remove();
		 });
	});

	jQuery(".input_parent").click(function() {
		jQuery(this).parent().submit();
	});

	jQuery('.wpb_gallery.event_open_carousel .wpb_image_grid li').addClass('shadows');

	jQuery(".event_buttons .icon-ticket").click(function(e) {
		e.preventDefault();
		jQuery('html,body').animate({scrollTop: jQuery("#event_ticket_content").offset().top-100});
	});
	
	// jQuery(".tagcloud").each(function(index){
	// 	var otags_a = jQuery(this).find("a"),
	// 	otags_number = otags_a.length,
	// 	otags_increment = 1 / otags_number,
	// 	otags_opacity = "";

	// 	jQuery(otags_a.get().reverse()).each(function(i,el) {
	// 	el.id = i + 1;
	// 	otags_opacity = el.id / otags_number - otags_increment;
	// 	if (otags_opacity < 0.2)
	// 		otags_opacity = 0.2;
	// 	jQuery(this).css({ backgroundColor: 'rgba(150,150,150,'+otags_opacity+')' });
	// 	});
	// });

	if ( jQuery(window).width() >= 767 ) {
		jQuery("a.menu-trigger").click(function() {
			jQuery(".mp-menu").css({top: jQuery(document).scrollTop() });

			return false;
		});
	}

	jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });

	jQuery(".gallery-icon a").attr('rel', 'prettyphoto');

	if (typeof prettyPhoto !== 'undefined' && $.isFunction(prettyPhoto)) {
		jQuery("a[rel^='prettyPhoto']").prettyPhoto();
	}

	jQuery(".hover_bottom_top")
	.mouseenter(function(){
		jQuery(this).animate({ bottom: "10px", opacity: "0.8" }, 300, function() {
			
		});
	}).mouseleave(function(){
		jQuery(this).animate({ bottom: "0px", opacity: "1" }, 300, function() {
			
		});
	});

	jQuery(".hover_top_to_bottom")
	.mouseenter(function(){
		jQuery(this).animate({ top: "3px", opacity: "0.8" }, 200, function() {
			
		});
	}).mouseleave(function(){
		jQuery(this).animate({ top: "0px", opacity: "1" }, 200, function() {
			
		});
	});

	jQuery(".seatera-recentpostsplus.widget .news-item").last().css({"background":"transparent", "padding":"0", "marginBottom":"0"});
	jQuery(".seatera-twitter.widget .tweet_list li").last().css({"background":"transparent", "padding":"0", "marginBottom":"0"});

	// Opacity hover effect
	jQuery(".opacity_hover").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.8" }, 80, function() {
			jQuery(social).animate({ opacity: "1.0" }, 80);
		});
	});

	var $window = $(window);
	var windowHeight = $window.height();

	$window.resize(function () {
		windowHeight = $window.height();
		jQuery(".fixed_menu .social-container").css({ 'top' : (jQuery(window).height()) - ( jQuery(".fixed_menu .social-container").height() + 60 ) });
	});

	/**
	 * jQuery.LocalScroll - Animated scrolling navigation, using anchors.
	 * Copyright (c) 2007-2009 Ariel Flesler - aflesler(at)gmail(dot)com | http://flesler.blogspot.com
	 * Dual licensed under MIT and GPL.
	 * Date: 3/11/2009
	 * @author Ariel Flesler
	 * @version 1.2.7
	 **/
	;(function($){var l=location.href.replace(/#.*/,'');var g=$.localScroll=function(a){$('body').localScroll(a)};g.defaults={duration:1e3,axis:'y',event:'click',stop:true,target:window,reset:true};g.hash=function(a){if(location.hash){a=$.extend({},g.defaults,a);a.hash=false;if(a.reset){var e=a.duration;delete a.duration;$(a.target).scrollTo(0,a);a.duration=e}i(0,location,a)}};$.fn.localScroll=function(b){b=$.extend({},g.defaults,b);return b.lazy?this.bind(b.event,function(a){var e=$([a.target,a.target.parentNode]).filter(d)[0];if(e)i(a,e,b)}):this.find('a,area').filter(d).bind(b.event,function(a){i(a,this,b)}).end().end();function d(){return!!this.href&&!!this.hash&&this.href.replace(this.hash,'')==l&&(!b.filter||$(this).is(b.filter))}};function i(a,e,b){var d=e.hash.slice(1),f=document.getElementById(d)||document.getElementsByName(d)[0];if(!f)return;if(a)a.preventDefault();var h=$(b.target);if(b.lock&&h.is(':animated')||b.onBefore&&b.onBefore.call(b,a,f,h)===false)return;if(b.stop)h.stop(true);if(b.hash){var j=f.id==d?'id':'name',k=$('<a> </a>').attr(j,d).css({position:'absolute',top:$(window).scrollTop(),left:$(window).scrollLeft()});f[j]='';$('body').prepend(k);location=e.hash;k.remove();f[j]=d}h.scrollTo(f,b).trigger('notify.serialScroll',[f])}})(jQuery);
});

function header_size() {

	jQuery(window).on('touchmove', function(event) {
		set_height();
	});
	var win    = jQuery(window),
	header     = jQuery('.header .top-header'),
	logo       = jQuery('.header .top-header .logo img'),
	elements   = jQuery('.header, .top-header .header-social-icons div a, .top-header .logo, .top-header .header_search, .header_search .search .gray-form .footer_search_input, .top-header .menu-btn.icon-menu-1'),
	el_height  = jQuery(elements).filter(':first').height(),
	isMobile   = 'ontouchstart' in document.documentElement,
	set_height = function() {
		var st = win.scrollTop(), newH = 0;

		if(st < el_height/2) {
			newH = el_height - st;
			header.removeClass('header-small');
		} else {
			newH = el_height/2;
			header.addClass('header-small');
		}

		elements.css({'height': newH + 'px', 'line-height': newH + 'px'});
		logo.css({'max-height': newH + 'px'});
	}

	if(!header.length) {
		return false;
	}

	win.scroll(set_height);
	set_height();
}

// debulked onresize handler

function on_resize(c,t){
	"use strict";

	var onresize=function(){clearTimeout(t);t=setTimeout(c,100);};return c;
}


function clearInput (input, inputValue) {
	"use strict";

	if (input.value === inputValue) {
		input.value = '';
	}
}

// function moveOffset() {
// 	if( jQuery(".full-width").length ) {
// 		var offset = jQuery(".full-width").position().left;
// 		jQuery(".full-width").css({
// 			width: jQuery('.main').width(),
// 			marginLeft: -offset
// 		});
// 	};
// };

jQuery(document).ready(function() {
	"use strict";

	// Top menu
	if( jQuery(".header .sf-menu").length ) {
		var menuOptions = {
			speed:      'fast',
			speedOut:   'fast',
			hoverClass: 'sfHover',
		}
		// initialise plugin
		var menu = jQuery('.header .sf-menu').superfish(menuOptions);
	}
	// !Top menu

	// Search widget
	jQuery('.search.widget .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search.widget form').submit();
	});
	// !Seaarch widget

	// Search widget
	jQuery('.search-no-results .main-inner .sb-icon-search').click(function(el){
		el.preventDefault();
		jQuery('.search-no-results .main-inner .search form').submit();
	});
	// !Seaarch widget
	

	// Social icons hover effect
	jQuery(".social_links li a").mouseenter(function() {
		var social = this;
		jQuery(social).animate({ opacity: "0.5" }, 250, function() {
			jQuery(social).animate({ opacity: "1.0" }, 100);
		});
	});
	// !Social icons hover effect

	// Widget contact form - send
	jQuery("#contact_form").submit(function() {
		jQuery("#contact_form").parent().find("#error, #success").hide();
		var str = jQuery(this).serialize();
		jQuery.ajax({
			type: "POST",
			url: my_ajax.ajaxurl,
			data: 'action=contact_form&' + str,
			success: function(msg) {
				if(msg === 'sent') {
					jQuery("#contact_form").parent().find("#success").fadeIn("slow");
				} else {
					jQuery("#contact_form").parent().find("#error").fadeIn("slow");
				}
			}
		});
		return false;
	});
	// !Widget contact form - send

	/* Merge gallery */
	jQuery('.merge-gallery div').mouseenter(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height()
		},250);
	}).mouseleave(function() {
		jQuery(this).find('.gallery-caption').animate({
			bottom: jQuery(this).find('img').height() + 150
		},250);
	});
});