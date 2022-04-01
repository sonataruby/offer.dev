<?php

namespace App\Controllers;

class Click extends BaseController
{

    public $client_ip = "";
    public function __construct(){
        $data = file_get_contents("http://ip-api.com/json");
    }
    public function index()
    {
        return view('pages/home');
    }
}
