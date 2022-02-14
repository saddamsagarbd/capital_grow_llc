<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\UserDetails;

class ReferenceController extends Controller
{
    public function index()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $user_profile = new UserDetails();
        $users = $user_profile->getReferedUsersList(Auth::user()->id);
        return view('admin_panel.referal_users', ['users' => $users]);
    }

    public function getReferedUser(Request $r)
    {
        $user_profile = new UserDetails();
        $users = $user_profile->getReferedUsersList($r["placementId"]);

        $html = "";

        if(count($users) > 0){

            foreach($users as $key => $user)
            {
                $html = '<li class="tree-li">
                    <a href="#" class="child-expand" data-p_id="'.$user->uid .'">
                        <i class="fa fa-caret-right tree-right-arrow"></i>'. $user->first_name .' '. $user->last_name .'
                    </a>
                    <ul class="list-arrow">
                        <div class="clr"></div>
                    </ul>
                </li>';
            }

        }else{
            $html = '<li><a href="#" class="child-expand">No users found</a></li>';
        }

        echo json_encode(['html' => $html]);
    }

    public function getIncomeStatement()
    {
        if(Auth::user()->user_status == 3){
            return redirect('/confirm-payment');
        }
        $user_profile = new UserDetails();
        $id = Auth::user()->user_id;
        if(Auth::user()->user_role == 2){
            $id = Auth::user()->id;
        }
        $statements = $user_profile->getIncomeStatement($id);
        return view('admin_panel.earning_statement', ['statements' => $statements]);
    }
}
