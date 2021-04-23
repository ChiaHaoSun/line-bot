<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Libraries\LINE;
use App\Libraries\LogHandler;
use App\Services\Event\User;
use App\Services\Message;
use Illuminate\Http\Request;
use LINE\LINEBot\Constant\HTTPHeader;

class LINEController extends Controller
{
    /**
     * LINE Bot's Library
     *
     * @var \App\Libraries\LINE
     */
    private $LINE;

    /**
     * request message log handler
     *
     * @var \App\Libraries\LogHandler
     */
    private $logHandler;

    /**
     * LINE message handler
     *
     * @var \App\Services\Message
     */
    private $message;

    public function __construct(LogHandler $logHandler, Message $message)
    {
        $this->LINE = new LINE(config('services.line.bot.channel_access_token'), config('services.line.bot.channel_secret'));
        $this->logHandler = $logHandler;
        $this->message = $message;
    }

    /**
     * LINE Message API webHook
     * @param \Illuminate\Http\Request $request
     */
    public function webHook(Request $request)
    {
        $signature = $request->headers->get(HTTPHeader::LINE_SIGNATURE);
        if (empty($signature)) {
            return response('Bad Requeest', 400);
        }
        $body = $request->getContent();
        // Check request with signature and parse request
        try {
            $events = $this->LINE->bot->parseEventRequest($body, $signature);
        } catch (InvalidSignatureException $e) {
            return response('Invalid signature', 400);
        } catch (InvalidEventRequestException $e) {
            return response('Invalid event request', 400);
        }
        info($events);
        // 處理reply訊息
        foreach ($events as $event) {
            switch (true) {
                case $event->isUserEvent():
                    $result = (new User($this->LINE, $this->logHandler, $this->message, $event))->eventHandler();
                    break;
            }
        }
    }
}
