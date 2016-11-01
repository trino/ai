<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Repositories\TaskRepository;

class HomeController extends Controller {
    public function index(Request $request) {
        return view("home_keyword")->render();
    }

    public function clipi(Request $request) {
        return view("home_clipi")->render();
    }

    public function getjs(Request $request) {
        return view("home_getjs")->render();
    }

    public function tablelist($table) {
        return view("home_list", array("table" => $table))->render();
    }

    public function edituser($user_id = false) {
        if(!$user_id){$user_id = read("id");}
        return view("home_edituser", array("user_id" => $user_id))->render();
    }

    public function edit(Request $request) {
        return view("home_editor")->render();
    }

    public function edittable(Request $request){
        return view("home_edittable")->render();
    }

    public function editmenu(Request $request){
        return view("home_editmenu")->render();
    }

    public function placeorder(){
        if(!read("id")){return array("Status" => false, "Reason" => "You are not logged in");}
        $info = $_POST["info"];
        if(isset($_POST["action"])){
            $ret = array("Status" => true, "Reason" => "");
            switch($_POST["action"]){
                case "closestrestaurant":
                    $ret["closest"] = $this->closestrestaurant($info);
                    break;
                default:
                    $ret["Status"] = false;
                    $ret["Reason"] = "'" . $info["action"] . "' is unhandled";
            }
            return json_encode($ret);
        }
        $addressID = $this->processaddress($info);
        if(isset($_POST["order"])) {
            $restaurant = $this->closestrestaurant($info);
            if(!isset($restaurant["id"])){return false;}
            $info["placed_at"] = now();
            $info["restaurant_id"] = $restaurant["id"];
            if(isset($_POST["stripe"])){$info["stripeToken"] = $_POST["stripe"];}

            $order = $_POST["order"];
            $orderid = insertdb("orders", $info);
            $dir = resource_path("orders");//no / at the end
            if (!is_dir($dir)) {mkdir($dir, 0777, true);}
            file_put_contents($dir . "/" . $orderid . ".json", json_encode($order, JSON_PRETTY_PRINT));
            $user = first("SELECT * FROM users WHERE id = " . $info["user_id"]);
            if($user["name"] != $_POST["name"] || $user["phone"] != $_POST["phone"]){
                $user["name"] = $_POST["name"];
                $user["phone"] = $_POST["phone"];
                insertdb("users", $user);
            }
            $user["orderid"] = $orderid;
            $user["mail_subject"] = "Receipt";
            $text = $this->sendEMail("email_receipt", $user);//send emails to customer and store, also generates the cost
            //if ($text) {return $text;} //shows email errors. Uncomment when email works
            if(isset($info["stripeToken"])){//process stripe payment here
                $amount = select_field_where("orders", "id=" . $orderid, "price");
                if (strpos($amount, ".")) {
                    $amount = $amount * 100;
                }//remove the period, make it in cents

                // Set secret key: remember to change this to live secret key in production
                if(!islive()) {
                    \Stripe\Stripe::setApiKey("BJi8zV1i3D90vmaaBoLKywL84HlstXEg"); //test
                } else {
                    \Stripe\Stripe::setApiKey("3qL9w2o6A0xePqv8C6ufRKbAqkKTDJAW"); //live
                }
                // Create the charge on Stripe's servers - this will charge the user's card
                try {
                    $charge = \Stripe\Charge::create(array(
                        "amount" => $amount,
                        "currency" => "cad",
                        "source" => $info["stripeToken"],
                        "description" => "Order ID: " . $orderid
                    ));
                    insertdb("orders", array("id" => $orderid, "paid" => 1));
                } catch (\Stripe\Error\Card $e) {
                    return false;// The card has been declined
                }
            }
            return '<div CLASS="ordersuccess"></div>' . view("popups_receipt", array("orderid" => $orderid))->render();
        } else {
            return $addressID;
        }
    }

    /*
    function processCC($info){
        //saves credit card info if it's not blank, returns the user info without the credit card info for saving as an order
        $fields = array("cc_fname", "cc_lname", "cc_number", "cc_xyear", "cc_xmonth", "cc_cc");
        $cardnumber = filter_var($info["cc_number"], FILTER_SANITIZE_NUMBER_INT);
        $docard = strlen($cardnumber)>14;
        $ccinfo = array("id" => $info["user_id"], "cc_addressid" => $info["cc_addressid"]);
        foreach($fields as $field){
            $ccinfo = encrypt($info[$field]);
            unset($info[$field]);
        }
        unset($info["cc_addressid"]);
        if($docard){
            insertdb("users", $ccinfo);
        }
        return $info;
    }
    */

    function closestrestaurant($data){
        if(!isset($data['radius'])){$data['radius'] = 100;}
        $owners = implode(",", collapsearray(Query("SELECT address_id FROM restaurants WHERE address_id > 0", true), "address_id"));
        $where = "id IN (" . $owners . ")";

        $SQL = "SELECT *, ( 6371 * acos( cos( radians('" . $data['latitude'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $data['longitude']."') ) + sin( radians('" . $data['latitude']."') ) * sin( radians( latitude ) ) ) ) AS distance FROM useraddresses WHERE $where HAVING distance <= " . $data['radius'] . " ORDER BY distance ASC LIMIT 1";
        return first($SQL);
    }

    function processaddress($info){
        //autosave address changes
        $address = first("SELECT * FROM useraddresses WHERE user_id = " . $info["user_id"] . " AND number = '" . $info["number"] . "' AND street = '" . $info["street"] . "' AND city = '" . $info["city"] . "'");
        if (!$address) {
            $address = array(
                "user_id" => $info["user_id"],
                "number" => $info["number"],
                "city" => $info["city"],
                "unit" => $info["unit"],
                //"buzzcode"      => $info["buzzcode"],
                "street" => $info["street"],
                "postalcode" => $info["postalcode"],
                "province" => $info["province"],
                "latitude" => $info["latitude"],
                "longitude" => $info["longitude"],
            );
            return insertdb("useraddresses", $address);
        } else if ($info["unit"] != $address["unit"]) {
            $address["unit"] = $info["unit"];
            return insertdb("useraddresses", $address);
        }
    }
}
