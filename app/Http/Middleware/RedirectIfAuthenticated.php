<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if(Auth::check()){
            $currentIdent = $request->session()->get('currentIdent','default');
        

        }
        if (Auth::check() &&  Auth::user()->type=='t')
            {
                // return new Response(view(‘soverview’));
                return redirect('/overview');
            }
            if (Auth::check() &&  Auth::user()->type=='s')
            {
                // return new Response(view(‘soverview’));
                return redirect('/replyslips');
            }
            if (Auth::check() &&  Auth::user()->type=='p')
            {
                //session(['currentIdent' => 1211009]);
                
                $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section')
                ->where('s_mom_id', Auth::user()->ident)
                ->get();

                if($result->isEmpty()){               
                    $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section')
                ->where('s_dad_id', Auth::user()->ident)
                ->get();
                }
                if ($result->count()) {
                    // do something
                    session(['children' => $result]);
                    session(['currentIdent' => $result[0]->id]);
                    
                }
                // return new Response(view(‘soverview’));
                // print_r(session('children'));echo session('currentIdent');die();
                return redirect('/overview');
            }

        if (Auth::guard($guard)->check()) {
            return redirect('/overview');
        }
        // return redirect('/overview');
         return $next($request);

    }
}
