<?php

namespace App\Services\API;

use GuzzleHttp\Client;

final class Guzzle
{
    protected $client;
    protected $headers;
    protected $token;

    public function __construct()
    {
        $this->client = new Client();
        $this->token = config('services.line.bot.channel_access_token');
        $this->headers = ['Authorization' => 'Bearer ' . $this->token];
    }

    /**
     * 設定使用者預設的圖文選單
     *
     * @param 使用者ID $userId
     * @param 圖文選單ID $menuId
     * @return void
     */
    public function setRichMenu($userId, $menuId)
    {
        $this->client->post(
            'https://api.line.me/v2/bot/user/' . $userId . '/richmenu/richmenu-' . $menuId,
            [
                'headers' => $this->headers
            ]
        );
        info('LINE Bot set default RichMenu ' . $menuId . ' to ' . $userId);

        return true;
    }

    /**
     * 取消使用者預設的圖文選單
     *
     * @param 使用者ID $userId
     * @return void
     */
    public function cancelRichMenu($userId)
    {
        $this->client->delete(
            'https://api.line.me/v2/bot/user/' . $userId . '/richmenu',
            [
                'headers' => $this->headers
            ]
        );
        info('LINE Bot cancel default RichMenu from ' . $userId);

        return true;
    }
}
