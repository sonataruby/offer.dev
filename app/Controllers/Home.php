<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        return view('pages/home',["header" => $this->getHeader(["title" => "Offer Program"])]);
    }
}
