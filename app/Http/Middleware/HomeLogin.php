<?php

namespace App\Http\Middleware;

use Closure;

use App\Http\Models\User;

class HomeLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        
        //执行自动登录操作
        if(isset($_COOKIE['auto']) && !isset($_SESSION['uid'])){

            $value = explode("|",encryption($_COOKIE['auto'],1));
            $ip = $request->getClientIp();

            if($ip == $value[1]){

                $account = $value[0];

                $user = User::where('account',$account)->select('id','lock')->first();
      
                if($user && !$user -> lock){
                    $_SESSION['uid'] = $user -> id; 
                }
            }

        }

        if(!isset($_SESSION['uid'])){//进入不被允许的控制器时，判断是否登录、注册过
            //而自动登录：是在没有登录注册的前提下不被允许的控制器时，如果有点选自动登录，就不会被拦截跳转至登录页
            return redirect('/login');
        }        
        
        return $next($request);
    }
}
