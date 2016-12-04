<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	@yield('title')
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/my.css')}}">
           <link rel="stylesheet" type="text/css" href="{{asset('org/uploadify/uploadify.css')}}">	
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>	
	<script src="{{asset('bootstrap/js/jquery.validate.min.js')}}"></script>	
	<script src="{{asset('bootstrap/js/city.js')}}"></script>	
	@yield('script')	
           <script src="{{asset('org/uploadify/jquery.uploadify.min.js')}}" type="text/javascript"></script>
	<script src="{{asset('bootstrap/js/edit.js')}}"></script>        		
	<script src="{{asset('bootstrap/js/left.js')}}"></script>        		
	<script src="{{asset('bootstrap/js/follow.js')}}"></script>        		
	<script src="{{asset('bootstrap/js/index.js')}}"></script>        		
</head>
<body>

	<!-- Fixed navbar -->
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
	        <span class="sr-only">Toggle navigation</span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="#">Project name</a>
	    </div>
	    <div id="navbar" class="navbar-collapse collapse">
	      <ul class="nav navbar-nav">
	        <li class=""><a href="/">首 页</a></li>
	        <li><a href="#about">About</a></li>
	        <li><a href="#contact">Contact</a></li>
	      </ul>      
	      <form class="navbar-form navbar-left" method="get" action="{{url('searchUser')}}">
	        <input type="text" class="form-control" placeholder="搜索微博、找人" name="k"
		@if(isset($k) && !empty($k))
			value="{{$k}}"										
		@endif
	        >
	        <button type="submit" class="btn btn-success">搜 索</button>
	      </form>
	      <ul class="nav navbar-nav navbar-right">
		<?php 
			$user = DB::table('userinfo')->where('uid',$_SESSION['uid'])->first();
	 	?>	      
	        <li><a href="{{url('userInfo/'.$_SESSION['uid'])}}">{{$user -> username}}</a></li>
	        <li><a href="{{url('userSet')}}">个人设置</a></li>
	        <li><a href="{{url('quit')}}">退 出</a></li>
	      </ul>
	    </div><!--/.nav-collapse -->
	  </div>
	</nav>        

	@section('content')

	<div class="col-xs-6 col-sm-2 sidebar-offcanvas" id="sidebar" role="navigation">
	  <div class="list-group">
	    <!-- <a href="#" class="list-group-item active">Link</a> -->
	    <a href="{{url('/')}}" class="list-group-item">首 页</a>
	    <a href="#" class="list-group-item">提到我的</a>
	    <a href="#" class="list-group-item">评 论</a>
	    <a href="#" class="list-group-item">私 信</a>
	    <a href="#" class="list-group-item">收 藏</a>
	    <?php 
	    	$group = DB::table('group')->where('uid',$_SESSION['uid'])->get();
	     ?>
	    <a href="javascript:;" class="list-group-item active">分 组</a>
	    <a href="{{url('/')}}" class="list-group-item">全 部</a>
	    @foreach($group as $v)	            				
		<a href="{{url($v -> id)}}" class="list-group-item">{{$v -> name}}</a>					
	    @endforeach	    
	    <a href="#" class="btn" id="create_group">创建新分组</a>
	  </div>
	</div><!--/.sidebar-offcanvas-->

	<!--==========创建分组==========-->
	<script type='text/javascript'>
	    var addGroup = "{{url('addGroup')}}";
	    var token = "{{csrf_token()}}";	    
	</script>	
	<div id='add-group'>
	    <div class="group_head">
	        <span class='group_text fleft'>创建好友分组</span>
	    </div>
	    <div class='group-name'>
	        <span>分组名称：</span>
	        <input type="text" name='name' id='gp-name' placeholder=" 在15位以内">
	    </div>
	    <div class='gp-btn-wrap'>
	        <span class='add-group-sub'>添加</span>
	        <span class='group-cencle'>取消</span>
	    </div>
	</div>
	<!--==========创建分组==========-->

	<div class="col-xs-6 col-sm-3 sidebar-offcanvas pull-right" id="sidebar" role="navigation">
	<?php 
		$field = ['username','face80 as face','follow','fans','wb','uid'];
		$userinfo = DB::table('userinfo') -> where('uid',$_SESSION['uid']) -> select($field) -> first();
	 ?>
		<div class="row">
			<div class="col-xs-6 text-right">
				<a href="{{url('userInfo/'.$userinfo -> uid)}}"><img src="
				@if($userinfo -> face)
				{{$userinfo -> face}}
				@else
				bootstrap/img/noface.gif								
				@endif
				" alt="{{$userinfo -> username}}" width="80"></a>
			</div>
			<div class="col-xs-6">
				<a href="{{url('userInfo/'.$userinfo -> uid)}}">{{$userinfo -> username}}</a>
			</div>			
		</div>
		<div class="row">
			<div class="col-xs-12 text-center">
				<span>关注 {{$userinfo -> follow}}</span><span> 粉丝 {{$userinfo -> fans}}</span><span> <a href="{{url('userInfo/'.$userinfo -> uid)}}">微博</a> {{$userinfo -> wb}}</span>
			</div>		
		</div>		
	</div>

	@show

	<footer class="text-center">
	  <p>&copy; Company 2016</p>
	</footer>	

</body>
</html>