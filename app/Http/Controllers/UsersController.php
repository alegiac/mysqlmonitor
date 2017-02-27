<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\SpeedxUser;
use Auth;
use App\Classes\Helpers;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    protected $app_helper;
    protected $request;
    protected $db;
    private $salt;
    public function __construct()
    {

        $this->db = app('db');
        $this->salt= 'MysqlTuner%66(--?';
    }

    public function login(Request $request){

        $params = [];
        session_start();

        if(isset($_SESSION["login"]) && $_SESSION["login"] === true){
            return redirect(env('SUB_FOLDER').'/dashboard');
        }



        $_SESSION["login"] = false;

        if ($request->has('email') && $request->has('password')) {
            $user = SpeedxUser:: where("email", "=", $request->input('email'))
                ->where("password", "=", sha1($this->salt.$request->input('password')))
                ->first();
            if ($user) {

                $_SESSION["login"] = true;

                return redirect(env('SUB_FOLDER').'/dashboard');
            } else {
                return Helpers::getView(__FUNCTION__,get_class($this),$params);
            }
        } else {
            return Helpers::getView(__FUNCTION__,get_class($this),$params);
        }

    }

    public function settings(Request $request){
        $params = array();

        $user =   $user = SpeedxUser:: find(1);
        $params['user'] = $user;


        if ($request->has('email')){
            $user->email = $request['email'];
            $params['success'] = 'Your settings are updated successfully';
            $user->save();
        }

        if ($request->  has('password')){
            $user->password = sha1($this->salt.$request->input('password'));
            $params['success'] = 'Your settings are updated successfully';
            $user->save();
        }

        return Helpers::getView(__FUNCTION__,get_class($this),$params);
    }

    public function logout(){
        session_start();
        unset($_SESSION["login"]);
        session_destroy();
        return redirect(env('SUB_FOLDER').'/login');
    }

}
