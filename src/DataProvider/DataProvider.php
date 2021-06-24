<?php
declare(strict_types=1);

namespace DataProvider;

use DataProvider\Response\Response;
use DataProvider\Response\VideoLink;
use DataProvider\Response\VideoLinks;

class DataProvider
{
    public static string $api_endpoint = 'https://cloudac.mildom.com/nonolive/gappserv/channel/liveEndRecoV4';

    /**
     * @throws \DataProviderException
     */
    public static function get(int $id): Response
    {
        $query = http_build_query([
            '__platform' => 'web',
            'room_id' => $id
        ]);
        $response = \Requests::get(self::$api_endpoint . '?' . $query);

        if ($response->status_code !== 200) {
            throw new DataProviderException('response status code out of range, got "' . $response->status_code . '"');
        }

        $json = json_decode($response->body);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new DataProviderException('invalid json data');
        }

        $response = new Response();
        $is_live = $json?->body?->live_mode ?? 0;
        $response->is_live = $is_live === 1 ? true : false;
        $response->stream_image = $json?->body?->video_list[0]?->video_pic ?? 'https://www.mildom.com/assets/svg/d3afae8f311ca2ba333472a8dd2f185f.svg';
        $response->stream_description = $json?->body?->video_list[0]?->title ?? '';
        $response->author_name = $json?->body?->video_list[0]?->author_info?->login_name ?? 'Anonymous';
        $response->stream_url = sprintf('https://www.mildom.com/playback/%s/%s', $id, $json?->body?->video_list[0]?->v_id ?? '');
        $response->videos = new VideoLinks();

        $response->stream_publish_date = $json?->body?->video_list[0]?->publish_time ?? null;
        if ($response->stream_publish_date) {
            $response->stream_publish_date = $response->stream_publish_date / 1000;
        } else {
            $response->stream_publish_date = time();
        }

        foreach ($json?->body?->video_list[0]?->video_link as $video_link) {
            $link = new VideoLink();
            $link->definition = $video_link?->definition ?? 'unknown resolution';
            $link->url = $video_link?->url ?? '';

            if (strlen($link->url) > 0) {
                $response->videos->add($link);
            }
        }

        $response->channel_url = 'https://www.mildom.com/' . $id;
        $response->raw = $json;

        return $response;
    }
}
