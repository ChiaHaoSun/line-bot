<?php

namespace App\Services;

use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\ImagemapActionBuilder\AreaBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapMessageActionBuilder;
use LINE\LINEBot\ImagemapActionBuilder\ImagemapUriActionBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\ImageMessageBuilder;
use LINE\LINEBot\MessageBuilder\TemplateMessageBuilder;
use LINE\LINEBot\MessageBuilder\VideoMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\VideoBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ButtonTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselColumnTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\CarouselTemplateBuilder;
use LINE\LINEBot\MessageBuilder\TemplateBuilder\ConfirmTemplateBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;

final class Message
{
    /**
     * 圖片訊息
     * @return ImageMessageBuilder
     */
    public function image() :ImageMessageBuilder
    {
        $imageUrl = url("/images/1.jpg"); //圖片大小不得超過 1 MB
        return new ImageMessageBuilder($imageUrl, $imageUrl);
    }

    /**
     * 影片訊息
     * @return VideoMessageBuilder
     */
    public function video() :VideoMessageBuilder
    {
        $videoUrl = url("/videos/video.mp4"); //影片大小不得超過 25 MB
        $imageUrl = url("/images/videoPreview.jpg"); //預覽圖
        return new VideoMessageBuilder($videoUrl, $imageUrl);
    }

    /**
     * 圖文訊息
     * @return ImagemapMessageBuilder
     */
    public function imageMap() :ImagemapMessageBuilder
    {
        $videoUrl = url("/videos/video.mp4");
        $imageUrl = url("/images/videoPreview.jpg"); //預覽圖
        return new ImagemapMessageBuilder(
            url("/images/imageMap.jpg?/1040"), //網址後面要加width(如:240px, 300px, 460px, 700px, 1040px)
            "\u{100042}中信兄弟棒球員圖文訊息\u{100005}",
            new BaseSizeBuilder(702, 1040),
            [
                new ImagemapUriActionBuilder(
                    'http://twbsball.dils.tku.edu.tw/wiki/index.php/彭政閔',
                    new AreaBuilder(0, 0, 520, 351)
                ),
                new ImagemapUriActionBuilder(
                    'http://twbsball.dils.tku.edu.tw/wiki/index.php/周思齊',
                    new AreaBuilder(521, 0, 520, 351)
                ),
                new ImagemapMessageActionBuilder(
                    '王威晨',
                    new AreaBuilder(0, 352, 520, 351)
                ),
                new ImagemapMessageActionBuilder(
                    '江坤宇',
                    new AreaBuilder(521, 352, 520, 351)
                )
            ],
            null,
            new VideoBuilder($videoUrl, $imageUrl, new AreaBuilder(0, 702, 1040, 702))
        );
    }

    /**
     * 按鈕模板 Buttons Template
     * @return TemplateMessageBuilder
     */
    public function buttonsTemplate() :TemplateMessageBuilder
    {
        $imageUrl = url("/images/1.jpg");
        return new TemplateMessageBuilder(
            '按鈕模板',
            new ButtonTemplateBuilder(
                '按鈕模板',
                '請進行按鈕模板測試',
                $imageUrl,
                [
                    new MessageTemplateActionBuilder(
                        '彭政閔',
                        '彭政閔'
                    ),
                    new UriTemplateActionBuilder(
                        '彭政閔 - 台灣棒球維基館',
                        'http://twbsball.dils.tku.edu.tw/wiki/index.php/%E5%BD%AD%E6%94%BF%E9%96%94'
                    ),
                    new PostbackTemplateActionBuilder(
                        '彭政閔季後賽三響砲',
                        '影片'
                    )
                ]
            )
        );
    }

    /**
     * 確認模板 Buttons Template
     * @return TemplateMessageBuilder
     */
    public function confirmTemplate() :TemplateMessageBuilder
    {
        return new TemplateMessageBuilder(
            '確認模板',
            new ConfirmTemplateBuilder(
                '請進行確認模板測試',
                [
                    new MessageTemplateActionBuilder(
                        '是',
                        '是'
                    ),
                    new MessageTemplateActionBuilder(
                        '否',
                        '否'
                    )
                ]
            )
        );
    }

    /**
     * 輪播模板 Carousel Template
     * @return TemplateMessageBuilder
     */
    public function carouselTemplate() :TemplateMessageBuilder
    {
        $imageUrl_1 = url("/images/1.jpg");
        $imageUrl_2 = url("/images/2.jpg");

        return new TemplateMessageBuilder('輪播模板',
            new CarouselTemplateBuilder(
                [
                    new CarouselColumnTemplateBuilder(
                        '輪播模板1',
                        '請進行輪播模板測試1',
                        $imageUrl_1,
                        [
                            new PostbackTemplateActionBuilder(
                                '傳送圖片',
                                '圖片'
                            ),
                            new PostbackTemplateActionBuilder(
                                '傳送影片',
                                '影片'
                            ),
                        ]
                    ),
                    new CarouselColumnTemplateBuilder(
                        '輪播模板2',
                        '請進行輪播模板測試2',
                        $imageUrl_2,
                        [
                            new PostbackTemplateActionBuilder(
                                '按鈕模板',
                                '按鈕'
                            ),
                            new PostbackTemplateActionBuilder(
                                '確認模板',
                                '確認'
                            ),
                        ]
                    ),
                ]
            )
        );
    }

    /**
     * Flex 訊息
     * @return FlexMessageBuilder
     */
    public function flex() :FlexMessageBuilder
    {
        return new FlexMessageBuilder(
            '彭政閔',
            BubbleContainerBuilder::builder()
                ->setHero(
                    ImageComponentBuilder::builder()
                        ->setUrl('https://img.hobbitfei.com/uploads/20190930014257_58.jpg')
                        ->setSize(ComponentImageSize::FULL)
                        ->setAspectMode(ComponentImageAspectMode::COVER)
                        ->setAction(
                            new UriTemplateActionBuilder(
                                null,
                                'http://twbsball.dils.tku.edu.tw/wiki/index.php/%E5%BD%AD%E6%94%BF%E9%96%94'
                            )
                        )
                )
                ->setBody(
                    BoxComponentBuilder::builder()
                        ->setLayout(ComponentLayout::VERTICAL)
                        ->setContents([
                            TextComponentBuilder::builder()
                                ->setText('彭政閔')
                                ->setWeight(ComponentFontWeight::BOLD)
                                ->setSize(ComponentFontSize::XL),
                            BoxComponentBuilder::builder()
                                ->setLayout(ComponentLayout::VERTICAL)
                                ->setSpacing(ComponentSpacing::SM)
                                ->setMargin(ComponentMargin::LG)
                                ->setContents([
                                    BoxComponentBuilder::builder()
                                        ->setLayout(ComponentLayout::BASELINE)
                                        ->setSpacing(ComponentSpacing::SM)
                                        ->setContents([
                                            TextComponentBuilder::builder()
                                                ->setText('綽號')
                                                ->setWeight(ComponentFontWeight::BOLD)
                                                ->setSize(ComponentFontSize::SM)
                                                ->setColor("#AAAAAA")
                                                ->setFlex(1),
                                            TextComponentBuilder::builder()
                                                ->setText('恰恰、火星人、中職先生')
                                                ->setWeight(ComponentFontWeight::BOLD)
                                                ->setSize(ComponentFontSize::SM)
                                                ->setColor("#666666")
                                                ->setFlex(5)
                                                ->setWrap(true)
                                        ]),
                                    BoxComponentBuilder::builder()
                                        ->setLayout(ComponentLayout::BASELINE)
                                        ->setSpacing(ComponentSpacing::SM)
                                        ->setContents([
                                            TextComponentBuilder::builder()
                                                ->setText('簡介')
                                                ->setWeight(ComponentFontWeight::BOLD)
                                                ->setSize(ComponentFontSize::SM)
                                                ->setColor("#AAAAAA")
                                                ->setFlex(1),
                                            TextComponentBuilder::builder()
                                                ->setText('台灣職業棒球選手，是台灣棒壇代表性的技巧型全方位右打者，亦是中信兄弟隊與前身兄弟象隊隊史中難以取代的高人氣明星選手，主要守備位置為一壘手與外野手。')
                                                ->setWeight(ComponentFontWeight::BOLD)
                                                ->setSize(ComponentFontSize::SM)
                                                ->setColor("#666666")
                                                ->setFlex(5)
                                                ->setWrap(true)
                                        ])
                                ])
                        ])
                )
                ->setFooter(
                    BoxComponentBuilder::builder()
                        ->setLayout(ComponentLayout::VERTICAL)
                        ->setSpacing(ComponentSpacing::SM)
                        ->setContents([
                            ButtonComponentBuilder::builder()
                                ->setAction(
                                    new UriTemplateActionBuilder(
                                        '詳細資料',
                                        'http://twbsball.dils.tku.edu.tw/wiki/index.php/%E5%BD%AD%E6%94%BF%E9%96%94'
                                    )
                                )
                        ])
                )
        );
    }
}
