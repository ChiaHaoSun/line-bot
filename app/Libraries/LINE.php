<?php

namespace App\Libraries;

use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot;

class LINE
{
    public $httpClient, $bot;

    public function __construct($channelAccessToken, $channelSecret)
    {
        $this->httpClient = new CurlHTTPClient($channelAccessToken);
        $this->bot = new LINEBot($this->httpClient, ['channelSecret' => $channelSecret]);
    }
}
