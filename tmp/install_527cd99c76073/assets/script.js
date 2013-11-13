/*
jQuery Orbit Plugin 1.0
www.ZURB.com/playground
Copyright 2010
*/

/*Coming Features List

1. Bullet Navigation (with this also autoadvance without timer)
2. Theme Classes (white, black, etc.)
3. Caption parameter for not just title, but whatever you want
4. Continuous parameter
5. Allow for image linking

*/

(function($) {

    $.fn.orbit = function(options) {

        //Yo' defaults
        var defaults = {  
            animation: 'fade', //fade, horizontal-slide, vertical-slide
            animationSpeed: 800, //how fast animtions are
            advanceSpeed: 4000, //if auto advance is enabled, time between transitions 
            startClockOnMouseOut: true, //if clock should start on MouseOut
            startClockOnMouseOutAfter: 3000, //how long after mouseout timer should start again
            directionalNav: true, //manual advancing directional navs
            captions: true,
            captionAnimationSpeed: 800,
            timer: false,
			hovercheck:false,
			timercheck:false,
			timerRunning:true,
			idDiv:0,
			preImg:'',
			nexImg:'',
			readmoreImg:'',
			moduleId:0,
			heightImg:0
            };  
        
        //Extend those options
        var options = $.extend(defaults, options); 

        return this.each(function() {
        
            //important global goodies
            var activeImage = 0;
            var numberImages = 0;
            var orbitWidth;
            var orbitHeight;
            var locked;
            
            //Grab each Shifter and add the class
            var orbit = $(this).addClass('orbit')
            //var PreNex = orbit.parent().children('div.slider-nav');
            //Collect all images and set slider size to biggest o' da images
            var images = orbit.children('img');
			orbitWidth = orbit.width();
			orbitHeight = orbit.height();
            images.each(function() {				
                var _img = $(this);
                var _imgWidth = _img.width();
                var _imgHeight = _img.height();
				
                if(_imgWidth > orbit.width()) {
                    orbit.width(_imgWidth);
                    orbitWidth = orbit.width()
                }
                if(_imgHeight > orbit.height()) {
                    orbit.height(_imgHeight)
                    orbitHeight = orbit.height();
                }
                numberImages++;
            });
			
			
            
            //set initial front photo z-index
            images.eq(activeImage).css({"z-index" : 3});
            
            //Timer info
            if(options.timer) {
                var timerHTML = '<div class="timer"><span class="pause"></span></div>'
                orbit.append(timerHTML);
                var timer = $(options.idDiv + ' div.timer')
				
                if(timer.length != 0) {
                    var speed = (options.advanceSpeed)/180;
                    var rotator = $(options.idDiv + ' div.timer span.rotator')
                    var mask = $(options.idDiv + ' div.timer span.mask')
                    var pause = $(options.idDiv + ' div.timer span.pause')
                    var degrees = 0;
                    var clock;
                    function startClock() {
                        options.timerRunning = true;
                        pause.removeClass('active')
                        clock = setInterval(function(e){
                            var degreeCSS = "rotate("+degrees+"deg)"
                            degrees += 2
                            rotator.css({ 
                                "-webkit-transform": degreeCSS,
                                "-moz-transform": degreeCSS
                            })
                            if(degrees > 180) {
                                rotator.addClass('move')
                                mask.addClass('move')
                            }
                            if(degrees > 360) {
                                rotator.removeClass('move')
                                mask.removeClass('move')
                                degrees = 0;
                                shift("next")
                            }
                        }, speed);
                    };  
                    function stopClock() {
                        options.timerRunning = false;
                        clearInterval(clock);
                        pause.addClass('active')
                    }   
                    startClock();
                    timer.click(function() {
						if(!options.timercheck)
						{
                            stopClock();
							pause.addClass('actived')
                        } else { 
                            startClock();
							pause.removeClass('actived')
                        }
						options.timercheck = !options.timercheck;
                        /*if(!options.timerRunning) {
                            startClock();
                        } else { 
                            stopClock();
                        }*/
                    })
					 orbit.mouseenter(function() {
						if(!options.timercheck)
						{
							options.hovercheck = true;
							if(options.timerRunning)
								stopClock();
							clearTimeout(outTimer);
						}
						//PreNex.show();
					})
					  orbit.mouseleave(function() {//alert(options.timerRunning)
							if(!options.timercheck)
							{
								if(options.hovercheck)
								{
									outTimer = setTimeout(function() {
										if(!options.timerRunning){
											startClock();
										}
									}, 2)
								}
								options.hovercheck = false;
							}
							//PreNex.hide();
						})
					  
                    if(options.startClockOnMouseOut){
                        var outTimer;
                        orbit.mouseleave(function() {
                            outTimer = setTimeout(function() {
                                if(!options.timerRunning){
                                    startClock();
                                }
                            }, options.startClockOnMouseOutAfter)
                        })
                        orbit.mouseenter(function() {
							stopClock();
                            clearTimeout(outTimer);
                        })
                    }
                }
            }           
            //animation locking functions
            function unlock() {
                locked = false;
            }
            function lock() { 
                locked = true;
            }
            
			//for content and read more
			
			$("#current_content_"+options.moduleId).html(images.eq(activeImage).next().html());
			if(options.readmoreImg!='') $("#read_more_content_"+options.moduleId).html('<a href="../../Copy of mod_k2gallery/assets/'+images.eq(activeImage).attr('href')+'" target="'+images.eq(activeImage).attr('linktarget')+'">'+options.readmoreImg+'</a>');
			
            //DirectionalNav { rightButton --> shift("next"), leftButton --> shift("prev");
            if(options.directionalNav) {								
				var nexsrc = images.eq(activeImage).attr('nexsrc');
				var presrc = images.eq(activeImage).attr('presrc');
				var neximg = "";
				var preimg = "";
				if(nexsrc!="")
					neximg = '<img src="../../Copy of mod_k2gallery/assets/'+ nexsrc + '" style="z-index:2"/>';
				if(presrc!="")
					preimg = '<img src="../../Copy of mod_k2gallery/assets/'+ presrc + '" style="z-index:2"/>';	
					
				var center = Math.round(options.heightImg/2);
				var bottom  = 180;
				if((center-bottom)>0)
					bottom += (center-bottom); 
				
                var directionalNavHTML = '<div class="slider-nav" style="width:'+(orbitWidth)+'px;right:23px;position:relative; bottom:'+bottom+'px; z-index:100"><div class="right"><div style="position:absolute;top:10px;right:-50px" class="childrenRight">'+neximg+'</div><div style="position:absolute;right:-75px">'+options.nexImg+'</div></div> <div class="left"><div style="position:absolute;top:10px" class="childrenLeft">'+preimg+'</div><div style="position: absolute; left: -27px;">'+options.preImg+'</div></div></div>';
                orbit.parent().append(directionalNavHTML);
                var leftBtn = orbit.parent().children('div.slider-nav').children('div.left');
                var rightBtn = orbit.parent().children('div.slider-nav').children('div.right');
                leftBtn.click(function() { 
                    if(options.timer) { stopClock(); }
                    shift("prev");
                });
                rightBtn.click(function() {
                    if(options.timer) { stopClock(); }
                    shift("next")
                });
				
				/*var PreNex = orbit.parent().children('div.slider-nav');
				orbit.parent().children("div.slider-nav").mouseenter(function() {
					PreNex.show();
				});
				
				leftBtn.mouseenter(function() {
					PreNex.show();
				});
				rightBtn.mouseenter(function() {
					PreNex.show();
				});
				leftBtn.mouseleave(function() {
					PreNex.hide();
				});
				rightBtn.mouseleave(function() {
					PreNex.hide();
				});*/
				
            }
            
            //CaptionComputing
            if(options.captions) {
                var captionHTML = '<div class="caption"><p></p></div>';
                orbit.append(captionHTML);
                var caption = orbit.children('div.caption').children('p');
                function setCaption() {
                    var _caption = '<a href="../../Copy of mod_k2gallery/assets/'+images.eq(activeImage).attr('href')+'" target="'+images.eq(activeImage).attr('linktarget')+'">'+images.eq(activeImage).attr('caption')+'</a>';
                    var _captionHeight = caption.height() + 20;
                    if(_caption != "") {
                        caption.html(_caption);
                        caption.parent().stop().animate({"bottom" : 0}, options.captionAnimationSpeed);
                    } else { 
                        caption.parent().stop().animate({"bottom" : -_captionHeight}, options.captionAnimationSpeed);
                    }
                }
                if(caption.length != 0){
                    setCaption();
                }
            }
            
            //BulletControls
            
            
            //Animating the shift!
            function shift(direction) {
                //reset Z & Unlock
                function resetAndUnlock() {
                    images.eq(prevActiveImage).css({"z-index" : 1});
                    unlock()
                }
                if(!locked) {
                    lock();
                    //remember previous activeImg
                    var prevActiveImage = activeImage;
                    var slideDirection = direction;
                    //deduce the proper activeImage
                    if(direction == "next") {
                        activeImage++
                        if(activeImage == numberImages) {
                            activeImage = 0;
                        }
                    } else if(direction == "prev") {
                        activeImage--
                        if(activeImage < 0) {
                            activeImage = numberImages-1;
                        }
                    } else {
                        activeImage = direction;
                        if (prevActiveImage < activeImage) { 
                            slideDirection = "prev";
                        } else if (prevActiveImage > activeImage) { 
                            slideDirection = "next"
                        }
                    }
                    //fade
                    if(options.animation == "fade") {
                        images.eq(prevActiveImage).css({"z-index" : 2});
                        images.eq(activeImage).css({"opacity" : 0, "z-index" : 3})
                        .animate({"opacity" : 1}, options.animationSpeed, resetAndUnlock);
                        if(options.captions) { setCaption(); }
                    }
                    //horizontal-slide
                    if(options.animation == "horizontal-slide") {						
                        images.eq(prevActiveImage).css({"z-index" : 2});
                        if(slideDirection == "next") {
                            images.eq(activeImage).css({"left": orbitWidth, "z-index" : 3})
                            .animate({"left" : 0}, options.animationSpeed, resetAndUnlock);
                        }
                        if(slideDirection == "prev") {
                            images.eq(activeImage).css({"left": -orbitWidth, "z-index" : 3})
                            .animate({"left" : 0}, options.animationSpeed, resetAndUnlock);
                        }
                        if(options.captions) { setCaption(); }
                    }
                    //vertical-slide
                    if(options.animation == "vertical-slide") { 
                        images.eq(prevActiveImage).css({"z-index" : 2});
                        if(slideDirection == "prev") {
                            images.eq(activeImage).css({"top": orbitHeight, "z-index" : 3})
                            .animate({"top" : 0}, options.animationSpeed, resetAndUnlock);
                        }
                        if(slideDirection == "next") {
                            images.eq(activeImage).css({"top": -orbitHeight, "z-index" : 3})
                            .animate({"top" : 0}, options.animationSpeed, resetAndUnlock);
                        }
                        if(options.captions) { setCaption(); }
                    }
					
					if(options.directionalNav) {
						var nexsrc = images.eq(activeImage).attr('nexsrc');
						var presrc = images.eq(activeImage).attr('presrc');
						var neximg = "";
						var preimg = "";
						if(nexsrc!="")					
							neximg = '<img src="../../Copy of mod_k2gallery/assets/'+nexsrc+'" style="z-index:3;"/>';
						if(presrc!="")
							preimg = '<img src="../../Copy of mod_k2gallery/assets/'+presrc+'" style="z-index:3;"/>';
							
						leftImg = leftBtn.children("div.childrenLeft");	
						rightImg = rightBtn.children("div.childrenRight");		
						leftImg.html(preimg);
						rightImg.html(neximg);
						
						//leftImg.children(':second-child').css({"opacity" : 0, "z-index" : 3,"display":"block","position":"absolute"}).animate({"opacity" : 1}, options.animationSpeed, resetAndUnlock);
						//leftImg.children(':first-child').remove();
						$("#current_content_"+options.moduleId).html(images.eq(activeImage).next().html());
						if(options.readmoreImg!='') $("#read_more_content_"+options.moduleId).html('<a href="../../Copy of mod_k2gallery/assets/'+images.eq(activeImage).attr('href')+'" target="'+images.eq(activeImage).attr('linktarget')+'">'+options.readmoreImg+'</a>');
					}
					
                } //lock
            }//orbit function
        });//each call
    }//orbit plugin call
})(jQuery);
        