<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\Http\Models\Group;
use App\Http\Models\Userinfo;
use App\Http\Models\Follow;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts/home', function ($view) {

            $uid = $_SESSION['uid'];

            //自建的小组
            $group = Group::where('uid',$uid)->get();

            //右上角的个人信息：关注|粉丝|微博
            $field = ['username','face80 as face','follow','fans','wb','uid'];
            $userinfo = Userinfo::where('uid',$uid) -> select($field) -> first();

            //可能感兴趣的人
            $follow = Follow::where('fans',$uid) -> pluck('follow');

            $friend = Follow::leftJoin('userinfo', 'follow.follow', '=', 'userinfo.uid')                
                        ->select('userinfo.uid','userinfo.username','userinfo.face50 as face')        
                        ->whereIn('follow.fans',$follow)
                        ->whereNotIn('follow.follow', $follow)
                        ->where('follow.follow', '<>', $uid)
                        ->groupBy('follow.follow')
                        // ->orderBy('time','desc')                         
                        ->take(4)                        
                        ->get();            

            $user = Userinfo::where('uid',$uid)->first();

            $view->with('group',$group)
                    ->with('userinfo',$userinfo)
                    ->with('friend',$friend)
                    ->with('user',$user);
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
