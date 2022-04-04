<?php

namespace App\Controllers;

class Ipscan extends BaseController
{
    public function index()
    {
        $data = json_decode(file_get_contents("https://raw.githubusercontent.com/jetkai/proxy-list/main/online-proxies/json/proxies-socks4%2B5-beautify.json"));
        $arv = [];
        foreach ($data as $key => $value) {
            foreach ($value as $k => $v) {
                list($ip,$post) = explode(":",$v);
                $dataIP = new \stdClass;
                $dataIP->type = $key;
                $dataIP->ip = $ip;
                $dataIP->port = $post;
                //$dataIP = json_decode(file_get_contents("http://ip-api.com/json/".$ip));
                //if($dataIP->countryCode == "US"){
                    $dataIP->type = $key;
                    $arv[] = $dataIP;
                //}

            }
        }

        return view('pages/ipscan',["ip" => $arv]);
    }
}
