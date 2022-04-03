<?php

namespace App\Controllers;
use App\Models\ClickModel;
use App\Models\OfferModel;
use App\Models\OfferFinishModel;
class Clickoffer extends BaseController
{

    public $client;
    public $offer;
    public $click;
    public $offerfinish;
    public function __construct(){
        
        $this->offer = new OfferModel;
        $this->click = new ClickModel;
        $this->offerfinish = new OfferFinishModel;
    }

    private function get_ip_address()
    {
        foreach (array('HTTP_CLIENT_IP',
                       'HTTP_X_FORWARDED_FOR',
                       'HTTP_X_FORWARDED',
                       'HTTP_X_CLUSTER_CLIENT_IP',
                       'HTTP_FORWARDED_FOR',
                       'HTTP_FORWARDED',
                       'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $IPaddress){
                    $IPaddress = trim($IPaddress); // Just to be safe

                    if (filter_var($IPaddress,
                                   FILTER_VALIDATE_IP,
                                   FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
                        !== false) {

                        return $IPaddress;
                    }
                }
            }
        }
    }
    public function index($id, $auth_id)
    {
        
        $ip = $this->get_ip_address();
        $this->client = json_decode(file_get_contents("http://ip-api.com/json/".$ip));
        $useragent = $this->request->getUserAgent();
        $this->offer->join("offer_api","offer_api.id=api_id","left");
        $offer = $this->offer->find($id);

        
        if($offer->device == "pc" && $useragent->isMobile()){
            die("Device not support");
        }



        $this->click->where(["offer_id" => $id, "ip" => $ip]);
        $readClick = $this->click->find();
        if($readClick){
            die("Ip Ready Work");
        }

        if(strtolower($this->client->countryCode) != strtolower($offer->country)){
            die("Country not support");
        }

        $arvClick = [
            "ip" => $this->client->query,
            "offer_id" => $id,
            "auth_id" => $auth_id,
            "brower" => $useragent->getBrowser(),
            "platform" => $useragent->getPlatform(),
            "useragent" => $useragent->getAgentString(),
            "version" => $useragent->getVersion(),
            "country" => $this->client->countryCode,
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


    public function test(){
        $this->offer->setFinish(3);
    }

    /*Set Post back*/

    public function postback($offer_id,$clickid){
        $this->click->join("offer","offer.id=offer_id","left");
        $this->click->join("users","users.id=auth_id","left");
        $data = $this->click->find($clickid);
        if($data){
            
            $arv = $this->offerfinish->getFinishByClick($clickid);
            $this->offer->setFinish($clickid);

            $client = \Config\Services::curlrequest();
            $client->request('post', 'http://localhost:7000/reward', ["json" => (Array)$arv]);
        }
        die("ok");
    }



    public function tranffic($id){
        $data = $this->offer->getTranfficInfo();
        
        exit();
    }
    public function tranfficapi(){
        $data = $this->offer->getTranffic();
        $arv = [];
        foreach ($data as $key => $value) {
            $arv[] = base_url("tranfic-".$value->id.".html");
        }
        echo implode($arv, "\n");
        exit();
    }
}
