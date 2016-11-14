@extends('layouts.home')

@section('title')
	<title>登 录</title>
@endsection

@section('content')

	<div class="container">
		
		<div class="row text-center">
			
			<div class="col-md-6 pull-right text-left">
				还没有账号?
				<a href="{{url('register')}}" class="btn">立即注册</a>
			</div>
		</div>

		<form action="" method="">
		
			<div class="row">
				
				<div class="col-md-6 text-right">
					账号：
				</div>

				<div class="col-md-6 text-left">
					<input type="text">
				</div>			
			</div>

			<div class="row">
				
				<div class="col-md-6 text-right">
					密码：
				</div>

				<div class="col-md-6 text-left">
					<input type="text">
				</div>			
			</div>		

			<div class="row text-center">
				
				<div class="col-md-6 pull-right text-left">
					<input type="checkbox" checked="checked"> &nbsp;下次自动登录
				</div>		
			</div>		

			<div class="row text-center">
				
				<div class="col-md-6 pull-right text-left">
					<input type="submit" value='马上登录' class="btn">
				</div>		
			</div>
		</form>			
	</div>

@endsection