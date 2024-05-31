
1 Step:        config\cors.php
<?php
    'supportsCredentials' => true,
    'allowedOrigins' => ['*'],
    'allowedHeaders' => ['Content-Type', 'X-Requested-With'],
    'allowedMethods' => ['*'], // ex: ['GET', 'POST', 'PUT',  'DELETE']
    'exposedHeaders' => [],
    'maxAge' => 0,





2 Step:          app\Http\Kernel.php
comment '\Illuminate\Session\Middleware\AuthenticateSession::class,'  line in protected $middlewareGroups = [   >  web => [ ... 




3.  vendor folder remove and    run command       'composer install'



4.  I have Middleware (app\Http\Middleware\AuthUserCheck.php)
<?php
    ...
     public function handle($request, Closure $next)
    {
      
        if(isset(Auth::user()->is_verified))
        {
           

            if(Auth::user()->is_verified == 1)
            {

                if ((Auth::user()->g2f_enabled) && (session()->has('g2f_checked'))) {

                    return $next($request);

                }elseif((Auth::user()->g2f_enabled) && !(session()->has('g2f_checked'))){

                    return redirect()->route('g2fChecked');

                }else{

                    return $next($request);

                }

            }else{
                return redirect('login')->with('error_msg',__('Please verify your EMAIL'));
            }

        }else{

            return redirect('login');
        }








5. My Logout Code:
<?php
    ...
    public function logout(Request $request)
    {

        try{
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            $rememberMeCookie = Auth::getRecallerName();
            $cookie = Cookie::forget($rememberMeCookie);
            Cookie::queue(Cookie::forget('usercode'));
            Session::forget('g2f_checked');
            Auth::logout();
            return redirect()->route('login');
        }catch(\Exception $e)
        {
            Auth::logout();
            return redirect()->route('login');
        }

    }



