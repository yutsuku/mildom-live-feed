<?php
declare(strict_types=1);

class DataProvider
{
    public static string $api_endpoint = 'https://cloudac.mildom.com/nonolive/gappserv/live/enterstudio';

    /**
     * @throws \DataProviderException
     */
    public static function get(int $id) : DataProviderResponse
    {
        $query = http_build_query([
            '__platform' => 'web',
            'user_id' => $id
        ]);
        $response = Requests::get(self::$api_endpoint . '?' . $query);

        if ($response->status_code !== 200) {
            throw new \DataProviderException('response status code out of range, got "'.$response->status_code.'"');
        }

        $json = json_decode($response->body);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \DataProviderException('invalid json data');
        }

        $response = new DataProviderResponse();
        $is_live = $json?->body?->live_mode ?? 0;
        $response->is_live = $is_live === 1 ? true : false;
        $response->stream_image = $json?->body?->pic ?? 'https://www.mildom.com/assets/svg/d3afae8f311ca2ba333472a8dd2f185f.svg';
        $response->stream_description = $json?->body?->anchor_intro ?? '';
        $response->author_name = $json?->body?->loginname ?? 'Anonymous';
        $response->channel_url = 'https://www.mildom.com/' . $id;

        return $response;
    }
}
