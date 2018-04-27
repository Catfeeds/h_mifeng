$(document).ready(function() {
	


});
$(function(){
	$(".xieyi").click(function(){
		$(".xieyi_bg").show();
		$(".xieyi_fix").show();
	})
	$(".xieyi_bg").click(function(){
		$(".xieyi_bg").hide();
		$(".xieyi_fix").hide();
	})
})
function $Tab(el_hd,el_bd,el_box,mode,data){
	$(el_box).each(function(){
		var $this=$(this);
		var dome=mode==undefined? 'click':mode;
		$this.find(el_hd).eq(0).addClass('on');
		$this.find(el_bd).eq(0).show().siblings().hide();
		$this.find(el_hd).on(dome,function(){
			var i=data==undefined? $(this).index():parseInt($(this).attr(data))-1;
			$(this).parents(el_box).find(el_bd).eq(i).stop(false,true).fadeIn(300).addClass('on').siblings().removeClass('on').stop(false,true).hide();
			$(this).parents().eq(1).find('.on').removeClass('on');
			$(this).addClass('on');
		});
	})
}

// function login_show(){
// 	$("html,body").css({'width':'100%','height':'100%','overflow':'hidden'});
// 	$(".Login .registered").show().siblings().hide();
// 	$(".Login").slideDown();
// }
// $("#registered_form").on("submit",function(){
// 	var formData=$(this).serialize();
// 	var form_url=$(this).attr('action');
// 	var _this = $(this);
// 	if(_this.attr('is')!='false'){
// 	  _this.attr("is",false);
// 	  $.ajax({
// 	    type: "POST",
// 	    url:form_url,
// 	    data:formData,
// 	    success:function(data){
// 	      _this.attr("is",true);
// 	      if(data.status==201){
// 	        //登陆
// 	        $(".Login .password").show().siblings().hide();
// 	      }else if(data.status==202){
// 	        //验证码
// 	        $(".Login .Lo_code").show().siblings().hide();
// 	      }else{
// 	        layer.open({
// 	          content: data.info
// 	          ,skin: 'msg'
// 	          ,time: 2
// 	        });
// 	      }
// 	    },
// 	  });
// 	}
// 	return false;
// })
// $("#do_login_form").on("submit",function(){
// 	var formData=$(this).serialize();
// 	var form_url=$(this).attr('action');
// 	var _this = $(this);
// 	if(_this.attr('is')!='false'){
// 	  _this.attr("is",false);
// 	  $.ajax({
// 	    type: "POST",
// 	    url:form_url,
// 	    data:formData,
// 	    success:function(data){
// 	      _this.attr("is",true);
// 	      if(data.status==200){
// 	        //注册成功
// 	        if(data.data){
// 	        	location.href=data.data;
// 	        }else{
// 						$("html,body").css({'width':'100%','height':'auto','overflow':'auto'});
// 	        	$('.Login').hide();
// 	        }
// 	      }else{
// 	      	layer.open({
// 	      	  content: data.info
// 	      	  ,skin: 'msg'
// 	      	  ,time: 2
// 	      	});
// 	      }
// 	    },
// 	  });
// 	}
// 	return false;
// })
// $("#do_register_form").on("submit",function(){
// 	var formData=$(this).serialize();
// 	var form_url=$(this).attr('action');
// 	var _this = $(this);
// 	if(_this.attr('is')!='false'){
// 	  _this.attr("is",false);
// 	  $.ajax({
// 	    type: "POST",
// 	    url:form_url,
// 	    data:formData,
// 	    success:function(data){
// 	      _this.attr("is",true);
// 	      if(data.status==200){
// 	        //注册成功
// 	        if(data.data){
// 	        	location.href=data.data;
// 	        }else{
// 				$("html,body").css({'width':'100%','height':'auto','overflow':'auto'});
// 	        	$('.Login').hide();
// 	        }
// 	      }else{
// 	      	layer.open({
// 	      	  content: data.info
// 	      	  ,skin: 'msg'
// 	      	  ,time: 2
// 	      	});
// 	      }
// 	    },
// 	  });
// 	}
// 	return false;
// })
// var sint = '';
// var i=60;
// $(".code").click(function(){
// 	var _this = $(this);
// 	var phone = '';
// 	if(_this.attr("data-phone")=='true'){
// 		phone = "&phone="
// 		phone += _this.parents(".code_box").siblings("input[name='phone']").val();
// 	}
	
// 	if(_this.attr('is')!='false'){
// 		_this.attr("is",false);
// 	  $.ajax({
// 	    type:'POST',
// 	    url:_this.attr("data-url"),
// 	    data:"code=code"+phone,
// 	    dataType: "JSON",
// 	    success:function(data){
// 	      if(data.status==200){
// 	      	layer.open({
// 	      	  content: "验证码是："+data.data
// 	      	  ,skin: 'msg'
// 	      	  ,time: 10
// 	      	});
// 	      	sint = setInterval(function(){
// 	      		_this.html(i--);
// 	      		if(i<=0){
// 					_this.attr("is",true);
// 	      			_this.html("发送验证码");
// 	      			clearInterval(sint);
// 	      		}
// 	      	},1000)
// 	      }else{
// 	      	_this.attr("is",true);
// 	      	layer.open({
// 	      	  content: data.info
// 	      	  ,skin: 'msg'
// 	      	  ,time: 2
// 	      	});
// 	      }
// 	    }
// 	  })
// 	}
// })