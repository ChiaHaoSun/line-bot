<?php

namespace App\Libraries;

use LINE\LINEBot\Event\FollowEvent;
use LINE\LINEBot\Event\JoinEvent;
use LINE\LINEBot\Event\LeaveEvent;
use LINE\LINEBot\Event\PostbackEvent;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\Event\MessageEvent\StickerMessage;
use LINE\LINEBot\Event\MessageEvent\ImageMessage;
use LINE\LINEBot\Event\MessageEvent\AudioMessage;
use LINE\LINEBot\Event\MessageEvent\VideoMessage;

class LogHandler
{

    /**
     * 發出位置
     * @var string
     */
    public $sourceType = 'unknown';

    /**
     * 依照傳入字串加入sourceType參數
     * @param string $sourceType
     */
    public function setSourceType(string $sourceType) :void
    {
        $this->sourceType = $sourceType;
    }

    public function addLog($event) :string
    {
        $messageType = 'unknown';
        switch (true) {
            // 處理加入好友訊息
            case $event instanceof FollowEvent:
                $messageType = 'follow';
                break;
            // 處理PostBack
            case $event instanceof PostbackEvent:
                $messageType = 'postBack';
                break;
            // 處理文字訊息
            case $event instanceof TextMessage:
                $messageType = 'text';
                break;
            // 處理貼圖訊息
            case $event instanceof StickerMessage:
                $messageType = 'sticker';
                break;
            // 處理圖片訊息
            case $event instanceof ImageMessage:
                $messageType = 'image';
                break;
            // 處理音檔訊息
            case $event instanceof AudioMessage:
                $messageType = 'audio';
                break;
            // 處理影片訊息
            case $event instanceof VideoMessage:
                $messageType = 'video';
                break;
            // 處理加入群組訊息
            case $event instanceof JoinEvent:
                $messageType = 'joinGroup';
                break;
            // 處理離開群組訊息
            case $event instanceof LeaveEvent:
                $messageType = 'leaveGroup';
                break;
        }

        return $messageType;
    }
}
