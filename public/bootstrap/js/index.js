$(function () {

	//显示图片上传框
	$('.icon-picture').click(function () {
		// $('#phiz').hide();
		$('#upload_img').show();
	});

	//微博异步上传表单
	$('#send_weibo').click(function(){

	   	var content = $('textarea[name="content"]').val();
	   	var max = $('input[name="max"]').val();
	   	var medium = $('input[name="medium"]').val();
	   	var mini = $('input[name="mini"]').val();

	   	if(content != ''){
	   		$.post(
	   			sendWeibo,
	   			{
	   				content : content,
	   				max : max,
	   				medium : medium,
	   				mini : mini,
	                                 	_token : token                                 
	   			},
	   			function(data){
	   				if(data.status){

	   					// $('#pic-show').fadeOut();
	   					// $('#upload_img').fadeOut();
	                                          		showTips(data.msg);       
	   					// $('textarea[name="content"]').val('');
	   					// $('input[name="max"]').val('');
	   					// $('input[name="medium"]').val('');
	   					// $('input[name="mini"]').val('');
	   					setTimeout(function(){
	   						$("body").scrollTop(0);
							window.location.reload();
	   					},2000);
	   					// window.location.reload();
	   				}else{
	   					alert(data.msg);
	   				}
	  				
	   			}
	   		,'json');
	   	}		
	});	

	$('.mini_img').click(function () {
		$(this).hide().next().show();
	});
	$('.img_info img').click(function () {
		$(this).parents('.img_tool').hide().prev().show();
	});		
	$('.packup').click(function () {
		$(this).parents('.img_tool').hide().prev().show();
	});	

	/**
	 * 转发框处理
	 */
	 $('.turn').click(function () {

	 	//获取原微内容并添加到转发框
	 	var orgObj = $(this).parents('.wb_main');
	 	var author = $.trim(orgObj.find('.author').html());
	 	var content = orgObj.find('.content').html();

 		// 替换掉其中的描红的内容 <font style="color:red;">*</font>
 		var regi = /<font style="color:red;">/g;
 		var regii = /<\/font>/g;
 		content = content.replace(regi,'').replace(regii,'');

	 	$('.turn-cname').html(author);//同时评论给谁

	 	//tid 被转载的原微博的id
	 	var tid = $(this).attr('tid') ? $(this).attr('tid') : 0;

	 	if(tid){
	 		//获取转发者的信息，拼成字符串放进输入框里	
	 		var cons = replace_weibo(' // @' + author + ' : ' + content);

	 		$('form[name=turn] textarea').val(cons);	
	 		 		
	 		// 转发的原微博内容
	 		author = $.trim(orgObj.find('.turn_name').html());
	 		content = orgObj.find('.turn_cons').html();

	 		$('form[name=turn] input[name=tid]').val(tid);	 		
	 	}

	 	$('.turn_main p').html(author + ' : ' + content);//最上面的信息条	 	

	 	//提取原微博ID
	 	$('form[name=turn] input[name=id]').val($(this).attr('id'));

	 	//----------------------------------------------------
	 	
	 	//隐藏表情框
	 	// $('#phiz').hide();

	 	//点击转发创建透明背景层
	 	createBg('opacity_bg');
	 	//定位转发框居中
	 	var turnLeft = ($(window).width() - $('#turn').width()) / 2;
	 	var turnTop = $(document).scrollTop() + ($(window).height() - $('#turn').height()) / 2;
	 	$('#turn').css({
	 		'left' : turnLeft,
	 		'top' : turnTop
	 	}).fadeIn().find('textarea').focus(function () {
	 		$(this).css('borderColor', '#FF9B00').keyup(function () {
				// var content = $(this).val();
				// var lengths = check(content);  //调用check函数取得当前字数
				// //最大允许输入140个字
				// if (lengths[0] >= 140) {
				// 	$(this).val(content.substring(0, Math.ceil(lengths[1])));
				// }
				// var num = 140 - Math.ceil(lengths[0]);
				// var msg = num < 0 ? 0 : num;
				// //当前字数同步到显示提示
				// $('#turn_num').html(msg);
			});
	 	}).focus().blur(function () {
	 		$(this).css('borderColor', '#CCCCCC');	//失去焦点时还原边框颜色
	 	});
	 });
	drag($('#turn'), $('.turn_text'));  //拖拽转发框	

	/**
	 * 替换微博内容，去除 <a> 链接与表情图片
	 */
	function replace_weibo (content) {

		//表情
		// content = content.replace(/<img.*?title=['"](.*?)['"].*?\/?>/ig, '[$1]');
		
		// url地址
		content = content.replace(/<a.*?>(.*?)<\/a>/ig, '$1');

		return content.replace(/<span.*?>\&nbsp;(\/\/)\&nbsp;<\/span>/ig, '$1');
	}	

	/**
	 * 点击评论，出现评论框
	 */
	$(".comment").click(function(){
	  
		var commentLoad = $(this).parents('.wb_main').find('.comment_load');
		var commentList = $(this).parents('.wb_main').find('.comment_list');

		// commentLoad.css('display','block');
		// commentList.css('display','block');
		//  ==
		// commentLoad.show();
		// commentList.show();

		//提取当前评论按钮对应微博的ID号
		var wid = $(this).attr('wid');

		//异步提取评论内容
		$.ajax({
			url : getComment,
			data : {
				wid : wid,
				_token : token
			},
			dataType : 'html',
			type : 'post',
			beforeSend : function () {
				commentLoad.show();
			},
			success : function (data) {
				if (data != 'false') {
					commentList.append(data);
				}
			},
			complete : function () {
				commentLoad.hide();
				commentList.show().find('textarea').val('').focus();
			}
		});
		// ==
		// commentLoad.show();
		// $.post(getComment, {
		// 		wid : wid,
		// 		_token : token
		// 	}, function (data) {
		// 		if (data != 'false') {
		// 			commentList.append(data);
		// 		}
		// 		commentLoad.hide();
		// 		commentList.show().find('textarea').val('').focus();				
		// }, 'html');		
	});	

	//回复
	$('body').on('click','.reply a', function () {
		var reply = $(this).parent().siblings('a').html();
		$(this).parents('.comment_list').find('textarea').val('回复@' + reply + ' ：');
		return false;
	});

	//提交评论
	$('.comment_btn').click(function () {
		var commentList = $(this).parents('.comment_list');
		var _textarea = commentList.find('textarea');
		var content = _textarea.val();

		//评论内容为空时不作处理
		if (content == '') {
			_textarea.focus();
			return false;
		}

		//提取评论数据
		var cons = {
			content : content,
			wid : $(this).attr('wid'),
			// uid : $(this).attr('uid'),
			isturn : $(this).prev().find('input:checked').val() ? 1 : 0,
			_token : token
		};

		$.post(comment, cons, function (data) {
			if (data != 'false') {
				if (cons.isturn) {//同时转发到我的微博
					$("body").scrollTop(0);
					window.location.reload();
				} else {//只是评论
					_textarea.val('');
					commentList.find('ul').after(data);
				}
			} else {
				alert('评论失败，请稍后重试');
			}
		}, 'html');
	});	

	/**
	 * 评论异步分类处理
	 */
	$('body').on('click','.page',function () {
		var commentList = $(this).parents('.comment_list');
		var commentLoad = commentList.prev();
		var wid = $(this).attr('wid');
		var page = $(this).attr('page');
		//异步提取评论内容
		$.ajax({
			url : getComment,
			data : {wid : wid, page : page,_token : token},
			dataType : 'html',
			type : 'post',
			beforeSend : function () {
				commentList.hide().find('dl').remove();
				commentLoad.show();
			},
			success : function (data) {
				if (data != 'false') {
					commentList.append(data);
				}
			},
			complete : function () {
				commentLoad.hide();
				commentList.show().find('textarea').val('').focus();
			}
		});
	});	

	/**
	 * 收藏微博
	 */
	$('.keep').click(function () {
		var wid = $(this).attr('wid');
		var keepUp = $(this).parent().prev().find('.keep-up');
		var msg = '';

		$.post(keep, {wid : wid,_token : token}, function (data) {
			if (data == 1) {
				msg = '收藏成功';
			}

			if (data == -1) {
				msg = '已收藏';
			}

			if (data == 0) {
				msg = '收藏失败';
			}

			keepUp.html(msg).fadeIn();
			setTimeout(function () {
				keepUp.fadeOut();
			}, 3000);

		}, 'json');
		
	});	

	/**
	 * 删除微博
	 */
	$('.wb_main').hover(function () {
		$(this).find('.del').show().css('color','#2B96E1').css('cursor','pointer');
	}, function () {
		$(this).find('.del').hide();
	});	
	$('.del').click(function () {
		var wid = $(this).attr('wid');
		var isDel = confirm('确认要删除该微博？');
		var obj = $(this).parents('.wb_main');

		if (isDel) {
			$.post(delWeibo, {wid : wid,_token:token}, function (data) {
				if (data) {
					obj.slideUp('slow', function () {
						obj.remove();
						window.location.reload();
					});
				} else {
					alert('删除失败请重试...');
				}
			}, 'json');
		}
	});	

	//搜索切换
	$('.sech-type').click(function () {
		$('.cur').removeClass('cur').addClass('click');
		$(this).removeClass('click').addClass('cur');
		$('form[name=search]').attr('action', $(this).attr('url'));
	});

	//消息推送回调函数
	get_msg(getMsgUrl);
});

/********************效果函数********************/

/**
 * 异步轮询函数
 */
function get_msg (url) {

	$.getJSON(url, function (data) {
		if (data.status) {

		   news({
				"total" : data.total,
				"type" : data.type
		   });
		}
		setTimeout(function () {
			get_msg(url);
		}, 5000);
	});
}

/**
 * 推送的新消息
 * @param  {[type]} json {total:新消息的条数,type:（1：评论，2：私信，3：@我）}
 * @return {[type]}      [description]
 */
var flags = true;//一打开该页面才启动的一个开关
function news (json) {
	switch (json.type) {
		case 1:
			$('#news ul .news_comment').show().find('a').html(json.total + '条新评论');
			break;
		case 2:
			$('#news ul .news_letter').show().find('a').html(json.total + '条新私信');
			break;
		case 3:
			$('#news ul .news_atme').show().find('a').html(json.total + '条@提到我');
			break;
	}
	var obj = $('#news');
	var icon = obj.find('i');
	obj.show().find('li').hover(function () {  //下拉项添加效果
		$(this).css('background', '#DCDCDC');
	}, function () {
		$(this).css('background', 'none');
	}).click(function () {
		clearInterval(newsGlint);
	});
	if (flags) {
		flags = false;
		var newsGlint = setInterval(function () {
			icon.toggleClass("icon-news");
		}, 500);
	}
}

/**
* 元素拖拽
* @param  obj		拖拽的对象
* @param  element 	触发拖拽的对象
*/
function drag (obj, element) {
	var DX, DY, moving;
	element.mousedown(function (event) {
		DX = event.pageX - parseInt(obj.css('left'));	//鼠标距离事件源宽度
		DY = event.pageY - parseInt(obj.css('top'));	//鼠标距离事件源高度
		moving = true;	//记录拖拽状态
	});
	$(document).mousemove(function (event) {
		if (!moving) return;
		var OX = event.pageX, OY = event.pageY;	//移动时鼠标当前 X、Y 位置
		var	OW = obj.outerWidth(), OH = obj.outerHeight();	//拖拽对象宽、高
		var DW = $(window).width(), DH = $('body').height();  //页面宽、高
		var left, top;	//计算定位宽、高
		left = OX - DX < 0 ? 0 : OX - DX > DW - OW ? DW - OW : OX - DX;
		top = OY - DY < 0 ? 0 : OY - DY > DH - OH ? DH - OH : OY - DY;
		obj.css({
			'left' : left + 'px',
			'top' : top + 'px'
		});
	}).mouseup(function () {
		moving = false;	//鼠标抬起消取拖拽状态
	});
}

/**
 * 创建全屏透明背景层
 * @param   id
 */
function createBg (id) {
	$('<div id = "' + id + '"></div>').appendTo('body').css({
 		'width' : $(document).width(),
 		'height' : $(document).height(),
 		'position' : 'absolute',
 		'top' : 0,
 		'left' : 0,
 		'z-index' : 2,
 		'opacity' : 0.3,
 		'filter' : 'Alpha(Opacity = 30)',
 		'backgroundColor' : '#000'
 	});
}

/**操作成功效果**/
function showTips(tips,time,height){
  var windowWidth = $(window).width();height=height?height:$(window).height();
  time = time ? time : 1;
  var tipsDiv = '<div class="tipsClass">' + tips + '</div>';
  $( 'body' ).append( tipsDiv );
  $( 'div.tipsClass' ).css({
     'top' : height/2 + 'px',
     'left' : ( windowWidth / 2 ) - 100 + 'px',
     'position' : 'absolute',
     'padding' : '3px 5px',
     'background': '#670768',
     'font-size' : 14 + 'px',
     'text-align': 'center',
     'width' : '300px',
     'height' : '40px',
     'line-height' : '40px',
     'color' : '#fff',
     'font-weight' : 'bold',
     'opacity' : '0.8'
  }).show();
  setTimeout( function(){
     $( 'div.tipsClass' ).animate({
        top: height/2-50+'px'
     }, "slow").fadeOut();
  }, time * 1000);
}	