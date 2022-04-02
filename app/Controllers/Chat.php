<?php

namespace App\Controllers;
use App\Models\OfferModel;
class Chat extends BaseController
{
    private $db;
    public function __construct(){

        $this->db = new OfferModel;

    }

    public function index()
    {   
        if (!logged_in())
        {
            return redirect()->route('login');
        }
        $offer = $this->db->findAll();
        $finish = $this->db->getFinish();
        return view('pages/chat',["offer" => $offer, "finish" => $finish, "header" => $this->getHeader(["title" => "Lead Offer"])]);
    }
}
