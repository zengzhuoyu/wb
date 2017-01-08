<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="/admin/css/ch-ui.admin.css">
        <link rel="stylesheet" href="/admin/font/css/font-awesome.min.css">
        <script type="text/javascript" src="/admin/js/jquery.js"></script>
        <script type="text/javascript" src="/admin/js/ch-ui.admin.js"></script>
</head>
<body>

    <!--搜索结果页面 列表 开始-->
    <form action="#" method="post">

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">
                    <tr>
                        <th class="tc">ID</th>
                        <th>用户昵称</th>
                        <th>头像</th>
                        <th>关注信息</th>
                        <th>注册时间</th>
                        <th>账号状态</th>
                        <th>操作</th>
                    </tr>
                    
                    @foreach($data as $v)                                

                        <tr>
                            <td class="tc">{{$v->id}}</td>
                            <td class="tc">{{$v->username}}</td>
                            <td class="tc">
                                <img src="
                                    @if($v->face)
                                        {{url($v->face)}}
                                    @else
                                        /bootstrap/img/noface.gif               
                                    @endif      
                                " alt="" width="50" height="50">                          
                            </td>
                            <td>
                                <span>关注：{{$v->follow}} | </span>
                                <span>粉丝：{{$v->fans}} | </span>
                                <span>微博：{{$v->wb}}</span>
                            </td>
                            <td>{!! date('Y-m-d',$v->registime) !!}</td>
                            <td>
                                    @if($v->lock)
                                        锁定    
                                    @endif                                   
                            </td>
                            <td>
                                    @if($v->lock)
                                        <a href="#">解除锁定</a>
                                    @else
                                        <a href="#">锁定用户</a>  
                                    @endif                              
                            </td>
                        </tr>

                    @endforeach

                </table>

                <div class="page_list">
                    {{$data->links()}}
                </div>
            </div>
        </div>
    </form>
    <!--搜索结果页面 列表 结束-->

<style>
    .result_content ul li span {
        font-size: 15px;
        padding: 6px 12px;
    }
</style>

</body>
</html>