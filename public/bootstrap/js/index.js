$(function () {

	//显示图片上传框
	$('.icon-picture').click(function () {
		// $('#phiz').hide();
		$('#upload_img').show();
	});

	//显示图片上传框
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
	   					setTimeout("location.reload()",2000);
	   					// window.location.reload();
	   				}else{
	   					alert(data.msg);
	   				}
	  				
	   			}
	   		,'json');
	   	}		
	});	

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

	 	// //提取原微博ID
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

	/**
	 * 替换微博内容，去除 <a> 链接与表情图片
	 */
	function replace_weibo (content) {

		//表情
		// content = content.replace(/<img.*?title=['"](.*?)['"].*?\/?>/ig, '[$1]');
		
		// url地址
		content = content.replace(/<a.*?>(.*?)<\/a>/ig, '$1');

		return content;
		// return content.replace(/<span.*?>\&nbsp;(\/\/)\&nbsp;<\/span>/ig, '$1');
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
});