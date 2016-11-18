@extends('layouts.loginregis')

@section('title')
	<title>注 册</title>
@endsection

@section('content')

	<div class="container">

		<div class="row text-center">
			
			<div class="col-md-6 pull-right text-left">
				已有账号?
				<a href="{{url('login')}}" class="btn">登录</a>
			</div>
		</div>
		
	            @if(count($errors)>0)
			<div class="row text-center">
				<div class="col-md-6 pull-right text-left">
			                    @if(is_object($errors))
			                        <!-- withErrors -->
			                        @foreach($errors->all() as $error)
			                            <span class="error">{{$error}}</span>
			                        @endforeach
			                    @else
			                        <!-- 密码修改成功 + 原密码错误 -->                
			                        <span class="error">{{$errors}}</span>
			                    @endif
				</div>
			</div>		                    
	            @endif  		

		<form action="{{url('runRegis')}}" method="post" name="register">
			{{csrf_field()}}
			<div class="row">
				
				<div class="col-md-6 text-right">
					用户账号：
				</div>

				<div class="col-md-6 text-left">
					<input type="text" name="account" id="account">
				</div>			
			</div>

			<div class="row">
				
				<div class="col-md-6 text-right">
					密码：
				</div>

				<div class="col-md-6 text-left">
					<input type="password" name="pwd" id="pwd">
				</div>			
			</div>			

			<div class="row">
				
				<div class="col-md-6 text-right">
					确认密码：
				</div>

				<div class="col-md-6 text-left">
					<input type="password" name="password_confirmation">
				</div>			
			</div>	

			<div class="row">
				
				<div class="col-md-6 text-right">
					昵称：
				</div>

				<div class="col-md-6 text-left">
					<input type="text" name="uname" id="uname">
				</div>			
			</div>	

			<div class="row">
				
				<div class="col-md-6 text-right">
					验证码：
				</div>

				<div class="col-md-6 text-left">
					<input type="text" size="3" name="verify" maxlength="4" id='verify'>
					<img src="{{url('getVerify')}}" id='verify-img'>					
				</div>			
			</div>	

			<div class="row text-center">
				
				<div class="col-md-6 pull-right text-left">
					<input type="submit" value='马上注册' class="btn">
				</div>		
			</div>		

		</form>		
	</div>

@endsection