$(function () {

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
   * 元素拖拽
   * @param  obj     拖拽的对象
   * @param  element    触发拖拽的对象
   */
   function drag (obj, element) {
      var DX, DY, moving;
      element.mousedown(function (event) {
         DX = event.pageX - parseInt(obj.css('left'));   //鼠标距离事件源宽度
         DY = event.pageY - parseInt(obj.css('top')); //鼠标距离事件源高度
         moving = true; //记录拖拽状态
      });
      $(document).mousemove(function (event) {
         if (!moving) return;
         var OX = event.pageX, OY = event.pageY;   //移动时鼠标当前 X、Y 位置
         var   OW = obj.outerWidth(), OH = obj.outerHeight();  //拖拽对象宽、高
         var DW = $(window).width(), DH = $('body').height();  //页面宽、高
         var left, top; //计算定位宽、高
         left = OX - DX < 0 ? 0 : OX - DX > DW - OW ? DW - OW : OX - DX;
         top = OY - DY < 0 ? 0 : OY - DY > DH - OH ? DH - OH : OY - DY;
         obj.css({
            'left' : left + 'px',
            'top' : top + 'px'
         });
      }).mouseup(function () {
         moving = false;   //鼠标抬起消取拖拽状态
      });
   }

	//发送私信框
   $('.l-reply,.send').click(function () {
      var username = '';

      if ($(this).attr('class') == 'l-reply') {//把要回复的对象的名称放到输入框里
         username = $(this).parents('.letter_main').find('.username').html();
      }

   	var letterLeft = ($(window).width() - $('#letter').width()) / 2;
	 	var letterTop = $(document).scrollTop() + ($(window).height() - $('#letter').height()) / 2;
		var obj = $('#letter').show().css({
	 		'left' : letterLeft,
	 		'top' : letterTop
	 	});

               obj.find('input[name=name]').val(username);
               obj.find('textarea').focus();
		createBg('letter-bg');
		drag(obj, obj.find('.letter_head'));
   });
   //关闭
   $('.letter-cencle').click(function () {
   		$('#letter').hide();
   		$('#letter-bg').remove();
   });

   /**
    * 删除私信
    */
   $('.del-letter').click(function () {
      var isDel = confirm('确定删除该私信？');
      var lid = $(this).attr('lid');
      var obj = $(this).parents('.letter_main');

      if (isDel) {
         $.post(delLetter, {lid : lid,_token : token}, function (data) {
            if (data) {
               obj.slideUp('slow', function () {
                  obj.remove();
               });
            } else {
               alert('删除失败重请试...');
            }
         }, 'json');
      }
   });
});