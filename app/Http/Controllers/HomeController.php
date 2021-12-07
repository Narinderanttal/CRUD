<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $users  = Auth::user();
        $role = $users->role;
        if(Auth::user())
        {
            $a = 1;
            $user = User::where('role', 'user')->get();
            return view('home',['a'=>$a, 'users'=>$user, 'role'=>$role]);
        }
    }

    public function adduser(Request $request, $id=NULL)
    {
        if(empty($request->all()))
        {
            $user = User::where('role', 'user')->where('id',$id)->first();
            return view('adduser',['id'=>$id, 'user'=>$user]);
        }
        else
        {
            $validator = Validator::make($request->all(), [     
                'firstname' => ['required', 'string', 'max:255'],
                'lastname' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]); 
            if($validator->fails())
            {
                   // $errormessage = $validator->errors();
                 return redirect('add-user')
                  ->withErrors($validator)
                  ->withInput();
            }
            else
            {
                if($id>0)
                {
                    $firstname = $request->input('firstname');
                    $lastname = $request->input('lastname');
                    $email =$request->input('email');
                    $password = Hash::make($request->input('password'));
                    $users = User::find($id);               
                    $users->firstname = $firstname;
                    $users->lastname = $lastname;
                    $users->email = $email;
                    $users->password = $password;
                    $users->save();   
                    if($users)
                    {
                        return redirect('home');
                    }
                }
                else{
                    $firstname = $request->input('firstname');
                    $lastname = $request->input('lastname');
                    $email =$request->input('email');
                    $password = Hash::make($request->input('password'));
                    $users = new User;               
                    $users->firstname = $firstname;
                    $users->lastname = $lastname;
                    $users->email = $email;
                    $users->password = $password;
                    $users->role ='user';
                    $users->save();   
                    if($users)
                    {
                        return redirect('home');
                    }
                }
            }
        }
    }


    public function uploadfile(Request $request, $id)
    {
        $request->validate([
            "file" => "required"
        ]);

        if ($request->hasFile('file')) 
        {
            $removeimg = User::where('id',$id)->first();
            if ($removeimg->profile_image != '') {
               $image_path = public_path().'/storage/file/'.$removeimg->profile_image;
                unlink($image_path);
            }
            

            $file = $request->file('file');
            $name = time().'.'.$file->getClientOriginalExtension();
            $destinationPath = public_path('/storage/file');
            $file->move($destinationPath, $name);

            $addfile = User::find($id);
            $addfile->profile_image = $name;
            $addfile->save(); 
        }
       
        return response()->json('File uploaded successfully');
    }


    public function deleteuser(Request $request, $id)
    {
       $removeimg = User::where('id',$id)->first();
        if ($removeimg->profile_image != '') {
           $image_path = public_path().'/storage/file/'.$removeimg->profile_image;
            unlink($image_path);
        }
        $delete = User::where('id',$id)->delete();
        return redirect('home');   
    }
    
}
