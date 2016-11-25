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

	   					$('#pic-show').fadeOut();
	   					$('#upload_img').fadeOut();
	                                          		showTips(data.msg);       
	   					$('textarea[name="content"]').val('');
	   					$('input[name="max"]').val('');
	   					$('input[name="medium"]').val('');
	   					$('input[name="mini"]').val('');
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
});