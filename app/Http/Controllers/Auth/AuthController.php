<?php
namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {
    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     * @var string
     */
    protected $redirectTo = '/tasks';

    /**
     * Create a new authentication controller instance.
     * @return void
     */
    public function __construct() {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     * @param  array  $data
     * @return User
     */
    protected function create(array $data) {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function gettoken(){
        return csrf_token();
    }

    public function login($action= false, $email = false) {
        $now = now(true);//seconds from epoch date
        if(!$action){$action = $_POST["action"];}
        $ret = array("Status" => true, "Action" => $action);
        if ($action == "logout") {
            foreach(array("id", "name", "email", "phone") as $Key){
                write($Key, '');
            }
            \Session::save();
        } else {
            if(!$email){$email= trim($_POST["email"]);}
            $user = first("SELECT * FROM users WHERE email = '" . $email . "'");
            $passwordmismatch = "Password and email address do not match a known account";
            if ($user) {
                switch ($action) {
                    case "registration":
                        $ret["Status"] = false;
                        $ret["Reason"] = "Email address is in use";
                        break;
                    case "verify":
                        $this->sendverifemail($email);
                    case "login":
                        if ($user["lastlogin"] >= ($now - 300) && $user["loginattempts"] > 5) {
                            $ret["Status"] = false;
                            $ret["Reason"] = "Too many login attempts. Please wait 5 minutes";
                        } else if($user["authcode"]) {
                            $ret["Status"] = false;
                            $ret["Reason"] = 'Email address not verified. Please click the [verify] button in your email';
                        } else if (\Hash::check($_POST["password"], $user["password"])) {
                            unset($user["password"]);//do not send this to the user!
                            $user["Addresses"] = Query("SELECT * FROM useraddresses WHERE user_id = " . $user["id"], true);
                            $ret["User"] = $user;
                            foreach ($user as $Key => $Value) {
                                write($Key, $Value);
                            }
                            \Session::save();
                            $ret["Token"] = csrf_token();
                        } else {
                            $ret["Status"] = false;
                            $ret["Reason"] = $passwordmismatch;//"Password mismatch";
                            $user["lastlogin"] = $now;
                            $user["loginattempts"]++;
                            insertdb("users", $user);
                        }
                        break;
                    case "forgotpassword":
                        $newpassword = generateRandomString(6);
                        $user["password"] = \Hash::make($newpassword);
                        insertdb("users", $user);
                        $ret["Reason"] = "Password reset";
                        $user["password"] = $newpassword;
                        $user["mail_subject"] = "Forgot password";
                        debugprint("Changed password to " . $newpassword);//MUST NOT MAKE IT TO POST-PRODUCTION!!!!
                        $text = $this->sendEMail("email.forgotpassword", $user);
                        if($text){
                            $ret["Status"] = false;
                            $ret["Reason"] = $text;
                        }
                        break;
                }
            } else {
                switch ($action) {
                    case "registration":
                        unset($_POST["action"]);
                        unset($_POST["_token"]);
                        $_POST["remember_token"]="";
                        $_POST["authcode"]=guidv4();
                        $_POST["created_at"] = now();
                        $_POST["updated_at"] = 0;
                        $_POST["password"] = \Hash::make($_POST["password"]);
                        insertdb("users", $_POST);
                        $this->sendverifemail($_POST["email"]);
                        break;
                    default:
                        $ret["Status"] = false;
                        $ret["Reason"] = $passwordmismatch;//"Email address not found."
                }
            }
        }
        die(json_encode($ret));
    }

    function sendverifemail($email){
        $user = first("SELECT * FROM users WHERE email = '" . $email . "'");
        $user["mail_subject"] = "Please click the verify button";
        $text = $this->sendEMail("email.verify", $user);
        if($text){
            $ret["Status"] = false;
            $ret["Reason"] = $text;
        } else {
            $ret["Status"] = true;
            $ret["Reason"] = "Please click the Verify button in your email";
        }
        die(json_encode($ret));
    }
}
