$(function(){

	//点击刷新验证码
	var verifyUrl = $('#verify-img').attr('src');
	$('#verify-img').click(function(){

		$(this).attr('src',verifyUrl + '?' + Math.random());
	});

	//jQuery Validate 表单验证
	
	/**
	 * 添加自定义验证方法
	 * 以字母开头，5-17 字母、数字、下划线"_"
	 */
	jQuery.validator.addMethod("user", function(value, element) {   
	    var tel = /^[a-zA-Z][\w]{4,16}$/;
	    return this.optional(element) || (tel.test(value));
	}, "以字母开头，5-17的字母、数字、下划线");

	$('form[name=register]').validate({
		errorElement : 'span',
		success : function (span) {
			span.addClass('success');
		},		
		rules : {
			account : {
				required : true,
				user : true,
				remote : {
					url : checkAccount,//发送地址
					type : 'post',//发送方式
					dataType : 'json',//发送数据类型
					data : {//发送的数据
						account : function () {
							return $('#account').val();
						},
						_token : token
					}
				}				
			},
			pwd : {
				required : true,
				user : true
			},
			password_confirmation : {
				required : true,
				equalTo : "#pwd"
			},
			uname : {
				required : true,
				rangelength : [2,10],
				remote : {
					url : checkUname,
					type : 'post',
					dataType : 'json',
					data : {
						uname : function () {
							return $('#uname').val();
						},
						_token : token						
					}
				}
			},
			verify : {
				required : true,
				remote : {
					url : checkVerify,
					type : 'post',
					dataType : 'json',
					data : {
						verify : function () {
							return $('#verify').val();
						},
						_token : token						
					}
				}
			}			
		},
		messages : {
			account : {
				required : '账号不能为空',
				remote : '账号已存在'				
			},
			pwd : {
				required : '密码不能为空'
			},
			password_confirmation : {
				required : '请确认密码',
				equalTo : '两次密码不一致'
			},
			uname : {
				required : '请填写您的昵称',
				rangelength : '昵称在2-10个字之间',
				remote : '昵称已存在'
			},
			verify : {
				required : ' ',
				remote : ' '
			}			
		}
	});
});