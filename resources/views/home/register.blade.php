@extends('layouts.home')

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

		<form action="" method="" name="register">

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
					<input type="password" name="pwded">
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