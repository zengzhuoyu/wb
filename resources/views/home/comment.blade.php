@extends('layouts.home')

@section('title')
  <title>评 论</title>
@endsection

@section('content')

<div class="container">

  <div class="row row-offcanvas row-offcanvas-right">
    
    @parent
    
    <div class="col-xs-12 col-sm-7">

        <div class="row">
            
            <div class="col-xs-6 col-lg-12 text-center">
                - - 评 论 - -    
            </div>            

        </div>

        <div class="row">
            <div class="col-xs-6 col-lg-12">
                我的评论 (共 {{count($data)}} 条)
            </div>
        </div>

        @if(count($data) > 0)

            @foreach($data as $v)

                <div class="row comment_main">
                    <div class="col-xs-6 col-lg-2">
                        <a href="{{url('userInfo/' . $v -> uid)}}"><img src="
                            @if($v -> face)
                                {{$v  -> face}}
                            @else
                                /bootstrap/img/noface.gif              
                            @endif
                        " alt="" width="50" height="50"></a>
                    </div>
                    <div class="col-xs-6 col-lg-7">
                        <div><a href="{{url('userInfo/' . $v -> uid)}}" class="username">{{$v -> username}}</a> : {{$v -> content}}</div>
                        <br>
                        <div>
                            <span>{{time_format($v -> time)}}</span>
                            <span class="pull-right"><span class="del-comment" cid="{{$v -> id}}" wid="{{$v -> wid}}">删除</span> | <span class='c-reply'>回复</span></span>
                        </div>

                        <!--=====回复框结束=====-->
                        <div class='comment_list' style="display:none;">
                            <textarea name="" sign=''></textarea>
                            <ul>
                                <li class='phiz fleft' sign=''></li>
                                <li class='reply_btn fright' wid='{{$v -> wid}}'>回复</li>
                            </ul>
                        </div>
                        <!--=====回复框结束=====-->            

                    </div>
                </div>

            @endforeach

            <div class="row text-center">
                <div class="col-xs-6 col-lg-12">
                    {{$data->links()}}                
                </div>
            </div>

        @else

            没有评论

        @endif

    </div>

  </div><!--/row-->

</div><!--/.container-->

@endsection
