<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;

class LoginWhereTo extends Controller
{
    public function index(Request $request) {
        if(Auth::check()){
            $currentIdent = $request->session()->get('currentIdent','default');
            session(['currentIdent' => Auth::user()->ident]);

        }
    //
     if  ( Auth::check() &&  Auth::user()->type=='s')
            {
                // return new Response(view(‘soverview’));
                return redirect('/overview');
            }
            elseif (Auth::check() &&  Auth::user()->type=='p')
            {
                // session(['currentIdent' => 1211009]);
                
                $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section','profilepic')
                ->where('s_mom_id', Auth::user()->ident)
                ->get();

                if($result->isEmpty()){               
                    $result = DB::table('students')
                ->select('id','firstname','lastname','grade','section','profilepic')
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
    elseif (Auth::check() &&  Auth::user()->type=='t')
            {
                // return new Response(view(‘soverview’));
                return redirect('/toverview');
            }
    elseif (Auth::check() &&  Auth::user()->type=='a')
            {
                // return new Response(view(‘soverview’));
                return redirect('/aoverview');
            }
    else{
        Auth::logout();
                return redirect('/login');
    }
    }  

    public function accountSwitcher(Request $request){

        if (Auth::check() &&  Auth::user()->type=='p')
            {
                if($request->session()->has('currentIdent')){
                    // echo session('currentIdent');
                    // echo $request->id;die();
                    if(session('currentIdent') == $request->id){
                        return redirect('/overview');
                    }
                    elseif(session('currentIdent') != $request->id){

                    if($request->session()->has('children')){
                            
                        
                        foreach(session('children') as $child){
                            if($request->id == $child->id) {
                                session(['currentIdent' => $child->id]);
                            }
                        }
                        
                    }
                        // $result = DB::table('students')
                        // ->select('id','firstname','lastname','grade','section')
                        // ->where('s_mom_id', Auth::user()->ident)
                        // ->get();

                        // if($result->isEmpty()){               
                        //     $result = DB::table('students')
                        //     ->select('id','firstname','lastname','grade','section')
                        //     ->where('s_dad_id', Auth::user()->ident)
                        //     ->get();
                        // }

                        // if ($result->count()) {
                        // // do something
                        // session(['children' => $result]);
                        // session(['currentIdent' => $result[0]->id]);
                    
                        // }

                    }
                    

                }

                // return new Response(view(‘soverview’));
                // print_r(session('children'));echo session('currentIdent');die();
                // return redirect('/overview');
                $referer = $request->server('HTTP_REFERER');
        return redirect()->to($referer);
            }

    }
}
