<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	@yield('title')
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/my.css')}}">
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>	
	<script src="{{asset('bootstrap/js/city.js')}}"></script>	
	@yield('script')	
	<script src="{{asset('bootstrap/js/editbasic.js')}}"></script>	
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
	            <li class="dropdown">
	              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Dropdown <span class="caret"></span></a>
	              <ul class="dropdown-menu" role="menu">
	                <li><a href="#">Action</a></li>
	                <li><a href="#">Another action</a></li>
	                <li><a href="#">Something else here</a></li>
	                <li class="divider"></li>
	                <li class="dropdown-header">Nav header</li>
	                <li><a href="#">Separated link</a></li>
	                <li><a href="#">One more separated link</a></li>
	              </ul>
	            </li>
	          </ul>
	          <ul class="nav navbar-nav navbar-right">
	            <li><a href="../navbar/">Default</a></li>
	            <li><a href="{{url('userSet')}}">个人设置</a></li>
	            <li><a href="{{url('quit')}}">退 出</a></li>
	          </ul>
	        </div><!--/.nav-collapse -->
	      </div>
	    </nav>

	@yield('content')

	<footer class="text-center">
	  <p>&copy; Company 2016</p>
	</footer>	
</body>
</html>