<?php

namespace App\Services\Event;

use App\Libraries\LINE;
use App\Libraries\LogHandler;
use App\Services\Message;
use LINE\LINEBot\Response;

abstract class AbstractEvent
{

    /**
     * LINE Bot's Library
     *
     * @var \App\Libraries\LINE
     */
    protected $LINE;

    /**
     * request message log handler
     *
     * @var \App\Libraries\LogHandler
     */
    protected $logHandler;

    /**
     * LINE message handler
     *
     * @var \App\Services\Message
     */
    protected $message;

    /**
     * LINE Bot Response
     *
     * @var \LINE\LINEBot\Response
     */
    public $response;

    public function __construct(
        LINE $LINE,
        LogHandler $logHandler,
        Message $message
    )
    {
        $this->LINE = $LINE;
        $this->logHandler = $logHandler;
        $this->message = $message;
    }

    /**
     * 處理event
     * @return mixed
     */
    abstract public function eventHandler();

    /**
     * 加入好友時並post相對應的template
     * @param \LINE\LINEBot\Event\FollowEvent $event
     * @return void
     */
    abstract protected function replyFollowMessage($event) :void;

    /**
     * 抓取文字訊息並post相對應的template
     * @param \LINE\LINEBot\Event\MessageEvent\TextMessage $event
     * @return void
     */
    abstract protected function replyTextMessage($event) :void;

    /**
     * 抓取post back訊息並post相對應的template
     * @param \LINE\LINEBot\Event\PostbackEvent $event
     * @return void
     */
    abstract protected function replyPostbackMessage($event) :void;


    /**
     * @param string $type
     * @param \LINE\LINEBot\Response $resp
     * @return void
     */
    protected function respHandler(string $type, Response $resp) :void
    {
        $this->response = $resp;
        if (config('app.env') !== 'production') {
            info('LINE Bot reply ' . $type . ' message ' . $resp->getHTTPStatus() . ': ' . $resp->getRawBody());
        }
    }
}
