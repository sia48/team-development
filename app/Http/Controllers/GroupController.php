<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function group() 
    {
        return view('group/group-store');
    }

    public function store(Request $request) 
    {
        //作成者のUserテーブルのgroup_idカラムも同時に登録する
        $group = new Group();
        $group->group_name = $request->group_name;
        $group->group_image = $request->group_image->store('public/group-image');
        $group->group_image = str_replace('public/group-image/', '', $group->group_image);
        $group->save();

        return redirect('/group_show');
    }

    public function show() 
    {
        $groups = Group::where('id', '=', 2)->get();
        return view('group/group-show', ['groups' => $groups]);
    }

    public function detail($id) 
    {
        $group = Group::find($id);
        
        return view('group/group-detail', ['group' => $group]);
    }

    public function update(Request $request, $id) 
    {
        $groups = Group::find($id);
        $groups->group_name = $request->group_name;
        $groups->group_image = $request->group_image->store('public/group-image');
        $groups->group_image = str_replace('public/group-image/', '', $groups->group_image);
        $groups->save();
        
        return redirect('group_show');
    }

    public function destroy($id) 
    {
        $group = Group::find($id);
        $group->delete();
        
        return redirect('group_show');
    }

    public function search($userName) 
    {
        $get_user = User::where('name', 'like', "%$userName%")->get();        
        return $get_user;
    }

    public function register(Request $request, $user_id) 
    {
        $user = User::where('id', '=', "$user_id")->first();  
        $user->group_id = $request->group_id;
        $user->save();    
        return $user;
    }
}
