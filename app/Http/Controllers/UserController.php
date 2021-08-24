<?php

namespace App\Http\Controllers;

use App\User;
use App\College;
use Auth;
use App\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\Rule;
// use App\Group;
use App\Mail\agent;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $users = User::paginate(15);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('users.create');
        // return view('status.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'email' => 'unique:users,email|required|max:191|string|email',
            'name' => 'required|max:191|string',
            'password' => 'required|between:6,50|string',
            ]);
     
           $user = new \App\User();
     
           $user->email = $request->email;
           $user->name = $request->name;
           $user->password = Hash::make($request->password);
     
           $user->save();
              return redirect('/users')->with('success', 'User has been created');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
    public function show(Users $status)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param    \Illuminate\Http\Request  $request
     * @param    int  $id
     * @return  \Illuminate\Http\Response
     */
     public function edit($id)
     {
       $user = Auth::user();

         $user = \App\User::findOrfail($id);
         $userGroups = $user->group;
         $groups = \App\Group::all();
         $roles = Role::all()->pluck('name');
         $userRoles = $user->roles;
         $permissions = Permission::all()->pluck('name');
         $userPermissions = $user->permissions;

         $colleges = \App\College::all();
         return view('users.edit', compact('user', 'roles', 'userRoles', 'permissions', 'userPermissions','groups', 'colleges', 'userGroups'));
  
     }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\users  $users
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
      {

   //   $this->validate($request, [
   //   'email' => [
   //   'required',
   //   Rule::unique('users')->ignore($request->user_id),
   //    'email',
   //    'max:191',
   //    'string',
   //    'regex:/^[A-Za-z0-9\.]*@(ksau-hs)[.](edu)[.](sa)$/',
   // ],
   //   'name' => 'required|max:191|string',
   //   'password' => 'nullable|between:6,20|string',
   //   ]);
       $user = \App\User::findOrfail($request->user_id);

       $user->email = $request->email;
       $user->name = $request->name;
       $user->badge_number = $request->badge_number;
       $user->student_number = $request->student_number;
       $user->batch = $request->batch_number;
       $user->college_id = $request->college_id;

       if(!empty($request->input('password')))
     {
         $user->password = Hash::make($request->password);
     }


       $user->save();

       //\Mail::to($user)->send(new agent);

       return redirect('/users')->with('success', 'user has been updated');
   }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Users  $users
     * @return \Illuminate\Http\Response
     */
     public function destroy($id)
     {

       // $users = Status::findOrfail($id);
       // $status->delete();
       // return redirect('/status')->with('success', 'status has been deleted');
     }

 //     public function addUserGroup(Request $request)
 //     {
 //       $user = User::findorfail($request->user_id);
 //       $user->group()->syncWithoutDetaching($request->group_id);
 //       return back();
 //     }
 //
 //     public function removeUserGroup($group_id, $user_id)
 // {
 //     $user = User::findorfail($user_id);
 //
 //     $user->group()->detach($group_id);
 //
 //     return back();
 // }

    public function addUserGroup(Request $request)
    {
    $user = User::findorfail($request->user_id);
    $user->group()->syncWithoutDetaching($request->group_id);
    return back();
    }

    public function removeUserGroup($group_id, $user_id)
    {
    $user = User::findorfail($user_id);

    $user->group()->detach($group_id);

    return back();
    }

     /**
     * Assign Role to user.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function addRole(Request $request)
    {
        $users = User::findOrfail($request->user_id);
        $roles = Role::all()->pluck('name');
        $userRoles = $users->roles;
        $users->assignRole($request->role_name);

        return back();
    }

    /**
     * revoke Role from a user.
     *
     * @param \Illuminate\Http\Request
     *
     * @return \Illuminate\Http\Response
     */
    public function revokeRole($role, $user_id)
    {
        $users = \App\User::findorfail($user_id);

        $users->removeRole(str_slug($role, ' '));

        return back();
    }


/**
* Assign Permission to user.
*
* @param \Illuminate\Http\Request
*
* @return \Illuminate\Http\Response
*/
public function addPermission(Request $request)
{
   $users = User::findOrfail($request->user_id);
   $permissions = Permission::all()->pluck('name');
   $userPermissions = $users->permissions;
   $users->givePermissionTo($request->permission_name);

   return back();
}

/**
* revoke Permission from a user.
*
* @param \Illuminate\Http\Request
*
* @return \Illuminate\Http\Response
*/
public function revokePermission($permission, $user_id)
{
   $users = \App\User::findorfail($user_id);

   $users->revokePermissionTo(str_slug($permission, ' '));

   return back();
}
}
