<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Validator;

class AuthController extends Controller
{  
    
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    protected $redirectAfterLogout = '/auth/login';

    protected $redirectTo = '/admin/post';

    /**
     * Create a new authentication controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest', [
            'except' => 'getLogout'
        ]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data            
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);
    }
}
