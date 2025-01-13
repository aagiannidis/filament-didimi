<?php
// filepath: /C:/www/filament-tickets-demo-site/app/helpers.php

if (!function_exists('getJsonData')) {
    function getJsonData($filename): array
    {
        $json = file_get_contents($filename);
        return json_decode($json, true);
    }
}

if (!function_exists('getJsonDataFromUrl')) {
    function getJsonDataFromUrl($url): array
    {
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
}
if (!function_exists('getJsonDataFromApi')) {
    function getJsonDataFromApi($url): array
    {
        $json = file_get_contents($url);
        return json_decode($json, true);
    }
}

if (!function_exists('getJsonDataFromApiWithToken')) {
    function getJsonDataFromApiWithToken($url, $token): array
    {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "Authorization: Bearer " . $token
            ]
        ];
        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        return json_decode($json, true);
    }
}

if (!function_exists('getJsonDataFromApiWithTokenAndPost')) {
    function getJsonDataFromApiWithTokenAndPost($url, $token, $data): array
    {
        $opts = [
            "http" => [
                "method" => "POST",
                "header" => "Authorization: Bearer " . $token . "\r\n" .
                            "Content-Type: application/json\r\n",
                "content" => json_encode($data)
            ]
        ];
        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        return json_decode($json, true);
    }
}

if (!function_exists('getJsonDataFromApiWithTokenAndPut')) {
    function getJsonDataFromApiWithTokenAndPut($url, $token, $data): array
    {
        $opts = [
            "http" => [
                "method" => "PUT",
                "header" => "Authorization: Bearer " . $token . "\r\n" .
                            "Content-Type: application/json\r\n",
                "content" => json_encode($data)
            ]
        ];
        $context = stream_context_create($opts);
        $json = file_get_contents($url, false, $context);
        return json_decode($json, true);
    }
}

