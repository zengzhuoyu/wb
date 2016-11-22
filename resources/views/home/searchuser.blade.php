@extends('layouts.home')

@section('title')
  <title>搜 索</title>
@endsection

@section('content')

    <div class="container">

      <div class="row row-offcanvas row-offcanvas-right">

        @parent
      
        <div class="col-xs-12 col-sm-10">

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
                    <img src="{{$v -> face180}}" alt="" width="180" height="180">
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
                    <div style="padding:25px 0px;">互相关注</div>
                    <div style="padding:25px 0px;">移 除</div>
                  @elseif ($v -> followed)
                    <div style="padding:25px 0px;">已关注</div>
                    <div style="padding:25px 0px;">移 除</div>                    
                  @else
                    <div style="padding:25px 0px;">+ 关注</div>                  
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
      </div>

    </div><!--/.container-->
        
@endsection
