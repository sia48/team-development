<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function group() 
    {
        return view('group/group-store', ['user' => Auth::user()]);
    }

    public function store(Request $request, $num) 
    {
        $request->validate([
            'group_name' => 'required|max:50'
        ],
        [
            'group_name.required' => 'グループ名を入力して下さい',
            'group_name.max' => 'グループ名は最大50文字までです'
        ]);

        $group = new Group();
        $group->group_name = $request->group_name;
        if(isset($request->group_image)) {
            $group->group_image = str_replace('public/group-image/', '', $request->group_image->store('public/group-image'));
        } else {
            $group->group_image = str_replace('public/group-image/', '', 'icon-default-user.svg'->store('public/group-image'));
        }
        $group->created_user_id = Auth::user()->id;
        $group->save();

        $user = Auth::user();
        if($user->group_id == 0) {
            $user->group_id = $group->id;
            $user->belongs_group = $group->id;
        } else {
            $belongs_group = $user->belongs_group;
            $belongs_group = explode(' ', $belongs_group);
            $input = '';
            foreach($belongs_group as $belong_group) {
                $input .= $belong_group. ' '; 
            }
            $input .= $group->id;
            $input = trim($input);
            $user->belongs_group = $input;
        }
        $user->save();

        $invitations = explode(',', $num);
        foreach($invitations as $invitation) {
            if($invitation != 0) {
                $inv_user = User::find($invitation);
                if($inv_user->id != $user->id) {
                    if($inv_user->invitation == 0) {
                        $inv_user->invitation = $group->id;
                        $inv_user->save();
                    } else {
                        $inv_user->invitation .= ' ' .$group->id;
                        $user->invitation = trim($user->invitation);
                        $inv_user->save();
                    }
                }
            }    
        }

        return redirect('/group_show');
    }

    public function show() 
    { 
        $user = Auth::user();
        $groups = [];
        if(isset($user->belongs_group)) {
            $belongs_group = $user->belongs_group;
            $belongs_group = explode(' ', $belongs_group);
            foreach($belongs_group as $belong_group) {
                $groups[] = Group::find($belong_group);
            }
        }
        return view('group/group-show', ['groups' => $groups, 'user' => $user]);
    }

    public function detail($id) 
    {
        $group = Group::find($id);
        $user = Auth::user();
        $array = User::select('id', 'name', 'belongs_group', 'profile_photo_path')->get();
        foreach($array as $value) {
            $belong_group = explode(' ', $value->belongs_group);
            foreach($belong_group as $val) {
                if($val == $id) {
                    $belongsTo_users[] = $value;
                }
            }
        }
        return view('group/group-detail', ['group' => $group, 'belongsTo_users' => $belongsTo_users, 'user' => $user]);
    }

    public function update(Request $request, $id) 
    {
        $request->validate([
            'group_name' => 'required|max:50'
        ],
        [
            'group_name.required' => 'グループ名を入力して下さい',
            'group_name.max' => 'グループ名は最大50文字までです'
        ]);

        $group = Group::find($id);
        $group->group_name = $request->group_name;
        if(isset($request->group_image)) {
            $group->group_image = $request->group_image->store('public/group-image');
            $group->group_image = str_replace('public/group-image/', '', $group->group_image);
        }
        $group->save();
        
        return redirect('/group_show');
    }

    public function destroy($id) 
    {
        $group = Group::find($id);
        $group->delete();      
        
        $inv_users = User::where('invitation', '!=', 0)->get();
        foreach($inv_users as $inv_user) {
            $re_inv = null;
            $inv_nums = explode(' ', $inv_user->invitation);
            foreach($inv_nums as $inv_num) {
                if($inv_num != $id) {
                    $re_inv .= $inv_num . ' ';
                } elseif($inv_num == $id && count($inv_nums) == 1) {
                    $re_inv = 0;
                }
            }
            $re_inv = trim($re_inv);
            $inv_user->invitation = $re_inv;
            $inv_user->save();
        }
        
        $join_users = User::where('belongs_group', '!=', null)->get();
        foreach($join_users as $join_user) {
            $join_user_belongs_groups = $join_user->belongs_group;
            $array = explode(' ', $join_user_belongs_groups);
            $re_group = null;
            foreach($array as $value) {
                if($value != $id) {
                    $re_group .= $value .' ';
                }
            }
            if($re_group == null) {
                $join_user->belongs_group = null;
                $join_user->group_id = 0;
            } else {
                $re_group = trim($re_group);
                $join_user->belongs_group = $re_group;
                if($join_user->group_id == $id) {
                    $re_group = explode(' ', $re_group);
                    foreach($re_group as $val) {
                        $join_user->group_id = $val;
                        break;
                    }
                }
            }
            $join_user->save();
        }

        return redirect('/group_show');
    }

    public function exit($id) 
    {
        $group = Group::find($id);
        $user = Auth::user();
        $belongs_users = User::where('belongs_group', '!=', null)->where('id', '!=', $user->id)->get();
        $count = 0;
        if($group->created_user_id == $user->id) {
            if(isset($belongs_users)) {
                foreach($belongs_users as $belongs_user) {
                    $belongs_group = explode(' ', $belongs_user->belongs_group);
                    foreach($belongs_group as $belong_group) {
                        if($belong_group == $id) {
                            $group->created_user_id = $belongs_user->id;
                            $group->save();
                            break;
                        }
                    }
                    $count++;
                }
            } else {
                $group->delete();
                $inv_users = User::where('invitation', '!=', 0)->get();
                foreach($inv_users as $inv_user) {
                    $re_inv = null;
                    $inv_nums = explode(' ', $inv_user->invitation);
                    foreach($inv_nums as $inv_num) {
                        if($inv_num != $id) {
                            $re_inv .= $inv_num . ' ';
                        } elseif($inv_num == $id && count($inv_nums) == 1) {
                            $re_inv = 0;
                        }
                    }
                    $re_inv = trim($re_inv);
                    $inv_user->invitation = $re_inv;
                    $inv_user->save();
                }
            }

            if($count == count($belongs_users) && $group->created_user_id == $user->id) {
                $group->delete();
                $inv_users = User::where('invitation', '!=', 0)->get();
                foreach($inv_users as $inv_user) {
                    $re_inv = null;
                    $inv_nums = explode(' ', $inv_user->invitation);
                    foreach($inv_nums as $inv_num) {
                        if($inv_num != $id) {
                            $re_inv .= $inv_num . ' ';
                        } elseif($inv_num == $id && count($inv_nums) == 1) {
                            $re_inv = 0;
                        }
                    }
                    $re_inv = trim($re_inv);
                    $inv_user->invitation = $re_inv;
                    $inv_user->save();
                }
            }
        }
            
        $belongs_groups = $user->belongs_group;
        $array = explode(' ', $belongs_groups);
        $re_group = null;
        foreach($array as $value) {
            if($value != $id) {
                $re_group .= $value .' ';
            }
        }          
        if($re_group == null) {
            $user->belongs_group = null;
            $user->group_id = 0;
        } else {
            $re_group = trim($re_group);
            $user->belongs_group = $re_group;
            if($user->group_id == $id) {
                $re_group = explode(' ', $re_group);
                foreach($re_group as $val) {
                    $user->group_id = $val;
                    break;
                }
            }
        }
        $user->save();
        return redirect('/group_show');
    }

    public function destroyMember($user_id, $id) 
    {
        $user = User::find($user_id);
        $belongs_groups = $user->belongs_group;
        $belongs_group = explode(' ', $belongs_groups);
        $key = array_search($id, $belongs_group);
        unset($belongs_group[$key]);
        $input = '';

        if($user->group_id == $id) {
            if(count($belongs_group) == 0) {
                $user->group_id = 0;
                $user->belongs_group = null;
                $user->save();
                return redirect()->route('group_detail', ['id' => $id]);
            } else {
                foreach($belongs_group as $belong_group) {
                    $user->group_id = $belong_group;
                    break;
                }
            }
        }    

        foreach($belongs_group as $belong_group) {
            $input .= $belong_group;
        }
        $user->belongs_group = trim($input);
        $user->save();

        return redirect()->route('group_detail', ['id' => $id]);
    }

    public function invitation($num) 
    {
        $user = Auth::user();
        if(is_null($user->belongs_group)) {
            $count = 0;
        } else {
            $belongs_groups = explode(' ', $user->belongs_group);
            $count = count($belongs_groups);
        }
        $input = '';
        if($num > 0) {
            $belongs_groups[] = $num;
            foreach($belongs_groups as $belongs_group) {
                $input .= $belongs_group. ' ';
            }
            $input = trim($input);
            if($count == 0) {
                $user->group_id = $num;
                $user->belongs_group = $num;
            } else {
                $user->belongs_group = $input;
            }
        }
        $invitations = explode(' ', $user->invitation);
        array_shift($invitations);
        $re_inv = null;
        foreach($invitations as $invitation) {
            $re_inv .= $invitation. ' ';
        }
        $re_inv = trim($re_inv);
        if($re_inv == null) {
            $user->invitation = 0;
        } else {
            $user->invitation = $re_inv;
        }
        $user->save();
        return redirect()->route('calendar', ['year' => date('Y'), 'month' => date('n')]);
    }

    public function select($id) 
    {
        $user = Auth::user();
        $user->group_id = $id;
        $user->save();

        return redirect('group_show');
    }

    public function search($user_name) 
    {
        $get_user = User::where('name', 'like', "%$user_name%")->get();        
        return $get_user;
    }

    public function register(Request $request, $user_id) 
    {
        $user = User::where('id', '=', "$user_id")->first();  
        $user->group_id = $request->group_id;
        $user->save();    
        return $user;
    }

    public function invUser($id, $group_id) 
    {
        $user = User::find($id);
        $invitations = explode(' ', $user->invitation);
        $re_inv = null;
        $key = array_search($group_id, $invitations);
        $belongs_groups = $user->belongs_group;
        $belongs_group = explode(' ', $belongs_groups);
        foreach($belongs_group as $belong_group) {
            if($belong_group == $group_id) {
                $message = 'error';
                return $message;
            }
        }

        foreach($invitations as $invitation) {
            if($user->invitation == 0) {
                $user->invitation = $group_id;
            } else {
                if($key != false) {
                    $message = 'error';
                    return $message;
                }
                $re_inv .= ' ' .$invitation;
            }
        }
        $re_inv .= ' ' .$group_id;
        $user->invitation = trim($re_inv);
        $user->save();

        return $user;
    }
}