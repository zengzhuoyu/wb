<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    	@yield('title')
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
	<link rel="stylesheet" type="text/css" href="{{asset('bootstrap/css/my.css')}}">
	<script src="{{asset('bootstrap/js/jquery.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
	<script src="{{asset('bootstrap/js/jquery.validate.min.js')}}"></script>	
	<script type='text/javascript'>
		var checkAccount = "{{url('checkAccount')}}";
		var checkUname = "{{url('checkUname')}}";
		var checkVerify = "{{url('checkVerify')}}";
		var token = "{{csrf_token()}}";
	</script>	
	<script src="{{asset('bootstrap/js/my.js')}}"></script>
</head>
<body>
	@yield('content')
</body>
</html>