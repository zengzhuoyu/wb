@extends('layouts.home')

@section('title')
  <title>微 博</title>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
        
        @parent
        
        <div class="col-xs-12 col-sm-7">
        
          <div class="row">

            <div class="col-xs-6 col-lg-11 text-right">
              <textarea cols="60" rows="5" name="content"></textarea>
            </div>          
          
          </div>

            <div class="row">

              <div class="col-xs-6 col-lg-3">
              </div>   
              <div class="col-xs-6 col-lg-1">
                <div class="emotion"></div>              
              </div>                 
              <div class="col-xs-6 col-lg-1">
                <div class="icon-picture"></div> 
                <!--图片上传框-->
                    <div id="upload_img" style='display:none;'>
                        <div class='upload-title'><p>本地上传</p><span class='close'></span></div>
                        <input id="file_upload" name="file_upload" type="file" multiple="true">         
                        <script type="text/javascript">
                            var token = "{{csrf_token()}}";
                            var sendWeibo = "{{url('sendWeibo')}}";
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
                                      $('#pic-show').fadeIn().find('img').attr('src', data);
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
              <div class="col-xs-6 col-lg-6 text-right">
                <button id="send_weibo">发 布</button>
              </div>
                                      
          </div>

@foreach($data as $v)

    @if(!$v -> isturn)

        <!-- 普通样式 -->
          <div class="row">

            <div class="col-xs-6 col-lg-1">
            </div>          
            <div class="col-xs-6 col-lg-1">
                <img src="
                    @if($v -> face)
                        {{$v -> face}}
                    @else
                        {{asset('bootstrap/img/noface.gif')}}                                                    
                    @endif
                " alt="" width="50" height="50">
            </div>             
            <div class="col-xs-6 col-lg-1">
            </div>       
            <div class="col-xs-6 col-lg-9 wb_main">
                <div style="font-weight:bold;" class="author">
                    <a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a>
                </div>
                <div class="content">{!! replace_weibo($v -> content) !!}</div>
                @if($v -> max)                
                    <div>
                        <img src="{{$v -> mini}}" alt="" width="50" height="50" class="mini_img">
                        <div style="display:none;" class="img_tool">
                            <ul style="list-style:none;">
                                <li class="packup">收 起</li>
                                <li><a href="{{$v -> max}}" target="_blank">查看大图</a></li>
                            </ul>
                            <div class="img_info">
                                <img src="{{$v -> medium}}" alt="" width="80" height="80">
                            </div>
                        </div>
                    </div>
                @endif                
                <div style="clear:both;">
                    <div class="pull-left">{{time_format($v -> time)}}</div>
                    <div class="pull-right"><span class="turn" id="{{$v -> id}}">转发</span>
                        @if($v -> turn)
                            ({{$v -> turn}})                                
                        @endif
                     | 收藏
                        @if($v -> keep)
                            ({{$v -> keep}})                                
                        @endif                  
                     | <span class="comment">评论</span>
                        @if($v -> comment)
                            ({{$v -> comment}})                                
                        @endif
                    </div>
                </div>

                <br>

                <!--=====回复框=====-->
                    <script>
                    var comment = "{{url('comment')}}";
                    </script>
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

            <div class="col-xs-6 col-lg-1">
            </div>          
            <div class="col-xs-6 col-lg-1">
                <img src="
                    @if($v -> face)
                        {{$v -> face}}
                    @else
                        {{asset('bootstrap/img/noface.gif')}}                                                    
                    @endif
                " alt="" width="50" height="50">
            </div>             
            <div class="col-xs-6 col-lg-1">
            </div>       
            <div class="col-xs-6 col-lg-9 wb_main">
                <div style="font-weight:bold;" class="author"><a href="{{url('userInfo/'.$v -> uid)}}">{{$v -> username}}</a></div>
                <div class="content">{!! replace_weibo(str_replace('//','<span style="color:#ccc;font-weight: bold;">&nbsp;//&nbsp;</span>',$v -> content)) !!}</div>

                <!-- 转发的原微博内容开始 -->
                <div style="margin:20px;padding:20px;border:1px solid #eee;">
                    <div style="font-weight:bold;" class="turn_name"><a href="{{url('userInfo/'.$v['isturn']['uid'])}}">{{$v['isturn']['username']}}</a></div>
                    <div class="turn_cons">{!! replace_weibo($v['isturn']['content']) !!}</div>
                    @if($v['isturn']['max'])                
                        <div>
                            <img src="{{$v['isturn']['mini']}}" alt="" width="50" height="50" class="mini_img">
                            <div style="display:none;" class="img_tool">
                                <ul style="list-style:none;">
                                    <li class="packup">收 起</li>
                                    <li><a href="{{$v['isturn']['max']}}" target="_blank">查看大图</a></li>
                                </ul>
                                <div class="img_info">
                                    <img src="{{$v['isturn']['medium']}}" alt="" width="80" height="80">
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

                <div style="clear:both;">
                    <div class="pull-left">{{time_format($v -> time)}}</div>
                    <div class="pull-right"><span class="turn" id="{{$v -> id}}" tid="{{$v['isturn']['id']}}">转发</span>
                        @if($v -> turn)
                            ({{$v -> turn}})                                
                        @endif
                     | 收藏
                        @if($v -> keep)
                            ({{$v -> keep}})                                
                        @endif                  
                     | <span class="comment">评论</span>
                        @if($v -> comment)
                            ({{$v -> comment}})                                
                        @endif
                    </div>
                </div>

                <br>

                <!--=====回复框=====-->
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


        </div>

        <div class="col-xs-6 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
          <div class="list-group">
            <a href="#" class="list-group-item active">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
            <a href="#" class="list-group-item">Link</a>
          </div>
        </div>

        <!--==========表情选择框==========-->
            <div id="phiz" class='hidden'>
                <div>
                    <p>常用表情</p>
                    <span class='close fright'></span>
                </div>
                <ul>
                    <li><img src="bootstrap/img/phiz/hehe.gif" alt="呵呵" title="呵呵" /></li>
                    <li><img src="bootstrap/img/phiz/xixi.gif" alt="嘻嘻" title="嘻嘻" /></li>
                    <li><img src="bootstrap/img/phiz/haha.gif" alt="哈哈" title="哈哈" /></li>
                    <li><img src="bootstrap/img/phiz/keai.gif" alt="可爱" title="可爱" /></li>
                    <li><img src="bootstrap/img/phiz/kelian.gif" alt="可怜" title="可怜" /></li>
                    <li><img src="bootstrap/img/phiz/wabisi.gif" alt="挖鼻屎" title="挖鼻屎" /></li>
                    <li><img src="bootstrap/img/phiz/chijing.gif" alt="吃惊" title="吃惊" /></li>
                    <li><img src="bootstrap/img/phiz/haixiu.gif" alt="害羞" title="害羞" /></li>
                    <li><img src="bootstrap/img/phiz/jiyan.gif" alt="挤眼" title="挤眼" /></li>
                    <li><img src="bootstrap/img/phiz/bizui.gif" alt="闭嘴" title="闭嘴" /></li>
                    <li><img src="bootstrap/img/phiz/bishi.gif" alt="鄙视" title="鄙视" /></li>
                    <li><img src="bootstrap/img/phiz/aini.gif" alt="爱你" title="爱你" /></li>
                    <li><img src="bootstrap/img/phiz/lei.gif" alt="泪" title="泪" /></li>
                    <li><img src="bootstrap/img/phiz/touxiao.gif" alt="偷笑" title="偷笑" /></li>
                    <li><img src="bootstrap/img/phiz/qinqin.gif" alt="亲亲" title="亲亲" /></li>
                    <li><img src="bootstrap/img/phiz/shengbin.gif" alt="生病" title="生病" /></li>
                    <li><img src="bootstrap/img/phiz/taikaixin.gif" alt="太开心" title="太开心" /></li>
                    <li><img src="bootstrap/img/phiz/ldln.gif" alt="懒得理你" title="懒得理你" /></li>
                    <li><img src="bootstrap/img/phiz/youhenhen.gif" alt="右哼哼" title="右哼哼" /></li>
                    <li><img src="bootstrap/img/phiz/zuohenhen.gif" alt="左哼哼" title="左哼哼" /></li>
                    <li><img src="bootstrap/img/phiz/xiu.gif" alt="嘘" title="嘘" /></li>
                    <li><img src="bootstrap/img/phiz/shuai.gif" alt="衰" title="衰" /></li>
                    <li><img src="bootstrap/img/phiz/weiqu.gif" alt="委屈" title="委屈" /></li>
                    <li><img src="bootstrap/img/phiz/tu.gif" alt="吐" title="吐" /></li>
                    <li><img src="bootstrap/img/phiz/dahaqian.gif" alt="打哈欠" title="打哈欠" /></li>
                    <li><img src="bootstrap/img/phiz/baobao.gif" alt="抱抱" title="抱抱" /></li>
                    <li><img src="bootstrap/img/phiz/nu.gif" alt="怒" title="怒" /></li>
                    <li><img src="bootstrap/img/phiz/yiwen.gif" alt="疑问" title="疑问" /></li>
                    <li><img src="bootstrap/img/phiz/canzui.gif" alt="馋嘴" title="馋嘴" /></li>
                    <li><img src="bootstrap/img/phiz/baibai.gif" alt="拜拜" title="拜拜" /></li>
                    <li><img src="bootstrap/img/phiz/sikao.gif" alt="思考" title="思考" /></li>
                    <li><img src="bootstrap/img/phiz/han.gif" alt="汗" title="汗" /></li>
                    <li><img src="bootstrap/img/phiz/kun.gif" alt="困" title="困" /></li>
                    <li><img src="bootstrap/img/phiz/shuijiao.gif" alt="睡觉" title="睡觉" /></li>
                    <li><img src="bootstrap/img/phiz/qian.gif" alt="钱" title="钱" /></li>
                    <li><img src="bootstrap/img/phiz/shiwang.gif" alt="失望" title="失望" /></li>
                    <li><img src="bootstrap/img/phiz/ku.gif" alt="酷" title="酷" /></li>
                    <li><img src="bootstrap/img/phiz/huaxin.gif" alt="花心" title="花心" /></li>
                    <li><img src="bootstrap/img/phiz/heng.gif" alt="哼" title="哼" /></li>
                    <li><img src="bootstrap/img/phiz/guzhang.gif" alt="鼓掌" title="鼓掌" /></li>
                    <li><img src="bootstrap/img/phiz/yun.gif" alt="晕" title="晕" /></li>
                    <li><img src="bootstrap/img/phiz/beishuang.gif" alt="悲伤" title="悲伤" /></li>
                    <li><img src="bootstrap/img/phiz/zuakuang.gif" alt="抓狂" title="抓狂" /></li>
                    <li><img src="bootstrap/img/phiz/heixian.gif" alt="黑线" title="黑线" /></li>
                    <li><img src="bootstrap/img/phiz/yinxian.gif" alt="阴险" title="阴险" /></li>
                    <li><img src="bootstrap/img/phiz/numa.gif" alt="怒骂" title="怒骂" /></li>
                    <li><img src="bootstrap/img/phiz/xin.gif" alt="心" title="心" /></li>
                    <li><img src="bootstrap/img/phiz/shuangxin.gif" alt="伤心" title="伤心" /></li>
                </ul>
            </div>
        <!--==========表情==========-->

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

      </div><!--/row-->

    </div><!--/.container-->

@endsection
