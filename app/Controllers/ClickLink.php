<?php

namespace App\Controllers;
use App\Models\ClickModel;
use App\Models\OfferModel;
class ClickLink extends BaseController
{

    public $client;
    public $offer;
    public $click;
    public function __construct(){
        $this->client = json_decode(file_get_contents("http://ip-api.com/json"));
        $this->offer = new OfferModel;
        $this->click = new ClickModel;
    }
    public function index($id)
    {
        if (!logged_in())
        {
            return redirect()->route('login');
        }
        $ip = $this->request->getIPAddress();
        $useragent = $this->request->getUserAgent();
        $this->offer->join("offer_api","offer_api.id=api_id","left");
        $offer = $this->offer->find($id);

        
        if($offer->device == "pc" && $useragent->isMobile()){
            die("Device not support");
        }

        if($offer->device == "mobile" && $useragent->isPc()){
            die("Device not support");
        }


        $this->click->where(["offer_id" => $id, "ip" => $ip]);
        $readClick = $this->click->find();
        if($readClick){
            die("Ip Ready Work");
        }


        $arvClick = [
            "ip" => $this->client->query,
            "offer_id" => $id,
            "auth_id" => user_id(),
            "brower" => $useragent->getBrowser(),
            "platform" => $useragent->getPlatform(),
            "useragent" => $useragent->getAgentString(),
            "version" => $useragent->getVersion(),
            "country" => $this->client->country,
            "state" => $this->client->region,
            "zip" => $this->client->zip,
        ];
        $idclick = $this->click->createClick($arvClick);
       

        if($idclick > 0){
            $this->offer->updateClick($id,$offer->click + 1);
        }
        /*
        find Traking Zone
        */
        
        $link = $this->buildLink($offer);
        $link = str_replace("#ID#",$idclick, $link);
        return _go($link);
        exit();
        
    }

    private function buildLink($offer){

        if($offer->name == "sweepstakesbucks"){
            return $this->sweepstakesbucks($offer);
        }

    }

    private function sweepstakesbucks($offer){
        $query = explode('?',$offer->tracking);
        $arv = explode('&',$query[1]);

        $extractZone = explode('|',$offer->zone);

        $v_replace = [];
        foreach ($extractZone as $key => $value) {
            list($k,$v) = explode("=",$arv[$value]);
            $v_replace[] = $v;
        }
        
        //find value link
        $arv = explode('&',parse_url($offer->link)["query"]);
        $g_replace = [];
        foreach ($extractZone as $key => $value) {
            list($k,$v) = explode("=",$arv[$value]);
            $g_replace[] = $v;
        }
        $link = str_replace($v_replace, $g_replace, $offer->tracking);


        return $link;
    }



    /*Set Post back*/

    public function postback($clickid){
        $this->click->join("offer","offer.id=offer_id","left");
        $this->click->join("users","users.id=auth_id","left");
        $data = $this->click->find($clickid);
        if($data){
            $arv = [
                "username" => $data->username,
                "ip" => $data->ip,
                "brower" => $data->brower,
                "ticket" => $clickid,
                "cost" => $data->cost
            ];
            $client = \Config\Services::curlrequest();
            $client->request('post', 'http://localhost:7000/reward', ["json" => (Array)$arv]);
        }
    }
}
