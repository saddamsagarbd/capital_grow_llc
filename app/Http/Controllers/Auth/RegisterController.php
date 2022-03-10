<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Mail\SendMail;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Events\CreateUserProfile;
use App\Events\CreateAccount;

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
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('auth.login');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name'        => 'required|string|max:255',
            'last_name'         => 'required|string|max:255',
            'email'             => 'required|email|unique:users',
            'username'          => 'required',
            'contact_number'    => 'required',
            'password'          => 'required|string|min:8|confirmed',
            'gender'            => 'required',
            'identification_no' => 'required',
            'dob'               => 'required',
            'img'               => 'required'
        ]);
    }

    public function sendEmail($data)
    {
        // send company account details via Mail
        $details = [
            'title' => "Payment Accounts List",
            'body' => "Bkash: 01994282802(personal)",
            'full_name' => $data["first_name"]." ".$data["last_name"],
        ];

        $subject = "Accounts List";
        $data['email'] = "saddamsagar02@gmail.com";
        $data['full_name'] = $data["first_name"]." ".$data["last_name"];

        Mail::to($data['email'])->send(new SendMail($details, $subject));
        // return Mail::send('emails.test', ['user' => $data], function ($m) use ($data) {
        //     $m->from('hello@app.com', 'Your Application');
 
        //     $m->to($data['email'], $data['full_name'])->subject('Your Reminder!');
        // });
        // return mail(
        //     $data['email'],
        //     $subject,
        //     "test",
        // );
        return true;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data, $filename = "")
    {
        // $test = $this->sendEmail($data);
        
        // echo "<pre>";
        // var_dump($test);
        // echo "</pre>";
        // exit;

        $data['user_id'] = mt_rand(100000,999999).strtotime(now());
        $data['filename'] = $filename;

        $userData = [
            'user_id'       => $data['user_id'],
            'username'      => $data['username'],
            'email'         => $data['email'],
            'password'      => Hash::make($data['password'])
        ];

        $user = User::create($userData);

        event(new CreateUserProfile((object) $data));

        event(new CreateAccount((object) $data));

        // Auth::login($user, true);
        $this->sendEmail($data);
        
        return $user;
    }
}
