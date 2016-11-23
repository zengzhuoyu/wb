$(function () {

    //好友关注
   $('.add-fl').click(function () {
         var followLeft = ($(window).width() - $('#follow').width()) / 2;
         var followTop = $(document).scrollTop() + ($(window).height() - $('#follow').height()) / 2;
         var flObj = $('#follow').show().css({
         'left' : followLeft,
         'top' : followTop
      });
         createBg('follow-bg');
         drag(flObj, flObj.find('.follow_head'));
         $('input[name=follow]').val($(this).attr('uid'));
         $.post(//获得自己建过的分组
            getGroup,
            {_token : token},
            function(data){
               if(data.status){
                  $("select[name='gid']").html('<option value="0">默认分组</option>');

                  var html = '';
                  $.each(data.data,function(i,v){
                      html += "<option value=" + v.id + ">" + v.name + "</option>";
                  });

                  $("select[name='gid']").append(html);                  
               }
            }
         ,'json');         
   });

   //添加关注
   $('.add-follow-sub').click(function () {
         var follow = $('input[name=follow]').val();//所关注的人的ID
         var group = $('select[name=gid]').val();//添加进的组别的ID
         $.post(addFollow, {
            follow : follow,
            gid : group,
            _token : token
         }, function (data) {
            if (data.status) {
               var obj = $('.add-fl[uid=' + follow + ']').parent();
               obj.find('.add-fl[uid=' + follow + ']').remove();
               if(data.mutual){
                  obj.append('<span>互相关注</span> | <button>移除关注</button>');
               }else{
                  obj.append('<span>√ 已关注</span> | <button>移除关注</button>');                  
               }
               $('#follow').hide();
               $('#follow-bg').remove();
            } else {
               alert(data.msg);
            }
         }, 'json');
   });

   //关闭关注框
   $('.follow-cencle').click(function () {
         $('#follow').hide();
         $('#follow-bg').remove();
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

});