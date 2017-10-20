
(function($) {

	skel
		.breakpoints({
			xlarge:	'(max-width: 1680px)',
			large:	'(max-width: 1280px)',
			medium:	'(max-width: 980px)',
			small:	'(max-width: 736px)',
			xsmall:	'(max-width: 480px)'
		});

	$(function() {

		var	$window = $(window),
			$body = $('body'),
			$wrapper = $('#page-wrapper'),
			$banner = $('#banner'),
			$header = $('#header');

		// Disable animations/transitions until the page has loaded.
			$body.addClass('is-loading');

			$window.on('load', function() {
				window.setTimeout(function() {
					$body.removeClass('is-loading');
				}, 100);
			});

		// Mobile?
			if (skel.vars.mobile)
				$body.addClass('is-mobile');
			else
				skel
					.on('-medium !medium', function() {
						$body.removeClass('is-mobile');
					})
					.on('+medium', function() {
						$body.addClass('is-mobile');
					});

		// Fix: Placeholder polyfill.
			$('form').placeholder();

		// Prioritize "important" elements on medium.
			skel.on('+medium -medium', function() {
				$.prioritize(
					'.important\\28 medium\\29',
					skel.breakpoint('medium').active
				);
			});

		// Scrolly.
			$('.scrolly')
				.scrolly({
					speed: 1500,
					offset: $header.outerHeight()
				});

		// Menu.
			$('#menu')
				//.append('<input type="text" class="menuSearch form-control" placeholder="Cari...">')
				.append('<a href="#menu" class="close"></a>')
				.appendTo($body)
				.panel({
					delay: 500,
					hideOnClick: true,
					hideOnSwipe: true,
					resetScroll: true,
					resetForms: true,
					side: 'right',
					target: $body,
					visibleClass: 'is-menu-visible'
				});

		// Header.
			if (skel.vars.IEVersion < 9)
				$header.removeClass('alt');

			if ($banner.length > 0
			&&	$header.hasClass('alt')) {

				$window.on('resize', function() { $window.trigger('scroll'); });

				$banner.scrollex({
					bottom:		$header.outerHeight() + 1,
					terminate:	function() { $header.removeClass('alt'); },
					enter:		function() { $header.addClass('alt'); },
					leave:		function() { $header.removeClass('alt'); }
				});

			}

	});

})(jQuery);


var main = (function($) { var _ = {

	/**
	 * Settings.
	 * @var {object}
	 */
	settings: {

		// Preload all images.
			preload: false,

		// Slide duration (must match "duration.slide" in _vars.scss).
			slideDuration: 500,

		// Layout duration (must match "duration.layout" in _vars.scss).
			layoutDuration: 750,

		// Thumbnails per "row" (must match "misc.thumbnails-per-row" in _vars.scss).
			thumbnailsPerRow: 2,

		// Side of main wrapper (must match "misc.main-side" in _vars.scss).
			mainSide: 'left'

	},

	/**
	 * Window.
	 * @var {jQuery}
	 */
	$window: null,

	/**
	 * Body.
	 * @var {jQuery}
	 */
	$body: null,

	/**
	 * Main wrapper.
	 * @var {jQuery}
	 */
	$main: null,

	/**
	 * Thumbnails.
	 * @var {jQuery}
	 */
	$thumbnails: null,

	/**
	 * Viewer.
	 * @var {jQuery}
	 */
	$viewer: null,

	/**
	 * Toggle.
	 * @var {jQuery}
	 */
	$toggle: null,

	/**
	 * Nav (next).
	 * @var {jQuery}
	 */
	$navNext: null,

	/**
	 * Nav (previous).
	 * @var {jQuery}
	 */
	$navPrevious: null,

	/**
	 * Slides.
	 * @var {array}
	 */
	slides: [],

	/**
	 * Current slide index.
	 * @var {integer}
	 */
	current: null,

	/**
	 * Lock state.
	 * @var {bool}
	 */
	locked: false,

	/**
	 * Keyboard shortcuts.
	 * @var {object}
	 */
	keys: {

		// Escape: Toggle main wrapper.
			27: function() {
				_.toggle();
			},

		// Up: Move up.
			38: function() {
				_.up();
			},

		// Down: Move down.
			40: function() {
				_.down();
			},

		// Space: Next.
			32: function() {
				_.next();
			},

		// Right Arrow: Next.
			39: function() {
				_.next();
			},

		// Left Arrow: Previous.
			37: function() {
				_.previous();
			}

	},

	/**
	 * Initialize properties.
	 */
	initProperties: function() {

		// Window, body.
			_.$window = $(window);
			_.$body = $('body');

		// Thumbnails.
			_.$thumbnails = $('#thumbnails');

		// Viewer.
			_.$viewer = $(
				'<div id="viewer">' +
					'<div class="inner">' +
						'<div class="nav-next"></div>' +
						'<div class="nav-previous"></div>' +
						'<div class="toggle"></div>' +
					'</div>' +
				'</div>'
			).appendTo(_.$body);

		// Nav.
			_.$navNext = _.$viewer.find('.nav-next');
			_.$navPrevious = _.$viewer.find('.nav-previous');

		// Main wrapper.
			_.$main = $('#m-thumb');

		// Toggle.
			$('<div class="toggle"></div>')
				.appendTo(_.$main);

			_.$toggle = $('.toggle');

		// IE<9: Fix viewer width (no calc support).
			if (skel.vars.IEVersion < 9)
				_.$window
					.on('resize', function() {
						window.setTimeout(function() {
							_.$viewer.css('width', _.$window.width() - _.$main.width());
						}, 100);
					})
					.trigger('resize');

	},

	/**
	 * Initialize events.
	 */
	initEvents: function() {

		// Window.

			// Remove is-loading-* classes on load.
				_.$window.on('load', function() {

					_.$body.removeClass('is-loading-0');

					window.setTimeout(function() {
						_.$body.removeClass('is-loading-1');
					}, 100);

					window.setTimeout(function() {
						_.$body.removeClass('is-loading-2');
					}, 100 + Math.max(_.settings.layoutDuration - 150, 0));

				});

			// Disable animations/transitions on resize.
				var resizeTimeout;

				_.$window.on('resize', function() {

					_.$body.addClass('is-loading-0');
					window.clearTimeout(resizeTimeout);

					resizeTimeout = window.setTimeout(function() {
						_.$body.removeClass('is-loading-0');
					}, 100);

				});

		// Viewer.

			// Hide main wrapper on tap (<= medium only).
				_.$viewer.on('touchend', function() {

					if (skel.breakpoint('medium').active)
						_.hide();

				});

			// Touch gestures.
				_.$viewer
					.on('touchstart', function(event) {

						// Record start position.
							_.$viewer.touchPosX = event.originalEvent.touches[0].pageX;
							_.$viewer.touchPosY = event.originalEvent.touches[0].pageY;

					})
					.on('touchmove', function(event) {

						// No start position recorded? Bail.
							if (_.$viewer.touchPosX === null
							||	_.$viewer.touchPosY === null)
								return;

						// Calculate stuff.
							var	diffX = _.$viewer.touchPosX - event.originalEvent.touches[0].pageX,
								diffY = _.$viewer.touchPosY - event.originalEvent.touches[0].pageY;
								boundary = 20,
								delta = 50;

						// Swipe left (next).
							if ( (diffY < boundary && diffY > (-1 * boundary)) && (diffX > delta) )
								_.next();

						// Swipe right (previous).
							else if ( (diffY < boundary && diffY > (-1 * boundary)) && (diffX < (-1 * delta)) )
								_.previous();

						// Overscroll fix.
							var	th = _.$viewer.outerHeight(),
								ts = (_.$viewer.get(0).scrollHeight - _.$viewer.scrollTop());

							if ((_.$viewer.scrollTop() <= 0 && diffY < 0)
							|| (ts > (th - 2) && ts < (th + 2) && diffY > 0)) {

								event.preventDefault();
								event.stopPropagation();

							}

					});

		// Main.

			// Touch gestures.
				_.$main
					.on('touchstart', function(event) {

						// Bail on xsmall.
							if (skel.breakpoint('xsmall').active)
								return;

						// Record start position.
							_.$main.touchPosX = event.originalEvent.touches[0].pageX;
							_.$main.touchPosY = event.originalEvent.touches[0].pageY;

					})
					.on('touchmove', function(event) {

						// Bail on xsmall.
							if (skel.breakpoint('xsmall').active)
								return;

						// No start position recorded? Bail.
							if (_.$main.touchPosX === null
							||	_.$main.touchPosY === null)
								return;

						// Calculate stuff.
							var	diffX = _.$main.touchPosX - event.originalEvent.touches[0].pageX,
								diffY = _.$main.touchPosY - event.originalEvent.touches[0].pageY;
								boundary = 20,
								delta = 50,
								result = false;

						// Swipe to close.
							switch (_.settings.mainSide) {

								case 'left':
									result = (diffY < boundary && diffY > (-1 * boundary)) && (diffX > delta);
									break;

								case 'right':
									result = (diffY < boundary && diffY > (-1 * boundary)) && (diffX < (-1 * delta));
									break;

								default:
									break;

							}

							if (result)
								_.hide();

						// Overscroll fix.
							var	th = _.$main.outerHeight(),
								ts = (_.$main.get(0).scrollHeight - _.$main.scrollTop());

							if ((_.$main.scrollTop() <= 0 && diffY < 0)
							|| (ts > (th - 2) && ts < (th + 2) && diffY > 0)) {

								event.preventDefault();
								event.stopPropagation();

							}

					});
		// Toggle.
			_.$toggle.on('click', function() {
				_.toggle();
			});

			// Prevent event from bubbling up to "hide event on tap" event.
				_.$toggle.on('touchend', function(event) {
					event.stopPropagation();
				});

		// Nav.
			_.$navNext.on('click', function() {
				_.next();
			});

			_.$navPrevious.on('click', function() {
				_.previous();
			});

		// Keyboard shortcuts.

			// Ignore shortcuts within form elements.
				_.$body.on('keydown', 'input,select,textarea', function(event) {
					event.stopPropagation();
				});

			_.$window.on('keydown', function(event) {

				// Ignore if xsmall is active.
					if (skel.breakpoint('xsmall').active)
						return;

				// Check keycode.
					if (event.keyCode in _.keys) {

						// Stop other events.
							event.stopPropagation();
							event.preventDefault();

						// Call shortcut.
							(_.keys[event.keyCode])();

					}

			});

	},

	/**
	 * Initialize viewer.
	 */
	initViewer: function() {

		// Bind thumbnail click event.
			_.$thumbnails
				.on('click', '.thumbnail', function(event) {

					var $this = $(this);

					// Stop other events.
						event.preventDefault();
						event.stopPropagation();

					// Locked? Blur.
						if (_.locked)
							$this.blur();

					// Switch to this thumbnail's slide.
						_.switchTo($this.data('index'));

				});

		// Create slides from thumbnails.
			_.$thumbnails.children()
				.each(function() {

					var	$this = $(this),
						$thumbnail = $this.children('.thumbnail'),
						s;

					// Slide object.
						s = {
							$parent: $this,
							$slide: null,
							$slideImage: null,
							$slideCaption: null,
							url: $thumbnail.attr('href'),
							loaded: false
						};

					// Parent.
						$this.attr('tabIndex', '-1');

					// Slide.

						// Create elements.
	 						s.$slide = $('<div class="slide"><div class="caption"></div><div class="image"></div></div>');

	 					// Image.
 							s.$slideImage = s.$slide.children('.image');

 							// Set background stuff.
	 							s.$slideImage
		 							.css('background-image', '')
		 							.css('background-position', ($thumbnail.data('position') || 'center'));

						// Caption.
							s.$slideCaption = s.$slide.find('.caption');

							// Move everything *except* the thumbnail itself to the caption.
								$this.children().not($thumbnail)
									.appendTo(s.$slideCaption);

					// Preload?
						if (_.settings.preload) {

							// Force image to download.
								var $img = $('<img src="' + s.url + '" />');

							// Set slide's background image to it.
								s.$slideImage
									.css('background-image', 'url(' + s.url + ')');

							// Mark slide as loaded.
								s.$slide.addClass('loaded');
								s.loaded = true;

						}

					// Add to slides array.
						_.slides.push(s);

					// Set thumbnail's index.
						$thumbnail.data('index', _.slides.length - 1);

				});

	},

	/**
	 * Initialize stuff.
	 */
	init: function() {

		// IE<10: Zero out transition delays.
			if (skel.vars.IEVersion < 10) {

				_.settings.slideDuration = 0;
				_.settings.layoutDuration = 0;

			}

		// Skel.
			skel.breakpoints({
				xlarge: '(max-width: 1680px)',
				large: '(max-width: 1280px)',
				medium: '(max-width: 980px)',
				small: '(max-width: 736px)',
				xsmall: '(max-width: 480px)'
			});

		// Everything else.
			_.initProperties();
			_.initViewer();
			_.initEvents();

		// Initial slide.
			window.setTimeout(function() {

				// Show first slide if xsmall isn't active or it just deactivated.
					skel.on('-xsmall !xsmall', function() {

						if (_.current === null)
							_.switchTo(0, true);

					});

			}, 0);

	},

	/**
	 * Switch to a specific slide.
	 * @param {integer} index Index.
	 */
	switchTo: function(index, noHide) {

		// Already at index and xsmall isn't active? Bail.
			if (_.current == index
			&&	!skel.breakpoint('xsmall').active)
				return;

		// Locked? Bail.
			if (_.locked)
				return;

		// Lock.
			_.locked = true;

		// Hide main wrapper if medium is active.
			if (!noHide
			&&	skel.breakpoint('medium').active
			&&	skel.vars.IEVersion > 8)
				_.hide();

		// Get slides.
			var	oldSlide = (_.current !== null ? _.slides[_.current] : null),
				newSlide = _.slides[index];

		// Update current.
			_.current = index;

		// Deactivate old slide (if there is one).
			if (oldSlide) {

				// Thumbnail.
					oldSlide.$parent
						.removeClass('active');

				// Slide.
					oldSlide.$slide.removeClass('active');

			}

		// Activate new slide.

			// Thumbnail.
				newSlide.$parent
					.addClass('active')
					.focus();

			// Slide.
				var f = function() {

					// Old slide exists? Detach it.
						if (oldSlide)
							oldSlide.$slide.detach();

					// Attach new slide.
						newSlide.$slide.appendTo(_.$viewer);

					// New slide not yet loaded?
						if (!newSlide.loaded) {

							window.setTimeout(function() {

								// Mark as loading.
									newSlide.$slide.addClass('loading');

								// Wait for it to load.
									$('<img src="' + newSlide.url + '" />').on('load', function() {
									//window.setTimeout(function() {

										// Set background image.
											newSlide.$slideImage
												.css('background-image', 'url(' + newSlide.url + ')');

										// Mark as loaded.
											newSlide.loaded = true;
											newSlide.$slide.removeClass('loading');

										// Mark as active.
											newSlide.$slide.addClass('active');

										// Unlock.
											window.setTimeout(function() {
												_.locked = false;
											}, 100);

									//}, 1000);
									});

							}, 100);

						}

					// Otherwise ...
						else {

							window.setTimeout(function() {

								// Mark as active.
									newSlide.$slide.addClass('active');

								// Unlock.
									window.setTimeout(function() {
										_.locked = false;
									}, 100);

							}, 100);

						}

				};

				// No old slide? Switch immediately.
					if (!oldSlide)
						(f)();

				// Otherwise, wait for old slide to disappear first.
					else
						window.setTimeout(f, _.settings.slideDuration);

	},

	/**
	 * Switches to the next slide.
	 */
	next: function() {

		// Calculate new index.
			var i, c = _.current, l = _.slides.length;

			if (c >= l - 1)
				i = 0;
			else
				i = c + 1;

		// Switch.
			_.switchTo(i);

	},

	/**
	 * Switches to the previous slide.
	 */
	previous: function() {

		// Calculate new index.
			var i, c = _.current, l = _.slides.length;

			if (c <= 0)
				i = l - 1;
			else
				i = c - 1;

		// Switch.
			_.switchTo(i);

	},

	/**
	 * Switches to slide "above" current.
	 */
	up: function() {

		// Fullscreen? Bail.
			if (_.$body.hasClass('fullscreen'))
				return;

		// Calculate new index.
			var i, c = _.current, l = _.slides.length, tpr = _.settings.thumbnailsPerRow;

			if (c <= (tpr - 1))
				i = l - (tpr - 1 - c) - 1;
			else
				i = c - tpr;

		// Switch.
			_.switchTo(i);

	},

	/**
	 * Switches to slide "below" current.
	 */
	down: function() {

		// Fullscreen? Bail.
			if (_.$body.hasClass('fullscreen'))
				return;

		// Calculate new index.
			var i, c = _.current, l = _.slides.length, tpr = _.settings.thumbnailsPerRow;

			if (c >= l - tpr)
				i = c - l + tpr;
			else
				i = c + tpr;

		// Switch.
			_.switchTo(i);

	},

	/**
	 * Shows the main wrapper.
	 */
	show: function() {

		// Already visible? Bail.
			if (!_.$body.hasClass('fullscreen'))
				return;

		// Show main wrapper.
			_.$body.removeClass('fullscreen');

		// Focus.
			_.$main.focus();

	},

	/**
	 * Hides the main wrapper.
	 */
	hide: function() {

		// Already hidden? Bail.
			if (_.$body.hasClass('fullscreen'))
				return;

		// Hide main wrapper.
			_.$body.addClass('fullscreen');

		// Blur.
			_.$main.blur();

	},

	/**
	 * Toggles main wrapper.
	 */
	toggle: function() {

		if (_.$body.hasClass('fullscreen'))
			_.show();
		else
			_.hide();

	},

}; return _; })(jQuery); main.init();


$(document).ready( function(){

	//this variable represents the total number of popups can be displayed according to the viewport width
	var total_popups = 0;

	//arrays of popups ids
	var popups = [];

	$('.chat_head').click(function(){
		//$(this).parent().filter('.chat_body').slideToggle('slow');
		$('.chat_body').slideToggle('slow');
	});
	//$('.msg_head').parent().attr('id').click(function(){
	$('.msg_head').click(function(){
		$(this).parent().find('.msg_wrap').slideToggle('slow');
		//$('.msg_wrap').slideToggle('slow');
	});

	$('.closec').click(function(){
		var id = $(this).parent().parent().attr('id');
		$(this).parent().parent().hide();
		// remove the index
		//popups.splice(popups.indexOf(id), 1);
		// display popups
		var right = 270;
		for (var idx=0;idx<total_popups;idx++){
			if (popups[idx] == id){
				if (idx==total_popups){
					popups.splice(idx, 1);
					$('.msg_box').filter('#'+id).css({'right':''});
					total_popups--;
				} else {
					total_popups--;
					//popups.splice(idx, 1);
					$('.msg_box').filter('#'+popups[idx]).css({'display':'none'});
					$('.msg_box').filter('#'+popups[idx]).css({'right':''});
					console.log("slice index["+idx+"]: "+popups[idx]);
					//Array.remove(popups,idx);
					for (var idt=idx;idt<total_popups+1;idt++){
						if ((idt+1)==total_popups+1){
							break;
						//} else if (total_popups ==0){
						//	popups[idt] = popups[idt+1];
						//	$('.msg_box').filter('#'+popups[idt]).css({'right':10});
						} else {
							popups[idt] = popups[idt+1];
							$('.msg_box').filter('#'+popups[idt]).css({'right':(right)*(idt+1)});
							//$('.msg_box').filter('#'+popups[idt]).css({'right':(right)*(idt)});
						}
					}
					//total_popups--;
					console.log("total_popups: "+total_popups);
					break;
				}
			} else {
				console.log("popups["+idx+"]: "+popups[idx]);
			}
		}

		//setRight();
	});

	$('div.user,a.user').click(function(){
		var id = $(this).attr('id');
		// show popup
		$('.msg_box').filter('#'+id).show();
		$('.msg_box').filter('#'+id).find('.msg_wrap').show();

		// add popup index
		popups[total_popups] = id;
		// add style popup
		var right = 270;
		var found = false;
		for (var idx=0;idx<total_popups;idx++){
			if (popups[idx] == id){
				found =true;
				console.log("popups["+idx+"]: "+popups[idx]);
			}
		}
		if (found==false){
			//if (total_popups==0){
			//	$('.msg_box').filter('#'+id).css({'right':10});
			//} else if (total_popups==1){
			//	$('.msg_box').filter('#'+id).css({'right':(right+5)*(total_popups+1)});
			//} else {
				//$('.msg_box').filter('#'+id).css({'right':(right)*(total_popups)});
				$('.msg_box').filter('#'+id).css({'right':(right)*(total_popups+1)});
			//}
			console.log("add index["+total_popups+"]: "+id);
			total_popups++;
		}

	});


	$('textarea.msg_input').keypress(function(e){
		var id = $(this).parent().parent().parent().attr('id');
		var extra = $(this).parent().parent().parent().attr('data-extra');

    
    if (e.keyCode == 13) {
        e.preventDefault();
        
        var msg = $(this).val();
				$(this).val('');
				if(msg!='') {
					if (extra == ''){
						$.ajax({
  						type: 'post',
  						url: 'messages.php',
  						data: {
   							user_to:res[0],
   							user_id:res[1],
								message:msg,
  						},
  						success: function (response) {
   							// We get the element having id of display_info and put the response inside it
								$('<div class="msg_b">'+msg+'</div>').insertBefore("#"+id+".msg_push");
   						},
							error: function(error) {
                                console.log(status + ": " + error);
            	            }
  					    });
					} else {
						$.ajax({
  						type: 'post',
  						url: 'messages.php',
  						data: {
   							user_to:id,
   							user_id:id,
								data:extra,
								message:msg,
  						},
  						success: function (response) {
   							// We get the element having id of display_info and put the response inside it
								$('<div class="msg_b">'+msg+'</div>').insertBefore("#"+id+".msg_push");
   						},
							error: function(error) {
                console.log(status + ": " + error);
            	}
  					});
					}
				}
					//$('<div class="msg_b">'+msg+'</div>').insertBefore('.msg_push');
			$(this).parent().parent().find(".msg_body").scrollTop($(this).parent().parent().find(".msg_body")[0].scrollHeight);
    
						    
	}
  });

/*
	//this function can remove a array element.
	Array.remove = function(array, from, to) {
	  var rest = array.slice((to || from) + 1 || array.length);
	  array.length = from < 0 ? array.length + from : from;
	  return array.push.apply(array, rest);
	};

	//this variable represents the total number of popups can be displayed according to the viewport width
	var total_popups = 0;

	//arrays of popups ids
	var popups = [];

	//this is used to close a popup
	function close_popup(id) {
	  for(var iii = 0; iii < popups.length; iii++) {
      if(id == popups[iii]) {
	      Array.remove(popups, iii);

	      document.getElementById(id).style.display = "none";
	      calculate_popups();
	      return;
	    }
	  }
	}

	//displays the popups. Displays based on the maximum number of popups that can be displayed on the current viewport width
	function display_popups() {
	  var right = 220;
	  var iii = 0;

		for(iii; iii < total_popups; iii++) {
	    if(popups[iii] != undefined) {
	      var element = document.getElementById(popups[iii]);
	      element.style.right = right + "px";
	      right = right + 320;
	      element.style.display = "block";
	    }
	  }

	  for(var jjj = iii; jjj < popups.length; jjj++) {
    	var element = document.getElementById(popups[jjj]);
	    element.style.display = "none";
	  }
	}

	//creates markup for a new popup. Adds the id to popups array.
	function register_popup(id, name) {

  	for(var iii = 0; iii < popups.length; iii++) {
      //already registered. Bring it to front.
      if(id == popups[iii]) {
        Array.remove(popups, iii);

        popups.unshift(id);
        calculate_popups();
        return;
      }
    }

    var element = '<div class="msg-box chat-popup" id="'+ id +'">';
        element = element + '<div class="msg-head">'+ name +;
        element = element + '<div class="close">x</div></div>';

    document.getElementsByTagName("body")[0].innerHTML = document.getElementsByTagName("body")[0].innerHTML + element;

    popups.unshift(id);
    calculate_popups();

  }

  //calculate the total number of popups suitable and then populate the toatal_popups variable.
  function calculate_popups() {
  	var width = window.innerWidth;
	  if(width < 540) {
      total_popups = 0;
    } else {
	    width = width - 200;
	    //320 is width of a single popup box
	    total_popups = parseInt(width/320);
	  }

	  display_popups();
  }

  //recalculate when window is loaded and also when window is resized.
  window.addEventListener("resize", calculate_popups);
  window.addEventListener("load", calculate_popups);
*/

  $('.wo-slider').bxSlider({
    slideWidth: 500,
    slideHeight: 300,
    minSlides: 1,
    maxSlides: 2,
    moveSlides: 1,
    slideMargin: 10
  });

	$('[data-toggle="tooltip"]').tooltip();

	// dataTable
	$('#tbl-pckg').DataTable();
	$('#tbl-fasilitas').DataTable();
	$('#tbl-adwo').DataTable();
	$('#tbl-adclient').DataTable();

  $("#stat-pkt").change(function() {
		$( "select option:selected" ).each(function() {
    	$("#in-pkt").val($(this).attr("value"));
		});
  });
	// jeditable
	/*
	$("#wo_aprv").editable("echo.php", {
        type      : "select",
        data      : "{'0':'Waiting', '1':'Approved'}",
        tooltip   : "Tooltip",
        submit    : "OK",
        event     : "click"
  });
	*/
	/*
	var wod_aprv = {'0':'Waiting','1':'Approved'};
	$('#wo_aprv').editable(function(value, settings) {
    return value;
	}, {
    type: 'select',
    wod_aprv: wod_aprv,
    callback: function(value, settings) {
        $(this).html(wod_aprv[value]);
    },
    submit: 'ok'
	});
	*/
	/*
	$(".wo_aprv").editable(function(value, settings) {//("yoursave.php", {
		return value;
	}, {

	  indicator : '<img src="img/ajax-loader.gif">',
    type   		: 'select',
    data 			: '{"Waiting":"Waiting","Approved":"Approved"}',
    tooltip   : 'Click to edit...',
    callback: function(value, settings) {
        $(this).html(jQuery.parseJSON(settings.data)[value]);
    },
    submit		: 'Ok'
	});
	*/
	$(".wo_aprv").editable("update-data.php",{
		indicator : '<img src="assets/img/ajax-loader.gif">',
    type   		: 'select',
    data 			: '{"Waiting":"Waiting","Approved":"Approved"}',
    tooltip   : 'Click to edit...',
    submit		: 'Ok'
	});

	$(document).on('click', '.browseb', function(){
		var file = $(this).parent().parent().parent().find('.fileb');
  	//var file = $("#payment");
  	file.trigger('click');
	});
	$(document).on('change', '.fileb', function(){
  	$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
		//$("#pay_name").val($(this).val().replace(/C:\\fakepath\\/i, ''));
		//$("#pay_name").val($(this).val());
	});

	$(document).on('click', '.browse', function(){
		//var file = $(this).parent().parent().parent().find('.file');
  	var file = $("#payment");
  	file.trigger('click');
	});
	$(document).on('change', '.file', function(){
  	//$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
		//$("#pay_name").val($(this).val().replace(/C:\\fakepath\\/i, ''));
		$("#pay_name").val($(this).val());
	});

	// --- CONTROL
	$('#mdl_change').on('show.bs.modal', function (event) {
		var button = $(event.relatedTarget) // Button that triggered the modal
		var title = button.data('title') // Extract info from data-* attributes
		var change = button.data('change') // Extract info from data-* attributes
		var modal = $(this)
		modal.find('.modal-title').text("Change " + title)
		$("#pro-set").val(title)
		$("#pro-ctrl").val(change)
		$("#pro-ctrl").text(change)
		//$("#pro-ctrl").attr("placeholder", change)

		$("#submit-ctrl").show();
		$("#mdl_change .modal-footer #close-ctrl").text("Cancel")
	});
	$("#submit-ctrl").on('click', function() {
    $("#pro-form").submit();
  });
	$("#pro-form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");

				if (postData[1].value != ""){
	        $.ajax({
            url: "update-data.php",
            type: "POST",
            data: postData,
            success: function(data, textStatus, jqXHR) {
                $('#mdl_change .modal-header .modal-title').html("Success!");
								//location.reload();
								if (postData[0].value == "Nama"){
									$('#Nama').text(postData[1].value);
									$('#btn-nama').data('change', postData[1].value);
									$('#btn-nama').attr('data-change', postData[1].value);
								} else if (postData[0].value == "Nama owner"){
									$('#Owner').text(postData[1].value);
									$('#btn-owner').data('change', postData[1].value);
									$('#btn-owner').attr('data-change', postData[1].value);
								} else if (postData[0].value == "Email"){
									$('#OwnerEmail').text(postData[1].value);
									$('#btn-email').data('change', postData[1].value);
									$('#btn-email').attr('data-change', postData[1].value);
								} else if (postData[0].value == "Telepon"){
									$('#Phone').text(postData[1].value);
									$('#btn-phone').data('change', postData[1].value);
									$('#btn-phone').attr('data-change', postData[1].value);
								}
                //$('#mdl_change .modal-body').html(data);
                $("#submit-ctrl").hide();
								$("#mdl_change .modal-footer #close-ctrl").text("Close")
            },
            error: function(jqXHR, status, error) {
                console.log(status + ": " + error);
            }
        	});
				} else {
						$('#mdl_change .modal-header .modal-title').html("Please fill the box!");
				}
        e.preventDefault();
  });

	// --- ADDR
	$('#mdl_address').on('show.bs.modal', function (event) {
			var button = $(event.relatedTarget) // Button that triggered the modal
			var title = button.data('title') // Extract info from data-* attributes
			var change = button.data('change') // Extract info from data-* attributes
			var modal = $(this)
			modal.find('.modal-title').text("Change " + title)
			$("#addr-set").val(title)
			$("#addr-ctrl").val(change)
			//$("#pro-ctrl").attr("placeholder", change)

			$("#submit-addr").show();
			$("#mdl_address .modal-footer #close-addr").text("Cancel")
	});
	$("#submit-addr").on('click', function() {
	    $("#addr-form").submit();
	});
	$("#addr-form").on("submit", function(e) {
	    var postData = $(this).serializeArray();
	    var formURL = $(this).attr("action");

			if (postData[1].value != ""){
	      	$.ajax({
	          	url: "update-data.php",
	            type: "POST",
	            data: postData,
	            success: function(data, textStatus, jqXHR) {
	                $('#mdl_address .modal-header .modal-title').html("Success!");
									//location.reload();
									$('#Address').text(postData[1].value);
									$('#btn-addr').data('change', postData[1].value);
									$('#btn-addr').attr('data-change', postData[1].value);
	                //$('#mdl_change .modal-body').html(data);
	                $("#submit-addr").hide();
									$("#mdl_address .modal-footer #close-addr").text("Close")
	            },
	            error: function(jqXHR, status, error) {
	                console.log(status + ": " + error);
	            }
        	});
			} else {
					$('#mdl_address .modal-header .modal-title').html("Please fill the box!");
			}
      e.preventDefault();
  });


	// --- PAKET
	$('#mdl_pckg').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Button that triggered the modal
				var title = button.data('title'); // Extract info from data-* attributes
				var paket = button.data('paket'); // Extract info from data-* attributes
				var name = button.data('name'); // Extract info from data-* attributes
				var cpct = button.data('cpct'); // Extract info from data-* attributes
				var price = button.data('price'); // Extract info from data-* attributes
				var modal = $(this);

				modal.find('.modal-title').text(title);

				$("#submit-pckg").show();
				$("#mdl_pckg .modal-footer #close-pckg").text("Cancel");
	});
	$("#submit-pckg").on('click', function() {
	    	$("#pckg-form").submit();
  });
	$("#pckg-form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");

				if (postData[1].value != ""){
		      	$.ajax({
		          	url: "update-data.php",
		            type: "POST",
		            data: postData,
		            success: function(data, textStatus, jqXHR) {
		                $('#mdl_pckg .modal-header .modal-title').html("Success!");
		                $("#submit-pckg").hide();
										$("#mdl_pckg .modal-footer #close-pckg").text("Close");
		            },
		            error: function(jqXHR, status, error) {
		                console.log(status + ": " + error);
		            }
		      	});
				} else {
						$('#mdl_pckg .modal-header .modal-title').html("Please fill the box!");
				}
		    e.preventDefault();
	});

	$('#mdl_pupdate').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var title = button.data('title'); // Extract info from data-* attributes
				var oid = button.data('oid'); // Extract info from data-* attributes
				var oname = button.data('oname'); // Extract info from data-* attributes
				var name = button.data('name'); // Extract info from data-* attributes
				var cpct = button.data('cpct'); // Extract info from data-* attributes
				var price = button.data('price'); // Extract info from data-* attributes
				var modal = $(this);

				modal.find('.modal-title').text(title)
					$("#pupdate-oname").val(oname);
					$("#pupdate-oid").val(oid);
					$("#pupdate-name").val(name);
					$("#pupdate-cpct").val(cpct);
					$("#pupdate-price").val(price);

				$("#submit-pupdate").show();
				$("#close-pupdate").text("Cancel");
	});
	$("#submit-pupdate").on('click', function() {
				$("#pupdate-form").submit();
	});
	$("#pupdate-form").on("submit", function(e) {
				var postData = $(this).serializeArray();
				var formURL = $(this).attr("action");

				if (postData[1].value != ""){
						$.ajax({
								url: "update-data.php",
								type: "POST",
								data: postData,
								success: function(data, textStatus, jqXHR) {
										$('#mdl_pupdate .modal-header .modal-title').html("Success!");
										$("#submit-pupdate").hide();
										$("#close-pupdate").text("Close")
								},
								error: function(jqXHR, status, error) {
										console.log(status + ": " + error);
								}
						});
				} else {
						$('#mdl_pupdate .modal-header .modal-title').html("Please fill the box!");
				}
				e.preventDefault();
	});


	// --- FASILITAS
	$('#mdl_fasilitas').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var title = button.data('title'); // Extract info from data-* attributes
				var paket = button.data('paket'); // Extract info from data-* attributes
				var tipe = button.data('tipe'); // Extract info from data-* attributes
				var item = button.data('item'); // Extract info from data-* attributes
				var total = button.data('total'); // Extract info from data-* attributes
				var modal = $(this);

				modal.find('.modal-title').text(title);

				$("#submit-fasilitas").show();
				$("#close-fasilitas").text("Cancel");
	});
	$("#submit-fasilitas").on('click', function() {
	    	$("#fasilitas-form").submit();
  });
	$("#fasilitas-form").on("submit", function(e) {
        var postData = $(this).serializeArray();
        var formURL = $(this).attr("action");

				if (postData[1].value != ""){
		      	$.ajax({
		          	url: "update-data.php",
		            type: "POST",
		            data: postData,
		            success: function(data, textStatus, jqXHR) {
		                $('#mdl_fasilitas .modal-header .modal-title').html("Success!");
										//location.reload();
											//$('#Address').text(postData[1].value);
											//$('#btn-addr').data('change', postData[1].value);
											//$('#btn-addr').attr('data-change', postData[1].value);
		                //$('#mdl_change .modal-body').html(data);
		                $("#submit-fasilitas").hide();
										$("#close-fasilitas").text("Close");
		            },
		            error: function(jqXHR, status, error) {
		                console.log(status + ": " + error);
		            }
		      	});
				} else {
						$('#mdl_fasilitas .modal-header .modal-title').html("Please fill the box!");
				}
		    e.preventDefault();
	});

	$('#mdl_fasilitasu').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var title = button.data('title'); // Extract info from data-* attributes
				var paket = button.data('paket'); // Extract info from data-* attributes
				var tipe = button.data('tipe'); // Extract info from data-* attributes
				var item = button.data('item'); // Extract info from data-* attributes
				var total = button.data('total'); // Extract info from data-* attributes
				var modal = $(this);

				modal.find('.modal-title').text(title);
					$("#fasilitasu-paket").val(paket);
						$("#fasilitasu-paketo").val(paket);
					$("#fasilitasu-item").val(item);
						$("#fasilitasu-itemo").val(item);
					$("#fasilitasu-tipe").val(tipe);
					$("#fasilitasu-total").val(total);
				//$("#addr-name").val('')
				//$("#addr-price").val('')
				//$("#pro-cpct").val("")

				$("#submit-fasilitasu").show();
				$("#close-fasilitasu").text("Cancel");
	});
	$("#submit-fasilitasu").on('click', function() {
				$("#fasilitasu-form").submit();
	});
	$("#fasilitasu-form").on("submit", function(e) {
				var postData = $(this).serializeArray();
				var formURL = $(this).attr("action");

				if (postData[1].value != ""){
						$.ajax({
								url: "update-data.php",
								type: "POST",
								data: postData,
								success: function(data, textStatus, jqXHR) {
										$('#mdl_fasilitasu .modal-header .modal-title').html("Success!");
										//location.reload();
											//$('#Address').text(postData[1].value);
											//$('#btn-addr').data('change', postData[1].value);
											//$('#btn-addr').attr('data-change', postData[1].value);
										//$('#mdl_change .modal-body').html(data);
										$("#submit-fasilitasu").hide();
										$("#mdl_fasilitasu .modal-footer #close-fasilitasu").text("Close");
								},
								error: function(jqXHR, status, error) {
										console.log(status + ": " + error);
								}
						});
				} else {
						$('#mdl_fasilitasu .modal-header .modal-title').html("Please fill the box!");
				}
				e.preventDefault();
	});

	$('#mdl_ont').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget); // Button that triggered the modal
				var title = button.data('title'); // Extract info from data-* attributes
				//var paket = button.data('paket'); // Extract info from data-* attributes
				//var tipe = button.data('tipe'); // Extract info from data-* attributes
				//var item = button.data('item'); // Extract info from data-* attributes
				//var total = button.data('total'); // Extract info from data-* attributes
				var modal = $(this);

				modal.find('.modal-title').text(title);
					//$("#fasilitasu-paket").val(paket);
						//$("#fasilitasu-paketo").val(paket);
					//$("#fasilitasu-item").val(item);
						//$("#fasilitasu-itemo").val(item);
					//$("#fasilitasu-tipe").val(tipe);
					//$("#fasilitasu-total").val(total);
				//$("#addr-name").val('')
				//$("#addr-price").val('')
				//$("#pro-cpct").val("")

				$("#submit-ont").show();
				$("#close-ont").text("Cancel");
	});
	$("#submit-ont").on('click', function() {
				//$("#ont-form").submit();
	});
	$("#ont-form").on("submit", function(e) {
				var postData = $(this).serializeArray();
				var formURL = $(this).attr("action");

				if (postData[1].value != ""){
						$.ajax({
								url: "update-data.php",
								type: "POST",
								data: postData,
								success: function(data, textStatus, jqXHR) {
										$('#mdl_ont .modal-header .modal-title').html("Success!");
										//location.reload();
											//$('#Address').text(postData[1].value);
											//$('#btn-addr').data('change', postData[1].value);
											//$('#btn-addr').attr('data-change', postData[1].value);
										//$('#mdl_change .modal-body').html(data);
										$("#submit-ont").hide();
										$("#mdl_ont .modal-footer #close-fasilitasu").text("Close");
								},
								error: function(jqXHR, status, error) {
										console.log(status + ": " + error);
								}
						});
				} else {
						$('#mdl_ont .modal-header .modal-title').html("Please fill the box!");
				}
				e.preventDefault();
	});


	// --- CONFIRM
	$('#mdl_confirm').on('show.bs.modal', function (event) {
				var button = $(event.relatedTarget) // Button that triggered the modal
				var title = button.data('title') // Extract info from data-* attributes
				var msg = button.data('msg') // Extract info from data-* attributes
				var href = button.data('href') // Extract info from data-* attributes
				var modal = $(this)

				modal.find('.modal-title').text(title)
				modal.find('.modal-body p').text(msg)
				modal.find('.modal-footer a').attr('href',href)
				//$("#addr-name").val('')
				//$("#addr-price").val('')
				//$("#pro-cpct").val("")

				//$("#submit-fasilitas").show();
				//$("#mdl_fasilitas .modal-footer #close-fasilitas").text("Cancel")
	});

});
