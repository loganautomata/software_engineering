<?php

namespace app\business;

class CURL
{
    function get($url, $header = array("Content-type:application/json;", "Accept:application/json"))
    {
        $ch = curl_init($url);
        $options = array(
            CURLOPT_URL => $url,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $header,
        );

        curl_setopt_array($ch, $options);
        $data = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($data, true);

        return $data;
    }
}
