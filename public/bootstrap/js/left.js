$(function () {

    //创建好友分组
   $('#create_group').click(function () {
   		var groupLeft = ($(window).width() - $('#add-group').width()) / 2;
	 	var groupTop = $(document).scrollTop() + ($(window).height() - $('#add-group').height()) / 2;
   		var gpObj = $('#add-group').show().css({
	 		'left' : groupLeft,
	 		'top' : groupTop
	 	});
   		createBg('group-bg');
   		drag(gpObj, gpObj.find('.group_head'));
   });

   //异步创建分组
   $('.add-group-sub').click(function(){
   	var groupName = $('#gp-name').val();
   	if(groupName != ''){
   		$.post(
   			addGroup,
   			{
   				name : groupName,
                                 _token : token                                 
   			},
   			function(data){
   				if(data.status){
	   				alert(data.msg);
	   				$('#add-group').hide();
                                         $('#group-bg').remove();//全屏透明背景层消除
   				}else{
   					alert(data.msg);
   				}
  				
   			}
   		,'json');
   	}
   });

   //关闭
   $('.group-cencle').click(function () {
   		$('#add-group').hide();
   		$('#group-bg').remove();
   });   

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

});