<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 7/12/17
 * Time: 11:54 AM
 */

namespace CalvilloComMx\Core\Social;


use Carbon\Carbon;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookPageService
{

    private $facebookPageId;

    public function __construct(LaravelFacebookSdk $fb)
    {
        $this->fb = $fb;
        $this->facebookPageId = env('FACEBOOK_PAGE_ID');
    }

    public function init()
    {
        if(!$socialToken = \Auth::user()->socialToken) {
            throw new \Exception('No facebook token!');
        }

        $this->fb->setDefaultAccessToken($socialToken->facebook_access_token);
        $res = $this->fb->get('/me/accounts/'.$this->facebookPageId);
        $token = $res->getGraphEdge()->getField(0)->getField('access_token');
        $this->fb->setDefaultAccessToken($token);
    }


    public function postPhoto($message, $url, $pictureUrl, Carbon $publish_time = null)
    {
        $requestData = [
            'message'=>"$message \n$url",
            'url' => $pictureUrl,
        ];

        if ($publish_time) {
            $requestData['scheduled_publish_time']  = $publish_time->timestamp;
            $requestData['published'] = false;
        }

        $res = $this->fb->post("/$this->facebookPageId/photos/", $requestData);

        return $res->getDecodedBody()['id'];
    }

    public function postLink($message, $url, Carbon $publish_time)
    {
        $requestData = [
            'message'=>"$message",
            'link' => $url,
        ];

        logger((new Carbon($publish_time)));
        logger((new Carbon($publish_time))->timestamp);

        if ($publish_time) {
            $requestData['scheduled_publish_time'] = $publish_time->timestamp;
            $requestData['published'] = false;
        }


        $res = $this->fb->post("/$this->facebookPageId/feed/", $requestData);

        return $res->getDecodedBody()['id'];
    }

}