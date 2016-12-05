@extends('layouts.home')

@section('title')
  <title>搜 索</title>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        @parent
      
        <div class="col-xs-12 col-sm-7">

<!--           <div class="row">    
            <div class="form-group col-lg-10">
              <form class="navbar-form navbar-left" method="get" action="{{url('searchUser')}}">
                <input type="text" class="form-control" placeholder="搜索微博、找人" name="keyname">
                <button type="submit" class="btn btn-success">搜 索</button>
              </form>       
            </div>
        </div> -->

        @if(count($data) > 0)

            <div class="row text-center">
                <div class="form-group col-lg-10">
                 共搜出<font style="color:red;"> {{count($data)}} </font>条数据
                  @if(isset($k) && empty($k))
                      | 全部用户
                  @endif                 
                </div>
            </div>

          @foreach($data as $v)
            <div class="row">
              
                <div class="form-group col-lg-3">
                    <img src="
                    @if($v -> face180)
                      {{$v -> face180}}
                    @else
                      bootstrap/img/noface.gif        
                    @endif                    
                    " alt="" width="100" height="100">
                </div>

                <div class="form-group col-lg-3">
                  <div style="padding:10px 0;">{!! str_replace($k,"<font style='color:red;'>$k</font>",$v -> username) !!}</div>
                  <div style="padding:10px 0;">{{$v -> sex}} 
                    @if(isset($v -> location) && !empty($v -> location))
                        {{$v -> location}}
                    @else
                        该用户未填写所在地
                    @endif
                  </div>
                  <div style="padding:10px 0;">关注 {{$v -> follow}} | 粉丝 {{$v -> fans}} | 微博 {{$v -> wb}}</div>
                </div>           
                <div class="form-group col-lg-3 pull-right">
                  @if($v -> mutual)
                    <span>互相关注</span> |
                    <button>移除关注</button>
                  @elseif ($v -> followed)
                    <span>√ 已关注</span> |
                    <button>移除关注</button>                    
                  @else
                    <button class="add-fl" uid="{{$v -> uid}}">+ 未关注</button>                  
                  @endif                  

                </div>             

            </div>
          @endforeach

        @else

            <div class="row text-center">
                <div class="form-group col-lg-10">
                  未找到与<font style="color:red;"> {{$k}} </font>相关的用户
                </div>
            </div>

        @endif

        <div class="row">
          <div class="pagination">
            {{$data -> appends(['k' => $k]) -> links()}}
          </div>
        </div>

        </div>

        <!--==========加关注弹出框==========-->
            <script type='text/javascript'>
              var addFollow = "{{url('addFollow')}}";
              var getGroup = "{{url('getGroup')}}";
              var token = "{{csrf_token()}}";
            </script>
            <div id='follow'>
                <div class="follow_head">
                    <span class='follow_text fleft'>关注好友</span>
                </div>
                <div class='sel-group'>
                    <span>好友分组：</span>
                    <select name="gid">
                    </select>
                </div>
                <div class='fl-btn-wrap'>
                    <input type="hidden" name='follow'/>
                    <span class='add-follow-sub'>关注</span>
                    <span class='follow-cencle'>取消</span>
                </div>
            </div>
        <!--==========加关注弹出框==========-->        
      </div>

    </div><!--/.container-->
        
@endsection
