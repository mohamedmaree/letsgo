<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Category;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(){
        $this->data['site_logo'] = setting('site_logo');
        $this->data['categories'] = Category::orderBy('id','asc')->get();
        return view('auth.register', $this->data);
    }
    

    public function userRegister(Request $request){
       $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|email|unique:users',
            'phone' => 'required|string|max:255||min:5|unique:users',
            'password' => 'required|alpha_dash|between:6,50',
        ]);

        $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'type'  => 'user',
                'password' => Hash::make($request->password),
                'role'     =>'0',
                'active'   => '1'
            ]);
        Auth::login($user);
        return redirect('home');            
    }

    public function ProviderRegister(Request $request){
        $this->validate(request(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|email|unique:users',
            'phone' => 'required|string|max:255||min:5|unique:users',
            'address'     => 'required|min:10',
            'lat'     => 'required',
            'long'     => 'required',
            'category_id' => 'required|integer',
            'password' => 'required|alpha_dash|between:6,50',
        ]);
    
        $user = new User();
        $user->name   = $request->name;
        $user->email  = $request->email;
        $user->phone  = $request->phone;
        $user->address = $request->address;
        $user->lat     =  $request->lat;
        $user->long    =  $request->long;
        $user->type    =  'provider';
        $user->category_id = $request->category_id;
        $user->password    =  Hash::make($request->password);
        $user->role        ='0';
        $user->active      =  '1';
        $user->save();
        if($category = Category::find($request->category_id)){
            $category->num_items = $category->num_items + 1;
            $category->save();
            $user->number = $category->num_items;
            $user->save();
        }
        Auth::login($user);
        return redirect('profile');           

    }


}
