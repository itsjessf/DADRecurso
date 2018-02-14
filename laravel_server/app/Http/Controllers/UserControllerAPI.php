<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Contracts\Support\Jsonable;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\User;
use Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserConfirmation;
use App\Mail\BlockAccount;
use App\Mail\ReactivateAccount;



class UserControllerAPI extends Controller
{
    public function getUsers(Request $request)
    {
        if ($request->has('page')) {
            return UserResource::collection(User::paginate(5)->where("admin",0));
        } else {
            return UserResource::collection(User::all()->where("admin",0));
        }
    }

    public function getUser($id)
    {
        return new UserResource(User::find($id));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'nickname' => 'required|unique:users',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:3|confirmed',
            'password_confirmation' => 'required|min:3'
        ]);
        $user = new User();
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->email = $request->email;
        $user->blocked = 1;
        $user->password = bcrypt($request->password);
        $user->save();
        Mail::to($user->email)->send(new UserConfirmation($user));
        return response()->json(new UserResource($user), 201);
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if($user->isAdmin()){
            return response()->json(
                ['msg'=>'Administrator cannot be edited'], 401);
        }

        if(!Hash::check($request->password_old, $user->password)){
            return response()->json(['password'=>'Wrong password'], 401); 
        }

        if(isset($request->password)){
            $user->password = Hash::make($request->password);     
        }

        $user->email = $request->email;
        $user->name = $request->name;
        $user->nickname = $request->nickname;
        $user->save();
        return response()->json(
            ['msg'=>'Sucess'], 200);
    }

    public function updateAdmin(UpdateAdminRequest $request, $id)
    {
        $user = User::findOrFail($id);
        if(!$user->isAdmin()){
            return response()->json(
                ['msg'=>'You are not administrator'], 401);
        }

        if(!Hash::check($request->password_old, $user->password)){
            return response()->json(['password'=>'Wrong password'], 401); 
        }

        if(isset($request->password)){
            $user->password = Hash::make($request->password);     
        }
        $user->email = $request->email;
        $user->save();
        return response()->json(
            ['msg'=>'Sucess'], 200);
    }

    public function delete($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return response()->json(null, 204);
    }
    public function emailAvailable(Request $request)
    {
        $totalEmail = 1;
        if ($request->has('email') && $request->has('id')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->where('id', '<>', $request->id)->count();
        } else if ($request->has('email')) {
            $totalEmail = DB::table('users')->where('email', '=', $request->email)->count();
        }
        return response()->json($totalEmail == 0);
    }

    public function toggleBlockUser($id)
    {
        $user = User::findOrFail($id);
        if($user->blocked == 1){
            $user->blocked = 0;
            $user->save();
            return response()->json(new UserResource($user), 201);
        }
        $user->blocked = 1;
        $user->save();
        return response()->json(new UserResource($user), 201);
    }

    public function storeReasonBlock(Request $request, $id){
        $user = User::findOrFail($id);
        if($user->blocked == 0){
         $user->reason_blocked = $request->reason_blocked; 
         $user->save();
         Mail::to($user->email)->send(new BlockAccount($user));
         return response()->json(new UserResource($user), 201);
     }
     $user->reason_reactivated = $request->reason_reactivated;
     $user->save();
     Mail::to($user->email)->send(new ReactivateAccount($user));
     return response()->json(new UserResource($user), 201);
 }


}

