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

        <form action="/admin/userIndex" method="get">

            <select name="type">
                <option value="1">用户ID</option>     
                <option value="0"       
                @if($type == 0)
                    selected ='selected'    
                @endif 
                >用户昵称</option>     
            </select>
            <input type="text" name='keyword' value="@if(!$all){{$keyword}}@endif"/>
            <input type="submit" value='确定'/>    
            @if(isset($data) && count($data) > 0 && $keyword && !$all)
                <input type="submit" value='查看全部' name="all" value="3"/>
            @endif
                
        </form>

        <div class="result_wrap">
            <div class="result_content">
                <table class="list_tab">

                    @if(isset($data) && count($data) > 0)

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
                                            <a href="#" class="lock" lock="0" uid="{{$v->id}}">解除锁定</a>
                                        @else
                                            <a href="#" class="lock" lock="1" uid="{{$v->id}}">锁定用户</a>  
                                        @endif                              
                                </td>
                            </tr>

                        @endforeach

                    @else

                        <tr>
                            <td style="text-align:center">没有检索到相关用户</td>
                        </tr>  

                    @endif



                </table>

                <div class="page_list">
                    {{$data->links()}}
                </div>
            </div>
        </div>

    <!--搜索结果页面 列表 结束-->

<style>
    .result_content ul li span {
        font-size: 15px;
        padding: 6px 12px;
    }

    form{
        text-align:center;
        margin:20px 0 10px 0;
    }
</style>

<script>

    var token = "{{csrf_token()}}";

    $(function() { 

        $(".lock").click(function(){
            
            var isLock = confirm($(this).html() + '?');
            if(!isLock) return;  

            var datas = {
                lock : $(this).attr('lock'),
                _token : token,
                uid : $(this).attr('uid')
            };

            $.post(
                "lockUser",
                datas,
                function (data) {

                if(data.status == 1){   
                    window.location.reload();
                }

                alert(data.msg);
            });             

        });
    });    
</script>

</body>
</html>