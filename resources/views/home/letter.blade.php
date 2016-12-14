@extends('layouts.home')

@section('title')
  <title>私 信</title>
@endsection

@section('content')

<div class="container">

  <div class="row row-offcanvas row-offcanvas-right">
    
    @parent
    
    <div class="col-xs-12 col-sm-7">

        <div class="row">
            
            <div class="col-xs-6 col-lg-12 text-center">
                - - 私 信 - -    
            </div>            

        </div>

        <div class="row">
            <div class="col-xs-6 col-lg-12">
                我的私信 (共 {{count($data)}} 条) <span class="send">发起私信</span>
            </div>
        </div>

                @if($errors)
                    <div class="row text-center">
                        <div class="col-md-6 pull-right text-left">
                                        @if(is_object($errors))
                                            <!-- withErrors -->
                                            @foreach($errors->all() as $error)
                                                <span class="err">{{$error}}</span>
                                            @endforeach
                                        @else
                                            <!-- 密码修改成功 + 原密码错误 -->                
                                            <span class="err">{{$errors}}</span>
                                        @endif
                        </div>
                    </div>                          
                @endif

        @if(count($data) > 0)

        @foreach($data as $v)

            <div class="row letter_main">
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
                        <span class="pull-right"><span class="del-letter" lid="{{$v -> id}}">删除</span> | <span class='l-reply'>回复</span></span>
                    </div>
                </div>
            </div>

        @endforeach

        <div class="row text-center">
            <div class="col-xs-6 col-lg-12">
                {{$data->links()}}                
            </div>
        </div>

        @else

            没有私信

        @endif

    </div>

  </div><!--/row-->

</div><!--/.container-->

<!--私信弹出框 - 开始-->
<div id='letter'>
    <form action='{{url("letterSend")}}' method='post'>
        {{csrf_field()}}
        <div class="letter_head">
            <span class='letter_text fleft'>发送私信</span>
        </div>
        <div class='send-user'>
            用户昵称：<input type="text" name='name'/>
        </div>
        <div class='send-cons'>
            内容：<textarea name="content"></textarea>
        </div>
        <div class='lt-btn-wrap'>
            <input type="submit" value='发送' class='send-lt-sub'/>
            <span class='letter-cencle'>取消</span>
        </div>
    </form>
</div>
<!--私信弹出框 - 结束-->

@endsection
