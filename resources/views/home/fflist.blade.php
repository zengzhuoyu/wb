@extends('layouts.home')

@section('title')
  <title>
    @if($type === 1)
      关注列表
    @else
      粉丝列表
    @endif    
  </title>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">
      @parent
        <div class="col-xs-12 col-sm-7">

            <div class="row text-center">
                <div class="form-group col-lg-10">
                  @if($type === 1)
                    关注 {{count($users)}} 人 | 关注列表
                  @else
                    粉丝 {{count($users)}} 人 | 粉丝列表
                  @endif                 
                </div>
            </div>

          @foreach($users as $v)
            <div class="row follow-list">
              
                <div class="form-group col-lg-3">
                    <img src="
                    @if($v -> face)
                      {{'/' . $v -> face}}
                    @else
                      /bootstrap/img/noface.gif        
                    @endif                    
                    " alt="" width="100" height="100">
                </div>

                <div class="form-group col-lg-3">
                  <div style="padding:10px 0;"><a href="{{url('userInfo/'. $v -> uid)}}">{{$v -> username}}</a></div>
                  <div style="padding:10px 0;"><span style="display:block" class="
                    @if($v -> sex == '男')
                      boy
                    @else
                      girl        
                    @endif
                  "></span>
                    @if(isset($v -> location) && !empty($v -> location))
                        {{$v -> location}}
                    @else
                        该用户未填写所在地
                    @endif
                  </div>
                  <div style="padding:10px 0;">关注 <a href="{{url('follow/'. $v -> uid)}}">{{$v -> follow}}</a> | 粉丝 <a href="{{url('fans/'. $v -> uid)}}">{{$v -> fans}}</a> | 微博 <a href="{{url('userInfo/'. $v -> uid)}}">{{$v -> wb}}</a></div>
                </div>                     
                <div class="form-group col-lg-3 pull-right">
                  @if(in_array($v -> uid,$follow) && in_array($v -> uid,$fans))
                    <span>互相关注</span> |
                  @elseif (in_array($v -> uid,$follow))
                    <span>√ 已关注</span> |              
                  @else
                    <button class="add-fl" uid="{{$v -> uid}}">+ 关注</button> |     
                  @endif                  
                    <button uid="{{$v -> uid}}" class="del-follow" type="{{$type}}">移除</button>
                </div>
            </div>
          @endforeach

        <div class="row text-center">
            <div class="col-xs-6 col-lg-12">
                {{$users->links()}}                
            </div>
        </div>

        </div>
     
      </div>

    </div><!--/.container-->
        
@endsection
