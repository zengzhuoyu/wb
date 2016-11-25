@extends('layouts.home')

@section('title')
  <title>个人设置</title>
@endsection

@section('script')
  <script type='text/javascript'>
    var address = "{{$user -> location}}";
    var constellation = "{{$user -> constellation}}";
  </script>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        @parent

        <div class="col-xs-12 col-sm-10">
          <div class="row">

            <ul id="myTab" class="nav nav-tabs">
               <li class="active"><a href="#basic" data-toggle="tab">基本信息</a></li>
               <li><a href="#headportrait" data-toggle="tab">修改头像</a></li>
               <li><a href="#pass" data-toggle="tab">修改密码</a></li>               
            </ul>          

            <div id="myTabContent" class="tab-content">
               <div class="tab-pane fade in active" id="basic" style="padding:30px">

                    <form method="post" action="{{url('editBasic')}}">
                    {{csrf_field()}}

                    @if($errors)
                      <div class="row">
                        <div class="form-group col-lg-3">
                        </div>                      
                        <div class="form-group col-lg-3">
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

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="nickname">昵称：</label>
                        </div>
                        <div class="form-group col-lg-3">                        
                          <input type="text" class="form-control" value="{{$user->username}}" id="nickname" name="nickname">
                        </div>                          
                      </div>
                               
                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <label for="truename">真实名称：</label>
                        </div>
                        <div class="form-group col-lg-3">                        
                          <input type="text" class="form-control" value="{{$user->truename}}" id="truename" name="truename">
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="">性别：</label>
                        </div>
                        <div class="form-group col-lg-2">                        
                            <label for="man"><input type="radio" name="sex" id="man" value="1"
                            @if($user -> sex == '男')
                            checked="checked"
                            @endif
                            > 男 </label>&nbsp;&nbsp;
                            <label for="woman"><input type="radio" name="sex" id="woman"  value="2"
                            @if($user -> sex == '女')
                            checked="checked"
                            @endif
                            > 女</label>
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="">所在地：</label>
                        </div>
                        <div class="form-group col-lg-2">                        
                          <select class="form-control" name="province">
                            <option value="">请选择</option>
                          </select>
                        </div>             
                        <div class="form-group col-lg-2">                        
                          <select class="form-control" name="city">
                            <option value="">请选择</option>
                          </select>
                        </div>                                      
                      </div>
                
                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <label for="">星座：</label>
                        </div>
                        <div class="form-group col-lg-2">                        
                          <select class="form-control" name="night">
                            <option value="">请选择</option>
                            <option value="白羊座">白羊座</option>
                            <option value="金牛座">金牛座</option>
                            <option value="双子座">双子座</option>
                            <option value="巨蟹座">巨蟹座</option>
                            <option value="狮子座">狮子座</option>
                            <option value="处女座">处女座</option>
                            <option value="天秤座">天秤座</option>
                            <option value="天蝎座">天蝎座</option>
                            <option value="射手座">射手座</option>
                            <option value="魔羯座">魔羯座</option>
                            <option value="水瓶座">水瓶座</option>
                            <option value="双鱼座">双鱼座</option>
                          </select>
                        </div>                                                 
                      </div>           

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <label for="intro">一句话介绍自己：</label>
                        </div>
                        <div class="form-group col-lg-4">                        
                          <textarea class="form-control" rows="3" id="intro" name="intro">{{$user -> intro}}</textarea>
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3">
                        </div>
                        <div class="form-group col-lg-1">                        
                          <button class="btn btn-primary">保存修改</button>
                        </div>                          
                      </div>

                    </form>

               </div>
               <div class="tab-pane fade" id="headportrait" style="padding:30px">
                    <form method="post" action="{{url('editFace')}}">
                    {{csrf_field()}}

                    @if($errors)
                      <div class="row">
                        <div class="form-group col-lg-4">
                        </div>                      
                        <div class="form-group col-lg-3">
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

                    <div class="row">
                      <div class="form-group col-lg-11 text-center">
                        <img src="
                        @if($user -> face180)
                          {{$user -> face180}}
                        @else
                          {{asset('bootstrap/img/noface.gif')}}                                        
                        @endif                        
                        " alt="" width='180' height='180' id='face-img'>
                      </div>
                    </div>

                    <div class="row">
                      <div class="form-group col-lg-4">                      
                      </div>                    
                      <div class="form-group col-lg-2">
                        <input id="file_upload" name="file_upload" type="file" multiple="true">         
                        <script type="text/javascript">
                            <?php $timestamp = time();?>
                            $(function() {
                                $('#file_upload').uploadify({
                                    'buttonText' : '头像上传',
                                    'formData'     : {
                                        'type' : 'Face',
                                        'timestamp' : '<?php echo $timestamp;?>',
                                        '_token'     : "{{csrf_token()}}"
                                    },
                                    'swf'      : "{{asset('org/uploadify/uploadify.swf')}}",
                                    'uploader' : "{{url('/uploadFace')}}",
                                    'onUploadSuccess' : function(file, data, response) {
                                      $('#face-img').attr('src', data);
                                      $('input[name=face180]').val(data);
                                      $('input[name=face80]').val(data);
                                      $('input[name=face50]').val(data);                                    
                                    }
                                });
                            });
                        </script>      
                        <style>
                            .uploadify{display:inline-block;}
                            .uploadify-button{border:none; border-radius:5px;}
                            table.add_tab tr td span.uploadify-button-text{color: #FFF; margin:0;}
                        </style>                                                         
                        <input type="hidden" name='face180' value=''/>
                        <input type="hidden" name='face80' value=''/>
                        <input type="hidden" name='face50' value=''/>                        
                      </div>
                    </div>

                    <div class="row">              
                      <div class="form-group col-lg-11 text-center">                        
                        <button class="btn btn-primary">保存修改</button>
                      </div>                          
                    </div>

                    </form>
               </div>
               <div class="tab-pane fade" id="pass" style="padding:30px">

                    <form method="post" action="{{url('editPwd')}}" name="editPwd">
                    {{csrf_field()}}

                    @if($errors)
                      <div class="row">
                        <div class="form-group col-lg-3">
                        </div>                      
                        <div class="form-group col-lg-3">
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

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="old">旧密码：</label>
                        </div>
                        <div class="form-group col-lg-3">                        
                          <input type="text" class="form-control" value="" name="old" id="old">
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="new">新密码：</label>
                        </div>
                        <div class="form-group col-lg-3">                        
                          <input type="text" class="form-control" value="" id="new" name="new">
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3 text-right">
                          <span style="color:red;">* </span><label for="password_confirmation">确认密码：</label>
                        </div>
                        <div class="form-group col-lg-3">                        
                          <input type="text" class="form-control" value="" name="password_confirmation" id="password_confirmation">
                        </div>                          
                      </div>

                      <div class="row">
                        <div class="form-group col-lg-3">
                        </div>
                        <div class="form-group col-lg-1">                        
                          <button class="btn btn-primary">保存修改</button>
                        </div>                          
                      </div>

                    </form>

               </div>               
            </div>

          </div>
        </div><!--/row-->

      </div>

    </div><!--/.container-->
        
@endsection
