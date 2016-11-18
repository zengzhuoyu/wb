@extends('layouts.loginregis')

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

		<form action="{{url('runLogin')}}" method="post" name="login">
		{{csrf_field()}}		
			<div class="row">
				
				<div class="col-md-6 text-right">
					账号：
				</div>

				<div class="col-md-6 text-left">
					<input type="text" name="account">
				</div>			
			</div>

			<div class="row">
				
				<div class="col-md-6 text-right">
					密码：
				</div>

				<div class="col-md-6 text-left">
					<input type="password" name="pwd">
				</div>			
			</div>		

			<div class="row text-center">
				
				<div class="col-md-6 pull-right text-left">
					<input type="checkbox" checked="checked" name="auto" id="auto">
					<label for="auto">7天内自动登录</label>
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