window.onerror=function(){return true;};

function loading(l,t){
    var long = l || '100%';
    var time = t || 1000;
    jQuery('.loading').clearQueue();
    jQuery('.loading').animate({'width':long},time);
};


jQuery(function($) {
    if(adminBar == "checked"){
        topBlock = 32;
    }else{
        topBlock = 0;
    }
    var windowWidth = parseInt($(window).width());
    if(windowWidth > 966){
        $('section img').hover(function(){$(this).clearQueue();$(this).fadeTo(300,0.8);},function(){$(this).clearQueue();$(this).fadeTo(300,1)})
    }

    if(!isNaN(cookie.get('font-size'))){checkFontSize(parseInt(cookie.get('font-size')))};
    DHeight = $(document).height();

    $('.toggletitle').click(function(){
        $(this).next('.togglecon').slideToggle();
    });

    var windowWidth = $(window).width();

    $('#mobile_nav_btn').click(function(){
        $(this).next('.menu-header').slideToggle()
    });

    //弹出层方法调用
    $('.is_login .iframe').click(function(){
        var _thisHref = $(this).attr('href');
        var _thisTitle = $(this).attr('title');
        popWin.showWin("780",windowHeight-100,_thisTitle,_thisHref);
        return false;
    })




//	function cons(){
//		var navHtml = $('.menu-header').html();
//		$('#nav_bs').html(navHtml);
////		var nav_bs = $('#nav_bs').height();
////		$('#nav_bs').css('height',windowHeight);
//		var mobileNavHeight = $(document).height()
//		$('.mobile_nav').height(mobileNavHeight);
//		var nav_bs = $('#nav_bs').height();
//		$('#nav_bs').css('height',windowHeight);
//		$('#nav_bs li').each(function(index, element) {
//			$(this).removeClass();
//			$(this).removeAttr("id");
//		});
//		$('#nav_bs ul').each(function(index, element) {
//			$(this).removeClass();
//		});
//	}
//
//	$('#mobile_nav_btn').click(function(){
////		$(this).next('.menu-header').slideToggle()
//		if ($('.web_bod').hasClass('animate_app')) {
//			$('.web_bod').removeClass('animate_app');
////			$('#nav_bs').html('');
//		}
//		 else {
//			$('.web_bod').addClass('animate_app');
//			cons()
//		}
//	});


    if($('#g_box li')[0] && $('#g_box li').length > 1){
        $('.gonggao').show()
        var tId = setInterval(gg_roll,5000)
    }else if($('#g_box li')[0]){
        $('.gonggao').show()
    }


    doNow = 0;  //初始化全局变量
    doNow2 = 0;
    doNow3 = 0;
    doin = 0; //side返回顶部的变量
    ofText = $('#official').text();
    TnavPos = $('.navcon').position(); //获取导航条的位置
    TnavMain = $('.menu-header').position();

    $('#article-index').hover(function(){
        if(doNow2 == 1){$(this).clearQueue();$(this).fadeTo(300,1);}
    },function(){
        if(doNow2 == 1){$(this).clearQueue();$(this).fadeTo(300,0.2)};
    });

    $("#float_gotop").hover(function(){
        if(doin == 1){$(this).clearQueue();$(this).fadeTo(100,1);}
    },function(){
        if(doin == 1){$(this).clearQueue();$(this).fadeTo(100,0.7)};
    });

    $(".floatbtn").hover(function(){
        if(doin == 1){$(this).clearQueue();$(this).fadeTo(100,1);}
    },function(){
        if(doin == 1){$(this).clearQueue();$(this).fadeTo(100,0.7)};
    });


    if(mod_txt != '#'){
        mod = $(mod_txt);
        nav = mod.position();
    };

    var $search = $('#s'); //设置search框的ID
    $search.focus(function() {
        $(this).css({
            'background': '#FD7B2F',
            'color': '#fff'
        });
        f = setInterval(flash, 1)
    });

    $search.blur(function() {
        $(this).css({
            'background': '#fff',
            'color': '#999'
        });
        clearTimeout(f);
    });
    function flash() {
        $search.fadeTo(1000, 0.7);
        $search.fadeTo(1000, 1);
    };

    $('.nav_button').hover(function() {
            $(this).clearQueue()
            $(this).fadeTo(100, 1);
            $(this).parents('dl').animate({"top":"-5px"},100);
        },
        function() {
            $(this).clearQueue()
            $(this).fadeTo(100, 0.7);
            $(this).parents('dl').animate({"top":"0px"},100);
        });

    // var moreLink = $('.more-link');
    // $('.main .post_box').hover(function() {
    //         $(this).find(moreLink).animate({
    //                 'width': '25%'
    //             },
    //             100);
    //     },
    //     function() {
    //         $(this).find(moreLink).animate({
    //                 'width': '15%'
    //             },
    //             100)
    //     });

    // $('#content .post_box').hover(function() {
    //         $(this).find(moreLink).animate({
    //                 'width': '25%'
    //             },
    //             100)
    //     },
    //     function() {
    //         $(this).find(moreLink).animate({
    //                 'width': '15%'
    //             },
    //             100)
    //     });


    $('.menu li').hover(function() {
            $(this).children('ul').children('li').show();
        },
        function() {
            $(this).children('ul').children('li').hide();
        });


    $('ul li','.menu').hover(function() {
        var width = $(this).parent().width();
        $(this).children('.sub-menu').css('left', width);
    });

    // $('.tit .h1 a').each(function(){
    //     $(this).click(function(){
    //         var oldText = $(this).text();
    //         $(this).text('页面正在加载，请稍候...');
    //         var t = setTimeout(_show($(this),oldText),5000);
    //     });
    // });

    function show(obj,text){obj.text(text);}
    function _show(obj,text){
        return function(){show(obj,text);}
    }

    $('#index-ul a').click(function(){
        var getId = $(this).attr('href');
        var getIdPos = $(getId).position();
        goRoll(getIdPos.top,300);
        duanFlash(getId);
        return false;
    });

    $('#rss_open').click(function(){
        $('.rss_box').show(200)
    })
    $('.close_rss').click(function(){
        $('.rss_box').hide(200)
    })

//	function appNav(){
//		var navHtml = $('.menu-header').html();
//		$('#nav_bs').html(navHtml);
//		var nav_bs = $('#nav_bs').height();
//		$('#nav_bs').css('height',windowHeight);
//		$('#nav_bs li').each(function(index, element) {
//			$(this).removeClass();
//			$(this).removeAttr("id");
//		});
//		$('#nav_bs ul').each(function(index, element) {
//			$(this).removeClass();
//		});
//
//		$('#mobile_nav_btn').click(function(){
//	//		$(this).next('.menu-header').slideToggle()
//			if ($('.web_bod').hasClass('animate_app')) {
//				$('.web_bod').removeClass('animate_app');
//			}
//			 else {
//				$('.web_bod').addClass('animate_app');
//			}
//		});
//
//	}



//	var explorer = window.navigator.userAgent ;
//	if (explorer.indexOf("MSIE") >= 0) {
//		ieWindow()
//	}
    navTop = $(document).scrollTop();
    var windowHeight = $(window).height();
    screenBottom = navTop + windowHeight;
    pageImgLoad('.conter img',screenBottom,navTop)
});

function goend(){
    goRoll(DHeight)
}


function goRoll(n,time){
    var speed = time || 1000;
    var n  = n || 0;
    jQuery('html,body').animate({scrollTop:n-50},speed);
};

function duanFlash(sect){
    jQuery(sect).css({'background':'#00BCF2','color':'#fff'});
    setTimeout(function(){jQuery(sect).css({'background':'none','color':'#454545'})},1000);
};

//function ieWindow(){
//	var windowWidth = $(window).width();
//	if(windowWidth >= 1366)
//	{
//		$('.post_box').width('44.9%');
//		$('.post_box').css('float','left');
//		$('.post_box .c-con').css('height','140px');
//	}else if(windowWidth < 1366){
//		$('.post_box').width('100%');
//		$('.post_box').css('float','none');
//		$('.post_box .c-con').css('height','auto');
//	}
//
//}

jQuery(window).scroll(function($){
    navTop = jQuery(document).scrollTop();
    var windowHeight = jQuery(window).height();
    screenBottom = navTop + windowHeight;
    pageImgLoad('.conter img',screenBottom,navTop)
    if(mod_txt != '#'){
        rollCheck();
    }
    rollSoy();
    rollNav();
    sideGoTop();
});

jQuery(window).resize(function($){
    if(mod_txt != '#'){
        rollResize();
    };
    var windowWidth = jQuery(window).width();
    if(windowWidth >= 960){
        $('.menu-header').show();
    }
    TnavPos = $('.navcon').position(); //获取导航条的位置
    TnavMain = $('.main').position();

    /*	if(windowWidth < 960){
     appNav()
     }

     */

    /*	var explorer = window.navigator.userAgent ;
     if (explorer.indexOf("MSIE") >= 0) {
     ieWindow()
     }
     */
});

function pageImgLoad(bod,scb,navTop){
    jQuery(bod).each(function() {
        imgPos = jQuery(this).position()
        imgY = imgPos.top
        imgSrc = jQuery(this).attr('data-src')
        imgH = jQuery(this).height()
        imgIf = imgY + imgH
        if(screenBottom >= imgY && navTop <=imgIf){jQuery(this).attr('src',imgSrc);}
    });
}

function sideGoTop(){
    if(navTop > 0 && doin == 0){
        doin = 1;
        jQuery("#float").animate({"bottom":"50px"},500);
//		$('#float_gotop').fadeTo(100,0.7);
    }else if(navTop <= 0 && doin == 1){
        doin = 0;
        jQuery("#float").animate({"bottom":"-152px"},500);
//		$('#float_gotop').fadeTo(100,0);
    };
};

function rollNav(){
    var TnavBot = jQuery('.navcon')
    var TnavPosTop = TnavPos.top;
    var TnavWidth = jQuery('.navcon').width();
    if(navTop >= TnavPosTop+10-topBlock && doNow3 == 0){
        TnavBot.css({
                //'left':TnavMain.left,
                'position':'fixed',
                'top':0+topBlock,
                'z-index':'15',
                'margin-top':'0',
                'overflow':'visible',
                'width':TnavWidth,
            }
        )
        TnavBot.after('<div id="navbei" style="height:55px;"></div>')
        doNow3 = 1;

    }else if(navTop <= TnavPosTop-10-topBlock && doNow3 == 1){
        TnavBot.css({
            'position':'static',
            'margin-top':'10px',
            'overflow':'hidden',
            'width':'94%',
        });
        jQuery('#navbei').remove();
        doNow3 = 0;
    }
}

function rollSoy(){
    var father = jQuery('#content');
    var bod = jQuery('#article-index');
    var fat = jQuery('.entry-content');
    if(father[0] && bod[0] && fat[0]){
        var bodPos = bod.position();
        var fatPos = fat.position();
        var fatherPos = father.position();
        var fatTop = fatPos.top;
        var fatHeight = fat.height();
        var bodLeft = fatherPos.left + father.width() - bod.width() - 10;
        if(navTop >= fatTop-50-topBlock  && doNow2 == 0)
        {
            bod.css({
                'position':'fixed',
                'top':50+topBlock,
                'z-index':'99',
                'left':bodLeft,
            })
            bod.fadeTo(500,0.2)
            doNow2 = 1;
        }else if(navTop <= fatTop-50-topBlock  && doNow2 == 1){
            bod.css('position','static')
            bod.fadeTo(500,1)
            doNow2 = 0;
        }else if(navTop >= fatHeight){
            bod.slideUp(300);
        }else if(navTop <= fatHeight){
            bod.slideDown(300);
        }
    }
}

/*	导航条贴合JS*/
function rollCheck(){
    var modHieght = mod.height();
    var bodyBG = jQuery('body').css('background-color');
    navbod = mod;
    navWidth = navbod.width();
    navbod.css('left',nav.left);
    if(navTop >= nav.top-50-topBlock && doNow == 0)
    {
        navbod.css({
            'position':'fixed',
            'top':50+topBlock,
            'z-index':'99',
            'background':bodyBG,
            'width':navWidth,
            'overflow':'hidden'
        });
        modHieght = mod.height();
        jQuery("<div id='tian'></div>").insertAfter(navbod);
        jQuery('#tian').css('height',modHieght + 20);
        doNow = 1;

    }else if(navTop <= nav.top-50-topBlock && doNow == 1){
        navbod.css({
            'position':'static',
            'background':'none'
        });
        jQuery('#tian').remove();
        doNow = 0;
    };
};

function checkFontSize(s){
    jQuery('.entry-content').css({'font-size':s + 'px','line-height':(s + 14)+'px'});
    //cookie.set('font-size',s,40000);
}

function rollResize(){
    var modHieght = mod.height();
    navbod = mod;
    navWidth = navbod.width();
    navbod.css('left',nav.left);
    var bodyBG = $('body').css('background-color');

    mod.css('position','static');
    var nav2 = mod.position();
    nav2temp = $('#primary');
    navbod2 = mod;
    navWidth2 = nav2temp.width();
    navbod2.css('left',nav2.left);
    navbod2.css('width',navWidth2);
    navWidth = navWidth2;
    navTop2 = jQuery(document).scrollTop();

    if(navTop2 >= nav2.top-50-topBlock)
    {
        navbod2.css({
            'position':'fixed',
            'top':50+topBlock,
            'z-index':'99',
            'background':bodyBG,
            'width':navWidth2,
        });
    }else{
        navbod2.css({
            'position':'static',
            'background':'none',
        });
    };
};


/*页面图片拉伸*/
jQuery(function($){
    var doBox = $('.entry-content');
    doBox.find('img').each(function(){
        var picWidth = parseInt($(this).width());
        var boxWidth = parseInt(doBox.width());
        if(picWidth > boxWidth)
        {
            var pW = $(this).width();
            var pH = $(this).height();
            var BL = pH / pW;
            var outH = boxWidth * BL;
            $(this).width(boxWidth);
            $(this).height(outH);
        };
    });


    var BoxWidth = $('.entry-content').width();
    var windowWidth = $(window).width();
    if(windowWidth < 960){
        $('.entry-content iframe').each(function(){
            var thisHeight = $(this).attr('height');
            var thisWidth  = $(this).attr('width');
            var BLT = thisHeight / thisWidth;
            var outVH = BoxWidth * BLT;
            $(this).attr("width",BoxWidth);
            $(this).attr("height",outVH);
        })
    }


});


//公告滚动函数
function gg_roll(){
    jQuery('#g_box').animate({'top':'-20px'},600,function(){jQuery('#g_box').css('top','0');move_GG_li()});
}
function move_GG_li(){
    var first = jQuery('#g_box li').first();
    var last  = jQuery('#g_box li').last();
    var firstHtml = first.html();
    first.remove();
    jQuery('#g_box').append("<li>" + firstHtml + "</li>");
}

//弹出层
var popWin = {
    scrolling: 'yes',
    //是否显示滚动条 no,yes,auto

    int: function() {
        this.mouseClose();
        this.closeMask();
        //this.mouseDown();
    },

    showWin: function(width, height, title, src) {
        var iframeHeight = height - 52;
        var marginLeft = width / 2;
        var marginTop = height / 2;
        var inntHtml = '';
        inntHtml += '<div id="mask" style="width:100%; height:100%; position:fixed; top:0; left:0; z-inde:1999;background:#000; z-index:101; filter:alpha(opacity=70); -moz-opacity:0.7; -khtml-opacity: 0.7; opacity:0.7;"></div>'
        inntHtml += '<div id="maskTop" style="width: ' + width + 'px; height: ' + height + 'px; border: #999999 1px solid; background: #fff; color: #333; position: fixed; top: 50%; left: 50%; margin-left: -' + marginLeft + 'px; margin-top: -' + marginTop + 'px; z-index: 2999; filter: progid:DXImageTransform.Microsoft.Shadow(color=#909090,direction=120,strength=4); -moz-box-shadow: 2px 2px 10px #909090; -webkit-box-shadow: 2px 2px 10px #909090; box-shadow: 2px 2px 10px #909090;">'
        inntHtml += '<div id="maskTitle" style="height: 50px; line-height: 50px; font-family: Microsoft Yahei; font-size: 20px; color: #333333; padding-left: 20px; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAAyCAYAAABlG0p9AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAABvSURBVEhL1cq5DcAwDENR7T+sL9lOOoUbkCoCwwKewOJbiGe+31BkwgeDM18YgrPhxuBs4CkS4cQQZMKFwd0R+gzFJaFjcD+EfXgoMuHA4O4Iew/FJWHD4BJhwxDoYcNTIKwY3NGwYggQFgxODEt8xO1/6P+HHxEAAAAASUVORK5CYII=); border-bottom: 1px solid #999999; position: relative;">'
        inntHtml += '' + title + ''
        inntHtml += '<div id="popWinClose" style="width: 28px; height: 28px; cursor: pointer; position: absolute; top: -12px; right: -9px; background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJeSURBVEhLvZbPq2lRFMf9B4bSTTIxZiBSMlCI9ycoKX+Bod7w/il3YIL4NyhFmYmBKD2Sp0ix3vqes/e529n74t33Op9astevr3PO2tvxvcLtdquzfbAtyAV8IlYX6d+DG7yxvbP9Fr2fglxR8ybavAYX/GD7Jfr8NahFD9HuMZz4U9Q5jEYjqlarFA6HiVPuDD7EkOMGvTjna9xi8/mcstmsJvKVIRc1Kl+K4haIHItut0t+v9/Y+JGhBrUq6M2xT9iBAXGeGQrY/U+miqI3NNhvw4t3EbNuyXeuzG3ood5eaLDfhhfO6JueWbPZtGKFQkGLNRoN2u/3FI/HtRh6SaDBPkusLnzWpMlkaRC7XC5WfLVaUTqddmKVSoVOp5MVG4/HlEql7mph6vRCC4IfYm2Nt7vAzW63o2KxSLVaja7Xq/DatFotrR49JdCCoHNcmfZZPp+n9XotMmxwVVwnVjbD4ZAikYhWj54SaN1dgjtZWiaToe12K7J0JpOJUUyaykuCsFwuR8fjUWR+slgsKBAIGGukqbwsiGdmElwul5RIJIw10lReEsQ0ns9nkaVzOBys226qhak8HRrsM7ktJLPZjDabjVjZYLBKpZJWrw0NfzzcFvj1KtPp1HpmsVjM2iIq/X5fqzdti4cbHycINjUYDAYUCoWcGA4BHAag1+tRMBi8q4VpGx/wl4dHWzKZpHa7TdFoVIuVy2XqdDrGSTUebYAXnh/e3v49AXZ49wcs4YB3rxgStyjApGG8TfsUPsTUaZQ8FZPgFrB585oo4QLvXoTdcIP/9Krv8/0BDUSOirKWU6wAAAAASUVORK5CYII=);"></div>'
        inntHtml += '</div>'
        inntHtml += '<iframe width="' + width + '" height="' + iframeHeight + '" frameborder="0" scrolling="' + this.scrolling + '" src="' + src + '"></iframe>';

        jQuery("body").append(inntHtml);
        this.int();


    },

    mouseClose: function() {
        jQuery("#popWinClose").on('mouseenter',
            function() {
                jQuery(this).css("background-image", "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJwSURBVEhLvZbLSiNBFIb7DVyKiIgb17oQRRAXgor6CIIIeQKXMksfxYUbFbMZRh0Yb6ODMgEddCVmoWkRLzFekukxfay/+lRbqSqTVob+4CyqzuVPV59TaS8JYRhmhM0Ly5MB9tiX4fDPIQq0CpsT9sC1G4JYzmnlMskQCRPCrrnOh0EuanC5+ojAL5wXc5/LUW5qitba2ynreTWGPfgQY4JaXNaNKfZ0dkY7g4OWyHuGWOTovCuKI+AYib+8TF+bmpyF6xlykKuD2iwTITbQIPE7Q4Kr2EdMF0VtaLCcFJxjnzySzzyZaaihHy80WE4Kxq3vemcns7PStzsyYvn+zMxQUCzSRne35UMtBTSUWIb3ZKeZSRCrBoH0lwsF2u7vj32/JyepWi5L3/3hIW319dXkwvTuhRYE53kt29tMMAlub2lvdJRy09MUVqu8G3GxsGDlo6YCWhCMryvXnO0OD1PF9zkiQj5VGPIqonhwQOsdHVY+aiqgVfMIZrCy7YEBCm5uOMqmdHTkFFOmk0gQ9nNoiF4eHznyjed8nr41NztzlOkkFsQ7cwmWz89ps6fHmaNMJ5Gg7MZKhaNs/pVK8thduTCdhk2DOVNjoXg6PaW/V1e8ikBj7Y2NWflW06BVee0cC/x6nYfjY/nOfnR1yRHRucxmrXzXWNQdfNwgGGpwt79Pa21tsQ+XAC4D4K+s0GpLS00uzBp8vm3qXm1bvb1UWFyk752dlu/X+Dj5S0vOTnVebUAsUr+80/17AmIjvT9ghXCk94mhMEUBOg3t7ZpT7MGnd6OioZgCRyAsnc9EhUhI70PYRBT4T5/6nvcKYG1hElXAZggAAAAASUVORK5CYII=)");

            });

        jQuery("#popWinClose").on('mouseleave',
            function() {
                jQuery(this).css("background-image", "url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAABwAAAAcCAYAAAByDd+UAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAJeSURBVEhLvZbPq2lRFMf9B4bSTTIxZiBSMlCI9ycoKX+Bod7w/il3YIL4NyhFmYmBKD2Sp0ix3vqes/e529n74t33Op9astevr3PO2tvxvcLtdquzfbAtyAV8IlYX6d+DG7yxvbP9Fr2fglxR8ybavAYX/GD7Jfr8NahFD9HuMZz4U9Q5jEYjqlarFA6HiVPuDD7EkOMGvTjna9xi8/mcstmsJvKVIRc1Kl+K4haIHItut0t+v9/Y+JGhBrUq6M2xT9iBAXGeGQrY/U+miqI3NNhvw4t3EbNuyXeuzG3ood5eaLDfhhfO6JueWbPZtGKFQkGLNRoN2u/3FI/HtRh6SaDBPkusLnzWpMlkaRC7XC5WfLVaUTqddmKVSoVOp5MVG4/HlEql7mph6vRCC4IfYm2Nt7vAzW63o2KxSLVaja7Xq/DatFotrR49JdCCoHNcmfZZPp+n9XotMmxwVVwnVjbD4ZAikYhWj54SaN1dgjtZWiaToe12K7J0JpOJUUyaykuCsFwuR8fjUWR+slgsKBAIGGukqbwsiGdmElwul5RIJIw10lReEsQ0ns9nkaVzOBys226qhak8HRrsM7ktJLPZjDabjVjZYLBKpZJWrw0NfzzcFvj1KtPp1HpmsVjM2iIq/X5fqzdti4cbHycINjUYDAYUCoWcGA4BHAag1+tRMBi8q4VpGx/wl4dHWzKZpHa7TdFoVIuVy2XqdDrGSTUebYAXnh/e3v49AXZ49wcs4YB3rxgStyjApGG8TfsUPsTUaZQ8FZPgFrB585oo4QLvXoTdcIP/9Krv8/0BDUSOirKWU6wAAAAASUVORK5CYII=)");

            });

    },

    closeMask: function() {
        jQuery("#popWinClose").on('click',
            function() {
                jQuery("#mask,#maskTop").fadeOut(function() {
                    jQuery(this).remove();

                });

            });

    }

    /*mouseDown : function(){
     var dragging = false;
     var iX, iY;
     //var elmen = $("div#maskTop");
     $("#maskTop").on('mousedown' , function(e){
     dragging = true;
     iX = e.clientX - this.offsetLeft;
     iY = e.clientY - this.offsetTop;
     this.setCapture && this.setCapture();
     return false;
     });
     document.onmousemove = function(e) {
     if (dragging) {
     var e = e || window.event;
     var oX = e.clientX - iX;
     var oY = e.clientY - iY;
     $("#maskTop").css({"left":oX + "px", "top":oY + "px"});
     return false;
     }
     };
     $(document).mouseup(function(e) {
     dragging = false;
     $("#maskTop")[0].releaseCapture();
     e.cancelBubble = true;
     })
     },*/

};

/*-----------------------方法start---------------------------*/

/*cookie方法*/
var cookie = {
    set:function(key,val,time){//设置cookie方法
        var date=new Date(); //获取当前时间
        var expiresDays=time;  //将date设置为365天以后的时间
        date.setTime(date.getTime()+expiresDays*24*3600*1000); //将tips的cookie设置为10天后过期
        document.cookie=key + "=" + val +";expires="+date.toGMTString();  //设置cookie
    },
    get:function(key){//获取cookie方法
        /*获取cookie参数*/
        var getCookie = document.cookie.replace(/[ ]/g,"");  //获取cookie，并且将获得的cookie格式化，去掉空格字符
        var arrCookie = getCookie.split(";")  //将获得的cookie以"分号"为标识 将cookie保存到arrCookie的数组中
        var tips;  //声明变量tips
        for(var i=0;i<arrCookie.length;i++){   //使用for循环查找cookie中的tips变量
            var arr=arrCookie[i].split("=");   //将单条cookie用"等号"为标识，将单条cookie保存为arr数组
            if(key==arr[0]){  //匹配变量名称，其中arr[0]是指的cookie名称，如果该条变量为tips则执行判断语句中的赋值操作
                tips=arr[1];   //将cookie的值赋给变量tips
                break;   //终止for循环遍历
            }
        }
        return tips;
    }
}
/*-----------------------方法end---------------------------*/