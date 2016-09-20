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
        $info = $_POST["info"];
        $info["placed_at"] = now();

        $order = $_POST["order"];

        $OrderID = insertdb("orders", $info);
        $dir = resource_path("orders");//no / at the end
        if(!is_dir($dir)){
            mkdir($dir, 0777, true);
        }

        file_put_contents($dir . "/" . $OrderID . ".json", json_encode($order, JSON_PRETTY_PRINT));

        $user = first("SELECT * FROM users WHERE id = " . read("id"));
        $user["orderid"] = $OrderID;
        $user["mail_subject"] = "Receipt";
        $text = $this->sendEMail("email.receipt", $user);
        //send emails to customer and store

        return $OrderID;
    }
}
