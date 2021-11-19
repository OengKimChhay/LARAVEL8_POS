<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Models\Auth\User;
use File;
use Hash;

class UserController extends Controller
{

    public function Register(Request $req){
        $validate = $req->validate([
            'name'=>['required','string'],
            'email'=>['required','email','unique:users,email'],
            'password' => ['required','same:re_password', Password::min(8)],
            're_password'=>['required',Password::min(8)],
            'image' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'], //dimensions:max_width=500,max_height=500
            'phone' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','numeric','digits_between:9,10'],
            'userType'=>['required']
        ],[
            'phone.regex' =>'The phone can not be string.',
            'phone.numeric' =>'The phone can not contain backspace or tap'
        ]);

        // check file
        if($req->hasFile('image')){
            $image = $req->file('image');
            $desitate_path = 'images/users';
            $imageName = time().'.'.$req->image->getClientOriginalName(); // to get image name and convert to number;            $img_resize->move($desitate_path,$imageName); //to store image
            $image->move($desitate_path,$imageName);
        }

        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = Hash::make($req->password);
        $user->image = $imageName;
        $user->phone = $req->phone;
        $user->userType = $req->userType;
        $user->save();
        if($user){
            return  response()->json([
                'status'=>'success',
                'message'=>'A new User has been added'
            ]);
        }else{
            return  response()->json([
                'status'=>'fail',
                'message'=>'Can not add this user'
            ]);
        }
    }

    public function GetUserData(Request $req){
        
        $user = User::orderBy('id','ASC')->paginate(10);
        return response()->json($user);
    }
    // update
    public function Update(Request $req,$id){
        $user = User::find($id);
        $validate = $req->validate([
            'new_name'=>['required','string'],
            'new_email'=>['required','unique:users,email,'.$user->id],
            'old_password' => [
                                'required', function ($attribute, $value, $fail) use ($user){
                                                if (!Hash::check($value , $user->password)){
                                                    return $fail('The Old password didn\'t match');
                                                }
                                            },
                                 Password::min(8)
                              ],
            'new_password'=>['required','different:old_password', Password::min(8)],
            // 'new_image' => ['required','image','mimes:jpeg,png,jpg,gif,svg','max:2048'], //dimensions:max_width=500,max_height=500
            'new_userType'=>['required'],
            'new_phone' => ['required','regex:/^([0-9\s\-\+\(\)]*)$/','numeric','digits_between:9,10'],
        ],[
            'new_phone.regex' =>'The phone can not be string.',
            'new_phone.numeric' =>'The phone can not contain backspace or tap'
        ]);

        // delete old image
        if($req->hasFile('new_image')){
            // to delete old image before update
            $image_path = public_path("\images\users\\").$user->image;
            if(File::exists($image_path)){
                File::delete($image_path);
                //one way: unlink($image_path);
            }
            $image = $req->file('new_image');
            $desitate_path = 'images/users';
            $NewimageName = time().'.'.$req->new_image->getClientOriginalName(); // to get image name and convert to number;
            $image->move($desitate_path,$NewimageName); //to store image
        }else{
            $NewimageName = $user->image;  //if we dont use img validate the input file can be blank so the default image is old image
        }
        $user->name = $req->new_name;
        $user->email = $req->new_email;
        $user->password = Hash::make($req->new_password);
        $user->userType = $req->new_userType;
        $user->image = $NewimageName;
        $user->phone= $req->new_phone;
        $user->save();
        if($user){
            return response()->json([
                'status'=>'success',
                'message'=>'User has been Updated!'
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Cant Updated!'
            ]);
        }
    }

    // delete
    public function Delete(Request $req,$id){
        $user = User::find($id);
        // delete old image
        if($user){
            $image_path = public_path("\images\users\\").$user->image;
            if(File::exists($image_path)){
                File::delete($image_path);
                //one way: unlink($image_path);
            }
            $user->delete();
            if($user){
                return  response()->json([
                    'status'=>'success',
                    'message'=>'A user has been deleted'
                ]);
            }else{
                return response()->json([
                    'status'=>'fail',
                    'message'=>'Can delete this person!'
                ]);
            }
        }
    }
    // login
    // NOTE::::: in this Login method to generate token we use laravel sanctum
    public function Login(Request $req){
        $validate = $req->validate([
            'user'=>['required'],
            'pass' => ['required'],
        ],[
            'user.required' => 'The Username field is required.',
            'pass.required' => 'The Password field is required.'
        ]);

        $checkUser = User::where('email',$req->user)
                         ->orWhere('phone',$req->user)
                         ->first();
        if($checkUser && Hash::check($req->pass, $checkUser->password)){//we must put the first param as the request from client if we use Hash
            
            $token = $checkUser->createToken('authentication')->plainTextToken; //this line is create token
            return response()->json([
                'status' =>'success',
                'message'=>'Login success',
                'token'  => $token,
                'user'   => [
                    'id'      =>$checkUser->id,
                    'name'    =>$checkUser->name,
                    'userType'=>$checkUser->userType,
                    'image'   =>$checkUser->image
                ],
            ]);
        }else{
            return response()->json([
                'status'=>'fail',
                'message'=>'Invalid Username or Password',
            ]);
        }
    }
    public function Logout(Request $req){
        // Revoke the token that was used to authenticate the current request...
        $req->user()->currentAccessToken()->delete();
        return response()->json([
            'status'=>'success',
            'message'=>'User Logge out'
        ]);
    }
}
