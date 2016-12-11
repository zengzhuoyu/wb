@extends('layouts.home')

@section('title')
  <title>个人信息页</title>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        <div class="col-xs-12 col-sm-12">
        
        <div class="row">
              <div class="col-lg-12">
                <img src="{{url('bootstrap/img/body_bg.jpg')}}" alt="" width="100%" height="100px">
              </div>
        </div>

        <div class="row">
              <div class="col-lg-2 text-right">
                <img src="
                  @if($userinfo -> face50)
                    {{url($userinfo -> face50)}}
                  @else
                    {{url('bootstrap/img/noface.gif')}}                                  
                  @endif                
                " alt="" width="80px" height="80px">
                <br><br>
                <span>关注 <a href="{{url('follow/'. $userinfo -> uid)}}">{{$userinfo -> follow}}</a> </span>
                <span>粉丝 <a href="{{url('fans/'. $userinfo -> uid)}}">{{$userinfo -> fans}}</a> </span>
                <span>微博 <a href="{{url('userInfo/'. $userinfo -> uid)}}">{{$userinfo -> wb}}</a></span>
              </div>
             <div class="col-lg-9">
                <div>{{$userinfo -> username}}</div><br>
                <div>{{$userinfo -> intro}}</div><br>
                <div><span style="display:block" class="
                  @if($userinfo -> sex == '男')
                    boy
                  @else
                    girl        
                  @endif
                "></span><span> {{$userinfo -> location}}</span><span> {{$userinfo -> constellation}}</span></div><br>
                @if($_SESSION['uid'] == $id)
                <div><a href="{{url('userSet')}}">修改个人资料</a></div>                                
                @endif                
              </div>
        </div>

        <br>
        
        @if($_SESSION['uid'] == $id)
        <div class="row">
          <div class="col-lg-8">
            <textarea cols="60" rows="5" name="content"></textarea>
            <div class="row">
              <div class="col-lg-1">
                <div class="emotion"></div>
              </div>
              <div class="col-lg-1">
                <div class="icon-picture"></div>
                <!--图片上传框-->
                    <div id="upload_img" style='display:none;'>
                        <div class='upload-title'><p>本地上传</p><span class='close'></span></div>
                        <input id="file_upload" name="file_upload" type="file" multiple="true">         
                        <script type="text/javascript">
                            var token = "{{csrf_token()}}";
                            var sendWeibo = "{{url('sendWeibo')}}";
                            var delWeibo = "{{url('delWeibo')}}";
                            <?php $timestamp = time();?>
                            $(function() {
                                $('#file_upload').uploadify({
                                    'buttonText' : '图片上传',
                                    'formData'     : {
                                        'type' : 'Weibo',
                                        'timestamp' : '<?php echo $timestamp;?>',
                                        '_token'     : "{{csrf_token()}}"
                                    },
                                    'swf'      : "{{asset('org/uploadify/uploadify.swf')}}",
                                    'uploader' : "{{url('/uploadFace')}}",
                                    'onUploadSuccess' : function(file, data, response) {
                                      $('#pic-show').fadeIn().find('img').attr('src', '/' + data);
                                      $('input[name=max]').val(data);
                                      $('input[name=medium]').val(data);
                                      $('input[name=mini]').val(data);                                    
                                    }
                                });
                            });
                        </script>      
                        <style>
                            .uploadify{display:inline-block;}
                            .uploadify-button{border:none; border-radius:5px; margin-top:8px;margin-left:35px;}
                            table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
                        </style>                             
                    </div>
                <!--图片上传框-->
                <div id='pic-show' style="display:none;">
                    <img src="" alt="" width="180" height="180"/>
                    <input type="hidden" name='max' value=''/>
                    <input type="hidden" name='medium' value=''/>
                    <input type="hidden" name='mini' value=''/>                    
                </div>                                           
              </div>               
              <div class="col-xs-6 col-lg-4 text-right">
                <button id="send_weibo">发 布</button>
              </div>                
              </div>              
            </div>
          </div>
          <div class="col-lg-4">
            
          </div>
        </div>

        @endif

@if(count($data) > 0)

@foreach($data as $v)

    @if(!$v -> isturn)

        <!-- 普通样式 -->
          <div class="row">
     
            <div class="col-xs-6 col-lg-7 wb_main">
                <div style="font-weight:bold;display:none;" class="author">
                    <a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a>
                </div>
                <div class="content">{!! replace_weibo($v -> content) !!}</div>
                @if($v -> max)                
                    <div>
                        <img src="{{'/'.$v -> mini}}" alt="" width="50" height="50" class="mini_img">
                        <div style="display:none;" class="img_tool">
                            <ul style="list-style:none;">
                                <li class="packup">收 起</li>
                                <li><a href="{{'/'.$v -> max}}" target="_blank">查看大图</a></li>
                            </ul>
                            <div class="img_info">
                                <img src="{{'/'.$v -> medium}}" alt="" width="80" height="80">
                            </div>
                        </div>
                    </div>
                @endif                
                <div style="clear:both;">
                    <div class="pull-left">{{time_format($v -> time)}}</div>
                    <div class="pull-right">
                        <span class="keep-up" style="display:none;"></span>
                    </div>                    
                    <div class="pull-right">
                       @if($_SESSION['uid'] == $v -> uid)
                            <span class="del" wid="{{$v -> id}}">删除 |</span>                                  
                        @endif
                    <span class="turn" id="{{$v -> id}}">转发</span>
                        @if($v -> turn)
                            ({{$v -> turn}})                                
                        @endif
                     | <span class="keep" wid="{{$v -> id}}">收藏</span>
                        @if($v -> keep)
                            ({{$v -> keep}})                                
                        @endif                  
                     | <span class="comment" wid="{{$v -> id}}">评论</span>
                        @if($v -> comment)
                            ({{$v -> comment}})                                
                        @endif
                    </div>
                </div>

                <br>

                <!--=====回复框=====-->
                    <script>
                        var comment = "{{url('comment')}}";
                        var getComment = "{{url('getComment')}}";
                        var keep = "{{url('keep')}}";
                    </script>
                    <div class='comment_load' style="display:none;">
                        <img src="/bootstrap/img/loading.gif" alt="">评论加载中,请稍候...
                    </div>
                    <div class='comment_list' style="display:none;">
                        <textarea name="" sign=''></textarea>
                        <ul>
                            <li class='phiz fleft' sign=''></li>
                            <li class='comment_turn fleft'>
                                <label>
                                    <input type="checkbox" name=''/>同时转发到我的微博
                                </label>
                            </li>
                            <li class='comment_btn fright' wid='{{$v -> id}}'>评论</li>
                        </ul>
                    </div>
                <!--=====回复框结束=====-->

            </div>             

          </div>

          <hr>

    @else

        <!-- 转发样式 -->
          <div class="row">
    
            <div class="col-xs-6 col-lg-7 wb_main">
                <div style="font-weight:bold;display:none;" class="author"><a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a></div>
                <div class="content">{!! replace_weibo(str_replace('//','<span style="color:#ccc;font-weight: bold;">&nbsp;//&nbsp;</span>',$v -> content)) !!}</div>

                @if($v -> isturn === -1)
                    该微博已被删除
                @else
                    <!-- 转发的原微博内容开始 -->
                    <div style="margin:20px;padding:20px;border:1px solid #eee;">
                        <div style="font-weight:bold;" class="turn_name"><a href="{{url('userInfo/'.$v['isturn']['uid'])}}">{{$v['isturn']['username']}}</a></div>
                        <div class="turn_cons">{!! replace_weibo($v['isturn']['content']) !!}</div>
                        @if($v['isturn']['max'])                
                            <div>
                                <img src="{{'/'.$v['isturn']['mini']}}" alt="" width="50" height="50" class="mini_img">
                                <div style="display:none;" class="img_tool">
                                    <ul style="list-style:none;">
                                        <li class="packup">收 起</li>
                                        <li><a href="{{'/'.$v['isturn']['max']}}" target="_blank">查看大图</a></li>
                                    </ul>
                                    <div class="img_info">
                                        <img src="{{'/'.$v['isturn']['medium']}}" alt="" width="80" height="80">
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div style="clear:both;">
                            <div class="pull-left">{{time_format($v['isturn']['time'])}}</div>
                            <div class="pull-right"><span class="" id="">转发</span>
                            @if($v['isturn']['turn'])
                                ({{$v['isturn']['turn']}})                                
                            @endif                 
                         | 评论
                            @if($v['isturn']['comment'])
                                ({{$v['isturn']['comment']}})                                
                            @endif                            
                            </div>
                        </div>                    
                    </div>
                    <!-- 转发的原微博内容结束 -->
                @endif

                <div style="clear:both;">
                    <div class="pull-left">{{time_format($v -> time)}}</div>
                    <div class="pull-right">
                        <span class="keep-up" style="display:none;"></span>
                    </div>                    
                    <div class="pull-right">
                       @if($_SESSION['uid'] == $v -> uid)
                            <span class="del" wid="{{$v -> id}}">删除 |</span>                                  
                        @endif 
                    <span class="turn" id="{{$v -> id}}" tid="{{$v['isturn']['id']}}">转发</span>
                        @if($v -> turn)
                            ({{$v -> turn}})                                
                        @endif
                     | <span class="keep" wid="{{$v -> id}}">收藏</span>
                        @if($v -> keep)
                            ({{$v -> keep}})                                
                        @endif                  
                     | <span class="comment" wid="{{$v -> id}}">评论</span>
                        @if($v -> comment)
                            ({{$v -> comment}})                                
                        @endif
                    </div>
                </div>

                <br>

                <!--=====回复框=====-->
                    <div class='comment_load' style="display:none;">
                        <img src="/bootstrap/img/loading.gif" alt="">评论加载中,请稍候...
                    </div>                
                    <div class='comment_list' style="display:none;">
                        <textarea name="" sign=''></textarea>
                        <ul>
                            <li class='phiz fleft' sign=''></li>
                            <li class='comment_turn fleft'>
                                <label>
                                    <input type="checkbox" name=''/>同时转发到我的微博
                                </label>
                            </li>
                            <li class='comment_btn fright' wid='{{$v -> id}}'>评论</li>
                        </ul>
                    </div>
                <!--=====回复框结束=====-->

            </div>               
          </div>

          <hr>

    @endif

@endforeach

        <div class="row text-center">
            <div class="col-xs-6 col-lg-12">
                {{$data->links()}}                
            </div>
        </div>

@else

    没有发布微博    
    <hr>

@endif

        <div class="row">
            <div class="col-xs-6 col-lg-6">
                我的关注({{count($follow)}}) &nbsp;<a>更多>></a>
            </div>
        </div>
        <div class="row">
        @foreach($follow as $v)
            <div class="col-xs-6 col-lg-1 text-center">
                <div><a href="{{url('userInfo/'.$v -> uid)}}"><img src="
                    @if($v -> face)
                            {{'/'.$v -> face}}
                    @else
                         /bootstrap/img/noface.gif                           
                    @endif
                " alt="{{$v -> username}}" with="50" height="50"></a></div><br>
                <div><a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a></div>
            </div>
        @endforeach
        </div>

        <div class="row">
            <div class="col-xs-6 col-lg-6">
                我的粉丝({{count($fans)}}) &nbsp;<a>更多>></a>
            </div>
        </div>
        <div class="row">
        @foreach($fans as $v)
            <div class="col-xs-6 col-lg-1 text-center">
                <div><a href="{{url('userInfo/'.$v -> uid)}}"><img src="
                    @if($v -> face)
                            {{'/'.$v -> face}}
                    @else
                         /bootstrap/img/noface.gif                           
                    @endif
                " alt="{{$v -> username}}" with="50" height="50"></a></div><br>
                <div><a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a></div>
            </div>
        @endforeach
        </div>


        </div>
    <!--==========转发输入框==========-->

        <div id='turn' style='display:none;'>
            <div class="turn_head">
                <span class='turn_text fleft'>转发微博</span>
                <span class="close fright"></span>
            </div>
            <div class="turn_main">
                <form action='{{url("turn")}}' method='post' name='turn'>
                {{csrf_field()}}
                    <p></p>
                    <div class='turn_prompt'>
                        你还可以输入<span id='turn_num'>140</span>个字</span>
                    </div>
                    <textarea name='content' sign='turn'></textarea>
                    <ul>
                        <li class='phiz fleft' sign='turn'></li>
                        <li class='turn_comment fleft'>
                            <label>
                                <input type="checkbox" name='becomment'/>同时评论给<span class='turn-cname'></span>
                            </label>
                        </li>
                        <li class='turn_btn fright'>
                            <input type="hidden" name='id' value=''/>
                            <input type="hidden" name='tid' value=''/>
                            <input type="submit" value='转发' class='turn_btn'/>
                        </li>
                    </ul>
                </form>
            </div>
        </div>
    <!--==========转发输入框==========-->     
      </div>

    </div><!--/.container-->

@endsection