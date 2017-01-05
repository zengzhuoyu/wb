<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="/admin/css/ch-ui.admin.css">
    <link rel="stylesheet" href="/admin/font/css/font-awesome.min.css">
    <script type="text/javascript" src="/admin/js/jquery.js"></script>
    <script type="text/javascript" src="/admin/js/ch-ui.admin.js"></script>
    <script type="text/javascript" src="/admin/js/layer.js"></script>
</head>
<body>
        <!--面包屑导航 开始-->
<div class="crumb_warp">
    <!--<i class="fa fa-bell"></i> 欢迎使用登陆网站后台，建站的首选工具。-->
    <i class="fa fa-home"></i> <a href="{{url('admin/info')}}">首页</a> &raquo; 系统基本信息
</div>
<!--面包屑导航 结束-->


<div class="result_wrap">
    <div class="result_title">
        <h3>个人信息</h3>
    </div>
    <div class="result_content">
        <ul>
            <li>
                <label>上一次登录时间</label><span>{{$_SESSION['logintime']}}</span>
            </li>        
            <li>
                <label>上一次登录ip</label><span>{{$_SESSION['loginip']}}</span>
            </li>
            <li>
                <label>本次登录时间</label><span>{{$_SESSION['nowtime']}}</span>
            </li>                
            <li>
                <label>本次登录ip</label><span>{{$_SESSION['nowip']}}</span>
            </li>            
        </ul>
    </div>
</div>

<div class="result_wrap">
    <div class="result_title">
        <h3>服务器信息</h3>
    </div>
    <div class="result_content">
        <ul>    
            <li>
                <label>操作系统</label><span>{{PHP_OS}}</span>
            </li>                    
            <li>
                <label>服务器环境</label><span>{{$_SERVER['SERVER_SOFTWARE']}}</span>
            </li>    
            <li>
                <label>服务器域名/IP</label><span>{{$_SERVER['SERVER_NAME']}} [ {{$_SERVER['SERVER_ADDR']}} ]</span>
            </li>            
        </ul>
    </div>
</div>

<div class="result_wrap">
    <div class="result_title">
        <h3>用户信息</h3>
    </div>
    <div class="result_content">
        <ul>    
            <li>
                <label>共有注册用户</label><span>{{$user}}</span>
            </li>                    
            <li>
                <label>被锁定用户</label><span>{{$lock}}</span>
            </li>    
        </ul>
    </div>
</div>

<div class="result_wrap">
    <div class="result_title">
        <h3>微博信息</h3>
    </div>
    <div class="result_content">
        <ul>    
            <li>
                <label>原作微博</label><span>{{$wb}}</span>
            </li>                    
            <li>
                <label>转发微博</label><span>{{$isturn}}</span>
            </li>    
            <li>
                <label>评论条数</label><span>{{$comment}}</span>
            </li>             
        </ul>
    </div>
</div>

<div class="result_wrap">
    <div class="result_title">
        <h3>版权信息</h3>
    </div>
    <div class="result_content">
        <ul>    
            <li>
                <label>版权所有</label><span>后盾网 京ICP备10027771号-1</span>
            </li>                    
            <li>
                <label>系统开发</label><span>黄永成</span>
            </li>            
        </ul>
    </div>
</div>

<!--结果集列表组件 结束-->
