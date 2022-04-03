<?php

namespace App\Controllers;
use App\Models\OfferModel;
use App\Models\ChatModel;
use App\Models\OfferFinishModel;

class Chat extends BaseController
{
    private $db;
    private $chat;
    public function __construct(){

        $this->db = new OfferModel;
        $this->chat = new ChatModel;
        $this->offer_finish = new OfferFinishModel;
    }

    public function index()
    {   
        if (!logged_in())
        {
            return redirect()->route('login');
        }
        $offer = $this->db->findAll();
        $finish = $this->offer_finish->getFinish();
        $chat = $this->chat->getMessages();
        return view('pages/chat',["offer" => $offer, "finish" => $finish, "chat" => $chat, "header" => $this->getHeader(["title" => "Lead Offer"])]);
    }

    public function savechat(){
       $data = (Object)$this->request->getPost("chat");
       $this->chat->setMessages($data->id, $data->username, $data->msg);
    }
}
