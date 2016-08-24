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

    public function edit(Request $request) {
        return view('home.editor');
    }

    public function edittable(Request $request){
        return view('home.edittable');
    }
}
