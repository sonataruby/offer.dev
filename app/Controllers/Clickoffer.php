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
        $db = db_connect();

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
            die("Ip ".$ip." Ready Work");
        }

        $readIPFinish = $db->query("SELECT * FROM offer_finish WHERE ip='".$ip."' LIMIT 1")->getRow();
        if($readIPFinish){
            die("Ip ".$ip." Ready Work Finish");
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
            $this->offer->updateClick($offer->id);
        }
        /*
        find Traking Zone
        */
        $link = $offer->link."&s1=".$idclick;
        //$link = $this->buildLink($offer);
        //$link = str_replace("#ID#",$idclick, $link);
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

    public function postback($token){

        if($token != "smarttoken") die("error token");

        $offer_id = $this->request->getGet("offer");
        $clickid = $this->request->getGet("task");
        $ip = $this->request->getGet("ip");

        $this->click->join("offer","offer.id=offer_id","left");
        $this->click->join("users","users.id=auth_id","left");
        $data = $this->click->find($clickid);
        if($data){
            
            $arv = $this->offerfinish->getFinishByClick($clickid);
            if($this->offer->setFinish($clickid)){
                $client = \Config\Services::curlrequest();
                $client->request('post', 'http://localhost:7000/reward', ["json" => (Array)$arv]);
            }

            
        }
        die("ok");
    }



    public function tranffic($id){
        $data = $this->offer->getTranfficInfo($id);
        $ip = $this->get_ip_address();
        $this->client = json_decode(file_get_contents("http://ip-api.com/json/".$ip));

        if(strtolower($this->client->countryCode) != strtolower($data->country)){
            print_r($data);
            die("Country not support : ".$ip);
        }else{
            $link = $data->link."&s1=".$data->click_id;
            $this->offer->updateTranfficInfo($id, ["runnumber" => $data->runnumber +1]);
            return _go($link);
        }
        exit();
    }
    public function tranfficapi(){
        $data = $this->offer->getTranffic();
        $arv = [];
        foreach ($data as $key => $value) {
            if($value->number > $value->runnumber){
                $arv[] = base_url(random_string('alnum', 16)."-".$value->id."-tranffic.html");
            }
            
        }
        echo implode($arv, ";");
        exit();
    }

    public function movetoweb(){
         $url = $this->request->getGet("url");
        $data = json_decode(file_get_contents("https://raw.githubusercontent.com/jetkai/proxy-list/main/online-proxies/json/proxies-socks4%2B5-beautify.json"));
        $arv = [];
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                $this->cronclick($value, $url);
                sleep(3);
            }
        }
    }
    public function cronclick($proxy = "", $url=""){
        
        
       
        
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        if($proxy != "") curl_setopt($ch, CURLOPT_PROXY, $proxy);
        curl_setopt($ch, CURLOPT_HEADER, 0); // return headers 0 no 1 yes
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return page 1:yes
        curl_setopt($ch, CURLOPT_TIMEOUT, 200); // http request timeout 20 seconds
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects, need this if the url changes
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2); //if http server gives redirection responce
        curl_setopt($ch, CURLOPT_USERAGENT,
            "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.7) Gecko/20070914 Firefox/2.0.0.7");
        curl_setopt($ch, CURLOPT_COOKIEJAR, "cookies.txt"); // cookies storage / here the changes have been made
        curl_setopt($ch, CURLOPT_COOKIEFILE, "cookies.txt");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // false for https
        curl_setopt($ch, CURLOPT_ENCODING, "gzip"); // the page encoding

        $data = curl_exec($ch); // execute the http request
        curl_close($ch); // close the connection
        print_r($data);

    }
}
