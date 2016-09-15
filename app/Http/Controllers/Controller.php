<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    //sends an email using a template
    public function sendEMail($template_name = "", $array = array()) {
        if(isset($array["message"])){
            $array["body"] = $array["message"];
            unset($array["message"]);
        }
        if(isset($array['email']) && is_array($array['email'])){
            $emails = $array['email'];
            foreach($emails as $email){
                $array["email"] = $email;
                $this->sendEMail($template_name, $array);
            }
        } else if($array['email']) {
            try {
                \Mail::send($template_name, $array, function ($messages) use ($array, $template_name) {
                    $messages->to($array['email'])->subject($array['mail_subject']);
                });
            } catch (\Swift_TransportException $e) {
                $text = $e->getMessage();
                debugprint($template_name . " EMAIL TO " . $array['email'] . " FAILED: " . $text);
                return $text;
            }
        }
    }
}
