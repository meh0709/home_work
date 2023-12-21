<?php

namespace App\Services\TurboSms;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

class TurboSmsService
{
    /**
     * @var string
     */
    protected string $url;

    /**
     * @var string
     */
    protected string $token;

    /**
     * @var Client
     */
    protected Client $client;

    protected const URL = 'https://api.turbosms.ua/message/send.json';

    public function __construct(Client $client)
    {

        $this->url = self::URL;
        $this->token = env('TURBOSMS_TOKEN');;
        $this->client = $client;
    }

    /**
     * @param $phone
     * @param $message
     * @return string
     * @throws GuzzleException
     */
    public function sendSms($phone, $message): string
    {

        try {
            $res = $this->client->post($this->url, [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                    'Authorization' => "Basic ". $this->token
                ],
                'form_params' => $this->params($phone, $message)
            ]);

            return $res->getBody()->getContents();
        }catch (RequestException $requestException){
            return $requestException->getMessage();
        }


    }

    /**
     * @param $phone
     * @param $message
     * @return array
     */
    public function params($phone, $message) : array
    {
        return [
            'recipients' => [$phone],
            'sms' => ['sender' => 'WeeDelivery', 'text' => $message]
        ];
    }

}
