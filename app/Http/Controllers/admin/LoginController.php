<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    // this method wil show admin login page/screen
    public function index(){
        return view('admin.login');
    }
     //This Method will authenticate admin
     public function authenticate(Request $request){
        $validator =Validator::make($request->all(),[
               'email'=>'required|email',
               'password' => 'required'
        ]);
//dd($request);
        if($validator->passes()){

           if(Auth::guard('admin')->attempt(['email'=>$request->email,'password'=>$request->password])){

            if(Auth::guard('admin')->user()->role != 'admin'){

                Auth::guard('admin')->logout();
                return redirect()->route('admin.login')->with('error','You are not authorized to access this page.');
 
            }
               return redirect()->route('admin.dashboard')->with('success','You have login successfully');
               dd('success');
            
           }
           else{

           return redirect()->route('admin.login')
           ->withInput()
           ->with('error','Either email or password is incorrect.');
           }
        }
        else{
           return redirect()->route('admin.login')
           ->withInput()
           ->withErrors($validator);
        }
   }

   public function processRegister(Request $request){
    $validator =Validator::make($request->all(),[
            'name'=>'required',
           'email'=>'required|email|unique:users',
           'password' => 'required|confirmed'
    ]);

    if($validator->passes()){


        $user = new User();
        $user->name=$request->name; 
        $user->email=$request->email; 
        $user->password=Hash::make($request->password);
        $user->role= 'admin';
        $user->save();
        return redirect()->route('admin.login')->with('success','You have registered successfully');
    }
    else{
       return redirect()->route('admin.register')
       ->withInput()
       ->withErrors($validator);
    }
}
    // this method will show register page
    public function register(){
        return view('admin.register');
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
