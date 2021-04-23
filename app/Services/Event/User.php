<?php

namespace App\Services\Event;

use Illuminate\Support\Facades\Log;
use LINE\LINEBot\Constant\EventSourceType;
use LINE\LINEBot\MessageBuilder\MultiMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\MessageBuilder\StickerMessageBuilder;

final class User extends AbstractEvent
{
    private $event;

    public function __construct($LINE, $logHandler, $message, $event)
    {
        parent::__construct($LINE, $logHandler, $message);
        $this->logHandler->setSourceType(EventSourceType::USER);
        $this->event = $event;
    }

    /**
     * {@inheritdoc}
     */
    public function eventHandler()
    {
        $messageType = $this->logHandler->addLog($this->event);
        switch ($messageType) {
            // 處理加入好友訊息
            case 'follow':
                $this->replyFollowMessage($this->event);
                break;
            // 處理PostBack
            case 'postBack':
                $this->replyPostbackMessage($this->event);
                break;
            // 處理文字訊息
            case 'text':
                $this->replyTextMessage($this->event);
                break;
            // 處理貼圖訊息
            case 'sticker':
                break;
            // 處理圖片訊息
            case 'image':
                break;
            // 處理音檔訊息
            case 'audio':
                break;
            // 處理影片訊息
            case 'video':
                break;
            default:
                Log::error('LINE Bot webhook handle event error ' . json_encode($this->event));
                break;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    protected function replyFollowMessage($event) :void
    {
        $welcomeText = "歡迎加入中信兄弟非官方帳號，";
        $welcomeText .= "本人因喜愛中信兄弟而製作LINEBot範例，";
        $welcomeText .= "您可以進行以下的範例測試:\n";
        $welcomeText .= "1. 輸入\"圖片\"或\"image\"，即可回傳圖片\n";
        $welcomeText .= "2. 輸入\"影片\"或\"video\"，即可回傳影片\n";
        $welcomeText .= "3. 輸入\"圖文訊息\"或\"imagemap\"，即可回傳圖文訊息\n";
        $welcomeText .= "4. 輸入\"按鈕\"或\"button\"，即可進行按鈕模板測試\n";
        $welcomeText .= "5. 輸入\"確認\"或\"confirm\"，即可進行確認模板測試\n";
        $welcomeText .= "6. 輸入\"輪播\"或\"carousel\"，即可進行輪播模板測試\n";
        $welcomeText .= "7. 輸入\"flex\"，即可回傳Flex訊息\n\n";
        $welcomeText .= "祝您諸事大吉\u{10000B} 萬事如意\u{100078}";

        $replyToken = $event->getReplyToken();
        $welcomeMessage = new MultiMessageBuilder();
        $welcomeMessage->add(new TextMessageBuilder($welcomeText))
                        ->add(new StickerMessageBuilder(1070, 17844));
        $resp = $this->LINE->bot->replyMessage($replyToken, $welcomeMessage);

        if (!empty($resp)) {
            $this->respHandler('follow', $resp);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function replyTextMessage($event) :void
    {
        $replyToken = $event->getReplyToken();
        $requestText = $event->getText();
        $resp = $this->shareMessage($requestText, $replyToken);

        if (!empty($resp)) {
            $this->respHandler('normal', $resp);
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function replyPostbackMessage($event) :void
    {
        $replyToken = $event->getReplyToken();
        $requestData = $event->getPostbackData();
        $resp = $this->shareMessage($requestData, $replyToken);

        if (!empty($resp)) {
            $this->respHandler('postBack', $resp);
        }
    }

    /**
     * 共用訊息
     *
     * @param [type] $request
     * @param [type] $replyToken
     * @return void
     */
    public function shareMessage($request, $replyToken)
    {
        switch ($request) {
            case "圖片":
            case "image":
                $replyImage = $this->message->image();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyImage);
                break;
            case "影片":
            case "video":
                $replyVideo = $this->message->video();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyVideo);
                break;
            case "圖文訊息":
            case "imagemap":
                $replyImageMap = $this->message->imageMap();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyImageMap);
                break;
            case "按鈕":
            case "button":
                $replyButtonsTemplate = $this->message->buttonsTemplate();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyButtonsTemplate);
                break;
            case "確認":
            case "confirm":
                $replyConfirmTemplate = $this->message->confirmTemplate();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyConfirmTemplate);
                break;
            case "輪播":
            case "carousel":
                $replyCarouselTemplate = $this->message->carouselTemplate();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyCarouselTemplate);
                break;
            case "flex":
                $replyCarouselTemplate = $this->message->flex();
                $resp = $this->LINE->bot->replyMessage($replyToken, $replyCarouselTemplate);
                break;
            default:
                $resp = $this->LINE->bot->replyText($replyToken, $request);
                break;
        }

        return $resp;
    }
}
