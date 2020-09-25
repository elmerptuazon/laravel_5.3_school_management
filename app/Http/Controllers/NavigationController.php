<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use DateTime;


class NavigationController extends Controller
{
    //

    public function notificationsListHeaderNav(){
        // echo "test"; die();
        if (Auth::check() ) {
        $result = DB::table('notify')
        ->select('*')
        ->get();

        $result1 = DB::table('notify_users')
        ->select('*')
        ->leftJoin('notify', 'notify_users.nid', '=', 'notify.id')
        ->where('viewed','0')
        ->where('uid',Auth::user()->id)
        ->orderBy('date','desc')
        ->get();
        // print_r($result1);die();

        return $result1;
    }else{
        // Auth::logout();
        return redirect('/login');
    }
    }

    public function notificationsUnreadCount(){
        if (Auth::check() ) {
        $result = DB::table('notify_users')
        ->select('*')
        ->leftJoin('notify', 'notify_users.nid', '=', 'notify.id')
        ->where('viewed','0')
        ->where('uid',Auth::user()->id)
        ->orderBy('date','desc')
        ->count();

        return $result;
    }else{
        // Auth::logout();
        return redirect('/login');
    }

    }

    public function testing(){

        $data = DB::table('users')
        ->select('*')
        ->get();

        return $data->toJson(JSON_PRETTY_PRINT);
      }


     
    

}
