$(document).ready(function() {
	(function(){
		var $html=$('html');
		var $window=$(window);
		var psdsize=840;
		var htmlfont=$window.width()/psdsize*100+'px';
		$html.css('font-size',htmlfont);
		$window.css('opacity',1);
		$window.resize(function () {
				htmlfont=$window.width()/psdsize*100+'px';
				$html.css('font-size',htmlfont)
		});
	})();//页面rem单位初始化适应
	
//导航
	 	var gg=$(window).width();
	 	if(gg<981){
		 	 
	 	}else{
	 		$(".nav-mian>ul>li").mouseenter(function(){
	        	$(".nav-mian>ul>li").removeClass('on');
	        	$(".subNav").stop(true,true).hide();
	        	$(this).addClass('on');
	            $(this).find(".subNav").stop(true,true).show();
		    });
            $(".nav-mian>ul>li").mouseleave(function(){
                $(this).removeClass('on');
                $(this).find(".subNav").stop(true,true).hide();
            });      
	 	}
	 	
//下拉选框
		$(".select").each(function(){
			var s=$(this);
			var z=parseInt(s.css("z-index"));
			var dt=$(this).children("dt");
			var dd=$(this).children("dd");
			var _show=function(){dd.slideDown(200);dt.addClass("on");s.css("z-index",z+1);};   //展开效果
			var _hide=function(){dd.slideUp(200);dt.removeClass("on");s.css("z-index",z);};    //关闭效果
			dt.click(function(){dd.is(":hidden")?_show():_hide();});
			dd.find("a").click(function(){dt.html($(this).html());_hide();});     //选择效果（如需要传值，可自定义参数，在此处返回对应的“value”值 ）
			$("body").click(function(i){ !$(i.target).parents(".select").first().is(s) ? _hide():"";});
		});


/*------------------------------------------------------底部 - 下拉菜单--------------------------------------------------------------------*/
$(function () {
    $(".menuBox a.default").click(function () {
        $(this).parent().find("ul").slideToggle("fast", function () {
            if ($(this).parent().find("ul").css("display") == "block") {
                $(".menuBox").css("border-top", "1px solid #fff");
            }
            else if ($(this).parent().find("ul").css("display") != "block") {
                $(".menuBox").css("border-top", "1px solid #ebebeb");
            }
        });

    });
    $(".menuBox ul li a").click(function () {
        $(this).parent().parent().slideUp("fast", function () {
            if ($(this).parent().find("ul").css("display") == "block") {
                $(".menuBox").css("border-top", "1px solid #fff");
            }
            else if ($(this).parent().find("ul").css("display") != "block") {
                $(".menuBox").css("border-top", "1px solid #ebebeb");
            }
        });

    });
    //点击其他地方时，收起下拉列表
    $(document).bind("click", function (e) {
        var $clicked = $(e.target);
        if (!$clicked.parents().hasClass("menuBox")) {
            $(".menuBox ul").slideUp("fast");
            $(".menuBox").css("border-top", "1px solid #ebebeb");
        }
    });
});
/*------------------------------------------------------底部 - 下拉菜单 - end--------------------------------------------------------*/

/*----------------------------------------------------------------上下滚动-----------------------------------------------------------*/
function scrollUp(area, box, leftBtn, rightBtn, showNum, waitTime, animateTime, easing, autoPlay) {
    var config = {
        area: $(area),                	//鼠标移入停止自动播放的层
        box: $(box), 				//包含ul那个div
        leftBtn: $(leftBtn),        	//左按钮
        rightBtn: $(rightBtn),      	//右按钮
        showNum: showNum, 			//容器显示的图片个数
        waitTime: waitTime,   			//间隔时间
        animateTime: animateTime,      	//滚动效果时间
        easing: easing, 			//滚动效果
        autoPlay: autoPlay, 		//是否自动播放	
        leftAllow: true,    			//为了防止连续点击左按钮
        rightAllow: true    			//为了防止连续点击右按钮		
    }

    //小于容器显示的图片个数，就不滚动
    if (config.box.find("li").length < config.showNum) {
        config.leftAllow = false; config.rightAllow = false;
    } else {
        var listLen = config.box.find("li").length;
        //复制全部li插入到ul最后面
        config.box.find("li").clone().appendTo(config.box.find("ul"));
        //复制全部li插入到ul的最前面
        config.box.find("li").clone().prependTo(config.box.find("ul"));
        for (i = 0; i < listLen; i++) {
            config.box.find("li").last().remove();
        }
        //设置ul的左外边距初始位置
        config.box.find("ul").css("margin-top", -config.box.find("li").outerHeight(true) * listLen + "px");
    }

    //向上滚动
    config.rightBtn.click(function () { autoScroll(); });
    function autoScroll() {
        if (config.rightAllow) {
            var marTop = parseInt(config.box.find("ul").css("margin-top"), 10);
            var imgWid = config.box.find("li").outerHeight(true);
            config.rightAllow = false;
            config.box.find("ul").animate({ marginTop: marTop - imgWid + "px" }, config.animateTime, easing, function () {
                marTop = parseInt(config.box.find("ul").css("margin-top"), 10);
                var len = config.box.find("li").length;
                var mar = (config.showNum - listLen) * imgWid + "px";
                if (marTop == (config.showNum - len) * imgWid) { config.box.find("ul").css("margin-top", mar); }
                config.rightAllow = true;
            });
        }
    }

    //向下滚动
    config.leftBtn.click(function () {
        if (config.leftAllow) {
            var marTop = parseInt(config.box.find("ul").css("margin-top"), 10);
            var imgWid = config.box.find("li").outerHeight(true);
            config.leftAllow = false;
            if (marTop == 0) { config.box.find("ul").css("margin-top", -listLen * imgWid + "px"); }
            marTop = parseInt(config.box.find("ul").css("margin-top"), 10);
            config.box.find("ul").animate({ marginTop: marTop + imgWid + "px" }, config.animateTime, easing, function () {
                marTop = parseInt(config.box.find("ul").css("margin-top"), 10);
                var len = config.box.find("li").length;
                var mar = -listLen * imgWid + "px";
                if (marTop == 0) { config.box.find("ul").css("margin-top", mar); }
                config.leftAllow = true;
            });
        }
    });

    //自动播放
    if (config.autoPlay) {
        var intID = setInterval(autoScroll, config.waitTime);
        config.area.hover(function () {
            clearInterval(intID);
        }, function () {
            intID = setInterval(autoScroll, config.waitTime);
        });
    }

}
/*----------------------------------------------------------------上下滚动 - 结束--------------------------------------------------------*/
/*------------------------------------------------------首页新闻滚动--------------------------------------------------------------------*/
$(function () {
    scrollUp(
		"#topNews_box", 						//鼠标移入停止自动播放的层
		"#topNews", 		//包含滚动图片的那个div
		"#topNews a.leftBtn", 			//左按钮
		"#topNews a.rightBtn", 			//左按钮
		1, 								//容器显示的图片个数
		3000, 							//间隔时间
		"normal", 						//滚动效果时间
		"", 								//滚动效果
		true								//是否自动播放
	);
});
/*------------------------------------------------------首页新闻滚动 - end------------------------------------------------------------------*/

})