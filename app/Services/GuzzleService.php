<?php


namespace App\Services;


use GuzzleHttp\Client;

/**
 * Class GuzzleService
 * @package App\Services
 */
class GuzzleService extends Client
{

    /**
     * @param string $method
     * @param string $uri
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getData(string $method, string $uri):array
    {
        $dataJson = [];
        try {
            $dataJson = parent::request($method, $uri);
            return json_decode($dataJson->getBody(), true);
        }catch (\Exception $exception){
            return $dataJson;
        }
    }
}