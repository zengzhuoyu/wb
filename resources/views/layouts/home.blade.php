<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	@yield('title')
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/my.css')}}">
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/my.js')}}"></script>
</head>
<body>
	@yield('content')
</body>
</html>