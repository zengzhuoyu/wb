$(function () {

   // $('.c-reply').toggle (function () {
   //    var name = $(this).parents('.comment_main').find('.username').html();
   //    var str = '回复@' + name.replace(/：$/, ' ') + '：';
   //    $(this).parents('.comment_main').find(".comment_list").show().find('textarea').val(str);
   // }, function () {
   //    $(this).parents('.comment_main').find(".comment_list").hide();
   // });

   // =>改写
   $('.c-reply').click(function(){

      var name = $(this).parents('.comment_main').find('.username').html();
      var str = '回复@' + name.replace(/：$/, ' ') + '：';

      var comment_list = $(this).parents('.comment_main').find(".comment_list");

      comment_list.toggle().find('textarea').val(str).focus();
   });

   //回复按钮
   $('.reply_btn').click(function () {

      var comment_list = $(this).parents('.comment_list');

      var data = {
         wid : $(this).attr('wid'),
         content : comment_list.find('textarea').val(),
         _token : token
      };

      $.post(reply, data, function (data) {
         if (data) {
            alert('评论已回复');
            comment_list.hide();
         } else {
            alert('回复失败请重试...');
         }
      }, 'json');
   });

   //删除评论
   $('.del-comment').click(function () {
      var data = {
         cid : $(this).attr('cid'),
         wid : $(this).attr('wid'),
         _token : token
      };
      var isDel = confirm('确定删除该评论？');
      var obj = $(this).parents('.comment_main');

      if (isDel) {
         $.post(delComment, data, function (data) {
            if (data) {
               obj.slideUp('slow', function () {
                  obj.remove();
               });
            } else {
               alert('删除失败请重试...');
            }
         }, 'json');
      }
   });


   /**
     * 表情处理
     * 以原生JS添加点击事件，不走jQuery队列事件机制
     */
   // var phiz = $('.phiz');
   // for (var i = 0; i < phiz.length; i++) {
   //    phiz[i].onclick = function () {
   //       //定位表情框到对应位置
   //       $('#phiz').show().css({
   //          'left' : $(this).offset().left,
   //          'top' : $(this).offset().top + $(this).height() + 5
   //       });
   //       //为每个表情图片添加事件
   //          var phizImg = $("#phiz img");
   //          var sign = this.getAttribute('sign');
   //          for (var i = 0; i < phizImg.length; i++){
   //             phizImg[i].onclick = function () {
   //          var content = $('textarea[sign = '+sign+']');
   //          content.val(content.val() + '[' + $(this).attr('title') + ']');
   //          $('#phiz').hide();
   //             }
   //          }
   //    }
   // }
   
   //关闭表情框
   // $('.close').hover(function () {
   //    $(this).css('backgroundPosition', '-100px -200px');
   // }, function () {
   //    $(this).css('backgroundPosition', '-75px -200px');
   // }).click(function () {
   //    $(this).parent().parent().hide();
   //    $('#phiz').hide();
   //    if ($('#turn').css('display') == 'none') {
   //       $('#opacity_bg').remove();
   //    };
   // });
   
});