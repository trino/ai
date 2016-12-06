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

    public function getjs(Request $request) {
        return view("home_getjs")->render();
    }

    public function tablelist($table) {
        if(isset($_POST["action"])){
            switch($_POST["action"]){
                case "testemail":
                    return $this->sendEMail("email_test", array(
                        'mail_subject' => "test",
                        "email" => "roy@trinoweb.com"
                    ));
                    break;
            }
        }
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
                    $ret["closest"] = $this->closestrestaurant($info, true);
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
            var_dump($restaurant);

            if(!isset($restaurant["id"])){return false;}
            $info["placed_at"] = now();
            $info["restaurant_id"] = $restaurant["id"];
            unset($info["name"]);
            unset($info["creditcard"]);
            if(isset($_POST["stripe"])){$info["stripeToken"] = $_POST["stripe"];}

            $order = $_POST["order"];
            unset($info["istest"]);
            $orderid = insertdb("orders", $info);
            $dir = resource_path("orders");//no / at the end
            if (!is_dir($dir)) {mkdir($dir, 0777, true);}
            file_put_contents($dir . "/" . $orderid . ".json", json_encode($order, JSON_PRETTY_PRINT));
            $user = first("SELECT * FROM users WHERE id = " . $info["user_id"]);

            if($user["name"] != $_POST["name"] || $user["phone"] != $_POST["phone"]){
                if(!isset($user["id"])) {
                    $user["id"] = $info["user_id"];
                }
                $user["name"] = $_POST["name"];
                $user["phone"] = $_POST["phone"];
                insertdb("users", array("id" => $user["id"], "name" => $_POST["name"], "phone" => $_POST["phone"]));//attempt to update user profile
            }

            $user["orderid"] = $orderid;
            $user["mail_subject"] = "Receipt";
            $text = $this->sendEMail("email_receipt", $user);//send emails to customer also generates the cost

            $user["mail_subject"] = "A new order was placed";
            $user["email"] = $restaurant["user"]["email"];
            $this->sendEMail("email_receipt", $user);//send emails to store
            $this->sendSMS($restaurant["user"]["phone"], $user["mail_subject"]);//send text to the store

            //if ($text) {return $text;} //shows email errors. Uncomment when email works
            if(isset($info["stripeToken"]) || $user["stripecustid"]){//process stripe payment here
                $amount = select_field_where("orders", "id=" . $orderid, "price");
                if (strpos($amount, ".")) {$amount = $amount * 100;}//remove the period, make it in cents
                $error = false;
                if($amount > 0) {
                    initStripe();
                    try {
                        if($user["stripecustid"]){
                            $customer_id = $user["stripecustid"];//load customer ID from user profile
                            if(isset($info["stripeToken"]) && $info["stripeToken"]){//update credit card info
                                $cu = \Stripe\Customer::retrieve($customer_id);
                                $cu->source = $info['stripeToken']; //obtained with Checkout
                                $cu->save();
                            }
                        } else {
                            $customer = \Stripe\Customer::create(array(
                                "source" => $info["stripeToken"],
                                "description" => $user["name"] . ' (ID:' . $user["id"] . ')'
                            ));
                            $customer_id = $customer["id"];
                            insertdb("users", array("id" => $user["id"], "stripecustid" => $customer_id));//attempt to update user profile
                        }

                        $charge = array(
                            "amount" => $amount,
                            "currency" => "cad",
                            //"source" => $info["stripeToken"],//charge card directly
                            "customer" => $customer_id,//charge customer ID
                            "description" => "Order ID: " . $orderid
                        );
                        if(isset($_POST["creditcard"]) && $_POST["creditcard"]){
                            $charge["source"] = $_POST["creditcard"];//charge a specific credit card
                            //$charge["card"] = $_POST["creditcard"];//charge a specific credit card
                        }

                        $charge = \Stripe\Charge::create($charge);// Create the charge on Stripe's servers - this will charge the user's card
                        insertdb("orders", array("id" => $orderid, "paid" => 1));//will only happen if the $charge succeeds

                    } catch (Stripe_CardError $e) {
                        $error = $e->getMessage();
                    } catch (Stripe_InvalidRequestError $e) {
                        $error = $e->getMessage();//Invalid parameters were supplied to Stripe's API
                    } catch (Stripe_AuthenticationError $e) {
                        $error = $e->getMessage();//Authentication with Stripe's API failed
                    } catch (Stripe_ApiConnectionError $e) {
                        $error = $e->getMessage();//Network communication with Stripe failed
                    } catch (Stripe_Error $e) {
                        $error = $e->getMessage();//Display a very generic error to the user
                    } catch (Exception $e) {
                        $error = $e->getMessage();//Something else happened, completely unrelated to Stripe
                    } catch (\Stripe\Error\Card $e) {
                        $error = $e->getMessage();
                    }
                } else {
                    $error = "Order total was $0.00";
                }
                if($error){
                    debugprint("Order ID: " .  $orderid . " - Stripe error: " . $error);
                    return $error;// The card has been declined
                }
            }
            return '<div CLASS="ordersuccess"></div>' . view("popups_receipt", array("orderid" => $orderid))->render();
        } else {
            return $addressID;
        }
    }

    function closestrestaurant($data, $gethours = false){
        if(!isset($data['radius'])){$data['radius'] = 100;}
        $owners = implode(",", collapsearray(Query("SELECT address_id FROM restaurants WHERE address_id > 0", true), "address_id"));
        $where = "id IN (" . $owners . ")";
        $SQL = "SELECT *, ( 6371 * acos( cos( radians('" . $data['latitude'] . "') ) * cos( radians( latitude ) ) * cos( radians( longitude ) - radians('" . $data['longitude']."') ) + sin( radians('" . $data['latitude']."') ) * sin( radians( latitude ) ) ) ) AS distance FROM useraddresses WHERE $where HAVING distance <= " . $data['radius'] . " ORDER BY distance ASC LIMIT 1";
        $Restaurant = first($SQL);//useraddresses
        if($Restaurant && $gethours){
            $Restaurant["hours"] = gethours($Restaurant["id"]);
            $Restaurant["restaurant"] = first("SELECT * FROM restaurants WHERE address_id = " . $Restaurant["id"]);
            $Restaurant["user"] = first("SELECT * FROM users WHERE id = " . $Restaurant["user_id"]);
        }
        return $Restaurant;
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
