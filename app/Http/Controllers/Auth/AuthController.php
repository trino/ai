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


        public function login() {
            $now = now(true);//seconds from epoch date
            $ret = array("Status" => true);
            if ($_POST["action"] == "logout") {
                foreach(array("id", "name", "email", "phone") as $Key){
                    write($Key, '');
                }
                \Session::save();
            } else {
                $user = first("SELECT * FROM users WHERE email = '" . trim($_POST["email"]) . "'");
                if ($user) {
                    switch ($_POST["action"]) {
                        case "login":
                            if ($user["lastlogin"] >= ($now - 300) && $user["loginattempts"] > 3) {
                                $ret["Status"] = false;
                                $ret["Reason"] = "Too many login attempts";
                            } else if (\Hash::check($_POST["password"], $user["password"])) {
                                unset($user["password"]);//do not send this to the user!
                                $ret["User"] = $user;
                                foreach ($user as $Key => $Value) {
                                    write($Key, $Value);
                                }
                                \Session::save();
                                $ret["Token"] = csrf_token();
                            } else {
                                $ret["Status"] = false;
                                $ret["Reason"] = "Password mismatch";
                                $user["lastlogin"] = $now;
                                $user["loginattempts"]++;
                                insertdb("users", $user);
                            }
                            break;
                        case "forgotpassword":
                            $newpassword = generateRandomString(6);
                            $user["password"] = Hash::make($newpassword);
                            insertdb("users", $user);
                            $ret["Reason"] = "Password reset: " . $newpassword;//send new password to customer instead!!!!
                            break;
                    }
                } else {
                    $ret["Status"] = false;
                    $ret["Reason"] = "Email address not found.";
                }
            }
            die(json_encode($ret));
        }
    }
