<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Task;
use App\Repositories\TaskRepository;

class HomeController extends Controller {
    public function index(Request $request) {
        return view('home.keyword');
    }

    public function clipi(Request $request) {
        return view('home.clipi');
    }

    public function getjs(Request $request) {
        return view('home.getjs');
    }

    public function tablelist($table) {
        return view('home.list', array("table" => $table));
    }

    public function edituser($user_id = false) {
        if(!$user_id){$user_id = read("id");}
        return view('home.edituser', array("user_id" => $user_id));
    }

    public function edit(Request $request) {
        return view('home.editor');
    }

    public function edittable(Request $request){
        return view('home.edittable');
    }

    public function editmenu(Request $request){
        return view('home.editmenu');
    }

    public function placeorder(){
        if(!read("id")){return array("Status" => false, "Reason" => "You are not logged in");}
        $info = $_POST["info"];
        $addressID = $this->processaddress($info);
        if(isset($_POST["order"])) {
            $info["placed_at"] = now();
            $order = $_POST["order"];
            $orderid = insertdb("orders", $info);
            $dir = resource_path("orders");//no / at the end
            if (!is_dir($dir)) {mkdir($dir, 0777, true);}
            file_put_contents($dir . "/" . $orderid . ".json", json_encode($order, JSON_PRETTY_PRINT));
            $user = first("SELECT * FROM users WHERE id = " . $info["user_id"]);
            $user["orderid"] = $orderid;
            $user["mail_subject"] = "Receipt";
            $text = $this->sendEMail("email.receipt", $user);//send emails to customer and store
            if ($text) {return $text;}
            return view("popups.receipt", array("orderid" => $orderid));
        } else {
            return $addressID;
        }
    }

    function processaddress($info){
        //autosave address changes
        $address = first("SELECT * FROM useraddresses WHERE user_id = " . $info["user_id"] . " AND number = '" . $info["number"] . "' AND street = '" . $info["street"] . "' AND city = '" . $info["city"] . "'");
        if(!$address){
            $address = array(
                "user_id"       => $info["user_id"],
                "number"        => $info["number"],
                "city"          => $info["city"],
                "unit"          => $info["unit"],
                //"buzzcode"      => $info["buzzcode"],
                "street"        => $info["street"],
                "postalcode"    => $info["postalcode"],
                "province"      => $info["province"],
                "latitude"      => $info["latitude"],
                "longitude"     => $info["longitude"],
                "phone"         => $info["cell"]
            );
            return insertdb("useraddresses", $address);
        } else if($info["unit"] != $address["unit"]){
            $address["unit"] = $info["unit"];
            return insertdb("useraddresses", $address);
        }
    }
}
