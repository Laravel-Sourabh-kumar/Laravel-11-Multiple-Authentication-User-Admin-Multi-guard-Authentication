<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class LoginController extends Controller
{
    //this metjpd wll show login page for customer

    public function index(){
        return view('login');
    }
    //This Method will authenticate user
    public function authenticate(Request $request){
         $validator =Validator::make($request->all(),[
                'email'=>'required|email',
                'password' => 'required'
         ]);

         if($validator->passes()){

            if(Auth::attempt(['email'=>$request->email,'password'=>$request->password])){
                return redirect()->route('account.dashboard')->with('success','You have login successfully');
  
            }
            else{

            return redirect()->route('account.login')
            ->withInput()
            ->with('error','Either email or password is incorrect.');
            }
         }
         else{
            return redirect()->route('account.login')
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
            $user->role= 'customer';
            $user->save();
            return redirect()->route('account.login')->with('success','You have registered successfully');
        }
        else{
           return redirect()->route('account.register')
           ->withInput()
           ->withErrors($validator);
        }
   }
    // this method will show register page
   public function register(){
    return view('register');
   }

   public function logout(){
     Auth::logout();
     return redirect()->route('account.login');
   }
}
